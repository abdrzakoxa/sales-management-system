<?php

namespace Store\Core;


class Messenger
{
    private static $Messenger = null;
    public $_session = null;
    const APP_TYPE_SUCCESS  = 1;
    const APP_TYPE_ERROR    = 2;
    const APP_TYPE_INFO     = 3;
    const APP_TYPE_WARNING  = 4;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if(SELF::$Messenger == null){
            SELF::$Messenger = new self();
        }
        return SELF::$Messenger;
    }

    public function create($Messenger,$type = SELF::APP_TYPE_SUCCESS)
    {
        if($this->has_Messengers()){
            $this->_session = Sessions::getInstance();
            $this->_session->Messengers = [];
        }
        $msg = $this->_session->Messengers;
        $msg[] = [$Messenger,$type];
        $this->_session->Messengers = $msg ;
        unset($msg);
    }


    public function createStatic($Messenger,$type = SELF::APP_TYPE_SUCCESS)
    {
        if($this->has_Messengers()){
            $this->_session = Sessions::getInstance();
            $this->_session->Messengers_Static = [];
        }
        $msg = $this->_session->Messengers_Static;
        $msg[] = [$Messenger,$type];
        $this->_session->Messengers_Static = $msg ;
        unset($msg);
    }


    public function getMessengers($is_static_messenger = false)
    {
        $Messenger = "Messengers";
        if ($is_static_messenger) $Messenger = "Messengers_Static";
        if(isset(Sessions::getInstance()->{$Messenger})){
            return Sessions::getInstance()->{$Messenger};
        }
        return false;
    }

    public function emptyMessengers($is_static = false)
    {
        $Messengers = 'Messengers';
        if ($is_static) $Messengers = "Messengers_Static";
        if(isset(Sessions::getInstance()->{$Messengers})){
            unset(Sessions::getInstance()->{$Messengers});
        }
        return true;
    }

    public function has_Messengers()
    {
        return !isset($this->_session->Messengers);
    }

    public function exist($is_Messengers_Static=false)
    {
        if ($is_Messengers_Static && isset(Sessions::getInstance()->Messengers_Static)){
            return true;
        }else if(isset(Sessions::getInstance()->Messengers) && Sessions::getInstance()->Messengers != ''){
            return true;
        }
        return false;
    }



}