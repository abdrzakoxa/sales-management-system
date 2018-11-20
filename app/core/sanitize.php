<?php

namespace Store\Core;


trait Sanitize
{


    public static function filterUrlPath($url)
    {
        $url = parse_url($url, PHP_URL_PATH);
        $url = str_replace('%20' , '' , $url);
        $url = trim($url, '/');
        $url = strtolower($url);
        return $url;
    }

    public static function filterString($string)
    {
        $string = trim($string);
        $string = filter_var($string,FILTER_SANITIZE_STRING);
        return $string;
    }

    public static function filterPath($path)
    {
        $path = trim($path);
        $path = strtolower($path);
        return $path;
    }

    public static function Repair($rep){
        if(isset($_POST[$rep])){
            return $_POST[$rep];
        }elseif(isset($_GET[$rep])){
            return $_GET[$rep];
        }
        return false;
    }

}