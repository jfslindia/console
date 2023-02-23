<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 7/31/2018
 * Time: 10:31 AM
 */

/**
 * DCR Model Class
 *
 * @category Models
 * @package  JFSL
 * @since    1.0
 * @version  1.0
 * @author   KP
 */
class DCR_Model extends CI_Model
{
    /**
     *Constructor, use for globally loading libraries, variables, helpers
     */
    function __construct()
    {
        parent::__construct();
        // loading database
        $this->load->database();
    }

    /**
     * For getting active cities of multiple stores.
     * @param $brand_code
     * @return mixed
     */
    public function get_stores_cities_sp($brand_code)
    {
        $query = $this->db->query("EXEC " . LOCAL_DB . ".dbo.GetCityDataForActiveStores @BrandCode ='" . $brand_code . "'")->result_array();
        return $query;
    }

    /**
     * For getting all the stores from Fabricare by passing brand code and city code.
     * @param $city_code
     * @param $brand_code
     * @return mixed
     */
    public function get_stores_sp($city_code, $brand_code)
    {
        $query = $this->db->query("EXEC " . LOCAL_DB . ".dbo.GetBranchDataForCity @CityCode ='" . $city_code . "', @BrandCode='" . $brand_code . "'")->result_array();
        return $query;
    }

    /**
     * Saving the daily collection details into our DB.
     * @param $details
     * @param $resubmission
     * @return mixed
     */
    public function save_daily_collection($details, $resubmission)
    {

        if ($resubmission == 'false') {
            /*Check if already the data present in the table for BranchCode or not for that FromDate and ToDate*/
            $collection_data = $this->db->select('*')->from('DCR_Collections')->where(array('StoreBranchCode' => $details['StoreBranchCode'], 'DateFrom' => $details['DateFrom'], 'DateTo' => $details['DateFrom']))->get()->result_array();
        } else {
            $collection_data = FALSE;
        }


        if (!$collection_data) {

            /*Checking whether the collected amount is less than or equal to total amount. If yes, collection is daily collection type.
            Else collection type is both */
            if ($details['CollectedAmount'] <= $details['TotalAmount']) {
                $details['CollectionType'] = 'Daily Collection';
                $status = $this->db->insert('DCR_Collections', $details);
                if ($status) {
                    $status = 1;
                } else {
                    $status = 0;
                }
            } else {
                /*Two rows needs to be inserted.*/
                $extra_amount = abs($details['TotalAmount'] - $details['CollectedAmount']);

                //Setting up daily collection row.
                $details['CollectedAmount'] = $details['TotalAmount'];
                $details['CollectionType'] = 'Daily Collection';
                $status = $this->db->insert('DCR_Collections', $details);

                //Setting up pending row
                $details['CollectedAmount'] = $extra_amount;
                $details['CollectionType'] = 'Pending Collection';
                $details['TotalAmount'] = NULL;
                $details['DateFrom'] = NULL;
                $details['DateTo'] = NULL;
                $status = $this->db->insert('DCR_Collections', $details);

                if ($status) {
                    $status = 1;
                } else {
                    $status = 0;
                }

            }
        } else {
            /*Collection data present for that FromDate and ToDate. So sending a warning regarding this already present data.*/
            $status = 5;
        }

        return $status;
    }

    /**
     * Saving the pending collection details into our DB. Only pending row will be inserted, so no need to insert two times lik in save_daily_collection().
     * @param $details
     * @return mixed
     */
    public function save_pending_collection($details)
    {


        $status = $this->db->insert('DCR_Collections', $details);


        return $status;
    }

    /**
     *Updating the pending details of a store
     * @param $branch_code -- BranchCode of the store.
     * @param $date_from -- collection date from
     * @param $date_to -- collection date to
     * @param $pending_amount -- Pending amount of the store
     * @return int Status codes. If 1, then updation is succesful. If 2, then the updation is failed. If 3,
     * then the $pending_amount is larger than the saved pending amount in the table, hence invalid.
     */
    public function update_pending_details($branch_code, $branch_name, $date_from = FALSE, $date_to = FALSE, $pending_amount)
    {
        $date = date('Y-m-d H:i:s');

        $update_status = FALSE;

        //Here, already a row is present, so updating the current pending amount of that store.
        /*Checking whether the new pending amount is equal with the saved record or not.*/

        $total_pending_amount = $this->db->select('SUM(PendingAmount) as PendingAmount')->from('DCR_PendingsLog')->where('StoreBranchCode', $branch_code)->get()->row_array();
        $pending_details = $this->db->select('*')->from('DCR_PendingsLog')->where('StoreBranchCode', $branch_code)->get()->result_array();

        /*If the entered pending amount is less than or equal to the pendings data in the pendings table, then it is valid.
        Of if the entered amount is larger than the actual pending amount, then client app should prompt to check the pending amount
        once again.

        EXAMPLE SCENARIO:
        -----------------------

        If the $pending_details['PendingAmount'] is 4000 and the $pending_amount is 5000, then it is invalid.
        If the $pending_amount is 4000 or less then it is valid.
        */


        //If pending amount is a positive value, that means the given pending amount is needs to be added with the current pending amount in the table.
        if ($pending_amount > 0) {

            //A new pending row is inserted.
            $update_status = $this->db->insert('DCR_PendingsLog', array('Date' => $date, 'StoreBranchCode' => $branch_code, 'StoreBranchName' => $branch_name, 'DateFrom' => date("m-d-Y", strtotime($date_from)), 'DateTo' => date("m-d-Y", strtotime($date_to)), 'PendingAmount' => $pending_amount));

        } else {

            //If the given pending amount is a negative value, that means the collector has collected more amount than total amount. So, that amount is
            // needs to be deducted from the current pending amount because it is due amount.

            if ($total_pending_amount > 0 && $total_pending_amount >= $pending_amount) {


                for ($i = 0; $i < sizeof($pending_details); $i++) {

                    $current_pending_amount = $pending_details[$i]['PendingAmount'];

                    if ($current_pending_amount > 0) {

                        if ($current_pending_amount - abs($pending_amount) >= 0) {
                            $current_pending_amount = $current_pending_amount - abs($pending_amount);
                            $update_status = $this->db->where(array('StoreBranchCode' => $branch_code, 'Id' => $pending_details[$i]['Id']))->update('DCR_PendingsLog', array('Date' => $date, 'PendingAmount' => $current_pending_amount));

                        } else {

                            $pending_amount = abs($pending_amount) - $current_pending_amount;
                            $current_pending_amount = 0;
                            $update_status = $this->db->where(array('StoreBranchCode' => $branch_code, 'Id' => $pending_details[$i]['Id']))->update('DCR_PendingsLog', array('Date' => $date, 'PendingAmount' => $current_pending_amount));
                        }

                    }
                }

            } else {
                return array('status_code' => 3, 'PendingAmount' => $total_pending_amount['PendingAmount']);//PendingAmount will not be a negative value.
            }
        }


        if ($update_status)
            return array('status_code' => 1);
        else
            return array('status_code' => 0);

    }

    /**
     * Checking whether we have the pending amount details on our DCR_Pendings table or not.
     * @param $branch_code
     * @return bool
     */
    public function check_pending_details($branch_code)
    {
        $result = $this->db->select('*')->from('DCR_PendingsLog')->where('StoreBranchCode', $branch_code)->get()->row_array();
        if ($result)
            return TRUE;
        else
            return FALSE;
    }

    /**
     * FIRST TIME DCR_Pendings table insertion ONLY
     * Checking whether we have the pending amount details on our DCR_Pendings table or not.
     * @param $branch_code
     * @param $pending_amount
     * @param $branch_name
     * @return bool
     */
    public function save_initial_pending_details($branch_code, $pending_amount, $branch_name)
    {
        $date = date('Y-m-d H:i:s');
        $status = $this->db->insert('DCR_PendingsLog', array('Date' => $date, 'StoreBranchCode' => $branch_code, 'StoreBranchName' => $branch_name, 'PendingAmount' => $pending_amount));
        if ($status)
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Getting the user information for the login
     * @param $mobile_number
     * @param $password
     */
    public function get_user_details($mobile_number, $password)
    {
        $result = $this->db->select('*')->from('DCR_Users')->where(array('Phone' => $mobile_number, 'Password' => $password))->get()->row_array();

        return $result;

    }


    /**
     * Get all the collection details per user
     * @param $user_id
     * @param $collection_type
     * @param $is_deposited
     * @param $date_from
     * @param $date_to
     * @param $city_code
     * @param $branch_code
     * @return mixed
     */
    public function get_collections($user_id, $collection_type, $is_deposited, $date_from, $date_to, $city_code, $branch_code)
    {
        /*Constructing the array of conditions that need to be applied on the query*/
        $condition_array = [];
        $condition_array['CollectedBy'] = $user_id;
        $condition_array['IsDeposited'] = $is_deposited;

        if ($branch_code) {
            $condition_array['StoreBranchCode'] = $branch_code;
        }
        if ($collection_type == 'Daily Collection' || $collection_type == 'Pending Collection') {
            $condition_array['CollectionType'] = $collection_type;
        }


        if ($date_from && $date_to) {
            if ($is_deposited == '1') {
                /*Deposited data*/
                $collections = $this->db->select("Id,convert(varchar(10),Date,105) as Date,StoreBranchCode,StoreBranchName,CollectionType,convert(varchar(10), DateFrom, 105) as DateFrom,convert(varchar(10), DateTo, 105) as DateTo,TotalAmount,CollectedAmount,IsDeposited,DepositId,DepositedDate,Remarks,StoreInCharge,CollectedBy")->from("DCR_Collections")->where($condition_array)->where("convert(varchar(10), DepositedDate, 105)  between '" . $date_from . "' and '" . $date_to . "'")->order_by("Date", "asc")->get()->result_array();
            } else {
                $collections = $this->db->select("Id,convert(varchar(10),Date,105) as Date,StoreBranchCode,StoreBranchName,CollectionType,convert(varchar(10), DateFrom, 105) as DateFrom,convert(varchar(10), DateTo, 105) as DateTo,TotalAmount,CollectedAmount,IsDeposited,DepositId,DepositedDate,Remarks,StoreInCharge,CollectedBy")->from("DCR_Collections")->where($condition_array)->where("convert(varchar(10), DateFrom, 105)  >= '" . $date_from . "' and convert(varchar(10), DateTo, 105) <= '" . $date_to . "'")->order_by("Date", "asc")->get()->result_array();
            }

        } else {

            $collections = $this->db->select("Id,convert(varchar(10),Date,105) as Date,StoreBranchCode,StoreBranchName,CollectionType,convert(varchar(10), DateFrom, 105) as DateFrom,convert(varchar(10), DateTo, 105) as DateTo,TotalAmount,CollectedAmount,IsDeposited,DepositId,DepositedDate,Remarks,StoreInCharge,CollectedBy")->from("DCR_Collections")->where($condition_array)->order_by("Date", "asc")->get()->result_array();

        }
        $this->write_pg_response($this->db->last_query());
        $this->write_pg_response("collections list".json_encode($collections));
        return $collections;

    }

    /**
     * Get the pending amount for a store
     * @param $branch_code
     * @param bool $date_from
     * @param bool $date_to
     * @return
     */
    public function get_store_pending($branch_code, $date_from = FALSE, $date_to = FALSE)
    {
        if ($date_from && $date_to) {
            //$pending = $this->db->select('SUM(PendingAmount) as PendingAmount')->from('DCR_PendingsLog')->where('StoreBranchCode', $branch_code)->where("convert(varchar(10), Date, 105)  between '".$date_from."' and '".$date_to."'")->get()->row_array();
            $pending = $this->db->select('SUM(PendingAmount) as PendingAmount')->from('DCR_PendingsLog')->where('StoreBranchCode', $branch_code)->where("convert(varchar(10), DateFrom, 105)  >= '" . $date_from . "' and convert(varchar(10), DateTo, 105) <= '" . $date_to . "'")->get()->row_array();
        } else {
            $pending = $this->db->select('SUM(PendingAmount)  as PendingAmount')->from('DCR_PendingsLog')->where('StoreBranchCode', $branch_code)->get()->row_array();
        }

        return $pending;
    }

    /**
     * Getting the collected amount from the DB by passing the collection ID.
     * @param $id
     */
    public function get_collected_amount($id)
    {

        $result = $this->db->select('CollectedAmount')->where('Id', $id)->from('DCR_Collections')->get()->row_array();
        return $result['CollectedAmount'];

    }

    /**
     * Insert deposit information in the DCR_Deposits table and update related fields in the DCR_Collections table.
     * @param $collections
     * @param $deposit_data
     * @return bool
     */
    public function deposit_collections($collections, $deposit_data)
    {

        /*Starting transaction for getting insert id corresponding the current insert*/
        $this->db->trans_start();

        /*Inserting data to the database*/
        $deposit = $this->db->insert('DCR_Deposits', $deposit_data);
        $deposit_id = $this->db->insert_id();

        /*Transaction complete*/
        $this->db->trans_complete();


        //Updating the IsDeposited,DepositId fields in the DCR_Collections if the deposit is successful.
        $update_collections = FALSE;
        if ($deposit) {
            for ($i = 0; $i < sizeof($collections); $i++)
                $update_collections = $this->db->where('Id', $collections[$i])->update('DCR_Collections', array('IsDeposited' => 1, 'DepositId' => $deposit_id, 'DepositedDate' => $deposit_data['Date']));
        }

        /*If $deposit and $update_collections are TRUE, then deposit is successful.*/
        if ($deposit && $update_collections) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    /**
     * Getting the user name from Id.
     * @param $id
     * @return mixed
     */
    public function get_user_name($id)
    {
        $result = $this->db->select('Name')->from('DCR_Users')->where('Id', $id)->get()->row_array();
        return $result['Name'];
    }

    /**
     * Getting all the details from the DCR_Collections based on IDs.
     * @param $collections collection IDs of DCR_Collections
     */
    public function get_deposit_data($collections)
    {

        $result = [];
        for ($i = 0; $i < sizeof($collections); $i++) {

            $row = $this->db->select('*')->from('DCR_Collections')->where(array('Id' => $collections[$i], 'IsDeposited' => 1))->get()->row_array();
            array_push($result, $row);
        }
        return $result;
    }

    public function test()
    {
        $query = $this->db->query("EXEC JFSL.dbo.RPT_StatementOfDailyCollection_Mobile @InvoicePaymentFromDate='01/1/2018',@InvoicePaymentToDate='10/10/2018',@PaymentMode='1',@Branch='BRN0000151'")->result_array();
        print_r($query);
    }

    /**
     *Getting the collection details of a store directly from the Fabricare.
     * @param $date_from
     * @param $date_to
     * @param $payment_mode
     * @param $branch_code
     */
    function get_collection_details_from_fabricare($date_from, $date_to, $payment_mode, $branch_code)
    {

        $query = "EXEC JFSL.dbo.RPT_StatementOfDailyCollection_Mobile @InvoicePaymentFromDate='" . $date_from . "',@InvoicePaymentToDate='" . $date_to . "',@PaymentMode='" . $payment_mode . "',@Branch='" . $branch_code . "'";
        $this->write_pg_response("store collection details from fabricare".$query);
        $collection_details = $this->db->query($query)->result_array();
                $this->write_pg_response("store collection details from fabricare".$query." response: ".json_encode($collection_details));
        return $collection_details;

    }

    public function get_all_stores()
    {
        $stores = $this->db->query('EXEC ' . SERVER_DB . '.dbo.sp_GetAllBranchDetails')->result_array();
        return $stores;
    }

    public function get_user_branchcodes($user_id)
    {
        $branch_codes = $this->db->select('Branches')->from('DCR_Users')->where('Id', $user_id)->get()->row_array();
        return $branch_codes;
    }

    public function get_email_address_from_branchcode($branch_code)
    {
        $email_id = $this->db->select("EmailID from JFSL.dbo.BranchInfo where BranchCode='" . $branch_code . "'")->get()->row_array();
        return $email_id;

    }


    /*Getting the branch name from the collection id.*/
    public function get_branch_name_from_collection_id($id)
    {
        $result = $this->db->select('StoreBranchName')->from('DCR_Collections')->where('Id', $id)->get()->row_array();
        return $result;

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


}