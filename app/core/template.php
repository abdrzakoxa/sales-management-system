<?php
namespace Store\core;


use Store\Models\ProfileModel;

trait Template
{
    public $template_load = 'startcontent|navigation|:VIEW|endcontent';
    private $tpl;
    private $assign;


    public function tpl($path)
    {
        if(file_exists($path))
        {
            $this->tpl = file_get_contents($path);
            $this->file_view = $path;

        }else
        {
            exit('Error: the file <b>' . $path . '</b> is Not Exist');
        }
        return $this;

    }

    public function render_tpl($tpl = null)
    {

        $tpl = $tpl == null ? $this->tpl : file_get_contents($tpl);

        // symbols # = space = . ' ' . commonts

        $tpl = preg_replace('/<!---[^\-\-\>]+-->/', '' ,$tpl);
        $tpl = preg_replace('/(\s+)_(\s+)/', '$1. \' \' .$2' ,$tpl);

        /* {var}, {{var}}, {#var}, {#var#}*/

        $tpl = preg_replace('/#([a-zA-Z_]+)(\s*)/', '$this->Language->get(\'$1\')$2' ,$tpl);
        $tpl = preg_replace('/\{\!\s*([^!}]*)\s*\!\}(\s*)/', '<?php echo $1 ?>$2' ,$tpl);
        $tpl = preg_replace('/\{\{\s*(\S+)\s*\}\}(\s*)/', '<?php echo htmlspecialchars($1) ?>$2' ,$tpl);
        $tpl = preg_replace('/\{\s*(\S+)(\[\S+\])\s*\}/', '<?php echo $this->Language->get(\'$1\')$2 ?>' ,$tpl);
        $tpl = preg_replace('/\{\s*(\S+)(->\S+)\s*\}/', '<?php echo $this->Language->get(\'$1\')$2 ?>' ,$tpl);
        $tpl = preg_replace('/\{\s*([^0-9,\s]+)\s*\}/', '<?php echo $this->Language->get(\'$1\') ?>' ,$tpl);
        $tpl = preg_replace('/\{#\s*(\w+)\s*\#}(\s*)/', '<?php if(isset($$1) && $$1 != "") echo htmlspecialchars($$1); ?>$2' ,$tpl);
        $tpl = preg_replace('/\{#\s*(\w+)\s*\}(\s*)/', '<?php if(isset($$1) && $$1 != "") echo $$1 ; ?>$2' ,$tpl);
        /*        $tpl = preg_replace('/\[\s*(\w+)\s*\](\s*)/', '<?php echo $this->$1 ?>$2' ,$tpl);*/
        $tpl = preg_replace('/\<\!\s*(.*)\s*\!\>(\s*)/', '<?php $1 ?>$2' ,$tpl);


        /* @php, @endphp */
        $tpl = preg_replace('/@php(\s+)/', '<?php$1' ,$tpl);
        $tpl = preg_replace('/@endphp(\s+)/', '?>$1' ,$tpl);

        /* @if, @elseif, @else, @endif */
        $tpl = preg_replace('/@if \(\s*(.*)\s*\):/', '<?php if ($1) : ?>' ,$tpl);
        $tpl = preg_replace('/@else(\s+):/', '<?php else : ?>$1' ,$tpl);
        $tpl = preg_replace('/@if \(\s*(.*)\s*\)/', '<?php if ($1) : ?>' ,$tpl);
        $tpl = preg_replace('/@elseif \((.*)\)/', '<?php elseif($1) : ?>' ,$tpl);
        $tpl = preg_replace('/@else(\s+)/', '<?php else : ?>$1' ,$tpl);
        $tpl = preg_replace('/@endif(\s+)/', '<?php endif; ?>$1' ,$tpl);
        $tpl = preg_replace('/@notshort \(\s*(\S+)\s*\)/', '<?php if(!is_numeric($this->format_num_dash($1))) : ?>' ,$tpl);
        $tpl = preg_replace('/@tax_allow/', '<?php if (self::tax_allow($1)) : ?>' ,$tpl);
        $tpl = preg_replace('/@discount_allow/', '<?php if (self::discount_allow($1)) : ?>' ,$tpl);
        $tpl = preg_replace('/@discount_tax_allow/', '<?php if (self::discount_allow($1) || self::tax_allow($1)) : ?>' ,$tpl);
        $tpl = preg_replace('/@end(\s+)/', '<?php endif; ?>$1' ,$tpl);
        $tpl = preg_replace('/@is_permission_user \(\s*([^,\(\)]+),([^,\(\)]+),([^,\(\)]+),([^,\(\)]+)\s*\)/', '<?php if(self::is_permission_user(\'$1\') || self::is_permission_user(\'$2\') || self::is_permission_user(\'$3\') || self::is_permission_user(\'$4\')) : ?>' ,$tpl);
        $tpl = preg_replace('/@is_permission_user \(\s*([^,\(\)]+),([^,\(\)]+),([^,\(\)]+)\s*\)/', '<?php if(self::is_permission_user(\'$1\') || self::is_permission_user(\'$2\') || self::is_permission_user(\'$3\')) : ?>' ,$tpl);
        $tpl = preg_replace('/@is_permission_user \(\s*([^,\(\)]+),([^,\(\)]+)\s*\)/', '<?php if(self::is_permission_user(\'$1\') || self::is_permission_user(\'$2\')) : ?>' ,$tpl);
        $tpl = preg_replace('/@is_permission_user \(\s*([^,\(\)]+)\s*\)/', '<?php if(self::is_permission_user(\'$1\')) : ?>' ,$tpl);

        /* @for, @endfor, @foreach, @endforeach */

        $tpl = preg_replace('/@foreach \((.*)\)/', '<?php foreach($1) : ?>' ,$tpl);
        $tpl = preg_replace('/@endforeach(\s+)/', '<?php endforeach; ?>$1' ,$tpl);
        $tpl = preg_replace('/@for \((.*)\)/', '<?php for($1) : ?>' ,$tpl);
        $tpl = preg_replace('/@endfor(\s+)/', '<?php endfor; ?>$1' ,$tpl);

        /* @date_format($var,format), @date (format,$var), @now(format), @ipost(), @iget()   */
        $tpl = preg_replace('/@full_date_format \(\s*([^,\s]*)\s*\)/', '<?php echo date_format(date_create($1),self::Settings(\'settings-numbers-formatting\')->DateFormat . \' \' . self::Settings(\'settings-numbers-formatting\')->TimeFormat) ?>' ,$tpl);
        $tpl = preg_replace('/@date_format \(\s*([^,\s]*)\s*\)/', '<?php echo date_format(date_create($1),self::Settings(\'settings-numbers-formatting\')->DateFormat) ?>' ,$tpl);
        $tpl = preg_replace('/@time_format \(\s*([^,\s]*)\s*\)/', '<?php echo date_format(date_create($1),self::Settings(\'settings-numbers-formatting\')->TimeFormat) ?>' ,$tpl);
        $tpl = preg_replace('/@date_format \(\s*([^,]*)\s*,\s*([^)]*)\s*\)/', '<?php echo date_format(date_create($1),\'$2\') ?>' ,$tpl);
        $tpl = preg_replace('/@date \(\s*(.*),(.*)\s*\)/', '<?php echo date(\'$1\',$2) ?>' ,$tpl);
        $tpl = preg_replace('/@now \(\s*(.*)\s*\)/', '<?php echo empty(\'$1\') ? date(self::Settings(\'settings-numbers-formatting\')->DateFormat) : date(\'$1\'); ?>' ,$tpl);
        $tpl = preg_replace('/@ipost \(\s*(\S*)\s*\)/', '<?php echo isset($_POST[\'$1\']) ? htmlspecialchars($_POST[\'$1\']) : "" ; ?>' ,$tpl);
        $tpl = preg_replace('/@post \(\s*(\S+)\s*\)/', '<?php echo htmlspecialchars(self::post(\'$1\'));  ?>' ,$tpl);
        $tpl = preg_replace('/@is_post \(\s*(\S+)\s*\)/', '<?php self::is_post(\'$1\')  ?>' ,$tpl);
        $tpl = preg_replace('/@iget \(\s*(.*)\s*\)/', '<?php echo isset($_GET[\'$1\']) ? htmlspecialchars($_GET[\'$1\']) : "" ; ?>' ,$tpl);
        $tpl = preg_replace('/@get \(\s*(.*)\s*\)/', '<?php echo htmlspecialchars($_GET[\'$1\'])  ?>' ,$tpl);
        $tpl = preg_replace('/@server \(\s*(\S+)\s*\)/', '<?php echo $_SERVER[\'$1\'] ?>' ,$tpl);
        $tpl = preg_replace('/@company_empty \(\)/', '<?php if(empty(self::Settings(\'setting-company\')->Name)) : ?>' ,$tpl);

        /* @isset, @endisset */
        $tpl = preg_replace('/@isset \((.*)\)/', '<?php if (isset($1) && !empty($1)) : ?>' ,$tpl);
        $tpl = preg_replace('/@endisset(\s+)/', '<?php endif; ?>$1' ,$tpl);



        /* @empty, @endempty */
        $tpl = preg_replace('/@empty \((\S+)\)/', '<?php if (empty($1)) : ?>' ,$tpl);
        $tpl = preg_replace('/@notempty \((.*)\):/', '<?php if (!empty($1)) : ?>' ,$tpl);
        $tpl = preg_replace('/@notempty \((\S+)\)/', '<?php if (!empty($1)) : ?>' ,$tpl);
        $tpl = preg_replace('/@endempty(\s*)/', '<?php endif; ?>$1' ,$tpl);

        /* custom functions  */
        $tpl = preg_replace('/@autoValue \(\s*([^,]+)\s*,\s*([^->]+)->(\S+)\s*\)/', '<?php if(self::is_post(\'$1\')) : echo htmlspecialchars(self::post(\'$1\')); elseif (isset($this->Language->get(\'$2\')->$3)) : echo $this->Language->get(\'$2\')->$3; endif; ?>' ,$tpl);
        $tpl = preg_replace('/@autoValue \(\s*([^,]+)\s*,\s*(\S+)\s*\)/', '<?php echo self::is_post(\'$1\') ? htmlspecialchars(self::post(\'$1\')) : $this->Language->get(\'$2\') ; ?>' ,$tpl);
        $tpl = preg_replace('/@auto_val_obj_post \(\s*([^,]+)\s*,\s*([^)]+)\s*\)/', '<?php if (self::is_post(\'$2\')) : echo self::post(\'$2\'); elseif (isset($1)) : echo $1; endif; ?>' ,$tpl);
        $tpl = preg_replace('/@auth_post \(\s*([^,]+)\s*,\s*(\S+)\s*\)/', '( self::is_post(\'$1\') && self::post(\'$1\') == $2 )' ,$tpl);
        $tpl = preg_replace('/@auth_obj \(\s*([^,]+)\s*,\s*([^)]+)\s*\)/', '( isset($1) && $1 == $2 )' ,$tpl);
        $tpl = preg_replace('/@auth_obj_post \(\s*([^,]+)\s*,\s*([^,]+)\s*,\s*([^)]+)\s*\)/', '<?php if ((isset($1) && $1 == $3) || (self::is_post(\'$2\') && self::post(\'$2\') == $3)) : ?>' ,$tpl);
        $tpl = preg_replace('/@SiteConfig \(\s*(\S+)\s*\)/', '<?php echo self::Settings(\'settings-site\')->$1 ?>' ,$tpl);
        $tpl = preg_replace('/@CompanyConfig \(\s*(\S+)\s*\)/', '<?php echo self::Settings(\'setting-company\')->$1 ?>' ,$tpl);
        $tpl = preg_replace('/@Currency \(\s*(\S+)\s*\)/', '<bdi><?php echo $this->Currency->out_currency($1) ?></bdi>' ,$tpl);
        $tpl = preg_replace('/@currency_input \(\s*(\S+)\s*\)/', '<?php echo $this->Currency->out_currency($1,false) ?>' ,$tpl);
        $tpl = preg_replace('/@inside_currency \(\s*(\S+)\s*\)/', '<?php echo $this->Currency->inside_currency($1) ?>' ,$tpl);
        $tpl = preg_replace('/@number_zero \(\s*(\S+)\s*\)/', '<?php echo self::number_zero($1) ?>' ,$tpl);
        $tpl = preg_replace('/@format_num_dash \(\s*(\S+)\s*\)/', '<?php echo $this->format_num_dash($1) ?>' ,$tpl);
        $tpl = preg_replace('/@format_num \(\s*(\S+)\s*\)/', '<?php echo self::format_num($1) ?>' ,$tpl);
        $tpl = preg_replace('/@number_parse \(\s*(\S+)\s*\)/', '<bdi><?php echo !empty (self::number_parse($1)) ? self::number_parse($1) : \'__\' ?></bdi>' ,$tpl);
        $tpl = preg_replace('/@number_parse_inp \(\s*(\S+)\s*\)/', '<?php echo self::number_parse($1) ?>' ,$tpl);
        $tpl = preg_replace('/@total_invoice \(\s*(\S+)\s*\)/', '<bdi><?php echo self::total_invoice($1) ?></bdi>' ,$tpl);
        $tpl = preg_replace('/@tax_invoice \(\s*(\S+)\s*\)/', '<bdi><?php echo self::tax_invoice($1) ?></bdi>' ,$tpl);
        $tpl = preg_replace('/@tax \(\s*(\S+)\s*\)/', '<bdi><?php echo self::tax($1) ?></bdi>' ,$tpl);
        $tpl = preg_replace('/@sprintf \(\s*(\S+)\s*\)/', '<?php echo sprintf($1) ?>' ,$tpl);
        $tpl = preg_replace('/@replace_link \(\s*(\S+)\s*\)/', '<?php echo $this->replace_link($1) ?>' ,$tpl);
        $tpl = preg_replace('/@time_elapsed_string \(\s*(\S+)\s*\)/', '<?php echo $this->time_elapsed_string($1) ?>' ,$tpl);
        $tpl = preg_replace('/@notification_title \(\s*(\S+)\s*\)/', '<?php echo $this->notification_title($1) ?>' ,$tpl);
        $tpl = preg_replace('/@notification_content \(\s*(\S+)\s*\)/', '<?php echo $this->notification_content($1) ?>' ,$tpl);
        $tpl = preg_replace('/@access \(\s*(\S+)\s*\)/', '<?php echo !empty($1) ? $1 : \'__\' ?>' ,$tpl);



        /* @pre */
        $tpl = preg_replace('/@pre \((\s*)(.*)(\s*)\)/', '<pre><?php$1 print_r($2) $3?></pre>' ,$tpl);
        $tpl = preg_replace('/@var_dump \((\s*)(.*)(\s*)\)/', '<pre><?php$1 var_dump($2) $3?></pre>' ,$tpl);
        /*__*/
        $tpl = preg_replace('/\~([a-zA-Z_]+)(\s*)/', '#$1$2' ,$tpl);



//        echo $tpl;
        eval(' ?> ' . $tpl . ' <?php ');
//        echo '--------------------------------';

    }

    public function render_header()
    {

        $this->render_tpl($this->config_template['structure']['starthead']);
        foreach ($this->config_template['header']['css'] as $key => $sources) {
            $action_key ='';
            if(strpos($key,'/') != false){
                $actoin = explode('/',$key);
                $controll = $actoin[0];
                $actoin = $actoin[1];
                $action_key = $actoin;
            }else{
                $actoin = strtolower($this->action);
                $controll = strtolower($key);
            }
            if ($controll[0] == '_'){
                if(ucfirst($this->removeStr('_' , $controll)) == $this->controller && $this->action == $actoin){
                    if(is_array($sources))
                    {
                        foreach ($sources as $source)
                        {
                            echo "\n            <link rel='stylesheet' href='$source' >";
                        }
                    }else{
                        echo "\n            <link rel='stylesheet' href='$sources' >";
                    }
                }
            }elseif ($controll[0] == '~'){
                if(ucfirst($this->removeStr('~' , $controll)) != $this->controller || $this->action != $actoin){
                    if(is_array($sources))
                    {
                        foreach ($sources as $source)
                        {
                            echo "\n            <link rel='stylesheet' href='$source' >";
                        }
                    }else{
                        echo "\n            <link rel='stylesheet' href='$sources' >";
                    }
                }
            }elseif($controll[0] == '-'){
                if(substr($controll , 1,2 )  == $this->Language->getLangUser()) {
                    if(is_array($sources))
                    {
                        foreach ($sources as $source)
                        {
                            echo "\n            <link rel='stylesheet' href='$source' >";
                        }
                    }else{
                        echo "\n            <link rel='stylesheet' href='$sources' >";
                    }                }
            }elseif($controll[0]){
                if(is_array($sources))
                {
                    foreach ($sources as $source)
                    {
                        echo "\n            <link rel='stylesheet' href='$source' >";
                    }
                }else{
                    echo "\n            <link rel='stylesheet' href='$sources' >";
                }              }
        }

        foreach ($this->config_template['header']['js'] as $key => $sources) {
            echo "\n            <script src='$sources'></script>";
        }

        foreach ($this->config_template['header']['js'] as $key => $sources) {
            echo "\n            <script src='$sources'></script>";
        }
        $this->render_tpl($this->config_template['structure']['endhead']);

    }

    public function render_footer()
    {

        foreach ($this->config_template['footer'] as $key => $sources) {
//            if (!file_exists($sources)) continue;
            $action_key ='';
            if(strpos($key,'/') != false){
                $actoin = explode('/',$key);
                $controll = $actoin[0];
                $actoin = $actoin[1];
                $action_key = $actoin;
            }else{
                $actoin = strtolower($this->action);
                $controll = strtolower($key);
            }
            if ($controll[0] == '_'){
                if(ucfirst($this->removeStr('_' , $controll)) == $this->controller && $this->action == $actoin){
                    if(is_array($sources))
                    {
                        foreach ($sources as $source)
                        {
                            echo "\n            <script src='$source'></script>";
                        }
                    }else{
                        echo "\n            <script src='$sources'></script>";
                    }
                }
            }elseif ($controll[0] == '~'){
                if(ucfirst($this->removeStr('~' , $controll)) != $this->controller || $this->action != $action_key){
                    if(is_array($sources))
                    {
                        foreach ($sources as $source)
                        {
                            echo "\n            <script src='$source'></script>";
                        }
                    }else{
                        echo "\n            <script src='$sources'></script>";
                    }
                }
            }elseif($controll[0] == '-'){
                if(substr($controll , 1,2 )  == $this->Language && $this->action == $actoin) {
                    if(is_array($sources))
                    {
                        foreach ($sources as $source)
                        {
                            echo "\n            <script src='$source'></script>";
                        }
                    }else{
                        echo "\n            <link rel='stylesheet' href='$sources' >";
                    }                }
            }elseif($controll[0]){
                if(is_array($sources))
                {
                    foreach ($sources as $source)
                    {
                        echo "\n            <script src='$source'></script>";
                    }
                }else{
                    echo "\n            <script src='$sources'></script>";
                }              }
        }

        $this->render_tpl($this->config_template['structure']['endfooter']);

    }

    public function render_content()
    {
        $temlate_load = explode('|',$this->template_load);
        foreach ($temlate_load as $template) {
            if($template == ':VIEW'){
                $this->render_tpl();
            }else{
                $this->render_tpl($this->config_template['structure'][$template]);
            }
        }
    }

    public function render_session()
    {
        if (!DB::connect()) return false;
        $Users = new \Store\Models\UsersModel();
        $Profile = new ProfileModel();
        if(isset($this->Session->User))
        {
            if(isset($this->Session->User->UserId)){
                $this->Session->User  = $Users->inner_join('UserId',$this->Session->User->UserId);
                if(ProfileModel::table('app_users_profile')->exist('UserId',$this->Session->User->UserId))
                {
                    $this->Session->Profile  = $Profile->getByKey($this->Session->User->UserId);
                }elseif(isset($this->Session->Profile)){
                    unset($this->Session->Profile);
                }
            }
        }
    }

    public function render()
    {
        $this->render_session();
        $this->start_notifications();
        $this->render_header();
        $this->render_content();
        $this->render_footer();
    }


    public function template_load($template_load)
    {
        $this->template_load = $template_load;
    }

    public function imageUser()
    {
        echo isset($this->Session->Profile->Image) ? DS . $this->Session->Profile->Image : '/uploads/default.png';
    }


}