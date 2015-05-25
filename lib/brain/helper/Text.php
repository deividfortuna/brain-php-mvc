<?php
namespace Brain\Helper;


class Text
{
    public static function toURL($string, $slug = '-')
    {
        return preg_replace('/[^A-Za-z0-9-]+/', $slug, strtolower($string));
    }

    public static function getNumbers($string)
    {
        return preg_replace("/[^0-9]/", "", $string);
    }

    public static function converterData($originalDate)
    {
        return str_replace('-', '/', date("d-m-Y", strtotime($originalDate)));
    }
} 