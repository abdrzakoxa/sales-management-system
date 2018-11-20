<form class="f-row form-style products-create" method="post" autocomplete="off">
    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_title }</label>
        <input type="text" name="title" value="@post (title)" min="3" max="240"  data-pattern="^[\w\(\):?!\-\,\.\/\' 0-9\u0600-\u06FF]{3,240}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label>{ label_quantity_in }</label>
        <select name="unit_id">
            <option value="0" disabled selected>{ label_quantity_in }</option>
            @foreach (#Units as $key => $unit)
            <option value="{! $unit->UnitId !}" @if ($this->post('unit_id') == $unit->Code)
                selected @endif > {! $unit->Name !}</option>
            @endforeach
        </select>
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_quantity }</label>
        <input type="text" name="quantity" value="@post (quantity)" max="10"  data-pattern="^[0-9]{1,16}(\.[0-9]{1,6})?$" >
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_notification_quantity }</label>
        <input type="text" name="notification_quantity" value="@post (notification_quantity)" data-pattern="^[0-9]{1,16}(\.[0-9]{1,6})?$" >
    </div>

    @tax_allow
    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_tax }</label>
        <input type="text" name="tax" value="@post (tax)" max="10"  data-pattern="^[0-9]{1,18}(\.[0-9]{1,8})?$|^[0-9]{1,3}(\.[0-9]{1,2})?%$" >
    </div>
    @end

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_buy_price }</label>
        <input type="text" name="buy_price" value="@post (buy_price)" max="10"  data-pattern="^[0-9]+(\.[0-9]{1,8})?$" >
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_sell_price }</label>
        <input type="text" name="sell_price" value="@post (sell_price)" max="10"  data-pattern="^[0-9]+(\.[0-9]{1,8})?$" >

    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label>{ label_made_country }</label>
        <select id="madeContry" name="madeCountry">
            <option value="0" disabled selected>{ label_made_country }</option>
            @foreach (#countries as $key => $country)
                <option value="{! $key !}" @if ($this->getPost('madeCountry') == $key)
                    selected @endif > {! $country !}</option>
            @endforeach
        </select>
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label>{ label_category_product }</label>
        <select name="categoryId">
            <option value="0" disabled selected>{ label_category_product }</option>
            @foreach (#Categories as $Category)
                <option value="{! $Category->ProductCategoryId; !}" @if ($this->getPost('categoryId') == $Category->ProductCategoryId)
                    selected @endif > {! $Category->Name !}</option>
            @endforeach
        </select>
    </div>


    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_barcode }</label>
        <input type="text" name="barcode" value="@post (barcode)" id="barcode"  data-pattern="^[A-Za-z0-9]{1,44}$" >
    </div>


    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }" >
    </div>


</form>
