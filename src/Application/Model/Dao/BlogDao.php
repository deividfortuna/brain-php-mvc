<?php
namespace Application\Model\Dao;

use Brain\BasicDao;

class BlogDao extends BasicDao
{
    public function getPosts($db)
    {
        $query = "SELECT * FROM teste";
        return parent::getAll($db, $query, []);
    }

    public function savePost($db, $post) : int {
        $query = "INSERT INTO teste (titulo) VALUES (':titulo')";
        $array = [":titulo" => $post["titulo"]];

        return parent::execute($db, $query, $array, 'lastInsertId');
    }
}