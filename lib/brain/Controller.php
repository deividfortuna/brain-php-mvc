<?php
namespace Brain;

/**
 * Controla a exibição de views e uso de suas variáveis.
 * Também resgata de parâmetros da url obtidos por System.
 *
 * @author  Deivid Fortuna <deividfortuna@gmail.com> Felipe Toreti <toreti@gmail.com>
 * @version 09/06/2014
 * @package Brain
 */
abstract class Controller
{
    /**
     * @var bool Define se a view deve ser exibida automaticamente após executar a action.
     */
    public $autoRender = true;

    /**
     * @var bool Define se as variáveis do sistema devem ser exibidas antes da view.
     */
    public $debug = false;

    /**
     * @var string[] Parâmetros resgatados da url no formato associativo.
     */
    private $paramAssociative = array();

    /**
     * @var string[] Parâmetros resgatados da url no formato indexado.
     */
    private $paramIndex = array();

    /**
     * @var System Permite resgatar dados da url.
     */
    public $System;

    /**
     * @var View Permite configurar a view e suas variáveis.
     */
    public $View;

    /**
     * É criada uma instancia de View para facilitar sua configuração.
     */
    public function __construct($View)
    {
    	$this->View = $View;
        //$this->View = new View();
    }

    /**
     * Método executado depois de exibir a view.
     */
    public function afterView()
    {
    }

    /**
     * Método executado antes de exibir a view.
     */
    public function beforeView()
    {
    }

    /**
     * Retorna o valor do parâmetro informado.
     *
     * Busca no formato associativo e por indice.
     * Retorna FALSE se o parâmetro não for encontrado.
     *
     * @param int|string $param
     * @return string|bool
     */
    public function getParam($param)
    {
        $this->setParam();
        return is_int($param) ? $this->getParamIndex($param) : $this->getParamAssociative($param);
    }

    /**
     * Retorna o valor do parâmetro informado, buscando no formato associativo ou FALSE caso o parâmetro não for encontrado.
     *
     * @param string $param
     * @return string|bool
     */
    public function getParamAssociative($param)
    {
        return isset($this->paramAssociative[$param]) ? $this->paramAssociative[$param] : false;
    }

    /**
     * Retorna o valor do parâmetro informado, buscando no formato de indice ou FALSE caso o parâmetro não for encontrado.
     *
     * @param int $param
     * @return string|bool
     */
    public function getParamIndex($param)
    {
        return isset($this->paramIndex[$param]) ? $this->paramIndex[$param] : false;
    }

    /**
     * Executado quando a action não for informada na url.
     */
    abstract public function index();

    /**
     * Executado quando a action não for encontrada.
     */
    abstract public function notFound();

    /**
     * Extrai os parametros da url.
     */
    private function setParam()
    {
        // Verifica se já foi extraido
        if (empty($this->paramIndex)) {

            // Remove o controller e action da url e armazena o array por indice
            $this->paramIndex = $this->System->getUrl();
            array_splice($this->paramIndex, 0, 2);

            // Monta o array associativo
            if (!empty($this->paramIndex) && count($this->paramIndex) % 2 === 0) {
                foreach ($this->paramIndex as $key => $val) {
                    if ($key % 2 === 0) {
                        $param[] = $val;
                    } else {
                        $value[] = $val;
                    }
                }
                $this->paramAssociative = array_combine($param, $value);
            }
        }
    }

    /**
     * Exibe o layout desejado que irá exibir a view.
     */
    public function view()
    {
        $this->View->dirView = DIR_ROOT.'app'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR;
        $this->View->showLayout();
    }
}
