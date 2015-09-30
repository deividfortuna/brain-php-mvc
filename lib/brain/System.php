<?php
namespace Brain;

/**
 * Executa o controller e a action da aplicação apropriada.
 *
 * A identificação é baseada na url acessada.
 * Também é responsável pelo autoload das classes do sistema e das aplicações.
 *
 * @author  Deivid Fortuna <deividfortuna@gmail.com>, Felipe Toreti <toreti@gmail.com>
 * @version 30/09/2015
 * @package Brain
 */
class System
{
    /**
     * @var string Nome da action atual.
     */
    private $action;

    /**
     * @var string Nome do controller atual.
     */
    private $controller;

    /**
     * @var System Instancia da classe.
     */
    private static $instance;

    /**
     * @var string[] Array contendo as partes da url acessada.
     */
    private $url = array();

    /**
     * @var string Url da aplicação atual.
     */
    public function __construct()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * Carrega o arquivo das classes do sistema e das aplicações conforme são instanciadas.
     *
     * @param string $class
     */
    private function autoload($class)
    {
        $class    = ltrim($class, '\\');
        $fileName = '';
        if ($lastNsPos = strrpos($class, '\\')) {
            $namespace = strtolower(substr($class, 0, $lastNsPos));
            $class     = substr($class, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $class).'.php';
        $dir = (strpos($fileName, 'app' . DIRECTORY_SEPARATOR) === false) ? DIR_ROOT .'lib'.DIRECTORY_SEPARATOR : DIR_ROOT;
        require_once $dir.$fileName;
    }

    /**
     * Converte a string informada para o formato CamelCase caso necessário.
     *
     * @param string $string
     * @param bool   $lowerCaseFirst
     * @return string
     */
    private function camelize($string, $lowerCaseFirst = true)
    {
        if (strpos($string, '-') !== false) {
            $string = preg_replace("/([_-\\s]?([a-z0-9]+))/e", "ucwords('\\2')", strtolower($string));
            $string = $lowerCaseFirst ? lcfirst($string) : $string;
        } else if(! $lowerCaseFirst) {
            $string = ucfirst($string);
        }
        return $string;
    }

    /**
     * Retorna o nome da action atual.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }


    /**
     * Retorna o nome do controller atual.
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Retorna a url acessada em forma de array.
     *
     * @param int $key
     * @return array
     */
    public function getUrl($key = null)
    {
        return is_null($key) ? $this->url : $this->url[$key];
    }


    /**
     * Retorna a instancia da classe.
     *
     * @return System
     */
    public static function instance()
    {
        if (!isset(self::$instance)) {
            $c              = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    /**
     * Acorda!
     *
     * Inicia o sistema, identificando a aplicação que deve ser utilizada e executando a action do controller.
     * Se for a index da aplicação principal, realiza o minimo de operações possível para ganhar desempenho.
     */
    public function wake()
    {
        if (!isset($_GET['url'])) {

            // Index da aplicação principal
            $this->controller = 'Home';
            $this->action     = 'index';
        } else {
            // Outras actions e aplicações
            $this->url    = explode('/', rtrim(filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL), '/'));
            // Outras actions da aplicação principal
            $this->controller = $this->camelize($this->url[0], false);
            $this->action     = isset($this->url[1]) ? $this->camelize($this->url[1], true) : 'index';
        }

        // Monta o nome do controller e verifica se o arquivo existe
        $controller         = '\\App\\Controller\\'.$this->controller;
        $controller_path    = 'app' . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $this->controller . '.php';
        $file               = DIR_ROOT . $controller_path;

        if (!file_exists($file)) {

            // Se o controller não for encontrado é chamada a action notFound
            $this->controller = 'Home';
            $this->action     = 'notFound';
            $controller       = '\\App\\Controller\\Home';
        }

        $View = new View();
        // Instancia o controller e executa a action
        $Controller = new $controller($View);
        $action     = method_exists($Controller, $this->action) ? $this->action : 'notFound';
        $Controller->System = $this;
        $Controller->$action();
        $Controller->beforeView();
        if ($Controller->debug) {
            var_dump($Controller);
        }
        if ($Controller->autoRender) {
            $Controller->view();
        }
        $Controller->afterView();
    }
}
