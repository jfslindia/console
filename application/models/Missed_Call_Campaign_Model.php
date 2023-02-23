<?php

/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 10/24/2019
 * Time: 11:03 AM
 */
class Missed_Call_Campaign_Model extends CI_Model
{
    /**
     *Constructor, use for globally loading libraries, variables, helpers
     */
    function __construct()
    {
        parent::__construct();
        // loading database
        $this->load->database();

        /*Loading the custom DBKit for SP executions.*/
        $this->load->library('DBKit/DBKit');
    }


    /**
     *  Function to retrieve are and route code from RouteMaster Database
     *
     * @param string $pincode Pincode entered by the customer
     * @param string $brand Brand of the customer
     *
     * @return array|bool|CI_DB_active_record|CI_DB_result IF no area is for the specified
     *                                                     pincode false is returned, else
     *                                                     RouteCode and Area are returned
     */
    public function area_list($pincode)
    {

        /*Sorting address to get Home as first address and office as second*/
        $this->db->order_by("Area", "asc");
        $this->db->select('*');
        /*Getting data from database*/
        $query = $this->db->get_where('V_RouteMaster', array('Pincode' => $pincode, 'Brand' => 'FABRICSPA'));


        /*if no address exists in database set return as false*/
        if ($query->num_rows() > 0) {
            $query = $query->result_array();
        } /*If data exist, return address data*/
        else {
            $query = FALSE;
        }

        return $query;
    }

    /**
     * Checking whether the customer already in Fabricare or not
     * @param $mobile_number
     * @return bool
     */
    public function get_fabricare_customer_details($mobile_number)
    {

        $result = $this->db->query('EXEC ' . SERVER_DB . '.dbo.GetCustomerDetails @mobileNo=' . $mobile_number)->row_array();

        if ($result['Status'] == 'Existing') {
            $ret = TRUE;
        } else {
            $ret = FALSE;
        }
        return $ret;
    }

    /**
     * Getting the customer details from the users table if any.
     * @param $mobile_number
     * @return mixed
     */
    public function get_user_details($mobile_number)
    {
        $result = $this->db->select('*')->from('users')->where('mobile_number', $mobile_number)->get()->row_array();
        return $result;
    }

    /**
     * Getting the area details from pincode and area code.
     * @param $pincode
     * @param $area_code
     * @return mixed
     */
    public function get_area_details($pincode, $area_code)
    {
        $details = $this->db->select('*')->from('V_RouteMaster')->where(array('BrandCode' => 'PCT0000001', 'AreaCode' => $area_code, 'Pincode' => $pincode))->get()->row_array();
        return $details;
    }

    /**
     * Register customer into Fabricare directlty from the campaign page.
     * @param $details
     * @return array
     */
    public function register_customer($details)
    {
        $query = "EXEC " . SERVER_DB . ".[dbo].[USP_CreateCustomer_campaign]
	 @NAME ='" . $details['name'] . "'
	,@DELIVERYADDRESS= '" . $details['house'] . "'
	,@DELIVERYCITYCODE= '" . $details['CityCode'] . "'
	,@DELIVERYAREACODE= '" . $details['AreaCode'] . "'
	,@PINCODE= " . $details['pincode'] . "
	,@PERMANANTADDRESS ='" . $details['house'] . "'
	,@PERMANANTCITYCODE = '" . $details['CityCode'] . "'
	,@PERMANANTAREACODE= '" . $details['AreaCode'] . "'
	,@PERMANANTPINCODE =" . $details['pincode'] . "
	,@BRANCHCODE= '" . $details['BranchCode'] . "'
	,@MOBILENO ='" . $details['mobile_number'] . "'
	,@EMAIL ='" . $details['email'] . "'";

        if ($details['dob'] == 'null') {
            $query = $query . ",@DOB =" . $details['dob'];
        } else {
            $query = $query . ",@DOB ='" . $details['dob'] . "'";
        }

        if ($details['gender'] == 'null') {
            $query = $query . ",@GENDER =" . $details['gender'];
        } else {
            $query = $query . ",@GENDER ='" . $details['gender'] . "'";
        }

        

        /*Creating a new DBKit object*/
        $dbkit = new DBKit();

        /*Establish the DB connection*/
        $dbkit->db_connect();

        /*Execute the query*/
        $dbkit->execute($query);

        $result = array();
        /*Getting the resultant row and push the row into the $coupon array.*/
        do {
            while (($row = sqlsrv_fetch_array($dbkit->get_statement()))) {

                array_push($result, $row);
            }
        } while (($ok = sqlsrv_next_result($dbkit->get_statement())));

        $dbkit->close_connection();

        return $result;

    }

}