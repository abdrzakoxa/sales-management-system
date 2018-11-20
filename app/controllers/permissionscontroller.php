<?php

namespace Store\Controllers;


use Store\Core\DB;
use Store\Core\Sessions;
use Store\Models\GroupsModel;
use Store\Models\PermissionsgroupsModel;
use Store\Models\PermissionsModel;

use Store\Core\Validate;

use Store\Core\Messenger;
use Store\Models\PermissionsusersModel;
use Store\Models\UsersModel;

class PermissionsController extends AbsController
{
    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('permissions.default');
        $Permissions = new PermissionsModel();
        $this->Data['Permissions'] = $Permissions->getAll();
        $this->View();
    }

    public function editAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('permissions.label');
        $this->Language->load('permissions.edit');
        $valid = new Validate($this->Language);
        $id = self::getGet('id');
        if(self::has_post('submit') && self::has_get('id')){
            $valid->data = $_POST;
            $valid->primary['PermissionId'] = $id ;
            $valid->rules = [
                'name'          => 'required|max:30|min:3|type:words|same_unq:app_permissions',
                'permission'    => 'required|max:30|min:3|type:requestUrl|same_unq:app_permissions',
            ];

            if($valid->check()){
                $permissionModel = new PermissionsModel();
                $permissionModel->PermissionId   = $id;
                $permissionModel->Name           = $this->getPost('name');
                $permissionModel->Permission     = $this->getPost('permission');

                if($permissionModel->update()){
                    Messenger::getInstance()->create($this->Language->get('success_permission_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }


        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_permissions','PermissionId')){
                $permissionMode = new PermissionsModel();
                $this->Data['permission'] = $permissionMode->getByKey($id);
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_permission_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/permission/');
            }
        }else{
            self::redirect('/permission/');
        }




        $this->View();

    }

    public function deleteAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_permissions','PermissionId')){
            $Permission = new PermissionsModel();
            $Permission->PermissionId = $id;
            if($Permission->delete())
            {
                Messenger::getInstance()->create($this->Language->get('success_permission_delete'));
                self::redirect('/Permissions/');
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_permission_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Permissions/');
        }
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('permissions.label');
        $this->Language->load('permissions.create');

        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'name'          => 'required|max:30|min:3|type:words|unique:app_permissions',
                'permission'    => 'required|max:30|min:3|type:requestUrl|unique:app_permissions',

            ];
            if($valid->check()){
                $PermissionModel = new PermissionsModel();
                $PermissionUsersModel = new PermissionsusersModel();
                $UsersModel = new UsersModel();
                $PermissionModel->Permission= $this->getPost('permission');
                $PermissionModel->Name = $this->getPost('name');
                if($PermissionModel->create()){
                    $last = DB::connect()->lastInsertId();
                    $users = $UsersModel->getAll();
                    if($last != null && $last > 0){
                        foreach ($users as $user) {
                            $PermissionUsersModel->UserId = $user->UserId;
                            $PermissionUsersModel->PermissionId = $last;
                            $PermissionUsersModel->create();
                        }
                    }
                    Messenger::getInstance()->create($this->Language->get('success_permission_added'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }

            }
        }
        $this->View();


    }

    public function existAction()
    {
        if(count($_POST) > 0 & self::has_post(key($_POST)))
        {
            $valueOld = isset($_POST['valueOld']) ? $_POST['valueOld'] : false ;
            echo PermissionsModel::table('app_permissions')->exist(key($_POST),self::getPost(key($_POST)),$valueOld);
        }
    }



}