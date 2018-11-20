<?php

namespace Store\Core;


use Store\Models\ExpensesModel;
use Store\Models\ProductsModel;
use Store\Models\ProfileModel;
use Store\Models\PurchasesModel;
use Store\Models\SalesInvoicesModel;
use Store\Models\SalesModel;

trait Helper
{

    public static function removeStr($str , $in)
    {
        return str_replace($str , '' , $in);
    }

    public static function getUrlOffset($i){
        $path = languages::filterPath($_SERVER['REQUEST_URI']);

        $path = explode('/',$path);

        return isset($path[$i]) ? $path[$i] : false;

    }

    public static function has_post($post)
    {
        return isset($_POST[$post]) && strtolower($_SERVER['REQUEST_METHOD']) == 'post' ? true : false;
    }

    public static function has_files($files)
    {
        return isset($_FILES[$files]) ? true : false;
    }

    public static function array_diff_reverse($array1,$array2)
    {
        $new_array = [];
        foreach ($array1 as $array){
            if(in_array($array,$array2))
            {
                $new_array[] = $array;
            }
        }
        return $new_array;
    }

    public static function has_get($get)
    {
        return isset($_GET[$get]) ? true : false;
    }

    public function getClassActive($Menu)
    {
        $url = trim(strtolower(SELF::getUrlOffset(1)));
        $Menu = is_array($Menu) ? array_map('strtolower',$Menu) : [trim(strtolower($Menu))];
        $controller = trim(strtolower($this->controller));

        if($Menu[0] == 'dashboard' && $controller == 'dashboard'){
            echo 'active DBIul';
        }else{
            if($controller == $url && in_array($url,$Menu)){
                echo 'active DBIul';
            }
        }

    }

    public static function redirect($path,$optionsPath = '/')
    {
        if($path == 'back')
        {
            $path = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $optionsPath ;
            header('location:' . $path);
            exit();

        }else{
            header('location:' . $path);
            exit();
        }
    }

    public static function Config($path) // mysql.username
    {
        $path = explode('.',$path);

        $array = require CONFIG_PATH . DS . 'configdb.php';

        foreach ($path as $value) {
            if(isset($array[$value])){
                $array = $array[$value];
            }
        }
        return $array;
    }

    public static function echoVar($var)
    {
        if(isset($var) && $var != ''){
            echo $var;
        }else{
            return false;
        }
    }

    public function conversionArray($arrays)
    {
        $newArray = [];
        foreach ($arrays as $array){
            $newArray[] = $array[0];
        }
        return $newArray;
    }

    public static function getPost($post)
    {
        if(isset($_POST[$post]) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
            return $_POST[$post];
        }
        return false;
    }

    public static function getGet($get)
    {
        if(isset($_GET[$get])){
            return $_GET[$get];
        }
        return false;
    }

    public static function getFiles($files)
    {
        if(isset($_FILES[$files])){
            return $_FILES[$files];
        }
        return false;
    }

    public static function array_flatten($array)
    {
        if(!is_array($array)){
            return false;
        }
        $result = array();
        foreach ($array as $array_flatten_key) {
            $result[] = $array_flatten_key[0];
        }
        return $result;
    }

    public function is_multi($check)
    {
        return is_array($check) && isset($check[0]) && is_array($check[0]) ? true : false ;

    }

    public static function getPremUser($Userid,$permissionsUsers,$permissionsGroups,$usersModel)
    {
//        print_r($user_select);
        $column_p_array = $permissionsUsers->getsBy('UserId',$Userid,'PermissionId','default');
        $column_p_array = self::array_flatten($column_p_array);
        $column_array2 = $permissionsGroups->getsBy('GroupId',$usersModel->getByKey($Userid)->GroupId,'permissionId','default');
        $column_array2 = self::array_flatten($column_array2);
        $permissions_user = self::array_diff_reverse($column_array2,$column_p_array);
        return $permissions_user = array_unique(array_merge($permissions_user,$column_p_array));
    }

    public static function pages_not_access($url)
    {
        $url = strtolower($url);
        $pages = [
          'Users/getPermGroup','Users/Exist','json/currency','json/settings','install/default'
        ];
        return in_array($url,array_map('strtolower',$pages));
    }

    public function clear_request($post='submit')
    {
        if(isset($_POST[$post]))
        {
            unset($_POST);
            header("Location:".$_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public static function decode($base64)
    {
        $base64 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
        return $base64;
    }

    public static function ready_array_purchases($array_obj)
    {
        $resoult = [];
        foreach ($array_obj as $obj) {
            $resoult['products'][] = $obj->ProductId;
            $resoult['quantity'][] = $obj->QuantityPurchases;
            $resoult['price'][] = $obj->PurchasePrice;
        }
        return $resoult;
    }


    public static function ready_array_sales($array_obj)
    {
        $resoult = [];
        foreach ($array_obj as $obj) {
            $resoult['products'][] = $obj->ProductId;
            $resoult['quantity'][] = $obj->QuantitySales;
            $resoult['price'][] = $obj->SellPrice;
        }
        return $resoult;
    }

    public static function out_currency_products($array_obj)
    {
        $resoult = [];
        if (isset($array_obj['products']))
        {
            foreach ($array_obj['products'] as $key => $product) {
                $resoult['products'][] = $product;
                $resoult['quantity'][] = $array_obj['quantity'][$key];
                $resoult['price'][] = Currency::out_currency($array_obj['price'][$key], false);
            }
        }
        return $resoult;
    }

    public static function readyImage($img){
        if (!empty($img))
        {
            echo UPLOADS_PATH . DS . $img;

        }else{
            echo UPLOADS_PATH . DS . 'default.png';
        }
    }


    public static function ready_updated_products($array_added,$array_old)
    {
        $resoult = [];
        if(isset($array_added['products']) && isset($array_old['products']))
        {
            foreach ($array_added['products'] as $key => $added) {
                if(in_array($added,$array_old['products']))
                {

                    foreach ($array_old['products'] as $key2 => $old) {
                        if($added == $old)
                        {
                            $resoult['products'][]  = $array_old['products'][$key2];
                            $resoult['quantity'][]  = $array_added['quantity'][$key] - $array_old['quantity'][$key2];
                            $resoult['price'][]     = $array_old['price'][$key2];
                        }
                    }
                }
            }
        }
        return $resoult;
    }


//    public static function ready_updated_sales($array_added,$array_old)
//    {
//        $resoult = [];
//        if(isset($array_added['products']))
//        {
//            foreach ($array_added['products'] as $key => $added) {
//                if(in_array($added,$array_old['products']))
//                {
//
//                    foreach ($array_old['products'] as $key2 => $old) {
//                        if($added == $old)
//                        {
//                            $resoult['products'][]  = $array_old['products'][$key2];
//                            $resoult['quantity'][]  = $array_added['quantity'][$key] - $array_old['quantity'][$key2];
//                            $resoult['price'][]     = $array_old['price'][$key2];
//                        }
//                    }
//                }
//            }
//        }
//        return $resoult;
//    }

    public function array_diff_multiple($old,$new)
    {
        if (isset($new['products']) && !isset($old['products'])) return $new;

        $resoult = [];
        $old['price'] = isset($old['price']) ? self::array_decimals($old['price']) : [];
        if(isset($new['products']) && isset($old['products'])){
            foreach ($new['products'] as $key => $item){
                if(in_array($item,$old['products']) && in_array($new['quantity'][$key],$old['quantity']) && in_array($new['price'][$key],$old['price']) )
                {

                }else{
                    $resoult['products'][] = $item;
                    $resoult['quantity'][] = $new['quantity'][$key];
                    $resoult['price'][] = $new['price'][$key];
                }
            }
        }

        return $resoult;
    }

    public function array_diff_up_ad($update,$add)
    {
        $resoult = [];
        if(isset($update['products']) && isset($add['products'])){
            foreach ($add['products'] as $key => $item){
                if(in_array($item,$update['products']) )
                {

                }else{
                    $resoult['products'][] = $item;
                    $resoult['quantity'][] = $add['quantity'][$key];
                    $resoult['price'][] = $add['price'][$key];
                }
            }
        }

        if(isset($update['products']) && !isset($add['products'])) return $update;
        if(isset($add['products']) && !isset($update['products'])) return $add;

        return $resoult;
    }


    public function remove_nigatef_quantity($array)
    {
        $resoult = [];
        if(isset($array['quantity'])){
            foreach ($array['quantity'] as $key => $item){
                if($item < 0)
                {

                }else{
                    $resoult['products'][] = $array['products'][$key];
                    $resoult['quantity'][] = $array['quantity'][$key];
                    $resoult['price'][] = $array['price'][$key];
                }
            }
        }

        return $resoult;
    }

    public function array_merge_multi($update,$add)
    {
        $resoult = [];
        if(is_array($update) && is_array($add) && isset($update['products']) && isset($add['products']) ) {

            $resoult['products'] = array_merge($update['products'],$add['products']);
            $resoult['quantity'] = array_merge($update['quantity'],$add['quantity']);
            $resoult['price'] = array_merge($update['price'],$add['price']);

        }
        elseif (is_array($update) && isset($update['products']) && !isset($add['products']) ) {
            return $update;
        }
        elseif (is_array($add) && isset($add['products']) && !isset($update['products']) )
        {
            return $add;
        }
        return $resoult;

    }

    public function get_array_diff_removed($old,$new)
    {
        $resoult = [];
        $new['price'] = isset($new['price']) ? self::array_decimals($new['price']) : [];
        if(isset($new['products']) && isset($old['products'])) {
            foreach ($old['products'] as $key => $item) {
                if (in_array($item, $new['products']) && in_array($old['quantity'][$key], $new['quantity']) && in_array($old['price'][$key], $new['price'])) {

                } else {
                    $resoult['products'][] = $item;
                    $resoult['quantity'][] = $old['quantity'][$key];
                    $resoult['price'][] = $old['price'][$key];
                }
            }
        }
        return $resoult;
    }

//    new functions

    public function time_elapsed_string($datetime , $full = false) {
        $now = new \DateTime();
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);


        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = $this->Language->get('date_ago_eleven_up');
        $date_ago_eleven_up = $this->Language->get('date_ago_eleven_up');
        $date_ago_ten_tow = $this->Language->get('date_ago_ten_tow');
        $date_ago_tow = $this->Language->get('date_ago_tow');

        foreach ($string as $k => &$v) {
            if ($this->Language->getLangUser() == 'en')
            {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $date_ago_eleven_up[$k] . ($diff->$k > 1 ? 's' : '');
                }
                else{
                    unset($string[$k]);
                }
                continue;
            }
            if ($diff->$k == 2) {
                $v = $date_ago_tow[$k] ;
            }
            else if ($diff->$k > 2 && $diff->$k < 11){
                $v = $diff->$k . ' ' . $date_ago_ten_tow[$k];
            }
            else if ($diff->$k > 10 || $diff->$k == 1){
                $v = $diff->$k . ' ' . $date_ago_eleven_up[$k] ;
            }else{
                unset($string[$k]);
            }
        }



        if (!$full) $string = array_slice($string,0,1);

        if($this->Language->getLangUser() == 'ar'){
            $string = $string ? 'مند ' . implode(', ', $string) : 'الأن';
        }else{
            $string = $string ? implode(', ', $string) . ' ' . $this->Language->get('ago') : $this->Language->get('just_now');
        }
        $string = preg_replace('/\s+1\s+/',' ',$string);
        return $string;
    }

    public static function array_decimals($array)
    {
        $n = [];
        $decimal = Currency::decimal();
        if (count($array) > 0)
        {
            foreach ($array as $a)
            {
                $n[] =  number_format($a,$decimal);
            }
        }

        return $n;
    }

    public static function post($post)
    {
        if(self::is_post($post)){
            return $_POST[$post];
        }

        return '';
    }

    public static function get($get)
    {
        if(self::is_get($get)){
            return $_GET[$get];
        }
        return '';
    }

    public static function is_post($post)
    {
        return isset($_POST[$post]) && $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
    }

    public static function is_post_only($post)
    {
        return isset($_POST[$post])  ? true : false;
    }

    public static function is_get($get)
    {
        return isset($_GET[$get]) ? true : false;
    }

    public function main_url()
    {
        return sprintf("%s://%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME']
        );
    }

    public static function Curl_output($url)
    {
        if(!preg_match('/^http/i',$url))
        {
            $url        = APP_LINK . '/' .trim($url,'/');
        }
        $url = str_replace('\\','/',$url);
        $ch         = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }


//    Numbers format

    public static function format_num($number)
    {
        $decimal = Settings::get('settings-numbers-formatting')->Decimals;
        $number = number_format($number,$decimal,Currency::decimal_separator(),Currency::decimal_thousands());
        return self::number_zero($number);
    }

    public function format_num_dash($number)
    {
        if($number > 1000000)
        {
            $smby = $this->Language->getLangUser() == 'ar' ? 'مليون' : 'M';
            return preg_replace('/^([0-9]{1,3})[0-9]{6}$/','$1',$number) . $smby;
        }
        elseif($number > 1000)
        {
            $smby = $this->Language->getLangUser() == 'ar' ? 'ألف' : 'K';
            return preg_replace('/^([0-9]{1,3})[0-9]{3}$/','$1',$number) . $smby;

        }
        else{
            return $number;
        }
    }

    public static function number_zero($number)
    {
        $number = preg_replace('/^([0-9, ]+)\.0+$/','$1',$number);
        $number = preg_replace('/^([0-9, ]+\.[1-9]+)0+$/','$1',$number);
        return $number;
    }

    public static function number_parse($number,$curr = true)
    {
        if(preg_match('/^[0-9]{1,18}(\.[0-9]{1,8})?$/',$number) && is_numeric($number))
        {
            return self::number_zero(Currency::out_currency($number,$curr));
        }
        return $number;
    }

    public static function decimal_insert($number)
    {
        if(preg_match('/^[0-9]{1,18}(\.[0-9]{1,8})?$/',$number) && is_numeric($number))
        {
            return Currency::inside_currency($number);
        }
        return $number;
    }

    public static function total_invoice($sum,$discount,$tax = 0,$discou = false,$format=true)
    {
        $dis = self::format_num_dic_tax($discount,$sum);
        if(!self::discount_allow()) $dis = 0;
        $number = $sum - $dis + $tax;
        return $discou ? $dis : Currency::out_currency($number,$format) ;
    }

    public static function tax_invoice($invoiceId,$trans = true,$format = true)
    {
        if(!self::tax_allow()) return $trans ? Currency::out_currency(0,$format) : 0;

        $sales = SalesModel::getBy('InvoiceId',$invoiceId,true);
        $tax = 0;
        foreach ($sales as $sale) {
            $tax_one = ProductsModel::getByKey($sale->ProductId)->Tax;
            $tax += self::format_num_dic_tax($tax_one,$sale->SellPrice) * $sale->QuantitySales;
        }
        return $trans ? Currency::out_currency($tax,$format) : $tax;
    }

    public static function tax($productId,$invoiceId,$tax,$htmltag=true)
    {
        if(!self::tax_allow()) return  0;

        $sales = SalesModel::getByCols(['InvoiceId' => $invoiceId , 'ProductId' => $productId]);

        $price = $sales[0]->SellPrice;
        $tax_f = self::format_num_dic_tax($tax, $price);
        if ($htmltag) {
            $tax_f = self::tax_html_tag($tax,$tax_f,$price);
        }else{
            $tax_f = Currency::out_currency($tax_f);
        }

        return $tax_f;
    }

    public static function tax_html_tag($des,$tax,$price=0,$currency = true)
    {
        if (preg_match('/^[0-9]{1,3}(\.[0-9]{1,2})?%$/',$des))
        {
            $tax = $currency ? Currency::out_currency($tax) : $tax;
            return "<bdi>$tax<sub> ($des)</sub></bdi>";
        }else{
            if ($price == 0) return 0;
            $tax_miya = $tax * 100 / $price;
            $tax = $currency ? Currency::out_currency($tax) : $tax;
            $tax_miya = self::format_num($tax_miya);
            return "<bdi>$tax<sub> ($tax_miya%)</sub></bdi>";
        }
    }


    public static function format_num_dic_tax($discount,$amount = null)
    {
        if(preg_match('/^[0-9]{1,3}(\.[0-9]{1,2})?%$/',$discount)){
            $discount = str_replace('%','',$discount);
            $discount = $discount * $amount / 100;
        }
        return is_numeric($discount) ? $discount : false;
    }

    public static function format_quantity($number,$full_format = true)
    {
        $decimal    = Settings::get('settings-numbers-formatting')->QuantityDecimals;

        $decimal_separator      = Settings::get('settings-numbers-formatting')->DecimalsSeparator;
        $Sep_dec = [1=>',',2=>'.',3=>''];

        $thousands_separator    = Settings::get('settings-numbers-formatting')->ThousandsSeparator;
        $Sep_tho = [1=>',',2=>'.',3=>' ',4=>''];


        if ($decimal == 8 && $full_format == true) $decimal = 0;
        if ($decimal == 8 && $full_format == false) return $number;
        if ($decimal == 7 && $full_format == false) return self::number_zero($number);
        if ($decimal == 7 && $full_format == true)
        {
            $number = number_format($number,6,$Sep_dec[$decimal_separator],$Sep_tho[$thousands_separator]);
            return self::number_zero($number);
        }


        return $full_format ? number_format($number,$decimal,$Sep_dec[$decimal_separator],$Sep_tho[$thousands_separator]) : number_format($number,$decimal);
    }

    public function getUnitName($id)
    {
        $pro = new ProductsModel();
        return $pro->inner_join('ProductId',$id);
    }

    public static function get_expanses_profit()
    {
        $sales = SalesModel::inner_join();
//        $purchases = PurchasesModel::inner_join();
        $expanses = ExpensesModel::getAll();
        $invoice = SalesInvoicesModel::inner_join();

        $profit = 0;
        $expans = 0;

        foreach ($invoice as $inv) {
            $expans += self::total_invoice($inv->Sum,$inv->Discount,0,true,false);
        }


        foreach ($sales as $sale) {
            // ORDER BY CreatedDate LIMIT 5
            $b = DB::statement('SELECT * FROM app_purchases WHERE ProductId=? AND CreatedDate < ? ORDER BY CreatedDate DESC LIMIT 1',[$sale->ProductId,$sale->CreatedDate],true)->get();

            if(!empty($b))
            {
                $b = $b[0];
                $profit += ( $sale->SellPrice - $b->PurchasePrice ) * $sale->QuantitySales;
            }else{
                $profit += ( $sale->SellPrice - $sale->BuyPrice ) * $sale->QuantitySales;
            }

        }

        foreach ($expanses as $expanse) {
            $expans += $expanse->Payment;
        }


        return [Currency::out_currency($profit,false),Currency::out_currency($expans,false)];

    }


    public static function tax_allow()
    {
        return isset(Settings::get('setting-products')->TaxesProducts) && Settings::get('setting-products')->TaxesProducts == 1 ? true : false ;
    }

    public static function discount_allow()
    {
        return isset(Settings::get('setting-products')->DiscountProducts) && Settings::get('setting-products')->DiscountProducts == 1 ? true : false ;
    }


    public function replace_link($content,$link)
    {
        $content = $this->notification_content($content);
        if (trim($link,'/') == '') return $content;
        return preg_replace('/\|\s*([^\|]+)\s*\|/',"<a class='not-link' href='/" . trim($link,'/') ."'>$1</a>",$content);

    }

    public function getNameUsername()
    {
        $UserId = $this->Session->User->UserId;
        $Username = $this->Session->User->Username;
        $profile = ProfileModel::getBy('UserId',$UserId);
        if (!empty($profile) && isset($profile->FirstName) && isset($profile->LastName)){
            return $profile->FirstName . ' ' . $profile->LastName;
        }
        return $Username;
    }

    public function notification_title($title)
    {
        $preg = preg_match('/^\{\{[^ ]+\}\}$/',$title);
        if ($preg)
        {
            $b = str_replace('{{','',$title);
            $b = str_replace('}}','',$b);
            unset($title);
            return $this->Language->get($b);
        }
        return $title;
    }

    public function notification_content($content)
    {
        $preg = preg_match('/^\{\{.+\}\}$/',$content);
        if ($preg)
        {
            $content = str_replace('{{','',$content);
            $content = str_replace('}}','',$content);
            $stringexplode = explode('!!',$content);
            $content_array = $stringexplode[0];
            if (isset($stringexplode[1])){
                $content_args = $stringexplode[1];
                $stringexplode = explode(',',$content_args);
                $content = $this->Language->getWithParams($content_array,$stringexplode);
            }else{
                $content = $this->Language->get($content_array);
            }
        }
        return $content;
    }

    public static function getIPAddress()
    {
        $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

}






//
//[1] => stdClass Object
//(
//    [PurchaseId] => 45
//            [PurchasePrice] => 0.00
//            [QuantityPurchases] => 20
//            [ProductId] => 1
//            [InvoiceId] => 32
//        )