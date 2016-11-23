<?php
namespace Application\Service;

use Application\Repository\BlogRepository;
use Brain\Service\BasicService;

class BlogService extends BasicService
{
    private $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    private function validate($blog) {
        $errors = [];

        if(strlen($blog['titulo']) == 0) {
            $errors['titulo'] = 'Campo titulo não pode ser vazio';
        }

        return $errors;
    }

    public function salvaPost($post) {
        $errors = $this->validate($post);

        if(sizeof($errors) > 0) {
            return $errors;
        }

        $this->blogRepository->save($post);
    }

    public function getPosts()
    {
        return $this->blogRepository->getPosts();
    }
}