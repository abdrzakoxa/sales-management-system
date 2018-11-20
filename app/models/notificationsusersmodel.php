<?php

namespace Store\models;


use Store\Core\DB;
use Store\Core\Sessions;

class NotificationsUsersModel extends AbsModel
{

    public $NotificationUserId ,$NotificationId, $CreatedDate, $UserId, $ViewedDate, $Showed;
    const TABLE = 'app_notifications_users';
    const ForeignKey = 'NotificationUserId';

    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->NotificationId,$this->UserId];
//        echo $insetValues;
        return DB::insert('insert into '. self::TABLE .' (NotificationId,UserId) values (?,?)',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->NotificationId,$this->UserId,$this->NotificationUserId];
        return DB::update("update ". self::TABLE ." set NotificationId=? , UserId=? WHERE NotificationUserId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->NotificationUserId)->delete();
    }

    public static function deleteByNot($id)
    {
        return DB::table(self::TABLE)->where('NotificationId','=',$id)->delete();
    }

    public static function Showed($id)
    {
        $updateValues = [1,$id];
        return DB::update("update ". self::TABLE ." set Showed=? WHERE NotificationUserId=?",$updateValues);
    }

    public static function Viewed($id = null)
    {
        $UserId = is_numeric(Sessions::getInstance()->User->UserId) ? Sessions::getInstance()->User->UserId : 0;

        if ($id == null)
        {
            return DB::update("update ". self::TABLE ." set ViewedDate=now() WHERE ViewedDate=0 AND UserId=?",[$UserId]);
        }else{
            $id = is_numeric($id) ? $id : 0;
            return DB::update("update ". self::TABLE ." set ViewedDate=now() WHERE NotificationUserId=? AND UserId=?",[$id,$UserId]);
        }
    }


    public static function getNotUser()
    {
        $User = Sessions::getInstance()->User->UserId;
        if (!is_numeric($User)) return false;
        return DB::statement('SELECT * FROM ' . self::TABLE . ' inner join app_notifications on app_notifications.NotificationId = app_notifications_users.NotificationId WHERE UserId=? ORDER BY app_notifications_users.NotificationUserId DESC ',[$User],true)->get();
    }


    public function getNotViews()
    {
        if (!isset(Sessions::getInstance()->User->UserId)) return false;
        $UserId = is_numeric(Sessions::getInstance()->User->UserId) ? Sessions::getInstance()->User->UserId : 0;
        return DB::statement('SELECT * FROM '.self::TABLE.' inner join app_notifications on app_notifications.NotificationId = app_notifications_users.NotificationId WHERE ViewedDate = ? AND UserId = ? ORDER BY app_notifications_users.NotificationId DESC',[0,$UserId],true)->get();
    }


    public static function getNotBy($id)
    {
        if (!is_numeric($id)) return false;
        $UserId = is_numeric(Sessions::getInstance()->User->UserId) ? Sessions::getInstance()->User->UserId : 0;
        $col = DB::statement('SELECT * FROM '.self::TABLE.' inner join app_notifications on app_notifications.NotificationId = app_notifications_users.NotificationId WHERE NotificationUserId=? AND UserId=?',[$id,$UserId],true)->getColumn();
        return isset($col) ? $col : false;
    }

    public function getNotViewsShowed()
    {
        if (!isset(Sessions::getInstance()->User->UserId)) return false;
        $UserId = is_numeric(Sessions::getInstance()->User->UserId) ? Sessions::getInstance()->User->UserId : 0;
        return DB::statement('SELECT * FROM '.self::TABLE.' inner join app_notifications on app_notifications.NotificationId = app_notifications_users.NotificationId WHERE Showed = ? AND ViewedDate = ? AND UserId = ? ORDER BY app_notifications_users.NotificationId DESC',[0,0,$UserId],true)->get();
    }

    public function countNotViews()
    {
        if (!isset(Sessions::getInstance()->User->UserId)) return false;
        $UserId = is_numeric(Sessions::getInstance()->User->UserId) ? Sessions::getInstance()->User->UserId : 0;
        $co = DB::statement('SELECT count(*) as count FROM '.self::TABLE.' WHERE ViewedDate = ? AND UserId = ?',[0,$UserId],true)->getColumn();
        return isset($co->count) ? $co->count : 0 ;
    }



}