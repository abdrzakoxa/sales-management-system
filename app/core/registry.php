<?php

namespace Store\Core;


class Registry
{
    private static $_nowRegistry = null;

    private function __construct(){}

    public static function getInstance()
    {
       if(SELF::$_nowRegistry == null)
       {
           SELF::$_nowRegistry = new SELF;
       }
       return SELF::$_nowRegistry;
    }

//    public function __set($key,$value)
//    {
//        $this->$key = $value;
//    }
//
//    public function __get($key)
//    {
//        $this->key ;
//    }
}