<?php

namespace Store\Models;

use Store\Core\DB;
use Store\core\Input;

class ClientsModel extends AbsModel
{

    public $ClientId, $FirstName , $LastName, $Email, $Phone, $Address;
    const TABLE = 'app_clients';
    const ForeignKey = 'ClientId';



    public function __construct()
    {
        $this->Table = SELF::TABLE;
        $this->ForeignKey = SELF::ForeignKey;
    }

    public function create()
    {
        $insetValues = [$this->FirstName,$this->LastName,$this->Email,$this->Phone,$this->Address];
        return DB::insert('insert into '. self::TABLE .' (FirstName,LastName,Email,Phone,Address) values (?,?,?,?,?)',$insetValues);
    }

    public function update()
    {
        $updateValues = [$this->FirstName,$this->LastName,$this->Email,$this->Phone,$this->Address,$this->ClientId];
        return DB::update("update ". self::TABLE ." set FirstName=? , LastName=?, Email=? , Phone=? , Address=? WHERE ClientId=?",$updateValues);
    }

    public function delete()
    {
        return DB::table(self::TABLE)->where(self::ForeignKey,'=',$this->ClientId)->delete();
    }



}