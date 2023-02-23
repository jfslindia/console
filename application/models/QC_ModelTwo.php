<?php

/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 7/5/2019
 * Time: 9:42 AM
 */
class QC_ModelTwo extends CI_Model
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
     * Getting the user information for the login
     * @param $mobile_number
     * @param $password
     */
    public function get_user_details($mobile_number, $password)
    {
        $result = $this->db->select('*')->from('QC_Users')->where(array('Phone' => $mobile_number, 'Password' => $password))->get()->row_array();

        return $result;

    }

    /**
     * Getting the garment details from the QC Master view
     * @param $tag_no -- Unique tag Number
     * @return mixed
     */
    public function get_garment_details($tag_no)
    {
        $result = $this->db->select('*')->from('V_QC_Master')->where('TagNo', $tag_no)->get()->row_array();
        return $result;
    }


    /**
     * Save image link and corresponding tag no in the QC_Images table.
     * @param $image_array
     * @return bool
     */
    public function save_qc_images($image_array)
    {

        $save = $this->db->insert('QC_Images', $image_array);

        return $save;
    }

    /**
     * Getting the gcmid of a user
     * @param $customer_id
     * @return mixed
     */
    public function get_fabricspa_gcmid($customer_id)
    {
        $gcmid = $this->db->select('fabricspa_android_gcmid,fabricspa_ios_gcmid')->from('users')->where('customer_id', $customer_id)->get()->row_array();
        return $gcmid;
    }

    /**
     * Saving the customer details into the table.
     * @param $customer_details
     * @return mixed
     */
    public function save_customer_details($customer_details)
    {

        $existing_customer_details = $this->db->select('*')->from('QC_Customer_Details')->where('CustomerCode', $customer_details['CustomerCode'])->get()->row_array();
        //If no customer details present, then insert new details.
        if (!$existing_customer_details)
            $result = $this->db->insert('QC_Customer_Details', $customer_details);
        else
            $result = NULL;
        return $result;
    }

    /**
     * Getting the QC customer details from the QC_Customer_Details table.
     * @param $customer_id
     * @return mixed
     */
    public function get_qc_customer_details($customer_id)
    {
        $customer_details = $this->db->select('*')->from('QC_Customer_Details')->where('CustomerCode', $customer_id)->get()->row_array();
        return $customer_details;
    }

    /**
     * Getting the fabricare data of the customer.
     * @param $customer_code
     */
    public function get_fabricare_customer_details($customer_code)
    {

        $details = $this->db->select('*')->from(SERVER_DB . '..CustomerInfo')->where('CustomerCode', $customer_code)->get()->row_array();
        return $details;
    }

    /**
     * Getting the log data from QC_Logs from a TagNo, i.e.tag_id
     * @param $tag_id
     */
    public function get_log_from_tag_id($tag_id)
    {
        $result = $this->db->select('*')->from('QC_Logs')->where('TagNo', $tag_id)->get()->row_array();
        return $result;
    }


    /**
     * Updating the QC status of a garment to Fabricare via Stored Procedure.
     * @param $tag_no
     * @param $remarks
     * @param $qc_user_id
     * @param $image_link_1
     * @param $image_link_2
     * @param $image_link_3
     * @param $sui_link : Shortened link for Customer user interface for approval/rejection
     * @param $status 1=Rejected, 2=Approved, 3=QCPending
     * @return array
     */
    public function update_qc_status_in_fabricare($tag_no, $remarks, $qc_user_id, $image_link_1, $image_link_2, $image_link_3, $sui_link, $status)
    {

        /* ---SP REFERENCE----
         * PROCEDURE [dbo].[UpdateQCStatusFromMobile] (
	 @TagNo VARCHAR(50)
	,@QCStatus INT -- 1=Rejected, 2=Approved, 3=QCPending
	,@AssignedTo INT --1=CDC, 2=MSS
	,@BranchType INT -- (QCFrom/PostedBy) logged-in user's branchtype id (1=CDC,2=MSS,3=QSS, 4=CustomerCare)
	,@CreatedBy INT -- Logged-in user's UserID
	,@QCRemark VARCHAR(MAX)
	,@FileName VARCHAR(500) = NULL
	)
       */
        //Right now QC status will be 3, i.e. Pending status. Log saved by QC user. Once customer responds to the log it will be changed to either 1 or 2.

        $query = "EXEC " . SERVER_DB . ".dbo.UpdateQCStatusFromMobile @TagNo='" . $tag_no . "',@QCStatus=" . $status . ",@AssignedTo=1,@BranchType=2,@CreatedBy=" . $qc_user_id . ",@QCRemark='" . $remarks . "',@Image1='" . $image_link_1 . "',@Image2='" . $image_link_2 . "',@Image3='" . $image_link_3 . "',@CustomLink='" . $sui_link . "'";

        /*Creating a new DBKit object*/
        $dbkit = new DBKit();

        /*Establish the DB connection*/
        $dbkit->db_connect();

        /*Execute the query*/
        $dbkit->execute($query);

        /*Generating the statement*/
        $statement = $dbkit->get_statement();

        $result['result'] = $dbkit->get_result($statement);

        $dbkit->close_connection();

        $result['query'] = $query;

        return $result;
    }

    //Getting a log data as well as garment details based on tag no.
    public function get_log_data($tag_no)
    {

        $query = "EXEC " . SERVER_DB . ".dbo.GetGarmentDetailsFromMobileByTagNo @TagNo='" . $tag_no . "'";


        /*Creating a new DBKit object*/
        $dbkit = new DBKit();

        /*Establish the DB connection*/
        $dbkit->db_connect();

        /*Execute the query*/
        $dbkit->execute($query);

        /*Generating the statement*/
        $statement = $dbkit->get_statement();

        /*Retrieving the result*/
        $result = $dbkit->get_result($statement);

        /*Closing the DB connection*/
        $dbkit->close_connection();

        return $result;
    }

    /**
     *Getting the reasons from Fabricare
     */
    public function get_reasons_from_fabricare()
    {

        $reasons = $this->db->select('SpecialInstrunctionMSSValue')->from(SERVER_DB . '.dbo.SpecialInstrunctionMSS')->get()->result_array();
        return $reasons;
    }

    /**
     * Updating the QC_Customer_Details table with shortened URL
     * @param $customer_code
     * @param $shortened_url
     */
    public function update_customer_unique_url($customer_code, $shortened_url)
    {
        $data = array('CustomerUniqueLink' => $shortened_url);
        $this->db->where('CustomerCode', $customer_code)->update('QC_Customer_Details', $data);
    }
}