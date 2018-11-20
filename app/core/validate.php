<?php

namespace Store\Core;

use Store\Models\PurchasesModel;
use Store\Models\SalesModel;

class Validate
{
    use Helper;
    public $Language = null;
    public $options;
    public static $regex = [
        'num' => '/^[-+]?[0-9]+(\.:?[0-9]+)?$/',
        'decimal' => '/^[0-9]+\.[0-9]{1,2}$/',
        'quantity' => '/^([0-9]+(\.[0-9]{1,6})?)?$/',
        'discount' => '/^[0-9]{1,18}(\.[0-9]{1,8})?$|^[0-9]{1,3}(\.[0-9]{1,2})?%$/',
        'alpha_decimal' => '/^[0-9]+(\.[0-9]{1,8})?$/',
        'int' => '/^[0-9]+$/',
        'requestUrl' => '/^[A-Za-z]+(\/[A-z]+)?$/',
        'UrlPages' => '/^(\/?[A-Za-z0-9]+\/?){0,80}(\??[a-zA-Z0-9]+(\=[a-zA-Z0-9]+(\&\&[A-Za-z0-9]+\=[a-zA-Z0-9]+)?)?)?$/',
        'word_english' => '/^[A-Za-z]+$/',
        'password' => '/^.*$/',
        'words' => '/^[a-zA-Z \p{Arabic}]+$/u',
        'alpha_num' => '/^[0-9A-z\p{Arabic}]+$/',
        'alpha_pass' => '/^(?=.*[A-z])(?=.*[0-9]).+$/',
        'alpha_dash' => '/^(?=[A-z\p{Arabic}]*[0-9_-])(?=[0-9]*[A-z\p{Arabic}]).{0,}$/',
        'word' => '/^[A-z\p{Arabic}]+$/',
        'all' => '/^[^\'\"\>\<]+$/',
        'phone' => '/^[+(]{0,2}\d{3}[). -]{0,2}[- .]?[0-9]{3}[-. ]?[\d]+$/',
        'date' => '/^([0-9][0-9][0-9][0-9])-(0:?[0-9]|1[0-2])-(3:?[0-1]|[0-2][0-9])$/',
        'time' => '/^(0:?[0-9]|[1-2][0-9]):(5:?[0-9]|[0-4][0-9]):(5:?[0-9]|[0-4][0-9])$/',
        'date_time' => '/^([0-9][0-9][0-9][0-9])-(0:?[0-9]|1[0-2])-(3:?[0-1]|[1-2][0-9]) (0:?[0-9]|[1-2][0-9]):(5:?[0-9]|[0-4][0-9]):(5:?[0-9]|[0-4][0-9])$/',
        'email'     => '/^([A-z0-9-_.]+)@([A-z0-9.-_]+)\.([A-z]{2,})$/',
        'address'     => '/^:?[A-z -\/,0-9\p{Arabic}]+$/u',
        'text'     => '/^[\w\|\(\)\:\?\!\-\,\.\/\' 0-9\p{Arabic}]+$/u',
        'barcode'     => '/^[A-Za-z0-9]+$/',
    ];

    const REGEX_NUM = 'num';
    const REGEX_INT = 'int';
    const REGEX_DECIMAL = 'decimal';
    const REGEX_ALPHA_NUM = 'alpha_num';
    const REGEX_ALPHA_DASH = 'alpha_dash';
    const REGEX_WORD = 'word';
    const REGEX_DATE = 'date';
    const REGEX_TIME = 'time';
    const REGEX_DATE_TIME = 'date_time';
    const REGEX_EMAIL = 'email';
    
    public $rules = null;
    public $data = array();
    public $error_list = array();
    public $primary = null;

//    classes

    public $Messenger = null;

    public function __construct($Language)
    {
        $this->Language = $Language;
        $this->Messenger = Messenger::getInstance();
    }

    public function check()
    {
        foreach ($this->rules as $input_name => $rule) {
            $input_value = isset($this->data[$input_name]) && !is_array($this->data[$input_name]) ? trim($this->data[$input_name]) : '';
            $value_arrays = isset($this->data[$input_name]) && is_array($this->data[$input_name]) ? $this->data[$input_name] : [];
            if($this->exist_rule($input_name.'.is_array') && $input_value === '') {
                $error_req = false;
                $error_post_unq = false;

                if ($this->exist_rule($input_name . '.required') && $value_arrays == '' && $error_req !=true) {
                    $this->error_list['required'][] = $this->Language->get('label_' . $input_name);
                    $error_req = true;
                }
                foreach ($value_arrays as $key => $value) {
                    if ($this->exist_rule($input_name . '.required') && $value == '' && $error_req != true) {
                        $this->error_list['required'][] = $this->Language->get('label_' . $input_name);
                        $error_req = true;
                    }
                    elseif ($this->exist_rule($input_name.'.max') && !empty($value) && !self::valid_max($value,$this->get_rule($input_name.'.max'),$this->get_rule($input_name.'.type'))){
                        $this->error_list['max'][] = [$this->Language->get('label_'.$input_name),$this->get_rule($input_name.'.max')];
                    }
                    elseif (($this->exist_rule($input_name . '.foreign') && !self::valid_foreign($value, $this->get_rule($input_name . '.foreign')) && ($value != (int)0 || $input_value != '')) || ($this->exist_rule($input_name . '.list') && !self::valid_list($value, $this->get_rule($input_name . '.list')) && ($value != (int)0 || $value != ''))) {
                        $this->error_list['problem'][] = '';
                        $this->enter();
                        return false;
                    }
                    if ($error_post_unq != true && $this->exist_rule($input_name . '.post_unq') && array_count_values($value_arrays)[$value] > 1) {
                        $this->error_list['post_unq'][] = $this->Language->get('label_' . $this->get_rule($input_name . '.post_unq'));
                        $error_post_unq = true;
                    }
                    if ($error_post_unq != true && $this->exist_rule($input_name . '.custom_max_col')) {
                        $fullTable = $this->get_rule($input_name . '.custom_max_col');
                        $explT = explode('.', $fullTable);
                        $table = $explT[0];
                        $col = $explT[1];
                        $data = $this->data[$col];
                        if (is_array($data)) {
                            foreach ($data as $key => $item) {
                                $is = $this->valid_custom_max_col($fullTable, $item, $this->data['quantity'][$key]);
                                if (!$is[0] && $is[1] != false) {
                                    $this->error_list['quantity'][] = $is[1];
                                    if (end($data) == $item) {
                                        $this->enter();
                                        return false;
                                    }
                                }else{
                                    $this->enter();
                                    return false;
                                }
                            }

                        }
                    }

                    if ($error_post_unq != true && $this->exist_rule($input_name . '.custom_product'))
                    {
                        $purchases = new PurchasesModel();
                        $purchases = $purchases->getAll();
                        $old_purchases = self::ready_array_purchases($purchases);
                        $new_purchases = ['products'=>$this->data['product_id'],'quantity'=>$this->data['quantity'],'price'=>$this->data['price']];

                        $purchases_add = $this->array_diff_multiple($old_purchases,$new_purchases);

                        $varibal = self::ready_updated_products($purchases_add,$old_purchases);

                        if(isset($varibal['products']))
                        {
                            foreach ($varibal['products'] as $key2 =>$product_id) {
                                if($varibal['quantity'][$key2] < 0)
                                {
                                    $is_seccess = $this->valid_custom_product($product_id,$varibal['quantity'][$key2]);
                                    if(!$is_seccess[0])
                                    {
                                        $this->error_list['product_lack'][] = $is_seccess[1];
                                        if (end($varibal) == $product_id) {
                                            $this->enter();
                                            return false;
                                        }
                                    }
                                }

                            }
                        }

                    }

                    if ($error_post_unq != true && $this->exist_rule($input_name . '.custom_sales'))
                    {
                        $SalesModel = new SalesModel();

                        $old_sales = self::ready_array_sales($this->options['Sales']);
                        $old_sales = self::out_currency_products($old_sales);
                        $new_sales = ['products'=>$_POST['product_id'],'quantity'=>$_POST['quantity'],'price'=>$_POST['price']];

                        $sales_add = $this->array_diff_multiple($old_sales,$new_sales);

                        $sales_updated_1 = $this->ready_updated_products($sales_add,$old_sales);

                        $sales_updated = $this->remove_nigatef_quantity($sales_updated_1);
                        $sales_added = $this->array_diff_up_ad($sales_updated_1,$sales_add);

                        $valid = $this->array_merge_multi($sales_added,$sales_updated);
                        if (isset($valid['products']))
                        {
                            foreach ($valid['products'] as $key => $product)
                            {
                                $is_seccess = $this->valid_custom_product($product,$valid['quantity'][$key]);
                                if(!$is_seccess[0])
                                {
                                    $this->error_list['product_lack'][] = $is_seccess[1];
                                    if (end($valid) == $product) {
                                        $this->enter();
                                        return false;
                                    }
                                }

                            }
                        }

                    }

                }
            }
            elseif($this->exist_rule($input_name.'.required') && $input_value === ''){
                $this->error_list['required'][] = $this->Language->get('label_'.$input_name);
            }
            elseif ($this->exist_rule($input_name.'.type') && !self::valid($input_value,$this->get_rule($input_name.'.type')) && !empty($input_value) ){
                $this->error_list['type'][] = $this->Language->get('label_'.$input_name);
            }
            elseif ($this->exist_rule($input_name.'.min') && !empty($input_value) && !self::valid_min($input_value,$this->get_rule($input_name.'.min'),$this->get_rule($input_name.'.type'))){
                $this->error_list['min'][] = [$this->Language->get('label_'.$input_name),$this->get_rule($input_name.'.min')];
            }
            elseif ($this->exist_rule($input_name.'.min') && !empty($input_value) && !self::valid_max($input_value,$this->get_rule($input_name.'.max'),$this->get_rule($input_name.'.type'))){
                $this->error_list['max'][] = [$this->Language->get('label_'.$input_name),$this->get_rule($input_name.'.max')];
            }
            elseif ($this->exist_rule($input_name.'.confirmation') && isset($this->data[$this->get_rule($input_name.'.confirmation')]) && $this->data[$this->get_rule($input_name.'.confirmation')] != $input_value ){
                $this->error_list['confirmation'][] = $this->Language->get('label_'.$input_name);
            }
            if ( !empty($input_value) && $this->exist_rule($input_name.'.unique') && self::valid_unique($input_value,$this->get_rule($input_name.'.unique'), $input_name))
            {
                $this->error_list['unique'][] = $this->Language->get('label_'.$input_name);
            }elseif ( !empty($input_value) && $this->exist_rule($input_name.'.same_unq') && $this->valid_same_unq($input_value,$this->get_rule($input_name.'.same_unq'), $input_name))
            {
                $this->error_list['unique'][] = $this->Language->get('label_'.$input_name);
            }
            if ( !empty($input_value) && ($this->exist_rule($input_name.'.foreign') && !self::valid_foreign($input_value,$this->get_rule($input_name.'.foreign')) && ($input_value != (int) 0 || $input_value != '' ) ) || ($this->exist_rule($input_name.'.list') && !self::valid_list($input_value,$this->get_rule($input_name.'.list')) && ($input_value != (int) 0 || $input_value != '' )) )
            {
                $this->error_list['problem'][] = '';
                $this->enter();
                return false;
            }

            if(!empty($input_value) && $this->exist_rule($input_name.'.key_exists') && $this->Language->key_exist_in_array($input_value,$this->get_rule($input_name.'.key_exists')) == false )
            {
                $this->error_list['problem'][] = '';
                $this->enter();
                return false;
            }


            if( !empty($input_value) && $this->exist_rule($input_name.'.match') && !$this->valid_match($input_value,$this->get_rule($input_name.'.match'))){
                $this->error_list['problem'][] = '';
                $this->enter();
                return false;
            }

            if($this->exist_rule($input_name.'.in_json_url') && !$this->valid_json_url($input_value,$this->get_rule($input_name.'.in_json_url')))
            {
                $this->error_list['problem'][] = '';
                $this->enter();
                return false;
            }


            if($this->exist_rule($input_name.'.is_true') && !$this->get_rule($input_name.'.is_true'))
            {
                $this->error_list['problem'][] = '';
                $this->enter();
                return false;
            }

        }

        $this->enter();

        return $this->Messenger->has_Messengers();

    }



    public function enter()
    {

        if(isset($this->error_list['quantity'])) $this->Language->feed_key('error_quantity_product',array_unique($this->error_list['quantity']));

        if(isset($this->error_list['product_lack'])) $this->Language->feed_key('error_product_lack',array_unique($this->error_list['product_lack']));

        if(isset($this->error_list['max'])) $this->Language->feed_key('error_max',$this->error_list['max']);

        if(isset($this->error_list['min'])) $this->Language->feed_key('error_min',$this->error_list['min']);

        if(isset($this->error_list['required'])) $this->Language->feed_key('error_required',$this->error_list['required']);

        if(isset($this->error_list['post_unq'])) $this->Language->feed_key('error_post_unq',$this->error_list['post_unq']);

        if(isset($this->error_list['type'])) $this->Language->feed_key('error_type',$this->error_list['type']);

        if(isset($this->error_list['captcha'][0])) Messenger::getInstance()->create($this->error_list['captcha'][0],Messenger::APP_TYPE_ERROR);

        if(isset($this->error_list['confirmation'])) $this->Language->feed_key('error_confirmation',$this->error_list['confirmation']);

        if (isset($this->error_list['unique'])) $this->Language->feed_key('error_unique',$this->error_list['unique']);

        if (isset($this->error_list['problem'])){
            $this->Messenger->emptyMessengers();
            $this->Language->feed_key('error_problem_user',$this->error_list['problem']) ;
            return false;
        }


    }


    /**
     * @return null
     *'username.requred';
     */

    public function get_rule($rule)
    {
        if($this->exist_rule($rule) == true){
            $rules = explode('.',$rule);
            $rule_key = $rules[0];
            $rule_search = $rules[1];
            $rule_val = $this->rules[$rule_key];
            preg_match_all('/'.$rule_search.'\:?[^|]*/',$rule_val,$matched);
            $match = isset($matched[0][0]) ? $matched[0][0] : '';
            if(preg_match('/:/',$match)){
                $spleet = preg_split('/:/',$match);
                return end($spleet);
            }
            return $match;
        }
        return false;
    }


    public function exist_rule($rule)
    {
        $rules = explode('.',$rule);
        $rule_key = isset($rules[0]) ? $rules[0] : '';
        $rule_search = isset($rules[1]) ? $rules[1] : '';;
        if(isset($this->rules[$rule_key])){
            preg_match_all('/'.$rule_search.'/',$this->rules[$rule_key],$matched);
            if(isset($matched[0][0])) return $matched[0][0] == $rule_search ? true : false ;
        }
        return false;
    }







    /**
     * @return boolean
     * valid all regex in up
     */
    public static function valid($data, $type)
    {
        if(!isset(self::$regex[$type])){
             die('<b>error:</b> undefined this regex <b>'.$type.'</b> in property Validation->regex');
        }
        return (bool) preg_match(self::$regex[$type],$data);
    }


    public static function is_num($type)
    {
        $_types = ['num','decimal','int'];
        if(in_array($type,$_types)){
            return true;
        }
        return false;
    }

    public static function valid_min($val,$range,$type){
        if(self::is_num($type)){
            return $val >= $range;
        }else{
            return mb_strlen($val) >= $range;
        }
    }

    public static function valid_max($val,$range,$type){
        if(self::is_num($type)){
            return $val <= $range;
        }else{
            return mb_strlen($val) <= $range;
        }
    }

    public static function valid_unique($val, $table, $column)
    {
        return (boolean) DB::table($table)->select($column)->where($column , '=',$val)->row_count();
    }

    public function valid_same_unq($val, $table, $column)
    {
        $col_primary = isset(array_keys($this->primary)[0]) ? array_keys($this->primary)[0] : '';
        $sttmnt = DB::statement("select {$column} from {$table} where $column=? and $col_primary!=?",[$val,$this->primary[$col_primary]])->rowCount();
        return $sttmnt > 0 ? true : false ;
    }



    public static function valid_foreign($val, $table_column)
    {
        $table  = explode('.',$table_column)[0];
        $column = explode('.',$table_column)[1];

        return (boolean) DB::table($table)->select($column)->where($column , '=',$val)->row_count();
    }


    public static function valid_list($val, $list, $delimiter=',')
    {
        $searchin = explode($delimiter, $list);
        return in_array($val, $searchin) ? true : false;
    }

    public function valid_match($val, $table_column)
    {
        $table  = explode('.',$table_column)[0];
        $column = explode('.',$table_column)[1];

        $col_primary = isset(array_keys($this->primary)[0]) ? array_keys($this->primary)[0] : '';
        $sttmnt = DB::statement("select {$column} from {$table} where $column=? and $col_primary=?",[$val,$this->primary[$col_primary]])->rowCount();
        return $sttmnt == 1 ? true : false ;

    }

    public function valid_custom_max_col($full_table,$where,$val)
    {
        $explT = explode('.',$full_table);
        $table = $explT[0];
        $col = $explT[1];


        $product = DB::statement("select * from $table where ProductId=?",[$where],true)->getColumn();

        return isset($product->Quantity) && isset($product->Title) ? [$product->Quantity >= $val , $product->Title] : [false,false];
    }


    public function valid_custom_product($product_id,$quantity_lack)
    {

        $product = DB::statement("select * from app_products where ProductId=?",[$product_id],true)->getColumn();

        return [$product->Quantity >= abs($quantity_lack) , $product->Title];

    }



    public function valid_json_url($input_val,$json_val)
    {
        $json_val   = str_replace('{value}',$input_val,$json_val);
        $output = self::Curl_output($json_val);
        if($output != '')
        {
            return true;
        }
        return false;
    }




}

