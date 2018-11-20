<?php

namespace Store\Controllers;

use Store\Core\Messenger;
use Store\core\Upload;
use Store\Core\Validate;
use Store\Models\ProfileModel;

class ProfileController extends AbsController
{

    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('profile.default');
        $profile = new ProfileModel();
        $Id = $this->Session->User->UserId;
        $this->Data['Profile'] = $profile->getbyKey($Id);

        if(self::has_files('image') && self::has_post('image-data'))
        {
            // Valid file

            $upload = new Upload(self::getFiles('image'),$this->Language);
            $upload->image_data = self::getPost('image-data');
            $upload->rules = ['image' => 'accept:png,gif,jpg,jpeg|max:4194304'];



            if($upload->Process()){ // $valid->check()
                if($upload->Upload())
                {
                    $ProductsModel = new ProfileModel();
                    $id = $this->Session->User->UserId;
                    $ProductsModel->UserId = $id;
                    $ProductsModel->Image = $upload->file_name;
                    if(ProfileModel::table('app_users_profile')->exist('UserId',$id)){
                        if($upload->delete_file($profile->getbyKey($Id)->Image))
                        {
                            if($ProductsModel->update_image()){
                                Messenger::getInstance()->create($this->Language->get('success_profile_updated'),Messenger::APP_TYPE_SUCCESS);
                                $this->clear_request('image-data');
                            }
                        }

                    }elseif($ProductsModel->create()){
                        Messenger::getInstance()->create($this->Language->get('success_profile_added'),Messenger::APP_TYPE_SUCCESS);
                        $this->clear_request('image-data');
                    }
                }
            }




        }

        if(self::has_post('submit'))
        {

            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'first_name'=> 'required|max:15|min:3|type:words',
                'last_name' => 'required|max:20|min:3|type:words',
                'DOB'       => 'type:date',
                'address'   => 'max:60|min:6'
            ];
            if($valid->check()){ // $valid->check()
                $ProductsModel = new ProfileModel();
                $id = $this->Session->User->UserId;
                $ProductsModel->UserId = $id;
                $ProductsModel->FirstName = $this->getPost('first_name');
                $ProductsModel->LastName = $this->getPost('last_name');
                $ProductsModel->Address = $this->getPost('address');
                $ProductsModel->DOB = $this->getPost('DOB');
                if(ProfileModel::table('app_users_profile')->exist('UserId',$id)){
                    if($ProductsModel->update()){
                        Messenger::getInstance()->create($this->Language->get('success_profile_updated'),Messenger::APP_TYPE_SUCCESS);
                        $this->clear_request();

                    }
                }elseif($ProductsModel->create()){
                    Messenger::getInstance()->create($this->Language->get('success_profile_added'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }


        $this->View();
    }

    public function notPermissionAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->View();
    }

}