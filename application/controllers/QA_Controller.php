<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 7/5/2019
 * Time: 9:40 AM
 */


if (!defined('BASEPATH')) exit('No direct script access allowed');

class QA_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        //loading URL helper
        $this->load->helper('url');


        /*Loading DCR_Model for Database Operations*/
        $this->load->model('QA_Model');

        $is_testing = $this->config->item('is_testing');
        define("IS_TESTING", $is_testing);

        //Current environment. This can be UAT/Live/QA.
        $environment = $this->config->item('current_environment');

        define("CURRENT_ENVIRONMENT", $environment);

        //Defining the DB IP address based on current environment.
        if (CURRENT_ENVIRONMENT == 'QA') {
            $db_server_ip = '192.168.5.28';
        } else {
            $db_server_ip = '192.168.5.78';
        }


        //Setting the Database for Live/UAT.
        if (IS_TESTING) {
            $server_db = 'JFSL_UAT';
            $local_db = 'Mobile_JFSL_UAT';
        } else {
            $server_db = 'JFSL';
            $local_db = 'Mobile_JFSL';
        }

        //Defining the global variables
        define("LOCAL_DB", $local_db);
        define("SERVER_DB", $server_db);


        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $body = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($body, TRUE);
        $request = $this->input->post();


        //                            $this->output->enable_profiler(TRUE);

        if (($body['api_key'] != '&GU*^gdfjfjfjfj@#TUBBssJOTE#W#RYIMN__(*65$#$UHB)') && ($request['api_key'] != '&GU*^gdfjfjfjfj@#TUBBssJOTE#W#RYIMN__(*65$#$UHB)')) {
            //                show_404();
            $userdata = array(
                'status' => 'failed',
                'status_code' => '0',
                'message' => 'unauthorised access',
            );


            $userdata = json_encode($userdata);

            $this->output
                ->set_content_type('application/json')
                ->set_output($userdata)
                ->_display();

            //                        echo $userdata;
            exit;
        }
    }

    public function index()
    {
        echo 'QA Controller!';
    }

    /**
     * A public method for logging in collectors.
     *
     */
    public function login()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        /*Getting the user details from the QC table. Both QA & QC uses the same QC users table.*/

        $user_details = $this->QA_Model->get_user_details($body['MobileNumber'], $body['Password']);

        if ($user_details) {
            $userdata = array('status' => 'success', 'status_code' => '1', 'message' => 'Succesfully logged in', 'user' => $user_details);
        } else {
            $userdata = array('status' => 'failed', 'status_code' => '4', 'message' => 'Invalid user...');
        }

        $userdata = json_encode($userdata);

        $this->output
            ->set_content_type('application/json')
            ->set_output($userdata);
    }

    /**
     *Getting garment details from tag id
     */
    public function get_garment_details(){

        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        $tag_no=$body['TagNo'];
        $garment_details=$this->QA_Model->get_garment_details($tag_no);

        if ($garment_details) {
            $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Succesfully fetched the result', 'garment_details' => $garment_details);
        } else {
            $data = array('status' => 'failed', 'status_code' => '0', 'message' => 'Failed to retrieve details...');
        }

        $data = json_encode($data);

        $this->output
            ->set_content_type('application/json')
            ->set_output($data);

    }

    /**
     *Public function to file upload.
     */
    public function file_upload()
    {

        if (is_uploaded_file($_FILES['uploaded_file']['tmp_name'])) {
            $uploads_dir = 'uploads/QC/';
            $tmp_name = $_FILES['uploaded_file']['tmp_name'];
            $pic_name = $_FILES['uploaded_file']['name'];
            $date = date('Y_m_d_H-i-s');
            $upload = move_uploaded_file($tmp_name, $uploads_dir . $date . '_' . $pic_name);
            if ($upload) {
                $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully uploaded file', 'image' => $date . '_' . $pic_name);
            } else {
                $data = array('status' => 'failed', 'status_code' => '0', 'message' => 'Failed to upload the file');
            }
        } else {
            $data = array('status' => 'failed', 'status_code' => '0', 'message' => 'Failed to upload the file');
        }
        echo json_encode($data);

    }


    /**
     *Submitting the QA long by the QC user
     */
    public function save_log(){
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        /*Creating unix time stamp*/
        $date = date('Y-m-d H:i:s');

        $user=$this->QA_Model->get_user_details_from_id($body['UserId']);

        /*Creating an array of the log details that needs to be inserted into DB.*/
        $log=array(
            'CreatedDate'=>$date,
            'TagNo'=>$body['TagNo'],
            'Reason'=>$body['Reason'],
            'FinishedBy'=>$body['FinishedBy'],
            'SubmittedBy'=>$user['Name']

        );

        $save=$this->QA_Model->save_log($log);

        if($save){

            $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully saved the log');
        }else{
            $data = array('status' => 'failed', 'status_code' => '0', 'message' => 'Failed to save the log');
        }

        echo json_encode($data);


    }

  /**
     *Getting the QA finished by users and reasons for select box
     */
    public function get_essentials(){

        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        $essentials=$this->QA_Model->get_qa_essentials();


        $data = array(


            'status'=>'success',
            'finished_by'=>$essentials['finished_by'],
            'reasons'=>$essentials['reasons']

        );

        echo json_encode($data);

    }

    /**
     *Private function for sending the gcmids.
     */
    private function send_gcmid($log){

        $customer_id=$log['CustomerCode'];

        $fabricspa_gcmid=$this->QA_Model->get_fabricspa_gcmid($customer_id);


        $device = 'android'; //Hardcoded
        $to_sent[0]=$fabricspa_gcmid['fabricspa_android_gcmid'];
        $title = 'QC check';
        $brand = 'Fabricspa';
        $image_url = base_url().'uploads/qc/'.$log['Image'];
        $message = 'Your garment needs attention! QC reason is '.$log['Reason'];


        if ($brand == 'Fabricspa')
            $brand_code = 'PCT0000001';
        else if ($brand == 'Click2Wash')
            $brand_code = 'PCT0000014';
        else
            $brand_code='';

        $library_params = array('brand_code' => $brand_code);
        $this->load->library('push_notification/firebase', $library_params);
        $this->load->library('push_notification/push');
        // print_r($to_sent);

        $total_users = sizeof($to_sent);


        $firebase = new Firebase($library_params);
        //$firebase = $this->firebase;
        $push = new Push();

        // optional payload
        $payload = array();
        $payload['sound'] = 'default';
        $payload['brand_code'] = $brand_code;
        $payload['type'] = 'qc';

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


      /* if ($response) {

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

        echo json_encode($data);*/
    }


    /*A private function for sending SMS.*/

    /**
     * @param $numbers : Array of phone numbers generated
     * @param $message : Message to send
     *
     * @param $senderId : SenderID for SMS library eg. FABSPA
     * @param $customer_care : Customer care number as per Bangalore or Mumbai
     * @return bool
     */
    private function send_sms($numbers, $message, $senderId, $customer_care)
    {

        //Your authentication key
        $authKey = "104533AtcaaQuVqC56b98ff3";


        //Your message to send, Add URL encoding here.

        $message = urlencode($message . " For help please call" . $customer_care);

        //Define route
        $route = "4";

        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $numbers,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route
        );

        //API URL
        $url = "https://control.msg91.com/api/sendhttp.php";

        // init the resource
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //get response
        $output = curl_exec($ch);
        if (!$output) {
            $ret = FALSE;
            rigger_error(curl_error($ch));
        } else {
            $ret = TRUE;
        }
        curl_close($ch);

        /*Returning status of sending message*/
        return $ret;
    }



    /**
     *Private method for sending email
     */
    private function send_email($brand,$message){

        //Loading email library
        $this->load->library('email', array('mailtype' => 'html'));

        //Email API configurations
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.mandrillapp.com';
        $config['smtp_user'] = 'web@snoways.in';
        $config['smtp_pass'] = 'PBxlel-v1KG5S5zED_ddWA';
        $config['smtp_port'] = 587;

        //Setting Email configurations
        $this->email->initialize($config);


        $this->email->from('no-reply@' . strtolower($brand) . '.com', $brand);


            $this->email->subject('QC Approval' . $brand);

        //Creating message
        $message_to_send = '<p>' . $message . '</P>';

        //Configuring message
        $this->email->message($message_to_send);

        //Constructing email adresses of users in a string
        $email_addresses = '';

       $this->email->to('CUSTOMER_EMAIL_ID');

        $mail_send_status = $this->email->send();
    }

}