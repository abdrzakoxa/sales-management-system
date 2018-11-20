<?php

namespace Store\Controllers;


use Store\Core\Messenger;
use Store\Core\Sessions;
use Store\Core\Validate;
use Store\Models\GroupsModel;
use Store\Models\PermissionsgroupsModel;
use Store\Models\PermissionsModel;
use Store\Models\PermissionsusersModel;
use Store\Models\UsersModel;

class AccountController extends AbsController
{


    public function editAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('users.label');
        $this->Language->load('account.edit');
        $GroupsUsers        = new GroupsModel();
        $permissions        = new PermissionsModel();
        $permissionsGroups  = new PermissionsgroupsModel();
        $permissionsUsers   = new PermissionsusersModel();
        $usersModel         = new UsersModel();
        $valid              = new Validate($this->Language);
        $this->Data['Groups'] = $GroupsUsers->getAll();
        if($this->is_permission_user('permissions'))
        {
            $this->Data['permissions']  = $permissions->getAll();
        }
        $id = isset(Sessions::getInstance()->User->UserId) && is_numeric(Sessions::getInstance()->User->UserId) ? Sessions::getInstance()->User->UserId : false;

        if ($id == false) self::redirect('/Auth/Logout');

        if(self::has_post('submit')){
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
                'groupid' => 'foreign:app_groups.groupId',
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
                if ($this->is_permission_user('permissions')){
                    $usersModel->GroupId        = $this->getPost('groupid');
                }
                $usersModel->Status         = 1;
                $usersModel->Sex            = $this->getPost('sex');
                if($usersModel->update()){
                    if($this->is_permission_user('permissions'))
                    {
                        if(is_array($permissionsAdded) &&  count($permissionsAdded) > 0) {
                            $permissionsUsers->UserId = $id;
                            foreach ($permissionsAdded as $permissionId) {
                                $permissionsUsers->PermissionId = $permissionId;
                                $permissionsUsers->create();
                            }
                        }

                        if(is_array($permissionsDeleted) && count($permissionsDeleted) > 0) {
                            $permissionsUsers->UserId = $id;
                            foreach ($permissionsDeleted as $permissionId) {
                                $permissionsUsers->PermissionId = $permissionId;
                                $permissionsUsers->delete();
                            }
                        }
                    }


                    Messenger::getInstance()->create($this->Language->get('success_account_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }


        if(Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_users','UserId')){
                $permissions_user = self::getPremUser($id,$permissionsUsers,$permissionsGroups,$usersModel);

                $usersModels = new UsersModel();
                $this->Data['User'] = $usersModels->getByKey($id);
                $this->Data['PermissionUser'] = $permissions_user;
                if($this->is_permission_user('permissions'))
                {
                    $this->Data['PermissionUser'] = $permissions_user;
                }
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_user_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/');
            }
        }else{
            self::redirect('/');
        }


        $this->View();
    }


}

