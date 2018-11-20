<form id="setting-company" class="f-row form-style" method="post" autocomplete="off">
    <span class="form-title bn">{ text_new_base_unit }</span>
    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label>{ label_name }</label>
        <input type="text" name="name" value="@auto_val_obj_post (#BaseUnit->Name,name)" min="3" max="15" data-pattern="^[A-z \u0600-\u06FF]{3,30}$">
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label>{ label_code }</label>
        <input type="text" name="code" value="@auto_val_obj_post (#BaseUnit->Code,code)" min="6" max="50" data-pattern="^[a-zA-Z]{1,6}$">
    </div>

    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }">
    </div>

</form>

