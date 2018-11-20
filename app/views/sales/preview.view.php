
<div class="invoice-products">
    <div class="actions">
        <button class="b-primary-upt bn" id="print" >{ text_print }</button>
        <a class="b-primary-upt bn" href="/Sales/Edit/?id={ invoice->InvoiceId }" >{ title_edit }</a>
        <a class="b-primary-upt bn" href="/Sales/Delete/?id={ invoice->InvoiceId }" onclick="return confirm('do you want delete this Invoice');" >{ title_delete }</a>
    </div>
    <div id="invoice-products" class="print_box f-row">
        <h1 class="company-name-head col-1">@CompanyConfig (Name)</h1>
        <div class="company-info col-2">
            <span><b>{ text_company_address }</b> : @CompanyConfig (Address)</span>
            <span><b>{ text_company_phone }</b> : @CompanyConfig (Phone)</span>
            <span><b>{ text_company_email }</b> : @CompanyConfig (Email)</span>
        </div>

        <div class="invoice-info col-2">
            <span><b>{ text_date }</b> : @now () </span>
            <span><b>{ text_invoice_id }</b> : <bdi># { invoice->InvoiceId }</bdi></span>
            <span><b>{ text_payment_type }</b> : { array_payment_type[#invoice->PaymentType] } </span>
            <span><b>{ text_payment_status }</b> : { array_payment_status[#invoice->PaymentStatus] } </span>
        </div>

        <div class="client-info col-1">
            <h2>{ client->FirstName } { client->LastName }</h2>
            <span><b>{ text_client_id }</b> : <bdi>#{ client->ClientId }</bdi></span>
            <span><b>{ text_company_phone }</b> : { client->Phone }</span>
            <span><b>{ text_company_email }</b> : { client->Email }</span>
            <span><b>{ text_company_address }</b> : { client->Address }</span>
        </div>

        <table class="table-custom col-1">
            <thead>
                <tr>
                    <th>{ text_quantity }</th>
                    <th>{ text_description }</th>
                    <th>{ text_tax }</th>
                    <th>{ text_price }</th>
                    <th>{ text_total }</th>
                </tr>
            </thead>
            <tbody>
            @foreach (#products as $product)
                <tr>
                    <td>@number_zero ($product->QuantitySales) {! self::getUnitName($product->ProductId)->UnitName !}</td>
                    <td>{! $product->Title !}</td>
                    <td>@tax ($product->ProductId,#invoice->InvoiceId,$product->Tax)</td>
                    <td>@Currency ($product->SellPrice)</td>
                    <td>@Currency ($product->TotalPriceProduct)</td>
                </tr>
            @endforeach

            </tbody>
        </table>

        <table class="price_totals col-2">
            <tbody>
                @discount_tax_allow
                <tr>
                    <th>{ text_sub_total }</th>
                    <td>@notempty (#products[0]->Total) @Currency (#products[0]->Total) @else @Currency (0)  @endif </td>
                </tr>
                @end
                @discount_allow
                <tr>
                    <th>{ text_discount }</th>
                    <td>@number_parse (#invoice->Discount)</td>
                </tr>
                @end
                @tax_allow
                <tr>
                    <th>{ text_tax }</th>
                    <td>@tax_invoice (#invoice->InvoiceId)</td>
                </tr>
                @end
                <tr>
                    <! $tax = self::tax_invoice(#invoice->InvoiceId,false) !>

                    <th>{ text_invoice_total }</th>
                    <td>@total_invoice (#products[0]->Total,#invoice->Discount,$tax)</td>
                </tr>
            </tbody>
        </table>

    </div>
</div>


