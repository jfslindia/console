<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * (c) Jyothy Laboratories Ltd
 * Created by Krishna Prasad K
 * Date: 4/5/2017
 * Time: 1:43 PM
 */
class Admin_Model extends CI_Model
{
    /**
     *Constructor for the admin model
     */
    function __construct()
    {
        parent::__construct();
        // loading database
        $this->load->database();
    }


    /**
     * Method to get the user registration details
     * @param $data
     * @return mixed
     */
    public function get_registration_details($data)
    {

        $convert_date1 = strtotime($data['start_date']);
        $convert_date2 = strtotime($data['end_date']);

        $newformat1 = date('Y-m-d H:i:s', $convert_date1);
        $newformat2 = date('Y-m-d H:i:s', $convert_date2);


        $this->db->select('date,id,name,email,mobile_number,sign_up_source,BrandCode,campaign_info');
        $this->db->from('Users');

        /*Checking the location option. If both is selected, else block will execute*/

        if (strtoupper($data['location']) == 'BANGALORE' || strtoupper($data['location']) == 'MUMBAI') {

            $this->db->where('location', $data['location']);
        } else {
        }
        /*Checking the brand option. If both is selected, else block will execute*/
        if ($data['brand_code'] == 'PCT0000001' || $data['brand_code'] == 'PCT0000014') {

            $this->db->where('BrandCode', $data['brand_code']);

        } else {
        }


        if ($data['first_time_coupon'] == "true") {

            $this->db->where(array('Promocode !=' => NULL,
                'Promocode !=' => ''));

        }else{
            $this->db->where(array('Promocode' => NULL));
        }

        $this->db->where('date >=', $newformat1);
        $this->db->where('date <=', $newformat2);

        $res = $this->db->get()->result_array();

        return $res;
    }

    /**
     * Method to get the user details
     * @param $data
     * @return mixed
     */
    public function get_users_details($data)

    {
        $this->db->select('*');
        $this->db->from('Users');
        $this->db->like($data['search_option'], $data['search_keyword']);

        if ($data['brand_option'] == "Fabricspa") {
            $this->db->where('BrandCode', 'PCT0000001');
        } else if ($data['brand_option'] == "Click2Wash") {
            $this->db->where('BrandCode', 'PCT0000014');
        } else {
        }

        $res = $this->db->get()->result_array();
        return $res;
    }

    /**
     * Method to get the orders details
     * @param $data
     * @return mixed
     */
    public function get_orders_details($data)

    {

        $convert_date1 = strtotime($data['start_date']);
        $convert_date2 = strtotime($data['end_date']);

        $newformat1 = date('Y-m-d H:i:s', $convert_date1);
        $newformat2 = date('Y-m-d H:i:s', $convert_date2);

        $this->db->select('Orders.date, Orders.id, Orders.user_id, Users.name, Users.mobile_number, Users.email,  Orders.service_type, Orders.status, Orders.wash_type, Orders.pick_up_date, Orders.pick_up_address, Orders.pincode, Orders.location, Orders.pick_up_source, Orders.coupon, Orders.BrandCode, Orders.Area, Orders.RouteCode, Orders.remarks, Orders.campaign_info');

        $this->db->from('Orders');

        $this->db->join('Users', 'Users.id=Orders.user_id');

        /*Checking the brand option. If both is selected, else block will execute.*/
        if ($data['brand_code'] == 'PCT0000001' || $data['brand_code'] == 'PCT0000014') {

            $this->db->where('Orders.BrandCode', $data['brand_code']);
        } else {
        }

        /*Checking the location option. If both is selected, else block will execute.*/

        if ($data['location'] == 'Bangalore' || $data['location'] == 'Mumbai') {

            $this->db->where('Orders.location', $data['location']);
        } else {
        }

        $this->db->where('Orders.date >=', $newformat1);
        $this->db->where('Orders.date <=', $newformat2);

        /*Checking the coupon option. */
        if ($data['coupon_details'] == "with_coupon") {
            $this->db->where('Orders.coupon !=', NULL);
            //$this->db->where('Orders.coupon', "");
        } else if ($data['coupon_details'] == "without_coupon") {

            $this->db->where('Orders.coupon is null');
        } else {
        }

        if ($data['order_details'] == "active_orders") {
            $this->db->where('Orders.status  !=', 'Cancelled');
        } else if ($data['order_details'] == "cancelled_orders") {
            $this->db->where('Orders.status', 'Cancelled');
            // $this->db->where('status  !=','Cancelled');
        } else {
        }


        $this->db->order_by('date');

        $this->db->group_by('Orders.date, Orders.id, Orders.user_id, Users.name, Users.mobile_number, Users.email,  Orders.service_type, Orders.status, Orders.wash_type, Orders.pick_up_date, Orders.pick_up_address, Orders.pincode, Orders.location, Orders.pick_up_source, Orders.coupon, Orders.BrandCode, Orders.Area, Orders.RouteCode, Orders.remarks, Orders.campaign_info');

        $res = $this->db->get()->result_array();
        return $res;
    }


    /**
     * Getting the details of the admin
     * @param $data
     * @return mixed
     */
    public function check_admin($data)
    {

        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('username', $data['username']);

        $res = $this->db->get()->result_array();
        return $res;

    }

    /**
     * Inserting the admin details into admin table
     * @param $data
     * @return bool
     */
    public function add_admin($data)
    {

        $query = $this->db->insert('admin', $data);
        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Method for admin login
     * @param $data
     * @return mixed
     */
    public function admin_login($data)
    {

        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('username', $data['username']);

        $res = $this->db->get()->row_array();
        return $res;

    }

    /**
     * Method to select current password of the admin
     * @param $data
     * @return mixed
     */
    public function change_password_check_password($data)
    {

        $this->db->select('password');
        $this->db->from('admin');
        $this->db->where('username', $data['username']);
        $res = $this->db->get()->result_array();
        return $res;
    }

    /**
     * Method to change the password of the admin
     * @param $data
     * @return bool
     */
    public function change_password($data)
    {


        $this->db->set('password', $data['password']);
        $this->db->where('username', $data['username']);
        $res = $this->db->update('admin');


        if ($res) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    /**
     * Method to generate registration details on the dashboard
     * @return mixed -- Date and number of registrations on that day
     */
    public function dashboard_registers()
    {
        
        $this->db->select('count(*) as reg');
        $this->db->select('CONVERT(VARCHAR(10), date, 105) as date');
        $this->db->from('Users');
       

        $this->db->where('date>=(SELECT DATEADD(day, -7, GETDATE()))');
        $this->db->group_by('CONVERT(VARCHAR(10),date, 105)');

        $res = $this->db->get()->result_array();

        return $res;
    }

    /**
     * Method to generate order details on the dashboard
     * @return mixed -- Date and number of orders on that day
     */
    public function dashboard_orders()
    {
        
        $this->db->select('count(*) as orders');
        $this->db->select('CONVERT(VARCHAR(10), pick_up_date, 105) as date');
        $this->db->from('Orders');

        $this->db->where('pick_up_date>=CONVERT(DATE, GETDATE()-3)');
        $this->db->where('pick_up_date <=  CONVERT(DATE, GETDATE()+5)');
        $this->db->where(array('BrandCode'=>'PCT0000001','Status!='=>'cancelled'));
        $this->db->group_by('CONVERT(VARCHAR(10), pick_up_date, 105)');
        /*$this->db->group_by('pick_up_date','asc');*/
        $res = $this->db->get()->result_array();

        //$res=NULL;

        return $res;


    }


    /**
     * Getting the total registered users of both Fabricspa and Click2Wash
     * @return array
     */
    public function dashboard_total_users()
    {

        $fab_count = $this->db->select('count(*) as count')->from('Users')->where('BrandCode', 'PCT0000001')->count_all_results();

        $c2w_count = $this->db->select('count(*) as count')->from('Users')->where('BrandCode', 'PCT0000014')->count_all_results();

        $male_count = $this->db->select('count(*) as males')->from('users')->where('gender','Male')->count_all_results();

        $female_count = $this->db->select('count(*) as females')->from('users')->where('gender','Female')->count_all_results();

        $age_group=$this->db->query("SELECT TOP 10 FLOOR(DATEDIFF(DAY, users.birth_day, getdate()) / 365.25) as age,count(*) as count from users where birth_day is not null and birth_day not in ('1900-01-01 00:00:00','1970-01-01 00:00:00') and FLOOR(DATEDIFF(DAY, users.birth_day, getdate()) / 365.25)>10 group by (FLOOR(DATEDIFF(DAY, users.birth_day, getdate()) / 365.25)) order by count desc")->result_array();

        $areawise_users=$this->db->query("SELECT TOP 10 UPPER(Area) as area,UPPER(location) as location,count(*) as count from users where Area is not null group by Area,location order by count desc")->result_array();

        $res = array('Fabricspa' => $fab_count, 'Click2Wash' => $c2w_count,'Males'=>$male_count,'Females'=>$female_count,'age_group'=>$age_group,'areawise_users'=>$areawise_users);

        return $res;
    }


    /**
     * Method for getting the source of registration of the Fabricspa users
     * @return array
     */
    public function dashboard_fab_source()
    {

        $fab_web_count = $this->db->select('count(*)')->from('Users')->where('BrandCode', 'PCT0000001')->where('sign_up_source', 'website')->count_all_results();

        $fab_android_count = $this->db->select('count(*)')->from('Users')->where('BrandCode', 'PCT0000001')->where('sign_up_source', 'android')->count_all_results();

        $fab_ios_count = $this->db->select('count(*)')->from('Users')->where('BrandCode', 'PCT0000001')->where('sign_up_source', 'ios')->count_all_results();



        $res = array('web_count' => $fab_web_count, 'android_count' => $fab_android_count, 'ios_count' => $fab_ios_count);

        return $res;
    }


    /**
     * Method for getting the source of registration of the Click2Wash users
     * @return array
     */
    public function dashboard_cw_source()
    {

        $fab_web_count = $this->db->select('count(*)')->from('Users')->where('BrandCode', 'PCT0000014')->where('sign_up_source', 'website')->count_all_results();

        $fab_android_count = $this->db->select('count(*)')->from('Users')->where('BrandCode', 'PCT0000014')->where('sign_up_source', 'android')->count_all_results();

        $fab_ios_count = $this->db->select('count(*)')->from('Users')->where('BrandCode', 'PCT0000014')->where('sign_up_source', 'ios')->count_all_results();



        $res = array('web_count' => $fab_web_count, 'android_count' => $fab_android_count, 'ios_count' => $fab_ios_count);

        return $res;
    }

    /**
     * Getting the GCMIDs via mobile number or email address
     * @param $via -- Column name(email or mobile_number)
     * @param $list -- List of mobile numbers of email addresses
     * @return array|bool
     */
    public function get_gcmids($via, $list, $brand, $device)
    {

        $list_length = sizeof($list);
        $gcmids = [];


        for ($i = 0; $i < $list_length; $i++) {

            if ($brand == 'Fabricspa') {
                if ($device == 'android') {
                    $this->db->select('fabricspa_android_gcmid as gcmid');
                } else if ($device == 'ios') {
                    $this->db->select('fabricspa_ios_gcmid as gcmid');
                } else {

                }
            } else if ($brand == 'Click2Wash') {
                if ($device == 'android') {
                    $this->db->select('click2wash_android_gcmid as gcmid');
                } else if ($device == 'ios') {
                    $this->db->select('click2wash_ios_gcmid as gcmid');
                } else {
                }

            } else {
            }

            $this->db->from('Users');

            $this->db->where($via, $list[$i]);

            $row = $this->db->get()->row_array();

            if ($row['gcmid'])
                $status = array_push($gcmids, $row);
        }

        if ($gcmids) {
            return $gcmids;
        } else {
            return FALSE;
        }

    }

    /**
     * Getting the GCMIDs via mobile number or email address
     * @param $via -- Column name(email or mobile_number)
     * @param $list -- List of mobile numbers of email addresses
     * @return array|bool
     */
    public function get_all_gcmids($brand, $device, $location, $start, $limit)
    {

        if ($brand == 'Fabricspa') {
            if ($device == 'android') {

                $this->db->select('fabricspa_android_gcmid as gcmid');
                $this->db->where('fabricspa_android_gcmid !=', NULL);
            } else if ($device == 'ios') {

                $this->db->select('fabricspa_ios_gcmid as gcmid');
                $this->db->where('fabricspa_ios_gcmid !=', NULL);
            } else {

            }
        } else if ($brand == 'Click2Wash') {
            if ($device == 'android') {

                $this->db->select('click2wash_android_gcmid as gcmid');
                $this->db->where('click2wash_android_gcmid !=', NULL);
            } else if ($device == 'ios') {

                $this->db->select('click2wash_ios_gcmid as gcmid');
                $this->db->where('click2wash_android_gcmid !=', NULL);
            } else {

            }

        } else {

        }
        $this->db->from('Users');

        if ($location!='') {

            $this->db->where_in('location', [$location, strtoupper($location)]);
        }

        if (isset($start) && isset($limit)) {

            $this->db->limit($limit, $start);
        }
        $this->db->order_by('id', 'asc');
        $gcmids = $this->db->get()->result_array();


        if ($gcmids) {
            return $gcmids;
        } else {
            return FALSE;
        }

    }




    public function get_stores()
    {
        $stores = $this->db->select('*')->from('stores')->order_by('id', 'asc')->get()->result_array();
        return $stores;
    }

    public function get_stores_in_groups()
    {

        $fabspa_bangalore_stores = $this->db->select('*')->from('stores')->where(array('brand' => 'Fabricspa', 'location' => 'Bangalore'))->get()->result_array();
        $fabspa_mumbai_stores = $this->db->select('*')->from('stores')->where(array('brand' => 'Fabricspa', 'location' => 'Mumbai'))->get()->result_array();
        $fabspa_delhi_stores = $this->db->select('*')->from('stores')->where(array('brand' => 'Fabricspa', 'location' => 'Delhi'))->get()->result_array();
        $fabspa_chennai_stores = $this->db->select('*')->from('stores')->where(array('brand' => 'Fabricspa', 'location' => 'Chennai'))->get()->result_array();
        $fabspa_pune_stores = $this->db->select('*')->from('stores')->where(array('brand' => 'Fabricspa', 'location' => 'Pune'))->get()->result_array();

        $c2w_bangalore_stores = $this->db->select('*')->from('stores')->where(array('brand' => 'Click2Wash', 'location' => 'Bangalore'))->get()->result_array();
        $c2w_mumbai_stores = $this->db->select('*')->from('stores')->where(array('brand' => 'Click2Wash', 'location' => 'Mumbai'))->get()->result_array();

        $fabricspa = array(
            'bangalore' => $fabspa_bangalore_stores,
            'mumbai' => $fabspa_mumbai_stores,
            'delhi' => $fabspa_delhi_stores,
            'chennai' => $fabspa_chennai_stores,
            'pune' => $fabspa_pune_stores
        );

        $click2wash = array(
            'bangalore' => $c2w_bangalore_stores,
            'mumbai' => $c2w_mumbai_stores
        );

        $result = array('fabricspa' => $fabricspa, 'click2wash' => $click2wash);

        return $result;

    }

    /*Saving stores*/
    public function save_store($store)
    {

        $status = $this->db->insert('stores', $store);
        $id = $this->db->insert_id();
        $saved_store = $this->db->select('*')->from('stores')->where('id', $id)->get()->result_array();
        return $saved_store;
    }

    public function update_store($store, $store_id)
    {

        $this->db->where('id', $store_id);
        $updated_status = $this->db->update('stores', $store);
        $updated_store = $this->db->select('*')->from('stores')->where('id', $store_id)->get()->result_array();
        return $updated_store;
    }

    public function delete_stores($stores)
    {

        $delete_status = $this->db->where_in('id', $stores)->delete('stores');
        return $delete_status;

    }

    /*Search Panel methods*/
    /**
     * Method for search with Mobile Number of the user
     * @param $search_text -- User input to search
     * @return array
     */
    public function search_with_mobile($search_text)
    {

        $user_details = [];
        $order_details = [];

        $user_details = $this->db->select('id,date,name,email,customer_id,mobile_number')->from('Users')->where('mobile_number', $search_text)->get()->result_array();

        if ($user_details) {

            $this->db->select('id,date,pick_up_date,CutomerOrderStatus as order_status,status as mobile_db_status, customer_id,bookingID,reference_number');
            $this->db->from('V_Order');
            //$this->db->where_not_in('status', array('Cancelled'));
            $this->db->where('user_id', $user_details[0]['id']);
            $this->db->order_by('date', 'desc');
            $order_details = $this->db->get()->result_array();
        }

        if ($order_details) {

            for ($i = 0; $i < sizeof($order_details); $i++) {

                if ($order_details[$i]['mobile_db_status'] == 'Cancelled') {
                    $order_details[$i]['order_status'] = 'Cancelled';
                }

            }


        }


        $result = array(

            'user_details' => $user_details,
            'order_details' => $order_details

        );
        return $result;


    }


    /**
     * Method for search with Email Address of the user
     * @param $search_text -- User input to search
     * @return array
     */
    public function search_with_email($search_text)
    {

        $user_details = [];
        $order_details = [];

        $user_details = $this->db->select('id,date,name,email,customer_id,mobile_number')->from('Users')->where('email', $search_text)->get()->result_array();

        if ($user_details) {

            $this->db->select('id,date,pick_up_date,CutomerOrderStatus as order_status,status as mobile_db_status, customer_id,bookingID,reference_number');
            $this->db->from('V_Order');
            //$this->db->where_not_in('status', array('Cancelled'));
            $this->db->where('user_id', $user_details[0]['id']);
            $this->db->order_by('date', 'desc');
            $order_details = $this->db->get()->result_array();
        }

        if ($order_details) {

            for ($i = 0; $i < sizeof($order_details); $i++) {

                if ($order_details[$i]['mobile_db_status'] == 'Cancelled') {
                    $order_details[$i]['order_status'] = 'Cancelled';
                }

            }


        }


        $result = array(

            'user_details' => $user_details,
            'order_details' => $order_details

        );
        return $result;


    }

    /**
     * Method for search with Customer ID Address of the user
     * @param $search_text -- User input to search
     * @return array
     */
    public function search_with_customer_id($search_text)
    {

        $user_details = [];
        $order_details = [];

        $user_details = $this->db->select('id,date,name,email,customer_id,mobile_number')->from('Users')->where('customer_id', $search_text)->get()->result_array();

        if ($user_details) {

            $this->db->select('id,date,pick_up_date,CutomerOrderStatus as order_status,status as mobile_db_status, customer_id,bookingID,reference_number');
            $this->db->from('V_Order');
            //$this->db->where_not_in('status', array('Cancelled'));
            $this->db->where('user_id', $user_details[0]['id']);
            $this->db->order_by('date', 'desc');
            $order_details = $this->db->get()->result_array();
        }

        if ($order_details) {

            for ($i = 0; $i < sizeof($order_details); $i++) {

                if ($order_details[$i]['mobile_db_status'] == 'Cancelled') {
                    $order_details[$i]['order_status'] = 'Cancelled';
                }

            }


        }


        $result = array(

            'user_details' => $user_details,
            'order_details' => $order_details

        );
        return $result;


    }

    /**
     * Method for search with Email Address of the user
     * @param $search_text -- User input to search
     * @return array
     */
    public function search_with_order_id($search_text)
    {

        $user_details = [];
        $order_details = [];

        $this->db->select('id,user_id,date,pick_up_date,CutomerOrderStatus as order_status,status as mobile_db_status, customer_id,bookingID,reference_number');
        $this->db->from('V_Order');

        $this->db->where('id', $search_text);
        $this->db->order_by('date', 'desc');
        $order_details = $this->db->get()->result_array();

        if ($order_details) {

            $this->db->select('id,date,name,email,customer_id,mobile_number');
            $this->db->from('Users');
            $this->db->where('id', $order_details[0]['user_id']);
            $user_details = $this->db->get()->result_array();
        }

        if ($order_details) {

            for ($i = 0; $i < sizeof($order_details); $i++) {

                if ($order_details[$i]['mobile_db_status'] == 'Cancelled') {
                    $order_details[$i]['order_status'] = 'Cancelled';
                }

            }


        }


        $result = array(

            'user_details' => $user_details,
            'order_details' => $order_details

        );
        return $result;


    }

    /**
     * Method for search with Email Address of the user
     * @param $search_text -- User input to search
     * @return array
     */
    public function search_with_booking_id($search_text)
    {

        $user_details = [];
        $order_details = [];

        $this->db->select('id,user_id,date,pick_up_date,CutomerOrderStatus as order_status,status as mobile_db_status, customer_id,bookingID,reference_number');
        $this->db->from('V_Order');

        $this->db->where('BookingID', $search_text);
        $this->db->order_by('date', 'desc');
        $order_details = $this->db->get()->result_array();

        if ($order_details) {

            $this->db->select('id,date,name,email,customer_id,mobile_number');
            $this->db->from('Users');
            $this->db->where('id', $order_details[0]['user_id']);
            $user_details = $this->db->get()->result_array();
        }

        if ($order_details) {

            for ($i = 0; $i < sizeof($order_details); $i++) {

                if ($order_details[$i]['mobile_db_status'] == 'Cancelled') {
                    $order_details[$i]['order_status'] = 'Cancelled';
                }

            }


        }


        $result = array(

            'user_details' => $user_details,
            'order_details' => $order_details

        );
        return $result;


    }

    /**
     * Method for search with Email Address of the user
     * @param $search_text -- User input to search
     * @return array
     */
    public function search_with_name($search_text)
    {

        $user_details = [];
        $order_details = [];
        $multiple_orders_of_multiple_users = [];

        $user_details = $this->db->select('id,date,name,email,customer_id,mobile_number')->from('Users')->like('name', $search_text)->get()->result_array();

        if ($user_details) {

            for ($i = 0; $i < sizeof($user_details); $i++) {

                $this->db->select('id,date,pick_up_date,CutomerOrderStatus as order_status,status as mobile_db_status, customer_id,bookingID,reference_number');
                $this->db->from('V_Order');
                //$this->db->where_not_in('status', array('Cancelled'));
                $this->db->where('user_id', $user_details[$i]['id']);
                $this->db->order_by('date', 'desc');
                $order_details = $this->db->get()->result_array();
                array_push($multiple_orders_of_multiple_users, $order_details);
            }

        }

        if ($multiple_orders_of_multiple_users) {

            for ($i = 0; $i < sizeof($user_details); $i++) {

                for ($j = 0; $j < sizeof($multiple_orders_of_multiple_users[$i]); $j++) {

                    if ($multiple_orders_of_multiple_users[$i][$j]['mobile_db_status'] == 'Cancelled') {
                        $multiple_orders_of_multiple_users[$i][$j]['order_status'] = 'Cancelled';
                    }

                }
            }


        }

        $result = array(

            'user_details' => $user_details,
            'order_details' => $multiple_orders_of_multiple_users

        );
        return $result;


    }

    /**
     * Returns all the registered DCR user in the DB.
     * @return mixed
     */
    public function get_dcr_users()
    {
        $dcr_users = $this->db->select('*')->from('DCR_Users')->order_by('Id', 'asc')->get()->result_array();
        return $dcr_users;
    }


    /*Saving dcr_users*/
    /**
     * Saving a DCR user
     * @param $dcr_user
     * @return mixed
     */
    public function save_dcr_user($dcr_user)
    {

        $status = $this->db->insert('DCR_Users', $dcr_user);
        $id = $this->db->insert_id();
        $saved_dcr_user = $this->db->select('*')->from('DCR_Users')->where('Id', $id)->get()->result_array();
        return $saved_dcr_user;
    }

    /**
     * Updating a DCR user details from the console.
     * @param $dcr_user
     * @param $dcr_user_id
     * @return mixed
     */
    public function update_dcr_user($dcr_user, $dcr_user_id)
    {

        $this->db->where('Id', $dcr_user_id);
        $updated_status = $this->db->update('DCR_Users', $dcr_user);
        $updated_dcr_user = $this->db->select('*')->from('DCR_Users')->where('Id', $dcr_user_id)->get()->result_array();
        return $updated_dcr_user;
    }

    /**
     * Deleting a DCR user from the console.
     * @param $dcr_users
     * @return mixed
     */
    public function delete_dcr_users($dcr_users)
    {

        $delete_status = $this->db->where_in('Id', $dcr_users)->delete('DCR_Users');
        return $delete_status;

    }


    /**
     * Returns all the registered qc user in the DB.
     * @return mixed
     */
    public function get_qc_users()
    {
        $qc_users = $this->db->select('*')->from('QC_Users')->order_by('Id', 'asc')->get()->result_array();
        return $qc_users;
    }


    /*Saving qc_users*/
    /**
     * Saving a qc user
     * @param $qc_user
     * @return mixed
     */
    public function save_qc_user($qc_user)
    {

        $status = $this->db->insert('QC_Users', $qc_user);
        $id = $this->db->insert_id();
        $saved_qc_user = $this->db->select('*')->from('QC_Users')->where('Id', $id)->get()->result_array();
        return $saved_qc_user;
    }

    /**
     * Updating a qc user details from the console.
     * @param $qc_user
     * @param $qc_user_id
     * @return mixed
     */
    public function update_qc_user($qc_user, $qc_user_id)
    {

        $this->db->where('Id', $qc_user_id);
        $updated_status = $this->db->update('QC_Users', $qc_user);
        $updated_qc_user = $this->db->select('*')->from('QC_Users')->where('Id', $qc_user_id)->get()->result_array();
        return $updated_qc_user;
    }

    /**
     * Deleting a qc user from the console.
     * @param $qc_users
     * @return mixed
     */
    public function delete_qc_users($qc_users)
    {

        $delete_status = $this->db->where_in('Id', $qc_users)->delete('QC_Users');
        return $delete_status;

    }

    public function get_payment_details($limit=FALSE,$offset=FALSE,$customer_code,$start_date,$end_date,$payment_id){
        if($start_date&&$end_date) {

            if($customer_code!=''){

                $details = $this->db->limit($limit,$offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo','TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->where('TransactionInfo.CustomerCode', $customer_code)->where(array('TransactionInfo.TransactionDate>=' => $start_date, 'TransactionInfo.TransactionDate<=' => $end_date))->order_by('TransactionInfo.TransactionDate','desc')->get()->result_array();
            }else if($payment_id!=''){

                //Payment ID will be provided here...
                $details = $this->db->limit($limit,$offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo','TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->where('TransactionPaymentInfo.PaymentId',$payment_id)->where(array('TransactionInfo.TransactionDate>=' => $start_date, 'TransactionInfo.TransactionDate<=' => $end_date))->order_by('TransactionInfo.TransactionDate','desc')->get()->result_array();
            }else{
                /*No customer id or payment id. Select all.*/
                $details = $this->db->limit($limit,$offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo','TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->where(array('TransactionInfo.TransactionDate>=' => $start_date, 'TransactionInfo.TransactionDate<=' => $end_date))->order_by('TransactionInfo.TransactionDate','desc')->get()->result_array();
            }

        }
        else{
            if($customer_code!='') {

                $details = $this->db->limit($limit,$offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo', 'TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->where('TransactionInfo.CustomerCode', $customer_code)->order_by('TransactionInfo.TransactionDate', 'desc')->get()->result_array();
            }else if($payment_id!=''){


                $details = $this->db->limit($limit,$offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo', 'TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->where('TransactionPaymentInfo.PaymentId',$payment_id)->order_by('TransactionInfo.TransactionDate', 'desc')->get()->result_array();
            }else{
                /*No customer id or payment id. Select all.*/
                $details = $this->db->limit($limit,$offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo', 'TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->order_by('TransactionInfo.TransactionDate', 'desc')->get()->result_array();
            }
        }
        return $details;
    }

    /**
     * For getting active cities of multiple stores.
     * @param $brand_code
     * @return mixed
     */
    public function get_stores_cities_sp($brand_code)
    {
        $query = $this->db->query("EXEC " . LOCAL_DB . ".dbo.GetCityDataForActiveStores @BrandCode ='" . $brand_code . "'")->result_array();
        return $query;
    }

    /**
     * For getting all the stores from Fabricare by passing brand code and city code.
     * @param $city_code
     * @param $brand_code
     * @return mixed
     */
    public function get_stores_sp($city_code, $brand_code)
    {
        $query = $this->db->query("EXEC " . LOCAL_DB . ".dbo.GetBranchDataForCity @CityCode ='" . $city_code . "', @BrandCode='" . $brand_code . "'")->result_array();
        return $query;
    }

    /**
     * Getting the brands from the Fabricare
     * @return mixed
     */
    public function get_brands(){
        $result=$this->db->select('BrandCode,BrandDescription')->from(SERVER_DB.'.dbo.BrandInfo')->get()->result_array();
        return $result;
    }

    public function get_all_stores(){
        $stores=$this->db->query('EXEC '.SERVER_DB.'.dbo.sp_GetAllBranchDetails')->result_array();
        return $stores;
    }

    public function get_user_password($mobile_number){
        $result=$this->db->select('Password')->from('DCR_Users')->where('Phone',$mobile_number)->get()->row_array();
        return $result;
    }

    public function get_offers()
    {

        $query = $this->db->select('*')->from('Offers')->get()->result_array();

        return $query;
    }

    /**
     * Saving an offer
     * @param $offer_id
     * @param $title
     * @param $description
     */
    public function save_offer($offer_id,$title,$description,$expiry,$brand_code){
        $array=array('offer_heading'=>$title,'offer_description'=>$description,'expiry'=>$expiry,'brand_code'=>$brand_code);
        $res=$this->db->where('id',$offer_id)->update('Offers',$array);
        return $res;
    }

    /**
     * Adding a new offer
     *
     * @param $title
     * @param $description
     */
    public function add_offer($title,$description,$expiry,$brand_code,$offer_image){
        $array=array('offer_heading'=>$title,'offer_description'=>$description,'expiry'=>$expiry,'brand_code'=>$brand_code,'offer_img'=>$offer_image);
        $res=$this->db->insert('Offers',$array);
        return $res;
    }

    public function delete_offer($offer_id){
        $status=$this->db->where_in('id', $offer_id)->delete('Offers');
        return $status;
    }

    /*Getting the all feedbacks from the db*/
    public function get_feedbacks(){
        $user_feedbacks=$this->db->limit(50)->select('f.date,f.name,f.feedback,u.customer_id')->from('feedback f')->join('users u','u.id=f.user_id')->order_by('date','desc')->get()->result_array();
        $order_feedbacks=$this->db->limit(50)->select('*')->from('CompletedOrderFeedback')->order_by('date','desc')->get()->result_array();
        $data=array('user_feedbacks'=>$user_feedbacks,'order_feedbacks'=>$order_feedbacks);
        return $data;

    }

    /**
     * Getting all the details from TransactionInfo details based on EGRN
     * @param $egrn
     */
    public function get_transaction_info_details_from_egrn($egrn){
        $details=$this->db->select('*')->from('TransactionInfo')->where('EGRNNo',$egrn)->order_by('TransactionDate','desc')->get()->result_array();
        return $details;
    }

    /**
     * Getting all the details from TransactionInfo details based on $payment_id
     * @param $payment_id
     */
    public function get_transaction_info_details_from_payment_id($payment_id){
        $details=$this->db->select('*')->from('TransactionInfo')->where('PaymentId',$payment_id)->order_by('TransactionDate','desc')->get()->result_array();
        return $details;
    }

    /**
     * Getting customer details from a given EGRN.
     * @param $egrn
     */
    public function get_customer_details_from_egrn($egrn){
        $customer_id=$this->db->select('CustomerCode')->from('TransactionInfo')->where('EGRNNo',$egrn)->get()->row_array();
        $customer_details=$this->db->select('*')->from('users')->where(array('customer_id'=>$customer_id['CustomerCode'],'customer_id!='=>NULL))->get()->row_array();
        return $customer_details;
    }


    /**
     * Getting customer details from a given $payment_id.
     * @param $payment_id
     */
    public function get_customer_details_from_payment_id($payment_id){
        $customer_id=$this->db->select('CustomerCode')->from('TransactionInfo')->where('PaymentId',$payment_id)->get()->row_array();
        $customer_details=$this->db->select('*')->from('users')->where(array('customer_id'=>$customer_id['CustomerCode'],'customer_id!='=>NULL))->get()->row_array();
        return $customer_details;
    }

    /**
     * Getting the entries in the TransactionPaymentInfo table.
     * @param $payment_ids
     * @return mixed
     */
    public function get_transaction_payment_info_details($payment_ids){
        $details=$this->db->select('*')->from('TransactionPaymentInfo')->where_in('PaymentId',$payment_ids)->order_by('CreatedOn','desc')->get()->result_array();
        return $details;
    }

    /**
     * Getting the unsettled orders of a customer.
     * @param $customer_id
     * @param $brand_code
     * @return mixed
     */
    public function get_unsettled_orders($customer_id,$brand_code){
        $unsettled_orders = $this->db->query("EXEC " . SERVER_DB . ".dbo.[OrderListForGenerateInvoice] @CustomerCode='" . $customer_id . "', @BrandCode='" . $brand_code . "'")->result_array();
        return $unsettled_orders;
    }

    /**
     * Updating the DCN value in the TransactionInfo table.
     * @param $id
     * @param $dcn
     * @return mixed
     */
    public function update_dcn($id,$dcn){
        $status=$this->db->where('Id',$id)->update('TransactionInfo',array('DCNo'=>$dcn));
        return $status;
    }

    /**
     * Inserting a new entry row in the TransactionPaymentInfo table.
     * @param $data
     * @return mixed
     */
    public function save_transaction_payment_info($data){
        $status=$this->db->insert('TransactionPaymentInfo',$data);
        return $status;
    }

    /**
     * Settle the order from the id of the TransactionPaymentInfo table.
     * @param $id
     * @return mixed
     */
    public function settle_order($id){
        $transaction_payment_info_details=$this->db->select('TransactionId,PaymentId')->from('TransactionInfo')->where('Id',$id)->get()->row_array();
        print_r($transaction_payment_info_details);
        $query = "EXEC " . SERVER_DB . ".dbo.[INVOICEANDSETTLEMENTFORMOBILEAPP] @TransactionId='" . $transaction_payment_info_details['TransactionId'] . "',@PAYMENTID='" . $transaction_payment_info_details['PaymentId'] . "'";
        
        $result=$this->db->query($query)->result_array();

        print_r($result);

        /*Updating the table with the invoice no if the order is settled.*/
        if($result){
            if($result[0]['RESULT']=='SUCCESS'){
            $this->db->where(array('TransactionId'=>$transaction_payment_info_details['TransactionId'],'PaymentId'=>$transaction_payment_info_details['PaymentId']))->update('TransactionPaymentInfo',array('InvoiceNo'=>$result[0]['INVOICENO'],'Remarks'=>$result[0]['REMARKS'],'SettlementProcedure'=>'Manually by '.ADMIN_USERNAME));
            }else{
                $result=NULL;
            }
        }
        return $result;

    }

    /**
     * Getting the incomplete Payment Transaction Details
     * @param bool $limit
     * @param bool $offset
     * @param $customer_code
     * @param $start_date
     * @param $end_date
     * @param $payment_id
     * @return mixed
     */
    public function get_incomplete_payment_details($limit=FALSE,$offset=FALSE,$start_date,$end_date){
        if($start_date&&$end_date) {

            /*$this->db->limit($limit,$offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,TransactionInfo.PaymentId")->from('TransactionInfo')->where(array('TransactionInfo.TransactionDate>=' => $start_date, 'TransactionInfo.TransactionDate<=' => $end_date));
            $details = $this->db->where_not_in('PaymentId',$this->db->select('PaymentId')->from('TransactionInfo'))->order_by('TransactionInfo.TransactionDate','desc')->get()->result_array();*/
            $details=$this->db->query("select TransactionDate,CustomerCode,EGRNNo,IsNull(DCNo,'N/A') as DCNo,PaymentId from transactioninfo where paymentid not in (select paymentid from transactionpaymentinfo) and transactiondate >='".$start_date."' and transactiondate <='".$end_date."' order by transactiondate desc  offset ".$offset." rows fetch next ".$limit." ROWS ONLY")->result_array();


        }else{

            $details=NULL;
        }


        return $details;
    }


    /**
     *Loading the appspa campaign stats. (Campaign start date: March 9,2019. End date: 31st March, 2019)
     */
    public function load_appspa_campaign_stats(){

        $last_24hrs_registrations_with_coupon=$this->db->query("select count(*) as TotalRegistrationsInLast24HrsWhoGotTheCoupon from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001' and Promocode='APPSPA'")->row_array();
        $last_24hrs_registrations_without_coupon=$this->db->query("select COUNT(*) as TotalRegistrationsInLast24HrsWithoutCoupon  from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001' and ISNULL(Promocode,'') <> 'APPSPA'")->row_array();
        $last_24hrs_registrations=$this->db->query("select count(*) as TotalRegistrationsInLast24Hrs from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001' ")->row_array();
        $last_24hrs_pickups_with_coupon=$this->db->query("select count(*) as TotalPickupsByNewlyRegisteredUsersWhoAppliedCoupon from orders where user_id in (select id from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001') and status!='cancelled' and Coupon='APPSPA'")->row_array();
        $last_24hrs_pickups_without_coupon=$this->db->query("select count(*) as TotalPickupsByNewlyRegisteredUsersWithoutCoupon  from orders where user_id in (select id from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001') and status!='cancelled' AND ISNULL(coupon,'') <> 'APPSPA'")->row_array();
        $last_24hrs_pickups=$this->db->query("select count(*) as TotalPickupsByNewlyRegisteredUsers from orders where user_id in (select id from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001') and status!='cancelled'")->row_array();


        $total_registrations_with_coupon=$this->db->query("select count(*) as TotalRegistrationsSinceCampaignWhoGotCoupon from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001' and Promocode='APPSPA'")->row_array();
        $total_registrations_without_coupon=$this->db->query("select count(*) as TotalRegistrationsSinceCampaignWithoutCoupon from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001' and ISNULL(Promocode,'') <> 'APPSPA'")->row_array();
        $total_registrations=$this->db->query("select count(*) as TotalRegistrationsSinceCampaign from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001'")->row_array();
        $total_pickups_with_appspa=$this->db->query("select count(*) as TotalPickupsSinceCampaignWhoAppliedCoupon from orders where user_id in (select id from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001') and status!='cancelled' and Coupon='APPSPA'")->row_array();
        $total_pickups_without_appspa=$this->db->query("select count(*) as TotalPickupsSinceCampaignWithoutCoupon from orders where user_id in (select id from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001') and status!='cancelled' and ISNULL(coupon,'') <> 'APPSPA'")->row_array();
        $total_pickups=$this->db->query("select count(*) as TotalPickupsSinceCampaign from orders where user_id in (select id from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001') and status!='cancelled' ")->row_array();
        $result=array(

            'last_24hrs_registrations_with_coupon'=>$last_24hrs_registrations_with_coupon,
            'last_24hrs_registrations_without_coupon'=>$last_24hrs_registrations_without_coupon,
            'last_24hrs_registrations'=>$last_24hrs_registrations,
            'last_24hrs_pickups_with_coupon'=>$last_24hrs_pickups_with_coupon,
            'last_24hrs_pickups_without_coupon'=>$last_24hrs_pickups_without_coupon,
            'last_24hrs_pickups'=>$last_24hrs_pickups,

            'total_registrations_with_coupon'=>$total_registrations_with_coupon,
            'total_registrations_without_coupon'=>$total_registrations_without_coupon,
            'total_registrations'=>$total_registrations,
            'total_pickups_with_appspa'=>$total_pickups_with_appspa,
            'total_pickups_without_appspa'=>$total_pickups_without_appspa,
            'total_pickups'=>$total_pickups

        );
        return $result;

    }

    /**
     * Calculating the total number of results based on parameters
     * @param $brand
     * @param $location
     * @param $device
     * @return mixed
     */
    public function total_cycle($brand,$location,$device){

        if ($brand == 'Fabricspa') {
            if ($device == 'android') {

                $this->db->select('fabricspa_android_gcmid as gcmid');
                $this->db->where('fabricspa_android_gcmid !=', NULL);
            } else if ($device == 'ios') {

                $this->db->select('fabricspa_ios_gcmid as gcmid');
                $this->db->where('fabricspa_ios_gcmid !=', NULL);
            } else {

            }
        } else if ($brand == 'Click2Wash') {
            if ($device == 'android') {

                $this->db->select('click2wash_android_gcmid as gcmid');
                $this->db->where('click2wash_android_gcmid !=', NULL);
            } else if ($device == 'ios') {

                $this->db->select('click2wash_ios_gcmid as gcmid');
                $this->db->where('click2wash_android_gcmid !=', NULL);
            } else {

            }

        } else {

        }
        $this->db->from('Users');

        if ($location!='') {

            $this->db->where_in('location', [$location, strtoupper($location)]);
        }


        $gcmids = $this->db->count_all_results();

        return $gcmids;
    }

    /**
     * Updating the user details
     * @param $user_id
     * @param $name
     * @param $mobile_number
     * @param $email
     */
    public function update_user_details($user_id,$name,$mobile_number,$email){

        /*Adding details to the log table.*/
        $existing_user_details=$this->db->select('name,email,mobile_number')->from('Users')->where('id',$user_id)->get()->row_array();


        /*Adding the details into log table*/

        $log_data=array('Date'=>date('Y-m-d H:i:s'),'UserId'=>$user_id,'OldName'=>$existing_user_details['name'],
            'OldEmail'=>$existing_user_details['email'],
            'MobileNumber'=>$mobile_number,
            'NewName'=>$name,
            'NewEmail'=>$email,
            'UpdatedBy'=>ADMIN_USERNAME
            );

        $log = $this->db->insert('UsersUpdateLog', $log_data);


        /*Updating the table with new data*/
        $this->db->set('name', $name);
        //$this->db->set('mobile_number', $mobile_number); No need as of now.
        $this->db->set('email', $email);
        $this->db->where('id', $user_id);
        $res = $this->db->update('Users');

        if($res){
            $updated_user=$this->db->select('name,mobile_number,id,email')->from('users')->where('id',$user_id)->get()->row_array();
            $result['status']=TRUE;
            $result['user_details']=$updated_user;
        }else{
            $result['status']=FALSE;
        }


        return $result;
    }

    /**
     * Getting the QA Logs
     * @return mixed
     */
    public function get_qa_logs(){
        $result=$this->db->select('*')->from('QA_Logs')->get()->result_array();
        return $result;
    }

    /**
     * Total QA_Logs count.
     * @return mixed
     */
    public function get_logs_count(){
        $count=$this->db->count_all_results('QA_Logs');
        return $count;
    }

    /**
     * Paginated result set for QA Logs
     * @param $per_page
     * @param $offset
     * @return mixed
     */
    public function get_paginated_logs($per_page,$offset){
        $result = $this->db->limit($per_page,$offset)->select('*')->from('QA_Logs')->order_by('CreatedDate','desc')->get()->result_array();

        return $result;
    }



    public function test(){
        $query=$this->db->query("select customer_id,count(customer_id) from V_Order where customer_id not in (select customer_id from V_Order where cutomerorderstatus='Cancelled' or Status='Cancelled')  group by customer_id having count(customer_id)>1")->result_array();
        echo sizeof($query);
    }





}