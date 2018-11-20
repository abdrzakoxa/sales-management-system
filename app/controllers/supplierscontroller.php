<?php

namespace Store\Controllers;


use Store\Core\Messenger;
use Store\Core\Validate;
use Store\Models\SuppliersModel;

class SuppliersController extends AbsController
{
    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('suppliers.default');
        $supplier = new SuppliersModel();
        $this->Data['Suppliers'] = $supplier->getAll();
        $this->View();
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('suppliers.label');
        $this->Language->load('suppliers.create');
        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'firstName' => 'required|max:15|min:3|type:words',
                'lastName'  => 'required|max:20|min:3|type:words',
                'email'     => 'required|max:50|min:6|type:email|unique:app_suppliers',
                'phone'     => 'required|max:18|min:6|type:phone|unique:app_suppliers',
                'address'   => 'required|max:120|min:6|type:address'
            ];
            if($valid->check()){ // $valid->check()
                $SuppliersModel = new SuppliersModel();
                $SuppliersModel->FirstName = $this->getPost('firstName');
                $SuppliersModel->LastName = $this->getPost('lastName');
                $SuppliersModel->Phone = $this->getPost('phone');
                $SuppliersModel->Email = $this->getPost('email');
                $SuppliersModel->Address = $this->getPost('address');
                if($SuppliersModel->create()){ /// $usersModel->create()
                    Messenger::getInstance()->create($this->Language->get('success_supplier_added'),Messenger::APP_TYPE_SUCCESS);
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
        $this->Language->load('suppliers.label');
        $this->Language->load('suppliers.edit');
        $SuppliersModel = new SuppliersModel();
        $id = self::getGet('id');


        if(self::has_post('submit') && Validate::valid($id,Validate::REGEX_INT)){
            $valid = new Validate($this->Language);
            $valid->primary = ['SupplierId'];
            $valid->data = $_POST;
            $valid->rules = [
                'firstName' => 'required|max:15|min:3|type:words',
                'lastName'  => 'required|max:20|min:3|type:words',
                'email'     => 'required|max:50|min:6|type:email|same_unq:app_suppliers',
                'phone'     => 'required|max:18|min:6|type:phone|same_unq:app_suppliers',
                'address'   => 'required|max:60|min:6|type:address'
            ];
            if($valid->check()){ // $valid->check()
                $SuppliersModel->SupplierId = $this->getGet('id');
                $SuppliersModel->FirstName = $this->getPost('firstName');
                $SuppliersModel->LastName = $this->getPost('lastName');
                $SuppliersModel->Phone = $this->getPost('phone');
                $SuppliersModel->Email = $this->getPost('email');
                $SuppliersModel->Address = $this->getPost('address');
                if($SuppliersModel->update()){
                    Messenger::getInstance()->create($this->Language->get('success_supplier_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }


        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_suppliers','SupplierId')){
                $this->Data['Supplier'] = $SuppliersModel->getByKey($id);
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_supplier_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Suppliers/');
            }
        }else{
            self::redirect('/Suppliers/');
        }


        $this->View();
    }

    public function deleteAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_suppliers','SupplierId')){
            $SuppliersModel = new SuppliersModel();
            $SuppliersModel->SupplierId = $id;
            if($SuppliersModel->delete())
            {
                Messenger::getInstance()->create($this->Language->get('success_supplier_delete'));
                self::redirect('/Suppliers/');
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_supplier_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Suppliers/');
        }
    }




    public function existAction()
    {
        if(count($_POST) > 0 & self::has_post(key($_POST)))
        {
            $valueOld = isset($_POST['valueOld']) ? $_POST['valueOld'] : false ;
            echo SuppliersModel::table('app_suppliers')->exist(key($_POST),self::getPost(key($_POST)),$valueOld);
        }
    }
}