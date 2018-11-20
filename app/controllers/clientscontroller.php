<?php

namespace Store\Controllers;


use Store\Core\Messenger;
use Store\Core\Validate;
use Store\Models\ClientsModel;

class ClientsController extends AbsController
{
    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('clients.default');
        $client = new ClientsModel();
        $this->Data['Clients'] = $client->getAll();
        $this->View();
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('clients.label');
        $this->Language->load('clients.create');
        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'firstName' => 'required|max:15|min:3|type:words',
                'lastName'  => 'required|max:20|min:3|type:words',
                'email'     => 'required|max:50|min:6|type:email|unique:app_clients',
                'phone'     => 'required|max:18|min:6|type:phone|unique:app_clients',
                'address'   => 'required|max:60|min:6|type:address'
            ];
            if($valid->check()){ // $valid->check()
                $ClientsModel = new ClientsModel();
                $ClientsModel->FirstName = $this->getPost('firstName');
                $ClientsModel->LastName = $this->getPost('lastName');
                $ClientsModel->Phone = $this->getPost('phone');
                $ClientsModel->Email = $this->getPost('email');
                $ClientsModel->Address = $this->getPost('address');
                if($ClientsModel->create()){ /// $usersModel->create()
                    Messenger::getInstance()->create($this->Language->get('success_client_added'),Messenger::APP_TYPE_SUCCESS);
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
        $this->Language->load('Clients.label');
        $this->Language->load('Clients.edit');
        $ClientsModel = new ClientsModel();
        $id = self::getGet('id');


        if(self::has_post('submit') && Validate::valid($id,Validate::REGEX_INT)){
            $valid = new Validate($this->Language);
            $valid->primary = ['clientId'];
            $valid->data = $_POST;
            $valid->rules = [
                'firstName' => 'required|max:15|min:3|type:words',
                'lastName'  => 'required|max:20|min:3|type:words',
                'email'     => 'required|max:50|min:6|type:email|same_unq:app_clients',
                'phone'     => 'required|max:18|min:6|type:phone|same_unq:app_clients',
                'address'   => 'required|max:60|min:6|type:address'
            ];
            if($valid->check()){ // $valid->check()
                $ClientsModel->ClientId = $this->getGet('id');
                $ClientsModel->FirstName = $this->getPost('firstName');
                $ClientsModel->LastName = $this->getPost('lastName');
                $ClientsModel->Phone = $this->getPost('phone');
                $ClientsModel->Email = $this->getPost('email');
                $ClientsModel->Address = $this->getPost('address');
                if($ClientsModel->update()){
                    Messenger::getInstance()->create($this->Language->get('success_user_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }


        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_clients','ClientId')){
                $this->Data['Client'] = $ClientsModel->getByKey($id);
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_client_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Clients/');
            }
        }else{
            self::redirect('/Clients/');
        }


        $this->View();
    }

    public function deleteAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_Clients','ClientId')){
            $ClientsModel = new ClientsModel();
            $ClientsModel->ClientId = $id;
            if($ClientsModel->delete())
            {
                Messenger::getInstance()->create($this->Language->get('success_client_delete'));
                self::redirect('/Clients/');
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_client_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Clients/');
        }
    }




    public function existAction()
    {
        if(count($_POST) > 0 & self::has_post(key($_POST)))
        {
            $valueOld = isset($_POST['valueOld']) ? $_POST['valueOld'] : false ;
            echo ClientsModel::table('app_Clients')->exist(key($_POST),self::getPost(key($_POST)),$valueOld);
        }
    }
}