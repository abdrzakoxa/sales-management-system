<?php

namespace Store\Models;

use Store\Core\DB;

class BaseUnitsModel extends AbsModel
{

    public $BaseUnitId, $Code , $Name;
    const TABLE = 'app_base_units';
    const ForeignKey = 'BaseUnitId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->Code,$this->Name];
        return DB::insert('insert into '. self::TABLE .' (Code,Name) values (?,?)',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->Code,$this->Name,$this->BaseUnitId];
        return DB::update("update ". self::TABLE ." set Code=? , Name=? WHERE BaseUnitId=?",$updateValues);
    }


    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->BaseUnitId)->delete();
    }




}