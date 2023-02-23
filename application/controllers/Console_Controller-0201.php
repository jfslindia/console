<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * (c) Jyothy Laboratories Ltd
 * Created by Krishna Prasad K
 * Date: 4/5/2017
 * Time: 1:36 PM
 * @property Console_Model $Console_Model
 * @property generic $generic
 */
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');
ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288'); // Setting to 512M - for pdo_sqlsrv
class Console_Controller extends CI_Controller
{
   
    /**
     *Constructor for the admin controller
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('Console_Model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('PHPReport/PHPExcel');
        $this->load->helper('file');
        $this->load->library("pagination");
        $this->load->library("javascript");
        $this->load->helper(array('form', 'url'));
        //Loading the project settings
        $this->load->library('Settings/ProjectSettings');
        $this->load->helper('download');
        $this->load->helper('date');

        //Loading the Admin Console settings.
        $this->load->library('Settings/ConsoleSettings');

        //Loading the Generic Functions
        $this->load->library('GenericFunctions/Generic');
        $this->load->library("pagination");
        $this->load->library('Settings/PaymentGatewaySettings');
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
    public function admin_transactions_to_verify()
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
            $this->load->view('Admin/Pages/admin_transactions_to_verify');
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


            $offers = $this->Console_Model->get_offers();
            $data = array('offers' => $offers);
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_offers', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }
      /**
     *To load the the essential popup data for mobileapp
     */
    // public function admin_essentialpopup()
    // {
    //     if (ADMIN) {

    //         /*Admin can access this page, but others needed to be checked.*/
    //         if (ADMIN_PREVILIGE != 'root') {

    //             /*Checking the validity of the access based on user accessibility.*/
    //             $page = 'MOBILEIMAGES';
    //             $validiy = $this->check_accessibility($page);

    //             if ($validiy == FALSE) {
    //                 echo 'invalid access';
    //                 exit(0);
    //             }
    //         }
	   //     $popups = $this->Console_Model->get_essentialpopups();
    //         $states = $this->Console_Model->get_states_sp();
    //         $cities=[];
    //         $cities[0][0] = array(
    //             "citycode"=>"1234",
    //             "cityname"=> "test"
    //         );
    //         $j = 1;
    //         for($i=0;$i<sizeof($states);$i++){    
    //             $cities[$j] = $this->Console_Model->get_state_cities_sp($states[$i]['statecode']);
    //             $j++;
    //         }
    //         $data = array('popups' => $popups,'states' => $states,'cities' => $cities);
    //         $this->load->view('Admin/Base/admin_header');
    //         $this->load->view('Admin/Pages/admin_essentialpopup', $data);
    //         $this->load->view('Admin/Base/admin_footer');	
    //     } else {

    //         $this->home();
    //     }
    // }
      public function admin_essentialpopup()
    {
        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'MOBILEIMAGES';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }


             $banners = array();
            $banners = $this->Console_Model->get_essentialpopups();
            $i=0;
            $filtered_banners = array();
            $duplicates = array();
            $k=0;
            foreach($banners as $b){
                if($i > 0){
                    if($b['state'] != NULL && $b['city'] != NULL ){
                        for($j=0;$j<sizeof($filtered_banners);$j++){
                            if($filtered_banners[$j]['url'] == $b['url'] && $filtered_banners[$j]['state'] == $b['state']){
                                $duplicates[$k] = $b;
                                $k++;
                                $is_exists = 1;
                                break;
                            }else{
                                $is_exists = 0;
                            }
                        }
                        if($is_exists == 0){
                            $filtered_banners[$i] = $b;
                            $i++;
                        }
                    }else{
                        $filtered_banners[$i] = $b;
                            $i++;
                    }
                }else{
                    $filtered_banners[0] = $b;
                    $i++;

                }
            }
            if(sizeof($duplicates) > 0){
                foreach($duplicates as $d){
                    $id = $d['id'];
                    $banners = array_filter($banners, function($x) use ($id) { return $x['id'] != $id; });
                    for($i=0;$i<sizeof($banners);$i++){
                        if($banners[$i]['state'] == $d['state'] && $banners[$i]['url'] == $d['url'] && $banners[$i]['city'] != NULL){
                            $banners[$i]['city'] = $banners[$i]['city'].",".$d['city'];
                            break;
                        }
                    }
                }

            }
            $states = $this->Console_Model->get_states_sp();
            $cities=[];
            $cities[0][0] = array(
                "citycode"=>"1234",
                "cityname"=> "test"
            );
            $j = 1;
            for($i=0;$i<sizeof($states);$i++){    
                $cities[$j] = $this->Console_Model->get_state_cities_sp($states[$i]['statecode']);
                $j++;
            }
            $data = array('popups' => $banners,'states' => $states,'cities' => $cities);
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_essentialpopup', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }
    /**
     *To load the the tip data for mobileapp
     */
    public function admin_tip()
    {
        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'MOBILEIMAGES';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }


            $tip = $this->Console_Model->get_tip();
            if($tip != array())
                $data = array('tip' => $tip);
            else
                $data = array('tip' => "");
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_tip', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }
    /**
     *To load the schedule wash images for mobileapp
     */
    // public function admin_schedulewash_images()
    // {
    //     if (ADMIN) {

    //         /*Admin can access this page, but others needed to be checked.*/
    //         if (ADMIN_PREVILIGE != 'root') {

    //             /*Checking the validity of the access based on user accessibility.*/
    //             $page = 'MOBILEIMAGES';
    //             $validiy = $this->check_accessibility($page);

    //             if ($validiy == FALSE) {
    //                 echo 'invalid access';
    //                 exit(0);
    //             }
    //         }


    //        $states = $this->Console_Model->get_states_sp();
    //         $cities=[];
    //        $cities[0][0] = array(
    //             "citycode"=>"1234",
    //             "cityname"=> "test"
    //         );
    //         $j = 1;
    //         for($i=0;$i<sizeof($states);$i++){
    //             $cities[$j] = $this->Console_Model->get_state_cities_sp($states[$i]['statecode']);
    //             $j++;
    //         }
    //         $wash_image = $this->Console_Model->get_wash_images();
    //         if($wash_image != array())
    //             $data = array('wash_image' => $wash_image,'states' => $states,'cities' => $cities);
    //         else 
    //             $data = array('wash_image' => "",'states' => $states,'cities' => $cities);
    //         $this->load->view('Admin/Base/admin_header');
    //         $this->load->view('Admin/Pages/admin_schedulewash_images', $data);
    //         $this->load->view('Admin/Base/admin_footer');
    //     } else {

    //         $this->home();
    //     }
    // }
    public function admin_schedulewash_images()
    {
        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'MOBILEIMAGES';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }
            $states = $this->Console_Model->get_states_sp();
            $cities=[];
            $cities[0][0] = array(
                "citycode"=>"1234",
                "cityname"=> "test"
            );
            $j = 1;
            for($i=0;$i<sizeof($states);$i++){
                $cities[$j] = $this->Console_Model->get_state_cities_sp($states[$i]['statecode']);
                $j++;
            }

            $banners = array();
            $banners = $this->Console_Model->get_wash_images();
            $i=0;
            $filtered_banners = array();
            $duplicates = array();
            $k=0;
            foreach($banners as $b){
                if($i > 0){
                    if($b['state'] != NULL && $b['city'] != NULL ){
                        for($j=0;$j<sizeof($filtered_banners);$j++){
                            if($filtered_banners[$j]['url'] == $b['url'] && $filtered_banners[$j]['state'] == $b['state']){
                                $duplicates[$k] = $b;
                                $k++;
                                $is_exists = 1;
                                break;
                            }else{
                                $is_exists = 0;
                            }
                        }
                        if($is_exists == 0){
                            $filtered_banners[$i] = $b;
                            $i++;
                        }
                    }else{
                        $filtered_banners[$i] = $b;
                            $i++;
                    }
                }else{
                    $filtered_banners[0] = $b;
                    $i++;

                }
            }
            if(sizeof($duplicates) > 0){
                foreach($duplicates as $d){
                    $id = $d['id'];
                    $banners = array_filter($banners, function($x) use ($id) { return $x['id'] != $id; });
                    for($i=0;$i<sizeof($banners);$i++){
                        if($banners[$i]['state'] == $d['state'] && $banners[$i]['url'] == $d['url'] && $banners[$i]['city'] != NULL){
                            $banners[$i]['city'] = $banners[$i]['city'].",".$d['city'];
                            break;
                        }
                    }
                }

            }
            if(sizeof($banners) > 0)
                $data = array('wash_image' => $banners,'states' => $states,'cities' => $cities);
            else 
                $data = array('wash_image' => "",'states' => $states,'cities' => $cities);
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_schedulewash_images', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }
    /**
     *To load the the payment search panel view
     */
    public function admin_coupons()
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

            $coupons = $this->Console_Model->get_coupons();
            $states = $this->Console_Model->get_states_sp();
            $cities=[];
            for($i=0;$i<sizeof($states);$i++){
                $cities[$i] = $this->Console_Model->get_state_cities_sp($states[$i]['statecode']);
            }
            $data = array('states' => $states,'cities' => $cities,'coupons' => $coupons);
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_coupons', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }
     /**
     *To load the the campaign panel view
     */
    public function admin_campaigns()
    {
        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'CAMPAIGNS';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }
            $campaigns = $this->Console_Model->get_campaign_details();
            $data = array('campaigns' => $campaigns,'username' =>$_SESSION['username'],'time' => 'All');
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_campaigns', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }
    /**
     *To load the the customer app home popup detail's page
     */
    // public function admin_popup()
    // {
    //     if (ADMIN) {

    //         /*Admin can access this page, but others needed to be checked.*/
    //         if (ADMIN_PREVILIGE != 'root') {

    //             /*Checking the validity of the access based on user accessibility.*/
    //             $page = 'MOBILEIMAGES';
    //             $validiy = $this->check_accessibility($page);

    //             if ($validiy == FALSE) {
    //                 echo 'invalid access';
    //                 exit(0);
    //             }
    //         }
    //         $popups = $this->Console_Model->get_popup_details();
    //         $states = $this->Console_Model->get_states_sp();
    //         $cities=[];
    //         $cities[0][0] = array(
    //             "citycode"=>"1234",
    //             "cityname"=> "test"
    //         );
    //         $j = 1;
    //         for($i=0;$i<sizeof($states);$i++){    
    //             $cities[$j] = $this->Console_Model->get_state_cities_sp($states[$i]['statecode']);
    //             $j++;
    //         }
    //         $data = array('popups' => $popups,'states' => $states,'cities' => $cities);
    //         $this->load->view('Admin/Base/admin_header');
    //         $this->load->view('Admin/Pages/admin_popups', $data);
    //         $this->load->view('Admin/Base/admin_footer');
    //     } else {

    //         $this->home();
    //     }
    // }
    public function admin_popup()
    {
        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'MOBILEIMAGES';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }
             $banners = array();
            $banners = $this->Console_Model->get_popup_details();
            $i=0;
            $filtered_banners = array();
            $duplicates = array();
            $k=0;
            foreach($banners as $b){
                if($i > 0){
                    if($b['state'] != NULL && $b['city'] != NULL ){
                        for($j=0;$j<sizeof($filtered_banners);$j++){
                            if($filtered_banners[$j]['url'] == $b['url'] && $filtered_banners[$j]['state'] == $b['state']){
                                $duplicates[$k] = $b;
                                $k++;
                                $is_exists = 1;
                                break;
                            }else{
                                $is_exists = 0;
                            }
                        }
                        if($is_exists == 0){
                            $filtered_banners[$i] = $b;
                            $i++;
                        }
                    }else{
                        $filtered_banners[$i] = $b;
                            $i++;
                    }
                }else{
                    $filtered_banners[0] = $b;
                    $i++;

                }
            }
            if(sizeof($duplicates) > 0){
                foreach($duplicates as $d){
                    $id = $d['id'];
                    $banners = array_filter($banners, function($x) use ($id) { return $x['id'] != $id; });
                    for($i=0;$i<sizeof($banners);$i++){
                        if($banners[$i]['state'] == $d['state'] && $banners[$i]['url'] == $d['url'] && $banners[$i]['city'] != NULL){
                            $banners[$i]['city'] = $banners[$i]['city'].",".$d['city'];
                            break;
                        }
                    }
                }

            }
            $states = $this->Console_Model->get_states_sp();
            $cities=[];
            $cities[0][0] = array(
                "citycode"=>"1234",
                "cityname"=> "test"
            );
            $j = 1;
            for($i=0;$i<sizeof($states);$i++){    
                $cities[$j] = $this->Console_Model->get_state_cities_sp($states[$i]['statecode']);
                $j++;
            }
            $data = array('popups' => $banners,'states' => $states,'cities' => $cities);
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_popups', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }
    /**
     * Adding new campaign details
     */
    public function add_campaign()
    {
        $request = $this->input->post();
        $title = $request['title'];
        $image = $request['image'];
        $desc = $request['desc'];
        $code = $request['code'];
        $url = $request['url'];
        $start = $request['start'];
        $end = $request['end'];
        $created_by = $request['created_by'];
        $res = $this->Console_Model->add_campaign($title,$image,$desc,$code,$url,$start,$end,$created_by);
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully saved');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to save');
        }
        echo json_encode($data);
    }
    /**
     * Deleting campaign data
     */
    public function delete_campaign()
    {
        $request = $this->input->post();
        $id = $request['campaign_id'];
       $res = $this->Console_Model->delete_campaign($id);
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully deleted');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to delete');
        }
        echo json_encode($data);
    }
    /**
     * Function to load campaign's details
     */
    public function show_campaign_details($id)
    {
        $campaign_data = $this->Console_Model->get_campaign_details_from_id($id);
        $data = array('campaigns' => $campaign_data);
        $this->load->view('Admin/Base/admin_header');
        $this->load->view('Admin/Pages/admin_campaign_details',$data);
        $this->load->view('Admin/Base/admin_footer');

    }
    public function delete_campaign_details($id)
    {
        $this->Console_Model->delete_campaign($id);
        $this->admin_campaigns();
    }
     /**
     * Function to update campaign details
     */
    public function update_campaign(){
        $request = $this->input->post();
        $campaign_id = $request['campaign_id'];
        $title = $request['title'];
        $desc = $request['desc'];
        $code = $request['code'];
        $url = $request['url'];
        $start = $request['start'];
        $end = $request['end'];
        $res = $this->Console_Model->update_campaign($campaign_id,$title,$desc,$code,$url,$start,$end);
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully updated');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to update');
        }
        echo json_encode($data);
    }
    /**
     * Function to show campaign details based on date
     */
    public function search_campaign()
    {
        $request = $this->input->post();
        $start = $request['start'];
        $end = $request['end'];
        $campaigns = $this->Console_Model->get_campaign_data_from_date($start,$end);  
        if($start != "" && $end != "")
            $time_period = $start .' - '. $end;
        else if($start == "" && $end != "")
            $time_period = "Upto ".$end;
        else if($start != "" && $end == "")
            $time_period = "From " .$start;
        else
            $time_period = "All";
        if ($campaigns) {
        
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to retrieve');
        }
       echo json_encode($campaigns);
       

    }
    /**
     * Function to download campaign  report
     */
    public function download_campaign_report()
    {
        $request = $this->input->post();
        $start = $request['start'];
        $end = $request['end'];
        $campaigns = $this->Console_Model->get_campaign_data_from_date($start,$end);
        if($campaigns){
            $n='';
            $j = 2;
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'SI.No');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Title');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Description');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'DiscountCode');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Start Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'End Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Created date');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Created by ');
            for($n =0;$n < sizeof($campaigns);$n++){
                if(isset($campaigns[$n])){
                    $date = date("d-m-Y", strtotime($campaigns[$n]['date']));
                    $start_date = date("d-m-Y", strtotime($campaigns[$n]['start_date']));
                    $end_date = date("d-m-Y", strtotime($campaigns[$n]['end_date']));
                    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $j-1);
                    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $campaigns[$n]['title']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $campaigns[$n]['description']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $campaigns[$n]['discount_code']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $start_date);
                    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $end_date);
                    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $date);
                    $objPHPExcel->getActiveSheet()->SetCellValue('H' . $j, $campaigns[$n]['created_by']);
                    $j++;   
                }    
            }
            $objPHPExcel->getActiveSheet()->setTitle('Campaigns');

            //Determining file name based on brand_code
            $folder_name = "Campaign_Reports";
            // Set properties

            $objPHPExcel->getProperties()->setCreator(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setLastModifiedBy(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setTitle($folder_name . "Campaigns");
            $objPHPExcel->getProperties()->setSubject("Campaigns");
            $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $date = date('Y-m-d-H-i-s');
            //Final file name would be;
                
            $file_name = $folder_name.$date.'.xls';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $target_file = 'excel_reports/' . $folder_name . '/' . $file_name;
            $save_status = $objWriter->save($target_file);
            $data = file_get_contents($target_file);
            $data = array(
                'status' => 'success',
                'file' => $file_name,
            
            );
    }else {
        $data = array(
            'status' => 'failed',
            'size' => 0,
        );
    }
    echo json_encode($data);
    }
    /**
     *Adding new essentialpopup details 
     */
    public function add_popup()
    {

        $request = $this->input->post();
        $site_url = $request['site_url'];
        $popup_image = $request['popup_image'];
        $statecode = $request['state'];
        $cities = $request['cities'];
        $expiry = $request['expiry'];
        if($statecode == "all" || $statecode == ""){    
            $res = $this->Console_Model->add_popup($popup_image,$site_url,$statecode,$cities,$expiry);
        }else{
            $all_states = $this->Console_Model->get_states_sp();
            for($j=0;$j<sizeof($all_states);$j++){
                if($all_states[$j]['statecode'] == $statecode)
                    $state = $all_states[$j]['statename'];
            }
            for($i=0;$i<sizeof($cities);$i++){
                $res = $this->Console_Model->add_popup($popup_image,$site_url,$state,$cities[$i],$expiry);
            }
        }
       
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully saved');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to save');
        }
        echo json_encode($data);
    }
    /**
     * Deleting popup details
     */
    // public function delete_popup()
    // {
    //     $request = $this->input->post();
    //     $popup_id = $request['popup_id'];
    //     $category = "homepage-popup";
    //     $data =  $this->Console_Model->get_banner($popup_id,$category);
    //     $url = substr($data[0]['url'],35);
    //     unlink($url);
    //     $res = $this->Console_Model->delete_popup($popup_id);
    //     if ($res) {
    //         $data = array('status' => 'success', 'message' => 'successfully deleted');
    //     } else {
    //         $data = array('status' => 'failed', 'message' => 'failed to delete');
    //     }
    //     echo json_encode($data);
    // }
    public function delete_popup()
    {
        $request = $this->input->post();
        $popup_id = $request['popup_id'];
        $category = "homepage-popup";
        $data =  $this->Console_Model->get_banner($popup_id,$category);
        $url = substr($data[0]['url'],38);
        unlink($url);
        $popup_ids[0] = $popup_id;
        if($data[0]['state'] != NULL && $data[0]['city'] != NULL ){
            $duplicate_data = array();
            $duplicate_data = $this->Console_Model->get_duplicate_banners($data[0]['state'],$data[0]['city'],$data[0]['url'],$category);   //check the same banner is added for other cities in the same state
            if(sizeof($duplicate_data) > 0){
                for($i=0;$i<sizeof($duplicate_data);$i++){
                    $popup_ids[$i+1] = $duplicate_data[$i]['id'];
                }
            }
        }
        for($i=0;$i<sizeof($popup_ids);$i++){
            $res = $this->Console_Model->delete_popup($popup_ids[$i]);
        }
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully deleted');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to delete');
        }
        echo json_encode($data);
    }
    /**
     * To number of cities in a state
     */
    public function get_state_cities_sp()
    {
        $request = $this->input->post();
        $statecode = $request['statecode'];
        $states = $this->Console_Model->get_states_sp();
        for($i=0;$i<sizeof($states);$i++){
            if($states[$i]['statecode'] == $statecode){
                $row = $i;
            }
        }
        $res=array('row'=> $row,'size'=> sizeof($states));
        echo json_encode($res);
    }
     /**
     *Adding new coupons
     */
    public function add_coupon()
    {

        $request = $this->input->post();
        $statecode = $request['state'];
        $all_states = $this->Console_Model->get_states_sp();
        $cities = $request['cities'];
        $promo_code = $request['promo_code'];
        $discount_code = $request['discount_code'];
        $app_remarks = $request['app_remarks'];
        $expiry_date = $request['expiry_date'];
        if($statecode == "all"){    
            $res = $this->Console_Model->add_coupon($statecode, $cities ,$promo_code, $discount_code, $app_remarks, $expiry_date);
        }else{
            for($j=0;$j<sizeof($all_states);$j++){
                if($all_states[$j]['statecode'] == $statecode)
                    $state = $all_states[$j]['statename'];
            }
            for($i=0;$i<sizeof($cities);$i++){
                $res = $this->Console_Model->add_coupon($state, $cities[$i] ,$promo_code, $discount_code, $app_remarks, $expiry_date);
            }
        }
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully saved');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to save');
        }
        echo json_encode($data);
    }
    /**
     * Deleting coupon
     */
    public function delete_coupon($id)
    {
        $date = date('Y-m-d');
        $this->Console_Model->delete_coupon($id);
        redirect('console/coupons');
    }
    /**
     *Saving offers in the offers page
     */
    public function save_essentialpopup()
    {

        $request = $this->input->post();
        $popup_id = $request['popup_id'];
        $title = $request['title'];
        $res = $this->Console_Model->save_essentialpopup($popup_id,$title);
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully saved');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to save');
        }
        echo json_encode($data);
    }
    /**
     *Adding new images for schedule wash
     */
    public function add_wash_image()
    {

        $request = $this->input->post();
        $wash_image = $request['wash_image'];
        $statecode = $request['state'];
        $cities = $request['cities'];
        $brand_code = $request['brand'];
        $site_url = $request['site_url'];
        if($statecode == "all" || $statecode == ""){    
            $res = $this->Console_Model->add_wash_image($wash_image,$statecode,$cities,$brand_code,$site_url);
        }else{
            $all_states = $this->Console_Model->get_states_sp();
            for($j=0;$j<sizeof($all_states);$j++){
                if($all_states[$j]['statecode'] == $statecode)
                    $state = $all_states[$j]['statename'];
            }
            for($i=0;$i<sizeof($cities);$i++){
                $res = $this->Console_Model->add_wash_image($wash_image,$state,$cities[$i],$brand_code,$site_url);
            }
        }
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully saved');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to save');
        }
        echo json_encode($data);
    }
    // public function delete_essentialpopups()
    // {
    //     $request = $this->input->post();
    //     $popup_id = $request['popup_id'];
    //     $category = "essentialpopup";
    //     $data =  $this->Console_Model->get_banner($popup_id,$category);
    //     $url = substr($data[0]['url'],35);
    //     unlink($url);
    //     $res = $this->Console_Model->delete_essentialpopups($popup_id);
    //     if ($res) {
    //         $data = array('status' => 'success', 'message' => 'successfully deleted');
    //     } else {
    //         $data = array('status' => 'failed', 'message' => 'failed to delete');
    //     }
    //     echo json_encode($data);
    // }
     public function delete_essentialpopups()
    {
        $request = $this->input->post();
        $id = $request['popup_id'];
        $category = "essentialpopup";
        $popup_ids = array();
        $popup_ids[0] = $id;
        $data =  $this->Console_Model->get_banner($id,$category);
        if($data[0]['state'] != NULL && $data[0]['city'] != NULL ){
            $duplicate_data = array();
            $duplicate_data = $this->Console_Model->get_duplicate_banners($data[0]['state'],$data[0]['city'],$data[0]['url'],$category);   //check the same banner is added for other cities in the same state
            if(sizeof($duplicate_data) > 0){
                for($i=0;$i<sizeof($duplicate_data);$i++){
                    $popup_ids[$i+1] = $duplicate_data[$i]['id'];
                }
            }
        }
        for($i=0;$i<sizeof($popup_ids);$i++){
            $res = $this->Console_Model->delete_essentialpopups($popup_ids[$i]);
        }
        if ($res) {
            $url = substr($data[0]['url'],38);
            unlink($url);
            $data = array('status' => 'success', 'message' => 'successfully deleted');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to delete');
        }
        echo json_encode($data);
    }
    public function delete_tip()
    {
        $request = $this->input->post();
        $tip_id = $request['tip_id'];
        $category = "tip";
        $data =  $this->Console_Model->get_banner($tip_id,$category);
        $url = substr($data[0]['url'],35);
        unlink($url);
        $res = $this->Console_Model->delete_tip($tip_id);
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully deleted');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to delete');
        }
        echo json_encode($data);
    }
    public function check_tip(){
        $tip = $this->Console_Model->check_tip();
        if($tip == "")
            $data = 'success';
        else
            $data = 'failed';
        
        echo json_encode($data);
    }
    /**
     *Adding new tip
     */
    public function add_tip()
    {

        $request = $this->input->post();
        $tip_image = $request['tip_image'];
        $res = $this->Console_Model->add_tip($tip_image);
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully saved');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to save');
        }
        echo json_encode($data);
    }
    public function upload_image()
    {
         $target_dir = "layout/img/mobileimages/";
        $_FILES["files"]["name"][0] = time().$_FILES["files"]["name"][0];
        $existing_files = array();
        $failed_files = array();
        $moved_files = array();
        $target_file = $target_dir . basename($_FILES["files"]["name"][0]);
        $target_file = str_replace(" ", "", $target_file);
        $fileExt = pathinfo($target_file, PATHINFO_EXTENSION);
        if($fileExt != 'jpg'  && $fileExt != 'gif'){
            $data = array(
                'status' => 'failed',
                'message' => 'Only jpg & gif files are allowed',
                'moved_files' => $moved_files,
                'existing_files' => $existing_files
            );
        }else{
            if (!file_exists(str_replace(" ", "", $target_file))) {
                    $res = move_uploaded_file($_FILES["files"]["tmp_name"][0], str_replace(" ", "", $target_file));
                    array_push($moved_files, array('file' => str_replace(" ", "", $_FILES["files"]["name"][0]), 'link' => base_url() . $target_file));
            } else {
                array_push($existing_files, array('file' => $_FILES["files"]["name"][0], 'link' => base_url() . $target_file));
            }
            // $image_size = filesize($target_file);
            // if($image_size > 46227){
            
            //     $config_manip = array(
            //         'image_library' => 'gd2',
            //         'source_image' => $target_file,
            //         'new_image' => $target_file,
            //         'maintain_ratio' => TRUE,
            //         'width' => 500,
            //     );
             
            //     $this->load->library('image_lib', $config_manip);
            //     if (!$this->image_lib->resize()) {
            //         echo $this->image_lib->display_errors();
            //     }
             
            //     $this->image_lib->clear();
            // }
            $data = array(
                'status' => 'success',
                'message' => 'Uploaded successfully',
                'moved_files' => $moved_files,
                'existing_files' => $existing_files
            );

        }
        echo json_encode($data);
    }
    /**
     *Adding new essentialpopup details 
     */
    public function add_essentialpopups()
    {

         $request = $this->input->post();
        if($request['title'] != 'New Essentialpopup')
            $title = $request['title'];
        else
            $title = '';
        if($request['site_url'] != 'Site URL')
            $site_url = $request['site_url'];
        else
            $site_url = '';
        $essential_image = $request['essential_image'];
        $statecode = $request['state'];
        $cities = $request['cities'];
        $brand_code = $request['brand'];
        if($statecode == "all" || $statecode == ""){    
            $res = $this->Console_Model->add_essentialpopups($title,$essential_image,$site_url,$statecode,$cities,$brand_code);
        }else{
            $all_states = $this->Console_Model->get_states_sp();
            for($j=0;$j<sizeof($all_states);$j++){
                if($all_states[$j]['statecode'] == $statecode)
                    $state = $all_states[$j]['statename'];
            }
            for($i=0;$i<sizeof($cities);$i++){
                $res = $this->Console_Model->add_essentialpopups($title,$essential_image,$site_url,$state,$cities[$i],$brand_code);
            }
        }
       
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully saved');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to save');
        }
        echo json_encode($data);
    }
    // public function delete_wash_image()
    // {
    //     $request = $this->input->post();
    //     $wash_id = $request['wash_id'];
    //     $category = "schedule-wash";
    //     $data =  $this->Console_Model->get_banner($wash_id,$category);
    //     $url = substr($data[0]['url'],35);
    //     unlink($url);
    //     $res = $this->Console_Model->delete_wash_image($wash_id);
    //     if ($res) {
    //         $data = array('status' => 'success', 'message' => 'successfully deleted');
    //     } else {
    //         $data = array('status' => 'failed', 'message' => 'failed to delete');
    //     }
    //     echo json_encode($data);
    // }
    public function delete_wash_image()
    {
        $request = $this->input->post();
        $wash_id = $request['wash_id'];
        $category = "schedule-wash";
        $data =  $this->Console_Model->get_banner($wash_id,$category);
        $popup_ids = array();
        $popup_ids[0] = $wash_id;
        if($data[0]['state'] != NULL && $data[0]['city'] != NULL ){
            $duplicate_data = array();
            $duplicate_data = $this->Console_Model->get_duplicate_banners($data[0]['state'],$data[0]['city'],$data[0]['url'],$category);   //check the same banner is added for other cities in the same state
            if(sizeof($duplicate_data) > 0){
                for($i=0;$i<sizeof($duplicate_data);$i++){
                    $popup_ids[$i+1] = $duplicate_data[$i]['id'];
                }
            }
        }
        for($i=0;$i<sizeof($popup_ids);$i++){
            $res = $this->Console_Model->delete_wash_image($popup_ids[$i]);
        }
        if ($res) {
            $url = substr($data[0]['url'],38);
            unlink($url);
            $data = array('status' => 'success', 'message' => 'successfully deleted');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to delete');
        }
        echo json_encode($data);
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


            $feedbacks = $this->Console_Model->get_feedbacks();
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
        if(ADMIN){
            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'SEND_NOTIFICATIONS';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }

            $states = $this->Console_Model->get_states_sp();
            $total_cities = 0;
                $cities=[];
                for($i=0;$i<sizeof($states);$i++){
                    $cities[$i] = $this->Console_Model->get_state_cities_sp($states[$i]['statecode']);
                    $total_cities = $total_cities + sizeof($cities[$i]);
                }
            $data = array('states' => $states,'cities' => $cities,'total_cities' => $total_cities,'username' =>$_SESSION['username']);
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_send_notifications', $data);
            $this->load->view('Admin/Base/admin_footer');
        }else{
            $this->home();
        }
    }

    /**
     *To load the send notification view
     */
    public function admin_payment_gateway_center()
    {
        if (ADMIN) {
            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {
                /*Checking the validity of the access based on user accessibility.*/
                $page = 'PAYMENT_GATEWAY_CENTER';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }

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

            $campaign_stats = array('stats' => $this->Console_Model->load_appspa_campaign_stats());

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

            $result = $this->Console_Model->get_brands();
            $stores = $this->Console_Model->get_all_stores();
            $data = array('brands' => $result, 'stores' => $stores);
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_dcr', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *Loading QA Users
     */
    public function admin_qa_users()
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
            $this->load->view('Admin/Pages/QA/admin_qa_users');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *Loading QA Users
     */
    public function admin_qa_finished_by_users()
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
            $this->load->view('Admin/Pages/QA/admin_qa_finished_by_users');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *QA Logs page
     */
    public function admin_qa_logs($number = FALSE)
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


            $qa_reasons = $this->Console_Model->get_qa_reasons();

            $keyword = $this->input->get('keyword');

            $pagination = $this->log_pagination('QA');

            /*Based on the get keyword request, determine whether we need to show the whole result or the searched data only */
            if (!$keyword) {
                //$logs = $this->Console_Model->get_qa_logs();

                $logs = $this->Console_Model->get_qa_paginated_logs($pagination['per_page'], $pagination['offset']);
            } else {
                if ($keyword != '')
                    $logs = $this->Console_Model->qa_search_tag_id($keyword);
                else
                    //$logs = $this->Console_Model->get_qa_logs();
                    $logs = $this->Console_Model->get_qa_paginated_logs($pagination['per_page'], $pagination['offset']);
            }


            for ($i = 0; $i < sizeof($logs); $i++) {
                $log_reason_array = explode(', ', $logs[$i]['Reason']);
                for ($j = 0; $j < sizeof($qa_reasons); $j++) {

                    /*Default value for all reasons*/
                    $logs[$i][$qa_reasons[$j]['Reason']] = '<span style="color: red !important;" class="uk-margin-small-right" uk-icon="icon: close; ratio: 1.5"></span>';
                }

                foreach ($logs[$i] as $key => $value) {
                    for ($k = 0; $k < sizeof($log_reason_array); $k++) {
                        if ($key == $log_reason_array[$k]) {
                            $logs[$i][$key] = '<span style="color: #0f6ecd !important;" class="uk-margin-small-right" uk-icon="icon: check; ratio: 1.5"></span>';
                        } else {

                        }
                    }
                }

            }


            $essentials = array('reasons' => $qa_reasons, 'logs' => $logs, 'pagination' => $pagination);

            $this->load->view('Admin/Base/admin_header');
            /*Based on get keyword request, render the page that needs to show*/
            if (!$keyword)
                $this->load->view('Admin/Pages/QA/admin_qa_logs', $essentials);
            else
                $this->load->view('Admin/Pages/QA/admin_qa_logs_search_page', $essentials);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }

    /**
     *Predefined reasons page for QA
     */
    public function admin_qa_reasons()
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
            $this->load->view('Admin/Pages/QA/admin_qa_reasons');
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }


    /**
     *QC Logs page
     */
    public function admin_qc_logs($number = FALSE)
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


            $qc_reasons = $this->Console_Model->get_qc_reasons();

            $keyword = $this->input->get('keyword');

            $pagination = $this->log_pagination('QC');

            /*Based on the get keyword request, determine whether we need to show the whole result or the searched data only */
            if (!$keyword) {
                //$logs = $this->Console_Model->get_qc_logs();

                $logs = $this->Console_Model->get_qc_paginated_logs($pagination['per_page'], $pagination['offset']);
            } else {
                if ($keyword != '')
                    $logs = $this->Console_Model->qc_search_tag_id($keyword);
                else
                    //$logs = $this->Console_Model->get_qc_logs();
                    $logs = $this->Console_Model->get_qc_paginated_logs($pagination['per_page'], $pagination['offset']);
            }


            /* for ($i = 0; $i < sizeof($logs); $i++) {
                 $log_reason_array = explode(', ', $logs[$i]['Reason']);
                 for ($j = 0; $j < sizeof($qc_reasons); $j++) {

                     //Default value for all reasons
                     $logs[$i][$qc_reasons[$j]['Reason']] = '<span style="color: red !important;" class="uk-margin-small-right" uk-icon="icon: close; ratio: 1.5"></span>';
                 }

                 foreach ($logs[$i] as $key => $value) {
                     for ($k = 0; $k < sizeof($log_reason_array); $k++) {
                         if ($key == $log_reason_array[$k]) {
                             $logs[$i][$key] = '<span style="color: #0f6ecd !important;" class="uk-margin-small-right" uk-icon="icon: check; ratio: 1.5"></span>';
                         } else {

                         }
                     }
                 }

             }*/


            $essentials = array('reasons' => $qc_reasons, 'logs' => $logs, 'pagination' => $pagination);

            $this->load->view('Admin/Base/admin_header');
            /*Based on get keyword request, render the page that needs to show*/
            if (!$keyword)
                $this->load->view('Admin/Pages/qc/admin_qc_logs', $essentials);
            else
                $this->load->view('Admin/Pages/qc/admin_qc_logs_search_page', $essentials);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }

    }


    /**
     * To load the details attached to a particular QC id.
     * @param $tag_no
     */
    public function admin_qc_log($tag_no = FALSE)
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

            if (isset($tag_no)) {


                $log_data = $this->Console_Model->get_log_data($tag_no);
                $cipher = $this->Console_Model->get_cipher_code($log_data['CustomerCode']);
                $images = $this->Console_Model->get_log_images($log_data['Id']);
                $garment_details = $this->Console_Model->get_garment_details($tag_no);

                $data = array('garment_details' => $garment_details, 'log_data' => $log_data, 'cipher' => $cipher, 'images' => $images);

                $this->load->view('Admin/Base/admin_header');
                $this->load->view('Admin/Pages/QC/admin_qc_log', $data);
                $this->load->view('Admin/Base/admin_footer');

            } else {
                echo 'inavlid request';
            }

        } else {
            echo 'inavlid request';
        }
    }

    /**
     *To load the admin creation view
     */
    public function admin_creation()
    {
        $this->load->view('Admin/Base/admin_login_header');
        $this->load->view('Admin/Pages/admin_creation');
        // $this->load->view('templates/login_footer');
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
                    'username' => $this->input->post('username')

                );


                $result = $this->Console_Model->admin_login($data);

                if ($result) {

                    $encrypted_password = substr(hash_hmac('sha256', $this->input->post('password'), 'JFSL_key_1234'), 0, 24);
                 
                    //Comparing the encrypted password to the password saved in the DB.
                    if ($result['password'] === $encrypted_password) {


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

            $request = $this->generic->json_input();

            $search_with = $request['search_with'];

            $search_text = $request['search_text'];

            $result = TRUE;

            switch ($search_with) {

                case 'mobile_number' :
                    $result = $this->Console_Model->search_with_mobile($search_text);
                    break;

                case 'email' :
                    $result = $this->Console_Model->search_with_email($search_text);
                    break;

                case 'customer_id' :
                    $result = $this->Console_Model->search_with_customer_id($search_text);
                    break;

                case 'order_id' :
                    $result = $this->Console_Model->search_with_order_id($search_text);
                    break;

                case 'booking_id' :
                    $result = $this->Console_Model->search_with_booking_id($search_text);
                    break;

                case 'name' :
                    $result = $this->Console_Model->search_with_name($search_text);
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

                    if (array_key_exists('date', $result['order_details'][$i])) {

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

        } else {
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


            $result = $this->Console_Model->get_registration_details($data);

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


        $payment_details = $this->Console_Model->get_payment_details($limit, $offset, $customer_code, $start_date, $end_date, $payment_id);


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

            $result = $this->Console_Model->get_users_details($data);

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

            $result = $this->Console_Model->get_orders_details($data);

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

                $result = $this->Console_Model->check_admin($data);

                if ($result == FALSE) {


                    /*Creating an encrypted password from user password*/
                    $encrypted_password = substr(hash_hmac('sha256', $data['password'], 'JFSL_key_1234'), 0, 24);

                    $new_user = array(
                        'username' => $data['username'],
                        'password' => $encrypted_password
                    );

                    $result = $this->Console_Model->add_admin($new_user);

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
            $request = $this->generic->json_input();

            $data = array(
                'username' => ADMIN_USERNAME,
                'old_password' => $request['old_password'],
                'new_password' => $request['new_password']
            );


            $result = $this->Console_Model->change_password_check_password($data);

            $encrypted_old_password = substr(hash_hmac('sha256', $data['old_password'], 'JFSL_key_1234'), 0, 24);

            if ($encrypted_old_password === $result['password']) {

                /*Creating an encrypted password from user password*/
                $encrypted_password = substr(hash_hmac('sha256', $data['new_password'], 'JFSL_key_1234'), 0, 24);

                $new_user = array(
                    'username' => ADMIN_USERNAME,
                    'password' => $encrypted_password
                );

                $result = $this->Console_Model->change_password($new_user);

                if ($result) {


                    $data = array(
                        'status' => 'success'

                    );


                } else {

                    $data = array(
                        'status' => 'failed'

                    );

                }

            } else {

                $data = array(
                    'status' => 'wrong_password',


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


            $result = $this->Console_Model->dashboard_registers();

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


            $result = $this->Console_Model->dashboard_orders();

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

            $result = $this->Console_Model->dashboard_total_users();

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


            $result = $this->Console_Model->dashboard_fab_source();

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
            $result = $this->Console_Model->dashboard_cw_source();

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
     * Method to get all notification details
     */
    public function get_notification_details()
    {
        $details = $this->Console_Model->get_notification_details();
        $data = array('data' => $details);
        $this->load->view('Admin/Base/admin_header');
        $this->load->view('Admin/Pages/admin_notification_details',$data);
        $this->load->view('Admin/Base/admin_footer');
            // $this->load->view('Admin/Base/admin_header');
            // $config = array();
            // $config["base_url"] =  site_url('Console_controller/get_notification_pages');
            // $config["total_rows"] = $this->Console_Model->get_count();
            // $config["per_page"] = 10;
            // $config["uri_segment"] = 100;
            // $this->pagination->initialize($config);
            // $page = $this->uri->segment(5,0);
            // $details = $this->Console_Model->get_notification_details($config["per_page"], $page);
            // $data = array('data' => $details);
            // $this->load->view('Admin/Pages/admin_notification_details',$data,$config["per_page"]);
            // $this->load->view('Admin/Base/admin_footer');
    }
    // public function get_notification_pages($page)
    // {
    //         $this->load->view('Admin/Base/admin_header');
    //         $config = array();
    //         $config["base_url"] =  site_url('Console_Controller/get_notification_pages');
    //         $config["total_rows"] = $this->Console_Model->get_count();
    //         $config["per_page"] = 10;
    //         $config["uri_segment"] = 100;
    //         $this->pagination->initialize($config);
    //         // $page = $this->uri->segment(5,0);
    //         $details = $this->Console_Model->get_notification_details($config["per_page"], $page);
    //         $data = array('data' => $details);
    //         $this->load->view('Admin/Pages/admin_notification_details',$data);
    //         $this->load->view('Admin/Base/admin_footer');
    // }
    /**
     * Method to Cancel scheduled notifications
     */
    public function cancel_scheduled_notifications($id)
    {
        $date = date('Y-m-d');
        $this->Console_Model->cancel_scheduled_notifications($id,$date);
        redirect('console/get_notification_details');
    }
    /**
     * Method to get notification send user's details
     */
    public function show_users_details($id) 
    {
        $details = $this->Console_Model->get_notification_users_details($id);
        $data = array('data' => $details);
        $this->load->view('Admin/Base/admin_header');
        $this->load->view('Admin/Pages/admin_notification_users_details',$data);
        $this->load->view('Admin/Base/admin_footer');
    }
    /**
     * To display notification file download options
     */
    public function notification_download_options()
    {
        $cities = $this->get_store_cities('Fabricspa');
        $data = array('cities' => $cities);
        $this->load->view('Admin/Base/admin_header');
        $this->load->view('Admin/Pages/admin_notification_download',$data);
        $this->load->view('Admin/Base/admin_footer'); 
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
            $is_file = $this->input->post('is_file');
            if($list !="")
                $gcmids = $this->Console_Model->get_gcmids($via, $list, $brand, $device,$is_file);
            $this->write_pg_response("notification numbers".json_encode($list));
            if ($gcmids) {
                $size=sizeof($gcmids);
                $data = array(
                    'status' => 'success',
                    'gcmids' => $gcmids,
                    'size' => $size
                );
            } else {
                $data = array(
                    'status' => 'failed'
                );
            }
            $this->write_pg_response("count : " . sizeof($gcmids) ." ,notification numbers : ".json_encode($list). "  gcmids : ". json_encode($gcmids));
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
            $state = $this->input->post('state');
            $location = $this->input->post('location');
            $start = $this->input->post('start');
            $limit = $this->input->post('limit');
            $size = 0;
            if($state == ""){
                $gcmids= $this->Console_Model->get_all_gcmids($brand, $device, $location, $start, $limit);
                $size=sizeof($gcmids);
            }else if($state == "all"){      
                $states = $this->Console_Model->get_states_sp();
                $cities=[];
                for($i=0;$i<sizeof($states);$i++){
                    $cities[$i] = $this->Console_Model->get_state_cities_sp($states[$i]['statecode']);
                    for($j=0;$j<sizeof($cities[$i]);$j++){
                        $gcmids[$j]= $this->Console_Model->get_all_gcmids($brand, $device, $cities[$i][$j]['cityname'], $start, $limit);
                        $size = $size+sizeof($gcmids[$j]);
                    }
                }
            }else{
                for($i=0;$i<sizeof($location);$i++){
                    $gcmids[$i]= $this->Console_Model->get_all_gcmids($brand, $device, $location[$i], $start, $limit);
                    $size = $size+sizeof($gcmids[$i]);
                }
                
            }
            if ($gcmids) {
                $data = array(
                    'status' => 'success',
                    'gcmids' => $gcmids,
                    'size' => $size
                );
            } else {
                $data = array(
                    'status' => 'failed'
                );
            }
            echo json_encode($data);
        }else {
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
            $location = $this->input->post('location');
            $start = $this->input->post('start');
            $limit = $this->input->post('limit'); 
            $date = $this->input->post('date');    
            $via="NULL";
            if ($brand == 'Fabricspa') {
                $brand_code = 'PCT0000001';
                $sender_id = 'PCT0000001';
            }
            else {
                $brand_code = 'PCT0000014';
                $sender_id = 'PCT0000014';
            }
            
            $date = date('d-m-Y');
            $status="Not Send";
            $schedule_date="today";
            $total_users="0";
            $notification_id=$this->Console_Model->save_notifications($date,$brand,$sender_id,$device,$location,$title,$image_url,$message,$via,$status,$schedule_date,$total_users);
                     


                $library_params = array('brand_code' => $brand_code);
                $this->load->library('push_notification/firebase', $library_params);
                $this->load->library('push_notification/push');
                if($limit< 299 ) {
                    $total_users = sizeof($to_sent);


                    $firebase = new Firebase($library_params);
                    // $firebase = $this->firebase;
                    $push = new Push();

                    // optional payload
                    $payload = array();
                    $payload['sound'] = 'default';
                    $payload['brand_code'] = $brand_code;
                    $payload['type'] = 'normal';
                    $payload['notification_id'] = $notification_id;

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
                    $push->setNotificationid($notification_id);
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
                            $json = $push->getPush();
                            $response = $firebase->sendMultiple($regIds, $json);
                            
                        }
                    } 
                    // else {
                    // //     $response = FALSE;
                    // // }


                    // if ($response) {
                    //     $data = array(
                    //         'status' => 'success',
                    //         'response' => $response,
                    //         'to_sent' => $to_sent,
                    //         'size' => $total_users
                    //     );
                    // } else {
                    //     $data = array(
                    //         'status' => 'failed',
                    //         'response' => $response
                    //     );
                    // }
                    // } 
                    else {
                            if ($push_type == 'topic') {
                                $json = $push->getPush();
                                $response = $firebase->sendToTopic('global', $json);
                            } else if ($push_type == 'multiple') {
                                $json = $push->getPush();
                            $response = $firebase->sendMultiple($regIds, $json);
                            }
                    }
                        if ($response) {  
                                $status="Send";
                                $schedule_date="today";
                                $id=$this->Console_Model->update_notifications($status,$limit,$notification_id);
                                $i='';
                                $size=sizeof($regIds);
                                for($i="0";$i < $size;$i++) {
                                    if($regIds[$i] != "(null)"){
                                        $mobile_number=$this->Console_Model->get_mobile_number($brand, $device, $location,$regIds[$i]);
                                        if($mobile_number != 0)
                                            $this->Console_Model->save_notification_details($id,$mobile_number,$status);
                                    }
                                }
                                // $mobile_no=$this->Console_Model->get_mobile_no_with_null_gcmid($brand, $device, $location,$start,$limit);
                                // if($mobile_no !="") {   
                                //     $mobile_number=json_encode($mobile_no);
                                //     $n='';
                                //     for($n=0;$n<sizeof($mobile_no);$n++) {
                                //         $status="Not Send";
                                //         $this->Console_Model->save_notification_details($id,$mobile_no[$n],$status);
                                //     }
                                // }
                                $data = array(
                                    'status' => 'success',
                                    'response' => $response,
                                    'to_sent' => $to_sent,
                                    'size' => $total_users
                                );
                                
                                echo json_encode($data);

                            }else{
                                $status="Not Send";
                                $schedule_date="today";
                                $data=$this->Console_Model->update_notifications($status,$limit,$notification_id);
                                //$data=$this->Console_Model->save_notifications($date,$brand,$sender_id,$device,$location,$title,$image_url,$message,$via,$status,$schedule_date,$limit);
                                $data = array(
                                    'status' => 'failed',
                                    'response' => "Not send",
                                    'to_sent' => $to_sent,
                                    'size' => $total_users
                                );
                                
                                echo json_encode($data);
                            }
                    }else {
                            $j = '';
                            $flag1="";
                            $flag2="";
                            for($j=0;$j<$limit;$j=$j+299) {
                                if($j>299){
                                    $start = $start+299;
                                }
                                $dif = $limit-$j;
                                if($dif >= 299 ) {
                                    $stop = 299;
                                  //  $gcmids = $this->Console_Model->get_all_gcmids($brand, $device, $location, $start, $stop);
                                    if($device == 'all'){
                                        $gcmids = $this->Console_Model->get_all_gcmid($brand,$location, $start, $stop);
                                    }else{
                                        $gcmids= $this->Console_Model->get_all_gcmids($brand, $device, $location, $start, $stop);
                                    }
                                }else{
                                    // $gcmids = $this->Console_Model->get_all_gcmids($brand, $device, $location, $start, $dif);
                                    if($device == 'all'){
                                        $gcmids = $this->Console_Model->get_all_gcmid($brand,$location, $start, $dif);
                                    }else{
                                        $gcmids= $this->Console_Model->get_all_gcmids($brand, $device, $location, $start, $dif);
                                    }
                                }
                                if ($brand == 'Fabricspa')
                                    $brand_code = 'PCT0000001';
                                else if ($brand == 'Click2Wash')
                                    $brand_code = 'PCT0000014';
                                $library_params = array('brand_code' => $brand_code);
                                $this->load->library('push_notification/firebase', $library_params);
                                $this->load->library('push_notification/push');
                                // print_r($gcmids);
                                $total_users = sizeof($gcmids);
                                // $total_users ="3000";
                    
                                $firebase = new Firebase($library_params);
                                //$firebase = $this->firebase;
                                $push = new Push();
                    
                                // optional payload
                                $payload = array();
                                $payload['sound'] = 'default';
                                $payload['brand_code'] = $brand_code;
                                $payload['type'] = 'normal';
                                $payload['notification_id'] = $notification_id;
                    
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
                    $push->setNotificationid($notification_id);
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
                                    if ($gcmids[$i] != '' || !empty($gcmids[$i]))
                                        array_push($regIds, $gcmids[$i]);
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
                                        $json = $push->getPush();
                                        $response = $firebase->sendMultiple($regIds, $json);
                                    }
                                } 
                                // else {
                                //     $response = FALSE;
                                // }
                    
                    
                                // if ($response) {
                                //     $data = array(
                                //         'status' => 'success',
                                //         'response' => $response,
                                //         'gcmids' => $gcmids,
                                //         'size' => $total_users
                                //     );
                                // } else {
                                //     $data = array(
                                //         'status' => 'failed',
                                //         'response' => $response
                                //     );
                                // }
                                else {
                                        if ($push_type == 'topic') {
                                            $json = $push->getPush();
                                            $response = $firebase->sendToTopic('global', $json);
                                        } else if ($push_type == 'multiple') {
                                            $json = $push->getPush();
                                            $response = $firebase->sendMultiple($regIds, $json);
                                        }
                                    }
                                    if ($response) {
                                        $status="Send";
                                        $schedule_date="today";
                                        
                                        if($flag1 == "") {

                                            $id=$this->Console_Model->update_notifications($status,$limit,$notification_id);
                                           //$id=$this->Console_Model->save_notifications($date,$brand,$sender_id,$device,$location,$title,$image_url,$message,$via,$status,$schedule_date,$limit);
                                            $flag1="1";
                                        }
                                        $k='';
                                        $size=sizeof($regIds);
                                        for($k="0";$k < $size;$k++) {
                                            $mobile_number=$this->Console_Model->get_mobile_number($brand, $device, $location,$regIds[$k]['gcmids']);
                                            if($mobile_number != 0)
                                                $this->Console_Model->save_notification_details($id,$mobile_number,$status);
                                        }
                                        // $mobile_no=$this->Console_Model->get_mobile_no_with_null_gcmid($brand, $device, $location,$start,$limit);
                                        // if($mobile_no) {
                                        //     foreach($mobile_no as $no) {
                                        //         $status="Not Send";
                                        //         $this->Console_Model->save_notification_details($id,$mobile_no,$status);
                                        //     }
                                        // }
                                        // $mobile_number=array();
                                        // // $mobile_numbers=$this->Console_Model->get_all_mobile_numbers($brand, $device, $location, $start, $limit);
                                        // for($j = 0; $j < $total_users; $j=$j+100) {
                                        //     if($j>=100){
                                        //         $start = $start+100;
                                        //     }
                                        //     $dif = $total_users - $j;
                                        //     if($dif>=100) {
                                        //         $end=100;
                                        //         // $mobile_number= array_slice($mobile_numbers,$i,100);
                                        //         $mobile_numbers=$this->Console_Model->get_all_mobile_numbers($brand, $device, $location, $start, $end);
                                        //         $status="Send";
                                        //         $mobile_number = json_encode($mobile_numbers);
                                        //         $this->Console_Model->save_notified_users($date,$brand,$device,$mobile_number, $title,$image_url,$message,$sender_id,$location,$status);
                                            
                                            
                                        //     }else {
                                        //         $end =$dif;
                                        //         // $mobile_number= array_slice($mobile_numbers,$i,$limit);
                                        //         $mobile_numbers=$this->Console_Model->get_all_mobile_numbers($brand, $device, $location, $start, $end);
                                        //         $status="Send";
                                        //         $mobile_number = json_encode($mobile_numbers);
                                        //         $this->Console_Model->save_notified_users($date,$brand,$device,$mobile_number, $title,$image_url,$message,$sender_id,$location,$status);
                                
                                                
                                        //     }
                                        // }
                                        
                                        
                                        $data = array(
                                            'status' => 'success',
                                            'response' => $response,
                                            'to_sent' => $gcmids,
                                            'size' => $limit
                                        );
                                        
                                    }else{
                                        $status="Not Send";
                                        $schedule_date="today";
                                        if($flag2 =="") {
                                            $data=$this->Console_Model->update_notifications($status,$limit,$notification_id);
                                            //$data=$this->Console_Model->save_notifications($date,$brand,$sender_id,$device,$location,$title,$image_url,$message,$via,$status,$schedule_date,$limit);
                                            $flag2="1";
                                        }
                                            $data = array(
                                            'status' => 'failed',
                                            'response' => "Not send",
                                            'to_sent' => $to_sent,
                                            'size' => $total_users
                                        );
                                        
                                    }  
                            }           
                            echo json_encode($data);    

                    }
                
                   
        } else {
            echo('No direct script access is allowed');
        }
    }
    /**
     * Schedule notifications
     */
    public function schedule_notification()
    {
        if ($this->input->is_ajax_request()) {
            $device = $this->input->post('device');
            $to_sent = $this->input->post('to_sent');
            $title = $this->input->post('title');
            $brand = $this->input->post('brand');
            $image_url = $this->input->post('image_url');
            $message = $this->input->post('message');
            $location = $this->input->post('location');
            $start = $this->input->post('start');
            $limit = $this->input->post('limit');     
            $via=$this->input->post('via');
            $schedule_date=$this->input->post('schedule_date');
            if ($brand == 'Fabricspa') {
                $brand_code = 'PCT0000001';
                $sender_id = 'PCT0000001';
            }
            else {
                $brand_code = 'PCT0000014';
                $sender_id = 'PCT0000014';
            }

            $date = date('d-m-Y');
            $status="Scheduled";
            $id=$this->Console_Model->save_notifications($date,$brand,$sender_id,$device,$location,$title,$image_url,$message,$via,$status,$schedule_date,$limit);
            if($limit<299) {
                $i='';
                $size=sizeof($to_sent);
                for($i="0";$i < $size;$i++) {
                    $mobile_number=$this->Console_Model->get_mobile_number($brand, $device, $location,$to_sent[$i]);
                    if($mobile_number != 0)
                        $this->Console_Model->save_notification_details($id,$mobile_number,$status);
                }
            }else {
                for($i=0;$i<$limit;$i=$i+299) {
                    
                    if($i>=299){
                        $start = $start+299;
                    }
                    $dif = $limit-$i;
                    if($dif >= 299 ) {
                        $stop = 299;
                       // $to_sent = $this->Console_Model->get_all_gcmids($brand, $device, $location, $start, $stop);
                       if($device == 'all'){
                            $to_sent = $this->Console_Model->get_all_gcmid($brand,$location, $start, $stop);
                        }else{
                            $to_sent= $this->Console_Model->get_all_gcmids($brand, $device, $location, $start, $stop);
                        }
                    }else{
                       // $to_sent = $this->Console_Model->get_all_gcmids($brand, $device, $location, $start, $dif);
                        if($device == 'all'){
                            $to_sent = $this->Console_Model->get_all_gcmid($brand,$location, $start, $dif);
                        }else{
                            $to_sent= $this->Console_Model->get_all_gcmids($brand, $device, $location, $start, $dif);
                        }
                    }
                    $s='';
                    $size=sizeof($to_sent);
                    $n='';
                    $values=array();
                    for ($n = 0; $n < $size; $n++) {
                        if (!empty($to_sent[$n]))
                            array_push($values, $to_sent[$n]);
                    }
                    for($s="0";$s < $size;$s++) {
                        $mobile_number=$this->Console_Model->get_mobile_number($brand, $device, $location,$values[$s]['gcmids']);
                        if($mobile_number != 0)
                            $this->Console_Model->save_notification_details($id,$mobile_number,$status);
                    }
                }
            }
            $data = array(
                'status' => 'Scheduled Successfully',
                'to_sent' => $to_sent,
                'size' => $limit
            );
            echo json_encode($data);

        } else {
            echo('No direct script access is allowed');
        }
    }
    /**
     * Schdeuling notifications for selected users
     */
    public function schedule_selected_notifications()
    {
        if ($this->input->is_ajax_request()) {
            $device = $this->input->post('device');
            $to_sent = $this->input->post('to_sent');
            $title = $this->input->post('title');
            $brand = $this->input->post('brand');
            $image_url = $this->input->post('image_url');
            $message = $this->input->post('message');
            $location = $this->input->post('location');
            $is_file = $this->input->post('is_file');
            $schedule_date = $this->input->post('schedule_date');
            $via = $this->input->post('via');
            $user = $this->input->post('user');
            $time = $this->input->post('time_slot');
            $tnc_status = $this->input->post('tnc_status');
            if($tnc_status == 1)
                $message = $message."(T&C Apply)";
            if ($brand == 'Fabricspa') {
                $brand_code = 'PCT0000001';
                $sender_id = 'PCT0000001';
            }
            else {
                $brand_code = 'PCT0000014';
                $sender_id = 'PCT0000014';
            }
            $date = date('d-m-Y');
            $status="Scheduled";
            $size=sizeof($to_sent);
            if($size<1000) {
                $id=$this->Console_Model->save_notifications($date,$brand,$sender_id,$device,$location,$title,$image_url,$message,$via,$status,$schedule_date,$size,$user,$time);
                $i='';
                for($i=0;$i < $size;$i++) {
                    $mobile_number=$this->Console_Model->get_mobile_number($brand, $device,$to_sent[$i]);
                    if($mobile_number != 0)
                        $this->Console_Model->save_notification_details($id,$mobile_number,$status);
                }
                $data = array(
                    'status' => 'Scheduled Successfully',
                    'to_sent' => $to_sent,
                    'size' => $size
                );
            }else{
                $data=array();
                $k='';
                $mob_no = (array_chunk($to_sent, 299));
                $flag = 0;
                for($sz = 0; $sz<sizeof($mob_no);$sz++){
                    if($flag == 0) {
                        $id=$this->Console_Model->save_notifications($date,$brand,$sender_id,$device,$location,$title,$image_url,$message,$via,$status,$schedule_date,$size,$user,$time);
                        $flag=$flag+1;
                    }else{
                        $id=$this->Console_Model->get_notification_id();
                        $id=$id[0]['notification_id'];
                    }
                    $gcmids = $this->Console_Model->get_gcmids($via, $mob_no[$sz], $brand, $device,$is_file);    
                    $j=0;
                    $total=sizeof($gcmids);
                    $n='';
                    $values=array();
                    for ($n = 0; $n < $total; $n++) {
                        if (!empty($gcmids[$n]))
                            array_push($values, $gcmids[$n]);
                    }
                    $no=sizeof($values);
                    for($j=0;$j < $no;$j++) {      
                        $mobile_number=$this->Console_Model->get_mobile_number($brand, $device, $values[$j]['gcmid']);
                        if($mobile_number != 0)
                            $this->Console_Model->save_notification_details($id,$mobile_number,$status);
                    }
                }
                $data = array(
                    'status' => 'Scheduled Successfully',
                    'to_sent' => $to_sent,
                    'size' => $size
                );
            }
            echo json_encode($data);
        } else {
            echo('No direct script access is allowed');
        }
    
    }
    /** 
     *  Send notification to the selected GCMIDS
    */
   public function send_selected_gcmids()
   {
    if ($this->input->is_ajax_request()) {
        $device = $this->input->post('device');
        $to_sent = $this->input->post('to_sent');
        $title = $this->input->post('title');
        $brand = $this->input->post('brand');
        $image_url = $this->input->post('image_url');
        $message = $this->input->post('message');
        $location = $this->input->post('location');
        $is_file = $this->input->post('is_file'); 
        $via = $this->input->post('via');
        $schedule_date = $this->input->post('schedule_date');
        $user = $this->input->post('user');
        $tnc_status = $this->input->post('tnc_status');
         if($tnc_status == 1)
                $message = $message."(T&C Apply)";
        $time = "";
        if ($brand == 'Fabricspa') {
            $brand_code = 'PCT0000001';
            $sender_id="PCT0000001";
        }
        else {
            $brand_code = 'PCT0000014';
            $sender_id="PCT0000014";
        }
        $date = date('d-m-Y');
        $status="Not Send";
        $schedule_date="today";
        $total_users="0";
        $notification_id=$this->Console_Model->save_notifications($date,$brand,$sender_id,$device,$location,$title,$image_url,$message,$via,$status,$schedule_date,$total_users,$user,$time);
    
        $library_params = array('brand_code' => $brand_code);
        $this->load->library('push_notification/firebase', $library_params);
        $this->load->library('push_notification/push');
        $total_users = sizeof($to_sent);
        if($total_users < 1000) {
            // if($is_file =="1")
                // $total_users=$total_users-1;
                $firebase = new Firebase($library_params);
                //$firebase = $this->firebase;
                $push = new Push();
    
                // optional payload
                $payload = array();
                $payload['sound'] = 'default';
                $payload['brand_code'] = $brand_code;
                $payload['type'] = 'normal';
                $payload['notification_id'] = $notification_id;
    
    
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
                        $push->setNotificationid($notification_id);
                if ($include_image) {
                    $push->setImage($image_url);
                } else {
                    $push->setImage('');
                }
                $push->setIsBackground(FALSE);
                $push->setPayload($payload);
    
                $json = '';
                $response = '';
                //Getting GCMIDs from Pref file or database. If pref GCMID list are empty, then select from DB.
            $flag =0;
            for ($i = 0; $i <= $total_users; $i= $i+299) {
                $dif = $total_users - $i;
                $regIds = array();
                if($dif>= 299)
                    $data = array_splice($to_sent,$i,299);
                else
                    $data = array_splice($to_sent,$i,$dif);
                array_push($regIds, $data);
                if ($device == 'android') {
                    if ($push_type == 'topic') {
                        $json = $push->getPush();
                        $response = $firebase->sendToTopic('global', $json);    
                    } else if ($push_type == 'multiple') {
                        $json = $push->getPush();
                       $response = $firebase->sendMultiple($regIds[0], $json);
                 
                    }
                } else if ($device == 'ios') {
                    if ($push_type == 'topic') {
                        $json = $push->getPush();
                        $response = $firebase->sendToTopic('global', $json);
                    } else if ($push_type == 'multiple') {
                       $json = $push->getPush();
                       $response = $firebase->sendMultiple($regIds[0], $json);
                    }
                }else {
                        if ($push_type == 'topic') {
                            $json = $push->getPush();
                            $response = $firebase->sendToTopic('global', $json);
                        } else if ($push_type == 'multiple') {   
                            $json = $push->getPush();
                            $response = $firebase->sendMultiple($regIds[0], $json);
                            //$data = $push->getPushIOS();
                            //$response = $firebase->sendMultipleIOS($regIds, $push->getPushNotificationIOS(), $data);
                        }
                }
            
                if ($response) {
                    $date=date('d-m-Y H:i:s');
                    $status="Send";
                    $schedule_date="today";
                    $id=$this->Console_Model->update_notifications($status,$total_users,$notification_id);
                    for($j=0;$j < sizeof($regIds[0]);$j++) {
                            if($regIds[0][$j] != "(null)"){
                                $mobile_number=$this->Console_Model->get_mobile_number($brand, $device,$regIds[0][$j]);
                                if($mobile_number !=0)
                                    $this->Console_Model->save_notification_details($id,$mobile_number,$status);
                            }   
                    }
                        $data = array(
                            'status' => 'success',
                            'response' => $response,
                            'to_sent' => $to_sent,
                            'size' => $total_users
                        );
                        
                        
                }else{
                    $date = date('d-m-Y');
                    $status="Not Send";
                    $schedule_date="today";
                    $data=$this->Console_Model->update_notifications($status,$total_users,$notification_id);
                    $data = array(
                        'status' => 'failed',
                        'response' => "Not send",
                        'to_sent' => $to_sent,
                        'size' => $total_users
                    );
                }
            }
        }else {
            $data=array();
            $total=sizeof($to_sent);
            $j='';
           $mob_no = (array_chunk($to_sent, 299));
            for ($j = 0; $j < $total_users; $j++) {
                if ($to_sent[$j] != '' || !empty($to_sent[$j]))
                    array_push($data, $to_sent[$j]);
            }
            $flag = 0;   
            for($sz = 0; $sz<sizeof($mob_no);$sz++){

                $gcmids = $this->Console_Model->get_gcmids($via, $mob_no[$sz], $brand, $device,$is_file);

                $firebase = new Firebase($library_params);
                //$firebase = $this->firebase;
                $push = new Push();
    
                // optional payload
                $payload = array();
                $payload['sound'] = 'default';
                $payload['brand_code'] = $brand_code;
                $payload['type'] = 'normal';
                $payload['notification_id'] = $notification_id;
    
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
                        $push->setNotificationid($notification_id);
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
            if($gcmids){
                $users_no=sizeof($gcmids);
                
                // Getting GCMIDs from Pref file or database. If pref GCMID list are empty, then select from DB.
                for ($k = 0; $k < $users_no; $k++) {
                    if (!empty($gcmids[$k]))
                        array_push($regIds, $gcmids[$k]);
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
                        $json = $push->getPush();
                       $response = $firebase->sendMultiple($regIds, $json);
                    }
                } else {
                    if ($push_type == 'topic') {
                        $json = $push->getPush();
                        $response = $firebase->sendToTopic('global', $json);
                    } else if ($push_type == 'multiple') {
                       $json = $push->getPush();
                       $response = $firebase->sendMultiple($regIds, $json);
                    }
                }
                    if ($response) {
                        $date = date('d-m-Y');
                        $status="Send";
                        $schedule_date="today";
                        $id=$this->Console_Model->update_notifications($status,$total_users,$notification_id);
                        $size=sizeof($regIds)-1;
                        for($s=0;$s < $users_no;$s++) {
                            if($regIds[$s]['gcmid'] != "(null)"){
                                $mobile_number=$this->Console_Model->get_mobile_number($brand, $device,$regIds[$s]['gcmid']);
                                if($mobile_number != 0)
                                    $this->Console_Model->save_notification_details($id,$mobile_number,$status);
                            }
                        }
                        $data = array(
                            'status' => 'success',
                            'response' => $response,
                            'to_sent' => $to_sent,
                            'size' => $total_users
                        );   
                    }else{
                        $date = date('d-m-Y');
                        $status="Not Send";
                        $schedule_date="today";
                        $data=$this->Console_Model->update_notifications($status,$total_users,$notification_id);
                        $data = array(
                            'status' => 'failed',
                            'response' => "Not send",
                            'to_sent' => $to_sent,
                            'size' => $total_users
                        );
                    }
                }
            }
            
        }
        echo json_encode($data);
    } else {
        echo('No direct script access is allowed');
    }
   }
   public function download_report()
   {
    if ($this->input->is_ajax_request()) { 
        $device = $this->input->post('device');
        $title = $this->input->post('title');
        $brand = $this->input->post('brand');
        $image_url = $this->input->post('image_url');
        $message = $this->input->post('message');
        $location = $this->input->post('location');
      
        $automate = $this->input->post('automate');
        if ($brand == 'Fabricspa') {
            $sender_id = 'PCT0000001';
        }
        else {
            $sender_id = 'PCT0000014';
        }
        if($automate =="false") {
            // $start = $body['start'];   
            // $limit = $body['limit'];
            $start = $this->input->post('start');
            $limit = $this->input->post('limit');
            if($start =="1") {
                $dif=$limit;
            }else {
                $dif=$limit-$start;
                $dif=$dif+1;
            }
            $fraction = $dif / 100;
       
            $x=fmod($fraction,1);
            if($x != "0") {
                $whole = floor($fraction);
                $fraction = $whole+1;
            }
            $fraction=$fraction+1;
            $res = $this->Console_Model->get_notification_data($fraction); 
        }else if($automate =="true") {
            $total_users=$this->Console_Model->get_total_users($device,$brand,$location);
            $fraction=$total_users/100;
            $x=fmod($fraction,1);
            if($x != "0") {
                $whole = floor($fraction);
                $fraction = $whole+1;
            }
            $fraction=$fraction+1;
            $data=$this->Console_Model->get_notification_data($fraction);
            $total="0";
            foreach($data as $n) {
                if($n['sender_id'] == $sender_id && $n['device'] == $device) {
                    $total = $total +1;
                }
            }
            $res = $this->Console_Model->get_notification_data($total);
        }else{
            
            $fraction =$this->input->post('size');
            // $fraction = $body['size'];
            $res = $this->Console_Model->get_notification_data($fraction);
        }
       
        $j = 2;
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
     
      

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Contact #');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Message');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Subject');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Sender ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Device name');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Location');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Send Date & Time');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Sent with image or not?');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Image Link');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Status');
        $total_results = sizeof($res);
        $k="0";
        for ($i = 0; $i < $total_results; $i++) {
            if($res[$i]['message']==$message   && $title == $res[$i]['title'] && $sender_id == $res[$i]['sender_id'] && $device == $res[$i]['device'] && $location == $res[$i]['location'] && $image_url == $res[$i]['image_url']) {
               
                $k=$k+1;
                if($res[$i]['mobile_numbers'] != "false") {
                    $data=str_replace('"mobile_number":','', $res[$i]['mobile_numbers']);
                    $text1=str_replace("{",'',$data);
                    $text2=str_replace("}",'',$text1);
                    $text3=str_replace("[",'',$text2);
                    $text4=str_replace("]",'',$text3);
                    // $text5=trim($text4,'"');
                    $text5=str_replace('"', "", $text4);
                    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $text5);
                    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $res[$i]['message']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $res[$i]['title']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $res[$i]['sender_id']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $res[$i]['device']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $res[$i]['location']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $res[$i]['date']);
                    if($res[$i]['image_url'] ==""){
                        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $j, "Without Image");
                    }else {
                        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $j, "With image");
                    }
                    $objPHPExcel->getActiveSheet()->SetCellValue('I' . $j, $res[$i]['image_url']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('J' . $j, $res[$i]['status']);
                    $j++;
                }
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('Notifications');


        //Determining file name based on brand_code
        $folder_name = $brand;
        // Set properties

        $objPHPExcel->getProperties()->setCreator(ADMIN_USERNAME);
        $objPHPExcel->getProperties()->setLastModifiedBy(ADMIN_USERNAME);
        $objPHPExcel->getProperties()->setTitle($folder_name . "Notification Details");
        $objPHPExcel->getProperties()->setSubject("Notification Details");
        $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $date = date('Y-m-d-H-i-s');
        //Final file name would be;
        $file_name = $folder_name .$device. '_Notification_' . $date . '.xls';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename='.$file_name.'.xls');
        header('Cache-Control: max-age=0');
        $target_file = 'excel_reports/' . $folder_name . '/' . $file_name;

        $save_status = $objWriter->save($target_file);
        $data = file_get_contents($target_file);
      
        // $data=force_download($target_file);

        $data = array(
            'status' => 'success',
            'file' => $file_name
          
        );


        echo json_encode($data);
        
    } else {
        echo('No direct script access is allowed');
    }
   }
   /**
    * Method to Download notification detaails as excel file
    */
    public function download_notification_report()
    {
        if ($this->input->is_ajax_request()) { 
            $device = $this->input->post('device');
            $brand = $this->input->post('brand');
            $location = $this->input->post('location');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $status = $this->input->post('status');
            $res = $this->Console_Model->get_notification_data_for_report($brand,$device,$location,$start_date,$end_date,$status);
            $n='';
            $j = 2;
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);

            $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
        
        

            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Contact #');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Message');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Subject');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Sender ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Device name');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Location');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Sent with image or not?');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Image Link');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Schedule Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Status');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Created By');
            for($n =0;$n < sizeof($res);$n++){
                $users_details= $this->Console_Model->get_userdata_for_report($res[$n]['notification_id']);
                $d='';
                for($d=0;$d<sizeof($users_details);$d++ ){
                    $date = date("m-d-Y", strtotime($res[$n]['date']));
                    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $users_details[$d]['mobile_number']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $res[$n]['message']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $res[$n]['title']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $res[$n]['sender_id']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $res[$n]['device']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $res[$n]['location']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $date);
                    if($res[$n]['image_url'] ==""){
                        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $j, "Without Image");
                    }else {
                        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $j, "With image");
                    }
                    $objPHPExcel->getActiveSheet()->SetCellValue('I' . $j, $res[$n]['image_url']);
                    if($res[$n]['schedule_date'] ==NULL){
                        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $j, "");
                    }elseif($res[$n]['schedule_date'] =="01-01-1970"){
                        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $j, "");
                    }else{
                        $schedule_date = date("d-m-Y", strtotime($res[$n]['schedule_date']));
                        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $j, $schedule_date);
                    }
                    $objPHPExcel->getActiveSheet()->SetCellValue('K' . $j, $res[$n]['status']);
                     $objPHPExcel->getActiveSheet()->SetCellValue('L' . $j, $res[$n]['created_by']);
                    $j++;
                }
            }
            $objPHPExcel->getActiveSheet()->setTitle('Notifications');

            //Determining file name based on brand_code
            if($brand !="no")
                $folder_name = $brand;
            else 
                $folder_name = 'Fabspa_C2W';
            if($device == "no")
                $device="default";
            // Set properties
    
            $objPHPExcel->getProperties()->setCreator(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setLastModifiedBy(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setTitle($folder_name . "Notification Details");
            $objPHPExcel->getProperties()->setSubject("Notification Details");
            $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");
    
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $date = date('Y-m-d-H-i-s');
            //Final file name would be;
            $file_name = $folder_name .$device. '_Notification_' . $date . '.xls';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $target_file = 'excel_reports/' . $folder_name . '/' . $file_name;
    
            $save_status = $objWriter->save($target_file);
            $data = file_get_contents($target_file);
          
            // $data=force_download($target_file);
    
            $data = array(
                'status' => 'success',
                'file' => $file_name,
              
            );
    
    
            echo json_encode($data);
    
        } else {
            echo('No direct script access is allowed');
        }
    }
    /**
     * Get GCMIDs of all users
     *Send the notification to the corresponding GCMIDs
     */
    // public function get_send_gcmids()
    // {
    //     if ($this->input->is_ajax_request()) {
    //         $device = $this->input->post('device');
    //         $title = $this->input->post('title');
    //         $image_url = $this->input->post('image_url');
    //         $brand = $this->input->post('brand');
    //         $location = $this->input->post('location');
    //         $message = $this->input->post('message');
    //         $start = $this->input->post('start');
    //         $limit = $this->input->post('limit');
    //         $i = '';
    //         for($i=0;$i<$limit;$i=$i+1000){
    //             if($i>=1000){
    //                 $start = $start+1000;
    //             }
    //             $dif = $limit-$i;
    //             if($dif >= 1000 ) {
    //                 $limit =1000;
    //                 $data = $this->Console_Model->get_all_gcmids($brand, $device, $location, $start, $limit);
    //                 $gcmids=$data['gcmids'];
    //                 $mobile_numbers=$data['mobile_number'];
    //             }else{
    //                 $limit = $dif;
    //                 $data = $this->Console_Model->get_all_gcmids($brand, $device, $location, $start, $dif);
    //                 $gcmids=$data['gcmids'];
    //                 $mobile_numbers=$data['mobile_number'];
    //             }
    //             if ($brand == 'Fabricspa')
    //                 $brand_code = 'PCT0000001';
    //             else if ($brand == 'Click2Wash')
    //                 $brand_code = 'PCT0000014';
    
    //             // $library_params = array('brand_code' => $brand_code);
    //             // $this->load->library('push_notification/firebase', $library_params);
    //             // $this->load->library('push_notification/push');
    //             // print_r($gcmids);
                
    //                 $total_users = sizeof($gcmids);
    
    
    //             // $firebase = new Firebase($library_params);
    //             //$firebase = $this->firebase;
    //             // $push = new Push();
    
    //             // optional payload
    //             $payload = array();
    //             $payload['sound'] = 'default';
    //             $payload['brand_code'] = $brand_code;
    //             $payload['type'] = 'normal';
    
    //             // notification title
    //             //$title = 'Test';
    
    //             // notification message
    //             //$message =  'Test Test';
    
    //             // push type - single user / topic
    //             $push_type = 'multiple';
    
    //             // whether to include to image or not
    //             if (isset($image_url))
    //                 $include_image = TRUE;
    //             else
    //                 $include_image = FALSE;
    
    
    //             // $push->setTitle($title);
    //             // $push->setMessage($message);
    //             if ($include_image) {
    //                 // $push->setImage($image_url);
    //             } else {
    //                 // $push->setImage('');
    //             }
    //             // $push->setIsBackground(FALSE);
    //             // $push->setPayload($payload);
    
    //             $json = '';
    //             $response = '';
    //             $regIds = array();
    
    //             //Getting GCMIDs from Pref file or database. If pref GCMID list are empty, then select from DB.
    //             // for ($i = 0; $i < $total_users; $i++) {
    //             //     if ($gcmids[$i] != '' || !empty($gcmids[$i]))
    //             //         array_push($regIds, $gcmids[$i]);
    //             // }
    //       //print_r($gcmids);
    
    
    
    //             if ($device == 'android') {
    //                 if ($push_type == 'topic') {
    //                     // $json = $push->getPush();
    //                     // $response = $firebase->sendToTopic('global', $json); 
    //                     $response = "test";     
    //                 } else if ($push_type == 'multiple') {
    //                     // $json = $push->getPush();
    //                     // $response = $firebase->sendMultiple($regIds, $json);
    //                     $response = "test";
    //                 }
    //             // } else if ($device == 'ios') {
    //             //     if ($push_type == 'topic') {
    //             //         // $json = $push->getPush();
    //             //         // $response = $firebase->sendToTopic('global', $json);
    //             //         $response = "test";
    //             //     } else if ($push_type == 'multiple') {
    //             //         //$json = $push->getPush();
    //             //         // $data = $push->getPushIOS();
    //             //         // $response = $firebase->sendMultipleIOS($regIds, $push->getPushNotificationIOS(), $data);
    //             //         $response = "test";
    //             //     }
    //             // } else {
    //             //     $response = FALSE;
    //             // }
    
    
    //             // if ($response) {
    //             //     $data = array(
    //             //         'status' => 'success',
    //             //         'response' => $response,
    //             //         'gcmids' => $gcmids,
    //             //         'size' => $total_users
    //             //     );
    //             // } else {
    //             //     $data = array(
    //             //         'status' => 'failed',
    //             //         'response' => $response
    //             //     );
    //             // }
    //             } else {
    //                     if ($push_type == 'topic') {
    //                         // $json = $push->getPush();
    //                         // $response = $firebase->sendToTopic('global', $json);
    //                         $response = "test";
    //                     } else if ($push_type == 'multiple') {
    //                         //$json = $push->getPush();
    //                         // $data = $push->getPushIOS();
    //                         // $response = $firebase->sendMultipleIOS($regIds, $push->getPushNotificationIOS(), $data);
    //                         $response = "test";
    //                     }
    //                 }
    //                 if ($response) {
    //                      // $date = new DateTime();
    //                     // for ($i = 0; $i < $total_users; $i=$i+100) {
    //                         // $dif = $total_users - $i;
    //                         // if($dif > 100 || $dif == '100') {
    //                             // $gcmids= array_slice($to_sent,$i,100);
    //                             // $this->Console_Model->save_notified_users($date,$gcmids, $title,$image_url,$message);
    //                         // }else {
    //                             // $limit =$dif+1;
    //                             // $gcmids= array_slice($to_sent,$i,$limit);
    //                             // $this->Console_Model->save_notified_users($date,$gcmids, $title,$image_url,$message);
    //                         // }
    //                     // }
    //                         $data = array(
    //                             'status' => 'success',
    //                             'response' => $response,
    //                             'to_sent' => $to_sent,
    //                             'size' => $total_users
    //                         );
                            
                           
    //                     }    
    //         }
    //         echo json_encode($data);

    //     }else {
    //         echo('No direct script access is allowed');
    //     }
    // }

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
            $via = $this->input->post('via');
            $flag = $this->input->post('flag');
            $city = $this->input->post('city');
            $user = $this->input->post('user');
             $tnc_status = $this->input->post('tnc_status');
            if($tnc_status == 1)
                $message = $message."(T&C Apply)";
            $total = 0;
            $time = "";
            $date = date('d-m-Y');
            $status="Not Send";
            $schedule_date="today";
            $total_users="0";
            $id=$this->Console_Model->save_notifications($date,$brand,$sender_id,$device,$location,$title,$image_url,$message,$via,$status,$schedule_date,$total_users,$user,$time);
            if($location != "" && $location != "all"){
                $cities = $location;
                $string ="";
                for($i=0;$i<sizeof($location);$i++){
                    if($i != sizeof($location)-1)
                        $string = $string.$location[$i].',';
                    else    
                        $string = $string.$location[$i];
                }
                $location = $string;
                for($s=0;$s<sizeof($cities);$s++){
                    $no = $this->Console_Model->get_total_receivers($brand,$device,$cities[$s]);
                    $total = $total+$no;
                }
            }else if($location == "all"){
                $states = $this->Console_Model->get_states_sp();
                $cities=[];
                for($i=0;$i<sizeof($states);$i++){
                    $cities[$i] = $this->Console_Model->get_state_cities_sp($states[$i]['statecode']);
                    for($p=0;$p<$cities[$i];$p++){
                        $no = $this->Console_Model->get_total_receivers($brand,$device,$cities[$i][$p]['cityname']);
                        $total = $total+$no;
                    }
                }
                $total_users = $total;
            }else{
                $no = $this->Console_Model->get_total_receivers($brand,$device,$city);
                $total = $total+$no;
                $total_users = $total;
            }
            if ($brand == 'Fabricspa') {
                $brand_code = 'PCT0000001';
                $sender_id="PCT0000001";
            }
            else if ($brand == 'Click2Wash') {
                $brand_code = 'PCT0000014';
                $sender_id="PCT0000014";
            }
            else
                $brand_code = NULL;
                // if($device == 'all'){
                //     $gcmids = $this->Console_Model->get_all_gcmid($brand,$city, $start, $limit);
                // }else{
                    $gcmids= $this->Console_Model->get_all_gcmids($brand, $device, $city, $start, $limit);
                // }
                for ($j = 0; $j < sizeof($gcmids); $j++) {
                    array_push($to_sent, $gcmids[$j]['gcmids']);
                }
                $total_users = sizeof($to_sent);
                if($location != "" && $total < 299)
                    $total_users = $total;
                
                    $library_params = array('brand_code' => $brand_code);
                    $this->load->library('push_notification/firebase', $library_params);
                    $this->load->library('push_notification/push');
    
    
                    $firebase = new Firebase($library_params);
                    //$firebase = $this->firebase;
                    $push = new Push();
    
                    // optional payload
                    $payload = array();
                    $payload['sound'] = 'default';
                    $payload['brand_code'] = $brand_code;
                    $payload['type'] = 'normal';
                    $payload['notification_id'] = $notification_id;
    
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
                        $push->setNotificationid($notification_id);
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
                for ($i = 0; $i < sizeof($to_sent); $i++) {
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
                        $json = $push->getPush();
                        $response = $firebase->sendMultiple($regIds, $json);
                    }
                }else {
                    if ($push_type == 'topic') {
                        $json = $push->getPush();
                        $response = $firebase->sendToTopic('global', $json);
                    } else if ($push_type == 'multiple') {
                        $json = $push->getPush();
                        $response = $firebase->sendMultiple($regIds, $json);
                    }
                }
              
                if ($response) {
                    $date = date('d-m-Y');
                    $status="Send";
                    $schedule_date="today";
                    if($flag == 1){
                        $id=$this->Console_Model->update_notifications($status,$limit,$notification_id);
                    }else{
                        $id=$this->Console_Model->get_notification_id();
                        $id=$id[0]['notification_id'];
                    }
                    $i='';
                    $size=sizeof($regIds);
                    for($i=0;$i < $size;$i++) {
                        if($regIds[$i] != "(null)"){
                            $mobile_number=$this->Console_Model->get_mobile_number($brand, $device, $regIds[$i]);
                            if($mobile_number != 0)
                                $this->Console_Model->save_notification_details($id,$mobile_number,$status);
                        }
                    }
                    $data = array(
                        'status' => 'success',
                        'response' => $response,
                        'to_sent' => $to_sent,
                        'size' => $total_users
                    );
                }else {
                    $date = date('d-m-Y');
                    $status="Not Send";
                    $schedule_date="today";
                    $id=$this->Console_Model->update_notifications($status,$limit,$notification_id);
                    $data = array(
                            'status' => 'failed',
                            'response' => $response,
                            'size' => 0
                        );
                }
                echo json_encode($data);                           
        } else {
            echo('No direct script access is allowed');
        }
    }
/**
 * Scheduling notifications to all users 
 */
public function schedule_automated_gcmids()
{
    if ($this->input->is_ajax_request()) {
        $device = $this->input->post('device');
        $to_sent = [];
        $title = $this->input->post('title');
        $brand = $this->input->post('brand');
        $image_url = $this->input->post('image_url');
        $message = $this->input->post('message');
        $location = $this->input->post('location');
        $city = $this->input->post('city');
        $start = $this->input->post('start');
        $limit = $this->input->post('limit');
        $via = $this->input->post('via');
        $schedule_date = $this->input->post('schedule_date');
        $flag=$this->input->post('flag');
        $user = $this->input->post('user');
        $time = $this->input->post('time_slot');
        $tnc_status = $this->input->post('tnc_status');
        if($tnc_status == 1)
                $message = $message."(T&C Apply)";
        if ($brand == 'Fabricspa') {
            $brand_code = 'PCT0000001';
            $sender_id = 'PCT0000001';
        }
        else {
            $brand_code = 'PCT0000014';
            $sender_id = 'PCT0000014';
        }
        $total = 0;
        if($location != "" && $location != "all"){
            $cities = $location;
            $string ="";
            for($i=0;$i<sizeof($location);$i++){
                if($i != sizeof($location)-1)
                    $string = $string.$location[$i].',';
                else    
                    $string = $string.$location[$i];
            }
            $location = $string;
            for($s=0;$s<sizeof($cities);$s++){
                $no = $this->Console_Model->get_total_receivers($brand,$device,$cities[$s]);
                $total = $total+$no;
            }
        }else if($location == "all"){
            $states = $this->Console_Model->get_states_sp();
            $cities=[];
            for($i=0;$i<sizeof($states);$i++){
                $cities[$i] = $this->Console_Model->get_state_cities_sp($states[$i]['statecode']);
                for($p=0;$p<$cities[$i];$p++){
                    $no = $this->Console_Model->get_total_receivers($brand,$device,$cities[$i][$p]['cityname']);
                    $total = $total+$no;
                }
            }
            $total_users = $total;
        }else{
            $no = $this->Console_Model->get_total_receivers($brand,$device,$city);
            $total = $total+$no;
            $total_users = $total;
        }
        if($total < 299)
            $limit = $total;
        $to_sent=array();
        $j='';
    
        $gcmids= $this->Console_Model->get_all_gcmids($brand, $device, $city, $start, $limit);
        for ($j = 0; $j < sizeof($gcmids); $j++) {

            array_push($to_sent, $gcmids[$j]['gcmids']);
        }
        $date = date('d-m-Y');
        $status="Scheduled";
        if($flag == 1) {
            $id=$this->Console_Model->save_notifications($date,$brand,$sender_id,$device,$location,$title,$image_url,$message,$via,$status,$schedule_date,$total,$user,$time);
        }else{
            $id=$this->Console_Model->get_notification_id();
            $id=$id[0]['notification_id'];
        }
        $i='';
        $size=sizeof($to_sent);
        for($i=0;$i < $size;$i++) {
            $mobile_number=$this->Console_Model->get_mobile_number($brand, $device, $to_sent[$i]);
            if($mobile_number != 0)
                $this->Console_Model->save_notification_details($id,$mobile_number,$status);
        }
        $data = array(
            'status' => 'success',
            'to_sent' => $gcmids,
            'size' => $size
        );
        echo json_encode($data);

     } else {
            echo('No direct script access is allowed');
    }
}
/**
 * Method to send scheduled notifications
 */
    public function send_scheduled_notifications()
    {
        $date = date('Y-m-d');
        $format = "%Y-%m-%d %h:%i %A";
        $today = mdate($format);
        $current_time = date('H',strtotime($today));
        if($current_time == 8)
            $time = "8 AM";
        else if($current_time == 9)
            $time = "9 AM";
        else if($current_time == 10)
            $time = "10 AM";
        else if($current_time == 11)
            $time = "11 AM";
        else if($current_time == 12)
            $time = "12 PM";
        else if($current_time == 13)
            $time = "1 PM";
        else if($current_time == 14)
            $time = "2 PM";
        else if($current_time == 15)
            $time = "3 PM";
        else if($current_time == 16)
            $time = "4 PM";
        else if($current_time == 17)
            $time = "5 PM";
        else if($current_time == 18)
            $time = "6 PM";
        else if($current_time == 19)
            $time = "7 PM";
        else
            $time = "";
        if($time != ""){
            $notifications = $this->Console_Model->get_scheduled_notifications($date,$time);       
            $i='';
            if($notifications){
            for($i =0;$i<sizeof($notifications);$i++)
            {
                $brand_code=$notifications[$i]['sender_id'];
                $library_params = array('brand_code' => $brand_code);
                $this->load->library('push_notification/firebase', $library_params);
                $this->load->library('push_notification/push');

                $mobile_numbers=$this->Console_Model->get_mobileno_of_scheduled_notifications($notifications[$i]['notification_id']);
                $gcmids = array();
                if($mobile_numbers){
                    $j='';
                        for($j=0;$j<sizeof($mobile_numbers);$j++){
                            $gcm_id = $this->Console_Model->get_gcmid_from_mobileno($mobile_numbers[$j]['mobile_number'],$notifications[$i]['brand']);
                            array_push($gcmids,$gcm_id[0]);
                        }
                        if(sizeof($gcmids) <= 299){
                            $to_sent = $gcmids;
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
                        if (isset($notifications[$i]['image_url']))
                            $include_image = TRUE;
                        else
                            $include_image = FALSE;


                        $push->setTitle($notifications[$i]['title']);
                        $push->setMessage($notifications[$i]['message']);
                        if ($include_image) {
                            $push->setImage($notifications[$i]['image_url']);
                        } else {
                            $push->setImage('');
                        }
                        $push->setIsBackground(FALSE);
                        $push->setPayload($payload);

                        $json = '';
                        $response = '';
                        $regIds = array();
                            //Getting GCMIDs from Pref file or database. If pref GCMID list are empty, then select from DB.
                            for ($k = 0; $k < $total_users; $k++) {
                                if($to_sent[$k] != "")
                                    array_push($regIds, $to_sent[$k]);
                            }
                            if ($notifications[$i]['device'] == 'android') {
                                if ($push_type == 'topic') {
                                    $json = $push->getPush();
                                    $response = $firebase->sendToTopic('global', $json);   
                                } else if ($push_type == 'multiple') {
                                    $json = $push->getPush();
                                    $response = $firebase->sendMultiple($regIds, $json);
                                }
                            } else if ($notifications[$i]['device'] == 'ios') {
                                if ($push_type == 'topic') {
                                    $json = $push->getPush();
                                    $response = $firebase->sendToTopic('global', $json);
                                } else if ($push_type == 'multiple') {
                                    $json = $push->getPush();
                                    $response = $firebase->sendMultiple($regIds, $json);
                                }
                            } else {
                                    if ($push_type == 'topic') {
                                        $json = $push->getPush();
                                        $response = $firebase->sendToTopic('global', $json);
                                    } else if ($push_type == 'multiple') {
                                        $json = $push->getPush();
                                        $response = $firebase->sendMultiple($regIds, $json);
                                    }
                            }
                            
                                if ($response) {
                                    $status = 'Send';
                                
                                }else{
                        
                                    $status = 'Not Send';
                                    
                                }
                            $this->Console_Model->update_send_status($notifications[$i]['notification_id'],$status) ;
                        }else{
                            $t = '';
                            $size = sizeof($gcmids);
                            for($t=0;$t<$size;$t=$t+299){
                                $dif = $size-$t;
                                if($dif >= 299 ) {
                                    $to_sent=array_splice($gcmids,$t,299);
                                }else{
                                    $to_sent=array_splice($gcmids,$t,$dif);
                                }
                                $firebase = new Firebase($library_params);
                                //$firebase = $this->firebase;
                                $push = new Push();
                                $brand_code =$notifications[$i]['sender_id'];
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
                                if (isset($notifications[$i]['image_url']))
                                    $include_image = TRUE;
                                else
                                    $include_image = FALSE;
    
    
                                $push->setTitle($notifications[$i]['title']);
                                $push->setMessage($notifications[$i]['message']);
                                if ($include_image) {
                                    $push->setImage($notifications[$i]['image_url']);
                                } else {
                                    $push->setImage('');
                                }
                                $push->setIsBackground(FALSE);
                                $push->setPayload($payload);
    
                                $json = '';
                                $response = '';
                                $regIds = array();
    
                                $total_users = sizeof($to_sent);
                                //Getting GCMIDs from Pref file or database. If pref GCMID list are empty, then select from DB.
                                for ($k = 0; $k < $total_users; $k++) {
                                    if ($to_sent[$k] != '' || !empty($to_sent[$k]))
                                        array_push($regIds, $to_sent[$k]);
                                }
                                if ($notifications[$i]['device'] == 'android') {
                                    if ($push_type == 'topic') {
                                        $json = $push->getPush();
                                        $response = $firebase->sendToTopic('global', $json);     
                                    } else if ($push_type == 'multiple') {
                                        $json = $push->getPush();
                                        $response = $firebase->sendMultiple($regIds, $json);
                                    }
                                } else if ($notifications[$i]['device'] == 'ios') {
                                    if ($push_type == 'topic') {
                                        $json = $push->getPush();
                                        $response = $firebase->sendToTopic('global', $json);
                                    } else if ($push_type == 'multiple') {
                                        $json = $push->getPush();
                                        $response = $firebase->sendMultiple($regIds, $json);
                                    }
                                } else {
                                        if ($push_type == 'topic') {
                                            $json = $push->getPush();
                                            $response = $firebase->sendToTopic('global', $json);
                                        } else if ($push_type == 'multiple') {
                                            $json = $push->getPush();
                                            $response = $firebase->sendMultiple($regIds, $json);
                                        }
                                }
                                    if ($response) {
                                        $status = 'Send';
                                    }else{
                                        $status = 'Not Send';
                                    }
                                $this->Console_Model->update_send_status($notifications[$i]['notification_id'],$status) ;
                            }
                        }  
                        
                }
            }
            }

        }else{ echo "No notification is scheduled for now";}
    }
//**********************************************************************************************
    /*--ADD/EDIT/DELETE STORES..--*/
////////////////////////////////////////////////////////////////////////////////////////////////

    //Public method accessed by Admin angular app
    public function get_stores()
    {

        $stores = $this->Console_Model->get_stores();
        echo json_encode($stores);

    }

    public function save_store()
    {

        if (ADMIN) {
            $request = $this->generic->json_input();
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


                $saved_store = $this->Console_Model->save_store($store);

                $data = $saved_store;


            } else if ($mode == 'edit') {

                $updated_store = $this->Console_Model->update_store($store, $store_id);

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
            $stores = $this->generic->json_input();

            $delete_status = $this->Console_Model->delete_stores($stores['selected_stores']);

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
        $request = $this->generic->json_input();
        $brand_code = $request['BrandCode'];


        $cities = $this->Console_Model->get_stores_cities_sp($brand_code);

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
        $request = $this->generic->json_input();
        $city_code = $request['CityCode'];
        $brand_code = $request['BrandCode'];
        $stores = $this->Console_Model->get_stores_sp($city_code, $brand_code);
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
        $this->load->library('encryption');


        $dcr_users = $this->Console_Model->get_dcr_users();

        echo json_encode($dcr_users);

    }


    /**
     *Saving a DCR user from the console.
     */
    public function save_dcr_user()
    {

        if (ADMIN) {
            $request = $this->generic->json_input();
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
            $this->load->library('encryption');


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


                $saved_dcr_user = $this->Console_Model->save_dcr_user($dcr_user);

                $data = $saved_dcr_user;


            } else if ($mode == 'edit') {

                $updated_dcr_user = $this->Console_Model->update_dcr_user($dcr_user, $dcr_user_id);

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
            $dcr_users = $this->generic->json_input();

            $delete_status = $this->Console_Model->delete_dcr_users($dcr_users['selected_dcr_users']);

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
     *Saving a qa user from the console.
     */
    public function save_qa_user()
    {

        if (ADMIN) {
            $request = $this->generic->json_input();
            $saved_qa_user = array();

            $mode = $request['mode'];
            $qa_user_name = $request['name'];
            $qa_user_contactno = $request['contactno'];


            $qa_user_passwd = $request['passwd'];


            if (array_key_exists('id', $request)) {
                $qa_user_id = $request['id'];
            } else {
                $qa_user_id = NULL;
            }


            /*Loading Encryption library*/
            $this->load->library('encryption');


            /*Creating unix time stamp*/
            $date = date('Y-m-d H:i:s');


            $qa_user = array(
                'Date' => $date,
                'Name' => $qa_user_name,
                'Phone' => $qa_user_contactno,
                'Password' => $qa_user_passwd,
                'CreatedBy' => ADMIN_USERNAME

            );


            if ($mode == 'add') {

                $saved_qa_user = $this->Console_Model->save_qa_user($qa_user);

                $data = $saved_qa_user;


            } else if ($mode == 'edit') {

                $updated_qa_user = $this->Console_Model->update_qa_user($qa_user, $qa_user_id);

                $data = $updated_qa_user;

            }


            echo json_encode($data);

        } else {
            $this->home();
        }

    }

    //Public method accessed by Admin angular app
    public function get_qa_users()
    {

        /*Loading the library for decryption*/
        $this->load->library('encryption');


        $qa_users = $this->Console_Model->get_qa_users();

        echo json_encode($qa_users);

    }

    public function delete_qa_users()
    {

        if (ADMIN) {
            $delete_status = FALSE;
            $qa_users = $this->generic->json_input();

            $delete_status = $this->Console_Model->delete_qa_users($qa_users['selected_qa_users']);

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
     *Saving a qa user from the console.
     */
    public function save_qa_finished_by_user()
    {

        if (ADMIN) {
            $request = $this->generic->json_input();
            $saved_qa_finished_by_user = array();

            $mode = $request['mode'];
            $qa_finished_by_user_name = $request['name'];


            if (array_key_exists('id', $request)) {
                $qa_finished_by_user_id = $request['id'];
            } else {
                $qa_finished_by_user_id = NULL;
            }


            /*Creating unix time stamp*/
            $date = date('Y-m-d H:i:s');


            $qa_finished_by_user = array(
                'Date' => $date,
                'Name' => $qa_finished_by_user_name,
                'CreatedBy' => ADMIN_USERNAME

            );


            if ($mode == 'add') {

                $saved_qa_finished_by_user = $this->Console_Model->save_qa_finished_by_user($qa_finished_by_user);

                $data = $saved_qa_finished_by_user;


            } else if ($mode == 'edit') {

                $updated_qa_finished_by_user = $this->Console_Model->update_qa_finished_by_user($qa_finished_by_user, $qa_finished_by_user_id);

                $data = $updated_qa_finished_by_user;

            }


            echo json_encode($data);

        } else {
            $this->home();
        }

    }

    //Public method accessed by Admin angular app
    public function get_qa_finished_by_users()
    {

        /*Loading the library for decryption*/
        $this->load->library('encryption');


        $qa_finished_by_users = $this->Console_Model->get_qa_finished_by_users();

        echo json_encode($qa_finished_by_users);

    }

    public function delete_qa_finished_by_users()
    {

        if (ADMIN) {
            $delete_status = FALSE;
            $qa_finished_by_users = $this->generic->json_input();

            $delete_status = $this->Console_Model->delete_qa_finished_by_users($qa_finished_by_users['selected_qa_finished_by_users']);

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

        if (ADMIN) {


            $logs = $this->Console_Model->get_qa_logs();

            /*$indexedOnly = array();

            foreach ($logs as $row) {
                $indexedOnly[] = array_values($row);
            }*/

            $result = array(
                'logs' => $logs
            );

            echo json_encode($result);


        } else {
            echo 'invalid';
        }


    }

    /**
     *Searching the QA logs to find result.
     */
    public function qa_search_tag_id()
    {

        if (ADMIN) {
            $request = $this->generic->json_input();


            $logs = $this->Console_Model->qa_search_tag_id($request['keyword']);

            $qa_reasons = $this->Console_Model->get_qa_reasons();


            for ($i = 0; $i < sizeof($logs); $i++) {
                $log_reason_array = explode(', ', $logs[$i]['Reason']);
                for ($j = 0; $j < sizeof($qa_reasons); $j++) {

                    /*Default value for all reasons*/
                    $logs[$i][$qa_reasons[$j]['Reason']] = '<span style="color: red !important;" class="uk-margin-small-right" uk-icon="icon: close; ratio: 1.5"></span>';
                }

                foreach ($logs[$i] as $key => $value) {
                    for ($k = 0; $k < sizeof($log_reason_array); $k++) {
                        if ($key == $log_reason_array[$k]) {
                            $logs[$i][$key] = '<span style="color: #0f6ecd !important;" class="uk-margin-small-right" uk-icon="icon: check; ratio: 1.5"></span>';
                        } else {

                        }
                    }
                }

            }

            $result = array(
                'logs' => $logs
            );

            echo json_encode($result);
        }
    }

    /**
     *Getting the data needed for rendering a pie chart for the QA logs
     */
    public function get_qa_data_for_chart()
    {

        if (ADMIN) {

            $reasons = $this->Console_Model->get_qa_reasons();

            $res = [];
            for ($i = 0; $i < sizeof($reasons); $i++) {
                $pie_chart_data['reason'] = $reasons[$i]['Reason'];
                $pie_chart_data['count'] = $this->Console_Model->get_qa_data_for_chart($reasons[$i]['Reason']);
                array_push($res, $pie_chart_data);
            }

            echo json_encode($res);
        }

    }


    /**
     *Saving a qa reason from the console.
     */
    public function save_qa_reason()
    {

        if (ADMIN) {
            $request = $this->generic->json_input();
            $saved_qa_reason = array();

            $mode = $request['mode'];

            $qa_reason = $request['qa_reason'];


            if (array_key_exists('id', $request)) {
                $qa_reason_id = $request['id'];
            } else {
                $qa_reason_id = NULL;
            }


            $qa_reason = array(
                'Reason' => $qa_reason

            );


            if ($mode == 'add') {

                $saved_qa_reason = $this->Console_Model->save_qa_reason($qa_reason);

                $data = $saved_qa_reason;


            } else if ($mode == 'edit') {

                $updated_qa_reason = $this->Console_Model->update_qa_reason($qa_reason, $qa_reason_id);

                $data = $updated_qa_reason;

            }


            echo json_encode($data);

        } else {
            $this->home();
        }

    }


    /**
     *Getting the predefined QA reasons from QA_Reasons
     */
    public function get_qa_reasons()
    {


        $qa_reasons = $this->Console_Model->get_qa_reasons();

        echo json_encode($qa_reasons);

    }

    /**
     *Deleting the predefined reasons from QA_Reasons
     */
    public function delete_qa_reasons()
    {

        if (ADMIN) {
            $delete_status = FALSE;
            $qa_reasons = $this->generic->json_input();

            $delete_status = $this->Console_Model->delete_qa_reasons($qa_reasons['selected_qa_reasons']);

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
     *Generating the QA report for the logs
     */
    public function generate_qa_logs_report()
    {

        $request = $this->generic->json_input();

        $start_datetime = $request['start_datetime'];
        $end_datetime = $request['end_datetime'];

        $logs = $this->Console_Model->get_qa_logs_for_generate_report($start_datetime, $end_datetime);

        $target_file = NULL;

        if ($logs) {


            $objPHPExcel = new PHPExcel();


            $total_results = sizeof($logs);
            $total_amount = 0;


            $objPHPExcel->setActiveSheetIndex(0);

            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Tag No');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Reason');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Other Reason');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Finished By');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Submitted By');


            $j = 2;

            for ($i = 0; $i < $total_results; $i++) {


                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $logs[$i]['CreatedDate']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $logs[$i]['TagNo']);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $logs[$i]['Reason']);

                if (!$logs[$i]['OtherReason']) {
                    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, 'N/A');
                } else {

                    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $logs[$i]['OtherReason']);
                }

                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $logs[$i]['FinishedBy']);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $logs[$i]['SubmittedBy']);


                $j++;
            }


            //Merging deposit slip id column
            //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:A' . $j);

            //Merging name column.
            //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:F' . $j);

            $objPHPExcel->getActiveSheet()->setTitle('QA_Logs_Report');

            // Set properties

            $objPHPExcel->getProperties()->setCreator('JFSL - Console');
            //$objPHPExcel->getProperties()->setLastModifiedBy();
            $objPHPExcel->getProperties()->setTitle("QA Logs Report");
            $objPHPExcel->getProperties()->setSubject("Logs submitted by QA users");
            $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $date = date('Y-m-d-H-i-s');
            //Final file name would be;
            $file_name = 'QA_Logs_Report' . $date . '.xlsx';

            $target_file = 'excel_reports/QALogs/' . $file_name;

            // Auto size columns for each worksheetget
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

            if ($target_file) {

                $data = array('status' => 'success', 'message' => 'File generated successfully. <a href="' . base_url() . $target_file . '">Click here to download</a>', 'file' => $target_file);
            } else {
                $data = array('status' => 'failed', 'message' => 'Failed to generate file');
            }


        } else {
            $data = array('status' => 'failed', 'message' => 'No log records found');
        }


        echo json_encode($data);

    }


    //Public method accessed by Admin angular app
    public function get_qc_logs()
    {

        if (ADMIN) {


            $logs = $this->Console_Model->get_qc_logs();

            /*$indexedOnly = array();

            foreach ($logs as $row) {
                $indexedOnly[] = array_values($row);
            }*/

            $result = array(
                'logs' => $logs
            );

            echo json_encode($result);


        } else {
            echo 'invalid';
        }


    }

    /**
     *Searching the qc logs to find result.
     */
    public function qc_search_tag_id()
    {

        if (ADMIN) {
            $request = $this->generic->json_input();


            $logs = $this->Console_Model->qc_search_tag_id($request['keyword']);

            $qc_reasons = $this->Console_Model->get_qc_reasons();


            for ($i = 0; $i < sizeof($logs); $i++) {
                $log_reason_array = explode(', ', $logs[$i]['Reason']);
                for ($j = 0; $j < sizeof($qc_reasons); $j++) {

                    /*Default value for all reasons*/
                    $logs[$i][$qc_reasons[$j]['Reason']] = '<span style="color: red !important;" class="uk-margin-small-right" uk-icon="icon: close; ratio: 1.5"></span>';
                }

                foreach ($logs[$i] as $key => $value) {
                    for ($k = 0; $k < sizeof($log_reason_array); $k++) {
                        if ($key == $log_reason_array[$k]) {
                            $logs[$i][$key] = '<span style="color: #0f6ecd !important;" class="uk-margin-small-right" uk-icon="icon: check; ratio: 1.5"></span>';
                        } else {

                        }
                    }
                }

            }

            $result = array(
                'logs' => $logs
            );

            echo json_encode($result);
        }
    }

    /**
     *Getting the data needed for rendering a pie chart for the qc logs
     */
    public function get_qc_data_for_chart()
    {

        if (ADMIN) {

            $reasons = $this->Console_Model->get_qc_reasons();

            $res = [];
            for ($i = 0; $i < sizeof($reasons); $i++) {
                $pie_chart_data['reason'] = $reasons[$i]['Reason'];
                $pie_chart_data['count'] = $this->Console_Model->get_qc_data_for_chart($reasons[$i]['Reason']);
                array_push($res, $pie_chart_data);
            }

            echo json_encode($res);
        }

    }




    /*For testing purpose only*/

    /**
     *Method for testing
     */
    public function test1()
    {

        //$this->Console_Model->test();
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

        $available_time_slots = $this->Console_Model->test3();

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
        $stores = $this->Console_Model->get_all_stores();
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
        $res = $this->Console_Model->add_offer($title, $description, $expiry, $brand_code, $offer_image);
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
        $res = $this->Console_Model->save_offer($offer_id, $title, $description, $expiry, $brand_code);
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
        $res = $this->Console_Model->delete_offer($offer_id);
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
        $_FILES["files"]["name"][0] = time().$_FILES["files"]["name"][0];
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

        if (ADMIN) {


            $request = $this->generic->json_input();

            $search_with = $request['search_with'];

            $search_text = $request['search_text'];

            if ($search_with == 'egrn') {
                $customer_details = $this->Console_Model->get_customer_details_from_egrn($search_text);
                $transaction_info_details = $this->Console_Model->get_transaction_info_details_from_egrn($search_text);
            } else if ($search_with == 'payment_id') {
                $customer_details = $this->Console_Model->get_customer_details_from_payment_id($search_text);
                $transaction_info_details = $this->Console_Model->get_transaction_info_details_from_payment_id($search_text);
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
                $transaction_payment_info_details = $this->Console_Model->get_transaction_payment_info_details($payment_ids);
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
            $unsettled_orders = $this->Console_Model->get_unsettled_orders($customer_id, $brand_code);
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

            $update_status = $this->Console_Model->update_dcn($id, $dcn);
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

            $save_status = $this->Console_Model->save_transaction_payment_info($new_transaction_payment_info_entry);


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
            $settle_order = $this->Console_Model->settle_order($transaction_payment_info_id);
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


        $payment_details = $this->Console_Model->get_incomplete_payment_details($limit, $offset, $start_date, $end_date);


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


        $cities = $this->Console_Model->get_stores_cities_sp($brand_code);


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
    public function save_user()
    {

        if (ADMIN) {

            $request = $this->generic->json_input();

            $user_id = $request['user_id'];
            $name = $request['name'];
            $mobile_number = $request['phone'];
            $email = $request['email'];

            $update_status = $this->Console_Model->update_user_details($user_id, $name, $mobile_number, $email);


            if ($update_status['status']) {
                $data = array('status' => 'success', 'message' => 'Successfully updated the user details', 'user_details' => $update_status['user_details']);
            } else {
                $data = array('status' => 'failed', 'message' => 'Failed to update the user details');
            }

            echo json_encode($data);

        } else {
            echo 'no direct access allowed';
        }


    }

    /**
     * Pagination logic for the QA logs
     * @return array
     */
    private function log_pagination($qa_or_qc)
    {
        if ($qa_or_qc == 'QA')
            $pagination_url = 'console/qa_logs';
        else
            $pagination_url = 'console/qc_logs';
        $uri_segment = 3;

        $config = array();
        $config["base_url"] = base_url() . $pagination_url;

        if ($qa_or_qc == 'QA')
            $config["total_rows"] = $this->Console_Model->get_qa_logs_count();
        else
            $config["total_rows"] = $this->Console_Model->get_qc_logs_count();

        $config["per_page"] = 10;
        $config["uri_segment"] = $uri_segment;
        //$config['num_links'] = 2;
        $choice = $config["total_rows"] / $config["per_page"];
        //$choice=2;
        $num_links = round($choice);
        if ($num_links >= 2)
            $num_links = 2;

        $config["num_links"] = $num_links;
        $config['first_link'] = 'F';
        $config['last_link'] = 'L';
        $config['next_link'] = '>';
        $config['prev_link'] = '<';
        $config['use_page_numbers'] = TRUE;


        $this->pagination->initialize($config);

        $page = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;

        //Setting up the $offset variable to limit the query for pagination
        $offset = $page - 1;
        $offset = $offset * $config['per_page'];
        if ($offset < 0)
            $offset = 0;

        //$data["results"] = $this->Harithakam_Model->feed_pagination($config["per_page"], $page, $offset);
        $links = $this->pagination->create_links();

        $pagination = array('links' => $links, 'offset' => $offset, 'per_page' => $config['per_page']);

        return $pagination;
    }

    public function test($number = FALSE)
    {
        $pagination = $this->log_pagination('QA');

        $log = $this->Console_Model->get_paginated_logs($pagination['per_page'], $pagination['offset']);

        print_r($pagination);
    }

    public function test_data()
    {
        $logs = $this->Console_Model->get_qa_logs();

        $indexedOnly = array();

        foreach ($logs as $row) {
            $indexedOnly[] = array_values($row);
        }

        $result = array(
            'data' => $indexedOnly
        );

        echo json_encode($result);
    }


    /**
     *Getting the transactions from the table TransactionInfo.
     */
    public function get_payment_transactions($start_date = FALSE)
    {
        if (ADMIN) {

            if ($start_date) {
                $start_date_formatted = date("Y-m-d H:i:s", strtotime($start_date . ' 00:00:00'));
                $end_date = new DateTime(date($start_date_formatted));
                $end_date->modify('+7 day');
                $end_date_formatted = $end_date->format('Y-m-d H:i:s');

                //Date for display to the admin
                $from_date = $start_date . ' 00:00:00';
                $to_date = $end_date->format('d-m-Y H:i:s');

                //If the end date is greater than current date, then no need to show the end date. Just display from date and onwards.
                if ($to_date > date('Y-m-d H:i:s')) {
                    $to_date = NULL;
                }

            } else {
                //Default start date and end date
                $start_date_formatted = date("Y-m-d H:i:s", strtotime('2019-06-01 00:00:00'));
                $end_date_formatted = date('Y-m-d H:i:s');
                $from_date = NULL;
                $to_date = NULL;


            }


            $transactions = $this->Console_Model->get_transaction_details($start_date_formatted, $end_date_formatted);
            $data = $this->generic->final_data('DATA_FOUND');
            $data['data'] = $transactions;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $this->generic->json_output($data);

        } else {

            $data = $this->generic->final_data('INVALID');
            $this->generic->json_output($data);
        }
    }


    /**
     *Checking the status of a transaction with the Zaakpay payment gateway.
     */
    public function check_with_gateway()
    {

        $request = $this->generic->json_input();

        $payment_id = $request['payment_id'];
        $transaction_id = $request['transaction_id'];

        //Calling the Zaakpay check status API for transaction status
        $status = $this->get_status($payment_id);

        if ($status) {

            $successful_transaction_statuses = array('Transaction has been captured', 'Transaction Payout Initiated', 'Transaction Payout Completed');

            //If the transaction status is in the above array, money has been credited so a button can be displayed to inform settle.
            if (in_array($status, $successful_transaction_statuses)) {
                $settle_button = TRUE;
            } else {
                $settle_button = FALSE;
            }

            $is_verified = $this->Console_Model->get_transaction_verify_status($payment_id, $transaction_id);
            $data = $this->generic->final_data('DATA_FOUND');
            $data['pg_response'] = $status;
            $data['settle_button'] = $settle_button;
            $data['verify'] = $is_verified['ConsoleVerify'];
        } else {
            $data = $this->generic->final_data('DATA_NOT_FOUND');
        }

        $this->generic->json_output($data);

    }


    /**
     * Getting the transaction status from Zaakpay by calling API
     * @param $payment_id
     * @return bool
     */
    private function get_status($payment_id)
    {

        //Loading the Payment Gateway settings
        $this->load->library('Settings/PaymentGatewaySettings');


        //Loading the mobikwik checksum library
        $this->load->library('mobikwik/checksum.php');


        $secret = SECRET_KEY;
        $mid = MERCHANT_IDENTIFIER;

        $all = "'" . $mid . "''" . $payment_id . "''0''Check Status'";

        $checksum = Checksum::calculateChecksum($secret, $all);

        $post_data_str = "checksum=" . $checksum . "&merchantIdentifier=" . $mid . "&orderId=" . $payment_id . "&mode=0&submitButton=Check Status";


        if (IS_TESTING) {
            $url = 'http://zaakpaystaging.centralindia.cloudapp.azure.com:8080/api/checktransaction/V8';
        } else {
            $url = "https://api.zaakpay.com/checktransaction";
        }
        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $post_data_str,

        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //get response
        $output = curl_exec($ch);
        curl_close($ch);

        $formatted_1 = str_replace($payment_id, '', (str_replace($mid, "", (string)$output)));
        $without_checksum = str_replace($checksum, '', $formatted_1);

        //Getting the predefined Zaakpay status descriptions
        //$status_descriptions=ZAAKPAY_STATUS_DESCRIPTIONS;
        $status_descriptions = ['Fraud Detected', 'MerchantIdentifier field missing or blank', 'MerchantIdentifier not valid', 'OrderId field missing or blank', 'Mode field missing or blank', 'Mode received with request was not valid', 'Checksum received with request is not equal to what we calculated', 'Merchant Data not complete in our database', 'Checksum was blank', 'OrderId either not processed or Rejected', 'Merchant Identifier or Order Id was not valid', 'We could not find this transaction in our database', 'Transaction in Scheduled state', 'Transaction in Initiated state', 'Transaction in Processing state', 'Transaction has been authorized', 'Transaction has been put on hold', 'Transaction is incomplete', 'Transaction has been settled', 'Transaction has been cancelled', 'Data Validation success', 'Transaction has been captured', 'Transaction Refund Completed', 'Transaction Payout Initiated', 'Transaction Payout Completed', 'Transaction Payout Error', 'Transaction Refund Paid Out', 'Transaction Chargeback has been initiated', 'Transaction Chargeback is being processed', 'Transaction Chargeback has been accepted', 'Transaction Chargeback has been reverted', 'Transaction Chargeback revert is now complete', 'Transaction Refund Initiated', 'Your Bank has declined this transaction, please Retry this payment with another pay method', 'Transaction Refund Before Payout Completed'];

        $status = FALSE;
        foreach ($status_descriptions as $description) {
            if (strpos(strtolower($without_checksum), strtolower($description)) !== false) {
                $status = $description;
                break;
            }
        }

        return $status;
    }

    /**
     *Verifying a transaction from the console
     */
    public function verify_transaction()
    {

        $request = $this->generic->json_input();
        $payment_id = $request['payment_id'];
        $transaction_id = $request['transaction_id'];
        $verification_date = date('Y-m-d H:i:s');

        if (array_key_exists('settle', $request)) {
            $settle = $request['settle'];
        } else {
            $settle = 0;
        }

        $verify = $this->Console_Model->verify_transaction($transaction_id, $payment_id, $settle, $verification_date);

        if ($verify) {
            $data = $this->generic->final_data('DATA_UPDATED');
        } else {
            $data = $this->generic->final_data('DATA_UPDATE_FAILED');
        }

        $this->generic->json_output($data);

    }
     /**
     *To load the the campaign panel view
     */
    public function admin_fab_home()
    {
        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'FABHOME RATE MASTER';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }
            $service_type=array("Deep Cleaning","Office Cleaning","Home Cleaning");
            $rate_details = array();
            for($i = 0;$i<sizeof($service_type);$i++){
                $rate_details[$i] = $this->Console_Model->get_fabhome_rate_data($service_type[$i]);
            }
            $data = array('deepcleaning_rates' => $rate_details[0],'officecleaning_rates' => $rate_details[1],'homecleaning_rates' => $rate_details[2]);
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_fabhome_rates', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }
    /**
     * For fetching rate_details based on service type
     */
    public function get_fabhome_rate_data()
    {
        $request = $this->input->post();
        $service_type = $request['service_type']; 
        $rate_details = $this->Console_Model->get_fabhome_rate_data($service_type);
        if($rate_details){
            $data = array(
                "status" => "success",
                "rates" => $rate_details
            );
        }else{
            $data = array(
                "status" => "failed",
                "rates" => ""
            );
        }
        $this->generic->json_output($data);
    }
    /**
     * Function for saving new rates into db
     */
    public function add_rates()
    {
        $request = $this->input->post();
        $service_type = $request['service_type']; 
        $service = $request['service']; 
        $category = $request['category']; 
        $uom = $request['input_uom']; 
        $rate_per_uom = $request['rate_per_uom']; 
        $discount_perc = $request['discount_perc']; 
        $discount = $request['discount']; 
        $tax = $request['tax']; 
        $expiry = $request['expiry_date']; 
        $existing_active_data = $this->Console_Model->get_fabhome_rate_data($service_type);
        $existing=0;
        for($i=0;$i<sizeof($existing_active_data);$i++){
            if(strtolower($existing_active_data[$i]['service']) == strtolower($service) && strtolower($existing_active_data[$i]['category']) == strtolower($category) && $existing_active_data[$i]['active'] == 1 && $existing_active_data[$i]['expiry'] >= date('Y-m-d')){
                $existing= 1;
            }
        }
        if($existing == 0){
            $status = $this->Console_Model->save_rate_details($service_type,$service,$category,$uom,$rate_per_uom,$discount_perc,$discount,$tax,$expiry);
            if($status){
                $data = array(
                    "status" => "success",
                    "message" => "Saved successfully"
                );
            }else{
                $data = array(
                    "status" => "failed",
                    "message" => "Failed to save"
                );
            }
        }else{
            $data = array(
                "status" => "failed",
                "message" => "Sorry,an active rate is already present "
            );
        }
        $this->generic->json_output($data);
    }
    public function edit_fabhome_rate($id)
    {
        $rate_details = $this->Console_Model->get_rate_details_from_id($id);
        $data = array('rates' => $rate_details);
        $this->load->view('Admin/Base/admin_header');
        $this->load->view('Admin/Pages/fabhome_rate_edit',$data);
        $this->load->view('Admin/Base/admin_footer');

    }
    public function update_rate()
    {
        $request = $this->input->post();
        $service_type = $request['service_type']; 
        $service = $request['service']; 
        $category = $request['category']; 
        $uom = $request['input_uom']; 
        $rate_per_uom = $request['rate_per_uom']; 
        $discount_perc = $request['discount_perc']; 
        $discount = $request['discount']; 
        $tax = $request['tax']; 
        $expiry = $request['expiry_date'];
        $id = $request['Id'];
        $status = $this->Console_Model->deactivate_fabhome_rate($id);
        if($status){
            $status = $this->Console_Model->save_rate_details($service_type,$service,$category,$uom,$rate_per_uom,$discount_perc,$discount,$tax,$expiry);
            if($status){
                $data = array(
                    "status" => "success",
                    "message" => "Updated successfully"
                );
            }else{
                $data = array(
                    "status" => "failed",
                    "message" => "Failed to update"
                );
            }
        }else{
            $data = array(
                "status" => "failed",
                "message" => "Failed to deactivate"
            );
        }
        $this->generic->json_output($data);
    }
    public function admin_fabhome_view_cart()
    {
        $cart_data = $this->Console_Model->get_fabhome_cart_data();
        $data = array('cart' => $cart_data);
        $this->load->view('Admin/Base/admin_header');
        $this->load->view('Admin/Pages/admin_fabhome_cart',$data);
       $this->load->view('Admin/Base/admin_footer');

    }
    public function admin_fabhome_view_cartitems($id)
    {
        $cart_data = $this->Console_Model->get_fabhome_cart_items($id);
        $customer_data = $this->Console_Model->get_customer_address($id);
        $data = array('cart_items' => $cart_data,'address' => $customer_data[0]['pick_up_address'],'order_status' => $customer_data[0]['status']);
        $this->load->view('Admin/Base/admin_header');
        $this->load->view('Admin/Pages/admin_fabhome_cart_items',$data);
        $this->load->view('Admin/Base/admin_footer');
    }
    public function update_order_status()
    {
        $request = $this->input->post();
        $status = $request['status']; 
        $id = $request['id'];
        $result = $this->Console_Model->update_order_status($id,$status,ADMIN_USERNAME);
        if($result){
            $data = array(
                "status" => "success",
                "message" => "Updated successfully"
            );
        }else{
            $data = array(
                "status" => "failed",
                "message" => "Failed to update"
            );
        }
        $this->generic->json_output($data);
    }
     public function deactivate_fabhome_rate($id = FALSE)
    {
        $status = $this->Console_Model->deactivate_fabhome_rate($id);
        $this->show_fabhome_rates();
    }
    public function activate_fabhome_rate($id = FALSE)
    {
        $status = $this->Console_Model->activate_fabhome_rate($id);
        $this->show_fabhome_rates();
    }
    public function show_fabhome_rates()
    {
        $service_type=array("Deep Cleaning","Office Cleaning","Home Cleaning");
        $rate_details = array();
        for($i = 0;$i<sizeof($service_type);$i++){
            $rate_details[$i] = $this->Console_Model->get_fabhome_rate_data($service_type[$i]);
        }
        $data = array('deepcleaning_rates' => $rate_details[0],'officecleaning_rates' => $rate_details[1],'homecleaning_rates' => $rate_details[2]);
        $this->load->view('Admin/Base/admin_header');
        $this->load->view('Admin/Pages/admin_fabhome_rates_details', $data);
        $this->load->view('Admin/Base/admin_footer');
    }
    public function admin_fabhome()
    {
        if (ADMIN) {

            /*Admin can access this page, but others needed to be checked.*/
            if (ADMIN_PREVILIGE != 'root') {

                /*Checking the validity of the access based on user accessibility.*/
                $page = 'MOBILEIMAGES';
                $validiy = $this->check_accessibility($page);

                if ($validiy == FALSE) {
                    echo 'invalid access';
                    exit(0);
                }
            }


            $tip = $this->Console_Model->get_fabhome_banner();
            if($tip != array())
                $data = array('fabhome_banner' => $tip);
            else
                $data = array('fabhome_banner' => "");
            $this->load->view('Admin/Base/admin_header');
            $this->load->view('Admin/Pages/admin_fabhome_banner', $data);
            $this->load->view('Admin/Base/admin_footer');
        } else {

            $this->home();
        }
    }
    public function add_fbhmbanner()
    {
        $request = $this->input->post();
        $fbhm_image = $request['fbhm_image'];
        $res = $this->Console_Model->add_fbhmbanner($fbhm_image);
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully saved');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to save');
        }
        echo json_encode($data);
    }
    public function delete_fbhmbanner()
    {
        $request = $this->input->post();
        $id = $request['id'];
        $category = "fabhome";
        $data =  $this->Console_Model->get_banner($id,$category);
        $url = substr($data[0]['url'],35);
        unlink($url);
        $res = $this->Console_Model->delete_fbhmbanner($id);
        if ($res) {
            $data = array('status' => 'success', 'message' => 'successfully deleted');
        } else {
            $data = array('status' => 'failed', 'message' => 'failed to delete');
        }
        echo json_encode($data);
    }
    public function check_fbhmbanner()
    {
        $banner = $this->Console_Model->check_fbhmbanner();
        if($banner == "")
            $data = 'success';
        else
            $data = 'failed';
        
        echo json_encode($data);
    }
     public function download_fabricspa_users_list()
    {
        $request = $this->input->post();
        $device = $request['device'];
        $user_data = $this->Console_Model->get_users_mobileno($device);
        if($user_data){
            $n='';
            $j = 2;
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Mobile Number');
            $mob_no = array();
            $k =0;
            for($n =0;$n < sizeof($user_data);$n++){
                if(isset($user_data[$n]) && $user_data[$n]['mobile_number'] != NULL){
                    if(!in_array($user_data[$n]['mobile_number'], $mob_no)){
                         $is_valid = $this->Console_Model->check_sign_up_source_valid($user_data[$n]['mobile_number'],$device);
                        if($is_valid == 1){
                            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $user_data[$n]['mobile_number']);
                            $j++;   
                            $mob_no[$k] = $user_data[$n]['mobile_number'];
                            $k++;
                        } 
                    } 
                }  
            }
            $objPHPExcel->getActiveSheet()->setTitle('Mobile Numbers');

            //Determining file name based on brand_code
            $folder_name = "Fabricspa_users";
            // Set properties

            $objPHPExcel->getProperties()->setCreator(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setLastModifiedBy(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setTitle($folder_name . "Mobile Numbers");
            $objPHPExcel->getProperties()->setSubject("Mobile Numbers");
            $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $date = date('d-m-Y-H-i-s');
            //Final file name would be;
                
            $file_name ='Fabricspa_'.$device.'_users'.$date.'.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename='.$file_name.'.xlsx');
            header('Cache-Control: max-age=0');
            $target_file = 'excel_reports/' . $folder_name . '/' . $file_name;
            $save_status = $objWriter->save($target_file);
            $data = file_get_contents($target_file);
            $data = array(
                'status' => 'success',
                'file' => $file_name,
            
            );
    }else {
        $data = array(
            'status' => 'failed',
            'size' => 0,
        );
    }
        echo json_encode($data);
    }
      public function upload_campaign_image()
    {
        $target_dir = "layout/img/offers/";
        $_FILES["files"]["name"][0] = time().$_FILES["files"]["name"][0];
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
    public function cancel_fabhome_unpaid_orders()
    {
        $sql = "SELECT * ";
        $sql .= " FROM fabhome_cart_dtls itm "; 
        $sql .= " where  itm.status  NOT IN ('Completed','Cancelled') ";    
        $sql .= " order by itm.cart_id  DESC";
        $query = $this->db->query($sql);
        $data = $query->result_array();
            $j = 0;
        for($i = 0; $i<sizeof($data);$i++){
            $time = date('Y-m-d H:i:s',strtotime("+60 minutes", strtotime($data[$i]['order_date'])));
            if($data[$i]['cart_status'] != 'Paid' && $time <= date('Y-m-d H:i:s')){
                $update_data = array(
                    'status' => 'Cancelled',
                    'updtd_date' => date('Y-m-d H:i:s'),
                    'updated_by' => 'auto'
                );
                $this->db->where('cart_id',$data[$i]['cart_id'])->update('fabhome_cart_dtls',$update_data);
            }
        }
    }
    /**
     * //Writing the pg response log in a file.
     * @param $result
     */
    private function write_pg_response($response)
    {
        //Writing the stored procedure log in a file.
        $log_day = date('d-M-Y');
        $log_date = date('d-M-Y H:i:s');
        $json_response = json_encode($response);
        $request_time = $_SERVER['REQUEST_TIME'];
        $txt = 'date: ' . $log_date . ', response: ' . $json_response . ', request time: ' . $request_time . PHP_EOL;
        $underline = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $underline = $underline . '-';
        }
        $txt = $txt . $underline;
        $log_file = file_put_contents('pg_response/' . $log_day . '_response.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
     /**
     * Function for updating payment status of orders with no response from paytm
     */
    public function update_fabhome_payment_status()
    {
        $request = $this->input->post();
        $id = $request['cart_id'];
        $order_details = $this->Console_Model->check_payment_status($id);
        $this->write_pg_response("Orders with no response from paytm  ...........<br>".sizeof($order_details));
        if(sizeof($order_details) >0){
            $payment_id = $order_details[0]['payment_id'];
            $this->write_pg_response("Payment id with no response from paytm  ...........<br>".$payment_id);
            $data = $this->get_fbh_paytm_payment_status_single($payment_id);
        }
        if($data = TRUE){
            $this->Console_Model->update_fabhome_payment_status($id,ADMIN_USERNAME);
            $data = array('status' => 'success', 'message' => 'successfully updated');
        }else{
            $data = array('status' => 'failed', 'message' => 'failed to update');
        }
        $this->write_pg_response("Final update status in update_fabhome_payment_status...........<br>".json_encode($data));
        echo json_encode($data);
    }
    public function get_fbh_paytm_payment_status_single($payment_id='')
    {    
        if($payment_id=='')
        $payment_id=$_GET['payment_id'];
        $this->write_pg_response("Payment id with no response from paytm  in get_fbh_paytm_payment_status_single ...........<br>".$payment_id);
        $data=$this->Console_Model->get_fbh_paytm_payment_status_single($payment_id);
        $this->write_pg_response("Order  with no response from paytm  in get_fbh_paytm_payment_status_single ...........<br>".json_encode($data));
        $i='';
        $size = sizeof($data);
        if($size>1)
        $size=1;
        for($i=0;$i<$size;$i++) {
            $payment_id=$data[$i]['payment_id'];
            $transaction_id =$data[$i]['transaction_id'];
            $egrn_no=$data[$i]['cart_id'];
            $paytmParams = array();         
            /* body parameters */
            $paytmParams["body"] = array(       
                /* Find your MID in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys */
                "mid" => PAYTM_MERCHANT_IDENTIFIER,     
                /* Enter your order id which needs to be check status for */
                "orderId" =>$payment_id,
            );
            /**
            * Generate checksum by parameters we have in body
            * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
            */
            $bdy=json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES); 
            $this->write_pg_response("Paytm params  in get_fbh_paytm_payment_status_single ...........<br>".$bdy);     
            $checksum = $this->Console_Model->get_fbh_checksum_details($bdy,PAYTM_SECRET_KEY);          
            /* head parameters */
            $paytmParams["head"] = array(           
                /* put generated checksum value here */
                "signature" => $checksum
            );
            
            /* prepare JSON string for request */
            $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
            
            /* for Staging */
            // $url = "https://securegw-stage.paytm.in/v3/order/status";
    
            /* for Staging */
            //$url = "https://securegw.paytm.in/v3/order/status";
            
            /* for Production */
            $url = "https://securegw.paytm.in/v3/order/status"; 
    
            ///https://securegw-stage.paytm.in/theia/processTransaction
            //print_r($post_data);
            //print_r($url);

            $this->write_pg_response("Paytm post data  in get_fbh_paytm_payment_status_single ...........<br>".$post_data);  
            $this->write_pg_response("Paytm URL  in get_fbh_paytm_payment_status_single ...........<br>".$url);

            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            //curl_setopt($ch, CURLOPT_PROXY, $proxy); // $proxy is ip of proxy server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $httpCode = curl_getinfo($ch , CURLINFO_HTTP_CODE); // this results 0 every time
            $response = curl_exec($ch);
            $this->write_pg_response("response from PAYTM in get_fbh_paytm_payment_status_single ...........<br>".$response);
            if($response!=''){
                $response = json_decode($response);
                $result = json_decode(json_encode($response), true);
                $this->write_pg_response("response from PAYTM in get_fbh_paytm_payment_status_single ...........<br>".$result);
                $resultStatus='';
                $resultCode='';
                $resultMsg='';
                $paymentMode='';
                $paid_amount='';
                $orderId=$payment_id;
    
                if(sizeof($result)>0){                  
                    $status =$result['body']['resultInfo'];
                    if(sizeof($result)>0){
                        $resultCode=$status['resultCode'];
                    }
                }      
                if($resultCode>0){      
                    $status=$resultMsg;
                    $query=$this->Console_Model->update_fbh_payment_summary($payment_id,$status);
                    $this->write_pg_response("update PAYTM  status in fabhome_payment_summary ...........<br>".$query);
            
                }
            }
        } 
        return true;  
    }
        /**
     * Finding count of customer deleted Fabricspa app
     */
    public function get_fabricspa_deleted_users()
    {
        $library_params = array('brand_code' => "PCT0000001");
        $this->load->library('push_notification/firebase', $library_params);
        $firebase = new Firebase($library_params);
        $firebase = $this->firebase;        
        $count = 0;
        $device = array("android","ios");
        $mobile_numbers = array();
        $n = 0;
        $location = array();
        $sign_up_device = array();
        $delete_id = $this->Console_Model->get_delete_id();
        $delete_id = $delete_id+1;
        for($i=0;$i<sizeof($device);$i++){
            $mob_no = array();
            $regIds = array();
            $k = 0;
            $g= 0;
            $user_data[$i] = $this->Console_Model->get_users_mobileno($device[$i]);
            // $this->write_pg_response("Users from auth details: ".json_encode($user_data[$i]));
            for($j=0;$j<sizeof($user_data[$i]);$j++){
                if(isset($user_data[$i][$j]) && $user_data[$i][$j]['mobile_number'] != NULL){
                    if(!in_array($user_data[$i][$j]['mobile_number'], $mob_no)){
                            $is_valid = $this->Console_Model->check_sign_up_source_valid($user_data[$i][$j]['mobile_number'],$device[$i]);
                            if($is_valid == 1){
                                $mob_no[$k] = $user_data[$i][$j]['mobile_number'];
                                $gcmid = $this->Console_Model->get_gcmid($mob_no[$k],$device[$i]);
                                array_push($regIds, $gcmid);
                                $response = $firebase->sendsilentnotification($regIds);
                                if(strpos($response,'error')){
                                    $count++;
                                    $mobile_numbers[$n] = $mob_no[$k];
                                    $location[$n] = $this->Console_Model->get_location_from_mobileno($mobile_numbers[$n]);
                                    $sign_up_device[$n] = $device[$i];
                                    $this->Console_Model->save_deleted_users_details($mobile_numbers[$n],$device[$i],$location[$n],$delete_id);
                                    $n++;
                                }
                                $response = "";
                                $regIds = array();
                                $is_valid = "";
                                $gcmid = "";
                                $k++;
                                
                                
                            }

                    }
                }

            }
            // $this->write_pg_response("Users from after removing duplicates : ".json_encode($mob_no));
        }
    

    }

     /**
     * Trigger Email 2days before to jfslmdm, if the discount is getting expired from console
     */
    public function get_expired_discounts()
    {
        $dayAfterTomorrow = (new \DateTime())->add(new \DateInterval('P2D'))->format('Y-m-d ');
        $discounts = array();
        $discounts = $this->Console_Model->get_expiring_coupons($dayAfterTomorrow); // fetching coupons that will expire after 2 days
        if(sizeof($discounts)> 0){
            $n='';
            $j = 2;
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'SI.No');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Promo Code');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Discount Code');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'App Remarks');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Valid From ');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Valid Till');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Total Users');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Used Status');
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b4a7d6');
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true)->setName('Arial')->setSize(10)->getColor()->setRGB('FFFFFF');
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
            for($n =0;$n < sizeof($discounts);$n++){
                if(isset($discounts[$n])){    
                $expiry_date = date("d-m-Y", strtotime($discounts[$n]['ExpiryDate']));
                if($discounts[$n]['start_date'] != NULL)
                    $start_date = date("d-m-Y", strtotime($discounts[$n]['start_date']));
                else
                    $start_date = "";
                if($discounts[$n]['total_users'] == 0)
                    $discounts[$n]['total_users'] = "";
                if($discounts[$n]['used_count'] !=''){
                    if($discounts[$n]['used_count'] > 0 && $discounts[$n]['used_count'] < $discounts[$n]['count'])
                        $discounts[$n]['usage_status']  = 'Partially used';
                    else if($discounts[$n]['used_count'] == $discounts[$n]['count'])
                        $discounts[$n]['usage_status']  = 'Completely used';
                    else
                        $discounts[$n]['usage_status']  = 'Not used';
                }else{
                    $discounts[$n]['usage_status'] = '';
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $j-1);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $discounts[$n]['PromoCode']);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $discounts[$n]['DiscountCode']);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $discounts[$n]['AppRemarks']);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $start_date);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $expiry_date);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $discounts[$n]['total_users']);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $j, $discounts[$n]['usage_status']);
                $j++;   
                }    
            }
            $objPHPExcel->getActiveSheet()->setTitle('Fabricspa Expiring Discounts');
            $folder_name = "Fabricspa Expiring Discounts";
            // Set properties

            $objPHPExcel->getProperties()->setCreator(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setLastModifiedBy(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setTitle('Fabricspa Expring Discounts');
            $objPHPExcel->getProperties()->setSubject('Fabricspa Discounts');
            $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            //Final file name would be;
                
            $file_name = 'Fabricspa Expiring Discounts-'.$dayAfterTomorrow.'.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename='.$file_name.'.xlsx');
            header('Cache-Control: max-age=0');
            $target_file = 'excel_reports/' . $folder_name . '/' . $file_name;
            $save_status = $objWriter->save($target_file);
            $data = file_get_contents($target_file);
            $data = array(
                'status' => 'success',
                'file' => $file_name,
            );
            /*Loading email library*/
            $this->load->library('email', array('mailtype' => 'html'));
            /*Email API configurations*/
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.mandrillapp.com';
            $config['smtp_user'] = 'web@snoways.in';
            $config['smtp_pass'] = 'PBxlel-v1KG5S5zED_ddWA';
            $config['smtp_port'] = 587;
            $title = "Fabricspa Discount Expiry List";
            $message = "<p>Dear Team,</p>
                <p>Please find attached list of discounts expiring on ".$dayAfterTomorrow."</p>
                <p></p>
                <p>Thanks & Regards</p>
                <p>JFSL IT</p>";
            $subject = "Fabricspa Discount Expiry List";
            $from = 'coupons.expire@jyothy.com';
            $send_to ='jfsl.mdm@jyothy.com';
            $this->email->initialize($config);
            $this->email->from($from);
            $this->email->subject($subject);
            $this->email->to($send_to);
           // $this->email->cc($cc_to);
            $this->email->attach($target_file);
            $this->email->message($message);
            $mail_send_status = $this->email->send();


        }else{
            $data = array(
                'status' => 'Failed',
                'file' => "",
            );
        } 
        $this->write_pg_response("discount mail".json_encode($data));
        echo json_encode($data);
    }
}