<div class="actions">
    <a class="b-primary-upt bn" href="/Purchases/Create/">{ text_new_purchase }</a>
</div>
<table class="display dataTableEnable">
    <thead>
    <tr>
        <th width="50px">{ text_purchase_id }</th>
        <th>{ text_username }</th>
        <th>{ text_supplier_name }</th>
        <th>{ text_payment_type }</th>
        <th>{ text_payment_status }</th>
        <th>{ text_sum_invoice }</th>
        <th>{ text_count_categories }</th>
        <th>{ text_discount }</th>
        <th>{ text_created_date }</th>
        <th>{ text_control }</th>
    </tr>
    </thead>
    <tbody>


@foreach (#Purchases as $Purchase)
    <tr>
        <td>{! $Purchase->InvoiceId !}</td>
        <td>{! $Purchase->Username !}</td>
        <td>{! $Purchase->FirstName # $Purchase->LastName !}</td>
        <td>{ array_payment_type[$Purchase->PaymentType] }</td>
        <td>{ array_payment_status[$Purchase->PaymentStatus]  }</td>
        <td>@total_invoice ($Purchase->Sum,$Purchase->Discount)</td>
        <td>{! $Purchase->CountCategories  !}</td>
        <td>@number_parse ($Purchase->Discount)</td>
        <td data-bottom-title="{ on_time } @time_format ($Purchase->CreatedDate)">@date_format ($Purchase->CreatedDate)</td>
        <td><a href="/Purchases/Edit/?id={! $Purchase->InvoiceId !}" data-top-title="{ title_edit }"><i class="far fa-edit"></i></a><a href="/Purchases/Delete/?id={! $Purchase->InvoiceId !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this user')"><i class="far fa-trash-alt"></i></a></td>
    </tr>
@endforeach
    </tbody>
</table>