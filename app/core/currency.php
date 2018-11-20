<?php


namespace Store\core;


class Currency
{
    private static $Instance;
    private static $currency_convert;
    private static $time_convert;
    private function __construct(){}

    public static function get_instance()
    {
        if(self::$Instance == null)
        {
            self::$Instance = new self;
        }
        return self::$Instance;
    }

    public function update_currency($time=true)
    {
        self::$time_convert = isset(Settings::get('app')->time_update_currency) ? Settings::get('app')->time_update_currency : null ;

        self::$currency_convert = isset(Settings::get('app')->currency_convert) ? Settings::get('app')->currency_convert : null;

        if (self::$time_convert != null && self::$time_convert + 86400 > time() && $time == true) return ;

        $app = new \stdClass();
        $app->time_update_currency = time();
        $code = Settings::get('setting-site')->Currency->code;
        $app->currency_convert = self::convert_currency('MAD',$code);
        Settings::save('app',$app);

    }


    private static function convert_currency($from,$to)
    {
        $from = strtoupper($from);
        $to = strtoupper($to);
        if ($from == $to) return 1;

        $url = "https://free.currencyconverterapi.com/api/v5/convert?q=" . $from . "_". $to ."&compact=ultra";
        $ch         = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $output = curl_exec($ch);
        curl_close($ch);

        $output = json_decode($output,true);
        return array_shift($output);
    }

    public static function out_currency($amount,$format = true,$trans = true)
    {
        $code = Settings::get('setting-site')->Currency->code;
        if($code == null) return false;

        $amount = $trans ? self::$currency_convert * $amount : $amount ;

        return !$format ? number_format($amount,self::decimal(),'.','') : self::format_currency($amount);

    }

    public static function inside_currency($amount)
    {
        $code = Settings::get('setting-site')->Currency->code;
        if($code == null || !is_numeric($amount)) return false;
        $amount = $amount / self::$currency_convert ;
        return number_format($amount,8,'.','');
    }


    public static function format_currency($number)
    {
        $decimal = self::decimal();
        $decimal_symbol = self::decimal_symbol();
        $display_currency_symbol= Settings::get('setting-site')->DisplayCurrencySymbol;


        if ($decimal == false || !is_numeric($number)) return false;
        if ($decimal >= 1 && 5 >= $decimal) {
            $num = number_format($number,self::decimal(),self::decimal_separator(),self::decimal_thousands());
            $num = preg_replace('/(\,|\.)0+$/','',$num);
            if($display_currency_symbol == 3)
            {
                $num = $num . ' ' . $decimal_symbol;
            }
            else if ($display_currency_symbol == 1)
            {
                $num = $decimal_symbol . $num;
            }

            return $num ;
        }
        return false;
    }

//    decimal


    public static function decimal_symbol()
    {
        return Settings::get('setting-site')->Currency->symbol;

    }

    public static function decimal()
    {
        $decimal                = Settings::get('settings-numbers-formatting')->CurrencyDecimals;
        if ($decimal == null) return false;
        $decimal_digits         = Settings::get('setting-site')->Currency->decimal_digits;
        $dec = [1=>1,2=>2,3=>3,4=>0,5=>$decimal_digits];
        return $dec[$decimal];
    }



    public static function decimal_separator()
    {
        $decimal_separator      = Settings::get('settings-numbers-formatting')->DecimalsSeparator;
        $Sep_dec = [1=>',',2=>'.',3=>''];

        return $Sep_dec[$decimal_separator];
    }

    public static function decimal_thousands()
    {
        $thousands_separator    = Settings::get('settings-numbers-formatting')->ThousandsSeparator;
        $Sep_tho = [1=>',',2=>'.',3=>' ',4=>''];

        return $Sep_tho[$thousands_separator];
    }

}