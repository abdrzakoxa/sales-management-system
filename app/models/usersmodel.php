<?php

namespace Store\Models;

use Store\Core\DB;
use Store\core\Input;

class UsersModel extends AbsModel
{

    public $UserId , $Username, $Email, $Password,$Password_old , $Phone, $GroupId, $Sex, $Registered, $LastLogin,$Status;
    const TABLE = 'app_users';
    const ForeignKey = 'UserId';


    public static $_input_rules_update = [
        'username'          => 'unique(Username)',
        'group_id'          => 'inTable(app_groups.GroupId)|unique(GroupId)',
        'phone'             => 'unique(phone)',
        'email'             => 'unique(GroupId)',
    ];

    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->Username,Input::Hash($this->Password),$this->Email,$this->Phone,$this->GroupId,$this->Sex,$this->Status];
        return DB::insert('insert into app_users (Username,Password,Email,Phone,GroupId,Sex,Status) values (?,?,?,?,?,?,?)',$insetValues);
    }

    public function update()
    {
        $password = $this->Password != null ? Input::Hash($this->Password) : $this->Password_old  ;
        if (empty($this->GroupId)){
            $updateValues = [$this->Username,$password,$this->Email,$this->Phone,$this->Sex,$this->Status,$this->UserId];
            return DB::update("update app_users set Username=? , Password=? , Email=? , Phone=? , Sex=?, Status=? WHERE UserId=?",$updateValues);
        }
        $updateValues = [$this->Username,$password,$this->Email,$this->Phone,$this->GroupId,$this->Sex,$this->Status,$this->UserId];
        return DB::update("update app_users set Username=? , Password=? , Email=? , Phone=? , GroupId=? , Sex=?, Status=? WHERE UserId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where('UserId','=',$this->UserId)->delete();
    }

    public static function authenticate($post)
    {
        $p = [];
        $p['password'] = Input::Hash($post['password']);
        $p['username'] = $post['username'];
        unset($post);
        $exist = (boolean) DB::table(self::TABLE)->select('Username')->where($p)->row_count();
        if($exist)
        {
            return self::getStatus($p['username']);
        }else{
            return 3;
        }
    }

//    public static function exist($column, $val)
//    {
//        if(strtolower($column) == 'password'){
//            $val = Input::Hash($val);
//        }
//        return DB::table(self::TABLE)->select($column)->where($column, '=', $val)->row_count();
//    }

    public function setLastLogin()
    {
        $table = self::TABLE;
        if($this->UserId != null){
            return DB::update("update {$table} set LastLogin=now() WHERE UserId=?",[$this->UserId]);
        }elseif ($this->Username != null){
            return DB::update("update {$table} set LastLogin=now() WHERE Username=?",[$this->Username]);
        }
    }

    public function setIPAddress($ip)
    {
        $table = self::TABLE;
        if($this->UserId != null){
            return DB::update("update {$table} set IpAddress=? WHERE UserId=?",[$ip,$this->UserId]);
        }elseif ($this->Username != null){
            return DB::update("update {$table} set IpAddress=? WHERE Username=?",[$ip,$this->Username]);
        }
    }

    public static function getStatus($selector)
    {
        if (is_numeric($selector)){
            return DB::table(self::TABLE)->select('Status')->where('UserId', '=', $selector)->getColumn()->Status;
        }else{
            return DB::table(self::TABLE)->select('Status')->where('username', '=', $selector)->getColumn()->Status;
        }
    }


    public function getIdByU()
    {
        return DB::table(self::TABLE)->select("UserId")->where('Username','=',$this->Username)->getColumn()->UserId;
    }

    public function inner_join($column,$val)
    {
        return DB::statement("SELECT * , app_groups.GroupName FROM " . self::TABLE . " INNER JOIN app_groups ON app_groups.GroupId = app_users.GroupId WHERE " . $column . "=? ",[$val],true)->getColumn();

    }

}