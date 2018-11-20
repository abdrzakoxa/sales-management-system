<?php

namespace Store\Models;

use Store\Core\DB;
use Store\core\Input;

class ExpensesModel extends AbsModel
{

    public $ExpenseId, $Payment , $CreatedDate, $UserId, $CategoryId;
    const TABLE = 'app_expenses';
    const ForeignKey = 'ExpenseId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->Payment,$this->UserId,$this->CategoryId];
        return DB::insert('insert into '. self::TABLE .' (Payment,CreatedDate,UserId,CategoryId) values (?,now(),?,?)',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->Payment,$this->CreatedDate,$this->UserId,$this->CategoryId,$this->ExpenseId];
        return DB::update("update ". self::TABLE ." set  Payment=?, CreatedDate=? , UserId=? , CategoryId=? WHERE ExpenseId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->ExpenseId)->delete();
    }

    public function getJoin()
    {
        return DB::statement('select ExpenseId, Payment, CreatedDate, app_expenses_categories.Type as CategoryName, app_users.Username FROM app_expenses
        INNER JOIN app_expenses_categories ON app_expenses_categories.ExpenseCategoryId=app_expenses.CategoryId
        INNER JOIN app_users ON app_expenses.UserId=app_users.UserId',[])->fetchAll(\PDO::FETCH_OBJ);
    }



}


//select Payment, CreatedDate, app_expenses_categories.Type as CategoryName FROM app_expenses
//        INNER JOIN app_expenses_categories ON app_expenses_categories.ExpenseCategoryId=app_expenses.CategoryId