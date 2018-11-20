<?php

namespace Store\Controllers;

class NotfoundController extends AbsController
{
    public function notfoundAction()
    {
        $this->View();
    }

    public function notPermissionAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('notfound.notpermission');
        $this->View();
    }

}