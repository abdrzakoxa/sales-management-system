<form class="f-row form-style group-create" method="post" autocomplete="off">
    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-1">
        <label >{ label_groupName }</label>
        <input type="text" name="groupName" value="@post (email)" min="3" max="15"  data-pattern="^[a-zA-Z \u0600-\u06FF]+$" >
    </div>

    @notempty (#permissions)
    <div class="input-group-s checkbox-g f-row col-1">
        @foreach (#permissions as $permission)
            <label class="checkmark-p col-sm-down-1 col-sm-up-2 col-md-up-3" for="{! $permission->Name !}">
                <input type="checkbox" name="permission[]" id="{! $permission->Name !}" value="{! $permission->PermissionId !}" >
                {! $permission->Name !}
            </label>
        @endforeach
    </div>
    @endempty

    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }" >
    </div>
</form>
