<?php


namespace Store\core;


class Authorization
{

    private static $_getInstance;

    private $_session;

    private function __construct()
    {
        $this->_session = Sessions::getInstance();
    }

    public static function getInstance()
    {
        if(self::$_getInstance == null)
        {
            self::$_getInstance = new self();
        }
        return self::$_getInstance;
    }


    public function Auth($controller,$action = null)
    {
        $controller = strtolower($controller);
        if ($controller == 'auth') return ;

        $path = $action != null ? $controller . '/' . $action : $controller;

        if(!isset($this->_session->login) && !Helper::pages_not_access($path)){
            Helper::redirect('/Auth/Logout');
        }else
        if (!DB::connect() && $controller != 'install')
        {
            Helper::redirect('/install');
        }


    }


    public function isAuth()
    {
        if(isset($this->_session->login)){
            return $this->_session->login;
        }
        return false;
    }

}