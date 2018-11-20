<! $Messengers = \Store\Core\Messenger::getInstance() !>
@if ($Messengers->exist())
@foreach ($Messengers->getMessengers() as $messenger)
<div class="container"><div class="Messenger fadeout Type{! $messenger[1] !} ">{{ $messenger[0] }}</div></div>
@endforeach
<! $Messengers->emptyMessengers() !>
@endif


<div class="form-install">
    <div class="container">

        @company_empty ()

        <form class="f-row form-style tab-parent" method="post" autocomplete="off">
            <span class="form-title bn">{ text_settings_company }</span>
            <div class="input-group-s col-1">
                <label>{ label_company_name }</label>
                <input type="text" name="company_name" value="@post (company_name)" min="3" max="15" data-pattern=".*">
            </div>

            <div class="input-group-s col-1">
                <label>{ label_email }</label>
                <input type="text" name="email" value="@post (email)" min="6" max="50" data-pattern=".*">
            </div>

            <div class="input-group-s col-1">
                <label>{ label_phone }</label>
                <input type="text" name="phone" value="@post (phone)" min="10" max="15" data-pattern=".*">
            </div>

            <div class="input-group-s col-1 bln">
                <label>{ label_address }</label>
                <input type="text" name="address" value="@post (address)" min="6" max="60" data-pattern=".*">
            </div>

            <div class="input-submit-p">
                <input type="submit" class="bn b-primary-submit" name="submit_settings_company" value="{ text_next }">
            </div>

        </form>

        @else

        <form class="f-row form-style tab-parent" method="post" autocomplete="off">
            <span class="form-title bn">{ text_settings_database }</span>
            <div class="input-group-s col-1">
                <label>{ label_database_name }</label>
                <input type="text" name="database_name" value="@post (database_name)" min="3" max="15" data-pattern="^.+$">
            </div>

            <div class="input-group-s col-1">
                <label>{ label_username }</label>
                <input type="text" name="username" value="@post (username)" min="6" max="50" data-pattern="^.+$">
            </div>

            <div class="input-group-s col-1">
                <label>{ label_password }</label>
                <input type="text" name="password" value="@post (password)" min="10" max="15" data-pattern="^.*$">
            </div>

            <div class="input-group-s col-1 bln">
                <label>{ label_host_name }</label>
                <input type="text" name="hostname" value="@post (hostname)" min="6" max="60" data-pattern="^.+$">
            </div>

            <div class="input-submit-p">
                <input type="submit" class="bn b-primary-submit" name="submit_settings_database" value="{ text_save }">
            </div>

        </form>

        @end

    </div>
</div>
