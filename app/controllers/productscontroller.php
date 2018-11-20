<?php

namespace Store\Controllers;


use Store\Core\Messenger;
use Store\Core\Validate;
use Store\Models\ProductsCategoriesModel;
use Store\Models\ProductsModel;
use Store\Models\UnitsModel;

class ProductsController extends AbsController
{
    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.countries');
        $this->Language->load('products.default');
        $products = new ProductsModel();
        $this->Data['Products'] = $products->inner_join();
        $this->View();
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('template.countries');
        $this->Language->load('products.label');
        $this->Language->load('products.create');
        $Categories = new ProductsCategoriesModel();
        $Units = new UnitsModel();
        $this->Data['Categories'] = $Categories->getAll();
        $this->Data['Units'] = $Units->getAll();


        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'title'         => 'required|max:240|min:3|type:text',
                'madeCountry'   => 'max:40|key_exists:countries',
                'quantity'      => 'required|max:23|type:quantity',
                'notification_quantity'=> 'max:23|type:quantity',
                'barcode'       => 'require|max:44|min:1|type:barcode|unique:app_products',
                'sell_price'    => 'required|max:25|type:alpha_decimal',
                'buy_price'     => 'required|max:25|type:alpha_decimal',
                'unit_id'       => 'required|foreign:app_units.UnitId',
                'tax'           => 'max:25|type:discount',
                'categoryId'    => 'required|foreign:app_products_categories.ProductCategoryId',
            ];
            if($valid->check()){ // $valid->check()
                $ProductsModel = new ProductsModel();
                $ProductsModel->Title = $this->getPost('title');
                $ProductsModel->MadeCountry = $this->getPost('madeCountry');
                $ProductsModel->Quantity = $this->getPost('quantity');
                $ProductsModel->NotificationQuantity = $this->getPost('notification_quantity');
                $ProductsModel->Tax = self::decimal_insert($this->getPost('tax'));
                $ProductsModel->UnitId = $this->getPost('unit_id');
                $ProductsModel->Barcode = $this->getPost('barcode');
                $ProductsModel->SellPrice = $this->Currency->inside_currency($this->getPost('sell_price'));
                $ProductsModel->BuyPrice = $this->Currency->inside_currency($this->getPost('buy_price'));
                $ProductsModel->CategoryId = $this->getPost('categoryId');
                if($ProductsModel->create()){ /// $usersModel->create()
                    Messenger::getInstance()->create($this->Language->get('success_product_added'),Messenger::APP_TYPE_SUCCESS);
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
        $this->Language->load('products.label');
        $this->Language->load('products.edit');
        $ProductsModel = new ProductsModel();
        $Categories = new ProductsCategoriesModel();
        $id = self::getGet('id');
        $Units = new UnitsModel();
        $this->Data['Units'] = $Units->getAll();


        if(self::has_post('submit') && Validate::valid($id,Validate::REGEX_INT)){
            $valid = new Validate($this->Language);
            $valid->primary = ['ProductId'=>$id];
            $valid->data = $_POST;
            $valid->rules = [
                'title'         => 'required|max:50|min:3|type:text',
                'madeCountry'   => 'max:40|key_exists:countries',
                'quantity'      => 'required|max:23|type:quantity',
                'notification_quantity'=> 'max:23|type:quantity',
                'tax'           => 'max:25|type:discount',
                'barcode'       => 'require|max:44|min:1|type:barcode|same_unq:app_products',
                'sell_prince'   => 'max:25|type:alpha_decimal',
                'buy_prince'   => 'max:25|type:alpha_decimal',
                'unit_id'       => 'required|foreign:app_units.UnitId',
                'categoryId'    => 'required|foreign:app_products_categories.ProductCategoryId',
            ];
            if($valid->check()){ // $valid->check()
                $ProductsModel->ProductId = $this->getGet('id');
                $ProductsModel->Title = $this->getPost('title');
                $ProductsModel->MadeCountry = $this->getPost('madeCountry');
                $ProductsModel->CategoryId  = $this->getPost('categoryId');
                $ProductsModel->SellPrice   = $this->Currency->inside_currency($this->getPost('sell_price'));
                $ProductsModel->BuyPrice    = $this->Currency->inside_currency($this->getPost('buy_price'));
                $ProductsModel->UnitId      = $this->getPost('unit_id');
                $ProductsModel->Barcode     = $this->getPost('barcode');
                $ProductsModel->Quantity    = $this->getPost('quantity');
                $ProductsModel->NotificationQuantity = $this->getPost('notification_quantity');
                $ProductsModel->Tax         = self::decimal_insert($this->getPost('tax'));
                if($ProductsModel->update()){
                    Messenger::getInstance()->create($this->Language->get('success_product_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }


        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_products','ProductId')){
                $this->Data['Products']     = $ProductsModel->getByKey($id);
                $this->Data['Categories']   = $Categories->getAll();
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_product_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Products/');
            }
        }else{
            self::redirect('/Products/');
        }


        $this->View();
    }

    public function deleteAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_products','ProductId')){
            $ProductsModel = new ProductsModel();
            $ProductsModel->ProductId = $id;
            if($ProductsModel->delete())
            {
                Messenger::getInstance()->create($this->Language->get('success_product_delete'));
                self::redirect('/Products/');
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_product_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Products/');
        }
    }



    public function previewAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('products.preview');
        $this->Language->load('products.label');
        $this->Language->load('template.countries');

        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_products','ProductId'))
        {
            $products = new ProductsModel();
            $this->Data['products'] = $products->inner_join('ProductId',$id);

        }else{
            Messenger::getInstance()->create($this->Language->get('warning_product_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Products/');
        }
        $this->View();

    }


    public function existAction()
    {
        if(count($_POST) > 0 & self::has_post(key($_POST)))
        {
            $valueOld = isset($_POST['valueOld']) ? $_POST['valueOld'] : false ;
            echo ProductsModel::table('app_products')->exist(key($_POST),self::getPost(key($_POST)),$valueOld);
        }
    }
}