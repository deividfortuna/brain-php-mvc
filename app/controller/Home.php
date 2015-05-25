<?php
namespace App\Controller;

use App\Model\Blog;
use App\Model\Depoimentos;
use App\Model\Obras;
use Brain\Controller;

class Home extends Controller
{
    public function action()
    {
        $this->View->setData(array('linkHome' => ROOT));
    }

    public function beforeView()
    {
        $url = ROOT;
        $this->View->setData(array(
            'url'        => $url,
            'urlExample' => $url.'example/',
            'css'        => $url.'file/css/',
            'js'         => $url.'file/js/',
        ));
    }

    public function debug()
    {
        //\Brain\Cache::instance()->load();

        $this->debug = true;
        $this->View->setView('home/action');
    }

    public function index()
    {
        /*
        $data          = null;

        $cmmd = "whois -h whois.radb.net -- '-i origin AS32934' | grep ^route";
        exec($cmmd, $array);

        var_dump($array); die();

        $this->View->setData($data);
        */
    }

    public function modal()
    {
        /*
         * Informa que você deseja realizar o cache dessa página
         */
        $this->View->setDoCache();

        $data          = null;

        $this->View->setLayout('modal')->setData($data);
    }

    public function notFound()
    {
        die('Página não encontrada');
    }
}
