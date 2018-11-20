<div class="actions">
    <a class="b-primary-upt bn" href="/Users/Create/">{ text_new_user }</a>
</div>
<table class="display dataTableEnable">
    <thead>
    <tr>
        <th>{ text_user_id }</th>
        <th>{ text_username }</th>
        <th>{ text_email }</th>
        <th>{ text_sex }</th>
        <th>{ text_status }</th>
        <th>{ text_phone_number }</th>
        <th>{ text_ip_address }</th>
        <th>{ text_registered }</th>
        <th>{ text_last_login }</th>
        <th>{ text_control }</th>
    </tr>
    </thead>
    <tbody>




@foreach (#Users as $User)
    <tr>
        <td>{! $User->UserId !}</td>
        <td>{! $User->Username !}</td>
        <td>{! $User->Email !}</td>
        <td>{! #array_sex[$User->Sex] !}</td>
        <td>{! #array_status[$User->Status] !}</td>
        <td>{! $User->Phone !}</td>
        <td>@access ($User->IpAddress)</td>
        <td data-bottom-title="{ on_time } @time_format ($User->Registered)"> @date_format ($User->Registered) </td>
        <td>
            @if (strtotime($User->LastLogin) > time() - 300)
            <i class="fas fa-dot-circle online"></i> { text_online }
            @else
            <i class="fas fa-dot-circle offline"></i> @if ($User->LastLogin != 0): @time_elapsed_string ($User->LastLogin) @else { create_account_ago } @end
            @endif
        </td>
        <td>
            <a href="/Users/Preview/?id={! $User->UserId !}" data-top-title="{ title_preview }"><i class="far fa-eye"></i></a>
            <a href="/Users/Edit/?id={! $User->UserId !}" data-top-title="{ title_edit }"><i class="far fa-edit"></i></a>
            <a href="/Users/Delete/?id={! $User->UserId !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this user')"><i class="far fa-trash-alt"></i></a>
        </td>
    </tr>
@endforeach
    </tbody>
</table>
