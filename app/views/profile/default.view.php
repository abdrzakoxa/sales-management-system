
<div class="input-image-profile">
    <img src="{{ $this->imageUser() }}" width="100" height="100" >
</div>



<form class="form-image"  method="post" enctype="multipart/form-data">
    <div class="image-editor">
        <div class="cropit-preview"></div>
        <div class="slidecontainer">
            <input type="range" class="cropit-image-zoom-input slider" id="myRange">
        </div>
        <input type="hidden" name="image-data" class="hidden-image-data" />
        <div class="controll">
            <span class="bn b-primary-submit costom-upload">
                { text_choose_photo }
                <input type="file" name="image" class="cropit-image-input">
            </span>
            <button class="bn b-primary-submit" type="submit">{ text_upload_photo }</button>
        </div>
    </div>
</form>
<div class="overlay"></div>


<form class="f-row form-style" method="post" autocomplete="off" >
    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_first_name }</label>
        <input type="text" name="first_name" value="@autoValue (first_name,Profile->FirstName)" min="3" max="15"  data-pattern="^[A-z \u0600-\u06FF]{3,15}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_last_name }</label>
        <input type="text" name="last_name" value="@autoValue (last_name,Profile->LastName)" min="3" max="20"  data-pattern="^[A-z \u0600-\u06FF]{3,20}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2 DOB">
        <label >{ label_DOB }</label>
        <input type="text" name="DOB" value="@autoValue (DOB,Profile->DOB)" min="3" max="20"  data-pattern="^(([0-9][0-9][0-9][0-9])-(0:?[0-9]|1[0-2])-(3:?[0-1]|[0-2][0-9]))?$" >
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2 bln">
        <label >{ label_address }</label>
        <input type="text" name="address" value="@autoValue (Address,Profile->Address)" min="6" max="60" data-pattern="^:?[A-z -\/,0-9\u0600-\u06FF]{0,60}$" >
    </div>


    @notempty (#permissions)
        <div class="input-group-s checkbox-g f-row col-1 ">
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

