<div id="tab-control" class="tab-control" data-cookies="system-settings" data-default-cos="setting-company">
    <a class="bn b-primary-icon" href="~setting-company" data-bottom-title="{ text_settings_company }" ><i class="far fa-building"></i></a>
    <a class="bn b-primary-icon" href="~setting-site" data-bottom-title="{ text_settings_site }" ><i class="fas fa-cog"></i></a>
    <a class="bn b-primary-icon" href="~setting-numbers-formatting" data-bottom-title="{ text_numbers_formatting }" ><i class="fas fa-sort-numeric-up"></i></a>
    <a class="bn b-primary-icon" href="~setting-products" data-bottom-title="{ text_settings_products }" ><i class="fas fa-shopping-cart"></i></a>
</div>
<div class="tab-content">
    <form id="setting-company" class="f-row form-style tab-parent" method="post" autocomplete="off">
        <span class="form-title bn">{ text_settings_company }</span>
        <div class="input-group-s col-md-down-1 col-md-up-2">
            <label>{ label_company_name }</label>
            <input type="text" name="company_name" value="@autoValue (company_name, SettingsCompany->Name)" min="3" max="15" data-pattern="^[A-z \u0600-\u06FF]{3,50}$">
        </div>

        <div class="input-group-s col-md-down-1 col-md-up-2">
            <label>{ label_email }</label>
            <input type="text" name="email" value="@autoValue (email, SettingsCompany->Email)" min="6" max="50" data-pattern="^(?=^.{6,50}$)(([A-z0-9-_.]+)@([A-z0-9.-_]+)\.([A-z]{2,}))$">
        </div>

        <div class="input-group-s col-md-down-1 col-md-up-2">
            <label>{ label_phone }</label>
            <input type="text" name="phone" value="@autoValue (phone, SettingsCompany->Phone)" min="10" max="15" data-pattern="^(?=^.{10,15}$)[+(]{0,2}\d{3}[). -]{0,2}[- .]?[0-9]{3}[-. ]?[\d]+$">
        </div>

        <div class="input-group-s col-md-down-1 col-md-up-2 bln">
            <label>{ label_address }</label>
            <input type="text" name="address" value="@autoValue (address, SettingsCompany->Address)" min="6" max="60" data-pattern="^[A-z -\/,0-9\u0600-\u06FF]{6,120}$">
        </div>

        <div class="input-submit-p">
            <input type="submit" class="bn b-primary-submit" name="submit_settings_company" value="{ text_save }">
        </div>

    </form>

    <!-- Settings Site -->

    <form id="setting-site" class="f-row form-style tab-parent" method="post" autocomplete="off">
        <span class="form-title bn">{ text_settings_site }</span>
        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label class="is_focus">{ label_language }</label>
            <select name="language">
                <option value="0" disabled selected>{ label_language }</option>
                @foreach (#languages as $code => $language)
                <option value="{! $code !}" @auth_obj_post (#SettingsSite->Language,language,$code) selected @end >{! $language !}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label class="is_focus">{ label_currency }</label>
            <select name="currency">
                <option value="0" disabled selected>{ label_currency }</option>
                @foreach (#Currency as $code => $currency)
                <option   value="{! $code !}" @auth_obj_post (#SettingsSite->Currency->code,currency,$code) selected @end >{! $currency->code !}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label>{ label_table_rows }</label>
            <select name="table_rows">
                <option value="0" disabled selected>{ label_table_rows }</option>
                <option value="10"  @auth_obj_post (#SettingsSite->TableRows,table_rows,10)   selected @end >10</option>
                <option value="25"  @auth_obj_post (#SettingsSite->TableRows,table_rows,25)   selected @end >25</option>
                <option value="50"  @auth_obj_post (#SettingsSite->TableRows,table_rows,50)   selected @end >50</option>
                <option value="100" @auth_obj_post (#SettingsSite->TableRows,table_rows,100) selected @end >100</option>
                <option value="-1"  @auth_obj_post (#SettingsSite->TableRows,table_rows,-1)   selected @end >{ text_all }</option>
            </select>
        </div>

        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label class="is_focus">{ label_captcha_login }</label>
            <select name="login_captcha">
                <option value="0" disabled selected>{ label_captcha_login }</option>
                <option value="1" @auth_obj_post (#SettingsSite->LoginCaptcha,login_captcha,1) selected @end >{ option_enable }</option>
                <option value="2" @auth_obj_post (#SettingsSite->LoginCaptcha,login_captcha,2) selected @end >{ option_disable }</option>
            </select>
        </div>

        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label class="is_focus">{ label_display_currency_symbol }</label>
            <select name="display_currency_symbol">
                <option value="0" disabled selected>{ label_display_currency_symbol }</option>
                <option value="1" @auth_obj_post (#SettingsSite->DisplayCurrencySymbol,display_currency_symbol,1) selected @end >{ option_before }</option>
                <option value="2" @auth_obj_post (#SettingsSite->DisplayCurrencySymbol,display_currency_symbol,2) selected @end >{ option_disable }</option>
                <option value="3" @auth_obj_post (#SettingsSite->DisplayCurrencySymbol,display_currency_symbol,3) selected @end >{ option_after }</option>
            </select>
        </div>

        <div class="input-submit-p">
            <input type="submit" class="bn b-primary-submit" name="submit_settings_site" value="{ text_save }">
        </div>
    </form>

    <!-- Settings products -->

    <form id="setting-products" class="f-row form-style tab-parent" method="post" autocomplete="off">
        <span class="form-title bn">{ text_settings_products }</span>

        <div class="input-group-s col-md-down-1 col-md-up-2">
            <label class="is_focus">{ label_taxes_products }</label>
            <select name="taxes_products">
                <option value="0" disabled selected>{ label_taxes_products }</option>
                <option value="1" @auth_obj_post (#SettingsProducts->TaxesProducts,taxes_products,1) selected @end >{ option_enable }</option>
                <option value="2" @auth_obj_post (#SettingsProducts->TaxesProducts,taxes_products,2) selected @end >{ option_disable }</option>
            </select>
        </div>

        <div class="input-group-s col-md-down-1 col-md-up-2">
            <label class="is_focus">{ label_discount_products }</label>
            <select name="discount_products">
                <option value="0" disabled selected>{ label_discount_products }</option>
                <option value="1" @auth_obj_post (#SettingsProducts->DiscountProducts,discount_products,1) selected @end >{ option_enable }</option>
                <option value="2" @auth_obj_post (#SettingsProducts->DiscountProducts,discount_products,2) selected @end >{ option_disable }</option>
            </select>
        </div>


        <div class="input-submit-p">
            <input type="submit" class="bn b-primary-submit" name="submit_products" value="{ text_save }">
        </div>

    </form>


    <!-- Settings Number formatting-->

    <form id="setting-numbers-formatting" class="f-row form-style tab-parent" method="post" autocomplete="off">
        <span class="form-title bn">{ text_numbers_formatting }</span>

        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label>{ label_date_format }</label>
            <select name="date_format">
                <option value="0" disabled selected>{ label_date_format }</option>
                @foreach (#date_format as $key => $value)
                <option value="{! $key !}" @auth_obj_post (#SettingsNumbersFormatting->DateFormat,date_format,$key) selected @end >{! $value !}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label>{ label_time_format }</label>
            <select name="time_format">
                <option value="0" disabled selected>{ label_time_format }</option>
                @foreach (#time_format as $key => $value)
                <option value="{! $key !}" @auth_obj_post (#SettingsNumbersFormatting->TimeFormat,time_format,$key) selected @end >{! $value !}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label>{ label_decimals }</label>
            <select name="decimals">
                <option value="0" disabled selected>{ label_decimals }</option>
                <option value="1" @auth_obj_post (#SettingsNumbersFormatting->Decimals,decimals,1)   selected @end >1</option>
                <option value="2" @auth_obj_post (#SettingsNumbersFormatting->Decimals,decimals,2)   selected @end >2</option>
                <option value="3" @auth_obj_post (#SettingsNumbersFormatting->Decimals,decimals,3)   selected @end >3</option>
                <option value="4" @auth_obj_post (#SettingsNumbersFormatting->Decimals,decimals,4)   selected @end >4</option>
                <option value="5" @auth_obj_post (#SettingsNumbersFormatting->Decimals,decimals,5)   selected @end >{ option_disable }</option>
            </select>
        </div>

        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label>{ label_quantity_decimals }</label>
            <select name="quantity_decimals">
                <option value="0" disabled selected>{ label_quantity_decimals }</option>
                <option value="1"  @auth_obj_post (#SettingsNumbersFormatting->QuantityDecimals,quantity_decimals,1) selected @end >1</option>
                <option value="2"  @auth_obj_post (#SettingsNumbersFormatting->QuantityDecimals,quantity_decimals,2) selected @end >2</option>
                <option value="3"  @auth_obj_post (#SettingsNumbersFormatting->QuantityDecimals,quantity_decimals,3) selected @end >3</option>
                <option value="4"  @auth_obj_post (#SettingsNumbersFormatting->QuantityDecimals,quantity_decimals,4) selected @end >4</option>
                <option value="5"  @auth_obj_post (#SettingsNumbersFormatting->QuantityDecimals,quantity_decimals,5) selected @end >5</option>
                <option value="6"  @auth_obj_post (#SettingsNumbersFormatting->QuantityDecimals,quantity_decimals,6) selected @end >6</option>
                <option value="7"  @auth_obj_post (#SettingsNumbersFormatting->QuantityDecimals,quantity_decimals,7) selected @end >{ option_auto }</option>
                <option value="7"  @auth_obj_post (#SettingsNumbersFormatting->QuantityDecimals,quantity_decimals,8) selected @end >{ option_disable }</option>
            </select>
        </div>

        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label>{ label_currency_decimals }</label>
            <select name="currency_decimals">
                <option value="0" disabled selected>{ label_currency_decimals }</option>
                <option value="1"  @auth_obj_post (#SettingsNumbersFormatting->CurrencyDecimals,currency_decimals,1) selected @end >1</option>
                <option value="2"  @auth_obj_post (#SettingsNumbersFormatting->CurrencyDecimals,currency_decimals,2) selected @end >2</option>
                <option value="3"  @auth_obj_post (#SettingsNumbersFormatting->CurrencyDecimals,currency_decimals,3) selected @end >3</option>
                <option value="4"  @auth_obj_post (#SettingsNumbersFormatting->CurrencyDecimals,currency_decimals,4) selected @end >{ option_disable }</option>
                <option value="5"  @auth_obj_post (#SettingsNumbersFormatting->CurrencyDecimals,currency_decimals,5) selected @end >{ option_default }</option>
            </select>
        </div>

        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label>{ label_decimals_separator }</label>
            <select name="decimals_separator">
                <option value="0" disabled selected>{ label_decimals_separator }</option>
                <option value="1"  @auth_obj_post (#SettingsNumbersFormatting->DecimalsSeparator,decimals_separator,1) selected @end >{ text_comma }</option>
                <option value="2"  @auth_obj_post (#SettingsNumbersFormatting->DecimalsSeparator,decimals_separator,2) selected @end >{ text_dot }</option>
                <option value="3"  @auth_obj_post (#SettingsNumbersFormatting->DecimalsSeparator,decimals_separator,5) selected @end >{ option_disable }</option>
            </select>
        </div>

        <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
            <label>{ label_thousands_separator }</label>
            <select name="thousands_separator">
                <option value="0" disabled selected>{ label_thousands_separator }</option>
                <option value="1"  @auth_obj_post (#SettingsNumbersFormatting->DecimalsSeparator,decimals_separator,1) selected @end >{ text_comma }</option>
                <option value="2"  @auth_obj_post (#SettingsNumbersFormatting->DecimalsSeparator,decimals_separator,2) selected @end >{ text_dot }</option>
                <option value="3"  @auth_obj_post (#SettingsNumbersFormatting->DecimalsSeparator,decimals_separator,2) selected @end >{ text_space }</option>
                <option value="4"  @auth_obj_post (#SettingsNumbersFormatting->DecimalsSeparator,decimals_separator,5) selected @end >{ option_disable }</option>
            </select>
        </div>

        <div class="input-submit-p">
            <input type="submit" class="bn b-primary-submit" name="submit_settings_numbers_formatting" value="{ text_save }">
        </div>
    </form>

</div>


