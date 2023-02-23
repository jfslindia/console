<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 3/13/2019
 * Time: 9:28 AM
 */

class Notify_Model extends CI_Model
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
     * Getting the android/ios gcmid of a customer by passing the customer code.
     * @param $customer_id
     * @return mixed
     */
    public function get_gcmids_of_a_customer($customer_id){

        $gcmids=$this->db->select('fabricspa_android_gcmid,fabricspa_ios_gcmid')->from('Users')->where('customer_id',$customer_id)->get()->row_array();
        return $gcmids;
        
    }


}