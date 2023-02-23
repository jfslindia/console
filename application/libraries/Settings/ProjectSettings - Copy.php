<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 8/24/2019
 * Time: 10:13 AM
 */

//Project settings file.
//These configurations can be altered according to the requirements. Only basic configurations are mentioned below.
class ProjectSettings{

    //Protected variable for Codeigniter object.
    protected $CI;
    private $trusted_origins;

    function __construct()
    {
        //Setting up CI object in order to manipulate CI methods/features.
        $this->CI =& get_instance();

        //Testing or not
        $is_testing = FALSE;
        define("IS_TESTING", $is_testing);


        //Current environment. This can be UAT/Live/QA/CLOUD.
        $environment ='CLOUD';
        define("CURRENT_ENVIRONMENT", $environment);

        $server_type='server';

        if($server_type=='local'){
            $current_server_ip="http://localhost/";
        }else{
            $current_server_ip="https://jfslcloud.in/";
        }

        define("CURRENT_SERVER_TYPE",$server_type);
        define("CURRENT_SERVER_IP",$current_server_ip);



        //Defining the DB IP address based on current environment.
        
        if (CURRENT_ENVIRONMENT == 'QA') {
            $db_server_ip = '192.168.5.28';
        } else if (CURRENT_ENVIRONMENT == 'UAT') {
            $db_server_ip = '192.168.5.75';
        } else if (CURRENT_ENVIRONMENT == 'LIVE') {
            $db_server_ip = '192.168.5.78';
        } else {
            //Cloud server
            $db_server_ip = '15.207.227.247';
        }

        define("DB_SERVER_IP",$db_server_ip);

        //Setting the Database for Live or UAT/QA.
        if (IS_TESTING) {
            //UAT/QA settings
            $server_db = 'JFSL_UAT';
            $local_db = 'Mobile_JFSL_UAT';
        } else {
            //Live settings
            $server_db = 'JFSL';
            $local_db = 'Mobile_JFSL';
        }

        //Defining the global variables
        define("LOCAL_DB", $local_db);
        define("SERVER_DB", $server_db);



    }

}