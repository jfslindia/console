<?php

/**
 * Created by PhpStorm.
 * User: KP
 * Date: 02-01-2020
 * Time: 23:02
 */
class SendEmail
{
    //Protected variable for Codeigniter object.
    protected $CI;

    protected $username;
    protected $passwd;
    protected $mail_server;

    function __construct()
    {
        //Setting up CI object in order to manipulate CI methods/features.
        $this->CI =& get_instance();

        $this->username = 'Reports@jyothy.com';
        $this->passwd = 'Jyothy@2019';
        $this->mail_server = 'Mail.Jyothy.com';

        $this->CI->load->model('SendMail_Model');
    }

    /**
     * Returing the email configuration.
     * @return array
     */
    public function get_config()
    {
        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $this->mail_server;
        $config['smtp_user'] = $this->username;
        $config['smtp_pass'] = $this->passwd;
        $config['smtp_port'] = 587;

        return $config;
    }



    /**
     * @param $from -- eg: no-reply@fabricspa.com
     * @param $send_to -- emails seperated by commas
     * @param $cc_to -- emails seperated by commas
     * @param $brand -- Fabricspa/Click2Wash
     * @param $title -- Fabricspa/Click2Wash/JFSL..etc
     * @param $subject -- Eg: Daily Collection Report...etc
     * @param $message -- Message body
     * @param $attachment -- File attachment
     */
    public function send_mail($from, $send_to, $cc_to, $brand, $title, $subject, $message, $attachment)
    {

        $this->CI->SendMail_Model->send_email($from, $send_to, $cc_to, $brand, $title, $subject, $message, $attachment);

    }
}