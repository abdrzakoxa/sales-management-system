<?php

namespace Store\Controllers;


use Store\Core\Messenger;
use Store\Core\Validate;
use Store\Models\ExpensesCategoriesModel;
use Store\Models\ExpensesModel;

class ExpensesCategoriesController extends AbsController
{
    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('ExpensesCategories.default');
        $ExpensesCategories = new ExpensesCategoriesModel();
        $this->Data['ExpensesCategories'] = $ExpensesCategories->getAll();
        $this->View();
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('ExpensesCategories.create');
        $this->Language->load('ExpensesCategories.label');
        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'type'           => 'required|max:25|min:3|type:words|unique:app_expenses_categories',
                'fixedPayment'   => 'max:25|type:alpha_decimal',
            ];
            if($valid->check()){ // $valid->check()
                $ExpensesCategories = new ExpensesCategoriesModel();
                $ExpensesCategories->Type = $this->getPost('type');
                $ExpensesCategories->FixedPayment = $this->Currency->inside_currency($this->getPost('fixedPayment'));
                if($ExpensesCategories->create()){ /// $usersModel->create()
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
        $this->Language->load('ExpensesCategories.label');
        $this->Language->load('ExpensesCategories.edit');
        $ExpensesCategories = new ExpensesCategoriesModel();
        $id = self::getGet('id');


        if(self::has_post('submit') && Validate::valid($id,Validate::REGEX_INT)){
            $valid = new Validate($this->Language);
            $valid->primary = ['ProductCategoryId'];
            $valid->data = $_POST;
            $valid->rules = [
                'type'           => 'required|max:25|min:3|type:words|same_unq:app_expenses_categories',
                'fixedPayment'   => 'max:25|type:alpha_decimal',
            ];
            if($valid->check()){ // $valid->check()
                $ExpensesCategories = new ExpensesCategoriesModel();
                $ExpensesCategories->ExpenseCategoryId = $this->getGet('id');
                $ExpensesCategories->Type = $this->getPost('type');
                $ExpensesCategories->FixedPayment = $this->Currency->inside_currency($this->getPost('fixedPayment'));
                if($ExpensesCategories->update()){
                    Messenger::getInstance()->create($this->Language->get('success_productsCategories_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }


        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_expenses_categories','ExpenseCategoryId')){
                $this->Data['ExpenseCategory'] = $ExpensesCategories->getByKey($id);
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_productCategory_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/ExpensesCategories/');
            }
        }else{
            self::redirect('/ExpensesCategories/');
        }


        $this->View();
    }

    public function deleteAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_expenses_categories','expenseCategoryId')){
            $ExpenseCategoryModel = new ExpensesCategoriesModel();
            $ExpenseCategoryModel->ExpenseCategoryId = $id;
            if($ExpenseCategoryModel->delete())
            {
                Messenger::getInstance()->create($this->Language->get('success_productsCategories_delete'));
                self::redirect('/ExpensesCategories/');
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_productCategory_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/ExpensesCategories/');
        }
    }




    public function existAction()
    {
        if(count($_POST) > 0 & self::has_post(key($_POST)))
        {
            $valueOld = isset($_POST['valueOld']) ? $_POST['valueOld'] : false ;
            echo ExpensesModel::table('app_products_categories')->exist(key($_POST),self::getPost(key($_POST)),$valueOld);
        }
    }
}