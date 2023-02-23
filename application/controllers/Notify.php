<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');


class Notify extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Notify_Model');
    }

    public function index(){

        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);


        $customer_id=$body['customer_id'];
        $message=$body['message'];

        if(array_key_exists('title', $body)){
            $title=$body['title'];
        }else{
            $title='Alert';
        }


        if(array_key_exists('api_key',$body)){
            $api_key=$body['api_key'];
            if($api_key=='OAHDOn#rX9&@#MXL:lEe@!'){
                $get_gcmids=$this->Notify_Model->get_gcmids_of_a_customer($customer_id);


                if($get_gcmids['fabricspa_android_gcmid']){
                    $this->send_gcmids('android',array(0=>$get_gcmids['fabricspa_android_gcmid']),$title,'Fabricspa','',$message);
                }

                if($get_gcmids['fabricspa_ios_gcmid']){
                    $this->send_gcmids('ios',array(0=>$get_gcmids['fabricspa_ios_gcmid']),$title,'Fabricspa','',$message);
                }


            }else{
                echo json_encode(array('status'=>'Invalid key'));
            }
        }
        else{
            echo json_encode(array('status'=>'Key is not given'));
        }

    }


    /**
     *Send the notification to the corresponding GCMIDs
     */
    public function send_gcmids($device,$to_sent,$title,$brand,$image_url,$message)
    {


        if ($brand == 'Fabricspa')
            $brand_code = 'PCT0000001';
        else if ($brand == 'Click2Wash')
            $brand_code = 'PCT0000014';
        else
            $brand_code=NULL;

        $library_params = array('brand_code' => $brand_code);
        $this->load->library('push_notification/firebase', $library_params);
        $this->load->library('push_notification/push');


        $total_users = sizeof($to_sent);


        $firebase = new Firebase($library_params);
        //$firebase = $this->firebase;
        $push = new Push();

        // optional payload
        $payload = array();
        $payload['sound'] = 'default';
        $payload['brand_code'] = $brand_code;

        // notification title
        //$title = 'Test';

        // notification message
        //$message =  'Test Test';

        // push type - single user / topic
        $push_type = 'multiple';

        // whether to include to image or not
        if (isset($image_url))
            $include_image = TRUE;
        else
            $include_image = FALSE;


        $push->setTitle($title);
        $push->setMessage($message);
        if ($include_image) {
            $push->setImage($image_url);
        } else {
            $push->setImage('');
        }
        $push->setIsBackground(FALSE);
        $push->setPayload($payload);

        $json = '';
        $response = '';
        $regIds = array();

        //Getting GCMIDs from Pref file or database. If pref GCMID list are empty, then select from DB.
        for ($i = 0; $i < $total_users; $i++) {
            if ($to_sent[$i] != '' || !empty($to_sent[$i]))
                array_push($regIds, $to_sent[$i]);
        }


        //print_r($to_sent);

        if ($device == 'android') {
            if ($push_type == 'topic') {
                $json = $push->getPush();
                $response = $firebase->sendToTopic('global', $json);
            } else if ($push_type == 'multiple') {
                $json = $push->getPush();
                $response = $firebase->sendMultiple($regIds, $json);
            }
        } else if ($device == 'ios') {
            if ($push_type == 'topic') {
                $json = $push->getPush();

                $response = $firebase->sendToTopic('global', $json);
            } else if ($push_type == 'multiple') {
                //$json = $push->getPush();
                $data = $push->getPushIOS();
                $response = $firebase->sendMultipleIOS($regIds, $push->getPushNotificationIOS(), $data);
            }
        } else {
            $response = FALSE;
        }


        if ($response) {

            $data = array(
                'status' => 'success',
                'response' => $response,
                'to_sent' => $to_sent,
                'size' => $total_users
            );
        } else {
            $data = array(
                'status' => 'failed',
                'response' => $response
            );
        }

        echo json_encode($data);


    }


}