<?php

namespace Store\controllers;


use Store\Core\DB;
use Store\Core\Messenger;
use Store\core\Settings;
use Store\Core\Validate;
use Store\Models\BaseUnitsModel;
use Store\Models\UnitsModel;

class SettingsController extends AbsController
{

    public function SystemAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('template.languages');
        $this->Language->load('settings.system');
        $this->Language->load('settings.label');
        $this->Language->load('settings.validation');
        $Settings = new \stdClass();

        $this->Data['SettingsCompany']              = Settings::get('setting-company');
        $this->Data['SettingsSite']                 = Settings::get('setting-site');
        $this->Data['SettingsProducts']             = Settings::get('setting-products');
        $this->Data['SettingsNumbersFormatting']    = Settings::get('settings-numbers-formatting');
        $this->Data['Currency']                     = json_decode(JsonController::CurrencyAction('all',true));


        if(self::has_post('submit_settings_company'))
        {
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'company_name'  => 'required|max:50|min:3|type:words',
                'email'         => 'required|max:50|min:6|type:email',
                'phone'         => 'required|max:18|min:6|type:phone',
                'address'       => 'required|max:120|min:6|type:address'
            ];
            if ($valid->check()) {
                $Settings->Name = self::post('company_name');
                $Settings->Email = self::post('email');
                $Settings->Phone = self::post('phone');
                $Settings->Address = self::post('address');
                if(Settings::save('setting-company',$Settings))
                {
                    Messenger::getInstance()->create($this->Language->get('success_settings_company_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }
            }
        }

        if(self::has_post('submit_settings_site'))
        {
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $C_code = self::has_post('currency') ? self::post('currency') : '';
            $currency = JsonController::CurrencyAction(['C_code'=>$C_code],true,false);
            $is_c = !empty($currency) ? true : false;
            $valid->rules = [
                'language'               => 'required|key_exists:languages',
                'currency'               => 'required|is_true:'.$is_c,
                'table_rows'             => 'required|list:10,25,50,100,-1',
                'login_captcha'          => 'required|list:1,2',
                'display_currency_symbol'=> 'required|list:1,2,3'
            ];
            if ($valid->check() && $is_c)
            {
                $currency = json_decode($currency);
                $Settings->Language = self::post('language');
                $Settings->Currency = $currency;
                $Settings->TableRows = self::post('table_rows');
                $Settings->LoginCaptcha = self::post('login_captcha');
                $Settings->DisplayCurrencySymbol = self::post('display_currency_symbol');
                $is_currency = isset(Settings::get('setting-site')->Currency) && Settings::get('setting-site')->Currency != $currency;
                if (Settings::save('setting-site',$Settings)){
                    if($is_currency) $this->Currency->update_currency(false);
                    Messenger::getInstance()->create($this->Language->get('success_settings_site_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request('submit_settings_site');
                }
            }
        }

        if(self::has_post('submit_products'))
        {
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'taxes_products'                => 'required|list:1,2',
                'discount_products'             => 'required|list:1,2',
            ];
            if ($valid->check())
            {
                $Settings->TaxesProducts = self::post('taxes_products');
                $Settings->DiscountProducts = self::post('discount_products');
                if (Settings::save('setting-products',$Settings)){
                    if($is_currency) $this->Currency->update_currency(false);
                    Messenger::getInstance()->create($this->Language->get('success_settings_site_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request('submit_products');
                }
            }
        }



        if(self::has_post('submit_settings_numbers_formatting'))
        {
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'date_format'       => 'required|key_exists:date_format',
                'time_format'       => 'required|key_exists:time_format',
                'decimals'          => 'required|list:1,2,3,4,5',
                'quantity_decimals' => 'required|list:1,2,3,4,5,6,7',
                'currency_decimals' => 'required|list:1,2,3,4,5',
                'decimals_separator'=> 'required|list:1,2,3',
                'thousands_separator'=> 'required|list:1,2,3,4',
            ];
            if ($valid->check())
            {
                $Settings->DateFormat           = self::post('date_format');
                $Settings->TimeFormat           = self::post('time_format');
                $Settings->Decimals             = self::post('decimals');
                $Settings->QuantityDecimals     = self::post('quantity_decimals');
                $Settings->CurrencyDecimals     = self::post('currency_decimals');
                $Settings->DecimalsSeparator    = self::post('decimals_separator');
                $Settings->ThousandsSeparator       = self::post('thousands_separator');
                if (Settings::save('settings-numbers-formatting',$Settings)){
                    Messenger::getInstance()->create($this->Language->get('success_settings_numbers_formatting_updated'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request('submit_settings_numbers_formatting');
                }
            }
        }

        $this->View();

    }

    public function UnitsAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('settings.label');
        $this->Language->load('settings.units');
        $this->Language->load('settings.main');
        $UnitsBase = new baseunitsModel();
        $Units = new UnitsModel();
        $this->Data['UnitsBase'] = $UnitsBase->getAll();
        $this->Data['Units'] = $Units->inner_join();
        $this->View();
    }

    public function BackupDatabaseAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.messengers');
        $this->Language->load('Settings.BackupDatabase');

        $action = self::has_get('action') && self::get('action') != '' ? self::get('action') : false;

        if($action == 'NewBackup')
        {
            $database = self::Config('mysql.dbname');
            $rand = md5(time());
            $path = BACKUP_PATH . DS ."backup_to_database_($database)_$rand.sql";
            $backup = DB::Backup();
            $file = fopen("$path", "w") or die("Unable to open file!");
            fwrite($file, $backup);
            Messenger::getInstance()->create($this->Language->get('success_create_backup_database'),Messenger::APP_TYPE_SUCCESS);
            self::redirect('/Settings/BackupDatabase/');
        }

        if($action == 'Delete' && self::has_get('name'))
        {
            $f = BACKUP_PATH . DS . self::get('name');
            if(file_exists($f)){
                unlink($f);
            }

            Messenger::getInstance()->create($this->Language->get('success_delete_backup_database'),Messenger::APP_TYPE_SUCCESS);
            self::redirect('/Settings/BackupDatabase/');
        }

        $dirs = array_diff(scandir(BACKUP_PATH), array('..', '.'));
        $result = [];

        foreach ($dirs as $dir) {
            $b = new \stdClass();
            $t = filemtime (BACKUP_PATH . DS . $dir);
            $b->CreatedDate =  date('Y-m-d h:i:s',$t);
            $b->time =  filemtime (BACKUP_PATH . DS . $dir);
            $b->fileName =  $dir;
            $result[] = $b;
            unset($b);
        }

        usort($result, function ($a, $b)
        {
            return $a->time < $b->time;
        });

        $this->Data['Backup'] = $result;
        $this->View();
    }

    public function CreateBaseUnitAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('settings.labelunits');
        $this->Language->load('settings.units');
        $UnitsBase = new BaseUnitsModel();
        $Units = new UnitsModel();
        $this->Data['UnitsBase'] = $UnitsBase->getAll();


        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'name' => 'required|max:30|min:3|type:words',
                'code' => 'required|max:6|type:word_english|unique:app_base_units'
            ];
            if($valid->check()){
                $UnitsBase->Name = $this->getPost('name');
                $UnitsBase->Code = $this->getPost('code');
                $Units->Name = $this->getPost('name');
                $Units->Code = $this->getPost('code');
                $Units->OperationValue = 1;
                $Units->Operator = '*';
                if($UnitsBase->create()){
                    $Units->BaseUnit = DB::connect()->lastInsertId();
                    if ($Units->Create())
                    {
                        Messenger::getInstance()->create($this->Language->get('success_unit_added'),Messenger::APP_TYPE_SUCCESS);
                        $this->clear_request();
                    }
                }

            }
        }


        $this->View();
    }

    public function EditBaseUnitAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('settings.labelunits');
        $this->Language->load('settings.main');
        $this->Language->load('settings.units');
        $BaseUnits = new BaseUnitsModel();
        $Units = new UnitsModel();
        $id = self::get('id');

        if(self::is_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_base_units','BaseUnitId')){
                $this->Data['BaseUnit'] = $BaseUnits->getByKey($id);
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_unit_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Settings/Units/');
            }
        }else{
            self::redirect('/Settings/Units/');
        }

        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->primary['BaseUnitId'] = self::get('id');
            $valid->rules = [
                'name'           => 'required|max:30|min:3|type:words',
                'code'           => 'required|max:6|type:word_english|same_unq:app_base_units'
            ];
            if($valid->check()){
                $BaseUnits->BaseUnitId = $id;
                $BaseUnits->Name = $this->getPost('name');
                $BaseUnits->Code = $this->getPost('code');
                $Code = isset($BaseUnits->getByKey($id)->Code) ? $BaseUnits->getByKey($id)->Code : null ;
                $un = isset($Units->getByCols(['BaseUnit'=>$id,'Code'=>$Code])[0]->UnitId) ? $Units->getByCols(['BaseUnit'=>$id,'Code'=>$Code])[0]->UnitId : null;
                $Units->UnitId = $un;
                $Units->Code = $this->getPost('code');
                $Units->Name = $this->getPost('name');
                $Units->BaseUnit = $id;
                $Units->Operator = '*';
                $Units->OperationValue = 1;
                if($BaseUnits->update() && $Units->update()){
                    Messenger::getInstance()->create($this->Language->get('success_unit_added'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }

            }
        }


        $this->View();
    }

    public function DeleteBaseUnitAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::get('id');
        if(self::is_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_base_units','BaseUnitId')){
            $BaseUnit = new BaseUnitsModel();
            $BaseUnit->BaseUnitId = $id;
            if($BaseUnit->delete())
            {
                Messenger::getInstance()->create($this->Language->get('success_unit_delete'));
                self::redirect('/Settings/Units/');
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_unit_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Settings/Units/');
        }
    }

    public function BackupViewAction()
    {
        $this->Language->load('template.Messengers');
        if(self::has_get('name') && self::get('name') != '')
        {
            $file = BACKUP_PATH . DS . self::get('name');
            if(file_exists($file)){
                echo '<pre>';
                echo file_get_contents($file);
                echo '</pre>';
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_file_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Settings/BackupDatabase/');
            }
        }else{
            self::redirect('/Settings/BackupDatabase/');
        }
    }

    public function BackupDownloadAction()
    {
        $this->Language->load('template.Messengers');
        if (self::has_get('name') && self::get('name') != '') {
            $fileName = basename(self::get('name'));
            $filePath = BACKUP_PATH . DS . $fileName;
            if (!empty($fileName) && file_exists($filePath)) {
                // Define headers
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$fileName");
                header("Content-Type: application/zip");
                header("Content-Transfer-Encoding: binary");

                // Read the file
                readfile($filePath);
                exit;
            }else {
                Messenger::getInstance()->create($this->Language->get('warning_file_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Settings/BackupDatabase/');
            }
        }else{
            self::redirect('/Settings/BackupDatabase/');
        }
    }

    public function DeleteUnitAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $id = self::get('id');
        if(self::is_get('id') && Validate::valid($id,Validate::REGEX_INT) && Validate::valid_unique($id,'app_units','UnitId')){
            $UnitModel = new UnitsModel();
            $UnitModel->UnitId = $id;
            if($UnitModel->delete())
            {
                Messenger::getInstance()->create($this->Language->get('success_unit_delete'));
                self::redirect('/Settings/Units/');
            }
        }else{
            Messenger::getInstance()->create($this->Language->get('warning_unit_not_exist'),Messenger::APP_TYPE_WARNING);
            self::redirect('/Settings/Units/');
        }
    }

    public function CreateUnitAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('settings.labelunits');
        $this->Language->load('settings.main');
        $this->Language->load('settings.units');
        $Units = new UnitsModel();
        $baseunits = new BaseUnitsModel();
        $this->Data['UnitsBase'] = $baseunits->getAll();


        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->rules = [
                'name'          => 'required|max:30|min:3|type:words',
                'code'          => 'required|max:6|type:word_english|unique:app_units',
                'base_unit'      => 'required|foreign:app_base_units.BaseUnitId',
                'operator'      => 'required|key_exists:main_operator',
                'operation_value'=> 'required|max:4294967295'
            ];
            if($valid->check()){
                $Units->Name = $this->getPost('name');
                $Units->Code = $this->getPost('code');
                $Units->BaseUnit = $this->getPost('base_unit');
                $Units->Operator = $this->getPost('operator');
                $Units->OperationValue = $this->getPost('operation_value');
                if($Units->create()){

                    Messenger::getInstance()->create($this->Language->get('success_unit_added'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }

            }
        }


        $this->View();
    }

    public function EditUnitAction()
    {
        $this->Language->load('template.main');
        $this->Language->load('template.Messengers');
        $this->Language->load('settings.labelunits');
        $this->Language->load('settings.main');
        $this->Language->load('settings.units');
        $units = new UnitsModel();
        $BaseUnits = new BaseUnitsModel();
        $id = self::get('id');

        if(self::is_get('id') && Validate::valid($id,Validate::REGEX_INT)){
            if(Validate::valid_unique($id,'app_units','UnitId')){
                $this->Data['Unit'] = $units->getByKey($id);
                $this->Data['BaseUnits'] = $BaseUnits->getAll();
            }else{
                Messenger::getInstance()->create($this->Language->get('warning_user_not_exist'),Messenger::APP_TYPE_WARNING);
                self::redirect('/Settings/Units/');
            }
        }else{
            self::redirect('/Settings/Units/');
        }

        if($this->has_post('submit')){
            $valid = new Validate($this->Language);
            $valid->data = $_POST;
            $valid->primary['UnitId'] = self::get('id');
            $valid->rules = [
                'name'           => 'required|max:30|min:3|type:words',
                'code'           => 'required|max:6|type:word_english|same_unq:app_units',
                'operator'       => 'required|key_exists:main_operator',
                'base_unit'      => 'required|foreign:app_base_units.BaseUnitId',
                'operation_value'=> 'required|max:4294967295'
            ];
            if($valid->check()){
                $units->UnitId = $id;
                $units->Name = $this->getPost('name');
                $units->Code = $this->getPost('code');
                $units->BaseUnit = $this->getPost('base_unit');
                $units->Operator = $this->getPost('operator');
                $units->OperationValue = $this->getPost('operation_value');
                if($units->update()){

                    Messenger::getInstance()->create($this->Language->get('success_unit_added'),Messenger::APP_TYPE_SUCCESS);
                    $this->clear_request();
                }

            }
        }


        $this->View();
    }

    public function themesAction()
    {

        $themes = ['light','dark','purple','blue'];
        $theme = self::get('theme');

        if (self::has_get('theme') && in_array($theme,$themes)){
            setcookie('theme',$theme,time() + 86400 * 30,"/");
        }

        self::redirect('back');
    }

    public function testAction()
    {
        $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        echo $f->format(1432);
    }


}