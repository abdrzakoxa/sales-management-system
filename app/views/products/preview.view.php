
<div class="preview-products">
    <div class="actions">
        <button class="b-primary-upt bn" id="print_products">{ text_print }</button>
        <a class="b-primary-upt bn" href="/products/Edit/?id={ products->ProductId }" >{ title_edit }</a>
        <a class="b-primary-upt bn" href="/products/Delete/?id={ products->ProductId }" onclick="return confirm('do you want delete this Product');" >{ title_delete }</a>
    </div>
    <div id="preview-products" >
        <div class="box-content f-row" id="box-content">
            <div class="bar-code col-1">
                <img id="barcode_preview" data-barcode="{ products->Barcode }">
            </div>

            <div class="col-2 property_products"><b>{ label_title }</b> : { products->Title }</div>
            <div class="col-2 property_products"><b>{ label_quantity_in }</b> : { products->UnitName }</div>
            <div class="col-2 property_products"><b>{ label_quantity }</b> : <bdi>@format_num (#products->Quantity) { products->UnitCode }</bdi></div>
            @if (#products->NotificationQuantity != '')
            <div class="col-2 property_products"><b>{ label_notification_quantity }</b> : <bdi>@format_num (#products->NotificationQuantity) { products->UnitCode }</bdi></div>
            @endif
            <div class="col-2 property_products"><b>{ label_made_country }</b> : {countries[#products->MadeCountry]}</div>
            <div class="col-2 property_products"><b>{ label_category_product }</b> : { products->Name }</div>
            <div class="col-2 property_products"><b>{ label_buy_price }</b> : @Currency (#products->BuyPrice)</div>
            <div class="col-2 property_products"><b>{ label_sell_price }</b> : @Currency (#products->SellPrice)</div>
            @if (#products->Tax != '')
            <div class="col-2 property_products"><b>{ label_tax }</b> : @number_parse (#products->Tax)</div>
            @endif
            <div class="col-2 property_products"><b>{ label_barcode }</b> : { products->Barcode }</div>
            <div class="col-2 property_products"><b>{ label_added_date }</b> : @full_date_format (#products->AddedDate)</div>

        </div>

    </div>
</div>

