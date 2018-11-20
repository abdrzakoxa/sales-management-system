<?php

namespace Store\Models;

use Store\Core\DB;
use Store\core\Input;

class PermissionsModel extends AbsModel
{

    public $PermissionId, $Name, $Permission;
    const TABLE = 'app_permissions';
    const ForeignKey = 'PermissionId';

    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->Name,$this->Permission];
        $table = self::TABLE;
        return DB::insert("insert into {$table} (Name,Permission) values (?,?)",$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->Name,$this->Permission,$this->PermissionId];
        $table = self::TABLE;
        return DB::update("update {$table} set Name=? , Permission=? WHERE PermissionId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where('PermissionId','=',$this->PermissionId)->delete();
    }

    public static function authenticate($post)
    {
        $post['password'] = Input::Hash($post['password']);
        $exist = (boolean) DB::table(self::TABLE)->select('Username')->where($post)->row_count();
        if($exist)
        {
            return self::getStatus($post['username']);
        }else{
            return 3;
        }
    }


    public static function getStatus($username)
    {
        return DB::table(self::TABLE)->select('Status')->where('username', '=', $username)->getColumn()->Status;
    }

}