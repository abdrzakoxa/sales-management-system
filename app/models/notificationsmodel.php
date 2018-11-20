<?php

namespace Store\models;


use Store\Core\DB;

class NotificationsModel extends AbsModel
{

    public $NotificationId,$Title,$Content,$Link,$Type;
    const TABLE = 'app_notifications';
    const ForeignKey = 'NotificationId';

    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->Title,$this->Content,$this->Link,$this->Type];
        return DB::insert('insert into '. self::TABLE .' (Title,Content,Link,Type) values (?,?,?,?)',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->Title,$this->Link,$this->Content,$this->Type,$this->NotificationId];
        return DB::update("update ". self::TABLE ." set Title=? , Link=? , Content=?, Type=? WHERE NotificationId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->NotificationId)->delete();
    }



}