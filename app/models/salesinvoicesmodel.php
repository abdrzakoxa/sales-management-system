<?php

namespace Store\Models;

use Store\Core\DB;
use Store\Core\Helper;
use Store\core\Input;

class SalesInvoicesModel extends AbsModel
{
    public $InvoiceId, $PaymentType , $PaymentStatus, $CreatedDate, $Discount, $ClientId, $UserId;
    const TABLE = 'app_sales_invoices';
    const ForeignKey = 'InvoiceId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->PaymentType,$this->PaymentStatus,$this->Discount,$this->ClientId,$this->UserId];
        return DB::insert('insert into '. self::TABLE .' (PaymentType,PaymentStatus,Discount,ClientId,UserId,CreatedDate) values (?,?,?,?,?,now())',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->PaymentType,$this->PaymentStatus,$this->Discount,$this->ClientId,$this->UserId,$this->InvoiceId];
        return DB::update("update ". self::TABLE ." set  PaymentType=?, PaymentStatus=? , Discount=? , ClientId=?, UserId=? WHERE InvoiceId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->InvoiceId)->delete();
    }

    public static function inner_join($col=null,$val=null)
    {
        if($col != null && $val != null)
        {
            $select = "app_sales_invoices.* , app_users.Username, app_clients.FirstName, app_clients.LastName, (select count(SaleId) FROM app_sales WHERE app_sales.InvoiceId = app_sales_invoices.InvoiceId) as CountCategories , (select sum(SellPrice * QuantitySales) FROM app_sales WHERE app_sales.InvoiceId = app_sales_invoices.InvoiceId) as Sum";
            return DB::statement("select $select from " . self::TABLE . " inner join app_users on app_users.UserId = app_sales_invoices.UserId inner join app_clients on app_clients.ClientId = app_sales_invoices.ClientId WHERE ".$col ."=? ",[$val],true)->get();
        }
        $select = "app_sales_invoices.* , app_users.Username, app_clients.FirstName, app_clients.LastName, (select count(SaleId) FROM app_sales WHERE app_sales.InvoiceId = app_sales_invoices.InvoiceId) as CountCategories , (select sum(SellPrice * QuantitySales) FROM app_sales WHERE app_sales.InvoiceId = app_sales_invoices.InvoiceId) as Sum";
        return DB::statement("select $select from " . self::TABLE . " inner join app_users on app_users.UserId = app_sales_invoices.UserId inner join app_clients on app_clients.ClientId = app_sales_invoices.ClientId ",[],true)->get();
    }

    public function getLastInsertId()
    {
        return DB::getLastInsertId();
    }

    public static function get_by_status($status)
    {
        if ($status > 1 && $status < 0) return false;
        $sales_staus_pay = DB::statement("SELECT count(*) as count FROM app_sales_invoices WHERE PaymentStatus = ?",[$status],true)->getColumn();
        $count_sales = is_numeric(self::count()) ? self::count() : 0;
        $sales_staus_pay_n = isset($sales_staus_pay->count) && is_numeric($sales_staus_pay->count) ? $sales_staus_pay->count  : 0;
        $sales = $count_sales == 0 ? 0 : $sales_staus_pay_n * 100 / $count_sales;
        return Helper::format_num($sales);

    }


}