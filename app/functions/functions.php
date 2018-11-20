<?php


function getProtocol(){

    $Protocol = strtolower($_SERVER['SERVER_PROTOCOL']);

    $Protocol = explode('/' , $Protocol);

    if(in_array('https' , $Protocol)){
        return 'https:';
    }elseif(in_array('http' , $Protocol)){
        return 'http:';
    }
    return false;
}




