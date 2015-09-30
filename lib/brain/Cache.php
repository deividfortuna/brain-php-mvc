<?php
namespace Brain;
/**
* @author  Deivid Fortuna <deividfortuna@gmail.com>
* @package Brain
*/

class Cache {

    private static $_System;
    private static $_path;
    private static $_file_path;
    private static $_creat_cache_file = false;

    private static $instance;

    public static $validity = 1; // em minutos

    public static function instance()
    {
        if (!isset(self::$instance)) {
            $c              = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public static function load()
    {
        self::$_System     = \Brain\System::instance();
        if(self::cacheFileIsValid()) self::loadCacheFile();
        else self::$_creat_cache_file = true;
    }

    private static function setPath()
    {
        $path       = DIR_ROOT . strtolower('cache/' . self::$_System->getController() . '/');
        if(!is_dir($path)) mkdir($path, 0777, true);

        return $path;
    }

    private static function getPath()
    {
        if(empty(self::$_path)) return self::setPath();
        else return self::$_path;
    }

    private static function setFilePath()
    {
        //var_dump(self::$_System->getUrl()); exit(); die;
        $file_name = implode("_", self::$_System->getUrl());
        $file_name = (empty($file_name)) ? 'home_index' : $file_name;

        self::$_file_path  = self::getPath() . strtolower($file_name). '.tmp';
        return self::$_file_path;
    }

    private static function getFilePath()
    {
        if(empty(self::$_file_path)) return self::setFilePath();
        else return self::$_file_path;
    }

    public static function creatCacheFile()
    {
        if (self::$_creat_cache_file) {
            $fp       = fopen(self::getFilePath(), 'w');
            $contents = ob_get_contents();
            $contents .= "<!-- Cached at time: " . date('d/m/Y h:i:s') . "-->";
            fwrite($fp, $contents);
            fclose($fp);
        }
        ob_end_flush();
    }

    private static function loadCacheFile()
    {
        if(file_exists(self::getFilePath()))
            require_once self::getFilePath();
        exit();
    }

    private static function cacheFileIsValid()
    {
        if(!file_exists(self::getFilePath())) return false;
        return (time() - (self::$validity * 60) < filemtime(self::getFilePath()));
    }
} 
