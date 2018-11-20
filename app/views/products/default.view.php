<div class="actions">
    <a class="b-primary-upt bn" href="/products/Create/">{ text_new_product }</a>
</div>

<table class="display dataTableEnable">
    <thead>
    <tr>
        <th width="100px">{ text_product_id }</th>
        <th>{ text_title }</th>
        <th>{ text_category_name }</th>
        <th>{ text_made_country }</th>
        <th>{ text_quantity }</th>
        <th>{ text_barcode }</th>
        @tax_allow
        <th>{ text_tax }</th>
        @end
        <th>{ text_buy_price }</th>
        <th>{ text_sell_price }</th>
        <th>{ text_added_date }</th>
        <th>{ text_control }</th>
    </tr>
    </thead>
    <tbody>

    @foreach (#Products as $Product)
    <tr>
        <td>{! $Product->ProductId !}</td>
        <td data-cut-title="15">{! $Product->Title !}</td>
        <td>{! $Product->Name !}</td>
        <td>{ countries[$Product->MadeCountry] }</td>
        <td data-bottom-title="{! self::format_quantity($Product->Quantity) _ $Product->UnitName !}" ><bdi>{! self::format_quantity($Product->Quantity) _ $Product->UnitCode !}</bdi></td>
        <td>{! $Product->Barcode !}</td>
        @tax_allow
        <td>@number_parse ($Product->Tax)</td>
        @end
        <td>@Currency ($Product->BuyPrice)</td>
        <td>@Currency ($Product->SellPrice)</td>
        <td data-bottom-title="{ on_time } @time_format ($Product->AddedDate)">@date_format ($Product->AddedDate)</td>
        <td>
            <a href="/products/Preview/?id={! $Product->ProductId !}" data-top-title="{ title_preview }"><i class="far fa-eye"></i></a>
            <a href="/products/Edit/?id={! $Product->ProductId !}" data-top-title="{ title_edit }"><i class="far fa-edit"></i></a>
            <a href="/products/Delete/?id={! $Product->ProductId !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this Product')"><i class="far fa-trash-alt"></i></a>
        </td>

    </tr>
    @endforeach
    </tbody>
</table>


