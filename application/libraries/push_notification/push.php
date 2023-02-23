<?php

/**
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class Push {

    // push message title
    private $title;
    private $message;
    private $image;
    // push message payload
    private $data;
    // flag indicating whether to show the push
    // notification or not
    // this flag will be useful when perform some opertation
    // in background when push is recevied
    private $is_background;

    function __construct() {
        
    }

    public function setNotificationid($notid) {
        $this->notificationid = $notid;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setImage($imageUrl) {
        $this->image = $imageUrl;
    }

    public function setPayload($data) {
        $this->data = $data;
    }

    public function setIsBackground($is_background) {
        $this->is_background = $is_background;
    }

    public function getPush() {
    $res = array();
    $res['data']['title'] = $this->title;
    $res['data']['is_background'] = $this->is_background;
    $res['data']['message'] = $this->message;
    $res['data']['image'] = $this->image;
    $res['data']['notificationid'] = $this->notificationid;
    $res['data']['payload'] = $this->data;
    $res['data']['timestamp'] = date('Y-m-d G:i:s');
    return $res;
}
    public function getPushNotification() {
        $res = array();
        $res['title'] = $this->title;
        $res['body'] = $this->message;
        return $res;
    }

    public function getPushIOS() {
        $res = array();

        $res['pic_url'] = $this->image;
        $res['notificationid'] = $this->notificationid;

        return $res;
    }
    public function getPushNotificationIOS() {
        $res = array();
        $res['title'] = $this->title;
        $res['body'] = $this->message;
        return $res;
    }

}
