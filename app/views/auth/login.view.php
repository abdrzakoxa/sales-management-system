<div class="cover-login">


<! $Messengers = \Store\Core\Messenger::getInstance() !>
@if ($Messengers->exist())
    @foreach ($Messengers->getMessengers() as $messenger)
        @if ($messenger[1] == 3)
        <div class="container"><div class="Messenger Type{! $messenger[1] !} "><?php echo $messenger[0] ?></div></div>
        @else
        <div class="container"><div class="Messenger fadeout Type{! $messenger[1] !} "><?php echo $messenger[0] ?></div></div>
        @endif
    @endforeach
    <! $Messengers->emptyMessengers() !>
@endif

    <form class="f-row form-login" autocomplete="off" method="post">
        <h2 class="title-login">{ text_login }</h2>
        <div class="avatar d-flex">
            <img width="80" height="80" src="<?php echo IMAGES_PATH . DS . 'user-login.png' ?>" >
        </div>
        <div class="input-group-s col-1">
        <label >{ label_username }</label>
            <input type="text" name="username" value="@post (username)" min="3" max="15"  data-pattern="^(?=[A-z\p{Arabic}]*[0-9_-])(?=[0-9]*[A-z\p{Arabic}]).{3,15}$">
        </div>


        <div class="input-group-s col-1">
        <label >{ label_password }</label>
            <input type="password" name="password" value="@post (password)" max="18" min="6" data-pattern="^(?=.*[A-z])(?=.*[0-9]).+$" >
        </div>

        @if (#is_captcha)

        <div class="input-group-s col-1">
            <label >{ text_captcha } ( { captcha } )</label>
            <input type="text" name="captcha" data-pattern="^[0-9]+$" >
        </div>


        <input type="hidden" name="captcha-num" value="{ captcha }">

        @endif

        <div class="input-submit-p col-1">
            <input type="submit" class="b-primary-submit bn w-100" name="submit" value="{ text_login_button }">
        </div>
    </form>

</div>