<?php
namespace Brain\Repository;


class BasicRepository
{
    public function openDatabaseSession($callback) {
        $db = new \PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASS);
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $callback($db);
    }
}