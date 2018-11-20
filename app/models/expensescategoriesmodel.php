<?php

namespace Store\Models;

use Store\Core\DB;

class ExpensesCategoriesModel extends AbsModel
{

    public $ExpenseCategoryId, $Type , $FixedPayment;
    const TABLE = 'app_expenses_categories';
    const ForeignKey = 'ExpenseCategoryId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->Type, $this->FixedPayment];
        return DB::insert('insert into '. self::TABLE .' (`Type`,FixedPayment) values (?,?)',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->Type, $this->FixedPayment, $this->ExpenseCategoryId];
        return DB::update("update ". self::TABLE ." set Type=? , FixedPayment=? WHERE ExpenseCategoryId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->ExpenseCategoryId)->delete();
    }



}