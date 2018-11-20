<?php

namespace Store\Models;

use Store\Core\DB;
use Store\Core\Helper;
use Store\core\Input;
use Store\Core\Sessions;

class ProfileModel extends AbsModel
{

    public $UserId, $FirstName , $LastName, $Address, $Image,$DOB;
    const TABLE = 'app_users_profile';
    const ForeignKey = 'UserId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->UserId,$this->FirstName,$this->LastName,$this->Address,$this->Image,$this->DOB];
        return DB::insert('insert into '. self::TABLE .' (UserId,FirstName,LastName,Address,Image,DOB) values (?,?,?,?,?,?)',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->FirstName,$this->LastName,$this->Address,$this->DOB,$this->UserId];
        return DB::update("update ". self::TABLE ." set FirstName=? , LastName=?, Address=?  , DOB=? WHERE UserId=?",$updateValues);
    }

    public function update_image()
    {
        $updateValues = [$this->Image,$this->UserId];

        return DB::update("update ". self::TABLE ." set Image=? WHERE UserId=?",$updateValues);

    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->UserId)->delete();
    }

    public static function get_name_user_login()
    {
        $session = isset(Sessions::getInstance()->User->UserId) && isset(Sessions::getInstance()->User->Username) ? Sessions::getInstance()->User->UserId : Helper::redirect('/logout/');
        $user = self::getByKey($session);
        if(isset($user->FirstName) && isset($user->LastName) && $user->FirstName != '')
        {
            return $user->FirstName . ' ' . $user->LastName ;
        }
        return Sessions::getInstance()->User->Username;
    }




}