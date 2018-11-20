<?php

namespace Store\Core;


class Sessions
{


    private $_running = false;

    public static $_session = null;


    private function __construct()
    {
        $this->start();
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function __get($name)
    {
        return $_SESSION[$name];
    }

    public static function getInstance()
    {
        if(SELF::$_session == null){
            SELF::$_session = new SELF();
        }
        return SELF::$_session;
    }

    public function __isset($name)
    {
        if(isset($_SESSION[$name])){
            return true;
        }else{
            return false;
        }
    }

    public function start()
    {
        if(!isset($_SESSION)){
            session_start();
        }else{
            $this->_running = true;
        }
        return SELF::$_session;
    }

    public function destroy()
    {
        $this->start();
        session_unset();
        session_destroy();
    }

    public function __unset($key)
    {
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }



}