<form class="f-row form-style expenses-categories-create" method="post" autocomplete="off">
    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_type }</label>
        <input type="text" name="type" value="@post (type)" min="3" max="25"  data-pattern="^[A-z \u0600-\u06FF]{3,25}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_fixed_payment }</label>
        <input type="text" name="fixedPayment" value="@post (fixedPayment)" min="0" max="10"  data-pattern="^[0-9]+(\.[0-9]{1,8})?$" >
    </div>


    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }" >
    </div>

</form>
