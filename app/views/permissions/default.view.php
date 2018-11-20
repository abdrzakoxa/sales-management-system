<div class="actions">
    <a class="b-primary-upt bn" href="/Permissions/Create/">{ text_new_permission }</a>
</div>
<table class="display dataTableEnable">
    <thead>
    <tr>
        <th>{ text_id }</th>
        <th>{ text_name }</th>
        <th>{ text_permission }</th>
        <th>{ text_control }</th>
    </tr>
    </thead>
    <tbody>
@foreach (#Permissions as $Permission)
        <tr>
            <td>{! $Permission->PermissionId !}</td>
            <td>{! $Permission->Name !}</td>
            <td>{! $Permission->Permission !}</td>
            <td><a href="/permissions/Edit/?id={! $Permission->PermissionId !}" data-top-title="{ title_edit }"><i class="far fa-edit"></i></a><a href="/permissions/Delete/?id={! $Permission->PermissionId !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this user')"><i class="far fa-trash-alt"></i></a></td>
        </tr>
@endforeach
    </tbody>
</table>
