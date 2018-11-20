<form class="f-row form-style users-edit" method="post" autocomplete="off">
    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_username }</label>
        <input type="text" name="username" value="{ User->Username }" min="3" max="15"  data-pattern="^(?=[A-z\p{Arabic}]*[0-9_-])(?=[0-9]*[A-z\p{Arabic}]).{3,15}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_email }</label>
        <input type="text" name="email" value="{ User->Email }" min="6" max="50"  data-pattern="^(?=^.{6,50})(([A-z0-9-_.]+)@([A-z0-9.-_]+)\.([A-z]{2,}))$" >
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_password }</label>
        <input type="password" name="password" max="18" min="6" data-pattern="^$|((?=.*[A-z])(?=.*[0-9])).{6,18}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_confirm_password }</label>
        <input type="password" name="confirm_password" max="18" min="6" data-pattern="^$" >
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_phone }</label>
        <input type="text" name="phone" value="{ User->Phone }" min="10" max="15" data-pattern="^[+(]{0,2}\d{3}[). -]{0,2}[- .]?[0-9]{3}[-. ]?[\d]+$" >
    </div>

    @is_permission_user (Groups)
    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label>{ label_groupid }</label>
        <select name="groupid">
            <option value="0" disabled selected>{ label_groupid }</option>
            @foreach (#Groups as $Group)
                <option value="{! $Group->GroupId !}" @if ( #User->GroupId == $Group->GroupId )
                    selected @endif > {! $Group->GroupName !}
                </option>
            @endforeach
        </select>
    </div>
    @end

    <div class="input-group-s radio-g col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_sex } :</label>
        <label class="checkmark-p" for="male">
            <input type="radio" id="male" value="2" name="sex" @if (#User->Sex == '2') checked @endif  >
            { label_male }
        </label>
        <label class="checkmark-p" for="female">
            <input type="radio" id="female" value="1" name="sex" @if (#User->Sex == '1') checked @endif  >
            { label_female }
        </label>

    </div>

    @notempty (#permissions)
        <div class="input-group-s checkbox-g f-row col-1">
            <span class="heading-input">{ permission_of_this_user }</span>
            @foreach (#permissions as $permission)
                <label class="checkmark-p col-sm-down-1 col-sm-up-2 col-md-up-3" for="{! $permission->Name !}">
                    <input type="checkbox" name="permission[]" id="{! $permission->Name !}" value="{! $permission->PermissionId !}"
                    @foreach (#PermissionUser as $permission_user)
                        @if ($permission_user == $permission->PermissionId)
                            checked
                        @endif
                    @endforeach
                    >
                    {! $permission->Name !}
                </label>
            @endforeach
        </div>
    @endempty

    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }" >
    </div>
    <input type="hidden" name="password_old" value="{ User->Password }" >
</form>
