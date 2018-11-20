<?php

namespace Store\Controllers;


use Store\Core\DB;
use Store\Core\Messenger;
use Store\Core\Validate;
use Store\Models\ProductsModel;
use Store\Models\PurchasesInvoicesModel;
use Store\Models\PurchasesModel;
use Store\Models\SuppliersModel;

class PurchasesController extends AbsController
{
    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.countries');
        $this->Language->load('purchases.default');
        $purchases = new PurchasesInvoicesModel();
        $this->Data['Purchases'] = $purchases->inner_join();
        $this->View();
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('template.countries');
        $this->Language->load('purchases.label');
        $this->Language->load('purchases.create');

        $Suppliers = new SuppliersModel();
        $Products = new ProductsModel();
        $this->Data['Suppliers'] = $Suppliers->getAll();
        $this->Data['Products'] = $Products->inner_join();

        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'payment_type'  => 'required|list:1,2,3',
                'discount'      => 'required|max:24|type:discount',
                'supplier_name' => 'required|foreign:app_suppliers.SupplierId',
                'product_name'  => 'required|foreign:app_products.ProductId',
                'product_id'    => 'required|is_array|foreign:app_products.ProductId|post_unq:product',
                'quantity'      => 'required|is_array|type:int|max:9999999',
                'price'         => 'required|is_array|type:alpha_decimal|max:25',
            ];
            if($valid->check()){

                $PurchasesInvoicesModel = new PurchasesInvoicesModel();
                $PurchasesInvoicesModel->PaymentType = $this->getPost('payment_type');
                $PurchasesInvoicesModel->PaymentStatus = 0;
                $PurchasesInvoicesModel->Discount = self::decimal_insert($this->getPost('discount'));
                $PurchasesInvoicesModel->SupplierId = $this->getPost('supplier_name');
                $PurchasesInvoicesModel->UserId = $this->Session->User->UserId;

                if($PurchasesInvoicesModel->create()){
                    $id = DB::connect()->lastInsertId() ;
                    $PurchasesModel = new PurchasesModel();
                    $ProductsModel = new ProductsModel();
                    $hasErorr = false;
                    foreach ($this->getPost('product_id') as $key => $product){
                        $PurchasesModel->ProductId = $product;
                        $PurchasesModel->PurchasePrice = $_POST['price'][$key];
                        $PurchasesModel->QuantityPurchases = $_POST['quantity'][$key];
                        if($_POST['quantity'][$key] > 0)
                        {
                            $ProductsModel->ProductId = $product;
                            $ProductsModel->IncreaseQuantity($_POST['quantity'][$key]);
                        }
                        $PurchasesModel->InvoiceId = $id;
                        if(!$PurchasesModel->create()){
                            $hasErorr = true;
                        }
                    }
                    if(!$hasErorr)
                    {
                        Messenger::getInstance()->create($this->Language->get('success_purchase_added'),Messenger::APP_TYPE_SUCCESS);
                        $this->clear_request();
                    }
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
        $this->Language->load('purchases.label');
        $this->Language->load('purchases.edit');
        $PurchasesInvoicesModel = new PurchasesInvoicesModel();
        $PurchasesModel = new PurchasesModel();
        $Suppliers = new SuppliersModel();
        $Products = new ProductsModel();
        $id = self::getGet('id');
        $this->Data['Suppliers'] = $Suppliers->getAll();
        $this->Data['Products'] = $Products->inner_join();




        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_purchases_invoices','InvoiceId')){
                $this->Data['PurchasesInvoices'] = $PurchasesInvoicesModel->getByKey($id);
                $this->Data['Purchases'] = $PurchasesModel->getByCols(['InvoiceId'=>$this->Data['PurchasesInvoices']->InvoiceId]);
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_purchase_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Purchases/');
            }
        }
        else{
            self::redirect('/Purchases/');
        }



        if(self::has_post('submit') && Validate::valid($id,Validate::REGEX_INT)){
            $valid = new Validate($this->Language);
            $valid->primary = ['InvoiceId'];
            $valid->data = $_POST;
            $valid->rules = [
                'payment_type'  => 'required|list:1,2,3',
                'payment_status'=> 'required|list:0,1',
                'discount'      => 'required|max:9|type:discount',
                'supplier_name' => 'required|foreign:app_suppliers.SupplierId',
                'product_id'    => 'required|is_array|foreign:app_products.ProductId|post_unq:product',
                'quantity'      => 'required|is_array|type:int|max:9999999|custom_product:app_products.product_id',//|custom_max_col:app_products.product_id
                'price'         => 'required|is_array|type:alpha_decimal|max:10',
            ];

            if($valid->check()){ // $valid->check()
                $PurchasesInvoicesModel = new PurchasesInvoicesModel();
                $PurchasesInvoicesModel->InvoiceId = $this->getGet('id');
                $PurchasesInvoicesModel->PaymentType = $this->getPost('payment_type');
                $PurchasesInvoicesModel->PaymentStatus = $this->getPost('payment_status');
                $PurchasesInvoicesModel->Discount = self::decimal_insert($this->getPost('discount'));
                $PurchasesInvoicesModel->SupplierId = $this->getPost('supplier_name');
                $PurchasesInvoicesModel->UserId = $this->Session->User->UserId;
                if($PurchasesInvoicesModel->update()){
                    $PurchasesModel = new PurchasesModel();
                    $ProductsModel = new ProductsModel();

                    $old_purchases = self::ready_array_purchases($this->Data['Purchases']);
                    $new_purchases = ['products'=>$_POST['product_id'],'quantity'=>$_POST['quantity'],'price'=>$_POST['price']];

                    $purchases_add = $this->array_diff_multiple($old_purchases,$new_purchases);

                    $purchases_delete = $this->get_array_diff_removed($old_purchases,$new_purchases);


                    if(!empty($purchases_add))
                    {
                        $PurchasesModel->InvoiceId = $this->getGet('id');
                        foreach ($purchases_add['products'] as $key => $purchase_add) {
                            $PurchasesModel->ProductId          = $purchase_add;
                            $PurchasesModel->QuantityPurchases  = $purchases_add['quantity'][$key];
                            $PurchasesModel->PurchasePrice      = $purchases_add['price'][$key];


                            if($purchases_add['quantity'][$key] > 0)
                            {
                                $ProductsModel->ProductId = $purchase_add;
                                $ProductsModel->IncreaseQuantity($purchases_add['quantity'][$key]);
                            }
                            $PurchasesModel->create();


                        }
                    }


                    if(!empty($purchases_delete))
                    {
                        foreach ($purchases_delete['products'] as $key => $product_delete) {
                            $PurchasesModel->PurchaseId = $PurchasesModel->getByCols(['ProductId'=>$product_delete,'InvoiceId'=>$this->getGet('id')])[0]->PurchaseId;

                            if($purchases_delete['quantity'][$key] > 0)
                            {
                                $ProductsModel->ProductId          = $product_delete;
                                $ProductsModel->ReduceQuantity($purchases_delete['quantity'][$key]);
                            }
                            $PurchasesModel->Delete();

                        }
                    }


                    Messenger::getInstance()->create($this->Language->get('success_purchase_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }




        $this->View();

    }

    public function deleteAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_purchases_invoices','InvoiceId')){
            $PurchasesModel = new PurchasesModel();
            $ProductsModel = new ProductsModel();
            $PurchasesInvoicesModel = new PurchasesInvoicesModel();
            $PurchasesInvoicesModel->InvoiceId = $id;
            $PaymentStatus = $PurchasesInvoicesModel->getByKey($id)->PaymentStatus;
            if($PaymentStatus == (int) 1)
            {
                if($PurchasesInvoicesModel->delete())
                {
                    Messenger::getInstance()->create($this->Language->get('success_purchase_delete'));
                    self::redirect('/Purchases/');
                }
            }else{
                $Purchases = $PurchasesModel->getByCols(['InvoiceId'=>$id]);
                $error = false;
                foreach ($Purchases as $Purchase) {
                    if($Purchase->QuantityPurchases > 0)
                    {
                        $ProductsModel->ProductId = $Purchase->ProductId;
                        if (!$ProductsModel->ReduceQuantity($Purchase->QuantityPurchases))
                        {
                            $error = true;
                        }
                    }
                }
                if(!$error)
                {
                    if($PurchasesInvoicesModel->delete())
                    {
                        Messenger::getInstance()->create($this->Language->get('success_purchase_delete'));
                        self::redirect('/Purchases/');
                    }
                }else{
                    Messenger::getInstance()->create($this->Language->get('warning_cant_delete_purchase'),Messenger::APP_TYPE_WARNING);
                    self::redirect('/Purchases/');
                }
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_purchase_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Purchases/');
        }
    }


    public function existAction()
    {
        if(count($_POST) > 0 & self::has_post(key($_POST)))
        {
            $valueOld = isset($_POST['valueOld']) ? $_POST['valueOld'] : false ;
            echo PurchasesInvoicesModel::table('app_purchases_invoices')->exist(key($_POST),self::getPost(key($_POST)),$valueOld);
        }
    }
}