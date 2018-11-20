<?php

namespace Store\Models;

use Store\core\Currency;
use Store\Core\DB;

abstract class AbsModel
{
    public $Table = null;
    public $ForeignKey = null;
    private static $table;

    public static function getAll()
    {
        return DB::table(static::TABLE)->select('*')->get();
    }

    public function getAllWidth($limit=5,$orderType='DESC')
    {
        return DB::statement("SELECT * FROM $this->Table ORDER BY $this->ForeignKey $orderType LIMIT $limit",[],true)->get();
    }

    public static function getByKey($id){
        return DB::table(static::TABLE)->select('*')->where(static::ForeignKey, '=' , $id)->getColumn();
    }

    public static function getAllOrder($order){
        return DB::statement('SELECT * from ' . static::TABLE . ' ORDER BY ' . $order ,[],true)->get();
    }

    public static function getBy($col,$val,$fetchAll = false)
    {
        if($fetchAll)
        {
            return DB::table(static::TABLE)->select('*')->where($col, '=' , $val)->get();
        }

        return DB::table(static::TABLE)->select('*')->where($col, '=' , $val)->getColumn();

    }

    public function getsBy($col,$val,$select='*',$fetch_style =\PDO::FETCH_OBJ)
    {
        return DB::table(static::TABLE)->select($select)->fetch_style($fetch_style)->where($col, '=' , $val)->get();
    }

    public static function table($table)
    {
        self::$table = $table;
        return new static();
    }

    public static function count()
    {
        $table = static::TABLE;
        $r = DB::statement('SELECT count(*) as count from '.$table,[],true)->get();
        $r = array_shift($r);
        return isset($r->count) && is_numeric($r->count) ? $r->count : 0;
    }

    public static function exist($column, $val, $where=false)
    {
        if(strtolower($column) == 'password'){
            $val = Input::Hash($val);
        }
        $table = self::$table;
        if($where == true)
        {
            $whe = "{$column}=:{$column} AND {$column}!=:valueOld";
            $execute = [$column=>$val,'valueOld'=>$where];
        }else{
            $whe = "{$column}=:{$column}";
            $execute = [$column=>$val];
        }
        $where=false;
        return DB::statement("select * from {$table} where {$whe}",$execute,true)->row_count();

    }

    public static function getByCols($cols)
    {
        return DB::table(static::TABLE)->select('*')->where($cols)->get();
    }


    public static function get_info_in_mondays()
    {

        $year = date('Y');
        $monday = date('m');
        $data = [0,0,0,0,0,0,0,0,0,0,0,0];


        if(self::getAll() != [])
        {
            foreach (self::getAll() as $item) {
                $timeitem = strtotime($item->CreatedDate);
                $yearitem = date('Y',$timeitem);
                $modayitem = date('m',$timeitem);
                if($yearitem == $year)
                {
                    for ($i = 1; $i <= 12; $i++) {
                        if ($modayitem == $i) {
                            if(isset($item->QuantitySales))
                            {
                                $data[$i-1] = $data[$i-1] + $item->QuantitySales;
                            } elseif (isset($item->QuantityPurchases)) {
                                $data[$i-1] = $data[$i-1] + $item->QuantityPurchases;
                            }
                        }
                    }

                }
            }
        }


        return array_splice($data,0,$monday);

    }

    public static function get_monay_in_mondays($col)
    {
        $year = date('Y');
        $monday = date('m');
        $data = [0,0,0,0,0,0,0,0,0,0,0,0];
        if(self::getAll() != [])
        {
            foreach (self::getAll() as $item) {
                $timeitem = strtotime($item->CreatedDate);
                $yearitem = date('Y',$timeitem);
                $modayitem = date('m',$timeitem);
                if($yearitem == $year)
                {
                    for ($i = 1; $i <= 12; $i++) {
                        if ($modayitem == $i) {
                            $data[$i-1] = $data[$i-1] + $item->{$col};
                        }
                    }

                }
            }
        }
        $new = [];
        foreach ($data as $datum) {
            $new[] = Currency::out_currency($datum,false);
        }

        return array_splice($new,0,$monday);

    }


}