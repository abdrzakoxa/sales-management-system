<?php

namespace Store\Controllers;


use Store\Core\Messenger;
use Store\Core\Validate;
use Store\Models\ProductscategoriesModel;
use Store\Models\SuppliersModel;

class ProductsCategoriesController extends AbsController
{
    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('ProductsCategories.default');
        $ProductsCategories = new ProductsCategoriesModel();
        $this->Data['ProductsCategories'] = $ProductsCategories->getAll();
        $this->View();
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('ProductsCategories.create');
        $this->Language->load('ProductsCategories.label');
        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'name'          => 'required|max:40|min:3|type:words|unique:app_products_categories',
                'description'   => 'max:5000|type:text',
            ];
            if($valid->check()){ // $valid->check()
                $ProductsCategories = new ProductsCategoriesModel();
                $ProductsCategories->Name = $this->getPost('name');
                $ProductsCategories->Description = $this->getPost('description');
                if($ProductsCategories->create()){ /// $usersModel->create()
                    Messenger::getInstance()->create($this->Language->get('success_productsCategories_added'),Messenger::APP_TYPE_SUCCESS);
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
        $this->Language->load('ProductsCategories.label');
        $this->Language->load('ProductsCategories.edit');
        $SuppliersModel = new ProductsCategoriesModel();
        $id = self::getGet('id');


        if(self::has_post('submit') && Validate::valid($id,Validate::REGEX_INT)){
            $valid = new Validate($this->Language);
            $valid->primary = ['ProductCategoryId'];
            $valid->data = $_POST;
            $valid->rules = [
                'name'          => 'required|max:25|min:3|type:words|same_unq:app_products_categories',
                'description'   => 'max:5000|type:text',
            ];
            if($valid->check()){ // $valid->check()
                $SuppliersModel->ProductCategoryId = $this->getGet('id');
                $SuppliersModel->Name = $this->getPost('name');
                $SuppliersModel->Description = $this->getPost('description');
                if($SuppliersModel->update()){
                    Messenger::getInstance()->create($this->Language->get('success_productsCategories_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }


        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_products_categories','ProductCategoryId')){
                $this->Data['ProductCategory'] = $SuppliersModel->getByKey($id);
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_productCategory_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/ProductsCategories/');
            }
        }else{
            self::redirect('/ProductsCategories/');
        }


        $this->View();
    }

    public function deleteAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_products_categories','ProductCategoryId')){
            $ProductCategoryModel = new ProductsCategoriesModel();
            $ProductCategoryModel->ProductCategoryId = $id;
            if($ProductCategoryModel->delete())
            {
                Messenger::getInstance()->create($this->Language->get('success_productsCategories_delete'));
                self::redirect('/ProductsCategories/');
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_productCategory_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/ProductsCategories/');
        }
    }




    public function existAction()
    {
        if(count($_POST) > 0 & self::has_post(key($_POST)))
        {
            $valueOld = isset($_POST['valueOld']) ? $_POST['valueOld'] : false ;
            echo SuppliersModel::table('app_products_categories')->exist(key($_POST),self::getPost(key($_POST)),$valueOld);
        }
    }
}