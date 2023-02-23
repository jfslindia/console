<?php

/**
 * @author Ravi Tamada
 * @link URL time_sleep_until(timestamp)torial link
 */
class Firebase {
	public $brand_code;
	public function __construct($library_params){
		
		$this->brand_code = $library_params['brand_code'];
		
	}

    // sending push message to single user by firebase reg id
    public function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
           // 'notification' => $message,
        );
        return $this->sendPushNotification($fields);
    }



    // Sending message to a topic by topic name
    public function sendToTopic($to, $message) {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
           // 'notification' => $message,
        );
        return $this->sendPushNotification($fields);
    }
	
	

    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $message) {
   
$message['data']['click_action'] = "FLUTTER_NOTIFICATION_CLICK";
$notification = array(
    'title' => $message['data']['title'],
    'body' => $message['data']['message'],
    'image' => $message['data']['image']
);
       $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message['data'],
            'priority'=> 'high',
           'notification' => $notification,
        );
return $this->sendPushNotification($fields);
    }

     public function basicNotification($registration_ids, $message) {
        $fields = array(
            'registration_ids' => $registration_ids,
           // 'notification' => $message,
        );

        return $this->sendPushNotification($fields);

    }

    public function SendMultipleIOS($registration_ids, $notification,$data) {
   
$notification['click_action'] = "FLUTTER_NOTIFICATION_CLICK";
 $fields = array(
            'content_available'=>  true,
            'mutable_content'=>  true,
           
            'category'=> 'myNotificationCategory',
            'registration_ids' => $registration_ids,
            'notification' => $notification,
            'data' => $data
        );
           return $this->sendPushNotification($fields);

   }



    // function makes curl request to firebase servers
    private function sendPushNotification($fields) {
        
     require_once __DIR__ . '/config.php';


        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        if($this->brand_code =='PCT0000001'){

        	 $headers = array(
            'Authorization: key=' . FABRICSPA_FIREBASE_API_KEY,
            'Content-Type: application/json'
        );
        
        }
        else if($this->brand_code =='PCT0000014'){
        	$headers = array(
            'Authorization: key=' . CLICK2WASH_FIREBASE_API_KEY,
            'Content-Type: application/json'
        );
        }

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

   curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch); 
        return $result;
    }
}

?>