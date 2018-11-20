<?php

namespace Store\Controllers;


use Store\Core\DB;
use Store\Core\Messenger;
use Store\core\Notifications;
use Store\Core\Validate;
use Store\Models\ProductsModel;
use Store\Models\SalesInvoicesModel;
use Store\Models\SalesModel;
use Store\Models\ClientsModel;

class SalesController extends AbsController
{
    use Notifications;
    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.countries');
        $this->Language->load('sales.default');
        $sales = new SalesInvoicesModel();
        $this->Data['Sales'] = $sales->inner_join();
        $this->View();
    }

    public function createAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('template.countries');
        $this->Language->load('sales.label');
        $this->Language->load('sales.create');

        $Clients = new ClientsModel();
        $Products = new ProductsModel();
        $barcode = isset($_GET['barcode']) && ( is_numeric($_GET['barcode']) || is_string($_GET['barcode']) ) ? $_GET['barcode'] : false;
        $this->Data['Clients'] = $Clients->getAll();
        $this->Data['Products'] = $Products->inner_join();

        $productId = empty($Products->getsBy('Barcode',$barcode,'ProductId')[0]->ProductId) ? false : $Products->getsBy('Barcode',$barcode,'ProductId')[0]->ProductId;
        $this->Data['Barcode'] = $productId ? $Products->inner_join('ProductId',$productId) : false;

        if (empty($this->Data['Barcode']) && !empty($barcode))
        {
            Messenger::getInstance()->create($this->Language->get('warning_product_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Sales/Create/');
        }

        if($this->has_post('submit')){

            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'payment_type'  => 'required|list:1,2,3',
                'discount'      => 'required|max:25|type:discount',
                'client_name'   => 'required|foreign:app_clients.ClientId',
                'product_name'  => 'required|foreign:app_products.ProductId',
                'product_id'    => 'required|is_array|foreign:app_products.ProductId|post_unq:product',
                'quantity'      => 'required|is_array|type:int|max:9999999',
                'price'         => 'required|is_array|type:alpha_decimal|max:25',
            ];
            if($valid->check()){ // $valid->check()

                $SalesInvoicesModel = new SalesInvoicesModel();
                $SalesInvoicesModel->PaymentType = $this->getPost('payment_type');
                $SalesInvoicesModel->PaymentStatus = 0;
                $SalesInvoicesModel->Discount = self::decimal_insert($this->getPost('discount'));
                $SalesInvoicesModel->ClientId = $this->getPost('client_name');
                $SalesInvoicesModel->UserId = $this->Session->User->UserId;

                if($SalesInvoicesModel->create()){ //$SalesInvoicesModel->create()
                    $id = DB::connect()->lastInsertId() ;
                    $SalesModel = new SalesModel();
                    $ProductsModel = new ProductsModel();
                    $hasErorr = false;
                    foreach (self::post('product_id') as $key => $product){
                        $SalesModel->ProductId = $product;
                        $SalesModel->SellPrice = $this->Currency->inside_currency($_POST['price'][$key]);
                        $SalesModel->QuantitySales = $_POST['quantity'][$key];
                        if(isset($_POST['quantity'][$key]))
                        {
                            $ProductsModel->ProductId = $product;
                            $SalesModel->InvoiceId = $id;
                            if($ProductsModel->ReduceQuantity($_POST['quantity'][$key]))
                            {
                                if(!$SalesModel->create()){
                                    $hasErorr = true;
                                }else{
                                    $this->finished_product($product);
                                }
                            }else{
                                $hasErorr = true;
                                Messenger::getInstance()->create($this->Language->get('error_quantity'),Messenger::APP_TYPE_ERROR);
                            }
                        }

                    }
                    if(!$hasErorr)
                    {
                        Messenger::getInstance()->create($this->Language->get('success_sale_added'),Messenger::APP_TYPE_SUCCESS);
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
        $this->Language->load('sales.label');
        $this->Language->load('sales.edit');
        $SalesInvoicesModel = new SalesInvoicesModel();
        $SalesModel = new SalesModel();
        $Clients = new ClientsModel();
        $Products = new ProductsModel();
        $id = self::getGet('id');
        $this->Data['Clients'] = $Clients->getAll();
        $this->Data['Products'] = $Products->inner_join();




        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_sales_invoices','InvoiceId')){
                $this->Data['SalesInvoices'] = $SalesInvoicesModel->getByKey($id);
                $this->Data['Sales'] = $SalesModel->getByCols(['InvoiceId'=>$this->Data['SalesInvoices']->InvoiceId]);
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_purchase_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Sales/');
            }
        }
        else{
            self::redirect('/Sales/');
        }



        if(self::has_post('submit') && Validate::valid($id,Validate::REGEX_INT)){
            $valid = new Validate($this->Language);
            $valid->primary = ['InvoiceId'];
            $valid->options['Sales'] = $this->Data['Sales'];
            $valid->data = $_POST;
            $valid->rules = [
                'payment_type'  => 'required|list:1,2,3',
                'payment_status'=> 'required|list:0,1',
                'discount'      => 'required|max:9|type:discount',
                'client_name'   => 'required|foreign:app_clients.ClientId',
                'product_id'    => 'required|is_array|foreign:app_products.ProductId|post_unq:product',
                'quantity'      => 'required|is_array|type:quantity|max:22|custom_sales:app_products.ProductId',
                'price'         => 'required|is_array|type:alpha_decimal|max:25',
            ];

            if($valid->check()){ // $valid->check()
                $SalesInvoicesModel = new SalesInvoicesModel();
                $SalesInvoicesModel->InvoiceId = $this->getGet('id');
                $SalesInvoicesModel->PaymentType = $this->getPost('payment_type');
                $SalesInvoicesModel->PaymentStatus = $this->getPost('payment_status');
                $SalesInvoicesModel->Discount = self::decimal_insert($this->getPost('discount'));
                $SalesInvoicesModel->ClientId = $this->getPost('client_name');
                $SalesInvoicesModel->UserId = $this->Session->User->UserId;
                if($SalesInvoicesModel->update()){
                    $SalesModel = new SalesModel();
                    $ProductsModel = new ProductsModel();

                    $old_sales = self::ready_array_sales($this->Data['Sales']);
                    $new_sales = ['products'=>$_POST['product_id'],'quantity'=>$_POST['quantity'],'price'=>$_POST['price']];

                    $sales_add = $this->array_diff_multiple($old_sales,$new_sales);

                    $sales_delete = $this->get_array_diff_removed($old_sales,$new_sales);


                    if(!empty($sales_delete))
                    {

                        foreach ($sales_delete['products'] as $key => $product_delete) {
                            $SalesModel->SaleId = $SalesModel->getByCols(['ProductId'=>$product_delete,'InvoiceId'=>self::get('id')])[0]->SaleId;
                            if($sales_delete['quantity'][$key] > 0)
                            {
                                $ProductsModel->ProductId          = $product_delete;
                                $ProductsModel->IncreaseQuantity($sales_delete['quantity'][$key]); // up
                            }
                            $SalesModel->Delete();
                        }
                    }

                    if(!empty($sales_add))
                    {
                        $SalesModel->InvoiceId = $this->getGet('id');
                        foreach ($sales_add['products'] as $key => $purchase_add) {
                            $SalesModel->ProductId          = $purchase_add;
                            $SalesModel->QuantitySales  = $sales_add['quantity'][$key];


                            if($_POST['quantity'][$key] > 0)
                            {
                                $ProductsModel->ProductId          = $purchase_add;
                                $ProductsModel->ReduceQuantity($sales_add['quantity'][$key]); // up
                            }


                            $SalesModel->SellPrice      = $this->Currency->inside_currency($sales_add['price'][$key]);
                            if ($SalesModel->create()) {
                                $this->finished_product($purchase_add);
                            }
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
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_sales_invoices','InvoiceId')){
            $SalesModel = new SalesModel();
            $ProductsModel = new ProductsModel();
            $SalesInvoicesModel = new SalesInvoicesModel();
            $SalesInvoicesModel->InvoiceId = $id;
            $PaymentStatus = $SalesInvoicesModel->getByKey($id)->PaymentStatus;
            if($PaymentStatus == (int) 1)
            {
                if($SalesInvoicesModel->delete())
                {
                    Messenger::getInstance()->create($this->Language->get('success_purchase_delete'));
                    self::redirect('/Sales/');
                }
            }else{
                $Sales = $SalesModel->getByCols(['InvoiceId'=>$id]);
                $error = false;
                foreach ($Sales as $Sale) {
                    if($Sale->QuantitySales > 0)
                    {
                        $ProductsModel->ProductId = $Sale->ProductId;
                        if (!$ProductsModel->IncreaseQuantity($Sale->QuantitySales)) // up
                        {
                            $error = true;
                        }
                    }
                }
                if(!$error)
                {
                    if($SalesInvoicesModel->delete())
                    {
                        Messenger::getInstance()->create($this->Language->get('success_purchase_delete'));
                        self::redirect('/Sales/');
                    }
                }
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_purchase_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Sales/');
        }
    }

    public function previewAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('sales.preview');
        $id = self::getGet('id');
        if(self::has_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_sales_invoices','InvoiceId'))
        {
            $invoice = new SalesInvoicesModel();
            $products = new SalesModel();
            $client = new ClientsModel();
            $this->Data['invoice'] = $invoice->getBy('invoiceId',$id);
            $this->Data['products'] = $products->inner_join('invoiceId',$id);
            $this->Data['client'] = $client->getByKey($this->Data['invoice']->ClientId);

        }else{
            Messenger::getInstance()->create($this->Language->get('warning_purchase_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Sales/');
        }
        $this->View();

    }




    public function existAction()
    {
        if(count($_POST) > 0 & self::has_post(key($_POST)))
        {
            $valueOld = isset($_POST['valueOld']) ? $_POST['valueOld'] : false ;
            echo SalesInvoicesModel::table('app_sales_invoices')->exist(key($_POST),self::getPost(key($_POST)),$valueOld);
        }
    }
}