<?php

namespace Store\core;


use function PHPSTORM_META\type;
use Store\models\NotificationsModel;
use Store\models\NotificationsUsersModel;
use Store\Models\ProductsModel;

trait Notifications
{

    public function finished_product($productId)
    {
        $product = ProductsModel::getByKey($productId);

        if (is_numeric($product->NotificationQuantity) && $product->NotificationQuantity >= $product->Quantity){
            $title = "{{not_title_3}}";
            $content = $product->Quantity > 0 ? "{{not_content_3!!". $product->Title . ',' . self::number_zero($product->Quantity) ."}}" : "{{not_content_zero_3!!". $product->Title ."}}";
            $userId = $this->Session->User->UserId;
            $link = "/products/Edit?id=" . $productId;
            if (empty(NotificationsModel::getBy('Content',$content))){
                $this->create_not($title,$content,3,$userId,$link);
            }
        }

    }

    public function start_notifications()
    {
//        $this->finished_product();
    }

    public function getLimit($num)
    {
        if (!is_numeric($num)) return false;
        return DB::statement('SELECT * FROM app_notifications ORDER BY NotificationId DESC LIMIT '.$num,[],true)->get();
    }

    public function create_not($title,$content_params,$type,$userId,$link = null)
    {
//        var_dump($title,$content_params,$type,$userId);
        $Notification = new NotificationsModel();
        $NotificationUser = new NotificationsUsersModel();
        $Notification->Title  = $title;
        $Notification->Content    = $content_params;
        $Notification->Type   = $type;
        $Notification->Link   = $link;
        if ($this->is_permission_user('Notifications',$userId)){
            if($Notification->create()) {
            $NotId = DB::connect()->lastInsertId();
            $NotificationUser->UserId = $userId;


            $NotificationUser->NotificationId = $NotId;
                if ($NotificationUser->create()) {
                    return true;
                }
            }
        }
        return false;
    }

}