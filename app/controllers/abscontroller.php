<?php

namespace Store\Controllers;


use Store\Core\Controller;
use Store\Core\DB;
use Store\Core\Helper;
use Store\Core\Messenger;
use Store\core\Notifications;
use Store\Core\Sanitize;
use Store\Core\Sessions;
use Store\core\Settings;
use Store\core\Template;
use Store\models\NotificationsUsersModel;
use Store\Models\UsersModel;

abstract class AbsController
{
    use Helper;
    use Notifications;
    use Template;
    use Sanitize;

    protected $controller , $action , $registry, $Data ,$permissions_user , $all_permissions;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function __get($key)
    {
        return $this->registry->$key;
    }

    public function __set($key,$value)
    {
        return $this->registry->$key = $value;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getUrlR()
    {
        return $this->controller . '/' . $this->action;
    }

    public function Preparation()
    {

        if (!DB::connect()) return false;

        $UserId = isset($this->Session->User->UserId) ? $this->Session->User->UserId : 0;

        if (strtolower($this->controller) != 'auth')
        {
            $user = UsersModel::getByKey($UserId);
            if (empty($user))
            {
                Messenger::getInstance()->create($this->Language->get('warning_user_drop'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Auth/Logout');
            }else{
                if (UsersModel::getStatus($UserId) == 0){
                    Messenger::getInstance()->create($this->Language->get('warning_your_account_disable'),Messenger::APP_TYPE_WARNING);
                    self::redirect('/Auth/Logout');
                }
            }
        }
        if ($this->is_permission_user('notifications')){
            $this->Language->set_lexicon(['notificationsnotview' => NotificationsUsersModel::getNotViews()]);
            $this->Language->set_lexicon(['notificationsnotviewshowed' => NotificationsUsersModel::getNotViewsShowed()]);
            $this->Language->set_lexicon(['countNotViews' => NotificationsUsersModel::countNotViews()]);
        }

    }

    public function operators_user()
    {
        if (!DB::connect()) return false;
        $UserId = $this->Session->User->UserId;
        $us = new UsersModel();
        $us->UserId = $UserId;
        $us->setLastLogin();
    }

    public function View()
    {
        $this->Preparation();
        if (isset($this->Session->User->UserId) && is_numeric($this->Session->User->UserId)){
            $this->operators_user();
        }
        $this->Language->set_lexicon($this->Data);
        $file = VIEWS_PATH . DS . $this->controller  . DS . $this->action . '.view.php';
        $fileNotFound = VIEWS_PATH . DS . strtolower(Controller::NOT_FOUND_CONTROLLER) . DS  .Controller::NOT_FOUND_ACTION . '.view.php';
        if(file_exists($file) && $this->action != Controller::NOT_FOUND_ACTION){
            $this->tpl($file)->render();
        }else{
            require_once $fileNotFound;
        }
    }

    public function VOO()
    {
        $this->Data = $this->Data == null ? $this->Language->getLexicon() : array_merge($this->Language->getLexicon(),$this->Data) ;
        print_r($this->tpl('sda'));
    }

    public function imageUser()
    {
        echo isset($this->Session->Profile->Image) ? UPLOADS_PATH . DS . $this->Session->Profile->Image : '/uploads/default.png';
    }

    public static function Settings($settings)
    {
        return Settings::get($settings);
    }



    private function getPermissionsUser()
    {
        $UserId = isset(Sessions::getInstance()->User->UserId) ? Sessions::getInstance()->User->UserId : '';
        if (empty($this->permissions_user))
        {
            $this->permissions_user = DB::statement("select app_permissions.Permission from app_permissions_users INNER JOIN app_permissions ON app_permissions_users.PermissionId=app_permissions.PermissionId where UserId=?", [$UserId])->fetchAll();
        }
        if (empty($this->all_permissions))
        {
            $this->all_permissions = DB::statement("select Permission from app_permissions")->fetchAll();
        }

        $p = self::array_flatten($this->permissions_user);
        $a = self::array_flatten($this->all_permissions);
        $array_diff = array_diff($a, $p);
        return array_map("strtolower", $array_diff);
    }


    private function getPermissionUserById($id = null)
    {
        if (!is_numeric($id)) return;
        $this->permissions_user = DB::statement("select app_permissions.Permission from app_permissions_users INNER JOIN app_permissions ON app_permissions_users.PermissionId=app_permissions.PermissionId where UserId=?", [$id])->fetchAll();
        $this->all_permissions = DB::statement("select Permission from app_permissions")->fetchAll();


        $p = self::array_flatten($this->permissions_user);
        $a = self::array_flatten($this->all_permissions);
        $array_diff = array_diff($a, $p);
        return array_map("strtolower", $array_diff);
    }



    public function is_permission_user($permissionUrl,$id=null)
    {

        $permissionUrl = strtolower($permissionUrl);
        if (is_numeric($id)){
            $permissions = $this->getPermissionUserById($id);
        }else{
            $permissions = $this->getPermissionsUser();
        }



        return in_array($permissionUrl,$permissions) ? false : true ;
    }





}