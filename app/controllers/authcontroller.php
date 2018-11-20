<?php

namespace Store\controllers;


use Store\core\Authorization;
use Store\Core\DB;
use Store\Core\Messenger;
use Store\core\Settings;
use Store\Core\Validate;
use Store\Models\ProfileModel;
use Store\Models\UsersModel;

class AuthController extends AbsController
{
    public function defaultAction()
    {
        $this->View();
    }

    public function loginAction()
    {
        if (!(DB::connect() && isset(Settings::get('setting-company')->Name) && Settings::get('setting-company')->Name != '' && isset(Settings::get('database')->DatabaseName) && Settings::get('database')->DatabaseName != ''))
        {
            self::redirect('/install');
        }

        if (Authorization::getInstance()->isAuth())
        {
            self::redirect('/');
        }

        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('auth.login');
        $this->template_load(':VIEW');
        $this->Data['is_captcha'] = self::Settings('setting-site')->LoginCaptcha == 1 ? true : false;

        $this->Data['captcha'] = rand(1,15) . '+' . rand(1,20);

        if(self::has_post('submit'))
        {
            $valid = new Validate($this->Language);
            $valid->data = $_POST;

            $valid->rules = [
                'username' => 'required|max:15|min:3|type:alpha_dash',
                'password' => 'required|max:18|min:6|type:alpha_pass',
            ];


            if ($this->Data['is_captcha']){
                $arry = explode('+',self::post('captcha-num'));
                $captcha = self::post('captcha');
                $arry = array_map('trim' ,$arry);
                if (!Validate::valid($captcha,'int')){
                    $valid->error_list['problem'][] = '';
                    $valid->enter();
                }else{
                    foreach ($arry as $key => $value){
                        if (!Validate::valid($value,'int')){
                            $valid->error_list['problem'][] = '';
                            $valid->enter();
                        }
                    };
                }
                $operator = isset($arry[0]) & isset($arry[1]) ? $arry[0] + $arry[1] : 0;
                if ($operator != $captcha){
                    $valid->error_list['captcha'][] = $this->Language->get('error_captcha');
                }

            }

            if($valid->check())
            {
                $Users = new UsersModel();
                unset($_POST['submit']);
                if($Users::authenticate($_POST) == 1)
                {
                    $Profile = new \Store\Models\ProfileModel();
                    $this->Session->User  = $Users->inner_join('Username', self::getPost('username'));
                    $userId = $this->Session->User->UserId;
                    if(ProfileModel::table('app_users_profile')->exist('UserId',$userId)){
                        $this->Session->Profile  = $Profile->getByKey($userId);
                    }else{
                        $title      = '{{not_title_profile_1}}';
                        $content    = '{{not_content_profile_1}}';
                        $type    = 1;
                        $link = "/Profile";
                        $this->create_not($title,$content,$type,$userId,$link);
                    }
                    $this->Session->login = true;
                    $Users->Username = self::getPost('username');

                    if ($this->Session->User->LastLogin == 0){
                        $this->Messenger->createStatic($this->Language->getWithParams('info_welcome',self::getNameUsername()),Messenger::APP_TYPE_INFO);
                    }
                    $Users->setIPAddress($this->getIPAddress());
                    $Users->setLastLogin();
                    self::redirect('/');
                }elseif($Users::authenticate($_POST) == 0)
                {
                    Messenger::getInstance()->create($this->Language->get('warning_accent_disable'),Messenger::APP_TYPE_ERROR);
                }else{
                    Messenger::getInstance()->create($this->Language->get('error_user_not_exist'),Messenger::APP_TYPE_ERROR);
                }


            }
        }
        $this->View();
    }

    public function logoutAction()
    {
        $this->Session->destroy();
        self::redirect('/Auth/login');
    }
}