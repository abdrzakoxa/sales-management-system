<?php

namespace Store\Core;


class Languages
{
    use Sanitize;
    use helper;

    private $time, $cookie;
    public $Language, $lexicon = array() , $feed_key;


    const myLang = ['ar','en'];

    const DefaultLang = 'ar';

    const LangOld = 30;

    public function __construct($time = '')
    {
        $this->time = empty($time) ? SELF::LangOld : $time;
        $this->Language = SELF::getLangUser();
    }

    public function feed_key($key,$params )
    {
        if($this->is_multi($params) && array_key_exists($key,$this->lexicon)){
            foreach ($params as $param) {
                $string = $this->lexicon[$key];
                array_unshift($param,$string);
                Messenger::getInstance()->create(call_user_func_array('sprintf',$param),Messenger::APP_TYPE_ERROR);
            }
        }
    elseif(array_key_exists($key,$this->lexicon))
        {
            if(count($params) <= 1){
                $string = $this->get($key);
                Messenger::getInstance()->create(sprintf($string,$params[0]),Messenger::APP_TYPE_ERROR);
            }else{
                $args = implode($this->get('text_and_space'),$params);
                $string = $this->get($key . 's');
                Messenger::getInstance()->create(sprintf($string,$args),Messenger::APP_TYPE_ERROR);
            }
        }
    }

    public function load($path)
    {
        $path = str_replace('.',DS,$path);
        $path = LANGUAGE_PATH . DS . $this->Language . DS . $path . '.lang.php';

        $path = self::filterPath($path);

        if(file_exists($path)){
            $langl = $this->lexicon;

            unset($_);

            require $path;
            $listLang = isset($_) ? $_ : [] ;

            $this->lexicon = array_merge($listLang,$this->lexicon);

        }
    }

    public function set_lexicon($lang)
    {
        if (is_array($lang))
        {
            if (count($this->lexicon) == 0)
            {
                $this->lexicon = $lang;
            }else  {
                $this->lexicon = array_merge($this->lexicon,$lang);
            }
        }

    }

    public function changeLang($newLang)
    {
        if(SELF::inMyLang($newLang)){
            $this->cookie->change($newLang);
            $this->Language = $newLang;
            return $this;
        }
        return false;
    }

    public function key_exist_in_array($key,$key_array)
    {
        if(array_key_exists($key,$this->lexicon[$key_array]))
        {
           return true;
        }
        return false;
    }

    public function getLexicon()
    {
        return $this->lexicon;
    }

    public static function getLangUser()
    {

        if(Cookies::is_set('lang')){
            return Cookies::need('lang');
        }

        return Settings::get('setting-site')->Language;
    }

    public function set()
    {
        $LangCookies = new Cookies('lang',$this->Language,$this->time);

        if($LangCookies->set()->is_enable()){

            $this->cookie = $LangCookies;

        }
    }

    public function get($key,$keytoo=null)
    {
        if($keytoo != null){
            return $this->lexicon[$key][$keytoo];
        }else{
            return isset($this->lexicon[$key]) ? $this->lexicon[$key] : false ;
        }
    }

    public function getWithParams($key,$params)
    {
        $key = $this->get($key);
        if(is_array($params))
        {
            $newParams = [$key];
            foreach ($params as $param) {
                $newParams[] = $param;
            }
            return call_user_func_array('sprintf',$newParams);
        }
        else{
            return sprintf($key,$params);
        }

    }

    public static function getLangBrowser()
    {
        $lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'ar';

        $lang = explode(';',$lang);

        $lang = explode(',',$lang[0]);

        $lang = SELF::filterString($lang[0]);

        if(SELF::inMyLang($lang)){
            return $lang;
        }
        return SELF::DefaultLang;

    }

    public static function inMyLang($lang)
    {
        if(in_array($lang,SELF::myLang)){
            return true;
        }
        return false;
    }

    public function filterLang($lang)
    {
        $lang = trim($lang);
        $lang = SELF::inMyLang($lang) ? $lang : $this->Language ;
        return $lang;
    }

    public function changeTo()
    {

        return $this->cookie->get() == 'ar' ? 'en' : 'ar' ;
    }

    public function getDir()
    {
        return $this->cookie->get() == 'ar' ? 'rtl' : 'ltr';
    }
}