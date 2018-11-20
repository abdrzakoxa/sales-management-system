<div class="actions">
    <a class="b-primary-upt bn" href="/Notifications/Create/">{ text_new_category }</a>
</div>

<div class="card">
    <div class="card-header">
        <span>{ text_card_title }</span>
        <div class="card-header-control">
            <div class="card-control-view">
                { text_view } : <span>{ text_classic }</span> | <span>{ text_full }</span>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if (#Notifications != [])
        @foreach (#Notifications as $Notification)
            <div class="card-category" >
                <h3><a href="{! $Notification->Link !}"><span class="not_icon_{! $Notification->Type !} not_icon"></span> @notification_title ($Notification->Title)</a></h3>
                @notempty ($Notification->Content)
                <p>@replace_link ($Notification->Content,$Notification->Link)</p>
                @endempty
                <span class="not-date"><i class="far fa-clock"></i> @time_elapsed_string ($Notification->CreatedDate)</span>
                <span class="card-control">
                    <a href="/Notifications/Edit/?id={! $Notification->NotificationId !}">
                        <i class="far fa-edit"></i>
                    </a>
                    <a href="/Notifications/Delete/?id={! $Notification->NotificationId !}" onclick="return confirm('do you want delete this Notification')">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </span>
            </div>
        @endforeach
        @else
            <div class="not-found-category">
                <span>{ text_not_found }</span>
            </div>
        @endif
    </div>
</div>
