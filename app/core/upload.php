<?php

namespace Store\core;


class Upload
{
    use Helper;
    private $file;
    private $lang;
    private $Messenger;
    private $is_valid = true;
    public  $rules;
    public  $file_name;
    public  $image_data = null;

    public function __construct($file,$lang)
    {
        $this->file = $file;
        $this->lang = $lang;
        $this->Messenger = Messenger::getInstance();
    }

    public function delete_file($file)
    {
        if(file_exists($file)){
            unlink($file);
        }
        return true;
    }

    public function Upload()
    {
        if($this->image_data != null && $this->file['name'] != ''){
            $acpt =explode('.',$this->file['name']);
            $acpt = end($acpt);
            $base = self::decode($this->image_data);
            $file_name = md5(time()) . '.' . $acpt;
            $file_path = UPLOADS_PATH . DS . $file_name ;
            $file_path = trim($file_path,DS);

            $this->file_name = $file_name;

            if(!file_exists($file_path))
            {
                fopen($file_path, "w");
                if(file_exists($file_path)){
                    file_put_contents($file_path,$base);
                    return true;
                }else{
                    return false;
                }

            }

        }
        elseif($this->is_valid  && $this->file['name'] != ''){
            $acpt =explode('.',$this->file['name']);
            $acpt = end($acpt);
            $targit_file = UPLOADS_PATH . DS . md5(time()) . '.' . $acpt;
            if(!file_exists($targit_file))
            {
                if(move_uploaded_file($this->file['tmp_name'],$targit_file)){
                    return true;
                }
            }
        }
        return false;
    }

    public function Process()
    {
        foreach ($this->rules as $key => $rule) {
            if($this->exist_rule('require',$rule) && empty($this->file['name']))
            {
                $this->lang->feed_key('error_required', [$this->lang->get('label_' .$key)]);
            }
            elseif( !empty($this->file['name']) && $this->exist_rule('accept',$rule) && !$this->valid_accept($this->get_rule('accept',$rule)))
            {
                $this->lang->feed_key('error_accept', [$this->lang->get('label_' .$key)]);
            }
            elseif( !empty($this->file['name']) &&$this->exist_rule('max',$rule) && !$this->valid_max($this->get_rule('max',$rule))){
                $this->lang->feed_key('error_size_max', [[$this->lang->get('label_' .$key),$this->get_rule('max',$rule)]]);
            }
            elseif( !empty($this->file['name']) && $this->exist_rule('min',$rule) && !$this->valid_min($this->get_rule('min',$rule))){
                $this->lang->feed_key('error_size_min', [[$this->lang->get('label_' .$key),$this->get_rule('min',$rule)]]);
            }else{
                return true;
            }

            $this->is_valid = false;
            return false;
        }
    }



    public function valid_accept($accepts)
    {
        $name = $this->file['name'];
        $acpt =explode('.',$name);
        $acpt = end($acpt);

        $accepts = explode(',',$accepts);
        if(in_array($acpt,$accepts))
        {
            return true;
        }

        return false;

    }

    public function valid_max($max)
    {
        $max_file = $this->file['size'];
        if($max_file < $max)
        {
            return true;
        }
        return false;

    }

    public function valid_min($max)
    {
        $max_file = $this->file['size'];
        if($max_file > $max)
        {
            return true;
        }
        return false;

    }

    public function exist_rule($rule,$rule_text)// require
    {
        if(!empty($this->rules)){
            preg_match_all('/'.$rule.'/',$rule_text,$matched);
            if(isset($matched[0][0])) return $matched[0][0] == $rule ? true : false ;
        }
        return false;
    }

    public function get_rule($rule,$rule_text) // access:png,jpg,gif
    {
        if($this->exist_rule($rule,$rule_text) == true){
            preg_match_all('/'.$rule.'\:?[^|]*/',$rule_text,$matched);
            $match = isset($matched[0][0]) ? $matched[0][0] : '';
            if(preg_match('/:/',$match)){
                $spleet = preg_split('/:/',$match);
                return end($spleet);
            }
            return $match;
        }
        return false;
    }


}
// $this->rules = 'require|access:png,gif|'