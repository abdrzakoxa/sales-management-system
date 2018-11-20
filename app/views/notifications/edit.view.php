<form class="f-row form-style expenses-categories-edit" method="post" autocomplete="off">

    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_title }</label>
        <input type="text" name="title" value="{ Notification->Title }" data-pattern="^[\w\(\)\:\?\!\-\| 0-9\u0600-\u06FF]{0,80}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_content }</label>
        <input type="text" name="content" value="{ Notification->Content }" data-pattern="^[\w\(\)\:\?\!\-\| 0-9\u0600-\u06FF]{3,150}$" >
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_link }</label>
        <input type="text" name="link" value="{ Notification->Link }" data-pattern="^(\/?[A-Za-z0-9]+\/?){0,80}(\??[a-zA-Z0-9]+(\=[a-zA-Z0-9]+(\&\&[A-Za-z0-9]+\=[a-zA-Z0-9]+)?)?)?$" >
    </div>


    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }" >
    </div>

</form>
