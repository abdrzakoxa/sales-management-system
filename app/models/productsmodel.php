<?php

namespace Store\Models;

use Store\Core\DB;
use Store\core\Input;
use Store\Core\Validate;

class ProductsModel extends AbsModel
{

    public $ProductId, $Title, $Tax, $UnitId , $MadeCountry, $AddedDate, $CategoryId, $Quantity, $NotificationQuantity, $Barcode, $SellPrice, $BuyPrice;
    const TABLE = 'app_products';
    const ForeignKey = 'ProductId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->Title,$this->MadeCountry,$this->Tax,$this->UnitId,$this->CategoryId,$this->Quantity,$this->NotificationQuantity,$this->Barcode,$this->SellPrice,$this->BuyPrice];
        return DB::insert('insert into '. self::TABLE .' (Title,MadeCountry,Tax,UnitId,CategoryId,Quantity,NotificationQuantity,Barcode,SellPrice,BuyPrice,AddedDate) values (?,?,?,?,?,?,?,?,?,?,now())',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->Title,$this->MadeCountry,$this->Tax,$this->UnitId,$this->CategoryId,$this->Quantity,$this->NotificationQuantity,$this->Barcode,$this->SellPrice,$this->BuyPrice,$this->ProductId];
        return DB::update("update ". self::TABLE ." set  Title=?, MadeCountry=?, Tax=?,UnitId=? , CategoryId=? , Quantity=?, NotificationQuantity=?, Barcode=?, SellPrice=?, BuyPrice=? WHERE ProductId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->ProductId)->delete();
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

    public function IncreaseQuantity($increase)
    {
        if(is_numeric($increase))
        {
            $quantity = self::getColByKey('Quantity',$this->ProductId) + $increase;

            $updateValues = [$quantity ,$this->ProductId];

            return DB::update("update ". self::TABLE ." set   Quantity=? WHERE ProductId=?",$updateValues);
        }
        return false;

    }

    public function ReduceQuantity($increase)
    {
        $Qold = self::getColByKey('Quantity',$this->ProductId);

        if(is_numeric($increase) && $Qold >= $increase)
        {
            $quantity = $Qold - $increase;

            $updateValues = [$quantity ,$this->ProductId];

            return DB::update("update ". self::TABLE ." set   Quantity=? WHERE ProductId=?",$updateValues);
        }
        return false;

    }

    public function doReduce($Reduce)
    {
        $Qold = self::getColByKey('Quantity',$this->ProductId);

        if(is_numeric($Reduce) && $Qold >= $Reduce)
        {
            return true;

        }
        return false;
    }

    public function UpdateQuantity($new,$old)
    {
        if(is_numeric($new) && is_numeric($old))
        {
            $quantity = self::getColByKey('Quantity',$this->ProductId) - $old + $new;

            $updateValues = [$quantity ,$this->ProductId];

            return DB::update("update ". self::TABLE ." set   Quantity=? WHERE ProductId=?",$updateValues);
        }
        return false;
    }

    public static function inner_join($col=null,$val=null,$magic = '=')
    {
        if($col != null && $val!= null){
            $re = DB::statement("select app_products_categories.* , app_products.* , app_units.Name as UnitName, app_units.Code as UnitCode from " . self::TABLE . " inner join app_products_categories on app_products_categories.ProductCategoryId = app_products.CategoryId inner join app_units on app_units.UnitId = app_products.UnitId WHERE $col $magic ?",[$val],true)->get();
            return $re[0];
        }
        return DB::statement("select app_products_categories.* , app_products.* , app_units.Name as UnitName, app_units.Code as UnitCode from " . self::TABLE . " inner join app_products_categories on app_products_categories.ProductCategoryId = app_products.CategoryId inner join app_units on app_units.UnitId = app_products.UnitId",[],true)->get();
    }



}