<form class="f-row form-style expenses-categories-create" method="post" autocomplete="off">
    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_title }</label>
        <input type="text" name="title" value="@post (title)" data-pattern="^[\w\(\)\:\?\!\-\| 0-9\u0600-\u06FF]{0,80}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_content }</label>
        <input type="text" name="content" value="@post (content)" data-pattern="^[\w\(\)\:\?\!\-\| 0-9\u0600-\u06FF]{3,150}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_link }</label>
        <input type="text" name="link" value="@post (link)" data-pattern="^(\/?[A-Za-z0-9]+\/?){0,80}(\??[a-zA-Z0-9]+(\=[a-zA-Z0-9]+(\&\&[A-Za-z0-9]+\=[a-zA-Z0-9]+)?)?)?$" >
    </div>


    @notempty (#Users)
    <div class="input-group-s checkbox-g f-row col-1">
        <span class="heading-input">{ label_users_notifications }</span>
        @foreach (#Users as $user)
        <label class="checkmark-p col-sm-down-1 col-sm-up-2 col-md-up-3" for="{! $user->Username !}">
            <input type="checkbox" name="users[]" id="{! $user->Username !}" value="{! $user->UserId !}" >
            {! $user->Username !}
        </label>
        @endforeach
    </div>
    @endempty

    @notempty (#Groups)
    <div class="input-group-s checkbox-g f-row col-1">
        <span class="heading-input">{ label_groups_notifications }</span>
        @foreach (#Groups as $group)
        <label class="checkmark-p col-sm-down-1 col-sm-up-2 col-md-up-3" for="{! $group->GroupName !}">
            <input type="checkbox" name="groups[]" id="{! $group->GroupName !}" value="{! $group->GroupId !}" >
            {! $group->GroupName !}
        </label>
        @endforeach
    </div>
    @endempty


    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }" >
    </div>

</form>
