<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php



class QC_SUI_Model extends CI_Model
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


    //Checking the cipher code validity.
    public function check_validity($code){

    	$validity=$this->db->select('*')->from('QC_Customer_Details')->where('UniqueCode',$code)->get()->row_array();
    	return $validity;
    }

    //Retrieving all QC logs for a customer.
    public function get_logs($customer_id){

        $open_logs=$this->db->query("EXEC ".SERVER_DB.".dbo.GetQCDetailsFromMobile  @Type = 3,@CustomerCode = '".$customer_id."'")->result_array();
        $approved_logs=$this->db->query("EXEC ".SERVER_DB.".dbo.GetQCDetailsFromMobile  @Type = 2,@CustomerCode = '".$customer_id."'")->result_array();
        $rejected_logs=$this->db->query("EXEC ".SERVER_DB.".dbo.GetQCDetailsFromMobile  @Type = 1,@CustomerCode = '".$customer_id."'")->result_array();

        $logs['open_logs']=$open_logs;
        $logs['approved_logs']=$approved_logs;
        $logs['rejected_logs']=$rejected_logs;

        return $logs;
    }


     /**
     * Getting the garment details from the QC Master view
     * @param $tag_no -- Unique tag Number
     * @return mixed
     */
    public function get_garment_details($tag_no){
        $result=$this->db->select('*')->from('V_QC_Master')->where('TagNo',$tag_no)->get()->row_array();
        return $result;
    }


    //Getting a log data based on tag no.
    // public function get_log_data($tag_no){

    //     $query="EXEC ".SERVER_DB.".dbo.GetQCDetailsFromMobileByTagNo @TagNo='".$tag_no."'";

    //     /*Creating a new DBKit object*/
    //     $dbkit=new DBKit();

    //     /*Establish the DB connection*/
    //     $dbkit->db_connect();

    //     /*Execute the query*/
    //     $dbkit->execute($query);


    //     $result = array();
    //     /*Getting the resultant row and push the row into the $coupon array.*/
    //     do {
    //         while (($row = sqlsrv_fetch_array($dbkit->get_statement()))) {

    //             array_push($result, $row);
    //         }
    //     } while (($ok = sqlsrv_next_result($dbkit->get_statement())));

    //     $dbkit->close_connection();

    //     return $result;
    // }
     //Getting a log data based on tag no.
    public function get_log_data($tag_no)
    {

        $query = "EXEC " . SERVER_DB . ".dbo.GetGarmentDetailsFromMobileByTagNo @TagNo='" . $tag_no . "'";
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    //Method to get a cipher of a customer.
    public function get_cipher_code($customer_id){
        $code=$this->db->select('UniqueCode')->from('QC_Customer_Details')->where('CustomerCode',$customer_id)->get()->row_array();
        return $code;
    }



    /**
     * Updating the QC status of a garment to Fabricare via Stored Procedure.
     * @param $tag_no
     * @parma $customer_id
     * @param $customer_response
     * @param $remarks
     * @param $qc_user_id
     * @param $image_link_1
     * @param $image_link_2
     * @param $image_link_3
     * @return array
     */
    public function update_qc_status_in_fabricare($tag_no,$customer_id,$customer_response,$remarks,$qc_user_id,$image_link_1,$image_link_2,$image_link_3){

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

        $query="EXEC ".SERVER_DB.".dbo.UpdateQCStatusFromMobile @TagNo='".$tag_no."',@CustomerCode='".$customer_id."',@QCStatus=".$customer_response.",@AssignedTo=2,@BranchType=2,@CreatedBy=-1,@QCRemark='".$remarks."',@Image1='".$image_link_1."',@Image2='".$image_link_2."',@Image3='".$image_link_3."'";



        /*Creating a new DBKit object*/
        $dbkit=new DBKit();

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

    /**
     * Getting the image links from the Tag No
     * @param $tag_no
     * @return mixed
     */
    public function get_image_links($tag_no){
        $result=$this->db->distinct()->select('*')->from('QC_Images')->where('TagNo',$tag_no)->limit(3)->get()->result_array();
        return $result;
    }
}