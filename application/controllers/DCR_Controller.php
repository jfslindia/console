<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 7/31/2018
 * Time: 10:18 AM
 */

/************************************************************************************************
 * STATUS CODES(Resultant key: status_code)
 *
 * Code Scenario
 * ---- --------
 *  0  Operation failed.(Scenarios: Saving failed/retrieving data failed)
 *  1  Operation succesful.(Scenarios: Saved successfully/retrieved data succesfully..etc)
 *  2  Store data is not available in the DCR_Pendings table
 *  3  Given PendingAmount is larger than the actual PendingAmount in the DCR_Pendings table. i.e. Invalid value.
 *  4  Empty result
 * 5 Multiple/Duplicate entry
 ************************************************************************************************/


if (!defined('BASEPATH')) exit('No direct script access allowed');

class DCR_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        //loading URL helper
        $this->load->helper('url');
        $this->load->library('PHPReport/PHPExcel');

        /*Loading DCR_Model for Database Operations*/
        $this->load->model('DCR_Model');

        //Loading the project settings
        $this->load->library('Settings/ProjectSettings');


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
        echo 'Daily Collection Controller!';
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


        $user_details = $this->DCR_Model->get_user_details($body['MobileNumber'], $body['Password']);

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
     *For getting all the cities along with the city name and city code of the currently active stores
     */
    public function get_cities()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);
        $brand_code = $body['BrandCode'];

        $cities = $this->DCR_Model->get_stores_cities_sp($brand_code);

        //Manipulating the results for changing the first letters to capitals
        for ($i = 0; $i < sizeof($cities); $i++) {
            $cities[$i]['CITYNAME'] = ucwords(strtolower($cities[$i]['CITYNAME']));
        }

        if ($cities)
            $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully retrieved cities', 'cities' => $cities);
        else
            $data = array('status' => 'failed', 'status_code' => '4', 'message' => 'Failed cities found');
        echo json_encode($data);
    }

    /**
     *Retrieving stores from the stored procedure directly from the Fabricare.
     */
    public function get_stores()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);
        /*$city_code = $body['CityCode'];
        $brand_code = $body['BrandCode'];
        $stores = $this->DCR_Model->get_stores_sp($city_code, $brand_code);
        //Manipulating the results for changing the first letters to capitals
        for ($i = 0; $i < sizeof($stores); $i++) {
            if (in_array($stores[$i]['BRANCHCODE'], $this->block_stores()) == FALSE) {
                $stores[$i]['BRANCHNAME'] = ucwords(strtolower($stores[$i]['BRANCHNAME']));
                $stores[$i]['BRANCHADDRESS'] = ucwords(strtolower($stores[$i]['BRANCHADDRESS']));
            } else {
                //Removing the store is that branch codes falls into the blocked criteria. Array will be re-indexed here.
                array_splice($stores, $i, 1);
            }
        }*/

        $user_id = $body['Id'];

        $stores = $this->DCR_Model->get_all_stores();


        $user_branchcodes = $this->DCR_Model->get_user_branchcodes($user_id);

        $user_branchcodes = json_decode($user_branchcodes['Branches']);

        $user_branch_details = [];

        for ($i = 0; $i < sizeof($user_branchcodes); $i++) {
            for ($j = 0; $j < sizeof($stores); $j++) {
                if ($stores[$j]['BranchCode'] == $user_branchcodes[$i]) {
                    array_push($user_branch_details, $stores[$j]);
                }
            }
        }

        if ($user_branch_details) {
            $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully retrieved cities', 'stores' => $user_branch_details);

        } else {
            $data = array('status' => 'failed', 'status_code' => '4', 'message' => 'No stores found');

        }
        echo json_encode($data);
    }

    /**
     *A private method for blocking stores based on custom constraints.
     */
    private function block_stores()
    {
        $blocked_branch_codes = array('SPEC000001');
        return $blocked_branch_codes;
    }

    /**
     *Getting the collection details directly from the Fabricare after executing the SP.
     */
    public function get_collection_details_from_fabricare()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);
        $date_from = date('m-d-Y', strtotime($body['DateFrom']));
        $date_to = date('m-d-Y', strtotime($body['DateTo']));
        $payment_mode = $body['PaymentMode'];
        $branch_code = $body['BranchCode'];
        $collection_details = $this->DCR_Model->get_collection_details_from_fabricare($date_from, $date_to, $payment_mode, $branch_code);


        if ($collection_details) {
            $amount_to_be_collected = 0;
            for ($i = 0; $i < sizeof($collection_details); $i++) {
                $amount_to_be_collected += $collection_details[$i]['BillAmount'];
            }
            $report = $this->generate_collection_report_from_fabricare($collection_details);
            $data = array('status' => 'success', 'message' => 'Successfully retrieved the collection details', 'amount_to_be_collected' => $amount_to_be_collected, 'report' => $report);
        } else {
            $data = array('status' => 'failed', 'message' => 'Failed to retrieve the collection details');
        }

        echo json_encode($data);
    }


    /**
     *Saving daily collection details into our database.
     */
    public function save_daily_collection()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        /*Checking whether the store data is present in our pendings table(DCR_Pendings) or not.
        If not, prompting collector to submit the pendings*/
        if ($this->check_pending_details($body['BranchCode']) == FALSE) {
            $data = array('status' => 'failed', 'status_code' => '2', 'message' => 'No pendings record found for that store');
            echo json_encode($data);
            exit();
        } else {

            $date_from = $body['DateFrom'];
            $date_to = $body['DateTo'];
            $branch_code = $body['BranchCode'];
            $branch_name = $body['BranchName'];
            //Collection type can be normal or pending
            $collection_type = $body['CollectionType'];
            $total_amount = $body['TotalAmount'];
            $collected_amount = $body['CollectedAmount'];
            $remarks = $body['Remarks'];
            $store_in_charge = $body['StoreInCharge'];
            $excel_report = $body['report'];
            $collected_by = $body['CollectedBy'];
            /*Checking whether the submission is resubmission or not.*/
            $resubmission = $body['Resubmission'];


            $pending_amount = $total_amount - $collected_amount;

            /*Updating the pendings details with the given data. If the given pending amount is larger than the saved data,
            then prompting the user to re-enter the pending amount once again. Else, update the amount in the table.
            */

          $pending_status = $this->DCR_Model->update_pending_details($branch_code, $branch_name, $date_from, $date_to, $pending_amount);
            if ($pending_status['status_code'] == 3) {
                $data = array('status' => 'failed', 'status_code' => '3', 'message' => 'Pending amount is invalid. Current pending amount of the store is ' . $pending_status['PendingAmount']);
                echo json_encode($data);
                exit();
            }

            /*Creating unix time stamp*/
            $date = date('Y-m-d H:i:s');

            $date_from = date('Y-m-d H:i:s', strtotime($date_from));

            $date_to = date('Y-m-d H:i:s', strtotime($date_to));


            //No need to insert DepositedDate here. It will always has NULL as the default value. When the collected data is deposited, its value will be changed.
            $save_data = array(
                'Date' => $date,
                'StoreBranchCode' => $branch_code,
                'StoreBranchName' => $branch_name,
                'CollectionType' => $collection_type,
                'DateFrom' => $date_from,
                'DateTo' => $date_to,
                'TotalAmount' => $total_amount,
                'CollectedAmount' => $collected_amount,
                'IsDeposited' => 0,
                'DepositId' => NULL,
                'DepositedDate' => NULL,
                'Remarks' => $remarks,
                'StoreInCharge' => $store_in_charge,
                'CollectedBy' => $collected_by
            );
            $save = $this->DCR_Model->save_daily_collection($save_data, $resubmission);
	
            //Sending the confirmation email.
            if ($save == 1) {
	
                $topic = 'Daily Collection';
                $subject = 'Daily Collection - ' . Date('d-m-Y');
                $attachment = NULL;
                $user_name = $this->DCR_Model->get_user_name($collected_by);
                $this->send_collections_mail($date, $date_from, $date_to, $topic, $subject, $attachment, $total_amount, $collected_amount, $pending_amount, $branch_code, $branch_name, $store_in_charge, $user_name, $remarks, $excel_report);
            }

            if ($save == 1) {
                $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully saved the daily collection');

            } else if ($save == 5) {
                $data = array('status' => 'failed', 'status_code' => '5', 'message' => 'Multiple records found.');

            } else {
                $data = array('status' => 'failed', 'status_code' => '0', 'message' => 'Saving failed.');
            }
        }


        echo json_encode($data);
    }

    /**
     *Saving pending collection details into our database.
     */
    public function save_pending_collection()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        /*Checking whether the store data is present in our pendings table(DCR_Pendings) or not.
        If not, prompting collector to submit the pendings*/
        if ($this->check_pending_details($body['BranchCode']) == FALSE) {
            $data = array('status' => 'failed', 'status_code' => '2', 'message' => 'No pendings record found for that store');
            echo json_encode($data);
            exit();
        } else {

            if (array_key_exists('DateFrom', $body)) {
                $date_from = date("m-d-Y", strtotime($body['DateFrom']));
            } else {
                $date_from = NULL;
            }

            if (array_key_exists('DateTo', $body)) {
                $date_to = date("m-d-Y", strtotime($body['DateTo']));
            } else {
                $date_to = NULL;
            }


            $branch_code = $body['BranchCode'];
            $branch_name = $body['BranchName'];
            //Collection type can be normal or pending
            $collection_type = $body['CollectionType'];

            $initial_pending_amount = $body['InitialPendingAmount'];
            $collected_amount = $body['CollectedAmount'];
            $remarks = $body['Remarks'];
            $store_in_charge = $body['StoreInCharge'];
            $collected_by = $body['CollectedBy'];

            /*Here, we are collecting the pending amount. So setting pending amount as a negative value
            (Because we already collected the pending). We need to deduct this value from the current pending amount in the DCR_Pendings table.*/
            $pending_amount = 0 - $collected_amount;

            /*Updating the pendings details with the given data. If the given pending amount is larger than the saved data,
            then prompting the user to re-enter the pending amount once again. Else, update the amount in the table.
            */

            $pending_status = $this->DCR_Model->update_pending_details($branch_code, $branch_name, NULL, NULL, $pending_amount);
            if ($pending_status['status_code'] == 3) {
                $data = array('status' => 'failed', 'status_code' => '3', 'message' => 'Pending amount is invalid. Current pending amount of the store is ' . $pending_status['PendingAmount']);
                echo json_encode($data);
                exit();
            }

            /*Creating unix time stamp*/
            $date = date('Y-m-d H:i:s');


            //No need to insert DepositedDate here. It will always has NULL as the default value. When the collected data is deposited, its value will be changed.
            $save_data = array(
                'Date' => $date,
                'StoreBranchCode' => $branch_code,
                'StoreBranchName' => $branch_name,
                'CollectionType' => $collection_type,
                'DateFrom' => $date_from,
                'DateTo' => $date_to,
                'TotalAmount' => NULL,
                'CollectedAmount' => $collected_amount,
                'IsDeposited' => 0,
                'DepositId' => NULL,
                'DepositedDate' => NULL,
                'Remarks' => $remarks,
                'StoreInCharge' => $store_in_charge,
                'CollectedBy' => $collected_by
            );
            $save = $this->DCR_Model->save_pending_collection($save_data);

            //Sending the confirmation email.
            if ($save) {

                $topic = 'Pending Collection';
                $subject = 'Pending Collection - ' . Date('d-m-Y');
                $attachment = NULL;
                $user_name = $this->DCR_Model->get_user_name($collected_by);
                $this->send_collections_mail($date, NULL, NULL, $topic, $subject, $attachment, $initial_pending_amount, $collected_amount, $pending_amount, $branch_code, $branch_name, $store_in_charge, $user_name, $remarks, null);
            }

            if ($save) {
                $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully saved pending collection');

            } else {
                $data = array('status' => 'failed', 'status_code' => '0', 'message' => 'Saving pending collection failed');

            }
        }


        echo json_encode($data);
    }


    /**
     *For the first time, when a store data is being collected, the new row in DCR_Pendings table is inserted.
     * Here, PendingAmount will be received and a new row is inserted. If that store has had no pending amount
     * to record then, the PendingAmount will be 0.
     */
    public function save_initial_pending_details()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);
        $branch_code = $body['BranchCode'];
        $pending_amount = $body['PendingAmount'];
        $branch_name = $body['BranchName'];
        //First time updating the store pending data into DCR_Pendings table.
        $status = $this->DCR_Model->save_initial_pending_details($branch_code, $pending_amount, $branch_name);
        if ($status) {
            $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully saved the pending data');

        } else {
            $data = array('status' => 'failed', 'status_code' => '0', 'message' => 'Saving initial pending details failed...');

        }
        echo json_encode($data);

    }

    /**
     * Checking whether we have the pending amount details on our DCR_Pendings table or not.
     * @param $branch_code
     * @return bool
     */
    private function check_pending_details($branch_code)
    {
        $status = $this->DCR_Model->check_pending_details($branch_code);
        return $status;
    }

    /**
     *Get all the collection details per user. This can be Daily Collection/Pending Collection or both.
     */
    public function get_collections()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);
        $user_id = $body['Id'];
        $collection_type = $body['CollectionType'];
        $is_deposited = $body['IsDeposited'];


        if (array_key_exists('DateFrom', $body)) {
            if ($body['DateFrom'] != '') {
                $date_from = $body['DateFrom'];
            } else {
                $date_from = NULL;
            }

        } else {
            $date_from = NULL;
        }
        if (array_key_exists('DateTo', $body)) {
            if ($body['DateTo'] != '') {
                $date_to = $body['DateTo'];
            } else {
                $date_to = NULL;
            }
        } else {
            $date_to = NULL;
        }
        if (array_key_exists('CityCode', $body)) {
            if ($body['CityCode'] != '') {
                $city_code = $body['CityCode'];
            } else {
                $city_code = NULL;
            }
        } else {
            $city_code = NULL;
        }
        if (array_key_exists('BranchCode', $body)) {
            if ($body['BranchCode'] != '') {
                $branch_code = $body['BranchCode'];
            } else {
                $branch_code = NULL;
            }

        } else {
            $branch_code = NULL;
        }
        $collections = $this->DCR_Model->get_collections($user_id, $collection_type, $is_deposited, $date_from, $date_to, $city_code, $branch_code);
        if ($collections) {
            $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully retrieved collections', 'collections' => $collections);

        } else {
            $data = array('status' => 'failed', 'status_code' => '4', 'message' => 'No collections found');

        }
        echo json_encode($data);
    }

    /**
     *Getting the pending amount for a particular store.
     */
    public function get_store_pending()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);
        $branch_code = $body['BranchCode'];
        if (array_key_exists('DateFrom', $body)) {
            $date_from = $body['DateFrom'];
        } else {
            $date_from = NULL;
        }
        if (array_key_exists('DateTo', $body)) {
            $date_to = $body['DateTo'];
        } else {
            $date_to = NULL;
        }

        $pending = $this->DCR_Model->get_store_pending($branch_code, $date_from, $date_to);

        if ($pending['PendingAmount']) {
            $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully retrieved pendings', 'pending' => $pending['PendingAmount']);

        } else {
            $data = array('status' => 'failed', 'status_code' => '2', 'message' => 'No pendings record found for that store');

        }
        echo json_encode($data);

    }

    /**
     *Depositing selected collections.
     */
    public function deposit_collections()
    {
          $input_time = date('Y-m-d H:i:s');
        $this->write_pg_response("DCR deposit collections input time".$input_time);
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        //$collections is an array of collection ids.
        $collections = $body['CollectionIds'];

        /*Creating unix time stamp*/
        $date = date('Y-m-d H:i:s');

        /*Finding out the total amount to be deposited.*/
        $total_amount = 0;
        for ($i = 0; $i < sizeof($collections); $i++) {
            $total_amount += $this->DCR_Model->get_collected_amount($collections[$i]);
        }
        $deposited_by = $body['DepositedBy'];
        $image = $body['Image'];

        $deposit_data = array(
            'Date' => $date,
            'DepositedAmount' => $total_amount,
            'Image' => $image,
            'DepositedBy' => $deposited_by

        );

        $deposit = $this->DCR_Model->deposit_collections($collections, $deposit_data);

        //Generating deposit report
        if ($deposit) {

            $topic = 'Deposit';
            $subject = 'Deposit - ' . Date('d-m-Y');
            $image_attachment = 'uploads/' . $image;

            $deposit_data = $this->DCR_Model->get_deposit_data($collections);

            $report_attachment = $this->generate_deposit_report($date, $deposit_data);


            //Getting the branch name from the a collection id.
            $branch_name = $this->DCR_Model->get_branch_name_from_collection_id($collections[0]);//0th index value just engough to check the city.

            $city_name = $this->check_city_from_branch_name($branch_name['StoreBranchName']);


            $user_name = $this->DCR_Model->get_user_name($deposited_by);

            $this->send_deposits_mail($date, $topic, $subject, $image_attachment, $report_attachment, $total_amount, $user_name, $city_name,$branch_name['StoreBranchName']);

        }

        if ($deposit) {
            $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully deposited the collections');

        } else {
            $data = array('status' => 'failed', 'status_code' => '0', 'message' => 'Failed to deposit the amount');

        }
        $output_time = date('Y-m-d H:i:s');
        $this->write_pg_response("DCR deposit collections input time".$input_time.", output_time -".$output_time);
        echo json_encode($data);

    }

    /**
     *Public function to file upload.
     */
    public function file_upload()
    {
        $input_time = date('Y-m-d H:i:s');
        $this->write_pg_response("DCR file upload input time".$input_time);
        if (is_uploaded_file($_FILES['uploaded_file']['tmp_name'])) {
            $uploads_dir = 'uploads/';
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
        $output_time = date('Y-m-d H:i:s');
        $this->write_pg_response("DCR file upload input time".$input_time."output time".$output_time);
        echo json_encode($data);

    }



    private function write_pg_response($response)
    {
        //Writing the stored procedure log in a file.
        $log_day = date('d-M-Y');
        $log_date = date('d-M-Y H:i:s');
        $json_response = json_encode($response);
        $request_time = $_SERVER['REQUEST_TIME'];
        $txt = 'date: ' . $log_date . ', response: ' . $json_response . ', request time: ' . $request_time . PHP_EOL;
        $underline = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $underline = $underline . '-';
        }
        $txt = $txt . $underline;
        $log_file = file_put_contents('pg_response/' . $log_day . '_response.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
    }


    /**
     * Private method for sending Daily Collection / Pending Collection mail
     * @param $date
     * @param $date_from
     * @param $date_to
     * @param $topic
     * @param $subject
     * @param $attachment
     * @param $total_amount
     * @param $collected_amount
     * @param $pending_amount
     * @param $branch_code
     * @param $branch_name
     * @param $store_in_charge
     * @param $user_name
     * @param $remarks
     * @param $excel_report
     * @return bool
     */
    private function send_collections_mail($date, $date_from, $date_to, $topic, $subject, $attachment, $total_amount, $collected_amount, $pending_amount, $branch_code, $branch_name, $store_in_charge, $user_name, $remarks, $excel_report)
    {
        /*Loading email library*/
        $this->load->library('email', array('mailtype' => 'html'));

        $email_id_of_branch = $this->DCR_Model->get_email_address_from_branchcode($branch_code);

        /*Email API configurations*/
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.mandrillapp.com';
        $config['smtp_user'] = 'web@snoways.in';
        $config['smtp_pass'] = 'PBxlel-v1KG5S5zED_ddWA';
        $config['smtp_port'] = 587;

        //$config['protocol'] = 'smtp'; 
        //$config['smtp_host'] = '192.168.5.224';
        //$config['smtp_port'] = 625;
        //$config['smtp_user'] = '';
        //$config['smtp_pass'] = '';


        /*Setting Email configurations*/
        $this->email->initialize($config);

        //$this->email->from('dcr@' . strtolower('jyothy') . '.com', $topic);
        $this->email->from('dcr@' . strtolower('JFSL') . '.in', $topic);

        $this->email->subject($branch_name . ' ' . $subject);

        $city_name = $this->check_city_from_branch_name($branch_name);

        if (IS_TESTING) {
            $this->email->to('jfsl.mdm@jyothy.com,manoj.marappa@jyothy.com,dcr@fabricspa.com');
        } else {
            $this->email->to('jfsl.mdm@jyothy.com,manoj.marappa@jyothy.com,dcr@fabricspa.com,' . $email_id_of_branch['EmailID']);
            $this->write_pg_response("collection mail to jfsl.mdm@jyothy.com,manoj.marappa@jyothy.com,dcr@fabricspa.com,".$email_id_of_branch['EmailID']);
            $cc = 'dcr@fabricspa.com,magesh.r@jyothy.com,collection@jfsl.in,Shilpa.n@jyothy.com,santosh.n@jyothy.com,sridhara.murthybm@jyothy.com,myappa.r@jyothy.com,Kiran.ms@jyothy.com,sukanta.kishor@jyothy.com';

            if (strtoupper($city_name) == 'DELHI') {
                $cc = $cc . ',srikant.pandey@jyothy.com,mukesh.sharma@jyothy.com,sanjay.kumar@jyothy.com,atif.siraj@jyothy.com,ram.tiwari@jyothy.com,amar.jha@jyothy.com,adnan.shamim@jyothy.com';
            }

            if (strtoupper($city_name) == 'GURGAON' || strtoupper($city_name) == 'INDIRAPURAM' || strtoupper($city_name) == 'NOIDA' || strtoupper($city_name) == 'VAISHALI' || strtoupper($city_name) == 'NEW DELHI') {
                $cc = $cc . ',adnan.shamim@jyothy.com';
            }

            if ($branch_name == 'WARDROBE CDC - SECTOR 15A NOIDA' || $branch_name == 'WARDROBE CDC - SECTOR 52 NOIDA' || $branch_name == 'WARDROBE CDC - NOIDA  SECTOR -41' || $branch_name == 'WARDROBE CDC - VAILSHALI' || $branch_name == 'WARDROBE CDC - INDIRAPURAM' || strtoupper($city_name) == 'GURGAON' || strtoupper($city_name) == 'INDIRAPURAM' || strtoupper($city_name) == 'NOIDA' || strtoupper($city_name) == 'VAISHALI' || strtoupper($city_name) == 'NEW DELHI') {
                $cc = $cc . ',mukesh.sharma@jyothy.com';
            }

            if (strtoupper($city_name) == 'PUNE') {
                $cc = $cc . ',mamata.shete@jyothy.com,mandar.bhosle@jyothy.com,ketan.jadhav@jyothy.com,sukanta.kishor@jyothy.com';
            }

            if (strtoupper($city_name) == 'MUMBAI') {
                $cc = $cc . ',mamata.shete@jyothy.com,nilesh.shelar@jyothy.com,sukanta.kishor@jyothy.com';
            }

            if (strtoupper($city_name) == 'NAVI MUMBAI') {
                $cc = $cc . ',mamata.shete@jyothy.com,nilesh.shelar@jyothy.com,sukanta.kishor@jyothy.com';
            }
             if(strtoupper($branch_name) == 'FABRICSPA CDC - DLF SUNCITY - HR' || strtoupper($branch_name) == 'FABRICSPA CDC - SUSHANTLOK GURGAON - HR'){
                $cc = $cc .',sanjay.kumar@jyothy.com';
            }
            $this->email->cc($cc);
        }


        /*$this->email->to('jfsl.mdm@jyothy.com,manoj.marappa@jyothy.com,dcr@fabricspa.com');*/

        //If the image link is specified, attach the image file to the mail.
        if ($attachment)
            $this->email->attach($attachment);

        if ($excel_report) {
            $this->email->attach($excel_report);
        }

        $table_data = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    	<html xmlns="http://www.w3.org/1999/xhtml">
    	<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    	<title></title>
    	<style>
    	h4{font-style: italic;}
    	.collected_amount{color:darkgreen;}
    	.pending_amount{color:red;}
        #table{
    	margin: auto;
    	border-color: grey;
    	border-collapse: collapse;
    }
    </style>
    </head>
    <body><table border="0" cellpadding="0" cellspacing="0"> </table>
    <table border="1" cellpadding="0" cellspacing="0" width="600" id="table">
    <tr>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>Type</h4>
    </td>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>' . $topic . '</h4>
    </td>
    </tr>
    <tr>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>Collected Date</h4>
    </td>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>' . date("d-m-Y h:i:s A", strtotime($date)) . '</h4>
    </td>
    </tr>
    ';

        if ($topic == 'Daily Collection') {

            $table_data = $table_data . '<tr><td align="center" valign="top" width="50%" class="templateColumnContainer">
    	<h4>Date From</h4>
    	</td>
    	<td align="center" valign="top" width="50%" class="templateColumnContainer">
    	<h4>' . date("d-m-Y", strtotime($date_from)) . '</h4>
    	</td>
    	</tr>
    	<tr>
    	<td align="center" valign="top" width="50%" class="templateColumnContainer">
    	<h4>Date To</h4>
    	</td>
    	<td align="center" valign="top" width="50%" class="templateColumnContainer">
    	<h4>' . date("d-m-Y", strtotime($date_to)) . '</h4>
    	</td>
    	</tr>
    	';
        }

        if ($topic == 'Daily Collection') {
            $table_data = $table_data . '<tr><td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>Amount to be collected</h4>
    </td>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4 class="collected_amount"> ₹' . $total_amount . '</h4>
    </td>
    </tr>';
        } else {
            $table_data = $table_data . '<tr><td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>Amount to be collected</h4>
    </td>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4 class="collected_amount"> ₹' . $pending_amount . '</h4>
    </td>
    </tr>';
        }


        $table_data = $table_data . '
    <tr>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>Collected Amount</h4>
    </td>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4 class="collected_amount"> ₹' . $collected_amount . '</h4>
    </td>
    </tr>';


        if ($topic == 'Daily Collection') {

            $table_data = $table_data . '<tr>
    	<td align="center" valign="top" width="50%" class="templateColumnContainer">
    	<h4>Difference</h4>
    	</td>
    	<td align="center" valign="top" width="50%" class="templateColumnContainer">
    	<h4 class="collected_amount"> ₹' . ($total_amount - $collected_amount) . '</h4>
    	</td>
    	</tr>';
        } else {
            $table_data = $table_data . '<tr>
    	<td align="center" valign="top" width="50%" class="templateColumnContainer">
    	<h4>Difference</h4>
    	</td>
    	<td align="center" valign="top" width="50%" class="templateColumnContainer">
    	<h4 class="collected_amount"> ₹' . ($total_amount - $collected_amount) . '</h4>
    	</td>
    	</tr>';
        }

        if ($pending_amount && $topic == 'Daily Collection') {
            $table_data = $table_data . '<tr>
    	<td align="center" valign="top" width="50%" class="templateColumnContainer">
    	<h4>Pending Amount</h4>
    	</td>
    	<td align="center" valign="top" width="50%" class="templateColumnContainer">
    	<h4 class="pending_amount"> ₹' . $pending_amount . '</h4>
    	</td>
    	</tr>';
        }

        $table_data = $table_data . '<tr>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>Branch Code</h4>
    </td>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>' . $branch_code . '</h4>
    </td>
    </tr>

    <tr>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>Store</h4>
    </td>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>' . $branch_name . '</h4>
    </td>
    </tr>

    <tr>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>Store in charge</h4>
    </td>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>' . $store_in_charge . '</h4>
    </td>
    </tr>

    <tr>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>Remarks</h4>
    </td>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>' . $remarks . '</h4>
    </td>
    </tr>

    <tr>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>Collected By</h4>
    </td>
    <td align="center" valign="top" width="50%" class="templateColumnContainer">
    <h4>' . $user_name . '</h4>
    </td>
    </tr>

    </table>
    <table border="0" cellpadding="0" cellspacing="0"> </table></body>
    </html>';        

        /*Configuring message*/
        $this->email->message($table_data);

        $mail_send_status = $this->email->send();


         $this->write_pg_response('add log file to check the Email Data DCR : ');       
        $this->write_pg_response('  SP  : '.$mail_send_status);


        if ($mail_send_status)
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Sending Deposit mails.
     * @param $date
     * @param $topic
     * @param $subject
     * @param $attachment
     * @param $total_amount
     * @param $user_name
     * @param $city_name -- Criteria for sending mail to a specific location.
     * @return bool
     */
    private function send_deposits_mail($date, $topic, $subject, $image_attachment, $report_attachment, $total_amount, $user_name, $city_name,$branch_name)
    {
        /*Loading email library*/
        $this->load->library('email', array('mailtype' => 'html'));


        /*Email API configurations*/
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.mandrillapp.com';
        $config['smtp_user'] = 'web@snoways.in';
        $config['smtp_pass'] = 'PBxlel-v1KG5S5zED_ddWA';
        $config['smtp_port'] = 587;
		
		//$config['protocol'] = 'smtp';
        //$config['smtp_host'] = '192.168.5.224';
        //$config['smtp_port'] = 625;
		//$config['smtp_user'] = '';
        //$config['smtp_pass'] = '';


        /*Setting Email configurations*/
        $this->email->initialize($config);

		//$this->email->from('dcr@' . strtolower('jyothy') . '.com', $topic);
        $this->email->from('dcr@' . strtolower('JFSL') . '.in', $topic);

        $this->email->subject($subject);


        if (IS_TESTING) {
            $this->email->to('jfsl.mdm@jyothy.com,manoj.marappa@jyothy.com,dcr@fabricspa.com');
        } else {

            $this->email->to('jfsl.mdm@jyothy.com,manoj.marappa@jyothy.com,dcr@fabricspa.com');
            $cc = 'dcr@fabricspa.com,vidhya.r@jyothy.com,magesh.r@jyothy.com,collection@jfsl.in,Shilpa.n@jyothy.com,santosh.n@jyothy.com,sridhara.murthybm@jyothy.com,myappa.r@jyothy.com,Kiran.ms@jyothy.com,sukanta.kishor@jyothy.com,Rajesh.m@jyothy.com,Babu.murali@jyothy.com,Konda.rameshbabu@jyothy.com';

            //Here, specific criteria is sending mails for Delhi city.
            if (strtoupper($city_name) == 'DELHI') {
                $cc = $cc . ',adnan.shamim@jyothy.com,Amar.jha@jyothy.com';
            }

            if (strtoupper($city_name) == 'GURGAON' || strtoupper($city_name) == 'INDIRAPURAM' || strtoupper($city_name) == 'NOIDA' || strtoupper($city_name) == 'VAISHALI') {
                $cc = $cc . ',adnan.shamim@jyothy.com';
            }

            if (strtoupper($city_name) == 'PUNE') {
                $cc = $cc . ',mamata.shete@jyothy.com,mandar.bhosle@jyothy.com,ketan.jadhav@jyothy.com,sukanta.kishor@jyothy.com';
            }

            if (strtoupper($city_name) == 'MUMBAI') {
                $cc = $cc . ',mamata.shete@jyothy.com,nilesh.shelar@jyothy.com,sukanta.kishor@jyothy.com';
            }

            if (strtoupper($city_name) == 'NAVI MUMBAI') {
                $cc = $cc . ',mamata.shete@jyothy.com,nilesh.shelar@jyothy.com,sukanta.kishor@jyothy.com';
            }
            if(strtoupper($branch_name) == 'FABRICSPA CDC - DLF SUNCITY - HR' || strtoupper($branch_name) == 'FABRICSPA CDC - SUSHANTLOK GURGAON - HR'  ){
                $cc = $cc .',sanjay.kumar@jyothy.com';
            }
            if(strtoupper($city_name) == 'GURGAON' || strtoupper($city_name) == 'INDIRAPURAM' || strtoupper($city_name) == 'NOIDA' || strtoupper($city_name) == 'VAISHALI' || strtoupper($city_name) == 'NEW DELHI'){
                $cc = $cc . ',mukesh.sharma@jyothy.com';
            }
            $this->email->cc($cc);

        }
        /*$this->email->to('jfsl.mdm@jyothy.com,manoj.marappa@jyothy.com,dcr@fabricspa.com');*/

        //If the image link is specified, attach the image file to the mail.
        if ($image_attachment)
            $this->email->attach($image_attachment);
        if ($report_attachment)
            $this->email->attach($report_attachment);

        $table_data = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    	<html xmlns="http://www.w3.org/1999/xhtml">
    	<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    	<title></title>
    	<style>
    	h4{font-style: italic;}
    	.amount{color:darkgreen;}
        #table{
    	margin:auto;
    	border-color: grey;
    	border-collapse: collapse;
    }
        #note{
    text-align:center;
    font-style: italic;
}
</style>
</head>
<body><table border="0" cellpadding="0" cellspacing="0"> </table>
<table border="1" cellpadding="0" cellspacing="0" width="600" id="table">
<tr>
<td align="center" valign="top" width="50%" class="templateColumnContainer">
<h4>Type</h4>
</td>
<td align="center" valign="top" width="50%" class="templateColumnContainer">
<h4>' . $topic . '</h4>
</td>
</tr>
<tr>
<td align="center" valign="top" width="50%" class="templateColumnContainer">
<h4>Date</h4>
</td>
<td align="center" valign="top" width="50%" class="templateColumnContainer">
<h4>' . date("d-m-Y h:i:s A", strtotime($date)) . '</h4>
</td>
</tr>
<tr>
<td align="center" valign="top" width="50%" class="templateColumnContainer">
<h4>Deposited Amount</h4>
</td>
<td align="center" valign="top" width="50%" class="templateColumnContainer">
<h4 class="amount"> ₹' . $total_amount . '</h4>
</td>
</tr>

<tr>
<td align="center" valign="top" width="50%" class="templateColumnContainer">
<h4>Deposited By</h4>
</td>
<td align="center" valign="top" width="50%" class="templateColumnContainer">
<h4>' . $user_name . '</h4>
</td>
</tr>
<tr><td colspan="2"><p id="note">*Please find the attached image</p></td></tr>
</table>
<table border="0" cellpadding="0" cellspacing="0"> </table></body>
</html>';




        /*Configuring message*/
        $this->email->message($table_data);

        /*Sending message*/
        $mail_send_status = $this->email->send();

         $this->write_pg_response('add log file to check the Email Data DCR : ');       
        $this->write_pg_response('  SP  : '.$mail_send_status);

        if ($mail_send_status)
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Generating the spreadsheet of deposit details which needs to be attached at the time of a deposit.
     * @param $date
     * @param $deposit_data
     *
     * @return string
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Writer_Exception
     */
    private function generate_deposit_report($date, $deposit_data)
    {
        $objPHPExcel = new PHPExcel();


        $total_results = sizeof($deposit_data);
        $total_amount = 0;


        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Collected Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Deposit Slip #');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Store Names');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Deposit Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Settlement Date From');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Settlement Date To');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Collection Type');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Collection Executive Name');


        $j = 2;

        for ($i = 0; $i < $total_results; $i++) {

            if ($deposit_data[$i]['DateFrom']) {
                $date_from = date("d-m-Y h:i:s A", strtotime($deposit_data[$i]['DateFrom']));
            } else {
                $date_from = 'N/A';
            }

            if ($deposit_data[$i]['DateTo']) {
                $date_to = date("d-m-Y h:i:s A", strtotime($deposit_data[$i]['DateTo']));
            } else {
                $date_to = 'N/A';
            }


            $name = $this->DCR_Model->get_user_name($deposit_data[$i]['CollectedBy']);

            $total_amount += $deposit_data[$i]['CollectedAmount'];
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, date("d-m-Y h:i:s A", strtotime($deposit_data[$i]['Date'])));
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $deposit_data[$i]['DepositId']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $deposit_data[$i]['StoreBranchName']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $deposit_data[$i]['CollectedAmount']);

            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, date("d-m-Y h:i:s A", strtotime($date)));
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $date_from);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $date_to);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $j, $deposit_data[$i]['CollectionType']);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $j, $name);

            $j++;
        }

        $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($j + 1), 'Total Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($j + 1), $total_amount);

        //Merging deposit slip id column
        //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:A' . $j);

        //Merging name column.
        //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:F' . $j);

        $objPHPExcel->getActiveSheet()->setTitle('Deposits');

        // Set properties

        $objPHPExcel->getProperties()->setCreator('JFSL');
        //$objPHPExcel->getProperties()->setLastModifiedBy();
        $objPHPExcel->getProperties()->setTitle("Deposit Report");
        $objPHPExcel->getProperties()->setSubject("Deposit");
        $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $date = date('Y-m-d-H-i-s');
        //Final file name would be;
        $file_name = 'Deposit_' . $date . '.xlsx';

        $target_file = 'excel_reports/Deposits/' . $file_name;

        // Auto size columns for each worksheet
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $sheet = $objPHPExcel->getActiveSheet();
        $styleArray = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '2F4F4F')
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $sheet->getDefaultStyle()->applyFromArray($styleArray);

        $objWriter->save($target_file);
        return $target_file;
    }

    /**
     * Generating the spreadsheet of collection details which needs to be attached at the time of a collection.
     * @param $collection_details
     * @return string
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Writer_Exception
     */
    private function generate_collection_report_from_fabricare($collection_details)
    {
        $objPHPExcel = new PHPExcel();


        $total_results = sizeof($collection_details);
        $total_amount = 0;


        $objPHPExcel->setActiveSheetIndex(0);


        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'EGRN');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Bill No');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Branch Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Store Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'City');


        $j = 2;

        for ($i = 0; $i < $total_results; $i++) {

            $total_amount += $collection_details[$i]['BillAmount'];
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $collection_details[$i]['EGRN']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $collection_details[$i]['BillNo']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $collection_details[$i]['BillAmount']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $collection_details[$i]['BranchCode']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $collection_details[$i]['OutletName']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $collection_details[$i]['Date']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $collection_details[$i]['Place']);

            $j++;
        }

        $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($j + 1), 'Total Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($j + 1), $total_amount);

        //Merging deposit slip id column
        //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:A' . $j);

        //Merging name column.
        //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:F' . $j);

        $objPHPExcel->getActiveSheet()->setTitle('Collection Details');

        // Set properties

        $objPHPExcel->getProperties()->setCreator('JFSL');
        //$objPHPExcel->getProperties()->setLastModifiedBy();
        $objPHPExcel->getProperties()->setTitle("Collection Report From Fabricare");
        $objPHPExcel->getProperties()->setSubject("Collection");
        $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $date = date('Y-m-d-H-i-s');
        //Final file name would be;
        $file_name = 'Collection_' . $date . '.xlsx';

        $target_file = 'excel_reports/Collections/' . $file_name;

        // Auto size columns for each worksheet
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $sheet = $objPHPExcel->getActiveSheet();
        $styleArray = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '2F4F4F')
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $sheet->getDefaultStyle()->applyFromArray($styleArray);

        $objWriter->save($target_file);
        return $target_file;
    }

    public function test()
    {
        $this->DCR_Model->test();
    }

    public function stores_test()

    {
        $stores = $this->DCR_Model->get_all_stores();
        $data = array('status' => 'success', 'stores' => $stores);
        echo json_encode($data);
    }

    public function check_time()
    {
        /*Creating unix time stamp*/
        $date = date('d-m-Y h:i:s A');
        echo $date;
    }

    private function check_city_from_branch_name($branch_name)
    {

        $all_stores = $this->DCR_Model->get_all_stores();


        $city = '';

        for ($i = 0; $i < sizeof($all_stores); $i++) {

            if ($all_stores[$i]['BranchName'] == $branch_name) {

                $city = $all_stores[$i]['CityName'];
                break;

            }
        }

        return $city;
    }


}