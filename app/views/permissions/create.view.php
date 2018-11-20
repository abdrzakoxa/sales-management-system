<form class="f-row form-style permissions-create" method="post" autocomplete="off">
    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_name }</label>
        <input type="text" name="name" value="@post (Name)" min="3" max="30"  data-pattern="^[a-zA-Z \u0600-\u06FF]{3,30}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_permission }</label>
        <input type="text" name="permission" value="@post (permission)" min="3" max="30"  data-pattern="^(?=^.{3,30}$)[A-Za-z]+(\/[A-z]+)?$" >
    </div>

    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }" >
    </div>
</form>
