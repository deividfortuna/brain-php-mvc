<?php
/**
 * Created by PhpStorm.
 * User: deividfortuna
 * Date: 08/07/14
 * Time: 14:48
 */

namespace App\Model;


use Brain\Model;

class Blog extends \Brain\Model
{
    public static function  getUltimosPosts()
    {
        $query = "SELECT id, ativo, DATE_FORMAT(data, '%d/%m/%Y') as data, titulo, chamada, texto FROM tb_blog WHERE ativo = 's' ORDER BY tb_blog.data DESC LIMIT :id";
        $array = array(
            ':id'   => 666
        );

        return Model::fetchAll($query, $array);
    }
} 