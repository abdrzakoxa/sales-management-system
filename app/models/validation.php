<?php

namespace Store\models;


trait Validation
{

    public function is_valid()
    {
        foreach (self::$_input_rules_update as $rule){
            echo $rule;
        }
    }



    public function UserExist()
    {

    }

}