<?php

namespace Store\core;


class Settings
{

    private static $file_settings = CONFIG_PATH . DS . 'settings.txt';
    private static $Settings = [];

    public static function load_config()
    {
        $file = file_get_contents(self::$file_settings);
        if(empty($file))
        {
            $defualts = self::DefaultSettings();
            file_put_contents(self::$file_settings,$defualts);
            self::$Settings = unserialize($defualts);
        }else{
            self::$Settings = unserialize($file);
        }
    }

    public static function save($key,$val)
    {
        self::$Settings[$key] = $val;
        return file_put_contents(self::$file_settings,serialize(self::$Settings)) ? true : false;
    }

    public static function get_all()
    {
        return self::$Settings;

    }

    public static function get($Settings)
    {
        if(isset(self::$Settings[$Settings]) && !empty($Settings))
        {
            return self::$Settings[$Settings];
        }
        return null;
    }



    public static function DefaultSettings()
    {
        return 'a:5:{s:3:"app";O:8:"stdClass":2:{s:20:"time_update_currency";i:1538138045;s:16:"currency_convert";d:0.106175;}s:15:"setting-company";O:8:"stdClass":4:{s:4:"Name";s:0:"";s:5:"Email";s:0:"";s:5:"Phone";s:0:"";s:7:"Address";s:0:"";}s:27:"settings-numbers-formatting";O:8:"stdClass":7:{s:10:"DateFormat";s:5:"d/m/Y";s:10:"TimeFormat";s:5:"H:i:s";s:8:"Decimals";s:1:"2";s:16:"QuantityDecimals";s:1:"7";s:16:"CurrencyDecimals";s:1:"5";s:17:"DecimalsSeparator";s:1:"2";s:18:"ThousandsSeparator";s:1:"3";}s:16:"setting-products";O:8:"stdClass":2:{s:13:"TaxesProducts";s:1:"1";s:16:"DiscountProducts";s:1:"1";}s:12:"setting-site";O:8:"stdClass":5:{s:8:"Language";s:2:"en";s:8:"Currency";O:8:"stdClass":7:{s:6:"symbol";s:1:"$";s:4:"name";s:9:"US Dollar";s:13:"symbol_native";s:1:"$";s:14:"decimal_digits";i:2;s:8:"rounding";i:0;s:4:"code";s:3:"USD";s:11:"name_plural";s:10:"US dollars";}s:9:"TableRows";s:2:"10";s:12:"LoginCaptcha";s:1:"1";s:21:"DisplayCurrencySymbol";s:1:"3";}}';
    }

}