<?php

namespace Store\Core;


class DB
{
    use Helper;
    private static $username,$is_connect, $password, $hostname, $dbname, $dns, $options = array(), $DB = null;

    private static $table;
    private static $select;
    public static $sttmt;
    private static $execute;
    private static $fetch_style = \PDO::FETCH_OBJ;
    private static $join;
    private static $where_sttmt;
    private static $where = array();
    private static $tabels = [
        'App_Permissions',
        'App_Groups',
        'App_Permissions_Groups',
        'App_Users', 'App_Permissions_Users',
        'App_Users_Profile',
        'App_Base_Units',
        'App_Units',
        'App_Products_Categories',
        'App_Products',
        'App_Suppliers',
        'App_Clients',
        'App_Sales_Invoices',
        'App_Sales',
        'App_Purchases_Invoices',
        'App_Purchases',
        'App_Notifications',
        'App_Notifications_Users',
        'App_Expenses_Categories',
        'App_Expenses',
    ];

    private function __construct()
    {
    }


    public static function Backup()
    {
        $date = Date(Settings::get('settings-numbers-formatting')->DateFormat);
        $time = Date(Settings::get('settings-numbers-formatting')->TimeFormat);
        $user = Sessions::getInstance()->User->Username ? Sessions::getInstance()->User->Username : 'Unknown';
        $result = "/*\r * User: $user \r * Date: $date\r * Time: $time \r*/";
        $database = self::Config('mysql.dbname');
        $result .= "\rCREATE DATABASE `$database` ;";
        $result .= "\rUSE `$database` ;";

        $tables = self::$tabels;

        foreach ($tables as $t_item) {
            $createTable = self::query('show create table '.$t_item)->fetchAll();
            $createTable = $createTable[0][1];
            $result .= "\n\r/* Create Table $t_item */\n" . $createTable . ';';

            $select = DB::query('select * from ' . $t_item );

            $data = $select->fetchAll(\PDO::FETCH_ASSOC);
            $result .= "\r";
            foreach ($data as $item) {
                $assin = '';
                foreach ($item as $key => $value) {
                    $assin .= "`$key` = '$value', ";
                }
                $assin = rtrim($assin,', ');
                $result .= "\r INSERT INTO `$t_item` SET $assin ;";
                unset($assin);
            }
            $result .= "\r";

            unset($createTable);
        }
        $result .= '/* End */';
        return $result;
    }

    public static function table($table)
    {
        SELF::$table = $table;
        return new static();
    }

    public static function whereStatic($where)
    {
        SELF::$where = $where;
        return new static();
    }

    public static function get()
    {
        $sttmt = SELF::$sttmt;
        $execute = SELF::$execute;
        $sttm = SELF::prepare($sttmt);
        $sttm->execute($execute);
        $fetch_style = SELF::$fetch_style;
        self::format_fetch_style();
        self::$sttmt = '';
        if($fetch_style == 'default'){
            return $sttm->fetchAll();
        }else{
            return $sttm->fetchAll($fetch_style);
        }

    }

    public static function query($query)
    {
        return SELF::connect()->query($query);
    }

    public static function connect()
    {
        if (self::$is_connect = false)
        {
            return false;
        }

        $database = Settings::get('database');

        if (isset($database->DatabaseName) && isset($database->Username) && isset($database->Password) && isset($database->Hostname))
        {
            SELF::$username = $database->Username;
            SELF::$password = $database->Password;
            SELF::$hostname = $database->Hostname;
            SELF::$dbname   = $database->DatabaseName;
        }else{
            self::$is_connect = false;
            return false;
        }


        SELF::$dns = "mysql:host=".SELF::$hostname;

        SELF::$options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ];


        if (SELF::$DB == null) {
            try {
                SELF::$DB = new \PDO(SELF::$dns, SELF::$username, SELF::$password, SELF::$options);
                if (!self::database_create()){
                    self::$is_connect = false;
                    return false;
                }
            } catch (\PDOException $e) {
                self::$is_connect = false;
                return false;
            }
        }



        return SELF::$DB;
    }

    public static function database_create()
    {
        $b = self::$DB->query("SHOW DATABASES LIKE '".SELF::$dbname."'");

        $database = $b->fetch(\PDO::FETCH_OBJ);

        if (empty($database)){

            if (file_exists(CONFIG_PATH . DS . 'db.sql'))
            {
                $sql = file_get_contents(CONFIG_PATH . DS . 'db.sql');
                self::$DB->query("CREATE DATABASE ".SELF::$dbname);
                self::$DB->query("USE `".SELF::$dbname."`");
                self::$DB->query($sql);
                Helper::redirect('/Auth/Logout');
            }else{
                return false;
            }
        }else{
            self::$DB->query("USE `".SELF::$dbname."`");
            $TABELS_my = self::$tabels;
            $TABELS = self::$DB->query("SHOW TABLES");
            $TABELS = $TABELS->fetchAll();
            $TABELS = self::array_flatten($TABELS);
            sort($TABELS);
            sort($TABELS_my);
            $TABELS = array_map('strtolower',$TABELS);
            $TABELS_my = array_map('strtolower',$TABELS_my);
            if ($TABELS != $TABELS_my){
                self::$DB->query("DROP DATABASE `".SELF::$dbname."`");
                self::redirect('/install/');
            }

        }

        return true;
    }

    public function select($sttm, $execute = null)
    {

        $str = strtolower($sttm);
        SELF::$select = '';

        if(strpos($str, 'select') != false){
            $sttm = SELF::prepare($sttm);
            $sttm->execute($execute);
            return $sttm->fetchAll(\PDO::FETCH_OBJ);
        }elseif($execute == null){
            SELF::$select = $sttm;
            $select = SELF::$select;
            $table = SELF::$table;
            SELF::$sttmt = "select {$select} from {$table} ";
            return new static();
        }

    }


    public function selectQuery($sttm, $execute)
    {

            $str = strtolower($sttm);

            $sttm = SELF::prepare($sttm);
            if(!strpos($str,'where')){
                $sttm->execute();
            }
//            return $sttm->fetchAll(\PDO::FETCH_OBJ);

            return $execute($sttm);



    }

    public function prepare($query)
    {
        return SELF::connect()->prepare($query);
    }

    public static function insert($sttm, $execute=array())
    {
        $sttms = SELF::prepare($sttm);
        $sttms->execute($execute);
        return $sttms;
    }


//    transAction

    public static function update($sttm, $execute=array())
    {
        $sttms = self::prepare($sttm);
        if($sttms->execute($execute))
        {
            return true;
        }
        return false;
    }


    public static function delete()
    {
        $table = self::$table;
        $where_sttmt = self::$where_sttmt;
        $sttmdelete = "Delete from {$table} WHERE {$where_sttmt}";
        $sttm = SELF::prepare($sttmdelete);
        $sttm->execute(self::$execute);
        return $sttm;
    }

    public static function statement($sttm, $execute=[],$trans = false)
    {


        if($trans == true)
        {
            self::$sttmt = $sttm;
            self::$execute = $execute;
            return new static();
        }
        $sttm = SELF::prepare($sttm);
        $sttm->execute($execute);
        return $sttm;
    }

    public function insulate($column)
    {
        $table = SELF::$table;
        $sttm = SELF::query("select {$column} from {$table}");
        $resultDB = $sttm->fetchAll();
        foreach ($resultDB as $key => $val) {
            foreach ($val as $value) {
                $result[] = $value;
            }
        }
        return $result;
    }

    public static function where($col,$logic=' AND ',$val=null) // return new static and insert where
    {
        $table = SELF::$table;
        $select = SELF::$select;
        $join = SELF::$join;


        if(is_array($col))
        {
            $array = array();
            foreach ($col as $key => $value)
            {
                $array[] = $key . '=:' . $key;
            }
            $where = implode($logic,$array);
            self::$where_sttmt = $where;
            SELF::$sttmt = "select {$select} from {$table} {$join} where {$where}";
            SELF::$execute = $col;
        }
        else{
            $afterLogic = explode('.', $col);
            $afterLogic = end($afterLogic);

            SELF::$sttmt = "select {$select} from {$table} $join where {$col}{$logic}:{$afterLogic}";
            self::$where_sttmt = $col . $logic.':'.$afterLogic;
            SELF::$execute = [$afterLogic => $val];
        }
        return new static();
    }

    public function count()
    {
        $exexute = SELF::$where != '' ? SELF::$where : [];

        $where = SELF::$where != '' ? 'where ' . array_keys($exexute)[0] . '=:' . array_keys($exexute)[0] : '';

        $table = SELF::$table;
        $sttm = SELF::prepare("select count(*) as count from {$table} {$where}");
        $sttm->execute($exexute);
        return $sttm->fetch(\PDO::FETCH_ASSOC)['count'];
    }

    public static function row_count()
    {
        $sttm = SELF::prepare(self::$sttmt);
        $sttm->execute(self::$execute);
        return $sttm->rowCount();
    }

    public function max($column)
    {
        $exexute = SELF::$where != '' ? SELF::$where : [];

        $where = SELF::$where != '' ? 'where ' . array_keys($exexute)[0] . '=:' . array_keys($exexute)[0] : '';

        $table = SELF::$table;
        $sttm = SELF::prepare("select max({$column}) as max from {$table} {$where}");
        $sttm->execute($exexute);
        return $sttm->fetch(\PDO::FETCH_ASSOC)['max'];
    }

    public function min($column)
    {
        $exexute = SELF::$where != '' ? SELF::$where : [];

        $where = SELF::$where != '' ? 'where ' . array_keys($exexute)[0] . '=:' . array_keys($exexute)[0] : '';

        $table = SELF::$table;
        $sttm = SELF::prepare("select min({$column}) as min from {$table} {$where}");
        $sttm->execute($exexute);
        return $sttm->fetch(\PDO::FETCH_ASSOC)['min'];
    }

    public function avg($column)
    {

        $exexute = SELF::$where != '' ? SELF::$where : [];

        $where = SELF::$where != '' ? 'where ' . array_keys($exexute)[0] . '=:' . array_keys($exexute)[0] : '';

        $table = SELF::$table;
        $sttm = SELF::prepare("select avg({$column}) as avg from {$table} {$where}");
        $sttm->execute($exexute);
        return $sttm->fetch(\PDO::FETCH_ASSOC)['avg'];
    }


    public function fetch_style($fetch_style = 'default')
    {
        SELF::$fetch_style = $fetch_style;
        return new static();
    }

    public function getColumn(){
        $sttmt = SELF::$sttmt;
        $execute = SELF::$execute;
        $sttm = SELF::prepare($sttmt);
        $sttm->execute($execute);
        $fetch_style = SELF::$fetch_style;
        self::format_fetch_style();
        if($fetch_style == 'default'){
            return $sttm->fetch();
        }else{
            return $sttm->fetch($fetch_style);
        }
    }

    public static function format_fetch_style(){
        SELF::$fetch_style = \PDO::FETCH_OBJ;
    }

    public static function getLastInsertId()
    {
        DB::connect()->lastInsertId();
    }

}