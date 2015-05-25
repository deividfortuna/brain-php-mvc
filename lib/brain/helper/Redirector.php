<?php
namespace Brain\Helper;


class Redirector
{
    public static function go($url)
    {
        self::goToUrl(ROOT . $url);
    }

    public static function goToUrl($url)
    {
        header('Location:'.$url);
        exit();
    }
} 