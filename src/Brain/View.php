<?php
namespace Brain;

/**
 *
 * @author  Deivid Fortuna<deividfortuna@gmail.com>, Felipe Toreti <toreti@gmail.com>
 * @version 09/06/2014
 * @package Brain
 */
class View
{
    private $data = array();
    public $dirView;
    private $layout;
    private $view;
    private $doCache = false;
    private $CacheProvider;

    public function getData()
    {
        return $this->data;
    }

    public function getLayout()
    {
        return $this->layout;
    }

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

    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    public function setDoCache()
    {
        $this->doCache = true;
        return $this;
    }

    public function setCacheProvider($Cache)
    {
        $this->CacheProvider = $Cache;
        return $this;
    }

    protected function show($view, array $data = null)
    {
        $view = str_replace('/', DIRECTORY_SEPARATOR, $view);

        if ($view == DIRECTORY_SEPARATOR) {
            return;
        }
        if (!is_null($data)) {
            extract($data);
        }
        require_once $this->dirView . $view . '.phtml';
    }


    protected function showWithData($view)
    {
        $this->show($view, $this->data);
    }

    protected function showElement($element, array $data = null)
    {
        $this->show('element/' . $element, $data);
    }

    protected function showElementWithData($element)
    {
        $this->show('element/' . $element, $this->data);
    }

    public function showLayout()
    {
        if ($this->doCache) {
            $this->CacheProvider->load();
        }

        if (is_null($this->layout)) {
            $this->layout = 'default';
        }
        $this->show($this->layout, $this->data);

        if ($this->doCache) {
            $this->CacheProvider->creatCacheFile();
        }
        exit;
    }

    protected function showView()
    {
        if (is_null($this->view)) {
            $S = System::instance();
            $this->view = lcfirst($S->getController()) . DIRECTORY_SEPARATOR . $S->getAction();
        }
        $this->show($this->view, $this->data);
    }
}
