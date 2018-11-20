<form class="f-row form-style suppliers-create" method="post" autocomplete="off">
    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_firstName }</label>
        <input type="text" name="firstName" value="@post (firstName)" min="3" max="15"  data-pattern="^[A-z \u0600-\u06FF]{3,15}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_lastName }</label>
        <input type="text" name="lastName" value="@post (lastName)" min="3" max="20"  data-pattern="^[A-z \u0600-\u06FF]{3,20}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_email }</label>
        <input type="text" name="email" value="@post (email)" min="6" max="50"  data-pattern="^(?=^.{6,50}$)(([A-z0-9-_.]+)@([A-z0-9.-_]+)\.([A-z]{2,}))$" >
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2">
        <label >{ label_phone }</label>
        <input type="text" name="phone" value="@post (phone)" min="10" max="15" data-pattern="^(?=^.{10,15}$)[+(]{0,2}\d{3}[). -]{0,2}[- .]?[0-9]{3}[-. ]?[\d]+$" >
    </div>

    <div class="input-group-s col-md-down-1 col-lg-up-3 col-md-up-2 bln">
        <label >{ label_address }</label>
        <input type="text" name="address" value="@post (address)" min="6" max="60" data-pattern="^:?[A-z -\/,0-9\u0600-\u06FF]{0,60}$" >
    </div>

    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }" >
    </div>

</form>
