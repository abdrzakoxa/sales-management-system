<?php

namespace Store\Controllers;


use Store\Core\DB;
use Store\Core\Messenger;
use Store\Core\Validate;
use Store\Models\GroupsModel;
use Store\models\NotificationsModel;
use Store\models\NotificationsUsersModel;
use Store\Models\PermissionsgroupsModel;
use Store\Models\PermissionsModel;
use Store\Models\PermissionsusersModel;
use Store\Models\ProfileModel;
use Store\Models\UsersModel;

class UsersController extends AbsController
{

    public function editAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('users.label');
        $this->Language->load('users.edit');
        $GroupsUsers        = new GroupsModel();
        $permissions        = new PermissionsModel();
        $permissionsGroups  = new PermissionsgroupsModel();
        $permissionsUsers   = new PermissionsusersModel();
        $usersModel         = new UsersModel();
        $valid              = new Validate($this->Language);
        $this->Data['Groups'] = $GroupsUsers->getAll();
        $this->Data['permissions']  = $permissions->getAll();
        $id = self::getGet('id');

        if ($id == $this->Session->User->UserId) self::redirect('/Account/Edit/');


        if(self::has_post('submit') && self::has_get('id')){
            $valid->data = $_POST;
            $valid->primary['UserId'] = $id ;
            $valid->rules = [
                'username' => 'required|max:15|min:3|type:alpha_dash|same_unq:app_users',
                'email' => 'required|max:50|min:6|type:email|same_unq:app_users',
                'password' => 'max:18|min:6|type:alpha_pass|confirmation:confirm_password',
                'confirm_password' => 'max:18|min:6|type:alpha_pass',
                'password_old' => 'required|match:app_users.password',
                'phone' => 'required|max:18|min:6|type:phone|same_unq:app_users',
                'sex' => 'required|list:1,2',
                'groupid' => 'required|foreign:app_groups.groupId',
                'status' => 'required|list:1,2',
                'permission' => 'required|is_array|foreign:app_permissions.permissionId'
            ];


            $havePermissions    = $permissionsUsers->getsBy('UserId',$id,'PermissionId','default');

            $permissionUserSelect = self::getPost('permission') ? self::getPost('permission') : [];

            $havePermissions    = self::array_flatten($havePermissions);

            $permissionsAdded = array_diff($permissionUserSelect,$havePermissions);

            $permissionsDeleted = array_diff($havePermissions,$permissionUserSelect);


            if($valid->check()){
                $usersModel->UserId         = $id;
                $usersModel->Username       = $this->getPost('username');
                $usersModel->Password       = $this->getPost('password');
                $usersModel->Password_old   = $this->getPost('password_old');
                $usersModel->Phone          = $this->getPost('phone');
                $usersModel->Email          = $this->getPost('email');
                $usersModel->GroupId        = $this->getPost('groupid');
                $usersModel->Status         = $this->getPost('status') == 2 ? 0 : 1;
                $usersModel->Sex            = $this->getPost('sex');
                if($usersModel->update()){
                    if (is_array($permissionsAdded) && count($permissionsAdded) > 0) {
                        $permissionsUsers->UserId = self::getGet('id');
                        foreach ($permissionsAdded as $permissionId) {
                            $permissionsUsers->PermissionId = $permissionId;
                            $permissionsUsers->create();
                        }
                    }

                    if (is_array($permissionsDeleted) && count($permissionsDeleted) > 0) {
                        $permissionsUsers->UserId = self::getGet('id');
                        foreach ($permissionsDeleted as $permissionId) {
                            $permissionsUsers->PermissionId = $permissionId;
                            $permissionsUsers->delete();
                        }
                    }


                    $title  = '{{not_title_1}}';
                    $content    = '{{not_content_1!!'.self::getNameUsername().'}}';
                    $type   = 1;
                    $userId = $id;
                    $this->create_not($title,$content,$type,$userId);
                    Messenger::getInstance()->create($this->Language->get('success_user_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();

                }
            }
        }


        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_users','UserId')){
                $permissions_user = self::getPremUser($id,$permissionsUsers,$permissionsGroups,$usersModel);

                $usersModels = new UsersModel();
                $this->Data['User'] = $usersModels->getByKey($id);
                $this->Data['PermissionUser'] = $permissions_user;
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_user_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Users/');
            }
        }else{
            self::redirect('/Users/');
        }


        $this->View();
    }

    public function deleteAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('users.delete');
        $id = self::getGet('id');
        $UserIdLogin = isset($this->Session->User->UserId) ? $this->Session->User->UserId : false;
        if($UserIdLogin == $id) {
            Messenger::getInstance()->create($this->Language->get('warning_not_delete_self_you'), Messenger::APP_TYPE_WARNING);;
            self::redirect('/Users');
        }else{
            if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_users','UserId')){
                $UserModel = new UsersModel();
                $UserModel->UserId = $id;
                if($UserModel->delete())
                {
                    $Notification = new NotificationsModel();
                    $Notification->Title  = $this->Language->getWithParams('not_title_delete',$id);
                    $Notification->Content    = $this->Language->getWithParams('not_content_delete',[$UserModel->getBy('UserId',$id)->Username,$this->Session->User->Username]);
                    $Notification->Type   = 1;
                    $Notification->UserId = $this->Session->User->UserId;
                    if($Notification->create())
                    {
                        Messenger::getInstance()->create($this->Language->get('success_user_delete'));
                        self::redirect('/Users/');
                    }

                }
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_user_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Users/');
            }
        }
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('users.label');
        $this->Language->load('users.create');

        $GroupsUsers = new GroupsModel();
        $permissions = new PermissionsModel();

        $this->Data['Groups']       = $GroupsUsers->getAll();
        $this->Data['permissions']  = $permissions->getAll();

        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'username' => 'required|max:15|min:3|type:alpha_dash|unique:app_users',
                'email' => 'required|max:50|min:6|type:email|unique:app_users',
                'password' => 'required|max:18|min:6|type:alpha_pass|confirmation:confirm_password',
                'confirm_password' => 'required|max:18|min:6|type:alpha_pass',
                'phone' => 'required|max:18|min:6|type:phone|unique:app_users',
                'sex' => 'required|list:1,2',
                'status' => 'required|list:1,2',
                'groupid' => 'required|foreign:app_groups.groupId',
                'permission' => 'required|is_array|foreign:app_permissions.permissionId'
            ];
            if($valid->check()){ // $valid->check()
                $usersModel = new UsersModel();
                $usersModel->Username = $this->getPost('username');
                $usersModel->Password = $this->getPost('password');
                $usersModel->Phone = $this->getPost('phone');
                $usersModel->Email = $this->getPost('email');
                $usersModel->GroupId = $this->getPost('groupid');
                $usersModel->Status         = $this->getPost('status') == 2 ? 0 : 1;
                $usersModel->Sex = $this->getPost('sex');
                if($usersModel->create()){ /// $usersModel->create()

                    $permissionsUsers = new PermissionsusersModel();
                    $permissionsUsers->UserId       = $usersModel->getIdByU();

                    if(is_array($this->getPost('permission')) && count($this->getPost('permission')) > 0) {
                        foreach ($this->getPost('permission') as $permission ){
                            $permissionsUsers->PermissionId = $permission;
                            $permissionsUsers->create();
                        }
                    }
                    Messenger::getInstance()->create($this->Language->get('success_user_added'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }

            }
        }


        $this->View();


    }

    public function defaultAction()
    {

        $this->Language->load('template.main');
        $this->Language->load('users.default');
        $UserModel = new UsersModel();
        $this->Data['Users'] = $UserModel->getAll();
        $this->View();

    }

    public function previewAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('users.preview');
        $profile = new ProfileModel();
        $Id = self::get('id');
        if (self::has_get('id')){

            $profile = $profile->getbyKey($Id);

            if (empty($profile)){
                $profile = new \stdClass();
                $profile->FirstName = '';
                $profile->LastName = '';
                $profile->DOB = '';
                $profile->Address = '';
                $profile->Image = '';
            }

            $this->Data['Profile'] = $profile;
        }else{
            self::redirect('/Users');
        }

        $this->View();


    }

    public function existAction()
    {
        if(count($_POST) > 0 & self::has_post(key($_POST)))
        {
            $valueOld = isset($_POST['valueOld']) ? $_POST['valueOld'] : false ;
            echo UsersModel::table('app_users')->exist(key($_POST),self::getPost(key($_POST)),$valueOld);
        }
    }

    public function getPermGroupAction()
    {
        if(self::has_post('GroupId'))
        {
            $Groups = new PermissionsgroupsModel();
            $Permission_group = $Groups->getsBy('GroupId',self::getPost('GroupId'),'PermissionId','default');
            $Permission_group = self::array_flatten($Permission_group);
            $Permission_group = implode('|',$Permission_group);
            echo trim($Permission_group);
        }elseif (self::has_post('UserId')){
            $Users = new PermissionsusersModel();
            $Permission_User = $Users->getsBy('UserId',self::getPost('UserId'),'PermissionId','default');
            $Permission_User = self::array_flatten($Permission_User);
            $Permission_User = implode('|',$Permission_User);
            echo trim($Permission_User);
        }
    }

    public function Ajax115sAction()
    {
        if (self::post('Random') == 'lkasnjsdoihi04i9iru934uew993giusd9uiu'){
            $User = new UsersModel();
            $User->UserId = $this->Session->User->UserId;
            $User->setLastLogin();
        }else{
            self::redirect("/");
        }
    }

}