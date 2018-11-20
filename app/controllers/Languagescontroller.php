<?php

namespace Store\Controllers;


use Store\Core\Helper;

class LanguagesController extends AbsController
{
    use Helper;



    public function changeAction()
    {
        $this->Language->changeLang($this->Language->changeTo());

        SELF::redirect('back');
    }




}