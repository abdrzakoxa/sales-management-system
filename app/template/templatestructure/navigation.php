<div class="side-menu">
    <div class="user-info">
        <img src="{! $this->imageUser() !}" width="100" height="100" >
        @if (isset($this->Session->Profile) && $this->Session->Profile->LastName != '' && $this->Session->Profile->FirstName != '')
        <span>{! $this->Session->Profile->FirstName _ $this->Session->Profile->LastName !}</span>
        @endif
        <small>{! $this->Session->User->GroupName !}</small>
    </div>

    <ul class="menu">
        @is_permission_user (dashboard)
        <li class="<! $this->getClassActive('dashboard') !>"><a href="/">
            <i class="fas fa-tachometer-alt"></i>{ text_dashboard }</a>
        </li>
        @end
        @is_permission_user (Products,ProductsCategories)
        <li class="<! $this->getClassActive(['products','productscategories']) !>"><a><i class="fas fa-store"></i>{ text_store }<i class="fas fa-angle-down icon-to-down"></i></a>
            <ul class="child-menu">
                @is_permission_user (Products)
                <li><a href="/Products/"><i class="fas fa-shopping-cart"></i>{ text_products }</a></li>
                @end
                @is_permission_user (Products/Create)
                <li><a href="/Products/Create"><i class="fas fa-plus"></i>{ text_new_products }</a></li>
                @end
                @is_permission_user (ProductsCategories)
                <li><a href="/ProductsCategories/"><i class="fas fa-bars"></i>{ text_categories }</a></li>
                @end
                @is_permission_user (ProductsCategories/Create)
                <li><a href="/ProductsCategories/Create"><i class="fas fa-plus"></i>{ text_new_categories }</a></li>
                @end
            </ul>
        </li>
        @end
        @is_permission_user (Users,Groups,Permissions)
        <li class="<! $this->getClassActive(['users','groups','permissions']) !>"><a><i class="fas fa-users"></i>{ text_users_management }<i class="fas fa-angle-down icon-to-down"></i></a>
            <ul class="child-menu">
                @is_permission_user (Users)
                <li><a href="/Users/"><i class="fas fa-user-tie"></i>{ text_users  }</a></li>
                @end
                @is_permission_user (Users/Create)
                <li><a href="/Users/Create"><i class="fas fa-plus"></i>{ text_new_user  }</a></li>
                @end
                @is_permission_user (Groups)
                <li><a href="/Groups/"><i class="fas fa-user-tag"></i>{ text_group_users  }</a></li>
                @end
                @is_permission_user (Groups/Create)
                <li><a href="/Groups/Create"><i class="fas fa-plus"></i>{ text_new_group  }</a></li>
                @end
                @is_permission_user (Permissions)
                <li><a href="/Permissions/"><i class="fas fa-key"></i>{ text_permissions  }</a></li>
                @end
                @is_permission_user (Permissions/Create)
                <li><a href="/Permissions/Create"><i class="fas fa-plus"></i>{ text_new_permission }</a></li>
                @end
            </ul>
        </li>
        @end
        @is_permission_user (ExpensesCategories,Expenses)
        <li class="<! $this->getClassActive(['ExpensesCategories','Expenses']) !>"><a><i class="far fa-money-bill-alt"></i>{ text_expenses_management }<i class="fas fa-angle-down icon-to-down"></i></a>
            <ul class="child-menu">
                @is_permission_user (Expenses)
                <li><a href="/Expenses/"><i class="fas fa-dollar-sign"></i>{ text_expenses  }</a></li>
                @end
                @is_permission_user (Expenses)
                <li><a href="/Expenses/Create"><i class="fas fa-plus"></i>{ text_new_expenses  }</a></li>
                @end
                @is_permission_user (ExpensesCategories)
                <li><a href="/ExpensesCategories/"><i class="fas fa-bars"></i>{ text_categories  }</a></li>
                @end
                @is_permission_user (ExpensesCategories)
                <li><a href="/ExpensesCategories/Create"><i class="fas fa-plus"></i>{ text_new_categories  }</a></li>
                @end
            </ul>
        </li>
        @end
        @is_permission_user (Sales,Purchases)
        <li class="<! $this->getClassActive(['Sales','Purchases']) !>"><a><i class="far fa-handshake"></i>{ text_transactions }<i class="fas fa-angle-down icon-to-down"></i></a>
            <ul class="child-menu">
                @is_permission_user (Sales)
                <li><a href="/Sales/"><i class="fas fa-cart-arrow-down"></i>{ text_sales  }</a></li>
                @end
                @is_permission_user (Sales/Create)
                <li><a href="/Sales/Create"><i class="fas fa-plus"></i>{ text_new_sales  }</a></li>
                @end
                @is_permission_user (Purchases)
                <li><a href="/Purchases/"><i class="fas fa-cart-plus"></i>{ text_purchases  }</a></li>
                @end
                @is_permission_user (Purchases/Create)
                <li><a href="/Purchases/Create"><i class="fas fa-plus"></i>{ text_new_purchases  }</a></li>
                @end
            </ul>
        </li>
        @end

        @is_permission_user (Notifications)
        <li class="<! $this->getClassActive('Notifications') !>"><a><i class="fas fa-exclamation-circle"></i>{ text_notifications }<i class="fas fa-angle-down icon-to-down"></i></a>
            <ul class="child-menu">
                @is_permission_user (Notifications)
                <li><a href="/Notifications/"><i class="fas fa-exclamation-circle"></i>{ text_notifications }</a></li>
                @end
                @is_permission_user (Notifications/Create)
                <li><a href="/Notifications/Create"><i class="fas fa-plus"></i>{ text_new_notifications  }</a></li>
                @end
            </ul>
        </li>
        @end

        @is_permission_user (Suppliers)
        <li class="<! $this->getClassActive('Suppliers') !>"><a><i class="fas fa-user-friends"></i>{ text_suppliers }<i class="fas fa-angle-down icon-to-down"></i></a>
            <ul class="child-menu">
                @is_permission_user (Suppliers)
                <li><a href="/Suppliers/"><i class="fas fa-user-friends"></i>{ text_suppliers }</a></li>
                @end
                @is_permission_user (Suppliers/Create)
                <li><a href="/Suppliers/Create"><i class="fas fa-plus"></i>{ text_new_supplier  }</a></li>
                @end
            </ul>
        </li>
        @end

        @is_permission_user (Clients)
        <li class="<! $this->getClassActive('Clients') !>"><a><i class="fas fa-user-friends"></i>{ text_clients }<i class="fas fa-angle-down icon-to-down"></i></a>
            <ul class="child-menu">
                @is_permission_user (Clients)
                <li><a href="/Clients/"><i class="fas fa-user-friends"></i>{ text_clients }</a></li>
                @end
                @is_permission_user (Clients/Create)
                <li><a href="/Clients/Create"><i class="fas fa-plus"></i>{ text_new_client  }</a></li>
                @end
            </ul>
        </li>
        @end

        @is_permission_user (Settings/System,Settings/Units,Auth/Logout)
        <li class="<! $this->getClassActive(['Settings']) !>"><a><i class="fas fa-cog"></i>{ text_settings }<i class="fas fa-angle-down icon-to-down"></i></a>
            <ul class="child-menu">
                @is_permission_user (Settings/System)
                <li><a href="/Settings/System"><i class="fas fa-cogs"></i>{ text_system }</a></li>
                @end
                @is_permission_user (Settings/Units)
                <li><a href="/Settings/Units"><i class="fas fa-balance-scale"></i>{ text_units }</a></li>
                @end
                @is_permission_user (Settings/BackupDatabase)
                <li><a href="/Settings/BackupDatabase"><i class="fas fa-database"></i>{ text_backup }</a></li>
                @end
                @is_permission_user (Auth/Logout)
                <li><a href="/Auth/Logout"><i class="fas fa-sign-out-alt"></i>{ text_logout }</a></li>
                @end
            </ul>
        </li>
        @end
        <li><a><i class="fas fa-palette"></i>{ text_themes }<i class="fas fa-angle-down icon-to-down"></i></a>
            <ul class="child-menu">
                <li><a href="/Settings/themes?theme=light"><i class="fas fa-square light"></i>{ text_light  }</a></li>
                <li><a href="/Settings/themes?theme=dark"><i class="fas fa-square dark"></i>{ text_dark  }</a></li>
                <li><a href="/Settings/themes?theme=blue"><i class="fas fa-square blue"></i>{ text_blue  }</a></li>
                <li><a href="/Settings/themes?theme=purple"><i class="fas fa-square purple"></i>{ text_purple  }</a></li>
            </ul>
        </li>
    </ul>
</div>

<div class="up-menu justify-space-bet">
    <div class="menu-end align-center">
        @is_permission_user (sales)
        <span id="scanner-barcode" data-bottom-title="{ text_use_scanner_to_add_sale }">
            <i class="fas fa-barcode"></i>
        </span>
        @end
        @is_permission_user (notifications)
        <span class="notifications">
            <span data-bottom-title="{ title_notifications }">
                <i class="fas fa-bell"></i>
            </span>
            @if (!empty(#countNotViews))
            <small class="count-not">{ countNotViews }</small>
            @endif
            <div class="notification-box box-toggle">
                @if (!empty (#notificationsnotview))
                @foreach (#notificationsnotview as $Notification)
                <div class="not-body">
                    <span class="not_icon_{! $Notification->Type !} not_icon"></span>
                    <a href="/Notifications/View?id={! $Notification->NotificationUserId !}" class="not-title">@notification_title ($Notification->Title)</a>
                    <span class="not-date" title="@date_format ($Notification->CreatedDate,H:i:s)"><i class="far fa-clock"></i>@time_elapsed_string ($Notification->CreatedDate)</span>
                </div>
                @endforeach
                @else
                <div class="not-body not-found-notifications">
                    <i class="fas fa-exclamation-triangle"></i> { text_not_found_not }
                </div>
                @endif
                <div class="not-show-all">
                    <a href="/Notifications/">{ text_show_all }</a>
                </div>
            </div>
        </span>
        @end
        <span class="profile-cog">
            <img src="{! $this->imageUser() !}" width="30" height="30">
            <span>
                @if (isset($this->Session->Profile->FirstName) && $this->Session->Profile->FirstName != '')
                {! #text_welcome _ $this->Session->Profile->FirstName !}
                @else
                {! #text_welcome _ $this->Session->User->Username !}
                @endif
            </span>
            <ul class="U-menu box-toggle">
                <li><a href="/Profile/">{ text_profile }</a></li>
                <li><a href="/Users/Edit/?id={! $this->Session->User->UserId !}">{ text_edit_account }</a></li>
                <li><a href="/Settings/System/">{ text_settings }</a></li>
                <li><a href="/Auth/Logout/">{ text_logout }</a></li>
            </ul>
        </span>
        <a id='lang' href="/languages/change/" data-bottom-title="{ title_lang }"><i class="fas fa-language"></i></a>
    </div>
    <div class="up-m-left">
        <i class="fas fa-chevron-left control-menu"></i>
        <span>{ title_page }</span>
    </div>


</div>
<div class="clear-path"></div>

<! $Messengers = \Store\Core\Messenger::getInstance() !>

@if ($Messengers->exist())
@foreach ($Messengers->getMessengers() as $messenger)
<div class="Messenger fadeout Type{! $messenger[1] !} ">{{ $messenger[0] }}</div>
@endforeach
<! $Messengers->emptyMessengers() !>
@endif


@if ($Messengers->exist(true))
@foreach ($Messengers->getMessengers(true) as $messenger)
<div class="Messenger Type{! $messenger[1] !} ">{{ $messenger[0] }}</div>
@endforeach
<! $Messengers->emptyMessengers(true) !>
@endif




<div class="notification-box-message">
    @if (#notificationsnotviewshowed != '')

    @foreach (#notificationsnotviewshowed as $notification)


    <div class="notification-box-box" id="{! $notification->NotificationUserId !}">
        <span class="close-not">
            <i class="fas fa-times"></i>
        </span>
        <span class="notification-title">
            <span class="not_icon_{! $notification->Type !} not_icon"></span>
            <a href="/Notifications/View?id={! $notification->NotificationUserId !}" class="not-title"> @notification_title ($notification->Title)</a>
        </span>
        <p>@replace_link ($notification->Content,$notification->Link)</p>
        <span class="not-date">
            <i class="far fa-clock"></i>
            {! $this->time_elapsed_string($notification->CreatedDate) !}
        </span>
    </div>
    @endforeach
    @end
</div>


<input type="text" name="barcode-input-dashboard124" id="barcode-input-dashboard124" autofocus >
