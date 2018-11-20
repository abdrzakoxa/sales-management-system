<form id="setting-company" class="f-row form-style" method="post" autocomplete="off">
    <span class="form-title bn">{ text_new_base_unit }</span>
    <div class="input-group-s col-md-down-1 col-md-up-2 col-lg-up-3">
        <label>{ label_name }</label>
        <input type="text" name="name" value="@post (name)" min="3" max="15" data-pattern="^[A-z \u0600-\u06FF]{3,30}$">
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2 col-lg-up-3">
        <label>{ label_code }</label>
        <input type="text" name="code" value="@post (code)" min="6" max="50" data-pattern="^[a-zA-Z]{1,6}$">
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2 col-lg-up-3">
        <label >{ label_base_unit }</label>
        <select name="base_unit">
            <option value="0" disabled selected>{ label_base_unit }</option>
            @foreach (#UnitsBase as $Unit)
            <option value="{! $Unit->BaseUnitId !}" @if ( @auth_post (base_unit,$Unit->BaseUnitId) ) selected @end > {! $Unit->Name !}</option>
            @endforeach
        </select>
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2 col-lg-up-3">
        <label >{ label_operator }</label>
        <select name="operator">
            <option value="0" disabled selected>{ label_operator }</option>
            @foreach (#main_operator as $code => $operator)
            <option value="{! $code !}" @if ( @auth_post (operator,$code) ) selected @end > {! $operator !}</option>
            @endforeach
        </select>
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2 col-lg-up-3">
        <label>{ label_operation_value }</label>
        <input type="text" name="operation_value" value="@post (operation_value)" min="6" max="50" data-pattern="^[0-9]+$">
    </div>

    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }">
    </div>

</form>

