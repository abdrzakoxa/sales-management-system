<div class="actions">
    <a class="b-primary-upt bn" href="/Sales/Create/">{ text_new_sale }</a>
</div>
<table class="display dataTableEnable">
    <thead>
    <tr>
        <th width="50px">{ text_sale_id }</th>
        <th>{ text_username }</th>
        <th>{ text_client_name }</th>
        <th>{ text_payment_type }</th>
        <th>{ text_payment_status }</th>
        @tax_allow
        <th>{ text_tax }</th>
        @end
        <th>{ text_sum_invoice }</th>
        <th>{ text_count_categories }</th>
        @discount_allow
        <th>{ text_discount }</th>
        @end
        <th>{ text_created_date }</th>
        <th>{ text_control }</th>
    </tr>
    </thead>
    <tbody>

@foreach (#Sales as $Sale)
    <tr>
        <td>{! $Sale->InvoiceId !}</td>
        <td>{! $Sale->Username !}</td>
        <td>{! $Sale->FirstName # $Sale->LastName !}</td>
        <td>{ array_payment_type[$Sale->PaymentType] }</td>
        <td>{ array_payment_status[$Sale->PaymentStatus] }</td>
        @tax_allow
        <td>@tax_invoice ($Sale->InvoiceId)</td>
        @end
        <td>@total_invoice ($Sale->Sum,$Sale->Discount)</td>
        <td>{! $Sale->CountCategories !}</td>
        @discount_allow
        <td>@number_parse ($Sale->Discount)</td>
        @end
        <td data-bottom-title="{ on_time } @time_format ($Sale->CreatedDate)">@date_format ($Sale->CreatedDate)</td>
        <td>
            <a href="/Sales/Preview/?id={! $Sale->InvoiceId !}" data-top-title="{ title_preview }"><i class="far fa-eye"></i></a>
            <a href="/Sales/Edit/?id={! $Sale->InvoiceId !}" data-top-title="{ title_edit }"><i class="far fa-edit"></i></a>
            <a href="/Sales/Delete/?id={! $Sale->InvoiceId !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this user');"><i class="far fa-trash-alt"></i></a>
        </td>
    </tr>
@endforeach
    </tbody>
</table>