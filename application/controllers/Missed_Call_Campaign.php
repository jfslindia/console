<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Missed_Call_Campaign
 * @property Missed_Call_Campaign_Model $Missed_Call_Campaign_Model
 * @property generic $generic
 */
class Missed_Call_Campaign extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        //loading URL helper
        $this->load->helper('url');

        //Loading the project settings
        $this->load->library('Settings/ProjectSettings');

        //Loading the campaign model
        $this->load->model('Missed_Call_Campaign_Model');

        //Loading the Generic Functions
        $this->load->library('GenericFunctions/Generic');


    }

    public function index($mobile_number = FALSE)
    {

        if ($mobile_number) {

            //Checking the mobile number with Fabricare.
            $fabricare_customer_details = $this->Missed_Call_Campaign_Model->get_fabricare_customer_details($mobile_number);

            //Checking the mobile number with mobile db.
            $user_details = $this->Missed_Call_Campaign_Model->get_user_details($mobile_number);
        } else {
            $fabricare_customer_details = FALSE;
            $user_details = FALSE;
        }


        $is_registered = FALSE;

        //Checking the customer is already registered or not. Whether it is in Fabricare or Mobile DB doesn't matter.
        if ($user_details || $fabricare_customer_details) {
            $is_registered = TRUE;
        }

        $data = array('mobile_number' => $mobile_number, 'is_customer_registered' => $is_registered);
        $this->load->view('Missed_Call_Campaign/campaign', $data);
    }


    /**
     *Registering a user
     */
    public function register()
    {

        /*Reading data from the body of the POST request,
       data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        $name = $body['name'];
        $mobile_number = $body['mobile_number'];
        $email = $body['email'];

        if (strlen($name) > 0 && strlen($mobile_number) == 10 && strlen($email) > 0) {


            if (array_key_exists('dob', $body)) {
                if ($body['dob'] != '')
                    $dob = date("Y-m-d", strtotime($body['dob']));
                else
                    $dob = 'null';
            } else {
                $dob = 'null';
            }

            $pincode = $body['pincode'];

            if (array_key_exists('gender', $body)) {
                if ($body['gender'] != '')
                    $gender = $body['gender'];
                else
                    $gender = 'null';
            } else {
                $gender = 'null';
            }

            $area_code = $body['area_code'];
            $house = $body['house'];

            if (array_key_exists('landmark', $body)) {
                if ($body['landmark'] != '')
                    $landmark = $body['landmark'];
                else
                    $landmark = 'null';
            } else {
                $landmark = 'null';
            }


            $area_details = $this->Missed_Call_Campaign_Model->get_area_details($pincode, $area_code);

            $date = Date('Y-m-d H:i:s');

            $data_to_save = array(

                'date' => $date,
                'name' => $name,
                'mobile_number' => $mobile_number,
                'email' => $email,
                'dob' => $dob,
                'gender' => $gender,
                'pincode' => $pincode,
                'house' => $house . ', ' . $landmark,
                'location' => $area_details['location'],
                'BranchCode' => $area_details['BranchCode'],
                'RouteCode' => $area_details['RouteCode'],
                'Area' => $area_details['Area'],
                'AreaCode' => $area_details['AreaCode'],
                'CityCode' => $area_details['CityCode'],
                'sign_up_source' => 'Campaign'
            );

            $status = $this->Missed_Call_Campaign_Model->register_customer($data_to_save);

            if ($status) {
                $data = $this->generic->final_data('DATA_SAVED');
            } else {
                $data = $this->generic->final_data('DATA_NOT_SAVED');
            }
        } else {
            $data = $this->generic->final_data('DATA_NOT_SAVED');
        }

        $this->generic->json_output($data);
        exit(0);
    }

    /**
     *Getting the area list from a pincode.
     */
    public function area_list()
    {
        /*Reading data from the body of the POST request,
       data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);
        $pincode = $body['pincode'];


        //Getting the area list from a entered pincode.
        $area_list = $this->Missed_Call_Campaign_Model->area_list($pincode);

        if ($area_list) {

            $data = $this->generic->final_data('DATA_FOUND');
            $data['area_list'] = $area_list;
        } else {
            $data = $this->generic->final_data('DATA_NOT_FOUND');
        }

        $this->generic->json_output($data);
        exit(0);

    }
}