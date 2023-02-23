<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 8/24/2019
 * Time: 10:13 AM
 */

//Admin console settings file.
//These configurations can be altered according to the requirements. Only basic configurations are mentioned below.
class ConsoleSettings{

    //Protected variable for Codeigniter object.
    protected $CI;
    private $trusted_origins;
    function __construct()
    {
        //Setting up CI object in order to manipulate CI methods/features.
        $this->CI =& get_instance();

        define("ADMIN", $this->CI->session->userdata('admin'));
        define("ADMIN_USERNAME", $this->CI->session->userdata('username'));
        define("ADMIN_PREVILIGE", $this->CI->session->userdata('previlige'));
        /*IMPORTANT: Inorder to compatible with older PHP versions, constant array declaration needs to be in the form of serialized array.*/
        define("ADMIN_ACCESSIBLE_PAGES", serialize(explode(',', $this->CI->session->userdata('accessible_pages'))));
        define("ADMIN_ACCESSIBLE_PAGES_STRING", $this->CI->session->userdata('accessible_pages'));

        $pages['SEARCH_PANEL'] = array('NAME' => 'SEARCH PANEL', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/search', 'ICON' => 'search');
        $pages['FEEDBACKS'] = array('NAME' => 'FEEDBACKS', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/feedbacks', 'ICON' => 'commenting');
        //$pages['INDEX_USERS'] = array('NAME' => 'USER DETAILS', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/users', 'ICON' => 'users');
        //$pages['INDEX_ORDERS'] = array('NAME' => 'ORDER DETAILS', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/orders', 'ICON' => 'cart');
        //$pages['INDEX_REGISTRATIONS'] = array('NAME' => 'USER REGISTRATIONS', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/registrations', 'ICON' => 'world');
        $pages['OFFERS'] = array('NAME' => 'OFFERS', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/offers', 'ICON' => 'tag');
        //$pages['STORES']=array('NAME'=>'OFFERS','LINK'=>base_url().'console/stores'); --> No longer using this func because of the integration with Fabricare.
        $pages['API_PREFERENCES'] = array('NAME' => 'API PREFERENCES', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/api_preferences', 'ICON' => 'settings');
        $pages['SEND_NOTIFICATIONS'] = array('NAME' => 'SEND NOTIFICATIONS', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/send_notifications', 'ICON' => 'star');
        $pages['DCR'] = array('NAME' => 'DCR', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/dcr', 'ICON' => 'mail');


        /* $qc_sub_menu[0]['SUB_MENU']='USERS';
         $qc_sub_menu[0]['SUB_MENU_ICON']='users';
         $qc_sub_menu[0]['SUB_MENU_LINK']=base_url() . 'console/qc_users';*/

        /*$qc_sub_menu[1]['SUB_MENU']='LOGS';
        $qc_sub_menu[1]['SUB_MENU_ICON']='list';
        $qc_sub_menu[1]['SUB_MENU_LINK']=base_url() . 'console/qc_users';//change TODO*/

        $pages['QC'] = array(
            'NAME' => 'QC', 'LINK' => base_url() . 'console/qc_logs',
            'ICON' => 'search',
            'SUB_MENU' => FALSE,
            'SUB_MENU_ARRAY' => []

        );

        $qa_sub_menu[0]['SUB_MENU'] = 'QA USERS';
        $qa_sub_menu[0]['SUB_MENU_ICON'] = 'users';
        $qa_sub_menu[0]['SUB_MENU_LINK'] = base_url() . 'console/qa_users';

        $qa_sub_menu[1]['SUB_MENU'] = 'FINISHED BY USERS';
        $qa_sub_menu[1]['SUB_MENU_ICON'] = 'users';
        $qa_sub_menu[1]['SUB_MENU_LINK'] = base_url() . 'console/qa_finished_by_users';

        $qa_sub_menu[2]['SUB_MENU'] = 'REASONS';
        $qa_sub_menu[2]['SUB_MENU_ICON'] = 'list';
        $qa_sub_menu[2]['SUB_MENU_LINK'] = base_url() . 'console/qa_reasons';

        $qa_sub_menu[3]['SUB_MENU'] = 'LOGS';
        $qa_sub_menu[3]['SUB_MENU_ICON'] = 'list';
        $qa_sub_menu[3]['SUB_MENU_LINK'] = base_url() . 'console/qa_logs';

        $pages['QA'] = array(
            'NAME' => 'QA', 'LINK' => base_url() . 'console/qa_logs',
            'ICON' => 'search',
            'SUB_MENU' => TRUE,
            'SUB_MENU_ARRAY' => $qa_sub_menu

        );

        $pages['DCR'] = array('NAME' => 'DCR', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/dcr', 'ICON' => 'mail');


        $payments_sub_menu[0]['SUB_MENU'] = 'TO VERIFY';
        $payments_sub_menu[0]['SUB_MENU_ICON'] = 'credit-card';
        $payments_sub_menu[0]['SUB_MENU_LINK'] = base_url() . 'console/transactions_to_verify';

        $pages['PAYMENTS'] = array(
            'NAME' => 'ZAAKPAY', 'LINK' => '',
            'ICON' => 'credit-card',
            'SUB_MENU' => TRUE,
            'SUB_MENU_ARRAY' => $payments_sub_menu

        );

        $pages['PAYMENT_GATEWAY_CENTER'] = array('NAME' => 'PAYMENT GATEWAY', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/payment_gateway_center', 'ICON' => 'credit-card');
        //$pages['PAYMENT_INCOMPLETE_TRANSACTIONS'] = array('NAME' => 'INCOMPLETE PAYMENT TRANSACTIONS', 'LINK' => base_url() . 'console/incomplete_transactions', 'ICON' => 'credit-card');
        $pages['APPSPA_CAMPAIGN'] = array('NAME' => 'APPSPA CAMPAIGN', 'SUB_MENU' => FALSE, 'LINK' => base_url() . 'console/appspa_campaign', 'ICON' => 'happy');


        /*IMPORTANT: Inorder to compatible with older PHP versions, constant array declaration needs to be in the form of serialized array.*/
        define("PAGES", serialize($pages));

    }

}