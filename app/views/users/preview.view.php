
<div class="input-image-profile">
    <img src="{{ $this->readyImage(#Profile->Image) }}" width="100" height="100" >
</div>




<form class="f-row form-style" method="post" autocomplete="off" >
    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_first_name }</label>
        <input type="disable" value="{ Profile->FirstName }">
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_last_name }</label>
        <input type="disable" value="{ Profile->LastName }">
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2 DOB">
        <label >{ label_DOB }</label>
        <input type="disable" value="{ Profile->DOB }">
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2 bln">
        <label >{ label_address }</label>
        <input type="disable" value="{ Profile->Address }" >
    </div>

</form>

