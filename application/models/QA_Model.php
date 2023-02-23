<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 7/5/2019
 * Time: 9:42 AM
 */

class QA_Model extends CI_Model
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
     * Getting the user information for the login
     * @param $mobile_number
     * @param $password
     */
    public function get_user_details($mobile_number, $password)
    {
        $result = $this->db->select('*')->from('QA_Users')->where(array('Phone' => $mobile_number, 'Password' => $password))->get()->row_array();

        return $result;

    }

    /**
     * Getting the user details from user id.
     * @param $user_id
     * @return mixed
     */
    public function get_user_details_from_id($user_id){
        $result = $this->db->select('*')->from('QA_Users')->where(array('Id' => $user_id))->get()->row_array();

        return $result;
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


    /**
     * Saving the log details into QA_Logs table
     * @param $log -- Array of log details
     */
    public function save_log($log){

        $result=$this->db->insert('QA_Logs',$log);
        return $result;
    }

    /**
     * Getting the gcmid of a user
     * @param $customer_id
     * @return mixed
     */
    public function get_fabricspa_gcmid($customer_id){
        $gcmid=$this->db->select('fabricspa_android_gcmid,fabricspa_ios_gcmid')->from('users')->where('customer_id',$customer_id)->get()->row_array();
        return $gcmid;
    }
 /**
     * Getting the essentials for the QA.
     * @return mixed
     */
    public function get_qa_essentials(){
        $finished_by=$this->db->select('*')->from('QA_Finished_By_Users')->get()->result_array();
        $reasons=$this->db->select('*')->from('QA_Reasons')->get()->result_array();
        $result['reasons']=$reasons;
        $result['finished_by']=$finished_by;
        return $result;
    }

}