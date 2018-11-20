<?php

namespace Store\Controllers;


use Store\Models\ClientsModel;
use Store\Models\ProductsModel;
use Store\Models\PurchasesModel;
use Store\Models\SalesModel;
use Store\Models\SuppliersModel;
use Store\Models\UsersModel;

class DashboardController extends AbsController
{
    public function defaultAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('index.default');
        $this->template_load('navigation|:VIEW');

        $std = new \stdClass();
        $std->Users_count = UsersModel::count();
        $std->Suppliers_count = SuppliersModel::count();
        $std->Clients_count = ClientsModel::count();
        $std->Purchases_count = self::number_zero(PurchasesModel::count_purchases()) ? self::number_zero(PurchasesModel::count_purchases()) : 0;
        $std->Sales_count   = self::number_zero(SalesModel::count_sales())  ? self::number_zero(SalesModel::count_sales()) : 0;
        $std->Products_count = ProductsModel::count();
        $std->LastSales = SalesModel::LastSales(5);
        $this->Data['Info'] = $std ;

        $this->View();
    }
}