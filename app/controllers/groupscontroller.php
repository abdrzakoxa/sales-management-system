<?php

namespace Store\controllers;


use Store\Models\GroupsModel;
use Store\Core\Validate;
use Store\Core\Messenger;
use Store\Models\PermissionsgroupsModel;
use Store\Models\PermissionsModel;
use Store\Models\UsersModel;

class GroupsController extends AbsController
{


    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('groups.default');
        $groups = new GroupsModel();
        $this->Data['Groups'] = $groups->getAll();
        $this->View();
    }

    public function editAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('groups.label');
        $this->Language->load('groups.edit');
        $valid = new Validate($this->Language);
        $id = self::getGet('id');
        $permissions                        = new PermissionsModel();
        $permissionsgroups                  = new PermissionsgroupsModel();
        if(self::has_post('submit') && self::has_get('id')){
            $valid->data = $_POST;
            $valid->primary['GroupId'] = $id ;
            $valid->rules = [
                'groupName' => 'required|max:15|min:3|type:words|same_unq:app_groups',
                'permission' => 'required|is_array|foreign:app_permissions.permissionId'
            ];

            $havePermissions    = $permissionsgroups->getsBy('GroupId',$id,'PermissionId','default');

            $permissionUserSelect = self::has_post('permission') ? self::getPost('permission') : [];

            $havePermissions = self::array_flatten($havePermissions);

            $permissionsAdded = array_diff($permissionUserSelect,$havePermissions);

            $permissionsDeleted = array_diff($havePermissions,$permissionUserSelect);

            if($valid->check()){
                $GroupModel = new GroupsModel();
                $GroupModel->GroupId   = $id;
                $GroupModel->GroupName = $this->getPost('groupName');

                if($GroupModel->update()){
                    Messenger::getInstance()->create($this->Language->get('success_group_updated'),Messenger::APP_TYPE_SUCCESS);
                    $permissionsgroups->GroupId = $id;
                    if(count($permissionsDeleted) > 0) {
                        foreach ($permissionsDeleted as $permissionD) {
                            $permissionsgroups->PermissionId = $permissionD;
                            $permissionsgroups->delete();
                        }
                    }
                    if(count($permissionsAdded) > 0){
                        foreach ($permissionsAdded as $permissionA) {
                            $permissionsgroups->PermissionId = $permissionA;
                            $permissionsgroups->create();
                        }
                    }
                }
            }
        }


        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_groups','GroupId')){
                $GroupModel                         = new GroupsModel();
                $permissionsgroups                  = new PermissionsgroupsModel();
                $this->Data['Groups']               = $GroupModel->getByKey($id);
                $this->Data['permissions']          = $permissions->getAll();
                $this->Data['PermissionsGroups']    = $permissionsgroups->getsBy('GroupId',$id);
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_group_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Groups/');
            }
        }else{
            self::redirect('/Groups/');
        }




        $this->View();

    }

    public function deleteAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_groups','GroupId')){
            $Groups = new GroupsModel();
            $Groups->GroupId = $id;
            if($this->Session->User->GroupId == $id)
            {
                Messenger::getInstance()->create($this->Language->get('warning_not_delete_group_self'), Messenger::APP_TYPE_WARNING);;
                self::redirect('/Groups');
            }
            if($Groups->delete())
            {
                Messenger::getInstance()->create($this->Language->get('success_group_delete'));
                self::redirect('/groups/');
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_group_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/groups/');
        }
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('groups.label');
        $this->Language->load('groups.create');
        $permissions = new PermissionsModel();
        $this->Data['permissions'] = $permissions->getAll();
        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'groupName' => 'required|max:15|min:3|type:words|unique:app_groups',
                'permission' => 'required|is_array|foreign:app_permissions.permissionId'
            ];
            if($valid->check()){
                $GroupModel = new GroupsModel();
                $permissionsGroups = new PermissionsgroupsModel();
                $GroupModel->GroupName = $this->getPost('groupName');
                if($GroupModel->create()){
                    $permissionsGroups->GroupId     = $GroupModel->getIdByN()->GroupId;
                    if(self::getPost('permission') && count(self::getPost('permission')) > 0){
                        foreach (self::getPost('permission') as $permission){
                            $permissionsGroups->PermissionId = $permission;
                            if(!$permissionsGroups->create()){
                                Messenger::getInstance()->create($this->Language->get('error_group_link_permission'),Messenger::APP_TYPE_ERROR);
                                return false;
                            }
                        }
                    }
                    Messenger::getInstance()->create($this->Language->get('success_group_added'),Messenger::APP_TYPE_SUCCESS);
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
            echo GroupsModel::table('app_groups')->exist(key($_POST),self::getPost(key($_POST)),$valueOld);
        }
    }

}