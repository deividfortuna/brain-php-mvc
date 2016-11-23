<?php
namespace Brain;


class BasicDao
{
    protected function execute($db, $query, $array = null, $return = null, $firstElement = false)
    {
        $q = $db->prepare($query);
        $q->execute($array);
        switch ($return) {
            case 'fetchAll':
                $q->setFetchMode(\PDO::FETCH_ASSOC);
                $r = $q->fetchAll();
                return count($r) > 0 ? ($firstElement ? $r[0] : $r) : false;
                break;
            case 'lastInsertId':
                return $db->lastInsertId();
                break;
            default:
                return $q->rowCount();
                break;
        }
    }

    protected function fetchAll($db, $query, $array, $firstElement = false)
    {
        return $this->execute($db, $query, $array, 'fetchAll', $firstElement);
    }

    protected function getAll($db, $query, $array)
    {
        return $this->fetchAll($db, $query, $array, false);
    }

    protected function get($db, $query, $array)
    {
        return $this->fetchAll($db, $query, $array, true);
    }

    protected function test($query, $array, $return = false)
    {
        $query = str_replace(array_keys($array), array_values($array), $query);

        $msg = print_r($query, true);
        if ($return) return $msg;

        echo '<pre>' . $msg . '</pre>';
        exit;
    }
}