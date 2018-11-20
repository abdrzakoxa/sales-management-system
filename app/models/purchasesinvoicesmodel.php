<?php

namespace Store\Models;

use Store\Core\DB;
use Store\core\Input;

class PurchasesInvoicesModel extends AbsModel
{
    public $InvoiceId, $PaymentType , $PaymentStatus, $CreatedDate, $Discount, $SupplierId, $UserId;
    const TABLE = 'app_purchases_invoices';
    const ForeignKey = 'InvoiceId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->PaymentType,$this->PaymentStatus,$this->Discount,$this->SupplierId,$this->UserId];
        return DB::insert('insert into '. self::TABLE .' (PaymentType,PaymentStatus,Discount,SupplierId,UserId,CreatedDate) values (?,?,?,?,?,now())',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->PaymentType,$this->PaymentStatus,$this->Discount,$this->SupplierId,$this->UserId,$this->InvoiceId];
        return DB::update("update ". self::TABLE ." set  PaymentType=?, PaymentStatus=? , Discount=? , SupplierId=?, UserId=? WHERE InvoiceId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->InvoiceId)->delete();
    }

    public function inner_join($col=null,$val=null)
    {
        $select = "app_purchases_invoices.* , app_users.Username, app_suppliers.FirstName, app_suppliers.LastName, (select count(PurchaseId) FROM app_purchases WHERE app_purchases.InvoiceId = app_purchases_invoices.InvoiceId) as CountCategories , (select sum(PurchasePrice * QuantityPurchases) FROM app_purchases WHERE app_purchases.InvoiceId = app_purchases_invoices.InvoiceId) as Sum";
        return DB::statement("select $select from " . self::TABLE . " inner join app_users on app_users.UserId = app_purchases_invoices.UserId inner join app_suppliers on app_suppliers.SupplierId = app_purchases_invoices.SupplierId ",[],true)->get();
    }

    public function getLastInsertId()
    {
        return DB::getLastInsertId();
    }


}