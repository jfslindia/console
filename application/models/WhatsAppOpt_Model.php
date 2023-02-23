<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 8/24/2019
 * Time: 11:31 AM
 */

class WhatsAppOpt_Model extends CI_Model
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
     * Getting the details of WhatsApp opt out from WhatsAppDetails table from a unique code of the customer.
     * @param $unique_code -- Unique code of a customer.
     * @return $details -- Row array containing the details corresponding to that unique code.
     */
    public function get_details($unique_code){

        $details=$this->db->select('*')->from('WhatsAppDetails')->where('UniqueCode',$unique_code)->get()->row_array();
        return $details;
    }

    /**
     * A public method for updating the customer opt in/out status in the WhatsAppDetails table.
     * @param $customer_id
     * @param $opt
     * @return mixed
     */
    public function update_opt($customer_id,$opt){
        $update_data=array('Opt'=>$opt);
        $update=$this->db->where('CustomerCode',$customer_id)->update('WhatsAppDetails',$update_data);

        return $update;

    }

    /**
     * Updating the opt in /opt out status to the Fabricare.
     * @param $customer_id
     * @param $opt
     * @return mixed
     */
    public function update_opt_in_fabricare($customer_id,$opt){
        //Updating the Fabricare by calling the sp.

        //created by 2--Android,3-ios,4-web
        $query="EXEC ".SERVER_DB.".dbo.Whatsapp_CustomerOptInAndOptOut @CustomerCode='".$customer_id."',@WhatToOptin=".$opt.',@CreatedBy=4';

        $dbkit=new DBKit();

        $dbkit->db_connect();

        $dbkit->execute($query);

        $result = array();

        do {
            while (($row = sqlsrv_fetch_array($dbkit->get_statement()))) {
                array_push($result, $row);
            }
        } while (($ok = sqlsrv_next_result($dbkit->get_statement())));

        $dbkit->close_connection();

        return $result;
    }

    /**
     * Getting the Fabricare customer details. It contains the WhatsApp optin/out status.
     * @param $customer_code
     * @return mixed
     */
    public function get_customer_details($customer_code){
        $result=$this->db->query('EXEC '.SERVER_DB.'.dbo.GetCustomerDetails @CustomerCode='.$customer_code)->row_array();
        return $result;

    }

}