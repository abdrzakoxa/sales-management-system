<?php

namespace Store\Controllers;


use Store\core\Authorization;
use Store\Core\DB;
use Store\Core\Messenger;
use Store\Core\Settings;
use Store\Core\Validate;


class InstallController extends AbsController
{
    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('install.system');
        $this->template_load(':VIEW');


        if (DB::connect() && isset(Settings::get('setting-company')->Name) && Settings::get('setting-company')->Name != '' && isset(Settings::get('database')->DatabaseName) && Settings::get('database')->DatabaseName != '')
        {
            self::redirect('/');
        }
        $Settings = new \stdClass();

        if(self::has_post('submit_settings_company'))
        {
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'company_name'  => 'required|max:50|min:3|type:words',
                'email'         => 'required|max:50|min:6|type:email',
                'phone'         => 'required|max:18|min:6|type:phone',
                'address'       => 'required|max:120|min:6|type:address'
            ];
            if ($valid->check()) {
                $Settings->Name = self::post('company_name');
                $Settings->Email = self::post('email');
                $Settings->Phone = self::post('phone');
                $Settings->Address = self::post('address');
                if(Settings::save('setting-company',$Settings))
                {
                    $this->clear_request('submit_settings_company');
                }
            }
        }


        if(self::has_post('submit_settings_database'))
        {
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'database_name'  => 'required',
                'username'       => 'required',
                'password'       => 'max:50',
                'hostname'       => 'required'
            ];

            if ($valid->check()) {
                $Settings->DatabaseName = self::post('database_name');
                $Settings->Username = self::post('username');
                $Settings->Password = self::post('password');
                $Settings->Hostname = self::post('hostname');
                if(Settings::save('database',$Settings))
                {
                    if (DB::connect()){
                    	if (!Authorization::getInstance()->isAuth()){
							Messenger::getInstance()->create($this->Language->get('information_login'),Messenger::APP_TYPE_INFO);
						}
						Messenger::getInstance()->create($this->Language->get('success_install_app'),Messenger::APP_TYPE_SUCCESS);
                        self::redirect('/');
                    }else{
                        Messenger::getInstance()->create($this->Language->get('error_connect_database'),Messenger::APP_TYPE_ERROR);
                    }
                    $this->clear_request('submit_settings_company');
                }

            }
        }


        $this->View();
    }
}