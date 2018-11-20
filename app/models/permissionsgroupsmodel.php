<?php

namespace Store\Models;

use Store\Core\DB;
use Store\core\Input;

class PermissionsgroupsModel extends AbsModel
{

    public $PermissionGroupId, $GroupId, $PermissionId;
    const TABLE = 'app_permissions_groups';
    const ForeignKey = 'PermissionGroupId';

    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->GroupId,$this->PermissionId];
        $table = self::TABLE;
        return DB::insert("insert into {$table} (GroupId,PermissionId) values (?,?)",$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->GroupId,$this->PermissionId,$this->PermissionGroupId];
        $table = self::TABLE;
        return DB::update("update {$table} set GroupId=? , PermissionId=? WHERE PermissionGroupId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(['PermissionId'=>$this->PermissionId,'GroupId'=> $this->GroupId])->delete();
    }


}