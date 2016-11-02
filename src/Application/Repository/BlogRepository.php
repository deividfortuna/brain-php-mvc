<?php
namespace Application\Repository;


use Application\Model\Dao\BlogDao;
use Brain\Repository\BasicRepository;

class BlogRepository extends BasicRepository
{
    private $blogDao;

    public function __construct(BlogDao $blogDao)
    {
        $this->blogDao = $blogDao;
    }

    public function save($post) {
        $this->openDatabaseSession(function ($db) use ($post) {
            var_dump("Saving...");
            $id = $this->blogDao->savePost($db, $post);
            var_dump($id);
        });
    }

    public function getPosts()
    {
        return $this->openDatabaseSession(function ($db) {
            return $this->blogDao->getPosts($db);;
        });
    }
}