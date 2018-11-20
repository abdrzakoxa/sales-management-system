<div class="actions">
    <a class="b-primary-upt bn" href="/Suppliers/Create/">{ text_new_supplier }</a>
</div>
<table class="display dataTableEnable">
    <thead>
    <tr>
        <th width="100px">{ text_supplier_id }</th>
        <th>{ text_first_name }</th>
        <th>{ text_last_name }</th>
        <th>{ text_email }</th>
        <th>{ text_phone_number }</th>
        <th>{ text_address }</th>
        <th>{ text_control }</th>
    </tr>
    </thead>
    <tbody>

@foreach (#Suppliers as $Supplier)

    <tr>
        <td>{! $Supplier->SupplierId !}</td>
        <td>{! $Supplier->FirstName !}</td>
        <td>{! $Supplier->LastName !}</td>
        <td>{! $Supplier->Email !}</td>
        <td>{! $Supplier->Phone !}</td>
        <td>{! $Supplier->Address !}</td>

        <td><a href="/Suppliers/Edit/?id={! $Supplier->SupplierId !}" data-top-title="{ title_edit }"><i class="far fa-edit"></i></a><a href="/Suppliers/Delete/?id={! $Supplier->SupplierId !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this user')"><i class="far fa-trash-alt"></i></a></td>
    </tr>

@endforeach
    </tbody>
</table>
