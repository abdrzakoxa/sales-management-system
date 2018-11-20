<?php

namespace Store\Core;


class Cookies
{
    private $name , $value, $time, $path;

    public function __construct($name, $value, $days=1, $path='/')
    {
        $days = time() + 86400 ;

        $this->name = $name;
        $this->value = $value;
        $this->time = $days ;
        $this->path = $path;
        return $this;
    }

    public function set(){
        setcookie($this->name, $this->value, $this->time, $this->path);
        return $this;
    }

    public function get($name=''){
        $name = empty($name) ? $this->name : $name;
        if($this->have($name)){
            return $_COOKIE[$this->name];
        }
        return false;
    }

    public function have($name='')
    {
        $name = empty($name) ? $this->name : $name;

        if(isset($_COOKIE[$name])){
            return true;
        }
        return false;
    }

    public function is_enable()
    {
        if($this->have()){
            return true;
        }
        return false;
    }

    public function change($newValue){
        if($this->have()){
            $this->value = $newValue;
            setcookie($this->name , $newValue , $this->time );
            return true;
        }
        return false;
    }

//    functions static



    public static function destroy($name , $day=30)
    {
        $day = $day * 60 * 60 * 24;

        return setcookie($name , '' , -$day);
    }

    public static function is_set($name){
        if(isset($_COOKIE[$name])){
            return true;
        }
        return false;
    }


    public static function need($name){
        if(SELF::is_set($name)){
            return $_COOKIE[$name];
        }
        return false;
    }



}