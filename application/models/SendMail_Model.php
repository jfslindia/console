<?php

/**
 * Created by PhpStorm.
 * User: KP
 * Date: 11-01-2020
 * Time: 10:07
 */
class SendMail_Model extends CI_Model
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
     * Getting the store's email address from a branch code.
     * @param $branch_code
     * @return mixed
     */
    public function get_store_email_from_branch_code($branch_code)
    {
        $email_address = $this->db->select('EmailID as email')->from(SERVER_DB . '.dbo.BranchInfo')->where('BranchCode', $branch_code)->get()->row_array();
        return $email_address;
    }


    /**
     * This method works only in cloud server. (192.168.202.5) Does not work in the current Live environment. (78 server/ or 28 QA server)
     * @param $from
     * @param $send_to
     * @param $cc_to
     * @param $brand
     * @param $title
     * @param $subject
     * @param $message
     * @param $attachment
     */
    public function send_email($from, $send_to, $cc_to, $brand, $title, $subject, $message, $attachment)
    {
        if (CURRENT_ENVIRONMENT == 'Cloud' || CURRENT_ENVIRONMENT=='Live') {
            $db = 'Alert_Engine';
        }else {
            $db = 'Alert_Engine_UAT';
        }

        $send_to=str_replace(',',';',$send_to);
        $query = $db.".dbo.alert_process 'JFSL_MOBILE_APP','".$send_to."','null','".$subject."','OFF','".$from."','".$brand."','".$message."','', '',null ,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,0";
        $this->write_sp_response($query);
        $sp = $this->db->query($db.".dbo.alert_process 'JFSL_MOBILE_APP','".$send_to."','null','".$subject."','OFF','".$from."','".$brand."','".$message."','', '',null ,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,0");

    }
    /**
     * //Writing the sp log in a file.
     * @param $result
     */
    private function write_sp_response($data)
    {
        //Writing the stored procedure log in a file.
        $log_day = date('d-M-Y');
        $log_date = date('d-M-Y H:i:s');
        $json_response = json_encode($data);
        $request_time = $_SERVER['REQUEST_TIME'];
        $txt = 'date: ' . $log_date . ', response: ' . $json_response . ', request time: ' . $request_time . PHP_EOL;
        $underline = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $underline = $underline . '-';
        }
        $txt = $txt . $underline;
        $log_file = file_put_contents('sp_response/' . $log_day . '_response.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}