<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * (c) Jyothy Laboratories Ltd
 * Created by Krishna Prasad K
 * Date: 4/5/2017
 * Time: 1:36 PM
 */
class Admin_Controller extends CI_Controller
{
    /**
     *Constructor for the admin controller
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_Model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('PHPReport/PHPExcel');
        $this->load->helper('file');
        $this->load->library("pagination");
        /*Loading the library for decryption*/
        $this->load->library('encrypt');

        //$this->output->enable_profiler(TRUE);
        define("ADMIN", $this->session->userdata('admin'));
        define("ADMIN_USERNAME", $this->session->userdata('username'));
        define("ADMIN_PREVILIGE", $this->session->userdata('previlige'));
        /*IMPORTANT: Inorder to compatible with older PHP versions, constant array declaration needs to be in the form of serialized array.*/
        define("ADMIN_ACCESSIBLE_PAGES", serialize(explode(',', $this->session->userdata('accessible_pages'))));
        define("ADMIN_ACCESSIBLE_PAGES_STRING", $this->session->userdata('accessible_pages'));


        $is_testing = $this->config->item('is_testing');
        define("IS_TESTING", $is_testing);

        //Setting the Database for Live/UAT/QA.
        if (IS_TESTING) {
            /*These configurations are same for UAT & QA*/
            $server_db = 'JFSL_UAT';
            $local_db = 'Mobile_JFSL_UAT';
        } else {
            $server_db = 'JFSL';
            $local_db = 'Mobile_JFSL';
        }

        //Defining the global variables
        define("LOCAL_DB", $local_db);
        define("SERVER_DB", $server_db);


        $pages['SEARCH_PANEL'] = array('NAME' => 'SEARCH PANEL','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/search', 'ICON' => 'search');
        $pages['FEEDBACKS'] = array('NAME' => 'FEEDBACKS','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/feedbacks', 'ICON' => 'commenting');
        $pages['INDEX_USERS'] = array('NAME' => 'USER DETAILS','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/users', 'ICON' => 'users');
        $pages['INDEX_ORDERS'] = array('NAME' => 'ORDER DETAILS','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/orders', 'ICON' => 'cart');
        $pages['INDEX_REGISTRATIONS'] = array('NAME' => 'USER REGISTRATIONS','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/registrations', 'ICON' => 'world');
        $pages['OFFERS'] = array('NAME' => 'OFFERS','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/offers', 'ICON' => 'tag');
        //$pages['STORES']=array('NAME'=>'OFFERS','LINK'=>base_url().'console/stores'); --> No longer using this func because of the integration with Fabricare.
        $pages['API_PREFERENCES'] = array('NAME' => 'API PREFERENCES','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/api_preferences', 'ICON' => 'settings');
        $pages['SEND_NOTIFICATIONS'] = array('NAME' => 'SEND NOTIFICATIONS','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/send_notifications', 'ICON' => 'star');
        $pages['DCR'] = array('NAME' => 'DCR','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/dcr', 'ICON' => 'mail');

        $qc_sub_menu[0]['SUB_MENU']='USERS';
        $qc_sub_menu[0]['SUB_MENU_ICON']='users';
        $qc_sub_menu[0]['SUB_MENU_LINK']=base_url() . 'console/qc_users';

        /*$qc_sub_menu[1]['SUB_MENU']='LOGS';
        $qc_sub_menu[1]['SUB_MENU_ICON']='list';
        $qc_sub_menu[1]['SUB_MENU_LINK']=base_url() . 'console/qc_users';//change TODO*/

        $pages['QC'] = array(
            'NAME' => 'QC', 'LINK' => base_url() . 'console/qc_users',
            'ICON' => 'search',
            'SUB_MENU'=>TRUE,
            'SUB_MENU_ARRAY'=>$qc_sub_menu

        );

        $qa_sub_menu[0]['SUB_MENU']='LOGS';
        $qa_sub_menu[0]['SUB_MENU_ICON']='list';
        $qa_sub_menu[0]['SUB_MENU_LINK']=base_url() . 'console/qa_logs';

        $pages['QA'] = array(
            'NAME' => 'QA', 'LINK' => base_url() . 'console/qa_logs',
            'ICON' => 'search',
            'SUB_MENU'=>TRUE,
            'SUB_MENU_ARRAY'=>$qa_sub_menu

        );

        $pages['PAYMENTS'] = array('NAME' => 'PAYMENTS','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/payments', 'ICON' => 'credit-card');
        $pages['PAYMENT_GATEWAY_CENTER'] = array('NAME' => 'PAYMENT GATEWAY CENTER','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/payment_gateway_center', 'ICON' => 'credit-card');
        //$pages['PAYMENT_INCOMPLETE_TRANSACTIONS'] = array('NAME' => 'INCOMPLETE PAYMENT TRANSACTIONS', 'LINK' => base_url() . 'console/incomplete_transactions', 'ICON' => 'credit-card');
        $pages['APPSPA_CAMPAIGN'] = array('NAME' => 'APPSPA CAMPAIGN','SUB_MENU'=>FALSE, 'LINK' => base_url() . 'console/appspa_campaign', 'ICON' => 'happy');


        /*IMPORTANT: Inorder to compatible with older PHP versions, constant array declaration needs to be in the form of serialized array.*/
        define("PAGES", serialize($pages));


    }

    /****
     *
     * VIEWS
     *
     * */

    /**
     *Index method points to the home method
     */
    public function index()
    {

        $this->home();
    }

    /**
     *Home method
     */
    public function home()
    {

        if (ADMIN) {
            if (ADMIN_PREVILIGE == 'root') {

                $this->load->view('Admin/Base/admin_header');
                $this->load->view('Admin/Pages/admin_dashboard');
                $this->load->view('Admin/Base/admin_footer');

            } else if (ADMIN && (!ADMIN_PREVILIGE || ADMIN_PREVILIGE != 'root')) {


                /*Checking the validity of the access based on user accessibility.*/
                $page = 'SEARCH_PANEL';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    $this->load->view('Admin/Base/admin_header');
                    $this->load->view('Admin/Pages/admin_home');
                    $this->load->view('Admin/Base/admin_footer');
                } else {

                    $this->load->view('Admin/Base/admin_header');
                    $this->load->view('Admin/Pages/admin_search_panel');
                    $this->load->view('Admin/Base/admin_footer');
                }


            }
        } else {
            $this->login();
        }
    }

    /**
     *Loading the login form
     */
    public function login()
    {

        $this->load->view('Admin/Base/admin_login_header');
        $this->load->view('Admin/Pages/admin_login');
        $this->load->view('Admin/Base/admin_footer');

    }

    /**
     *To load the home dashboard view
     */
    public function admin_dashboard()
    {

        if (ADMIN_PREVILIGE == 'root') {

            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_dashboard');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *To load the users details view
     */
    public function admin_users()
    {

        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'INDEX_USERS';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_users');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *To load the users registration details view
     */
    public function admin_registration()
    {

        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'INDEX_REGISTRATIONS';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }

            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_reg', TRUE);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *To load the users' order details view
     */
    public function admin_orders()
    {

        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'INDEX_ORDERS';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }

            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_orders');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *To load the users' order details view
     */
    public function admin_store_address()
    {

        if (ADMIN) {
            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {


                /*Checking the validity of the access based on user accessibility.*/
                $page = 'STORE_ADDRESSES';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }


            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_store_address');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *To load the the search panel view
     */
    public function admin_search_panel()
    {
        if (ADMIN) {
            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {


                /*Checking the validity of the access based on user accessibility.*/
                $page = 'SEARCH_PANEL';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }

            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_search_panel');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }

    /**
     *To load the the payment search panel view
     */
    public function admin_payments()
    {
        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'PAYMENTS';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }

            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_payments');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }

    /**
     *To load the the payment search panel view
     */
    public function admin_offers()
    {
        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'OFFERS';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }


            $offers = $this->Admin_Model->get_offers();
            $data = array('offers' => $offers);
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_offers', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }

    /**
     *To load the the payment search panel view
     */
    public function admin_feedbacks()
    {
        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'FEEDBACKS';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }


            $feedbacks = $this->Admin_Model->get_feedbacks();
            $data = array('feedbacks' => $feedbacks);
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_feedbacks', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }

    /**
     *To load the preference view
     */
    public function admin_api_preferences()
    {

        /*Only admin can access this page*/
        if (ADMIN && ADMIN_PREVILIGE == 'root') {
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_api_preferences');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *To load the send notification view
     */
    public function admin_send_notifications()
    {

        /*Admin can access this page, but others needed to be checked.*/
        if (ADMIN && ADMIN_PREVILIGE != 'root') {

            /*Checking the validity of the access based on user accessibility.*/
            $page = 'SEND_NOTIFICATIONS';
            $validiy = $this->check_accessibility($page);

            if ($validiy == FALSE) {
                echo 'invalid access';
                exit(0);
            }
        }

        $cities = $this->get_store_cities('Fabricspa');

        $data = array('cities' => $cities);


        $this->load->view('Admin/Base/admin_header');
        $this->load->view('Admin/Pages/admin_send_notifications', $data);
        $this->load->view('Admin/Base/admin_footer');

    }

    /**
     *To load the send notification view
     */
    public function admin_payment_gateway_center()
    {


        /*Only admin can access this page*/
        if (ADMIN && ADMIN_PREVILIGE == 'root') {

            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_payment_gateway_center');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }

    /**
     *To load the send notification view
     */
    public function admin_incomplete_transactions()
    {


        /*Only admin can access this page*/
        if (ADMIN && ADMIN_PREVILIGE == 'root') {

            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_incomplete_transactions');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }

    /**
     *To load the change password view
     */
    public function admin_change_password()
    {

        if (ADMIN) {
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_change_password');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *To load the appspa campaign stats page. (From March 9,2019 to 31st March 2019)
     */
    public function admin_appspa_campaign()
    {

        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'APPSPA_CAMPAIGN';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }

            $campaign_stats = array('stats' => $this->Admin_Model->load_appspa_campaign_stats());

            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_appspa_campaign', $campaign_stats);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }


    /**
     *Settings of Daily Collection Reports
     */
    public function admin_dcr()
    {

        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'DCR';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }

            $result = $this->Admin_Model->get_brands();
            $stores = $this->Admin_Model->get_all_stores();
            $data = array('brands' => $result, 'stores' => $stores);
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_dcr', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *Settings of QC process
     */
    public function admin_qc_users()
    {

        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'QC';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }


            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_qc_users');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *Settings of QA process
     */
    public function admin_qa_logs()
    {

        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'QA';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }


            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_qa_logs');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }


    /**
     *To load the admin creation view
     */
    public function admin_creation()
    {
        $this->load->view('Admin/Base/admin_login_header');
        $this->load->view('Admin/Pages/admin_creation');
        //$this->load->view('templates/login_footer');
    }


    /**
     *Login process method
     */
    public function login_pro()
    {

        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required',
                array('required' => 'You must provide a %s.')
            );

            /*checking form validation*/
            if ($this->form_validation->run() == TRUE) {

                $data = array(
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('password')

                );


                $result = $this->Admin_Model->admin_login($data);


                if ($result) {

                    $decrypted_password = $this->encrypt->decode($result['password']);

                    if ($data['password'] == $decrypted_password) {


                        //Setting up the admin array to be saved in the session.
                        $to_session = array('admin' => TRUE, 'username' => $data['username'], 'previlige' => $result['previlige'], 'accessible_pages' => $result['accessible_pages']);

                        $this->session->set_userdata($to_session);

                        $data = array(
                            "status" => "success",
                            "result" => $result
                        );

                    } else {
                        $data = array(
                            "status" => "failed",
                            "result" => $result
                        );
                    }

                } else {
                    $data = array(
                        "status" => "invalid",
                        "result" => $result
                    );
                }


                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
            } else {


                $errors = $this->form_validation->error_array();

                if (array_key_exists('username', $errors)) {
                    $data = array(
                        'status' => 'error',
                        'message' => $errors['username']
                    );
                } else if (array_key_exists('password', $errors)) {
                    $data = array(
                        'status' => 'error',
                        'message' => $errors['password']
                    );
                }

                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
            }
        } else {
            echo('No direct script access is allowed');
        }
    }

    /**
     *
     */
    public function logout()
    {


        $this->session->sess_destroy();
        //calling the login.

        $this->login();


    }

    /**
     *Checking the user accessibility to a particular page.
     */
    private function check_accessibility($page)
    {


        if (in_array($page, unserialize(ADMIN_ACCESSIBLE_PAGES))) {
            $validity = TRUE;
        } else {
            $validity = FALSE;

        }
        return $validity;
    }

    /**
     *Function for searching
     */
    public function search_panel()
    {


        if (ADMIN) {

            $request = json_decode(file_get_contents('php://input'), true);

            $search_with = $request['search_with'];

            $search_text = $request['search_text'];

            $result = TRUE;



            switch ($search_with) {

                case 'mobile_number' :
                    $result = $this->Admin_Model->search_with_mobile($search_text);
                    break;

                case 'email' :
                    $result = $this->Admin_Model->search_with_email($search_text);
                    break;

                case 'customer_id' :
                    $result = $this->Admin_Model->search_with_customer_id($search_text);
                    break;

                case 'order_id' :
                    $result = $this->Admin_Model->search_with_order_id($search_text);
                    break;

                case 'booking_id' :
                    $result = $this->Admin_Model->search_with_booking_id($search_text);
                    break;

                case 'name' :
                    $result = $this->Admin_Model->search_with_name($search_text);
                    break;

                default:
                    echo 'No!';
                    break;


            }


            if (sizeof($result['user_details']) >= 1) {
                for ($i = 0; $i < sizeof($result['user_details']); $i++) {
                    $result['user_details'][$i]['date'] = date("d-m-Y H:i:s", strtotime($result['user_details'][$i]['date']));
                }
            }




            if (sizeof($result['order_details']) >= 1) {

                for ($i = 0; $i < sizeof($result['order_details']); $i++) {

                    if(array_key_exists('date',$result['order_details'][$i])){

                        $result['order_details'][$i]['date'] = date("d-m-Y H:i:s", strtotime($result['order_details'][$i]['date']));
                        $result['order_details'][$i]['pick_up_date'] = date("d-m-Y H:i:s", strtotime($result['order_details'][$i]['pick_up_date']));
                    }

                }
            }


            if ($result['user_details']) {
                $data = array('status' => 'success', 'result' => $result);
            } else {
                $data = array('status' => 'failed', 'result' => $result);
            }

            echo json_encode($data);

        }else{
            echo 'No direct script allowed';
        }

    }


    /**
     *Getting the registration details of customers
     */
    public function get_registration_details()
    {
        if ($this->input->is_ajax_request()) {
            $brand = '';
            if ($this->input->post('brand_value') == 'Fabricspa') {
                $brand = 'PCT0000001';
            } else if ($this->input->post('brand_value') == 'Click2Wash') {
                $brand = 'PCT0000014';
            }
            $data = array(
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'location' => $this->input->post('location_value'),
                'brand_code' => $brand,
                'first_time_coupon' => $this->input->post('first_time_coupon')
            );


            $result = $this->Admin_Model->get_registration_details($data);

            for ($i = 0; $i < sizeof($result); $i++) {
                $result[$i]['date'] = date("d-m-Y H:i:s", strtotime($result[$i]['date']));
            }


            if ($result == TRUE) {


                $data = array(
                    "status" => "success",
                    "result" => $result
                );

            } else {
                $data = array(
                    "status" => "failed",
                    "result" => $result
                );

            }
            //Either you can print value or you can send value to database
            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }

    /**
     *Get payment/transaction details of a user from the DB.
     */
    public function get_payment_details()
    {

        $customer_code = $this->input->post('customer_code');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $payment_id = $this->input->post('payment_id');
        $count = $this->input->post('count');
        $generate_report = $this->input->post('generate_report');
        $limit = 40;
        if ($count == 0) {
            $offset = 0;

        } else {
            $offset = $limit * $count;

        }


        if ($start_date == ' 00:00:00.000') {
            $start_date = NULL;
        }
        if ($end_date == ' 00:00:00.000') {
            $end_date = NULL;

        }


        $payment_details = $this->Admin_Model->get_payment_details($limit, $offset, $customer_code, $start_date, $end_date, $payment_id);


        if ($generate_report == 1) {
            $limit = FALSE;
            $offset = FALSE;
            $report = $this->generate_payment_report($payment_details);
        } else {
            $report = NULL;
        }

        if ($report) {
            $file_link = base_url() . $report;
        } else {
            $file_link = NULL;
        }

        if ($payment_details) {
            $data = array('status' => 'success', 'payment_details' => $payment_details, 'offset' => $offset, 'report_file' => $file_link);
        } else {
            $data = array('status' => 'failed');
        }
        echo json_encode($data);

    }

    public function generate_payment_report($payment_details)
    {
        $objPHPExcel = new PHPExcel();


        $total_results = sizeof($payment_details);
        $total_amount = 0;


        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Customer Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'EGRN');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'DCN');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Payment ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Payment Mode');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Coupon Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Payment Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Payment Gateway Status');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Invoice Number');
        $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Branch Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Remarks');


        $j = 2;

        for ($i = 0; $i < $total_results; $i++) {


            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $payment_details[$i]['TransactionDate']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $payment_details[$i]['CustomerCode']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $payment_details[$i]['EGRNNo']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $payment_details[$i]['DCNo']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $payment_details[$i]['PaymentID']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $payment_details[$i]['PaymentMode']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $payment_details[$i]['CouponCode']);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $j, $payment_details[$i]['PaymentAmount']);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $j, $payment_details[$i]['PaymentGatewayStatusDescription']);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $j, $payment_details[$i]['InvoiceNo']);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $j, $payment_details[$i]['BranchCode']);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $j, $payment_details[$i]['Remarks']);

            $j++;
        }


        //Merging deposit slip id column
        //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:A' . $j);

        //Merging name column.
        //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:F' . $j);

        $objPHPExcel->getActiveSheet()->setTitle('Payment_Details_Report');

        // Set properties

        $objPHPExcel->getProperties()->setCreator('JFSL - Console');
        //$objPHPExcel->getProperties()->setLastModifiedBy();
        $objPHPExcel->getProperties()->setTitle("Payment Details Report");
        $objPHPExcel->getProperties()->setSubject("Payment Details");
        $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $date = date('Y-m-d-H-i-s');
        //Final file name would be;
        $file_name = 'PaymentDetails_' . $date . '.xlsx';

        $target_file = 'excel_reports/PaymentDetails/' . $file_name;

        // Auto size columns for each worksheet
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $sheet = $objPHPExcel->getActiveSheet();
        $styleArray = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '2F4F4F')
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $sheet->getDefaultStyle()->applyFromArray($styleArray);

        $objWriter->save($target_file);
        return $target_file;
    }

    public function test_read_excel()
    {
        $objPHPExcel = new PHPExcel();
        $inputFileName = 'Fabspa_C2W_Orders_07-Sep-2017-14-53-42.xlsx';
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($inputFileName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        print_r($sheetData[1]);

    }

    /**
     * Method to generate the excel file of user registrations
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Writer_Exception
     */
    public function get_registration_details_excel()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->input->post('res');
            // Create new PHPExcel object

            $objPHPExcel = new PHPExcel();


            $total_results = sizeof($res);


            $objPHPExcel->setActiveSheetIndex(0);

            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'NAME');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'EMAIL');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'MOBILE');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'SIGN UP SOURCE');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'BRAND CODE');


            $j = 2;

            for ($i = 0; $i < $total_results; $i++) {

                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $res[$i]['date']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $res[$i]['id']);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $res[$i]['name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $res[$i]['email']);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $res[$i]['mobile_number']);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $res[$i]['sign_up_source']);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $res[$i]['BrandCode']);

                $j++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Registrations');


            //Determining file name based on brand_code
            $i = 0;
            $f_cout = 0;
            $c_count = 0;
            while ($i < $total_results) {

                if ($res[$i]['BrandCode'] == 'PCT0000001') {
                    $f_cout += 1;
                } else if ($res[$i]['BrandCode'] == 'PCT0000014') {
                    $c_count += 1;
                }

                $i++;
            }

            if ($f_cout == $total_results)
                $folder_name = 'Fabricspa';
            else if ($c_count == $total_results)
                $folder_name = 'Click2Wash';
            else
                $folder_name = 'Fabspa_C2W';

            // Set properties

            $objPHPExcel->getProperties()->setCreator(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setLastModifiedBy(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setTitle($folder_name . " Registration Details");
            $objPHPExcel->getProperties()->setSubject("User Registration Details");
            $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $date = date('Y-m-d-H-i-s');
            //Final file name would be;
            $file_name = $folder_name . '_Registration_' . $date . '.xlsx';

            $target_file = 'excel_reports/' . $folder_name . '/' . $file_name;

            $save_status = $objWriter->save($target_file);


            $data = array(

                "link" => $target_file,
                'status' => $save_status
            );


            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }


    /**
     *Method for getting the details of users
     */
    public function get_users_details()
    {

        if ($this->input->is_ajax_request()) {

            $data = array(
                'search_option' => $this->input->post('search_option'),
                'brand_option' => $this->input->post('brand_option'),
                'search_keyword' => $this->input->post('search_keyword')

            );

            $result = $this->Admin_Model->get_users_details($data);

            if ($result == TRUE) {


                $data = array(
                    "status" => "success",
                    "result" => $result
                );

            } else {
                $data = array(
                    "status" => "failed",
                    "result" => $result
                );

            }
            //Either you can print value or you can send value to database
            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }

    /**
     *Method for getting the order details
     */
    public function get_orders_details()
    {
        if ($this->input->is_ajax_request()) {

            $brand = '';
            if ($this->input->post('brand_value') == 'Fabricspa') {
                $brand = 'PCT0000001';
            } else if ($this->input->post('brand_value') == 'Click2Wash') {
                $brand = 'PCT0000014';
            }
            $data = array(
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'location' => $this->input->post('location_value'),
                'brand_code' => $brand,
                'coupon_details' => $this->input->post('coupon_details'),
                'order_details' => $this->input->post('order_details')
            );

            $result = $this->Admin_Model->get_orders_details($data);

            if ($result == TRUE) {


                $data = array(
                    "status" => "success",
                    "result" => $result
                );

            } else {
                $data = array(
                    "status" => "failed",
                    "result" => $result
                );

            }
            //Either you can print value or you can send value to database
            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }


    /**
     * Generating Excel file from the output of get_orders_details
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Writer_Exception
     */
    public function get_orders_details_excel()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->input->post('res');
            // Create new PHPExcel object

            $objPHPExcel = new PHPExcel();


            $total_results = sizeof($res);


            $objPHPExcel->setActiveSheetIndex(0);

            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'BOOKING ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'USER ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'NAME');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'MOBILE');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'EMAIL');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'BRAND CODE');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'SERVICE TYPE');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'STATUS');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'WASH TYPE');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'PICK UP DATE');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'PICK UP ADDRESS');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'PINCODE');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'LOCATION');
            $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'COUPON');
            $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'PICK UP SOURCE');
            $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'AREA');
            $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'ROUTE CODE');
            $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'REMARKS');
            $objPHPExcel->getActiveSheet()->SetCellValue('T1', 'CAMPAIGN INFO');


            $j = 2;

            for ($i = 0; $i < $total_results; $i++) {

                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $res[$i]['date']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $res[$i]['id']);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $res[$i]['user_id']);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $res[$i]['name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $res[$i]['mobile_number']);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $res[$i]['email']);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $res[$i]['BrandCode']);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $j, $res[$i]['service_type']);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $j, $res[$i]['status']);
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $j, $res[$i]['wash_type']);
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $j, $res[$i]['pick_up_date']);
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $j, $res[$i]['pick_up_address']);
                $objPHPExcel->getActiveSheet()->SetCellValue('M' . $j, $res[$i]['pincode']);
                $objPHPExcel->getActiveSheet()->SetCellValue('N' . $j, $res[$i]['location']);
                $objPHPExcel->getActiveSheet()->SetCellValue('O' . $j, $res[$i]['coupon']);
                $objPHPExcel->getActiveSheet()->SetCellValue('P' . $j, $res[$i]['pick_up_source']);
                $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $j, $res[$i]['Area']);
                $objPHPExcel->getActiveSheet()->SetCellValue('R' . $j, $res[$i]['RouteCode']);
                $objPHPExcel->getActiveSheet()->SetCellValue('S' . $j, $res[$i]['remarks']);
                $objPHPExcel->getActiveSheet()->SetCellValue('T' . $j, $res[$i]['campaign_info']);


                $j++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Orders');


            //Determining file name based on brand_code
            $i = 0;
            $f_cout = 0;
            $c_count = 0;
            while ($i < $total_results) {

                if ($res[$i]['BrandCode'] == 'PCT0000001') {
                    $f_cout += 1;
                } else if ($res[$i]['BrandCode'] == 'PCT0000014') {
                    $c_count += 1;
                }

                $i++;
            }

            if ($f_cout == $total_results)
                $folder_name = 'Fabricspa';
            else if ($c_count == $total_results)
                $folder_name = 'Click2Wash';
            else
                $folder_name = 'Fabspa_C2W';

            // Set properties

            $objPHPExcel->getProperties()->setCreator(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setLastModifiedBy(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setTitle($folder_name . " Orders Details");
            $objPHPExcel->getProperties()->setSubject("User Orders Details");
            $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $date = date('Y-m-d-H-i-s');
            //Final file name would be;
            $file_name = $folder_name . '_Orders_' . $date . '.xlsx';

            $target_file = 'excel_reports/' . $folder_name . '/' . $file_name;

            $save_status = $objWriter->save($target_file);


            $data = array(

                "link" => $target_file,
                'status' => $save_status
            );


            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }


    //Load URLs


    /**
     *Function for registering the admin
     */
    public function admin_creation_pro()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('username', 'Username', 'required');

            $this->form_validation->set_rules('password', 'Password', 'required',
                array('required' => 'You must provide a %s.')
            );

            /*checking form validation*/
            if ($this->form_validation->run() == TRUE) {

                $data = array(
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('password')

                );

                /*Checking for previous details. Checking the entered data is already in the table or not.*/

                $result = $this->Admin_Model->check_admin($data);

                if ($result == FALSE) {

                    /*Loading Encryption library*/
                    $this->load->library('encrypt');

                    /*Creating an encrypted password from user password*/
                    $encrypted_password = $this->encrypt->encode($data['password']);

                    $new_user = array(
                        'username' => $data['username'],
                        'password' => $encrypted_password
                    );

                    $result = $this->Admin_Model->add_admin($new_user);

                    if ($result == TRUE) {

                        $data = array(
                            "status" => "success",
                            "result" => $result
                        );

                    } else {

                        $data = array(
                            "status" => "failed",
                            "result" => $result
                        );
                    }


                } else if ($result == TRUE) {
                    $data = array(
                        "status" => "old_user",
                        "result" => $result
                    );

                }

                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));


            } else {

                //Generating the form validation errors into an array.
                $errors = $this->form_validation->error_array();

                if (array_key_exists('username', $errors)) {
                    $data = array(
                        'status' => 'error',
                        'message' => $errors['username']
                    );

                } else if (array_key_exists('password', $errors)) {
                    $data = array(
                        'status' => 'error',
                        'message' => $errors['password']
                    );
                }

                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
            }
        } else {
            echo('No direct script access is allowed');
        }

    }

    /**
     *Function for changing the admin password
     */
    public function change_password()
    {

        if (ADMIN) {
            $request = json_decode(file_get_contents('php://input'), true);

            $data = array(
                'username' => ADMIN_USERNAME,
                'old_password' => $request['old_password'],
                'new_password' => $request['new_password']
            );


            /*Loading Encryption library*/
            $this->load->library('encrypt');


            $result = $this->Admin_Model->change_password_check_password($data);

            $decrypted_password = $this->encrypt->decode($result[0]['password']);

            if ($data['old_password'] == $decrypted_password) {

                /*Creating an encrypted password from user password*/
                $encrypted_password = $this->encrypt->encode($data['new_password']);

                $new_user = array(
                    'username' => ADMIN_USERNAME,
                    'password' => $encrypted_password
                );

                $result = $this->Admin_Model->change_password($new_user);

                if ($result) {


                    $data = array(
                        'status' => 'success',
                        'result' => $result
                    );


                } else {

                    $data = array(
                        'status' => 'failed',
                        'result' => $result
                    );

                }

            } else {

                $data = array(
                    'status' => 'wrong_password',
                    'password' => $decrypted_password,
                    'result' => $result
                );

            }

            echo json_encode($data);

        } else {
            echo('No direct script access is allowed');
        }

    }

    /**
     *Getting the user registration details on the dashboard
     */
    public function dashboard_registers()
    {
        if ($this->input->is_ajax_request()) {


            $result = $this->Admin_Model->dashboard_registers();

            if ($result) {

                $data = array(
                    'status' => 'success',
                    'result' => $result
                );
            } else {

                $data = array(
                    'status' => 'failed',
                    'result' => $result
                );

            }

            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }

    /**
     *Getting the order details on the dashboard
     */
    public function dashboard_orders()
    {
        if ($this->input->is_ajax_request()) {


            $result = $this->Admin_Model->dashboard_orders();

            if ($result) {

                $data = array(
                    'status' => 'success',
                    'result' => $result
                );
            } else {

                $data = array(
                    'status' => 'failed',
                    'result' => $result
                );

            }

            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }

    /**
     *Getting the total user stat on the dashboard
     */
    public function dashboard_total_users()
    {

        if ($this->input->is_ajax_request()) {

            $result = $this->Admin_Model->dashboard_total_users();

            if ($result) {

                $data = array(
                    'status' => 'success',
                    'result' => $result
                );
            } else {

                $data = array(
                    'status' => 'failed',
                    'result' => $result
                );

            }

            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }

    /**
     *Getting the source of registration of Fabricspa users on the dashboard
     */
    public function dashboard_fab_source()
    {

        if ($this->input->is_ajax_request()) {


            $result = $this->Admin_Model->dashboard_fab_source();

            if ($result) {

                $data = array(
                    'status' => 'success',
                    'result' => $result
                );
            } else {

                $data = array(
                    'status' => 'failed',
                    'result' => $result
                );

            }

            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }

    /**
     *Getting the source of registration of Click2Wash users on the dashboard
     */
    public function dashboard_cw_source()
    {

        if ($this->input->is_ajax_request()) {
            $result = $this->Admin_Model->dashboard_cw_source();

            if ($result) {

                $data = array(
                    'status' => 'success',
                    'result' => $result
                );
            } else {

                $data = array(
                    'status' => 'failed',
                    'result' => $result
                );

            }

            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }


    /**
     * Loading the API Preference data from the preference JSON file
     * @return mixed
     */
    public function load_preference_data()
    {
        if ($this->input->is_ajax_request()) {

            $pref = $this->input->post('pref');
            $brand = $this->input->post('brand_option');

            if ($pref == 'schedule_reminder') {

                if ($brand == 'fabricspa') {

                    //Loading the preference JSON file
                    $preference_file = read_file('preference/fabricspa/schedule_reminder.json');
                    $preference = json_decode($preference_file);

                } else if ($brand == 'click2wash') {

                    //Loading the preference JSON file
                    $preference_file = read_file('preference/click2wash/schedule_reminder.json');
                    $preference = json_decode($preference_file);
                }

            } else if ($pref == 'order_confirmation') {

                if ($brand == 'fabricspa') {

                    //Loading the preference JSON file
                    $preference_file = read_file('preference/fabricspa/order_confirmation.json');
                    $preference = json_decode($preference_file);

                } elseif ($brand == 'click2wash') {

                    //Loading the preference JSON file
                    $preference_file = read_file('preference/click2wash/order_confirmation.json');
                    $preference = json_decode($preference_file);
                }

            } else if ($pref == 'delivery_confirmation') {

                if ($brand == 'fabricspa') {

                    //Loading the preference JSON file
                    $preference_file = read_file('preference/fabricspa/delivery_confirmation.json');
                    $preference = json_decode($preference_file);

                } elseif ($brand == 'click2wash') {

                    //Loading the preference JSON file
                    $preference_file = read_file('preference/click2wash/delivery_confirmation.json');
                    $preference = json_decode($preference_file);
                }

            }

            echo json_encode($preference);
        } else {
            echo('No direct script access is allowed');
        }
    }

    /**
     *Method for saving the API preference. Changes are saved in respective JSON pref files.
     */
    public function save_preference()
    {

        if ($this->input->is_ajax_request()) {
            /*Creating unix time stamp*/
            $date = date('Y-m-d H:i:s');

            //Loading preferences for Schedule Reminder
            if ($this->input->post("pref") == 'schedule_reminder') {

                $send_to = $this->input->post("send_to");
                $brand = $this->input->post("brand");
                $location = $this->input->post("location");
                $medium = $this->input->post("medium");
                $title = $this->input->post("title");
                $image_url = $this->input->post("image_url");
                $payload = $this->input->post("payload");

                $message = $this->input->post("message");
                $preference_info = array('Updated On' => $date, 'Updated By' => ADMIN_USERNAME);

                $save_data = array('send_to' => $send_to, 'location' => $location, 'medium' => $medium,
                    'title' => $title, 'image_url' => $image_url, 'payload' => $payload, 'message' => $message, 'preference_info' => $preference_info);

                $new_pref = json_encode($save_data);

                if (write_file('preference/' . $brand . '/schedule_reminder.json', $new_pref)) {

                    /*The send_to key is brand independant. This should be same in all preferences.*/
                    /*So ensuring the saving of send_to in both preference files.(Because normally only one brand specific
                    pref file is saved at the moment.*/
                    /*Overwriting the send_to key in both pref files to avoid loss of the 'send_to' value.*/

                    /*Overwriting the fabricspa pref file.*/
                    $jsonString = file_get_contents('preference/fabricspa/schedule_reminder.json');
                    $data = json_decode($jsonString, true);
                    $data['send_to'] = $send_to;

                    $newJsonString = json_encode($data);
                    file_put_contents('preference/fabricspa/schedule_reminder.json', $newJsonString);

                    /*Overwriting the click2wash pref file.*/
                    $jsonString = file_get_contents('preference/click2wash/schedule_reminder.json');
                    $data = json_decode($jsonString, true);
                    $data['send_to'] = $send_to;

                    $newJsonString = json_encode($data);
                    file_put_contents('preference/click2wash/schedule_reminder.json', $newJsonString);


                    $data = array(
                        'status' => 'success'
                    );
                } else {
                    $data = array(
                        'status' => 'failed'
                    );
                }


            } //Loading preferences for Order Confirmation
            else if ($this->input->post("pref") == 'order_confirmation') {

                $brand = $this->input->post("brand");
                $location = $this->input->post("location");
                $medium = $this->input->post("medium");
                $title = $this->input->post("title");
                $image_url = $this->input->post("image_url");
                $payload = $this->input->post("payload");

                $message = $this->input->post("message");

                $preference_info = array('Updated On' => $date, 'Updated By' => ADMIN_USERNAME);

                $save_data = array('location' => $location, 'medium' => $medium,
                    'title' => $title, 'image_url' => $image_url, 'payload' => $payload, 'message' => $message, 'preference_info' => $preference_info);

                $new_pref = json_encode($save_data);
                if (write_file('preference/' . $brand . '/order_confirmation.json', $new_pref)) {
                    $data = array(
                        'status' => 'success'
                    );
                } else {
                    $data = array(
                        'status' => 'failed'
                    );
                }


            } //Loading preferences for Delivery Confirmation
            else if ($this->input->post("pref") == 'delivery_confirmation') {

                $brand = $this->input->post("brand");
                $location = $this->input->post("location");
                $medium = $this->input->post("medium");
                $title = $this->input->post("title");
                $image_url = $this->input->post("image_url");
                $payload = $this->input->post("payload");

                $message = $this->input->post("message");

                $preference_info = array('Updated On' => $date, 'Updated By' => ADMIN_USERNAME);

                $save_data = array('location' => $location, 'medium' => $medium,
                    'title' => $title, 'image_url' => $image_url, 'payload' => $payload, 'message' => $message, 'preference_info' => $preference_info);

                $new_pref = json_encode($save_data);
                if (write_file('preference/' . $brand . '/delivery_confirmation.json', $new_pref)) {
                    $data = array(
                        'status' => 'success'
                    );
                } else {
                    $data = array(
                        'status' => 'failed'
                    );
                }

            }

            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }


    /**
     *Method to get GCMIDs of users via mobile number or email address
     */
    public function get_gcmids()
    {

        if ($this->input->is_ajax_request()) {

            $device = $this->input->post('device');
            $via = $this->input->post('via');
            $list = $this->input->post('list');
            $brand = $this->input->post('brand');

            $gcmids = $this->Admin_Model->get_gcmids($via, $list, $brand, $device);

            if ($gcmids) {
                $data = array(
                    'status' => 'success',
                    'gcmids' => $gcmids
                );
            } else {
                $data = array(
                    'status' => 'failed'
                );
            }
            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }

    /**
     *Method to get GCMIDs of all users
     */
    public function get_all_gcmids()
    {

        if ($this->input->is_ajax_request()) {

            $device = $this->input->post('device');
            $brand = $this->input->post('brand');
            $location = $this->input->post('location');
            $start = $this->input->post('start');
            $limit = $this->input->post('limit');

            $gcmids = $this->Admin_Model->get_all_gcmids($brand, $device, $location, $start, $limit);

            if ($gcmids) {
                $data = array(
                    'status' => 'success',
                    'gcmids' => $gcmids
                );
            } else {
                $data = array(
                    'status' => 'failed'
                );
            }
            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    }


    /**
     *Send the notification to the corresponding GCMIDs
     */
    public function send_gcmids()
    {
        if ($this->input->is_ajax_request()) {

            $device = $this->input->post('device');
            $to_sent = $this->input->post('to_sent');
            $title = $this->input->post('title');
            $brand = $this->input->post('brand');
            $image_url = $this->input->post('image_url');
            $message = $this->input->post('message');


            if ($brand == 'Fabricspa')
                $brand_code = 'PCT0000001';
            else if ($brand == 'Click2Wash')
                $brand_code = 'PCT0000014';

            $library_params = array('brand_code' => $brand_code);
            $this->load->library('push_notification/firebase', $library_params);
            $this->load->library('push_notification/push');
            // print_r($to_sent);

            $total_users = sizeof($to_sent);


            $firebase = new Firebase($library_params);
            //$firebase = $this->firebase;
            $push = new Push();

            // optional payload
            $payload = array();
            $payload['sound'] = 'default';
            $payload['brand_code'] = $brand_code;
            $payload['type'] = 'normal';

            // notification title
            //$title = 'Test';

            // notification message
            //$message =  'Test Test';

            // push type - single user / topic
            $push_type = 'multiple';

            // whether to include to image or not
            if (isset($image_url))
                $include_image = TRUE;
            else
                $include_image = FALSE;


            $push->setTitle($title);
            $push->setMessage($message);
            if ($include_image) {
                $push->setImage($image_url);
            } else {
                $push->setImage('');
            }
            $push->setIsBackground(FALSE);
            $push->setPayload($payload);

            $json = '';
            $response = '';
            $regIds = array();

            //Getting GCMIDs from Pref file or database. If pref GCMID list are empty, then select from DB.
            for ($i = 0; $i < $total_users; $i++) {
                if ($to_sent[$i] != '' || !empty($to_sent[$i]))
                    array_push($regIds, $to_sent[$i]);
            }


            //print_r($to_sent);

            if ($device == 'android') {
                if ($push_type == 'topic') {
                    $json = $push->getPush();
                    $response = $firebase->sendToTopic('global', $json);
                } else if ($push_type == 'multiple') {
                    $json = $push->getPush();
                    $response = $firebase->sendMultiple($regIds, $json);
                }
            } else if ($device == 'ios') {
                if ($push_type == 'topic') {
                    $json = $push->getPush();

                    $response = $firebase->sendToTopic('global', $json);
                } else if ($push_type == 'multiple') {
                    //$json = $push->getPush();
                    $data = $push->getPushIOS();
                    $response = $firebase->sendMultipleIOS($regIds, $push->getPushNotificationIOS(), $data);
                }
            } else {
                $response = FALSE;
            }


            if ($response) {

                $data = array(
                    'status' => 'success',
                    'response' => $response,
                    'to_sent' => $to_sent,
                    'size' => $total_users
                );
            } else {
                $data = array(
                    'status' => 'failed',
                    'response' => $response
                );
            }

            echo json_encode($data);

        } else {
            echo('No direct script access is allowed');
        }
    }


    /**
     *Send the notification to the corresponding GCMIDs
     */
    public function send_automated_gcmids()
    {
        if ($this->input->is_ajax_request()) {

            $device = $this->input->post('device');
            $to_sent = [];
            $title = $this->input->post('title');
            $brand = $this->input->post('brand');
            $image_url = $this->input->post('image_url');
            $message = $this->input->post('message');
            $location = $this->input->post('location');
            $start = $this->input->post('start');
            $limit = $this->input->post('limit');


            if ($brand == 'Fabricspa')
                $brand_code = 'PCT0000001';
            else if ($brand == 'Click2Wash')
                $brand_code = 'PCT0000014';
            else
                $brand_code = NULL;


            $gcmids = $this->Admin_Model->get_all_gcmids($brand, $device, $location, $start, $limit);

            for ($j = 0; $j < sizeof($gcmids); $j++) {
                array_push($to_sent, $gcmids[$j]['gcmid']);
            }


            $library_params = array('brand_code' => $brand_code);
            $this->load->library('push_notification/firebase', $library_params);
            $this->load->library('push_notification/push');


            $total_users = sizeof($to_sent);


            $firebase = new Firebase($library_params);
            //$firebase = $this->firebase;
            $push = new Push();

            // optional payload
            $payload = array();
            $payload['sound'] = 'default';
            $payload['brand_code'] = $brand_code;
            $payload['type'] = 'normal';

            // notification title
            //$title = 'Test';

            // notification message
            //$message =  'Test Test';

            // push type - single user / topic
            $push_type = 'multiple';

            // whether to include to image or not
            if (isset($image_url))
                $include_image = TRUE;
            else
                $include_image = FALSE;


            $push->setTitle($title);
            $push->setMessage($message);
            if ($include_image) {
                $push->setImage($image_url);
            } else {
                $push->setImage('');
            }
            $push->setIsBackground(FALSE);
            $push->setPayload($payload);

            $json = '';
            $response = '';
            $regIds = array();

            //Getting GCMIDs from Pref file or database. If pref GCMID list are empty, then select from DB.
            for ($i = 0; $i < $total_users; $i++) {
                if ($to_sent[$i] != '' || !empty($to_sent[$i]))
                    array_push($regIds, $to_sent[$i]);
            }




              if ($device == 'android') {
                  if ($push_type == 'topic') {
                      $json = $push->getPush();
                      $response = $firebase->sendToTopic('global', $json);
                  } else if ($push_type == 'multiple') {
                      $json = $push->getPush();
                      $response = $firebase->sendMultiple($regIds, $json);
                  }
              } else if ($device == 'ios') {
                  if ($push_type == 'topic') {
                      $json = $push->getPush();

                      $response = $firebase->sendToTopic('global', $json);
                  } else if ($push_type == 'multiple') {
                      //$json = $push->getPush();
                      $data = $push->getPushIOS();
                      $response = $firebase->sendMultipleIOS($regIds, $push->getPushNotificationIOS(), $data);
                  }
              } else {
                  $response = FALSE;
              }


            if ($response) {

                $data = array(
                    'status' => 'success',
                    'response' => $response,
                    'to_sent' => $to_sent,
                    'size' => $total_users
                );
            } else {
                $data = array(
                    'status' => 'failed',
                    'response' => $response
                );
            }


            echo json_encode($data);

        } else {
            echo('No direct script access is allowed');
        }
    }


//**********************************************************************************************
    /*--ADD/EDIT/DELETE STORES..--*/
////////////////////////////////////////////////////////////////////////////////////////////////

    //Public method accessed by Admin angular app
    public function get_stores()
    {

        $stores = $this->Admin_Model->get_stores();
        echo json_encode($stores);

    }

    public function save_store()
    {

        if (ADMIN) {
            $request = json_decode(file_get_contents('php://input'), true);
            $saved_store = array();

            $mode = $request['mode'];
            $store_title = $request['title'];
            $store_address = $request['address'];
            $store_email = $request['email'];
            $store_contactno = $request['contactno'];
            $store_map = $request['map'];
            $store_brand = $request['brand'];
            $store_location = $request['location'];


            if (array_key_exists('id', $request)) {
                $store_id = $request['id'];
            } else {
                $store_id = NULL;
            }

            if (array_key_exists('contactno', $request)) {
                if (!$request['contactno']) {
                    if ($store_brand == 'Fabricspa') {
                        if ($store_location == 'Bangalore') {
                            $store_contactno = '080 4664 4664';
                        } else if ($store_location == 'Mumbai') {
                            $store_contactno = '080 4664 4664';
                        } else {
                            $store_contactno = '';
                        }
                    } else if ($store_brand == 'Click2Wash') {
                        if ($store_location == 'Bangalore') {
                            $store_contactno = '080 4661 4661';
                        } else if ($store_location == 'Mumbai') {
                            $store_contactno = '022 4610 9999';
                        } else {
                            $store_contactno = '';
                        }
                    }
                } else {
                    $store_contactno = $request['contactno'];
                }
            }


            if (array_key_exists('map', $request)) {
                if (!$request['map']) {
                    $store_price_link = '';
                }
            }

            if (array_key_exists('link', $request)) {
                if (!$request['link']) {
                    $store_price_link = NULL;
                } else {
                    $store_price_link = $request['link'];
                }
            } else {
                $store_price_link = NULL;
            }

            $store = array(

                'title' => $store_title,
                'address' => $store_address,
                'email' => $store_email,
                'contactno' => $store_contactno,
                'map' => $store_map,
                'location' => $store_location,
                'brand' => $store_brand,
                'price_list_link' => $store_price_link,
                'created_by' => ADMIN_USERNAME
            );


            if ($mode == 'add') {


                $saved_store = $this->Admin_Model->save_store($store);

                $data = $saved_store;


            } else if ($mode == 'edit') {

                $updated_store = $this->Admin_Model->update_store($store, $store_id);

                $data = $updated_store;

            }


            echo json_encode($data);

        } else {
            $this->home();
        }

    }


    public function delete_stores()
    {

        if (ADMIN) {
            $delete_status = FALSE;
            $stores = json_decode(file_get_contents('php://input'), true);

            $delete_status = $this->Admin_Model->delete_stores($stores['selected_stores']);

            if ($delete_status) {
                $data = array('status' => 'success', 'message' => 'issue(s) deleted');
                echo json_encode($data);
            } else {
                $data = array('status' => 'failed', 'message' => 'Delete failed');
                echo json_encode($data);
            }

        } else {
            $this->home();
        }

    }


    /*Updated stores SP*/
    /**
     *For getting all the cities along with the city name and city code of the currently active stores
     */
    public function get_cities_sp()
    {
        $request = json_decode(file_get_contents('php://input'), true);
        $brand_code = $request['BrandCode'];


        $cities = $this->Admin_Model->get_stores_cities_sp($brand_code);

        //Manipulating the results for changing the first letters to capitals
        for ($i = 0; $i < sizeof($cities); $i++) {
            $cities[$i]['CITYNAME'] = ucwords(strtolower($cities[$i]['CITYNAME']));
        }

        if ($cities)
            $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully retrieved cities', 'cities' => $cities);
        else
            $data = array('status' => 'failed', 'status_code' => '4', 'message' => 'Failed cities found');
        echo json_encode($data);
    }

    /**
     *Retrieving stores from the stored procedure directly from the Fabricare.
     */
    public function get_stores_sp()
    {
        $request = json_decode(file_get_contents('php://input'), true);
        $city_code = $request['CityCode'];
        $brand_code = $request['BrandCode'];
        $stores = $this->Admin_Model->get_stores_sp($city_code, $brand_code);
        //Manipulating the results for changing the first letters to capitals
        for ($i = 0; $i < sizeof($stores); $i++) {
            if (in_array($stores[$i]['BRANCHCODE'], $this->block_stores()) == FALSE) {
                $stores[$i]['BRANCHNAME'] = ucwords(strtolower($stores[$i]['BRANCHNAME']));
                $stores[$i]['BRANCHADDRESS'] = ucwords(strtolower($stores[$i]['BRANCHADDRESS']));
            } else {
                //Removing the store is that branch codes falls into the blocked criteria. Array will be re-indexed here.
                array_splice($stores, $i, 1);
            }
        }

        if ($stores) {
            $data = array('status' => 'success', 'status_code' => '1', 'message' => 'Successfully retrieved cities', 'stores' => $stores);

        } else {
            $data = array('status' => 'failed', 'status_code' => '4', 'message' => 'No stores found');

        }
        echo json_encode($data);
    }

    /**
     *A private method for blocking stores based on custom constraints.
     */
    private function block_stores()
    {
        $blocked_branch_codes = array('SPEC000001');
        return $blocked_branch_codes;
    }


//**********************************************************************************************
    /*--ADD/EDIT/DELETE dcr_users..--*/
////////////////////////////////////////////////////////////////////////////////////////////////

    //Public method accessed by Admin angular app
    public function get_dcr_users()
    {

        /*Loading the library for decryption*/
        $this->load->library('encrypt');


        $dcr_users = $this->Admin_Model->get_dcr_users();

        echo json_encode($dcr_users);

    }


    /**
     *Saving a DCR user from the console.
     */
    public function save_dcr_user()
    {

        if (ADMIN) {
            $request = json_decode(file_get_contents('php://input'), true);
            $saved_dcr_user = array();

            $mode = $request['mode'];
            $dcr_user_name = $request['name'];
            $dcr_user_contactno = $request['contactno'];
            $dcr_user_privilege = $request['privilege'];

            $dcr_user_passwd = $request['passwd'];
            $dcr_user_branches = json_encode($request['stores[]']);
            $dcr_user_branch_names = json_encode($request['store_names[]']);


            if (array_key_exists('id', $request)) {
                $dcr_user_id = $request['id'];
            } else {
                $dcr_user_id = NULL;
            }


            /*Loading Encryption library*/
            $this->load->library('encrypt');


            /*Creating unix time stamp*/
            $date = date('Y-m-d H:i:s');


            $dcr_user = array(
                'Date' => $date,
                'Name' => $dcr_user_name,
                'Phone' => $dcr_user_contactno,
                'Privilege' => $dcr_user_privilege,
                'Password' => $dcr_user_passwd,
                'CreatedBy' => ADMIN_USERNAME,
                'Branches' => $dcr_user_branches,
                'BranchNames' => $dcr_user_branch_names
            );


            if ($mode == 'add') {


                $saved_dcr_user = $this->Admin_Model->save_dcr_user($dcr_user);

                $data = $saved_dcr_user;


            } else if ($mode == 'edit') {

                $updated_dcr_user = $this->Admin_Model->update_dcr_user($dcr_user, $dcr_user_id);

                $data = $updated_dcr_user;

            }


            echo json_encode($data);

        } else {
            $this->home();
        }

    }


    public function delete_dcr_users()
    {

        if (ADMIN) {
            $delete_status = FALSE;
            $dcr_users = json_decode(file_get_contents('php://input'), true);

            $delete_status = $this->Admin_Model->delete_dcr_users($dcr_users['selected_dcr_users']);

            if ($delete_status) {
                $data = array('status' => 'success', 'message' => 'issue(s) deleted');
                echo json_encode($data);
            } else {
                $data = array('status' => 'failed', 'message' => 'Delete failed');
                echo json_encode($data);
            }

        } else {
            $this->home();
        }

    }



    /**
     *Saving a qc user from the console.
     */
    public function save_qc_user()
    {

        if (ADMIN) {
            $request = json_decode(file_get_contents('php://input'), true);
            $saved_qc_user = array();

            $mode = $request['mode'];
            $qc_user_name = $request['name'];
            $qc_user_contactno = $request['contactno'];


            $qc_user_passwd = $request['passwd'];



            if (array_key_exists('id', $request)) {
                $qc_user_id = $request['id'];
            } else {
                $qc_user_id = NULL;
            }


            /*Loading Encryption library*/
            $this->load->library('encrypt');


            /*Creating unix time stamp*/
            $date = date('Y-m-d H:i:s');


            $qc_user = array(
                'Date' => $date,
                'Name' => $qc_user_name,
                'Phone' => $qc_user_contactno,
                'Password' => $qc_user_passwd,
                'CreatedBy' => ADMIN_USERNAME

            );


            if ($mode == 'add') {

                $saved_qc_user = $this->Admin_Model->save_qc_user($qc_user);

                $data = $saved_qc_user;


            } else if ($mode == 'edit') {

                $updated_qc_user = $this->Admin_Model->update_qc_user($qc_user, $qc_user_id);

                $data = $updated_qc_user;

            }


            echo json_encode($data);

        } else {
            $this->home();
        }

    }

    //Public method accessed by Admin angular app
    public function get_qc_users()
    {

        /*Loading the library for decryption*/
        $this->load->library('encrypt');


        $qc_users = $this->Admin_Model->get_qc_users();

        echo json_encode($qc_users);

    }

    public function delete_qc_users()
    {

        if (ADMIN) {
            $delete_status = FALSE;
            $qc_users = json_decode(file_get_contents('php://input'), true);

            $delete_status = $this->Admin_Model->delete_qc_users($qc_users['selected_qc_users']);

            if ($delete_status) {
                $data = array('status' => 'success', 'message' => 'issue(s) deleted');
                echo json_encode($data);
            } else {
                $data = array('status' => 'failed', 'message' => 'Delete failed');
                echo json_encode($data);
            }

        } else {
            $this->home();
        }

    }

    //Public method accessed by Admin angular app
    public function get_qa_logs()
    {

        if(ADMIN){


            $logs = $this->Admin_Model->get_qa_logs();

            $indexedOnly = array();

            foreach ($logs as $row) {
                $indexedOnly[] = array_values($row);
            }

            $result=array (
                'data' =>$indexedOnly
            );

            echo json_encode($result);


        }else{
            echo 'invalid';
        }


    }




    /*For testing purpose only*/

    /**
     *Method for testing
     */
    public function test1()
    {

        //$this->Admin_Model->test();
        $pickupdate = '2017-07-19 00:00:00';
        $datetime = new DateTime();

        $current_day = gmdate("Y-m-d 00:00:00", $datetime->getTimestamp());

        $interval = new DateInterval('P30D');

        $end_day = gmdate("Y-m-d 00:00:00", date_add($datetime, $interval)->getTimestamp());

        echo $current_day;

        if ($pickupdate >= $current_day && $pickupdate < $end_day) {
            echo 'yay!';
        } else {
            echo 'No';
        }


    }

    /**
     *Method for testing
     */
    public function test2()
    {
        $datetime = new DateTime();
        $inter = new DateInterval('P0Y');
        $pickupdate = date_add($datetime, $inter)->getTimestamp();

        $DateBegin = date('Y-m-d');
        $DateEnd = date(strtotime("+15 days"));
        if (($pickupdate > $DateBegin) && ($pickupdate < $DateEnd)) {
            $ret = TRUE;
            echo 'yay';
        } else {
            $ret = FALSE;
            echo 'nooo';
        }


    }


    /**
     *Method for testing
     */
    public function test3()
    {

        $available_time_slots = $this->Admin_Model->test3();

        $time_slots = [];
        $total_time_slots = sizeof($available_time_slots);

        for ($i = 0; $i < $total_time_slots; $i++) {

            $TimeFrom_new_format = date('h:i A', strtotime($available_time_slots[$i]['TimeFrom']));
            $TimeTo_new_format = date('h:i A', strtotime($available_time_slots[$i]['TimeTo']));

            //$time_slot = array('TimeFrom' => $TimeFrom_new_format, 'TimeTo' =>$TimeTo_new_format);
            $time_slot = $TimeFrom_new_format . ' - ' . $TimeTo_new_format;
            array_push($time_slots, $time_slot);

        }

    }

    /**
     *Method for testing
     */
    public function test4()
    {

        $jsonString = file_get_contents('preference/fabricspa/schedule_reminder.json');
        $data = json_decode($jsonString, true);
        $data['send_to'] = 'neww';

        $newJsonString = json_encode($data);
        file_put_contents('preference/fabricspa/schedule_reminder.json', $newJsonString);
    }

    public function stores_test()

    {
        $stores = $this->Admin_Model->get_all_stores();
        $data = array('status' => 'success', 'stores' => $stores);
        echo json_encode($data);
    }

    /**
     *Adding new offers in the offers page
     */
    public function add_offer()
    {

        $request = $this->input->post();

        $title = $request['title'];
        $description = $request['description'];
        $expiry = $request['expiry'];
        $brand_code = $request['brand_code'];
        $offer_image = $request['offer_image'];
        $res = $this->Admin_Model->add_offer($title, $description, $expiry, $brand_code, $offer_image);
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully saved');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to save');
        }
        echo json_encode($data);
    }

    /**
     *Saving offers in the offers page
     */
    public function save_offer()
    {

        $request = $this->input->post();
        $offer_id = $request['offer_id'];
        $title = $request['title'];
        $description = $request['description'];
        $expiry = $request['expiry'];
        $brand_code = $request['brand_code'];
        $res = $this->Admin_Model->save_offer($offer_id, $title, $description, $expiry, $brand_code);
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully saved');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to save');
        }
        echo json_encode($data);
    }

    public function delete_offer()
    {
        $request = $this->input->post();
        $offer_id = $request['offer_id'];
        $res = $this->Admin_Model->delete_offer($offer_id);
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully deleted');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to delete');
        }
        echo json_encode($data);
    }

    public function upload_offer_image()
    {
        $target_dir = "layout/img/offers/";


        $existing_files = array();
        $failed_files = array();
        $moved_files = array();


        $target_file = $target_dir . basename($_FILES["files"]["name"][0]);
        $target_file = str_replace(" ", "", $target_file);


        // Check if file already exists
        if (!file_exists(str_replace(" ", "", $target_file))) {
            // Check file size
            if ($_FILES["files"]["size"][0] > 500000) {
                $error = array('file' => $_FILES["files"]["name"][0], 'error_type' => 'large');
                array_push($failed_files, $error);
            } else {

                $res = move_uploaded_file($_FILES["files"]["tmp_name"][0], str_replace(" ", "", $target_file));
                //$resized_avatar = $this->resize_avatar($_FILES["files"]["tmp_name"][0],$target_file);
                array_push($moved_files, array('file' => str_replace(" ", "", $_FILES["files"]["name"][0]), 'link' => base_url() . $target_file));
            }
        } else {
            array_push($existing_files, array('file' => $_FILES["files"]["name"][0], 'link' => base_url() . $target_file));
        }


        $data = array(

            'moved_files' => $moved_files,
            'failed_files' => $failed_files,
            'existing_files' => $existing_files
        );


        echo json_encode($data);
    }

    //A private method for image compression
    private function compress_image($source_url, $destination_url)
    {

        $info = getimagesize($source_url);

        $width = $info[0];
        $height = $info[1];
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source_url);

        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source_url);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source_url);

        //Resizing the image if the height is above 600px. Max allowed height is 600px.
        if ($info[1] > 600) {
            $aspect_ratio = $width / $height;
            $new_height = 600;
            $new_width = $new_height * $aspect_ratio;

            //Resizing the image resource.
            $resized_image = imagescale($image, $new_width, $new_height, IMG_BICUBIC);
        } else {
            //If height is below 600, $resized_image image resource has the same value as $image image resource.
            $new_width = $width; //$new_width has the original value since the height is below 600px. $new_width is required to calculate the quality of the final compressed image.
            $resized_image = $image;
        }
//Getting the quality value for generating the final image by passing $new_width variable.
        $quality = $this->get_quality($new_width);

        imagejpeg($resized_image, $destination_url, $quality);
        return $destination_url;
    }

//A private function for calculating the quality of the target image based on width of the uploading file.
    private function get_quality($width)
    {

        if ($width >= 1200) {
            $quality = 25;
        } else if ($width >= 800 && $width <= 1199) {
            $quality = 35;
        } else {
            $quality = 50;
        }

        return $quality;
    }

    public function decode()
    {
        if (ADMIN_PREVILIGE == 'root') {
            $this->load->view('Admin/Pages/admin_decode');
        }
    }

    /**
     *Decoding passwd
     */
    public function decode_now()
    {
        if (ADMIN_PREVILIGE == 'root') {
            $encrypted = $this->input->post('encrypted');
            echo $this->encrypt->decode($encrypted);
        }
    }


    /**
     *Search EGRN for payment related details, settlement of transaction etc...
     */
    public function search_payment()
    {

        if (ADMIN && ADMIN_PREVILIGE == 'root') {

            $search_text = $this->input->post('search_text');
            $search_with = $this->input->post('search_with');

            if ($search_with == 'egrn') {
                $customer_details = $this->Admin_Model->get_customer_details_from_egrn($search_text);
                $transaction_info_details = $this->Admin_Model->get_transaction_info_details_from_egrn($search_text);
            } else if ($search_with == 'payment_id') {
                $customer_details = $this->Admin_Model->get_customer_details_from_payment_id($search_text);
                $transaction_info_details = $this->Admin_Model->get_transaction_info_details_from_payment_id($search_text);
            } else {
                $customer_details = [];
                $transaction_info_details = [];
            }
            /*Pushing all the payment ids into an array to get the corresponding details in the transaction payment info table.*/
            $payment_ids = [];
            if ($transaction_info_details) {
                foreach ($transaction_info_details as $details) {
                    array_push($payment_ids, $details['PaymentId']);
                }
            }

            /*Initiating the transaction payment info details array.*/
            $transaction_payment_info_details = [];

            if ($payment_ids) {
                $transaction_payment_info_details = $this->Admin_Model->get_transaction_payment_info_details($payment_ids);
            }

            $data = array('customer_details' => $customer_details, 'transaction_info_details' => $transaction_info_details,
                'transaction_payment_info_details' => $transaction_payment_info_details);
            if ($data['transaction_info_details'] || $data['customer_details']) {
                $result = array('status' => 'success', 'message' => 'succesfully retrieved the result', 'result' => $data);
            } else {
                $result = array('status' => 'failed', 'message' => 'failed to retrieve the result');
            }

            echo json_encode($result);
        } else {
            echo 'invalid';
        }


    }

    /**
     *Getting the unsettled orders of a customer.
     */
    public function show_unsettled_orders()
    {
        if (ADMIN && ADMIN_PREVILIGE == 'root') {
            $customer_id = $this->input->post('customer_id');
            $brand_code = 'PCT0000001';//Default BrandCode
            $unsettled_orders = $this->Admin_Model->get_unsettled_orders($customer_id, $brand_code);
            if ($unsettled_orders) {
                $result = array('status' => 'success', 'message' => 'succesfully retrieved the result', 'unsettled_orders' => $unsettled_orders);
            } else {
                $result = array('status' => 'failed', 'message' => 'failed to retrieve the result');
            }
            echo json_encode($result);
        } else {
            echo 'invalid';
        }
    }

    /**
     *Updating the DCN in the transaction info table after getting the ID of the entry.
     */
    public function update_dcn()
    {
        if (ADMIN && ADMIN_PREVILIGE == 'root') {
            $id = $this->input->post('id');
            $dcn = $this->input->post('dcn');

            $update_status = $this->Admin_Model->update_dcn($id, $dcn);
            if ($update_status) {
                $result = array('status' => 'success', 'message' => 'succesfully updated the DCN');
            } else {
                $result = array('status' => 'failed', 'message' => 'failed to update the DCN');
            }
            echo json_encode($result);
        } else {
            echo 'invalid';
        }
    }

    /**
     *Adding a new entry in the TransactionPaymentInfo table.
     */
    public function save_transaction_payment_info()
    {
        if (ADMIN && ADMIN_PREVILIGE == 'root') {

            $payment_id = $this->input->post('payment_id');
            $transaction_id = $this->input->post('transaction_id');
            $created_on = date('d-M-Y H:i:s');
            $payable_amount = $this->input->post('amount');
            $payment_gateway_status = $this->input->post('gateway_status');
            $payment_gateway_description = $this->input->post('gateway_description');
            $payment_mode = $this->input->post('payment_mode');
            $pg_trans_id = $this->input->post('pg_trans_id');
            $remarks = $this->input->post('remarks');

            $new_transaction_payment_info_entry = array(

                'PaymentId' => $payment_id,
                'CreatedOn' => $created_on,
                'TransactionId' => $transaction_id,
                'PaymentMode' => $payment_mode,
                'CouponCode' => NULL,
                'PaymentAmount' => (float)$payable_amount,
                'RoundOff' => 0.00,
                'PaymentGatewayStatus' => $payment_gateway_status,
                'PaymentGatewayStatusDescription' => $payment_gateway_description,
                'InvoiceNo' => NULL,
                'Remarks' => $remarks,
                'PgTransId' => $pg_trans_id,
                'BranchCode' => NULL
            );

            $save_status = $this->Admin_Model->save_transaction_payment_info($new_transaction_payment_info_entry);


            if ($save_status) {
                $result = array('status' => 'success', 'message' => 'succesfully saved the data');
            } else {
                $result = array('status' => 'failed', 'message' => 'failed to save the data');
            }

            echo json_encode($result);

        } else {
            echo 'invalid';
        }
    }


    /**
     *A public method for the settling an order.
     */
    public function settle_order()
    {
        if (ADMIN && ADMIN_PREVILIGE == 'root') {
            $transaction_payment_info_id = $this->input->post('id');
            $settle_order = $this->Admin_Model->settle_order($transaction_payment_info_id);
            if ($settle_order) {
                $result = array('status' => 'success', 'message' => 'succesfully settled the order');
            } else {
                $result = array('status' => 'failed', 'message' => 'failed to settle the order');

            }

            echo json_encode($result);
        } else {
            echo 'invalid';
        }
    }


    /**
     * Getting the incomplete transaction details. i.e. Data found in TransactionInfo and not in TransactionPaymentInfo
     *
     */
    public function get_incomplete_transactions()
    {

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $count = $this->input->post('count');
        $generate_report = $this->input->post('generate_report');
        $limit = 40;
        if ($count == 0) {
            $offset = 0;

        } else {
            $offset = $limit * $count;

        }


        if ($start_date == ' 00:00:00.000') {
            $start_date = NULL;
        }
        if ($end_date == ' 00:00:00.000') {
            $end_date = NULL;

        }


        $payment_details = $this->Admin_Model->get_incomplete_payment_details($limit, $offset, $start_date, $end_date);


        if ($generate_report == 1) {
            $limit = FALSE;
            $offset = FALSE;
            $report = $this->generate_incomplete_payments_report($payment_details);
        } else {
            $report = NULL;
        }

        if ($report) {
            $file_link = base_url() . $report;
        } else {
            $file_link = NULL;
        }

        if ($payment_details) {
            $data = array('status' => 'success', 'payment_details' => $payment_details, 'offset' => $offset, 'report_file' => $file_link);
        } else {
            $data = array('status' => 'failed');
        }
        echo json_encode($data);
    }

    public function generate_incomplete_payments_report($payment_details)
    {
        $objPHPExcel = new PHPExcel();


        $total_results = sizeof($payment_details);
        $total_amount = 0;


        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Customer Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'EGRN');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'DCN');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Payment ID');


        $j = 2;

        for ($i = 0; $i < $total_results; $i++) {


            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $payment_details[$i]['TransactionDate']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $payment_details[$i]['CustomerCode']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $payment_details[$i]['EGRNNo']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $payment_details[$i]['DCNo']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $payment_details[$i]['PaymentId']);


            $j++;
        }


        //Merging deposit slip id column
        //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:A' . $j);

        //Merging name column.
        //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:F' . $j);

        $objPHPExcel->getActiveSheet()->setTitle('Incomplete_Payments_Report');

        // Set properties

        $objPHPExcel->getProperties()->setCreator('JFSL - Console');
        //$objPHPExcel->getProperties()->setLastModifiedBy();
        $objPHPExcel->getProperties()->setTitle("Incomplete Payment Details");
        $objPHPExcel->getProperties()->setSubject("Payment transactions that are not completed.");
        $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $date = date('Y-m-d-H-i-s');
        //Final file name would be;
        $file_name = 'IncompletePaymentDetails_' . $date . '.xlsx';

        $target_file = 'excel_reports/IncompletePayments/' . $file_name;

        // Auto size columns for each worksheet
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $sheet = $objPHPExcel->getActiveSheet();
        $styleArray = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '2F4F4F')
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $sheet->getDefaultStyle()->applyFromArray($styleArray);

        $objWriter->save($target_file);
        return $target_file;
    }


    /**
     *For getting all the cities along with the city name and city code of the currently active stores
     * @param $brand -- Default value - Fabricspa. Because all valid cities will be present.
     * @return bool
     */
    private function get_store_cities($brand)
    {
        if (strtoupper($brand) == 'FABRICSPA') {
            $brand_code = 'PCT0000001';
        } else if (strtoupper($brand) == 'CLICK2WASH') {
            $brand_code = 'PCT0000014';
        } else {
            $brand_code = NULL;
        }


        $cities = $this->Admin_Model->get_stores_cities_sp($brand_code);


        //Manipulating the results for changing the first letters to capitals
        for ($i = 0; $i < sizeof($cities); $i++) {
            $cities[$i]['CITYNAME'] = ucwords(strtolower($cities[$i]['CITYNAME']));
        }

        if ($cities)
            return $cities;
        else
            return FALSE;

    }

    /**
     *Updating the user details the user details
     */
    public function save_user(){

        if(ADMIN) {

            $request = json_decode(file_get_contents('php://input'), true);

            $user_id = $request['user_id'];
            $name = $request['name'];
            $mobile_number = $request['phone'];
            $email = $request['email'];

            $update_status=$this->Admin_Model->update_user_details($user_id,$name,$mobile_number,$email);



            if($update_status['status']){
                $data=array('status'=>'success','message'=>'Successfully updated the user details','user_details'=>$update_status['user_details']);
            }else{
                $data=array('status'=>'failed','message'=>'Failed to update the user details');
            }

            echo json_encode($data);

        }else{
            echo 'no direct access allowed';
        }


    }

    /**
     * Pagination logic for the QA logs
     * @return array
     */
    private function log_pagination(){
        $pagination_url='qa_logs';
        $uri_segment=2;

        $config = array();
        $config["base_url"] = base_url() . $pagination_url;

        $config["total_rows"] = $this->Admin_Model->get_logs_count();

        $config["per_page"] = 4;
        $config["uri_segment"] = $uri_segment;
        //$config['num_links'] = 2;
        $choice = $config["total_rows"] / $config["per_page"];
        //$choice=2;
        $num_links=round($choice);
        if($num_links>=2)
            $num_links=2;

        $config["num_links"] = $num_links;
        $config['first_link'] = 'F';
        $config['last_link'] = 'L';
        $config['next_link'] = '>';
        $config['prev_link'] = '<';
        $config['use_page_numbers'] = TRUE;



        $this->pagination->initialize($config);

        $page = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;

        //Setting up the $offset variable to limit the query for pagination
        $offset=$page-1;
        $offset=$offset*$config['per_page'];
        if($offset<0)
            $offset=0;

        //$data["results"] = $this->Harithakam_Model->feed_pagination($config["per_page"], $page, $offset);
        $links = $this->pagination->create_links();

        $pagination=array('links'=>$links,'offset'=>$offset,'per_page'=>$config['per_page']);

        return $pagination;
    }

    public function test()
    {
        $this->load->view('Admin/Base/admin_header');
        $this->load->view('Admin/Pages/admin_data_table');
        $this->load->view('Admin/Base/admin_footer');
    }

    public function test_data(){


        $logs=$this->Admin_Model->get_qa_logs();


        $indexedOnly = array();

        foreach ($logs as $row) {
            $indexedOnly[] = array_values($row);
        }

        $result=array (
            'data' =>$indexedOnly
        );

        echo json_encode($result);
    }


}