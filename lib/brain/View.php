<?php
namespace Brain;

/**
 * Controla a exibição de views e uso de suas variáveis.
 *
 * @author  Felipe Toreti <toreti@gmail.com>
 * @version 09/06/2014
 * @package Brain
 */
class View
{
    /**
     * @var mixed[] Variáveis a serem utilizadas na view.
     */
    private $data = array();

    /**
     * @var string Diretório onde estão as views.
     */
    public $dirView;

    /**
     * @var string Nome do layout utilizado.
     */
    private $layout;

    /**
     * @var string Nome da view utilizada.
     */
    private $view;

    /**
     * @var boolean do_cache sinaliza se a pagina deve ser cacheada
     */
    private $do_cache = false;

    /**
     * Retorna o array das variáveis utilizadas na view.
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Retorna o layout a ser exibido.
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Retorna a view a ser exibida.
     *
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Define as variáveis que devem ser enviadas para a view.
     *
     * Pode ser informado uma váriavel por vez, informando $data e $value,
     * ou diversas informado um array associativo onde a chave é o nome da variável.
     *
     * @param string|array $data
     * @param string $value
     * @return View
     */
    public function setData($data, $value = null)
    {
        if (is_array($data)) {
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data[$data] = $value;
        }
        return $this;
    }

    /**
     * Define o layout a ser exibido.
     *
     * @param $layout
     * @return View
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Define a view a ser exibida.
     *
     * @param string $view
     * @return View
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    public function setDoCache(){
        $this->do_cache = true;
    }

    /**
     * Exibe o arquivo informado e extrai suas variáveis.
     *
     * @param string $view
     * @param array $data
     */

    protected function show($view, array $data = null)
    {
        if (!is_null($data)) {
            extract($data);
        }
        $view = str_replace('/', DIRECTORY_SEPARATOR, $view);
        require_once $this->dirView.$view.'.phtml';
    }


    /**
     * Exibe o arquivo informado e utiliza as variáveis definidas no controller.
     *
     * @param string $view
     */
    protected function showWithData($view)
    {
        $this->show($view, $this->data);
    }

    /**
     * Atalho para exibir arquivos dentro da pasta "element".
     * Não tem acesso as variáveis definidas no controller, apenas às informadas por parâmetro.
     *
     * @param string $element
     * @param array $data
     */
    protected function showElement($element, array $data = null)
    {
        $this->show('element/'.$element, $data);
    }

    /**
     * Atalho para exibir arquivos dentro da pasta "element".
     * Utiliza as variáveis definidas no controller.
     *
     * @param string $element
     */
    protected function showElementWithData($element)
    {
        $this->show('element/'.$element, $this->data);
    }

    /**
     * Exibe o layout que contém a view principal.
     */
    public function showLayout()
    {
        if($this->do_cache) {
            \Brain\Cache::instance()->load();
        }

        if (is_null($this->layout)) {
            $this->layout = 'default';
        }
        $this->show($this->layout, $this->data);

        if($this->do_cache) {
            \Brain\Cache::instance()->creatCacheFile();
        }
        exit;
    }

    /**
     * Exibe a view principal.
     *
     * Caso o nome da view não tenha sido declarado,
     * utiliza o nome do controller como pasta e o da action como arquivo.
     */
    protected function showView()
    {
        if (is_null($this->view)) {
            $S = System::instance();
            $this->view = lcfirst($S->getController()).DIRECTORY_SEPARATOR.$S->getAction();
        }
        $this->show($this->view, $this->data);
    }
}
