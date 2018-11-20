<?php
namespace Store\Models;

use Store\Core\DB;
use Store\Core\Helper;
use Store\core\Input;
use Store\Core\Sessions;

class PermissionsusersModel extends AbsModel
{
    use Helper;

    public $PermissionUserId, $UserId, $PermissionId;
    const TABLE = 'app_permissions_users';
    const ForeignKey = 'PermissionUserId';

    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->UserId,$this->PermissionId];
        $table = self::TABLE;
        return DB::insert("insert into {$table} (UserId,PermissionId) values (?,?)",$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->UserId,$this->PermissionId,$this->PermissionUserId];
        $table = self::TABLE;
        return DB::update("update {$table} set UserId=? , PermissionId=? WHERE PermissionUserId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(['PermissionId'=>$this->PermissionId,'UserId'=> $this->UserId])->delete();
    }

    public static function getPermissionsUser()
    {
        $UserId = Sessions::getInstance()->User->UserId;
        $permissions = DB::statement("select app_permissions.Permission from app_permissions_users INNER JOIN app_permissions ON app_permissions_users.PermissionId=app_permissions.PermissionId where UserId=?",[$UserId])->fetchAll();
        $permissions_all = DB::statement("select Permission from app_permissions")->fetchAll();
        $p = self::array_flatten($permissions);
        $a = self::array_flatten($permissions_all);
        $array_diff = array_diff($a,$p);
        return array_map("strtolower", $array_diff);
    }

    public static function is_permission_user($permissionUrl)
    {
        $permissionUrl = strtolower($permissionUrl);
        $permissions = PermissionsusersModel::getPermissionsUser();

        return in_array($permissionUrl,$permissions) ? false : true ;
    }



}