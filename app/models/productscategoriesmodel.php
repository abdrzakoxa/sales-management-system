<?php

namespace Store\Models;

use Store\Core\DB;
use Store\core\Input;

class ProductsCategoriesModel extends AbsModel
{

    public $ProductCategoryId, $Name , $Description;
    const TABLE = 'app_products_categories';
    const ForeignKey = 'ProductCategoryId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->Name, $this->Description];
        return DB::insert('insert into '. self::TABLE .' (`Name`,Description) values (?,?)',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->Name, $this->Description, $this->ProductCategoryId];
        return DB::update("update ". self::TABLE ." set Name=? , Description=? WHERE ProductCategoryId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->ProductCategoryId)->delete();
    }



}