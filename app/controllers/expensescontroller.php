<?php

namespace Store\Controllers;


use Store\Core\Messenger;
use Store\Core\Validate;
use Store\Models\ExpensesCategoriesModel;
use Store\Models\ExpensesModel;
use Store\Models\UsersModel;

class ExpensesController extends AbsController
{
    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.countries');
        $this->Language->load('expenses.default');
        $expenses = new ExpensesModel();
        $this->Data['Expenses'] = $expenses->getJoin();
        $this->View();
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('template.countries');
        $this->Language->load('expenses.label');
        $this->Language->load('expenses.create');
        $Categories = new ExpensesCategoriesModel();
        $this->Data['Categories'] = $Categories->getAll();


        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'payment'       => 'max:25|type:alpha_decimal',
                'categoryId'    => 'required|foreign:app_expenses_categories.ExpenseCategoryId',
            ];
            if($valid->check()){ // $valid->check()
                $ExpensesModel = new ExpensesModel();
                $payment = ExpensesCategoriesModel::getbyKey($this->getPost('categoryId'));
                $payment = $payment->FixedPayment;
                $ExpensesModel->Payment     = empty($this->getPost('payment')) ? $payment : $this->Currency->inside_currency($this->getPost('payment'));
                $ExpensesModel->UserId      = $this->Session->User->UserId;
                $ExpensesModel->CategoryId  = $this->getPost('categoryId');
                if($ExpensesModel->create()){
                    Messenger::getInstance()->create($this->Language->get('success_expense_added'),Messenger::APP_TYPE_SUCCESS);
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
        $this->Language->load('template.countries');
        $this->Language->load('expenses.label');
        $this->Language->load('expenses.edit');
        $ExpensesModel = new ExpensesModel();
        $Users = new UsersModel();
        $Categories = new ExpensesCategoriesModel();
        $id = self::getGet('id');


        if(self::has_post('submit') && Validate::valid($id,Validate::REGEX_INT)){
            $valid = new Validate($this->Language);
            $valid->primary = ['ExpenseId'];
            $valid->data = $_POST;
            $valid->rules = [
                'payment'   => 'max:25|type:alpha_decimal',
                'categoryId'=> 'required|foreign:app_expenses_categories.ExpenseCategoryId',
                'UserId'    => 'required|foreign:app_users.UserId',
            ];
            if($valid->check()){ // $valid->check()
                $ExpensesModel->ExpenseId = $this->getGet('id');
                $ExpensesModel->Payment = $this->Currency->inside_currency($this->getPost('payment'));
                $ExpensesModel->UserId = $this->getPost('UserId');
                $ExpensesModel->CategoryId = $this->getPost('categoryId');
                if($ExpensesModel->update()){
                    Messenger::getInstance()->create($this->Language->get('success_expense_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }


        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_expenses','ExpenseId')){
                $this->Data['Expenses']     = $ExpensesModel->getByKey($id);
                $this->Data['Categories']   = $Categories->getAll();
                $this->Data['Users']        = $Users->getAll();
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_expense_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Expenses/');
            }
        }else{
            self::redirect('/Expenses/');
        }


        $this->View();
    }

    public function deleteAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_expenses','ExpenseId')){
            $ExpensesModel = new ExpensesModel();
            $ExpensesModel->ExpenseId = $id;
            if($ExpensesModel->delete())
            {
                Messenger::getInstance()->create($this->Language->get('success_expense_delete'));
                self::redirect('/Expenses/');
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_expense_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Expenses/');
        }
    }




    public function existAction()
    {
        if(count($_POST) > 0 & self::has_post(key($_POST)))
        {
            $valueOld = isset($_POST['valueOld']) ? $_POST['valueOld'] : false ;
            echo ExpensesModel::table('app_expenses')->exist(key($_POST),self::getPost(key($_POST)),$valueOld);
        }
    }
}