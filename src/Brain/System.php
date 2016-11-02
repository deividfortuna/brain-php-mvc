<?php
namespace Brain;
use DI\ContainerBuilder;

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
     * Converte a string informada para o formato CamelCase caso necessário.
     *
     * @param string $string
     * @param bool $lowerCaseFirst
     * @return string
     */
    private function camelize($string, $lowerCaseFirst = true)
    {
        if (strpos($string, '-') !== false) {
            $string = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
            if (!$lowerCaseFirst) {
                $string = lcfirst($string);
            }
        } else if (!$lowerCaseFirst) {
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
            $c = __CLASS__;
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
        $container = ContainerBuilder::buildDevContainer();

        if (!isset($_GET['url'])) {

            // Index da aplicação principal
            $this->controller = 'Main';
            $this->action = 'index';
        } else {
            // Outras actions e aplicações
            $this->url = explode('/', rtrim(filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL), '/'));
            // Outras actions da aplicação principal
            $this->controller = $this->camelize($this->url[0], false);
            $this->action = isset($this->url[1]) ? $this->camelize($this->url[1], true) : 'index';
        }

        // Monta o nome do controller e verifica se o arquivo existe
        $controller = 'Application\\Controller\\' . $this->controller;

        try {
            $Controller = $container->get($controller);
        } catch (\Exception $e) {
            $this->notFound();
        }

        if(!method_exists($controller, $this->action)) {
            $this->notFound();
        }

        $action = $this->action;
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

    private function notFound() {
        header("HTTP/1.0 404 Not Found");
        die("Página não existe kk");
    }
}
