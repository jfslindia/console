<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 1/9/2019
 * Time: 10:00 AM
 */

class Survey_Model extends CI_Model
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
     * Getting customer details from the users table.
     * @param $customer_id
     * @return mixed
     */
    public function get_customer_details($customer_id){
        $customer_details=$this->db->select('*')->from('Users')->where('customer_id',$customer_id)->get()->row_array();
        return $customer_details;
    }

    /**
     * Getting the current step of the customer.
     * @param $customer_id
     * @return mixed
     */
    public function get_current_step($customer_id){
        $step=$this->db->select('Step')->from('SurveyTable')->where('CustomerCode',$customer_id)->get()->row_array();
        return $step;
    }

    /**
     * Getting all the answer history of the customer
     * @param $customer_id
     * @return mixed
     */
    public function get_answer_history($customer_id){
        $step=$this->db->select('Data')->from('SurveyData')->where('CustomerCode',$customer_id)->get()->row_array();
        return $step;
    }

    /**
     * Inserting the first answer history of the customer.
     * @param $customer_id
     * @param $new_answer_history
     */
    public function insert_answer($customer_id,$answer_history){
        $data=array('Data'=>$answer_history,'CustomerCode'=>$customer_id);
        $status=$this->db->insert('SurveyData',$data);
        /*Inserting the question step count in the SurveyTable.*/
        $this->db->insert('SurveyTable',array('CustomerCode'=>$customer_id,'Step'=>1));
        return $status;
    }

    /**
     * Updating the answer history of the customer.
     * @param $customer_id
     * @param $new_answer_history
     */
    public function update_answer_history($customer_id,$new_answer_history){
        $data=array('Data'=>$new_answer_history);
        $status=$this->db->where('CustomerCode',$customer_id)->update('SurveyData',$data);

        /*Updating the step value.*/
        $this->db->set('Step', 'Step+1', FALSE);
        $this->db->where('CustomerCode', $customer_id);
        $this->db->update('SurveyTable');

        return $status;
    }
}