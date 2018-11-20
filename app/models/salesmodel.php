<?php


namespace Store\Models;

use Store\Core\DB;
use Store\core\Input;

class SalesModel extends AbsModel
{
    public $SaleId, $SellPrice , $QuantitySales, $ProductId, $InvoiceId;
    const TABLE = 'app_sales';
    const ForeignKey = 'SaleId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->SellPrice,$this->QuantitySales,$this->InvoiceId,$this->ProductId];
        return DB::insert('insert into '. self::TABLE .' (SellPrice,QuantitySales,InvoiceId,ProductId) values (?,?,?,?)',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->SellPrice,$this->QuantitySales,$this->InvoiceId,$this->SaleId];
        return DB::update("update ". self::TABLE ." set  SellPrice=?, QuantitySales=? , InvoiceId=?  WHERE SaleId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->SaleId)->delete();
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

    public static function inner_join($col=null,$val=null)
    {
        $select = "app_sales.*, app_products.Title ,app_products.BuyPrice";
        if($col != null && $val!= null){
            $select = "app_sales.*, app_products.Title, app_products.Tax , app_sales.SellPrice * app_sales.QuantitySales as TotalPriceProduct ,  (select sum(SellPrice * QuantitySales) FROM app_sales WHERE app_sales.InvoiceId = $val) as Total";
            return DB::statement("select $select from " . self::TABLE . " inner join app_products on app_products.ProductId = app_sales.ProductId WHERE $col=?",[$val],true)->get();
        }
        return DB::statement("select $select from " . self::TABLE . " inner join app_products on app_products.ProductId = app_sales.ProductId",[],true)->get();
    }


    public static function get_best_sales()
    {
        $salles =  DB::statement('SELECT *, SUM(QuantitySales) as count FROM app_sales group by ProductId ORDER BY count DESC LIMIT 5',[],true)->get();
        $label = [];
        $count = [];
        if(count($salles) > 0)
        {
            foreach ($salles as $salle) {
                $label[] = ProductsModel::getByKey($salle->ProductId)->Title;
                $count[] = $salle->count;
            }
        }
        $count[] = 0;
        return ['label'=>$label ,'count'=>$count];
    }

    public static function count_sales()
    {
        $table = static::TABLE;
        $r = DB::statement('SELECT SUM(QuantitySales) as count from '.$table,[],true)->get();
        $r = array_shift($r);
        return $r->count;
    }


    public static function LastSales($count)
    {
        $std = [];
        $last = DB::statement("SELECT app_sales.*, app_products.Title , app_units.Name, app_units.Code FROM " . self::TABLE . " inner join app_products on app_products.ProductId = app_sales.ProductId inner join app_units on app_products.UnitId = app_units.UnitId ORDER BY SaleId DESC LIMIT $count",[],true)->get();
        foreach ($last as $item) {
            $ivoice = SalesInvoicesModel::inner_join('InvoiceId',$item->InvoiceId)[0];
            $ivoice->UnitName = $item->Name;
            $ivoice->UnitCode = $item->Code;
            $ivoice->Title = $item->Title;
            $ivoice->SellPrice = $item->SellPrice;
            $ivoice->QuantitySales = $item->QuantitySales;
            $std[] = $ivoice;
        }
        return [$std,$count];
    }


}