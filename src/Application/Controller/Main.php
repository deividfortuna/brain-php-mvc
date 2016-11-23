<?php
namespace Application\Controller;

use Application\Service\BlogService;
use Brain\Controller;
use Brain\CacheView;
use Brain\View;

class Main extends Controller
{
    private $blogService;

    public function __construct(View $view, BlogService $blogService)
    {
        parent::__construct($view);
        $this->blogService = $blogService;
    }

    public function action()
    {
        $this->View->setData(array('linkHome' => ROOT));
    }

    public function beforeView()
    {
        $url = ROOT;
        $this->View->setData(array(
            'url' => $url,
            'urlExample' => $url . 'example/',
            'css' => $url . 'file/css/',
            'js' => $url . 'file/js/',
        ));
    }

    public function debug()
    {
        $Cache = new CacheView($this->System);

        $this->debug = true;

        $this->View
            ->setCacheProvider($Cache)
            ->setDoCache()
            ->setView('home/action');
    }

    public function index()
    {
        $this->View->setView('home/index');
    }

    public function createNewPost() {
        $post = [
            "titulo" => "Tetse"
        ];

        $this->blogService->salvaPost($post);

        $posts = $this->blogService->getPosts();
        var_dump($posts);
    }

    public function modal()
    {
        $Cache = new CacheView($this->System);

        $this->View
            ->setCacheProvider($Cache)
            ->setDoCache();


        $data = null;

        $this->View->setLayout('modal')->setView('home/modal')->setData($data);
    }

    public function notFound()
    {
        die('Página não encontrada');
    }
}
