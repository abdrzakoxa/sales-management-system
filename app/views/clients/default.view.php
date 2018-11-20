<div class="actions">
    <a class="b-primary-upt bn" href="/Clients/Create/">{ text_new_client }</a>
</div>
<table class="display dataTableEnable">
    <thead>
    <tr>
        <th width="100px">{ text_client_id }</th>
        <th>{ text_first_name }</th>
        <th>{ text_last_name }</th>
        <th>{ text_email }</th>
        <th>{ text_phone_number }</th>
        <th>{ text_address }</th>
        <th>{ text_control }</th>
    </tr>
    </thead>
    <tbody>


@foreach (#Clients as $Client)

    <tr>
        <td>{! $Client->ClientId !}</td>
        <td>{! $Client->FirstName !}</td>
        <td>{! $Client->LastName !}</td>
        <td>{! $Client->Email !}</td>
        <td>{! $Client->Phone !}</td>
        <td>{! $Client->Address !}</td>

        <td><a href="/Clients/Edit/?id={! $Client->ClientId !}" data-top-title="{ title_edit }"><i class="far fa-edit"></i></a><a href="/Clients/Delete/?id={! $Client->ClientId !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this user')"><i class="far fa-trash-alt"></i></a></td>
    </tr>

@endforeach
    </tbody>
</table>
