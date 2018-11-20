<?php


namespace Store\Models;

use Store\Core\DB;
use Store\core\Input;

class PurchasesModel extends AbsModel
{
    public $PurchaseId, $PurchasePrice , $QuantityPurchases, $ProductId, $InvoiceId;
    const TABLE = 'app_purchases';
    const ForeignKey = 'PurchaseId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->PurchasePrice,$this->QuantityPurchases,$this->InvoiceId,$this->ProductId];
        return DB::insert('insert into '. self::TABLE .' (PurchasePrice,QuantityPurchases,InvoiceId,ProductId) values (?,?,?,?)',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->PurchasePrice,$this->QuantityPurchases,$this->InvoiceId,$this->PurchaseId];
        return DB::update("update ". self::TABLE ." set  PurchasePrice=?, QuantityPurchases=? , InvoiceId=?  WHERE PurchaseId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->PurchaseId)->delete();
    }

    public static function getColByKey($col,$id)
    {
        if(is_numeric($id)){
            if(DB::table(self::TABLE)->select($col)->where(self::ForeignKey,'=',$id)->getColumn() != '' )
            {
                return DB::table(self::TABLE)->select($col)->where(self::ForeignKey,'=',$id)->getColumn()->{$col};
            }
        }
        return false;

    }

    public static function count_purchases()
    {
        $table = static::TABLE;
        $r = DB::statement('SELECT SUM(QuantityPurchases) as count from '.$table,[],true)->get();
        $r = array_shift($r);
        return $r->count;
    }





}