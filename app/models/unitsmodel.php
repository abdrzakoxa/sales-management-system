<?php

namespace Store\Models;

use Store\Core\DB;
use Store\core\Input;

class UnitsModel extends AbsModel
{

    public $UnitId, $Code , $Name, $BaseUnit, $Operator, $OperationValue;
    const TABLE = 'app_units';
    const ForeignKey = 'UnitId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->Code,$this->Name,$this->BaseUnit,$this->Operator,$this->OperationValue];
        return DB::insert('insert into '. self::TABLE .' (Code,Name,BaseUnit,Operator,OperationValue) values (?,?,?,?,?)',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->Code,$this->Name,$this->BaseUnit,$this->Operator,$this->OperationValue,$this->UnitId];
        return DB::update("update ". self::TABLE ." set Code=? , Name=?, BaseUnit=? , Operator=?, OperationValue=? WHERE UnitId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->UnitId)->delete();
    }

    public function inner_join()
    {
        return DB::statement("SELECT app_base_units.Name as BaseUnitName , app_units.* FROM app_units INNER JOIN app_base_units ON app_base_units.BaseUnitId = BaseUnit",[],true)->get();
    }




}