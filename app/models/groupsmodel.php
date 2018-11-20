<?php

namespace Store\Models;

use Store\Core\DB;


class GroupsModel extends AbsModel
{

    public $GroupId,$GroupName;
    const TABLE = 'app_groups';
    const ForeignKey = 'GroupId';

    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->GroupName];
        $table = self::TABLE;
        return DB::insert("insert into {$table} SET GroupName=?",$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->GroupName,$this->GroupId];
        $table = self::TABLE;
        return DB::update("update {$table} set GroupName=? WHERE GroupId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->GroupId)->delete();
    }

    public function getIdByN()
    {
        return DB::table(self::TABLE)->select("GroupId")->where('GroupName','=',$this->GroupName)->getColumn();
    }

}