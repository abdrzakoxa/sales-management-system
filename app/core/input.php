<?php

namespace Store\core;


use function Sodium\crypto_pwhash_scryptsalsa208sha256;

class Input
{


    public static function post($item){
        if(isset($_POST[$item])){
            return $_POST[$item];
        }
        return false;
    }

    public static function get($item){
        if(isset($_GET[$item])){
            return $_GET[$item];
        }
        return false;
    }

    public static function exist_post($item)
    {
        return isset($_POST[$item])? true : false ;
    }

    public static function exist_get($item)
    {
        return isset($_POST[$item])? true : false ;
    }

    public static function content_post($item){
        return isset($_POST[$item])? $_POST[$item] : false ;
    }

    public static function content_get($item){
        return isset($_GET[$item])? $_GET[$item] : false ;
    }

    public static function Hash($value)
    {
        return sha1(md5(hash('sha256',$value)));
    }


}