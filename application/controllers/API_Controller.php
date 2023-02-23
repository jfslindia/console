<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class API_Controller
 * @property API_Model $API_Model
 * @property generic $generic
 */
class API_Controller extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */


    function __construct()
    {

        parent::__construct();


        //loading URL helper
        $this->load->helper('url');

        /*Loading DCR_Model for Database Operations*/
        $this->load->model('API_Model');

        //Loading the project settings
        $this->load->library('Settings/ProjectSettings');

        //Loading the Generic Functions
        $this->load->library('GenericFunctions/Generic');


        define('QC_LINK_API_KEY', 'a_0_=+Se3de25fD#@$Fcd+=');
        define('WHATSAPP_OPTOUT_API_KEY', 'ha7Qoh73jxIO3OUWxi2');
        define('REPORTS_API_KEY', 'HOho%qeio*Y2Xei-iU2_=1');
        define('QC_IMAGE_UPLOAD_KEY', 'HOIc^23@ioe3423IU)3nx24');
        define('NOTIFY_API_KEY', 'OAHDOn#rX9&@#MXL:lEe@!');
        define('CHECK_WITH_ZAAKPAY_KEY', 'ijijdEFR3I(&)Y23mkxzm3WDI03+');
        define('RATE_CARD_KEY', 'L2y(&34xPO-0+34joxW');
        define('BLOG_KEY', 'JPI%#Je732x^Kj4Lw025');
        define('GET_NEAREST_STORE_API_KEY', 'hohfo+=OF34))@xJXIS232)@');
        define('PAYMENT_LINK_GENERATION_KEY', 'AOSI*^^&%#ASHOIshCAHO34R23');


    }

    public function index()
    {

        $data = $this->generic->final_data('INVALID_REQUEST');
        $this->generic->json_output($data);
        exit(0);
    }

    /**
     *Returning the QC approval/rejection link
     */
    public function get_customer_unique_link()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        $headers = getallheaders();

        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != QC_LINK_API_KEY) {
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

        if (array_key_exists('CustomerCode', $body)) {
            $customer_code = $body['CustomerCode'];
        } else {
            $data = $this->generic->final_data('NO_KEY_FOUND', 'CustomerCode');
            $this->generic->json_output($data);
            exit(0);
        }
        $code = $this->API_Model->get_qc_customer_link($body['CustomerCode']);

        if (CURRENT_SERVER_TYPE == 'local') {
            $link_base = CURRENT_SERVER_IP . 'jfsl/sui/';
        } else {
            if (CURRENT_ENVIRONMENT == 'LIVE') {
                $link_base = CURRENT_SERVER_IP . "jfsl/sui/";
            } else if (CURRENT_ENVIRONMENT == 'UAT') {
                $link_base = CURRENT_SERVER_IP . "jfsl_uat/sui/";
            } else if (CURRENT_ENVIRONMENT == 'QA') {
                $link_base = CURRENT_SERVER_IP . "jfsl_qa/sui/";
            } else {
                $link_base = '';
            }
        }

        $link = $link_base . $code['UniqueCode'];

        if ($code['UniqueCode']) {

            //Calling the API for shortening the link.
            $result = $this->generic->call_url_shortner_api($link);


            if ($result['status'] == 'success') {
                //Link is generated. Add the link to the final data.
                $data = $this->generic->final_data('DATA_FOUND');
                $data['link'] = $result['short_url'];
            } else {
                $data = $this->generic->final_data('DATA_NOT_FOUND');
            }

        } else {

            //Link is generated. Return back the result.
            $data = $this->generic->final_data('DATA_NOT_FOUND');
        }


        $this->generic->json_output($data);

    }

    /**
     *API for returning unique whatsapp opt out link for a customer.
     */
    public function get_whatspapp_opt_out_link()
    {

        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        $headers = getallheaders();


        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != WHATSAPP_OPTOUT_API_KEY) {
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

        if (array_key_exists('CustomerCode', $body)) {

            $customer_id = $body['CustomerCode'];
        } else {

            $data = $this->generic->final_data('NO_KEY_FOUND', 'CustomerCode');
            $this->generic->json_output($data);
            exit(0);
        }


        //First, check whether a link is already generated for this customer or not. If the link is already generated, return. Else create a link.
        $link = $this->API_Model->get_whatspapp_opt_out_link($customer_id);


        if ($link) {

            if (CURRENT_SERVER_TYPE == 'local') {
                $link_base = CURRENT_SERVER_IP . 'jfsl/WAO/';
            } else {
                if (CURRENT_ENVIRONMENT == 'LIVE') {
                    $link_base = CURRENT_SERVER_IP . "jfsl/WAO/";
                } else if (CURRENT_ENVIRONMENT == 'UAT') {
                    $link_base = CURRENT_SERVER_IP . "jfsl_uat/WAO/";
                } else if (CURRENT_ENVIRONMENT == 'QA') {
                    $link_base = CURRENT_SERVER_IP . "jfsl_qa/WAO/";
                } else {
                    $link_base = '';
                }
            }


            $link = $link_base . $link['UniqueCode'];

            //Calling the API for shortening the link.
            $result = $this->generic->call_url_shortner_api($link);


            if ($result['status'] == 'success') {
                //Link is generated. Add the link to the final data.
                $data = $this->generic->final_data('DATA_FOUND');
                $data['link'] = $result['short_url'];
            } else {
                $data = $this->generic->final_data('DATA_NOT_FOUND');
            }

        } else {

            //Here no link found for that customer. So create a unique link and send it back.
            // Edited by Manju to avoid duplicate cipher problem from 20 to 21
            $unique_code = $this->generic->generate_cipher($customer_id, 4, 4, 21, TRUE);

            //Saving the newly generating link to the table.
            $date = date('Y-m-d H:i:s');

            $new_entry = array('Date' => $date, 'CustomerCode' => $customer_id, 'UniqueCode' => $unique_code);

            $this->API_Model->save_whatspapp_opt_out_link($new_entry);

            if (CURRENT_SERVER_TYPE == 'local') {
                $link_base = CURRENT_SERVER_IP . 'jfsl/WAO/';
            } else {
                if (CURRENT_ENVIRONMENT == 'LIVE') {
                    $link_base = CURRENT_SERVER_IP . "jfsl/WAO/";
                } else if (CURRENT_ENVIRONMENT == 'UAT') {
                    $link_base = CURRENT_SERVER_IP . "jfsl_uat/WAO/";
                } else if (CURRENT_ENVIRONMENT == 'QA') {
                    $link_base = CURRENT_SERVER_IP . "jfsl_qa/WAO/";
                } else {
                    $link_base = '';
                }
            }


            $link = $link_base . $unique_code;

            //Calling the API for shortening the link.
            $result = $this->generic->call_url_shortner_api($link);

            if ($result['status'] == 'success') {
                //Link is generated. Add the link to the final data.
                $data = $this->generic->final_data('DATA_FOUND');
                $data['link'] = $result['short_url'];
            } else {
                $data = $this->generic->final_data('DATA_NOT_FOUND');
            }

        }

        $this->generic->json_output($data);
        exit(0);
    }

    /**
     *API for downloading the EGRN/Invoice documents
     */
    public function get_report()
    {

        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);


        $headers = getallheaders();

        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != REPORTS_API_KEY) {
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


        $report_type = strtolower($body['report_type']);
        $document_no = $body['document_no'];


        if ($report_type == 'egrn') {
            $report_type = 'EGRN';
        } else if ($report_type == 'invoice') {
            $report_type = 'Invoice';
        } else {
            $report_type = '';
        }
        /*If the order number is contain - and DC number, split that value and pass it for sp*/
        if (strpos($document_no, '-')) {

            $order = explode('-', $document_no);
            $document_no = $order[0];

        }


        //Creating the hash based on document number.
        $cipher = $this->generic->generate_hash($document_no, 10);


        //The proposed file URL will be:
        $file_url = base_url() . 'api/report/' . $cipher . '/' . $report_type . '/' . $document_no;

        //Generating a short link for the target file.
        //Calling the API for shortening the link.
        $result = $this->generic->call_url_shortner_api($file_url);

        if ($result['status'] == 'success') {
            //Link is generated. Add the link to the final data.
            $data = $this->generic->final_data('DATA_FOUND');
            $data['link'] = $result['short_url'];
        } else {
            $data = $this->generic->final_data('DATA_NOT_FOUND');
        }

        $this->generic->json_output($data);
        exit(0);
    }

    /**
     * Showing the PDF reports
     * @param $hash -- hash code generated based on document/report number.
     * @param $report_type -- EGRN/Invoice
     * @param $report_number -- Document No
     */
    public
    function report($hash = FALSE, $report_type = FALSE, $report_number = FALSE)
    {

        //Creating the hash based on document number.
        $generated_hash = $this->generic->generate_hash($report_number, 10);

        if ($generated_hash != $hash) {

            //Here, generated cipher and given cipher is different. Invalid request.
            $final_data = $this->generic->final_data('INVALID');
            $this->generic->json_output($final_data);
        }


        if ($report_type && $report_number) {

            if (strtoupper($report_type) != 'EGRN' && strtoupper($report_type) != 'INVOICE') {
                $final_data = $this->generic->final_data('INVALID');
                $this->generic->json_output($final_data);
            }

            //Generating the PDF file on customer's request.
            $target_file = $this->generate_pdf_report($report_type, $report_number);


            if (is_file($target_file)) {

                // Header content type
                header("Content-type: application/pdf");

                header("Content-Length: " . filesize($target_file));

                header('Content-Disposition: inline; filename="' . $report_number . '.pdf"');

                // Send the file to the browser.
                readfile($target_file);

                //Deleting the newly created PDF
                unlink($target_file);

            } else {

                $data = array('report_type' => $report_type, 'report_number' => $report_number);
                $this->load->view('404/404', $data);
            }
        } else {
            $final_data = $this->generic->final_data('INVALID');
            $this->generic->json_output($final_data);
        }


    }


    /**
     * Generating the PDF report at customer's request.
     * @param $report_type
     * @param $document_no
     * @return null|string -- Target file. Path file address.
     */
    private
    function generate_pdf_report($report_type, $document_no)
    {

        if (strtolower($report_type) == 'egrn') {
            $report_type = 'EGRN';
        } else if (strtolower($report_type) == 'invoice') {
            $report_type = 'Invoice';
        } else {
            $report_type = '';
        }

        if (strpos($document_no, '-')) {

            $order = explode('-', $document_no);
            $document_no = $order[0];

        }

        $data = array("reportName" => $report_type, "DocumentNo" => $document_no);

        $data_string = json_encode($data);

        $file_write_status = FALSE;

        $target_file = 'pdf_reports/' . $report_type . '/' . $document_no . '.pdf';

        //Seperate API urls for testing and live environments.
        if (IS_TESTING) {
            if (CURRENT_ENVIRONMENT == 'QA') {
                $post_url = 'http://192.168.5.31/GETMobileReport_QA/api/GenerateReport';
            } else {
                $post_url = 'http://192.168.202.7:8080/GetMobileReports/api/GenerateReport';
            }
        } else {

            if (CURRENT_ENVIRONMENT == 'LIVE') {
                $post_url = 'http://192.168.5.31/GETMobileReport_Live/api/GenerateReport';
            } else {
                //Cloud
                $post_url = 'http://15.207.206.115/GetMobileReports/api/GenerateReport';
            }

        }


        $ch = curl_init($post_url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );

        $result = curl_exec($ch);

        $message = json_decode($result, true);

        if ($message['Message'] != 'An error has occurred.') {

            $fp = fopen($target_file, 'w+');
            $file_write_status = fwrite($fp, $result);

        } else {
            $file_write_status = FALSE;
        }

        curl_close($ch);

        if (file_exists($target_file)) {

            $file_url = $target_file;
        } else {
            $file_url = NULL;
        }

        return $file_url;

    }

    /**
     *Public method for uploading QC images.
     *
     * Note: Developed for Fabricare side upload in 78 server image upload.
     */
    public
    function upload_qc_image()
    {


        $headers = getallheaders();

        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != QC_IMAGE_UPLOAD_KEY) {
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

        /*Reading data from the body of the POST request,
       data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);


        //If no tag no is present, send the no key found output result.
        if (!array_key_exists('tag_no', $body)) {
            $data = $this->generic->final_data('NO_KEY_FOUND', 'tag_no');
            $this->generic->json_output($data);
            exit(0);
        }

        //If no tag no is present, send the no key found output result.
        if (!array_key_exists('customer_code', $body)) {
            $data = $this->generic->final_data('NO_KEY_FOUND', 'customer_code');
            $this->generic->json_output($data);
            exit(0);
        }

        $tag_no = $body['tag_no'];
        $customer_code = $body['customer_code'];


        //If there's no image_1 is provided, throw NO_KEY_FOUND error.
        if (array_key_exists('image_1', $body)) {

            $base64_decoded = base64_decode($body['image_1']);

            $uploads_dir = 'uploads/QC/';

            //Creating a folder based on current date. If a folder already exists, that would be the upload dir.
            $current_date = date('d-m-Y');
            if (file_exists($uploads_dir . $current_date) && is_dir($uploads_dir . $current_date)) {
                $uploads_dir = $uploads_dir . $current_date;
            } else {
                mkdir($uploads_dir . $current_date);
                $uploads_dir = $uploads_dir . $current_date;
            }

            //A 8 character hash based on decoded Base64 string.
            $pic_name = substr(hash_hmac('sha256', $base64_decoded, '$HashKey!'), 0, 8);

            $pic_name = $pic_name . '.jpg';


            $upload = file_put_contents($uploads_dir . '/' . $pic_name, $base64_decoded);

            if ($upload) {

                $remote_image = base_url() . $uploads_dir . '/' . $pic_name;
                $remote_image = str_replace(' ', '', $remote_image);

                //Saving the tag no and the image link to the QC_Images table.
                $save_image = $this->API_Model->save_image($tag_no, $remote_image);

                $data = $this->generic->final_data('DATA_SAVED');

                $data['image_1'] = $remote_image;

            } else {
                $data = $this->generic->final_data('DATA_SAVE_FAILED');
            }

        } else {
            $data = $this->generic->final_data('NO_KEY_FOUND', 'image_1');
            $this->generic->json_output($data);
        }


        //If there's no image_2 & image_3 is provided, No need to throw NO_KEY_FOUND error.
        for ($i = 2; $i <= 3; $i++) {


            if (array_key_exists('image_' . $i, $body)) {

                if ($body['image_' . $i] != '') {

                    $base64_decoded = base64_decode($body['image_' . $i]);

                    $uploads_dir = 'uploads/QC/';

                    //Creating a folder based on current date. If a folder already exists, that would be the upload dir.
                    $current_date = date('d-m-Y');
                    if (file_exists($uploads_dir . $current_date) && is_dir($uploads_dir . $current_date)) {
                        $uploads_dir = $uploads_dir . $current_date;
                    } else {
                        mkdir($uploads_dir . $current_date);
                        $uploads_dir = $uploads_dir . $current_date;
                    }

                    //A 8 character hash based on decoded Base64 string.
                    $pic_name = substr(hash_hmac('sha256', $base64_decoded, '$HashKey!'), 0, 8);

                    $pic_name = $pic_name . '.jpg';

                    $upload = file_put_contents($uploads_dir . '/' . $pic_name, $base64_decoded);


                    $remote_image = base_url() . $uploads_dir . '/' . $pic_name;
                    $remote_image = str_replace(' ', '', $remote_image);

                    //Saving the tag no and the image link to the QC_Images table.
                    $save_image = $this->API_Model->save_image($tag_no, $remote_image);

                    $data['image_' . $i] = $remote_image;
                }

            }

        }

        //Finally attaching the SUI URL of the customer.

        $cipher = $this->generate_cipher_for_customer($customer_code);

        $link_for_the_customer = base_url() . 'sui/' . $cipher;

        //Calling the API for shortening the link.
        $url_shortner = $this->generic->call_url_shortner_api($link_for_the_customer);

        if ($url_shortner['status'] == 'success') {
            $customer_sui_short_link = $url_shortner['short_url'];
        } else {
            $customer_sui_short_link = NULL;
        }

        //Saving the shortened URL into table.
        $this->API_Model->update_customer_unique_url($customer_code, $customer_sui_short_link);

        //Attaching the result to the final data.
        $data['customer_unique_url'] = $customer_sui_short_link;

        $this->generic->json_output($data);

    }

    /**
     * A private method for creating cipher for the customer. If a cipher is already generated, return it back.
     * @param $customer_code
     * @return string -- Unique cipher code.
     */
    private
    function generate_cipher_for_customer($customer_code)
    {

        /*Creating unix time stamp*/
        $date = date('Y-m-d H:i:s');

        $fabricare_customer_details = $this->API_Model->get_fabricare_customer_details($customer_code);


        if ($fabricare_customer_details['LastName'] != '') {
            $customer_full_name = $fabricare_customer_details['FirstName'] . ' ' . $fabricare_customer_details['LastName'];
        } else {
            $customer_full_name = $fabricare_customer_details['FirstName'];
        }

        //Checking the customer is already has a customer code or not.
        $qc_customer_details = $this->API_Model->get_qc_customer_details($customer_code);

        if (!$qc_customer_details) {

// Edited by Manju to avoid duplicate cipher problem from 4 to 5
            $cipher = $this->generic->generate_cipher($customer_code, 4, 4, 5, TRUE);

            //Saving the customer details into QC_Customer_Details table.
            $customer_details = array(
                'Date' => $date,
                'CustomerCode' => $customer_code,
                'Name' => $customer_full_name,
                'Email' => $fabricare_customer_details['EmailID1'],
                'MobileNumber' => $fabricare_customer_details['ContactNo'],
                'UniqueCode' => $cipher
            );

            $customer_details = $this->API_Model->save_customer_details($customer_details);

        } else {
            $cipher = $qc_customer_details['UniqueCode'];
        }

        return $cipher;
    }

    /**
     *Getting the publically accessible image links for Fabricare.
     */
    public
    function get_image_links()
    {


        $headers = getallheaders();

        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != QC_IMAGE_UPLOAD_KEY) {
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


        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        $tag_no = $body['tag_no'];

        //If no tag no is present, send the no key found output result.
        if (!$tag_no) {
            $data = $this->generic->final_data('NO_KEY_FOUND', 'tag_no');
            $this->generic->json_output($data);
            exit(0);
        } else {
            //Getting the links from the table.
            $image_links = $this->API_Model->get_image_links($tag_no);
            if ($image_links) {
                $data = $this->generic->final_data('DATA_FOUND');

                $i = 1;
                foreach ($image_links as $image_link) {
                    $data['image_' . $i] = $image_link['Image_URL'];
                    $i++;
                }

            } else {
                $data = $this->generic->final_data('DATA_NOT_FOUND');
            }
        }

        $this->generic->json_output($data);

    }


//A private function for calculating the quality of the target image based on width of the uploading file.
    private
    function get_quality($width)
    {

        if ($width >= 1200) {
            $quality = 25;
        } else if ($width >= 800 && $width <= 1199) {
            $quality = 35;
        } else {
            $quality = 50;
        }

        return $quality;
    }

    /**
     *Public function to send mobile notification
     */
    public
    function notify()
    {


        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);


        $headers = getallheaders();

        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != NOTIFY_API_KEY) {
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


        //If no tag no is present, send the no key found output result.
        if (!array_key_exists('mobile_number', $body)) {
            $data = $this->generic->final_data('NO_KEY_FOUND', 'mobile_number');
            $this->generic->json_output($data);
            exit(0);
        }

        $mobile_number = $body['mobile_number'];

        //If no tag no is present, send the no key found output result.
        if (!array_key_exists('message', $body)) {
            $data = $this->generic->final_data('NO_KEY_FOUND', 'message');
            $this->generic->json_output($data);
            exit(0);
        }

        $message = $body['message'];

        if (array_key_exists('title', $body)) {
            $title = $body['title'];
        } else {
            $title = 'Alert';
        }


        $get_gcmids = $this->API_Model->get_gcmids_of_a_customer($mobile_number);


        if ($get_gcmids['fabricspa_android_gcmid']) {
            $this->send_gcmids('android', array(0 => $get_gcmids['fabricspa_android_gcmid']), $title, 'Fabricspa', '', $message);
        }

        if ($get_gcmids['fabricspa_ios_gcmid']) {
            $this->send_gcmids('ios', array(0 => $get_gcmids['fabricspa_ios_gcmid']), $title, 'Fabricspa', '', $message);
        }


    }

    /**
     *Send the notification to the corresponding GCMIDs
     */
    private
    function send_gcmids($device, $to_sent, $title, $brand, $image_url, $message)
    {


        if ($brand == 'Fabricspa')
            $brand_code = 'PCT0000001';
        else if ($brand == 'Click2Wash')
            $brand_code = 'PCT0000014';
        else
            $brand_code = NULL;

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

            $data = $this->generic->final_data('SUCCESS', NULL, $response);
            $data['to_sent'] = $to_sent;
            $data['size'] = $total_users;

        } else {
            $data = $this->generic->final_data('FAILED', NULL, $response);
        }

        $this->generic->json_output($data);
    }

    /**
     *Checking the all manual verify transactions with Zaakpay in a loop by giving limit and offset.
     */
    public
    function check_with_zaakpay()
    {

        //Json Request
        $request = $this->generic->json_input();

        $headers = getallheaders();

        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != CHECK_WITH_ZAAKPAY_KEY) {
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

        $limit = $request['limit'];
        $offset = $request['offset'];


        //Loading the Payment Gateway settings
        $this->load->library('Settings/PaymentGatewaySettings');


        //Loading the mobikwik checksum library
        $this->load->library('mobikwik/checksum.php');

        $transactions = $this->API_Model->get_transactions_with_manual_verify($limit, $offset);

        if (sizeof($transactions) > 0) {

            //Looping through the transactions
            for ($i = 0; $i < sizeof($transactions); $i++) {

                $secret = SECRET_KEY;
                $mid = MERCHANT_IDENTIFIER;

                $all = "'" . $mid . "''" . $transactions[$i]['PaymentId'] . "''0''Check Status'";

                $checksum = Checksum::calculateChecksum($secret, $all);

                $post_data_str = "checksum=" . $checksum . "&merchantIdentifier=" . $mid . "&orderId=" . $transactions[$i]['PaymentId'] . "&mode=0&submitButton=Check Status";


                if (IS_TESTING) {
                    $url = 'http://zaakpaystaging.centralindia.cloudapp.azure.com:8080/api/checktransaction/V8';
                } else {
                    $url = "https://api.zaakpay.com/checktransaction";
                }
                // init the resource
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_POST => TRUE,
                    CURLOPT_POSTFIELDS => $post_data_str,

                ));


                //Ignore SSL certificate verification
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                //get response
                $output = curl_exec($ch);
                curl_close($ch);

                $formatted_1 = str_replace($transactions[$i]['PaymentId'], '', (str_replace($mid, "", (string)$output)));
                $without_checksum = str_replace($checksum, '', $formatted_1);

                //Getting the predefined Zaakpay status descriptions
                //$status_descriptions = ZAAKPAY_STATUS_DESCRIPTIONS;
                $status_descriptions = ['Fraud Detected', 'MerchantIdentifier field missing or blank', 'MerchantIdentifier not valid', 'OrderId field missing or blank', 'Mode field missing or blank', 'Mode received with request was not valid', 'Checksum received with request is not equal to what we calculated', 'Merchant Data not complete in our database', 'Checksum was blank', 'OrderId either not processed or Rejected', 'Merchant Identifier or Order Id was not valid', 'We could not find this transaction in our database', 'Transaction in Scheduled state', 'Transaction in Initiated state', 'Transaction in Processing state', 'Transaction has been authorized', 'Transaction has been put on hold', 'Transaction is incomplete', 'Transaction has been settled', 'Transaction has been cancelled', 'Data Validation success', 'Transaction has been captured', 'Transaction Refund Completed', 'Transaction Payout Initiated', 'Transaction Payout Completed', 'Transaction Payout Error', 'Transaction Refund Paid Out', 'Transaction Chargeback has been initiated', 'Transaction Chargeback is being processed', 'Transaction Chargeback has been accepted', 'Transaction Chargeback has been reverted', 'Transaction Chargeback revert is now complete', 'Transaction Refund Initiated', 'Your Bank has declined this transaction, please Retry this payment with another pay method', 'Transaction Refund Before Payout Completed'];

                $status = FALSE;
                foreach ($status_descriptions as $description) {
                    if (strpos(strtolower($without_checksum), strtolower($description)) !== false) {
                        $status = $description;
                        break;
                    }
                }

                //After API hit check whether a response is received or not.
                if ($status) {

                    //Checking the status falls under successful transcation statuses.
                    $successful_transaction_statuses = array('Transaction has been captured', 'Transaction Payout Initiated', 'Transaction Payout Completed');

                    //If the transaction status is in the above array, money has been credited so a button can be displayed to inform settle.
                    if (in_array($status, $successful_transaction_statuses)) {
                        //Check whether the transaction is settled or not.
                        $check_settlement = $this->API_Model->check_settlement($transactions[$i]['PaymentId']);

                        $data_to_write = array('PaymentId' => $transactions[$i]['PaymentId'], 'Status' => $status);

                        //If no settlement is found against the payment id, save the payment id in the text file.
                        if (!$check_settlement) {
                            $this->write_paymentid($data_to_write);
                        }

                        echo json_encode($data_to_write);

                    } else {
                        //Setting the manual verify to 0. (Here, there maybe chance of manual verify 1 even for failed transactions case.
                        $this->API_Model->reset_manual_verify($transactions[$i]['PaymentId']);
                        $data_to_display = array('PaymentId' => $transactions[$i]['PaymentId'], 'Status' => $status);
                        echo json_encode($data_to_display);
                    }

                } else {
                    $data_to_display = array('PaymentId' => $transactions[$i]['PaymentId'], 'Status' => 'Can not get the response from Gateway. Manual check needed.');
                    $this->write_paymentid($data_to_display, 'TRUE');
                    echo json_encode($data_to_display);
                }
            }
        } else {
            $data = $this->generic->final_data('FAILED', NULL, 'No more data remaining');
            $this->generic->json_output($data);
        }

    }

    /**
     * //Writing the successful payment ids to the file.
     * @param $result
     */
    private
    function write_paymentid($result, $is_manual_check_needed = FALSE)
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

        if ($is_manual_check_needed) {
            $log_file = file_put_contents('paymentids/' . $log_day . '_pid_manual_check.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
        } else {

            $log_file = file_put_contents('paymentids/' . $log_day . '_pid.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
        }

    }

    /**
     *Getting the rate card of a branch code.
     */
    public
    function get_rates()
    {

        //Json Request
        $request = $this->generic->json_input();

        $headers = getallheaders();

        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != RATE_CARD_KEY) {
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

        //If no tag no is present, send the no key found output result.
        if (!array_key_exists('branch_code', $request)) {
            $data = $this->generic->final_data('NO_KEY_FOUND', 'branch_code');
            $this->generic->json_output($data);
            exit(0);
        }

        $branch_code = $request['branch_code'];

        $prices = $this->API_Model->get_storewise_prices_sp($branch_code);

        if ($prices) {
            $data = $this->generic->final_data('DATA_FOUND');
            $data['rate_card'] = $prices;
        } else {
            $data = $this->generic->final_data('DATA_NOT_FOUND');
        }

        $this->generic->json_output($data);

    }

    /**
     *Getting the blog post from the table.
     */
    public
    function get_blog()
    {

        $headers = getallheaders();

        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != BLOG_KEY) {
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

        $blog_posts = $this->API_Model->get_blog();

        for ($i = 0; $i < sizeof($blog_posts); $i++) {

            $blog_posts[$i]['post_url'] = 'https://fabricspa.com/post/' . $blog_posts[$i]['post_slug'];

            //Stripping away all the HTML tags to get the clean text only result.
            $blog_posts[$i]['post_content'] = str_replace('&nbsp;', '', (str_replace('&amp;', '', strip_tags($blog_posts[0]['post_content']))));
        }


        if ($blog_posts) {

            $data = $this->generic->final_data('DATA_FOUND');
            $data['posts'] = $blog_posts;

        } else {
            $data = $this->generic->final_data('DATA_NOT_FOUND');
        }

        $this->generic->json_output($data);
    }

    /**
     *Getting the nearest 5 stores from a lat and long
     */
    public
    function get_nearest_stores()
    {


        $headers = getallheaders();

        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != GET_NEAREST_STORE_API_KEY) {
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

        $request = $this->generic->json_input();

        //If no tag no is present, send the no key found output result.
        if (!array_key_exists('lat', $request)) {
            $data = $this->generic->final_data('NO_KEY_FOUND', 'lat');
            $this->generic->json_output($data);
            exit(0);
        }

        if (!array_key_exists('long', $request)) {
            $data = $this->generic->final_data('NO_KEY_FOUND', 'long');
            $this->generic->json_output($data);
            exit(0);
        }

        $lat = $request['lat'];
        $long = $request['long'];

        $nearest_stores = $this->API_Model->get_nearest_stores($lat, $long);

        if ($nearest_stores) {
            $final_data = $this->generic->final_data('DATA_FOUND');
            $final_data['stores'] = $nearest_stores;
        } else {
            $final_data = $this->generic->final_data('NO_DATA_FOUND');
        }

        //Outputting the final result
        $this->generic->json_output($final_data);
    }

    public function generate_payment_link()
    {

        $headers = getallheaders();

        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != PAYMENT_LINK_GENERATION_KEY) {
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

        $request = $this->generic->json_input();
        if ($request) {
            //If no egrn no is present, send the no key found output result.
            if (!array_key_exists('egrn', $request)) {
                $data = $this->generic->final_data('NO_KEY_FOUND', 'egrn');
                $this->generic->json_output($data);
                exit(0);
            }

           // To identify the payment Source Edited by Manju on 31July2021
           if (array_key_exists('source_from', $request)) {
            $source_from =$request['source_from'];
            }

            $egrn = $request['egrn'];
            if($source_from=='')
            $source_from='Fabricspa';
            //Checking whether the EGRN has already a link generated or not.
            $link_check = $this->API_Model->link_check($egrn);
            if (!$link_check) {
                // Edited by Manju to avoid duplicate cipher problem from 4 to 5
                  $cipher = $this->generic->generate_cipher($egrn, 20, 8, 5, TRUE);
                //Generating the payment link.
                if (IS_TESTING) {
                    $payment_link = 'https://appuat.fabricspa.com/UAT/paynow/' . $cipher;
                } else {
                    $payment_link = 'https://apps.fabricspa.com/paynow/' . $cipher;
                }
                //Calling the URL shortner service.
                $short_url = $this->generic->call_url_shortner_api($payment_link);


                $customer_details = $this->API_Model->customer_details_from_order_number($egrn);

                if ($short_url) {
                    if ($short_url['status'] == 'success') {
                        $final_data = $this->generic->final_data('DATA_FOUND');
                        $final_data['url'] = $short_url['short_url'];
                        // Saving the details into the DB.
                        $this->API_Model->save_payment_link($egrn, $cipher, $customer_details['CUSTOMERCODE'], $short_url['short_url'],$source_from);
                    } else {
                        $final_data = $this->generic->final_data('FAILED');
                    }
                } else {
                    $final_data = $this->generic->final_data('FAILED');
                }

            } else {
                $final_data = $this->generic->final_data('DATA_FOUND');
                $final_data['url'] = $link_check['ShortURL'];
            }

        } else {
            $final_data = $this->generic->final_data('NO_DATA_FOUND');

        }

        $this->generic->json_output($final_data);

    }


public function generate_shorturl_from_invoiceno()
    {
        $headers = getallheaders();
        if (array_key_exists('api_key', $headers)) {
            if ($headers['api_key'] != PAYMENT_LINK_GENERATION_KEY) {
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

        $request = $this->generic->json_input();
        if($request){
            //If no invoice no is present, send the no key found output result.
            if (!array_key_exists('invoiceno', $request)) {
                $data = $this->generic->final_data('NO_KEY_FOUND', 'invoiceno');
                $this->generic->json_output($data);
                exit(0);
            }
            $invoice_no = $request['invoiceno'];
            //Checking whether the Invoice No has already a url generated or not.
            $url_check = $this->API_Model->is_url_exists($invoice_no);
        
            if (!$url_check) {
                // Edited by Manju to avoid duplicate cipher problem from 4 to 5
                  $cipher = $this->generic->generate_cipher($invoice_no, 20, 8, 5, TRUE);
                //Generating the invoice link.
                if (IS_TESTING) {
                    $payment_link = 'https://appuat.fabricspa.com/UAT/paynow/report/' . $invoice_no;
                } else {
                    $payment_link = 'https://apps.fabricspa.com/paynow/report/' . $invoice_no;
                }
                //Calling the URL shortner service.
                $short_url = $this->generic->call_url_shortner_api($payment_link);
                $customer_details = $this->API_Model->customer_details_from_order_number($invoice_no);
                if ($short_url) {
                    if ($short_url['status'] == 'success') {
                        $final_data = $this->generic->final_data('DATA_FOUND');
                        $final_data['url'] = $short_url['short_url'];
                        // Saving the details into the DB.
                        $url_check=$this->API_Model->save_short_url($cipher, $customer_details['CUSTOMERCODE'],$invoice_no, $short_url['short_url']);
                    } else {
                        $final_data = $this->generic->final_data('FAILED');
                    }
                } else {
                    $final_data = $this->generic->final_data('FAILED');
                }
            }else {
                $final_data = $this->generic->final_data('DATA_FOUND');
                $final_data['url'] = $url_check['ShortURL'];
            }

        }else {
            $final_data = $this->generic->final_data('NO_DATA_FOUND');

        }

        $this->generic->json_output($final_data);
    }

}
