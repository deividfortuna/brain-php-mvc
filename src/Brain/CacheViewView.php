<?php
namespace Brain;
/**
 * @author  Deivid Fortuna <deividfortuna@gmail.com>
 * @package Brain
 */

class CacheView
{

    private $_System;
    private $_path;
    private $_file_path;
    private $_creat_cache_file = false;

    private static $instance;

    public $validity = 1; // em minutos

    public function __construct($System)
    {
        $this->_System = $System;
    }

    public static function instance()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function load()
    {
        if ($this->cacheFileIsValid()) {
            $this->loadCacheFile();
        } else {
            $this->_creat_cache_file = true;
        }
    }

    private function setPath()
    {
        $path = DIR_ROOT . strtolower('cache/' . $this->_System->getController() . '/');
        if (!is_dir($path)) mkdir($path, 0777, true);

        return $path;
    }

    private function getPath()
    {
        if (empty($this->_path)) {
            return $this->setPath();
        } else {
            return $this->_path;
        }
    }

    private function setFilePath()
    {
        $file_name = implode("_", $this->_System->getUrl());
        $file_name = (empty($file_name)) ? 'home_index' : $file_name;

        $this->_file_path = $this->getPath() . strtolower($file_name) . '.tmp';
        return $this->_file_path;
    }

    private function getFilePath()
    {
        if (empty($this->_file_path)) return $this->setFilePath();
        else return $this->_file_path;
    }

    public function creatCacheFile()
    {
        if ($this->_creat_cache_file) {
            $fp = fopen($this->getFilePath(), 'w');
            $contents = ob_get_contents();
            $contents .= "<!-- Cached at time: " . date('d/m/Y h:i:s') . "-->";
            fwrite($fp, $contents);
            fclose($fp);
        }
        ob_end_flush();
    }

    private function loadCacheFile()
    {
        if (file_exists($this->getFilePath()))
            require_once $this->getFilePath();
        exit();
    }

    private function cacheFileIsValid()
    {
        if (!file_exists($this->getFilePath())) return false;
        return (time() - ($this->validity * 60) < filemtime($this->getFilePath()));
    }
} 
