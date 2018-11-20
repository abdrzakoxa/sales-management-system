<form class="f-row form-style group-edite" method="post" autocomplete="off">


    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-1">
        <label >{ label_groupName }</label>
        <input type="text" name="groupName" value="{ Groups->GroupName }" min="3" max="15"  data-pattern="^[a-zA-Z \u0600-\u06FF]+$" >
    </div>

    @if (!empty(#permissions))
    <div class="input-group-s checkbox-g col-1">
        @foreach (#permissions as $permission)
            <label class="checkmark-p" for="{! $permission->Name !}">
                <input type="checkbox" name="permission[]" id="{! $permission->Name !}" value="{! $permission->PermissionId !}"
                    @foreach (#PermissionsGroups as $permissionGroup)
                    @if ($permissionGroup->PermissionId == $permission->PermissionId)
                        checked
                    @endif
                    @endforeach
                >
                {! $permission->Name !}
            </label>
        @endforeach
    </div>
    @endif

    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }" >
    </div>

</form>



