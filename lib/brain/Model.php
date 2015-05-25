<?php
namespace Brain;

class Model
{
    protected $db;
    public static $_table;
    private static $instance;

    function __construct()
    {
        $this->db = new \PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASS);
        // Exibir erros do PDO
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    static private function getInstance()
    {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new self;
            } catch (\Exception $e) {
                die('Erro ao criar instância da classe Model. (' . $e . ')');
            }
        }
        return self::$instance;
    }

    /**
     * Executa o sql informado e retorna o resultado desejado.
     *
     * @param string $query
     * @param array $array
     * @param string $return
     *      <b>fetchAll</b> retorna os registros encontrados<br/>
     *      <b>lastInsertId</b> retorna o id do registro inserido<br/>
     *      <b>NULL</b> retorna a quantidade de linhas afetadas
     * @param boolean $firstElement caso seja utilizado o $retorno <b>fetchAl</b>, indica se deve retornar o primeiro item ou o resultado completo
     * @return array|bool|int
     */
    static function execute($query, $array = null, $return = null, $firstElement = false)
    {
        $M = self::getInstance();
        $q = $M->db->prepare($query);
        $q->execute($array);
        switch ($return) {
            case 'fetchAll':
                $q->setFetchMode(\PDO::FETCH_ASSOC);
                $r = $q->fetchAll();
                return count($r) > 0 ? ($firstElement ? $r[0] : $r) : false;
                break;
            case 'lastInsertId':
                return $M->db->lastInsertId();
                break;
            default:
                return $q->rowCount();
                break;
        }
    }

    private static function fetchAll($query, $array, $firstElement = false)
    {
        return self::execute($query, $array, 'fetchAll', $firstElement);
    }

    public static function findAll($query, $array)
    {
        return self::fetchAll($query, $array, false);
    }

    public static function findUnique($query, $array)
    {
        return self::fetchAll($query, $array, true);
    }

    /**
     * Retorna o sql preenchido com as variáveis informadas.
     *
     * @param string $query
     * @param array $array
     * @param bool $return
     * @return string
     */
    static function teste($query, $array, $return = false)
    {
        $query = str_replace(array_keys($array), array_values($array), $query);

        $msg = print_r($query, true);
        if ($return) return $msg;

        echo '<pre>'.$msg.'</pre>';
        exit;
    }
} 