<?php

namespace Store\controllers;


use Store\Core\Cookies;
use Store\Core\DB;
use Store\Core\Messenger;
use Store\Core\Validate;
use Store\Models\GroupsModel;
use Store\models\NotificationsModel;
use Store\models\NotificationsUsersModel;
use Store\Models\ProfileModel;
use Store\Models\UsersModel;

class NotificationsController extends AbsController
{

    public function DefaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('notifications.default');
        $this->Data['Notifications'] = NotificationsUsersModel::getNotUser();
        NotificationsUsersModel::Viewed();
        $this->View();
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('notifications.label');
        $this->Language->load('notifications.create');
        $this->Data['Users'] = UsersModel::getAll();
        $this->Data['Groups'] = GroupsModel::getAll();

        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'content'   => 'required|max:150|type:text',
                'title'     => 'max:80|type:text',
                'link'      => 'max:80|type:UrlPages',
                'users'      => 'is_array|foreign:app_users.UserId',
                'groups'      => 'is_array|foreign:app_groups.GroupId',
            ];
            if($valid->check()){ // $valid->check()
                $notifications = new NotificationsModel();
                $notifications->Title   = self::getPost('title') != '' ? self::getPost('title') : sprintf($this->Language->get('notification_in_user'),ProfileModel::get_name_user_login());
                $notifications->Content = $this->getPost('content');
                $notifications->Type    = 2;
                $notifications->Link    = $this->getPost('link');
                if($notifications->create()){ // $notifications->create()
                $lastId = DB::connect()->lastInsertId();
                $notificationsusers = new NotificationsUsersModel();
                    if (!empty(self::post('users')))
                    {
                        foreach (self::post('users') as $user)
                        {
                            $notificationsusers->UserId         = $user;
                            $notificationsusers->NotificationId = $lastId;
                            $notificationsusers->create();
                        }
                    }


                    if (!empty(self::post('groups')))
                    {
                        foreach (self::post('groups') as $group)
                        {
                            $users_groups = UsersModel::getBy('GroupId',$group,true);
                            if (!empty($users_groups)){
                                foreach ($users_groups as $user_group) {
                                    if(!empty(self::post('users')) && in_array($user_group->UserId,self::post('users')) ){
                                        if (in_array($user_group->UserId,self::post('users'))) continue;
                                    }
                                    $notificationsusers->UserId         = $user_group->UserId;
                                    $notificationsusers->NotificationId = $lastId;
                                    $notificationsusers->create();
                                }
                            }
                        }
                    }

                    Messenger::getInstance()->create($this->Language->get('success_notification_added'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }




            }
        }
        $this->View();
    }

    public function editAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('notifications.create');
        $this->Language->load('notifications.label');
        $notifications = new NotificationsModel();

        $id = self::getGet('id');


        if(self::has_post('submit') && Validate::valid($id,Validate::REGEX_INT)){
            $valid = new Validate($this->Language);
            $valid->primary = ['ExpenseId'];
            $valid->data = $_POST;
            $valid->rules = [
                'content'   => 'required|max:150|type:text',
                'title'     => 'max:80|type:text',
                'link'      => 'max:80|type:UrlPages',
            ];
            if($valid->check()){ // $valid->check()
                $notifications->NotificationId  = $id;
                $notifications->Title           = self::getPost('title') != '' ? self::getPost('title') : sprintf($this->Language->get('notification_in_user'),ProfileModel::get_name_user_login());
                $notifications->Content         = $this->getPost('content');
                $notifications->Type            = 2;
                $notifications->Link            = $this->getPost('link');
                if($notifications->update()){
                    Messenger::getInstance()->create($this->Language->get('success_notification_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }


        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_notifications','NotificationId')){
                $this->Data['Notification']     = $notifications->getByKey($id);
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_notification_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Notifications/');
            }
        }else{
            self::redirect('/Notifications/');
        }


        $this->View();
    }

    public function deleteAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_notifications','NotificationId')){
            $notifications = new NotificationsModel();
            NotificationsUsersModel::deleteByNot($id);
            $notifications->NotificationId = $id;
            if($notifications->delete())
            {
                Messenger::getInstance()->create($this->Language->get('success_notification_delete'));
                self::redirect('/Notifications/');
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_notification_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Notifications/');
        }
    }

    public function ShowedNotificationAction()
    {
        $id = self::post('Id');
        if (!is_numeric($id) && !Validate::valid($id,'int')) self::redirect('/');
        NotificationsUsersModel::Showed($id);
    }

    public function ViewAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('notifications.default');

        if (self::is_get('id') && is_numeric(self::get('id')))
        {
            $id = self::get('id');
            if (!NotificationsUsersModel::getNotBy($id)) self::redirect('/');
            $this->Data['Notification'] = NotificationsUsersModel::getNotBy($id);
            NotificationsUsersModel::Showed($id);
            NotificationsUsersModel::Viewed($id);

        }else{
            self::redirect('/');
        }
        $this->View();
    }


}