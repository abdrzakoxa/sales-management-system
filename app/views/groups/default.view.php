<div class="actions">
    <a class="b-primary-upt bn" href="/Groups/Create/">{ text_new_groups }</a>
</div>
<table class="display dataTableEnable">
    <thead>
    <tr>
        <th width="100px">{ text_id }</th>
        <th>{ text_name }</th>
        <th width="100px">{ text_control }</th>
    </tr>
    </thead>
    <tbody>

    @foreach (#Groups as $Group)
        <tr>
            <td>{! $Group->GroupId !}</td>
            <td>{! $Group->GroupName !}</td>
            <td><a href="/Groups/Edit/?id={! $Group->GroupId !}" data-top-title="{ title_edit }"><i class="far fa-edit"></i></a><a href="/Groups/Delete/?id={! $Group->GroupId !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this Group')"><i class="far fa-trash-alt"></i></a></td>
        </tr>
    @endforeach
    </tbody>
</table>
