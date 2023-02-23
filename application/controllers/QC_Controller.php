<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 7/5/2019
 * Time: 9:40 AM
 */


if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class QC_Controller
 * @property QC_Model $QC_Model
 * @property generic $generic
 */
class QC_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        //loading URL helper
        $this->load->helper('url');


        /*Loading DCR_Model for Database Operations*/
        $this->load->model('QC_Model');

        //Loading the project settings
        $this->load->library('Settings/ProjectSettings');

        //Loading the Generic Functions
        $this->load->library('GenericFunctions/Generic');


        define('API_KEY', '&GU*^gdfjfjfjfj@#TUBBssJOTE#W#RYIMN__(*65$#$UHB)');

        $headers = getallheaders();

        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != API_KEY) {
                //Invalid request.
                $data = $this->generic->final_data('INVALID_API_KEY');
                $this->generic->json_output($data);
                exit(0);
            } else {
                //Valid case
            }
        } else {
            //No key is present in the header. This one is invalid request too.
            $data = $this->generic->final_data('NO_API_KEY');
            $this->generic->json_output($data);
            exit(0);
        }
    }

    public function index()
    {
        echo 'Quality Check Controller!';
    }

    /**
     * A public method for logging in collectors.
     *
     */
    public function login()
    {
        //Parsing the json request
        $body = $this->generic->json_input();

        $curl = curl_init();
          
          $api_url = "http://192.168.202.4/WhatsappAPI/api/JFSL/CheckIsValidLogIn";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode(array("Username" => $body['username'], "Password" => $body['password'])),
            CURLOPT_HTTPHEADER => array(

                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);

        $response = json_decode($response, TRUE);

        if ($response) {
            if ($response['isValid'] == 1) {
                $data = $this->generic->final_data('SUCCESS', 'Succesfully logged in');
                $data['user_id'] = (string)$response['UserId'];

            } else {
                $data = $this->generic->final_data('FAILED', 'Invalid user');
            }

        } else {
            $data = $this->generic->final_data('FAILED', 'Invalid user');
        }

        $this->generic->json_output($data);
    }

    /**
     *Getting garment details from tag id
     */
    public function get_garment_details()
    {

        //Parsing the json request
        $body = $this->generic->json_input();

        $tag_no = $body['TagNo'];
        $garment_details = $this->QC_Model->get_log_data($tag_no);

        if ($garment_details) {
            $data = $this->generic->final_data('DATA_FOUND');
            $data['garment_details'] = $garment_details;
        } else {
            $data = $this->generic->final_data('DATA_NOT_FOUND');
        }

        $this->generic->json_output($data);

    }

    /**
     *Public function to file upload.
     */
    public function file_upload()
    {

        if (is_uploaded_file($_FILES['uploaded_file']['tmp_name'])) {
            $uploads_dir = 'uploads/QC/';

            //Creating a folder based on current date. If a folder already exists, that would be the upload dir.
            $current_date = date('d-m-Y');
            if (file_exists($uploads_dir . $current_date) && is_dir($uploads_dir . $current_date)) {
                $uploads_dir = $uploads_dir . $current_date;
            } else {
                mkdir($uploads_dir . $current_date);
                $uploads_dir = $uploads_dir . $current_date;
            }


            $tmp_name = $_FILES['uploaded_file']['tmp_name'];
            $pic_name = $_FILES['uploaded_file']['name'];

            $upload = move_uploaded_file($tmp_name, $uploads_dir . '/' . $pic_name);
            if ($upload) {

                /*Saving the images to the image table*/
                $tag_no=$this->input->post('TagNo');
                $image_array['Image_URL'] = base_url() . $uploads_dir . '/' . $pic_name;
                $image_array['TagNo'] = $tag_no;
                $image_array['Source'] = 'QC App';
                $image_array['Date'] = date('Y-m-d H:i:s');

                //Save the uploaded image into QC_Images table.
                $save_images = $this->QC_Model->save_qc_images($image_array);


                $data = $this->generic->final_data('SUCCESS', 'Successfully uploaded file');
                $data['image'] = base_url() . $uploads_dir . '/' . $pic_name;



            } else {
                $data = $this->generic->final_data('FAILED', 'Failed to upload the file');

            }
        } else {
            $data = $this->generic->final_data('FAILED', 'Failed to upload the file');
        }

        $this->generic->json_output($data);
    }

    /**
     *Getting predefined reasons for QC.
     */
    public function get_reasons()
    {


        $reasons = $this->QC_Model->get_reasons_from_fabricare();

        $reasons_array = [];

        foreach ($reasons as $reason) {
            array_push($reasons_array, $reason['SpecialInstrunctionMSSValue']);
        }

        if(sizeof($reasons_array)>0){
            $data=$this->generic->final_data('DATA_FOUND');
            $data['reasons']=$reasons_array;
        }else{
            $data=$this->generic->final_data('DATA_NOT_FOUND');
        }


        $this->generic->json_output($data);

    }

    /**
     *Submitting the QC long by the QC user
     */
    public function save_log()
    {
        //Parsing the json request
        $body = $this->generic->json_input();

        //Getting the unique cipher for the customer.
        $cipher = $this->generate_cipher_for_customer($body['CustomerCode']);

        $link_for_the_customer = base_url() . 'sui/' . $cipher;

        //Calling the API for shortening the link.
        $url_shortner = $this->generic->call_url_shortner_api($link_for_the_customer);

        if ($url_shortner['status'] == 'success') {
            $customer_sui_short_link = $url_shortner['short_url'];
        } else {
            $customer_sui_short_link = NULL;
        }

        if (array_key_exists(0, $body['Image'])) {
            $image_0 = $body['Image'][0];
        } else {
            $image_0 = NULL;
        }

        if (array_key_exists(1, $body['Image'])) {
            $image_1 = $body['Image'][1];
        } else {
            $image_1 = NULL;
        }

        if (array_key_exists(2, $body['Image'])) {
            $image_2 = $body['Image'][2];
        } else {
            $image_2 = NULL;
        }

        if (array_key_exists('status', $body)) {
            $status=$body['status'];
        } else {
            //Default status,i.e. QCPending
            $status=3;
        }



        //Saving the shortened URL into table.
        $this->QC_Model->update_customer_unique_url($body['CustomerCode'], $customer_sui_short_link);

        //Update the QC status in the Fabricare.
        $fabricare_update_status = $this->QC_Model->update_qc_status_in_fabricare($body['TagNo'], $body['Reason'], $body['QCUserID'], $image_0, $image_1, $image_2, $customer_sui_short_link,$status);

        $this->write_sp_logs($fabricare_update_status);

        if ($fabricare_update_status['result']) {


            $data = $this->generic->final_data('DATA_SAVED');
            $data['link'] = $customer_sui_short_link;

        } else {
            $data = $this->generic->final_data('DATA_SAVE_FAILED');
        }

        $this->generic->json_output($data);
    }

    /**
     * A private method for creating cipher for the customer. If a cipher is already generated, return it back.
     * @param $customer_code
     * @return string -- Unique cipher code.
     */
    private function generate_cipher_for_customer($customer_code)
    {

        /*Creating unix time stamp*/
        $date = date('Y-m-d H:i:s');

        $fabricare_customer_details = $this->QC_Model->get_fabricare_customer_details($customer_code);


        if ($fabricare_customer_details['LastName'] != '') {
            $customer_full_name = $fabricare_customer_details['FirstName'] . ' ' . $fabricare_customer_details['LastName'];
        } else {
            $customer_full_name = $fabricare_customer_details['FirstName'];
        }

        //Checking the customer is already has a customer code or not.
        $qc_customer_details = $this->QC_Model->get_qc_customer_details($customer_code);

        if (!$qc_customer_details) {

            $cipher = $this->generic->generate_cipher($customer_code, 4, 4, 4, TRUE);

            //Saving the customer details into QC_Customer_Details table.
            $customer_details = array(
                'Date' => $date,
                'CustomerCode' => $customer_code,
                'Name' => $customer_full_name,
                'Email' => $fabricare_customer_details['EmailID1'],
                'MobileNumber' => $fabricare_customer_details['ContactNo'],
                'UniqueCode' => $cipher
            );

            $customer_details = $this->QC_Model->save_customer_details($customer_details);

        } else {
            $cipher = $qc_customer_details['UniqueCode'];
        }

        return $cipher;
    }

    /**
     *Private function for sending the gcmids.
     */
    private function send_gcmid($log)
    {

        $customer_id = $log['CustomerCode'];

        $fabricspa_gcmid = $this->QC_Model->get_fabricspa_gcmid($customer_id);


        $device = 'android'; //Hardcoded
        $to_sent[0] = $fabricspa_gcmid['fabricspa_android_gcmid'];
        $title = 'QC check';
        $brand = 'Fabricspa';
        $image_url = NULL;
        $message = 'Your garment needs attention! QC reason is ' . $log['Reason'];


        if ($brand == 'Fabricspa')
            $brand_code = 'PCT0000001';
        else if ($brand == 'Click2Wash')
            $brand_code = 'PCT0000014';
        else
            $brand_code = '';

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

    /**
     *Private function for sending the gcmids.
     */
    public function send_gcmid_test()
    {

        //Parsing the json request
        $body = $this->generic->json_input();


        $customer_id = 'C2W200000000088';

        $fabricspa_gcmid = $this->QC_Model->get_fabricspa_gcmid($customer_id);


        $device = 'android'; //Hardcoded
        $to_sent[0] = $fabricspa_gcmid['fabricspa_android_gcmid'];
        $title = 'QC check';
        $brand = 'Fabricspa';
        $image_url = NULL;
        $message = 'Your garment needs attention! QC reason is Stain';


        if ($brand == 'Fabricspa')
            $brand_code = 'PCT0000001';
        else if ($brand == 'Click2Wash')
            $brand_code = 'PCT0000014';
        else
            $brand_code = '';

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
    private function send_email($brand, $message)
    {

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

    /**
     * //Writing the stored procedure log in a file.
     * @param $result
     */
    private function write_sp_logs($result)
    {
        //Writing the stored procedure log in a file.
        $log_day = date('d-M-Y');
        $log_date = date('d-M-Y H:i:s');
        $json_request = json_encode($_POST);

        $request_uri = $_SERVER['REQUEST_URI'];
        $request_time = $_SERVER['REQUEST_TIME'];
        $txt = 'date: ' . $log_date . ', json_request: ' . $json_request . ', request URI: ' . $request_uri . ', request time: ' . $request_time . ',result: ' . json_encode($result) . PHP_EOL;
        $underline = '--------------------------------------------------------------------------------------------------------------------------------------------------------';
        $txt = $txt . $underline;
        $log_file = file_put_contents('query_logs/' . $log_day . '_query.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

}