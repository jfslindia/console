<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * (c) Jyothy Laboratories Ltd
 * Created by Krishna Prasad K
 * Date: 4/5/2017
 * Time: 1:43 PM
 */
class Console_Model extends CI_Model
{
    /**
     *Constructor for the admin model
     */
    function __construct()
    {
        parent::__construct();
        // loading database
        $this->load->database();
        $this->load->library('Paytm');
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

        } else {
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
        $this->db->from('ConsoleUsers');
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

        $query = $this->db->insert('ConsoleUsers', $data);
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
        $this->db->from('ConsoleUsers');
        $this->db->where(array('username' => $data['username'], 'active' => 1));

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
        $this->db->from('ConsoleUsers');
        $this->db->where('username', $data['username']);
        $res = $this->db->get()->row_array();
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
        $res = $this->db->update('ConsoleUsers');


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
        $this->db->where(array('BrandCode' => 'PCT0000001', 'Status!=' => 'cancelled'));
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

        $male_count = $this->db->select('count(*) as males')->from('users')->where('gender', 'Male')->count_all_results();

        $female_count = $this->db->select('count(*) as females')->from('users')->where('gender', 'Female')->count_all_results();

        $age_group = $this->db->query("SELECT TOP 10 FLOOR(DATEDIFF(DAY, users.birth_day, getdate()) / 365.25) as age,count(*) as count from users where birth_day is not null and birth_day not in ('1900-01-01 00:00:00','1970-01-01 00:00:00') and FLOOR(DATEDIFF(DAY, users.birth_day, getdate()) / 365.25)>10 group by (FLOOR(DATEDIFF(DAY, users.birth_day, getdate()) / 365.25)) order by count desc")->result_array();

        $areawise_users = $this->db->query("SELECT TOP 10 UPPER(Area) as area,UPPER(location) as location,count(*) as count from users where Area is not null group by Area,location order by count desc")->result_array();

        $res = array('Fabricspa' => $fab_count, 'Click2Wash' => $c2w_count, 'Males' => $male_count, 'Females' => $female_count, 'age_group' => $age_group, 'areawise_users' => $areawise_users);

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
    public function get_gcmids($via, $list, $brand, $device,$is_file)
    { 
        // $list_length=sizeof($list);
        // $gcmids = [];
        // if($is_file =="1")
        //     $list_length=$list_length-1;
        // for ($i = 0; $i < $list_length; $i++) {
        //     if($device == "all") {
        //         if($brand == 'Fabricspa'){
        //             $this->db->select('fabricspa_android_gcmid as gcmid');
        //             $this->db->where('fabricspa_android_gcmid !=',NULL);
        //             $this->db->from('Users');
        //             $this->db->where($via, $list[$i]);
        //             $row = $this->db->get()->row_array();
        //             if (isset($row['gcmid'])){
        //                 $status = array_push($gcmids, $row);
        //             }else{
        //                 $this->db->select('fabricspa_ios_gcmid as gcmid');
        //                 $this->db->where('fabricspa_ios_gcmid !=',NULL);
        //                 $this->db->from('Users');
        //                 $this->db->where($via, $list[$i]);
        //                 $row = $this->db->get()->row_array();
        //                 if (isset($row['gcmid'])){
        //                     $status = array_push($gcmids, $row);
        //                 }
        //             }
        //         }else{
        //             $this->db->select('click2wash_android_gcmid as gcmid');
        //             $this->db->where('click2wash_android_gcmid !=',NULL);
        //             $this->db->from('Users');
        //             $this->db->where($via, $list[$i]);
        //             $row = $this->db->get()->row_array();
        //             if (isset($row['gcmid'])){
        //                 $status = array_push($gcmids, $row);
        //             }else{
        //                 $this->db->select('click2wash_ios_gcmid as gcmid');
        //                 $this->db->where('click2wash_ios_gcmid !=',NULL);
        //                 $this->db->from('Users');
        //                 $this->db->where($via, $list[$i]);
        //                 $row = $this->db->get()->row_array();
        //                 if (isset($row['gcmid'])){
        //                     $status = array_push($gcmids, $row);
        //                 }
        //             }
        //         }
        //     }else{
        //         if ($brand == 'Fabricspa') {
        //             if ($device == 'android') {
        //                 $this->db->select('fabricspa_android_gcmid as gcmid');
        //                 $this->db->where('fabricspa_android_gcmid !=', NULL);
        //             } else if ($device == 'ios') {
        //                 $this->db->select('fabricspa_ios_gcmid as gcmid');
        //                 $this->db->where('fabricspa_ios_gcmid !=', NULL);
        //             } else {

        //             }
        //         } else if ($brand == 'Click2Wash') {
        //             if ($device == 'android') {
        //                 $this->db->select('click2wash_android_gcmid as gcmid');
        //                 $this->db->where('click2wash_android_gcmid !=', NULL);
        //             } else if ($device == 'ios') {
        //                 $this->db->select('click2wash_ios_gcmid as gcmid');
        //                 $this->db->where('click2wash_ios_gcmid !=', NULL);
        //             } else {
        //             }
                    
        //         } else {
        //         }

        //         $this->db->from('Users');

        //         $this->db->where($via, $list[$i]);

        //         $row = $this->db->get()->row_array();

        //         if (isset($row['gcmid']))
        //         // if($row['gcmid'])
        //             $status = array_push($gcmids, $row);
        //     }
        // }
        // if ($gcmids) {
        //     return $gcmids;
        // } else {
        //     return FALSE;
        // }
        $list_length=sizeof($list);
        $gcmids = [];
        if($is_file =="1")
            $list_length=$list_length-1;
        for ($i = 0; $i < $list_length; $i++) {
            if($device == "all") {
                if($brand == 'Fabricspa'){
                    $this->db->select('fabricspa_android_gcmid as gcmid');
                    $this->db->where('fabricspa_android_gcmid !=',NULL);
                    $this->db->from('Users');
                    $this->db->where($via, $list[$i]);
                    $row = $this->db->get()->row_array();
                    if (isset($row['gcmid'])){
                        $status = array_push($gcmids, $row);
                    }else{
                        if($via == "mobile_number"){// check if it's user's alternative number
                            $this->db->select('fabricspa_android_gcmid as gcmid');
                            $this->db->where('fabricspa_android_gcmid !=',NULL);
                            $this->db->from('Users');
                            $this->db->where('alternative_mobno', $list[$i]);
                            $row = $this->db->get()->row_array();
                            if (isset($row['gcmid'])){
                                $status = array_push($gcmids, $row);
                            }else{
                                $this->db->select('fabricspa_ios_gcmid as gcmid');
                                $this->db->where('fabricspa_ios_gcmid !=',NULL);
                                $this->db->from('Users');
                                $this->db->where($via, $list[$i]);
                                $row = $this->db->get()->row_array();
                                if (isset($row['gcmid'])){
                                    $status = array_push($gcmids, $row);
                                }else{
                                    $this->db->select('fabricspa_ios_gcmid as gcmid');
                                    $this->db->where('fabricspa_ios_gcmid !=',NULL);
                                    $this->db->from('Users');
                                    $this->db->where('alternative_mobno', $list[$i]);
                                    $row = $this->db->get()->row_array();
                                    if (isset($row['gcmid'])){
                                        $status = array_push($gcmids, $row);
                                    }
                                }
                            }
                        }else{
                            $this->db->select('fabricspa_ios_gcmid as gcmid');
                            $this->db->where('fabricspa_ios_gcmid !=',NULL);
                            $this->db->from('Users');
                            $this->db->where('alternative_mobno', $list[$i]);
                            $row = $this->db->get()->row_array();
                            if (isset($row['gcmid'])){
                                $status = array_push($gcmids, $row);
                            }
                        }
                    }
                }else{
                    $this->db->select('click2wash_android_gcmid as gcmid');
                    $this->db->where('click2wash_android_gcmid !=',NULL);
                    $this->db->from('Users');
                    $this->db->where($via, $list[$i]);
                    $row = $this->db->get()->row_array();
                    if (isset($row['gcmid'])){
                        $status = array_push($gcmids, $row);
                    }else{
                        $this->db->select('click2wash_ios_gcmid as gcmid');
                        $this->db->where('click2wash_ios_gcmid !=',NULL);
                        $this->db->from('Users');
                        $this->db->where($via, $list[$i]);
                        $row = $this->db->get()->row_array();
                        if (isset($row['gcmid'])){
                            $status = array_push($gcmids, $row);
                        }
                    }
                }
            }else{
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
                        $this->db->where('click2wash_ios_gcmid !=', NULL);
                    } else {
                    }
                    
                } else {}
                $this->db->from('Users');

                $this->db->where($via, $list[$i]);

                $row = $this->db->get()->row_array();
                if (isset($row['gcmid'])){
                    $status = array_push($gcmids, $row);
                }else if($via == "mobile_number"){
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
                            $this->db->where('click2wash_ios_gcmid !=', NULL);
                        } else {
                        }
                        
                    } else {}
                    $this->db->from('Users');
                    $this->db->where('alternative_mobno', $list[$i]);
                    $row = $this->db->get()->row_array();
                    if (isset($row['gcmid']))
                        $status = array_push($gcmids, $row);
                }else{}
            }
        }
        if ($gcmids) {
            return $gcmids;
        } else {
            return FALSE;
        }

    }
    /**
     * Getting total no of receivers
     */
    public function get_total_receivers($brand,$device,$location)
    {
        if($brand == "all"){
            if ($brand == 'Fabricspa') {
                // $this->db->select('id');
                $this->db->where('BrandCode','PCT0000001');
                $this->db->where('fabricspa_android_gcmid !=',NULL);
                $this->db->or_where('fabricspa_ios_gcmid !=',NULL);
                // $this->db->from('Users');
                if ($location != '') {
                    $this->db->where_in('location', [$location, strtoupper($location)]);
                }
                // $query = $this->db->get('Users');
                // $total = $query->num_rows();
                $total= $this->db->count_all_results('Users');

            }else{
                // $this->db->select('id');
                $this->db->where('BrandCode','PCT0000014');
                $this->db->where('click2wash_android_gcmid !=',NULL);
                $this->db->or_where('click2wash_ios_gcmid !=',NULL);
                // $this->db->from('Users');
                if ($location != '') {
                    $this->db->where_in('location', [$location, strtoupper($location)]);
                }
                // $query = $this->db->get('Users');
                // $total = $query->num_rows();
                $total= $this->db->count_all_results('Users');

            }
        }else{
            if ($brand == 'Fabricspa') {
                if ($device == 'android') {
                    $this->db->select('id');
                    $this->db->where(array('fabricspa_android_gcmid !=' => NULL,'sign_up_source' => 'ANDROID'));
                } else if ($device == 'ios') {
                    $this->db->select('id');
                    $this->db->where(array('fabricspa_ios_gcmid !=' => NULL,'sign_up_source' => 'IOS'));
                } else {

                }
            } else if ($brand == 'Click2Wash') {
                if ($device == 'android') {
                    $this->db->select('id');
                    $this->db->where('click2wash_android_gcmid !=', NULL);
                } else if ($device == 'ios') {
                    $this->db->select('id');
                    $this->db->where('click2wash_ios_gcmid !=', NULL);
                } else {
                }
                
            } else {
            }
            if ($location != '') {

                $this->db->where_in('location', [$location, strtoupper($location)]);
            }

            $total= $this->db->count_all_results('Users');
        }
        return $total;
    }
    /**
     * Getting total no of rows in notification table
     */
    public function get_count() {
        return $this->db->count_all('Notifications');
    }

    /**
     * Getting all notification history
     */
    public function get_notification_details()
    {
        $data = $this->db->select('*')->from('Notifications')->order_by('notification_id','desc')->get()->result_array();
        return $data;
        // $this->db->limit($limit, $start);
        // $data = $this->db->select('*')->from('Notifications')->get()->result_array();
        // return $data;

        
    }
    /**
     * Updating cancelled notifications
     */
    public function cancel_scheduled_notifications($id,$date)
    {
        $data1=array(
            'status' => 'Cancelled',
            'schedule_cancel_date' => $date
        );
        $this->db->where('notification_id',$id);
        $this->db->update('Notifications',$data1);
        $data2=array(
            'status' => 'Cancelled'
        );
        $this->db->where('notification_id',$id);
        $status = $this->db->update('Notification_Details',$data2);

        return $status;
    }
    /**
     * Getting notifications scheduled for today
     */
    public function get_scheduled_notifications($date,$time)
    {

        $data = $this->db->select('*')->from('Notifications')->where(array('schedule_date'=> $date,'time_slot' => $time,'status' =>'Scheduled'))->get()->result_array();
        return $data;
    }
    /**
     * Getting mobile numbers of scheduled notifications
     */
    public function get_mobileno_of_scheduled_notifications($id)
    {
        $data = $this->db->select('mobile_number')->from('Notification_Details')->where('notification_id',$id)->get()->result_array();
        return $data;
    }
    /**
     * Getting the GCMIDs via mobile number
     */
    public function get_gcmid_from_mobileno($mobile_number,$brand)
    {
        if($brand == 'Fabricspa'){
           $brand_code = 'PCT0000001';
        }else{
            $brand_code = 'PCT0000014';
        }
        $data = array();
        $details = $this->db->select('*')->from('Users')->where(array('mobile_number' => $mobile_number,'BrandCode' =>$brand_code))->get()->result_array();
        if($brand == 'Fabricspa'){
            if($details[0]['fabricspa_android_gcmid'] != ""){
                array_push($data,$details[0]['fabricspa_android_gcmid']);
            }else if($details[0]['fabricspa_ios_gcmid'] != ""){
                array_push($data,$details[0]['fabricspa_ios_gcmid']);
            }else{}
        }else{
            if($details[0]['click2wash_android_gcmid'] != ""){
                array_push($data,$details[0]['click2wash_android_gcmid']);
            }else if($details[0]['click2wash_ios_gcmid'] != ""){
                array_push($data,$details[0]['click2wash_ios_gcmid']);
            }else{}
        }
        return $data;
    }
    /**
     * Updating scheduled notification status in notifications and notification details table
     */
    public function update_send_status($id,$status)
    {
        // $data = array('status' => $status);
        // $this->db->where('notification_id',$id);
        // $status1 = $this->db->update('Notifications',$data);
        // $this->db->where('notification_id',$id);
        // $status2 = $this->db->update('Notification_Details',$data);
        // if($status1 && $status2)
        //     return True;
        // else
        //     return False;
         $data1 = array(
            'status' => $status,
            'date' => date('Y-m-d H:i:s')
        );
        $data2 = array(
            'status' => $status,
        );
        $this->db->where('notification_id',$id);
        $status1 = $this->db->update('Notifications',$data1);
        $this->db->where('notification_id',$id);
        $status2 = $this->db->update('Notification_Details',$data2);
        if($status1 && $status2)
            return True;
        else
            return False;

    }
    /**
     * Getting notification send user's history by passing notification_id
     */
    public function get_notification_users_details($id)
    {
        $data = $this->db->select('*')->from('Notification_Details')->where('notification_id',$id)->get()->result_array();
        return $data;
    }
    /**
     * Getting notification details for making report 
     */
    public function get_notification_data_for_report($brand,$device,$location,$start_date,$end_date,$status)
    {
       if($brand != "no"){
           if($device != "no"){
               if($location != "no"){
                   if($status !="no"){
                        $multiple_where = array('brand' => $brand,'device' => $device,'location' => $location,'status' => $status);
                   }else{
                       $multiple_where = array('brand' => $brand,'device' => $device,'location' => $location);
                   }
               }else{
                   if($status != "no"){
                        $multiple_where = array('brand' => $brand,'device' => $device,'status'=> $status);
                   }else{
                        $multiple_where = array('brand' => $brand,'device' => $device);
                   }
               }
           }else{
                if($location != "no"){
                    if($status !="no"){
                        $multiple_where = array('brand' => $brand,'location' => $location,'status' => $status);
                    }else {
                        $multiple_where = array('brand' => $brand,'location' => $location);
                    }
                }else{
                    if($status != "no"){
                        $multiple_where = array('brand' => $brand,'status' =>$status);
                    }else{
                        $multiple_where = array('brand' => $brand);
                    }
                }

           }
       }else {
            if($device != "no"){
                if($location != "no"){
                    if($status != "no"){
                        $multiple_where = array('device' => $device,'location' => $location,'status' =>$status );
                    }else {
                        $multiple_where = array('device' => $device,'location' => $location);
                    }
                }else{
                    if($status != "no"){
                        $multiple_where = array('device' => $device,'status' =>$status);
                    }else {    
                        $multiple_where = array('device' => $device);
                    }
                }
            }else {
                if($location != "no"){
                    if($status != "no"){
                        $multiple_where = array('location' => $location,'status' => $status);
                    }else{
                        $multiple_where = array('location' => $location);
                    }
                }else{
                    if($status != "no"){
                        $multiple_where = array('status' => $status);
                    }else {
                        $multiple_where = "";
                    }
                }
            }
       }
       $this->db->select('*');
       if($multiple_where != ""){
            $this->db->where($multiple_where);
       }
       if($start_date != ""){
            $this->db->where('date >=', $start_date);
       }
       if($end_date != ""){
           $this->db->where('date <= ',$end_date);
       }
       $this->db->from('Notifications');
       $this->db->order_by('notification_id', 'asc');
       $data = $this->db->get()->result_array();
       if ($data) {
           return $data ;
       } else {
           return FALSE;
       }
    }
    /**
     * Getting user's data for preparing report
     */
    public function get_userdata_for_report($id)
    {
        $data = $this->db->select('*')->from('Notification_Details')->where('notification_id',$id)->get()->result_array();
        return $data;
    }
    /**
     * Getting the GCMIDs via mobile number or email address
     * @param $via -- Column name(email or mobile_number)
     * @param $list -- List of mobile numbers of email addresses
     * @return array|bool
     */
    public function get_all_gcmids($brand, $device, $location, $start, $limit)
    {
        if($device == "all"){
            if($brand == 'Fabricspa'){
                $i='';
                $data=array();
                for($i=1;$i<=$limit;$i++){
                    if($i==1)
                        $n=$start;
                    else
                        $n =$n+$start;
                    $this->db->select('*');
                    $this->db->where('fabricspa_android_gcmid !=',NULL);
                    $this->db->or_where('fabricspa_ios_gcmid !=',NULL);
                    $this->db->from('Users');
                    if ($location != '') {
                        $this->db->where_in('location', [$location, strtoupper($location)]);
                    }
                    $this->db->limit(1, $n);
                    $result = $this->db->get()->result_array();
                    if($result[0]['fabricspa_android_gcmid'] !="")
                        array_push($data,array('gcmids'=> $result[0]['fabricspa_android_gcmid']));
                        //array_push($data, $result[0]['fabricspa_android_gcmid']);

                    else
                        array_push($data,array('gcmids' => $result[0]['fabricspa_ios_gcmid']));
                        //array_push($data,$result[0]['fabricspa_ios_gcmid']);

                }
            }else{
                $i='';
                $data=array();
                for($i=1;$i<=$limit;$i++){
                    if($i==1)
                        $n=$start;
                    else
                        $n =$n+$start;
                    $this->db->select('*');
                    $this->db->where('click2wash_android_gcmid !=',NULL);
                    $this->db->or_where('click2wash_ios_gcmid !=',NULL);
                    $this->db->from('Users');
                    if ($location != '') {
                        $this->db->where_in('location', [$location, strtoupper($location)]);
                    }
                    $this->db->limit(1, $n);
                    
                    $result = $this->db->get()->result_array();
                    if($result[0]['fabricspa_android_gcmid'] !="")
                        array_push($data,$result[0]['click2wash_android_gcmid']);
                    else
                        array_push($data,$result[0]['click2wash_ios_gcmid']);
                }
            }
        }else{
            $data=array();
            if ($brand == 'Fabricspa') {
                if ($device == 'android') {

                    $this->db->select('fabricspa_android_gcmid as gcmids');
                    $this->db->where('fabricspa_android_gcmid !=', NULL);
                } else if ($device == 'ios') {
                    $this->db->select('fabricspa_ios_gcmid as gcmids');
                    $this->db->where('fabricspa_ios_gcmid !=', NULL);
                } else {

                }
            } else if ($brand == 'Click2Wash') {
                if ($device == 'android') {

                    $this->db->select('click2wash_android_gcmid as gcmids');
                    $this->db->where('click2wash_android_gcmid !=', NULL);
                } else if ($device == 'ios') {

                    $this->db->select('click2wash_ios_gcmid as gcmids');
                    $this->db->where('click2wash_ios_gcmid !=', NULL);
                } else {

                }

            } else {

            }
            $this->db->from('Users');
            if ($location != '') {
                $this->db->where_in('location', [$location, strtoupper($location)]);
            }
            if (isset($start) && isset($limit)) {
                $this->db->limit($limit, $start);
            }
            $this->db->order_by('id', 'asc');
            $data = $this->db->get()->result_array();
        }
        if ($data) {
            return $data ;
        } else {
            return FALSE;
        }

    }
    /**
     * Getting gcmids within a limit
     */
    public function get_all_gcmid($brand,$location, $start, $limit)
    {
        if($brand == 'Fabricspa')
            $brand_code='PCT0000001';
        else if($brand == 'Click2Wash')
            $brand_code='PCT0000014';
        $data=array();
        for($i=1;$i<=$limit;$i++){
            if($i==1)
                $n=$start;
            else
                $n =$n+$start;
            if($location)
                $this->db->where('location',$location);
                $this->db->or_where('location',strtoupper($location));
            $this->db->select('*');
            $this->db->from('Users');
            $this->db->where('BrandCode',$brand_code);
            $this->db->limit(1, $n);
            $result = $this->db->get()->result_array();
            if($brand == 'Fabricspa'){
                    if(isset($result[0]['fabricspa_android_gcmid']))
                        array_push($data,array('gcmids'=> $result[0]['fabricspa_android_gcmid']));
                
                    else if(isset($result[0]['fabricspa_ios_gcmid']))
                        array_push($data,array('gcmids' => $result[0]['fabricspa_ios_gcmid']));
                
            }else if($brand == 'Click2Wash'){
                    if(isset($result[0]['fabricspa_android_gcmid']))
                        array_push($data,array('gcmids'=> $result[0]['click2wash_android_gcmid']));
                    else if(isset($result[0]['click2wash_ios_gcmid']))

                        array_push($data,array('gcmids' => $result[0]['click2wash_ios_gcmid']));
            }else{}
        }
        if ($data) {
            return $data ;
        } else {
            return FALSE;
        }
    }
    /**
     * Getting  all mobile numbers
     */
    public function get_all_mobile_numbers($brand, $device, $location, $start, $limit)
    {
        if ($brand == 'Fabricspa') {
            if ($device == 'android') {

                $this->db->select('fabricspa_android_gcmid as gcmids');
                $this->db->where('fabricspa_android_gcmid !=', NULL);
            } else if ($device == 'ios') {
                $this->db->select('fabricspa_ios_gcmid as gcmids');
                $this->db->where('fabricspa_ios_gcmid !=', NULL);
            } else {

            }
        } else if ($brand == 'Click2Wash') {
            if ($device == 'android') {

                $this->db->select('click2wash_android_gcmid as gcmids');
                $this->db->where('click2wash_android_gcmid !=', NULL);
            } else if ($device == 'ios') {

                $this->db->select('click2wash_ios_gcmid as gcmids');
                $this->db->where('click2wash_ios_gcmid !=', NULL);
            } else {

            }

        } else {

        }
        $this->db->from('Users');

        if ($location != '') {

            $this->db->where_in('location', [$location, strtoupper($location)]);
        }

        if (isset($start) && isset($limit)) {

            $this->db->limit($limit, $start);
        }
        $this->db->order_by('id', 'asc');
        $data = $this->db->get()->result_array();
        $n="0";
        foreach($data as $p)
        {
            if($p['gcmids'] == "(null)") {
                
                $n=$n+1;
               
            }
        }
        $limit=$limit-$n;
        $null="(null)";
        $fabandWhere = ['fabricspa_android_gcmid !=' => NULL, 'fabricspa_android_gcmid !=' => $null];
        $fabiosWhere = ['fabricspa_ios_gcmid !=' => NULL, 'fabricspa_ios_gcmid !=' => $null];
        $clickandWhere = ['click2wash_android_gcmid !=' => NULL, 'click2wash_android_gcmid !=' => $null];
        $clickiosWhere = ['click2wash_ios_gcmid !=' => NULL, 'click2wash_ios_gcmid !=' => $null];
        if ($brand == 'Fabricspa') {
            if ($device == 'android') {

                $this->db->select('mobile_number as mobile_number');
                $this->db->where($fabandWhere);
            } else if ($device == 'ios') {
                $this->db->select('mobile_number as mobile_number');
                $this->db->where( $fabiosWhere);
            } else {

            }
        } else if ($brand == 'Click2Wash') {
            if ($device == 'android') {

                $this->db->select('mobile_number as mobile_number');
                $this->db->where($clickandWhere);
            } else if ($device == 'ios') {

                $this->db->select('mobile_number as mobile_number ');
                $this->db->where($clickiosWhere);
            } else {

            }

        } else {

        }
        $this->db->from('Users');

        if ($location != '') {

            $this->db->where_in('location', [$location, strtoupper($location)]);
        }

        if (isset($start) && isset($limit)) {

            $this->db->limit($limit, $start);
        }
        $this->db->order_by('id', 'asc');
        $mobile_numbers = $this->db->get()->result_array();
       
        if ($mobile_numbers) {
            return $mobile_numbers;
        } else {
            return FALSE;
        }
    }

    //   /*
    // * Get mobile numbers without gcmids
    //  */
    // public function get_mobile($brand, $device, $location, $start, $end)
    // {
    //     if ($brand == 'Fabricspa') {
    //         if ($device == 'android') {

    //             $this->db->select('fabricspa_android_gcmid as gcmids');
    //             $this->db->where('fabricspa_android_gcmid !=', NULL);
    //         } else if ($device == 'ios') {
    //             $this->db->select('fabricspa_ios_gcmid as gcmids');
    //             $this->db->where('fabricspa_ios_gcmid !=', NULL);
    //         } else {

    //         }
    //     } else if ($brand == 'Click2Wash') {
    //         if ($device == 'android') {

    //             $this->db->select('click2wash_android_gcmid as gcmids');
    //             $this->db->where('click2wash_android_gcmid !=', NULL);
    //         } else if ($device == 'ios') {

    //             $this->db->select('click2wash_ios_gcmid as gcmids');
    //             $this->db->where('click2wash_ios_gcmid !=', NULL);
    //         } else {

    //         }

    //     } else {

    //     }
    //     $this->db->from('Users');

    //     if ($location != '') {

    //         $this->db->where_in('location', [$location, strtoupper($location)]);
    //     }

    //     if (isset($start) && isset($limit)) {

    //         $this->db->limit($limit, $start);
    //     }
    //     $this->db->order_by('id', 'asc');
    //     $data = $this->db->get()->result_array();
    //     $n="0";
    //     foreach($data as $p)
    //     {
    //         if($p['gcmids'] == "(null)") {
                
    //             $n=$n+1;
               
    //         }
    //     }
    //     if($n>0) {
    //         $this->db->select('mobile_number as number');
    //         $this->db->where($brand.'_'.$device.'_gcmid' ,"(null)");
    //         $this->db->from('Users');
    //         if ($location != '') {

    //             $this->db->where_in('location', [$location, strtoupper($location)]);
    //         }
    //         $this->db->order_by('Id','ASC')->limit($n);
    //         $mobile_number=$this->db->get()->result_array();
    //     }
      
    //     if ($mobile_number) {
    //         return $mobile_number;
    //     } else {
    //         return FALSE;
    //     }
    // }
    /**
     * Getting users with no gcmid
     */
    // public function get_mobile_number_without_gcmid($brand, $device, $location,$start,$limit)
    // {
        
        
        // $null="(null)";
        // if ($brand == 'Fabricspa') {
        //     if ($device == 'android') {

        //         $this->db->select('mobile_number as mobile_numbers');
        //         $this->db->where('fabricspa_android_gcmid', $null);
        //     } else if ($device == 'ios') {
        //         $this->db->select('mobile_number as mobile_numbers');
        //         $this->db->where('fabricspa_ios_gcmid', $null);
        //     } else {

        //     }
        // } else if ($brand == 'Click2Wash') {
        //     if ($device == 'android') {

        //         $this->db->select('mobile_number as mobile_numbers');
        //         $this->db->where('click2wash_android_gcmid !=', $null);
        //     } else if ($device == 'ios') {

        //         $this->db->select('mobile_number as mobile_numbers');
        //         $this->db->where('click2wash_ios_gcmid !=', $null);
        //     } else {

        //     }

        // } else {

        // }
        // $this->db->from('Users');
        // if ($location != '') {

        //     $this->db->where_in('location', [$location, strtoupper($location)]);
        // }
        // $mobile_numbers = $this->db->get()->result_array();


    //     if ($mobile_numbers) {
    //         return $mobile_numbers;
    //     } else {
    //         return FALSE;
    //     }
    // }
    public function get_mobile_number_without_gcmid($brand, $device, $location,$start,$limit,$date,$title,$image_url,$message,$sender_id)
    {
        if($limit> 1000) {
            $limit="1000";
        }
        if ($brand == 'Fabricspa') {
            if ($device == 'android') {

                $this->db->select('fabricspa_android_gcmid as gcmids');
                $this->db->where('fabricspa_android_gcmid !=', NULL);
            } else if ($device == 'ios') {
                $this->db->select('fabricspa_ios_gcmid as gcmids');
                $this->db->where('fabricspa_ios_gcmid !=', NULL);
            } else {

            }
        } else if ($brand == 'Click2Wash') {
            if ($device == 'android') {

                $this->db->select('click2wash_android_gcmid as gcmids');
                $this->db->where('click2wash_android_gcmid !=', NULL);
            } else if ($device == 'ios') {

                $this->db->select('click2wash_ios_gcmid as gcmids');
                $this->db->where('click2wash_ios_gcmid !=', NULL);
            } else {

            }

        } else {

        }
        $this->db->from('Users');

        if ($location != '') {

            $this->db->where_in('location', [$location, strtoupper($location)]);
        }

        if (isset($start) && isset($limit)) {

            $this->db->limit($limit, $start);
        }
        $this->db->order_by('id', 'asc');
        $data = $this->db->get()->result_array();
        $n="0";
        foreach($data as $p)
        {
            if($p['gcmids'] == "(null)") {
                
                $n=$n+1;
               
            }
        }
        if($n>0) {
            $this->db->select('mobile_number');
            $this->db->where($brand.'_'.$device.'_gcmid' ,"(null)");
            $this->db->from('Users');
            if ($location != '') {

                $this->db->where_in('location', [$location, strtoupper($location)]);
            }
            $this->db->order_by('Id','ASC')->limit($n);
            $mobile_number=$this->db->get()->result_array();
            // $mobile_number=$this->db->select('mobile_number')->from('Users')->where($brand.'_'.$device.'_gcmid' ,"(null)")->order_by('Id','ASC')->limit($n)->get()->result_array();
            $status="Not Send";
            $mobile_number=json_encode($mobile_number);
            $res = $this->save_notified_users($date,$brand,$device,$mobile_number, $title,$image_url,$message,$sender_id,$location,$status); 
        }
            return true;
      
    }
    /**
     * Saving notifications to table
     */
    public function save_notifications($date,$brand,$sender_id,$device,$location,$title,$image_url,$message,$via,$status,$schedule_date,$total,$user,$time)
    {
        if($location == "")
            $location = "All";
        if($image_url !=""){

            $sent_with="With Image";

        }else{
            $sent_with="Without Image";
        }
        $date = date('Y-m-d H:i:s');
        if($title != ""){
            if (strpos($title, "'") !== FALSE){
                $title = str_replace("'","''",$title);
            }
            
        }
                if($message != ""){
            if (strpos($message, "'") !== FALSE){
                $message = str_replace("'","''",$message);
            }
            
        }
       if($schedule_date != "today") {
            $sql = " INSERT INTO [dbo].[Notifications]
                ([date]
                ,[brand]
                ,[sender_id]
                ,[device]
                ,[location]
                ,[title]
                ,[sent_image]
                ,[image_url]
                ,[message]
                ,[list_via]
                ,[status]
                ,[schedule_date]
                ,[total_receivers]
                ,[created_by]
                ,[time_slot])
            VALUES
                ('$date'
                ,'$brand'
                ,'$sender_id'
                ,'$device'
                ,'$location'
                ,N'$title'
                ,'$sent_with'
                ,'$image_url'
                ,N'$message'
                ,'$via'
                ,'$status'
                ,'$schedule_date' 
                ,'$total'
                ,'$user'
                ,'$time' ) ";
       
       }else {
                $sql = " INSERT INTO [dbo].[Notifications]
                ([date]
                ,[brand]
                ,[sender_id]
                ,[device]
                ,[location]
                ,[title]
                ,[sent_image]
                ,[image_url]
                ,[message]
                ,[list_via]
                ,[status]
                ,[total_receivers]
                ,[created_by]
                ,[time_slot])
            VALUES
                ('$date'
                ,'$brand'
                ,'$sender_id'
                ,'$device'
                ,'$location'
                ,N'$title'
                ,'$sent_with'
                ,'$image_url'
                ,N'$message'
                ,'$via'
                ,'$status'
                ,'$total'
                ,'$user'
                ,'$time' ) ";
        }
         $status = $this->db->query($sql);
        if($status) {
            $id = $this->db->insert_id();
            return $id;
        }else {
            return FALSE;
        }
    }


 /**
     * Saving notifications to table
     */
    public function update_notifications($status,$total,$id)
    {
       
          
		if($total=='' || $total==NULL  || $total==null || $total=='NULL'  || $total=='null' )
		$total=0;
	  $insert_data = array(
                'status' => $status ,
                'total_receivers' => $total 
            );

              $this->db->where('notification_id', $id);
            $status = $this->db->update('Notifications', $insert_data);

            return $id;
        
    }
    /**
     * Getting last inserted notification's id
     */
    public function get_notification_id()
    {
        $data=$this->db->select('notification_id')->from('Notifications')->order_by('notification_id','desc')->Limit(1)->get()->result_array();
        return $data;
    }
     /**
     * Getting  mobile number associated with the corresponsing GCMID
     */
    public function get_mobile_number($brand, $device, $gcmid)
    {
        if($device == "all"){
            if($brand == 'Fabricspa'){
                $this->db->select('mobile_number as mobile_number');
                $this->db->where('fabricspa_android_gcmid',$gcmid);
                $this->db->from('Users');
                $mobile_number = $this->db->get()->result_array();
                if($mobile_number == "") {
                    $this->db->select('mobile_number as mobile_number');
                    $this->db->where('fabricspa_ios_gcmid',$gcmid);
                    $this->db->from('Users');
                    $mobile_number = $this->db->get()->result_array();
                }
            }else{
                $this->db->select('mobile_number as mobile_number');
                $this->db->where('click2wash_android_gcmid',$gcmid);
                $this->db->from('Users');
                $mobile_number = $this->db->get()->result_array();
                if($mobile_number == "") {
                    $this->db->select('mobile_number as mobile_number');
                    $this->db->where('click2wash_ios_gcmid',$gcmid);
                    $this->db->from('Users');
                    $mobile_number = $this->db->get()->result_array();
                }
            }

        }else{
            if ($brand == 'Fabricspa') {
                if ($device == 'android') {

                    $this->db->select('mobile_number as mobile_number');
                    $this->db->where(array('fabricspa_android_gcmid'=> $gcmid,'sign_up_source' => 'ANDROID'));
                } else if ($device == 'ios') {
                    $this->db->select('mobile_number as mobile_number');
                    $this->db->where(array('fabricspa_ios_gcmid'=> $gcmid,'sign_up_source' => 'IOS'));
                } else {

                }
            } else if ($brand == 'Click2Wash') {
                if ($device == 'android') {

                    $this->db->select('mobile_number as mobile_number');
                    $this->db->where(array('click2wash_android_gcmid '=> $gcmid,'sign_up_source' => 'ANDROID'));
                } else if ($device == 'ios') {

                    $this->db->select('mobile_number as mobile_number');
                    $this->db->where(array('click2wash_ios_gcmid ' => $gcmid,'sign_up_source' => 'IOS'));
                } else {

                }

            } else {

            }
            $this->db->from('Users');
            $mobile_number = $this->db->get()->result_array();
        }
        if ($mobile_number) {
            return $mobile_number[0]['mobile_number'];
        } else {
            return FALSE;
        }
    }
    /**
     * Saving notification details
     */
    public function save_notification_details($id,$mobile_number, $status)
    {
        // $this->db->select('notification_id');
        // $this->db->from('Notification_Details');
        // $this->db->where(array('mobile_number'=> $mobile_number,'notification_id' => $id));
        // $ret = $this->db->get()->num_rows();
        // if($ret == ""){
            $data=array(
                'notification_id' => $id,
                'mobile_number' => $mobile_number,
                'status' => $status,
    		'read_status' => '0'
            );
            $status=$this->db->insert('Notification_Details',$data);
            if($status)
                return TRUE;
            else
                return FALSE;
        // }else{
        //     return TRUE;
        // }
    }
    /**
     * Getting mobile numbers with gcmid null
     */
    public function get_mobile_no_with_null_gcmid($brand, $device, $location,$start,$limit)
    {
        if ($brand == 'Fabricspa') {
            if ($device == 'android') {

                $this->db->select('fabricspa_android_gcmid as gcmids');
                $this->db->where('fabricspa_android_gcmid !=', NULL);
            } else if ($device == 'ios') {
                $this->db->select('fabricspa_ios_gcmid as gcmids');
                $this->db->where('fabricspa_ios_gcmid !=', NULL);
            } else {

            }
        } else if ($brand == 'Click2Wash') {
            if ($device == 'android') {

                $this->db->select('click2wash_android_gcmid as gcmids');
                $this->db->where('click2wash_android_gcmid !=', NULL);
            } else if ($device == 'ios') {

                $this->db->select('click2wash_ios_gcmid as gcmids');
                $this->db->where('click2wash_ios_gcmid !=', NULL);
            } else {

            }

        } else {

        }
        $this->db->from('Users');

        if ($location != '') {

            $this->db->where_in('location', [$location, strtoupper($location)]);
        }

        if (isset($start) && isset($limit)) {

            $this->db->limit($limit, $start);
        }
        $this->db->order_by('id', 'asc');
        $data = $this->db->get()->result_array();
        $n="0";
        foreach($data as $p)
        {
            if($p['gcmids'] == "(null)") {
                
                $n=$n+1;
               
            }
        }
        if($n>0) {
            $this->db->select('mobile_number');
            $this->db->where($brand.'_'.$device.'_gcmid' ,"(null)");
            $this->db->from('Users');
            if ($location != '') {

                $this->db->where_in('location', [$location, strtoupper($location)]);
            }
            $this->db->order_by('Id','ASC')->limit($n);
            $mobile_number=$this->db->get()->result_array();
        }else{
            $mobile_number="";
        }
        
        return $mobile_number; 
    }
    /**
     * Saving notified users 
     */
    public function save_notified_users($date,$brand,$device,$mobile_number, $title,$image_url,$message,$sender_id,$location,$status)
    { 
        if($image_url !=""){

            $sent_with="With Image";

        }else{
            $sent_with="Without Image";
        }
       
        $insert_data = array(
            'mobile_numbers' => $mobile_number,
            'message' => $message,
            'title' => "N".$title."",
            'sender_id'=>$sender_id,
            'device' => $device,
            'location'=>$location,
            'date' => $date,
            'sent_image'=>$sent_with,
           'image_url' => $image_url,
           'status' => $status
           
        );
         $status = $this->db->insert('Notifications', $insert_data);
        if($status) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    /**
     *  Getting details of notification sended users 
     *   
     */
    public function get_notification_data($fraction)
    {
        $data=$this->db->select('*')->from('Notifications')->order_by('Id','desc')->limit($fraction)->get()->result_array();
       
        return $data;
    }
    /*
    *Getting total no of users 
    */
    public function get_total_users($device,$brand,$location)
    {
        if ($brand == 'Fabricspa') {
            if ($device == 'android') {

                // $this->db->select('fabricspa_android_gcmid as gcmids');
                $this->db->where('fabricspa_android_gcmid !=', NULL);
            } else if ($device == 'ios') {
                $this->db->select('fabricspa_ios_gcmid as gcmids');
                $this->db->where('fabricspa_ios_gcmid !=', NULL);
            } else {

            }
        } else if ($brand == 'Click2Wash') {
            if ($device == 'android') {

                // $this->db->select('click2wash_android_gcmid as gcmids');
                $this->db->where('click2wash_android_gcmid !=', NULL);
            } else if ($device == 'ios') {

                // $this->db->select('click2wash_ios_gcmid as gcmids');
                $this->db->where('click2wash_ios_gcmid !=', NULL);
            } else {

            }

        } else {

        }
        // $this->db->from('Users');
        if ($location != '') {

            $this->db->where_in('location', [$location, strtoupper($location)]);
        }

        // $this->db->order_by('id', 'asc');
        // $query = $this->db->get();
        // $data=$query->num_rows();
        $data= $this->db->count_all_results('Users');
        if ($data) {
            return $data;
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
     * Returns all the registered qa user in the DB.
     * @return mixed
     */
    public function get_qa_users()
    {
        $qa_users = $this->db->select('*')->from('QA_Users')->order_by('Id', 'asc')->get()->result_array();
        return $qa_users;
    }


    /*Saving qa_users*/
    /**
     * Saving a qa user
     * @param $qa_user
     * @return mixed
     */
    public function save_qa_user($qa_user)
    {

        $status = $this->db->insert('QA_Users', $qa_user);
        $id = $this->db->insert_id();
        $saved_qa_user = $this->db->select('*')->from('QA_Users')->where('Id', $id)->get()->result_array();
        return $saved_qa_user;
    }

    /**
     * Updating a qa user details from the console.
     * @param $qa_user
     * @param $qa_user_id
     * @return mixed
     */
    public function update_qa_user($qa_user, $qa_user_id)
    {

        $this->db->where('Id', $qa_user_id);
        $updated_status = $this->db->update('qa_Users', $qa_user);
        $updated_qa_user = $this->db->select('*')->from('QA_Users')->where('Id', $qa_user_id)->get()->result_array();
        return $updated_qa_user;
    }

    /**
     * Deleting a qa user from the console.
     * @param $qa_users
     * @return mixed
     */
    public function delete_qa_users($qa_users)
    {

        $delete_status = $this->db->where_in('Id', $qa_users)->delete('QA_Users');
        return $delete_status;

    }

    /**
     * Returns all the registered qa user in the DB.
     * @return mixed
     */
    public function get_qa_finished_by_users()
    {
        $qa_finished_by_users = $this->db->select('*')->from('qa_finished_by_users')->order_by('Id', 'asc')->get()->result_array();
        return $qa_finished_by_users;
    }


    /*Saving qa_finished_by_users*/
    /**
     * Saving a qa finished by user
     * @param $qa_finished_by_user
     * @return mixed
     */
    public function save_qa_finished_by_user($qa_finished_by_user)
    {

        $status = $this->db->insert('qa_finished_by_users', $qa_finished_by_user);
        $id = $this->db->insert_id();
        $saved_qa_finished_by_user = $this->db->select('*')->from('qa_finished_by_users')->where('Id', $id)->get()->result_array();
        return $saved_qa_finished_by_user;
    }

    /**
     * Updating a qa finished by user details from the console.
     * @param $qa_finished_by_user
     * @param $qa_finished_by_user_id
     * @return mixed
     */
    public function update_qa_finished_by_user($qa_finished_by_user, $qa_finished_by_user_id)
    {

        $this->db->where('Id', $qa_finished_by_user_id);
        $updated_status = $this->db->update('qa_finished_by_users', $qa_finished_by_user);
        $updated_qa_finished_by_user = $this->db->select('*')->from('qa_finished_by_users')->where('Id', $qa_finished_by_user_id)->get()->result_array();
        return $updated_qa_finished_by_user;
    }

    /**
     * Deleting a qa user from the console.
     * @param $qa_finished_by_users
     * @return mixed
     */
    public function delete_qa_finished_by_users($qa_finished_by_users)
    {

        $delete_status = $this->db->where_in('Id', $qa_finished_by_users)->delete('qa_finished_by_users');
        return $delete_status;

    }


    public function get_payment_details($limit = FALSE, $offset = FALSE, $customer_code, $start_date, $end_date, $payment_id)
    {
        if ($start_date && $end_date) {

            if ($customer_code != '') {

                $details = $this->db->limit($limit, $offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo', 'TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->where('TransactionInfo.CustomerCode', $customer_code)->where(array('TransactionInfo.TransactionDate>=' => $start_date, 'TransactionInfo.TransactionDate<=' => $end_date))->order_by('TransactionInfo.TransactionDate', 'desc')->get()->result_array();
            } else if ($payment_id != '') {

                //Payment ID will be provided here...
                $details = $this->db->limit($limit, $offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo', 'TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->where('TransactionPaymentInfo.PaymentId', $payment_id)->where(array('TransactionInfo.TransactionDate>=' => $start_date, 'TransactionInfo.TransactionDate<=' => $end_date))->order_by('TransactionInfo.TransactionDate', 'desc')->get()->result_array();
            } else {
                /*No customer id or payment id. Select all.*/
                $details = $this->db->limit($limit, $offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo', 'TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->where(array('TransactionInfo.TransactionDate>=' => $start_date, 'TransactionInfo.TransactionDate<=' => $end_date))->order_by('TransactionInfo.TransactionDate', 'desc')->get()->result_array();
            }

        } else {
            if ($customer_code != '') {

                $details = $this->db->limit($limit, $offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo', 'TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->where('TransactionInfo.CustomerCode', $customer_code)->order_by('TransactionInfo.TransactionDate', 'desc')->get()->result_array();
            } else if ($payment_id != '') {


                $details = $this->db->limit($limit, $offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo', 'TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->where('TransactionPaymentInfo.PaymentId', $payment_id)->order_by('TransactionInfo.TransactionDate', 'desc')->get()->result_array();
            } else {
                /*No customer id or payment id. Select all.*/
                $details = $this->db->limit($limit, $offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,IsNull(TransactionPaymentInfo.PaymentID,'N/A') as PaymentID,IsNull(TransactionPaymentInfo.PaymentMode,'N/A') as PaymentMode,IsNull(TransactionPaymentInfo.CouponCode,'N/A') as CouponCode,TransactionPaymentInfo.PaymentAmount,IsNull(PaymentGatewayStatusDescription,'N/A') as PaymentGatewayStatusDescription,IsNull(TransactionPaymentInfo.InvoiceNo,'N/A') as InvoiceNo,IsNull(TransactionPaymentInfo.Remarks,'N/A') as Remarks,IsNull(TransactionPaymentInfo.BranchCode,'N/A') as BranchCode")->from('TransactionInfo')->join('TransactionPaymentInfo', 'TransactionInfo.TransactionId=TransactionPaymentInfo.TransactionId', 'left')->order_by('TransactionInfo.TransactionDate', 'desc')->get()->result_array();
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
    public function get_brands()
    {
        $result = $this->db->select('BrandCode,BrandDescription')->from(SERVER_DB . '.dbo.BrandInfo')->get()->result_array();
        return $result;
    }

    public function get_all_stores()
    {
        $stores = $this->db->query('EXEC ' . SERVER_DB . '.dbo.sp_GetAllBranchDetails')->result_array();
        return $stores;
    }

    public function get_user_password($mobile_number)
    {
        $result = $this->db->select('Password')->from('DCR_Users')->where('Phone', $mobile_number)->get()->row_array();
        return $result;
    }

    public function get_offers()
    {

        $query = $this->db->select('*')->from('Offers')->get()->result_array();

        return $query;
    }
    public function get_essentialpopups()
    {
        $query = $this->db->select('*')->from('EssentialsStorePopupDetails')->where('category' ,'essentialpopup')->get()->result_array();
        return $query;
    }
     public function get_tip()
    {
        $this->db->select('*');
        $this->db->from('EssentialsStorePopupDetails');
        $this->db->where('category' ,'tip');
        $this->db->or_where('category' ,'tip-desktop');
        $this->db->or_where('category' ,'tip-mobile');
        $query = $this->db->get()->result_array();
        return $query;
    }
    public function get_wash_images()
    {
        $this->db->select('*');
        $this->db->from('EssentialsStorePopupDetails');
        $this->db->where('category' ,'schedule-wash');
        $this->db->or_where('category' ,'schedule-wash-desktop');
        $this->db->or_where('category' ,'schedule-wash-mobile');
        $query = $this->db->get()->result_array();
        return $query;
    }
    public function get_coupons()
    {

        $query = $this->db->select('*')->from('Coupons')->order_by('Id','desc')->get()->result_array();
        return $query;
    }
     public function get_campaign_details()
    {

        $query = $this->db->select('*')->from('Campaign_Data')->order_by('Id','desc')->get()->result_array();
        return $query;
    }
    public function add_campaign($title,$image,$desc,$code,$url,$start,$end,$created_by)
    {
        $date = date('Y-m-d');
        $array = array('title' => $title, 'image' => $image, 'description' => $desc, 'discount_code' => $code, 'url' => $url,'start_date' => $start,'end_date' => $end,'created_by' => $created_by,'date' => $date);
        $res = $this->db->insert('Campaign_Data', $array);
        return $res;
    }
    public function delete_campaign($id)
    {
         $img = $this->db->select('image')->from('Campaign_Data')->where('Id',$id)->get()->result_array();
        $url = substr($img[0]['image'],35);
        unlink($url);
        $status = $this->db->where_in('Id', $id)->delete('Campaign_Data');
        return $status;
    }
    public function get_campaign_details_from_id($id)
    {
        $query = $this->db->select('*')->from('Campaign_Data')->where('Id', $id)->get()->result_array();
        return $query;
    }
    public function update_campaign($campaign_id,$title,$desc,$code,$url,$start,$end)
    {
        $start = date('Y-m-d',strtotime($start));
        $end = date('Y-m-d',strtotime($end));
        $data = array('title' => $title, 'description' => $desc, 'discount_code' => $code, 'url' => $url,'start_date' => $start,'end_date' => $end);
        $status = $this->db->where('Id',$campaign_id)->update('Campaign_Data',$data);
        return $status;
    }
    public function get_campaign_data_from_date($start,$end)
    {
        $this->db->select('*');
        $this->db->from('Campaign_Data');
        if($start != "")
            $this->db->where('start_date >=', $start);
        if($end != "")
            $this->db->where('end_date <=' , $end);
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function get_states_sp()
    {
        $query =  $this->db->query('EXEC USP_GET_STATES_CITIES')->result_array();
        return $query;
    }
    public function get_state_cities_sp($statecode)
    {
        $query =  $this->db->query("EXEC USP_GET_STATES_CITIES @statecode='".$statecode."'")->result_array();
        return $query;
    }
    public function check_tip($device)
    {
        $query = $this->db->select('*')->from('EssentialsStorePopupDetails')->where('category' ,'tip-'.$device)->get()->num_rows();
        return $query;
    }
    public function save_essentialpopup($popup_id, $title)
    {
        $array = array('title' => $title);
        $res = $this->db->where('id', $popup_id)->update('EssentialsStorePopupDetails', $array);
        return $res;
    }
   public  function add_tip($tip_image,$category)
    {
        $array=array('title' => 'Nill','url' => $tip_image,'category' => $category);
        $res = $this->db->insert('EssentialsStorePopupDetails',$array);
        return $res;
    }  
    public function delete_tip($tip_id)
    {
        $status = $this->db->where_in('id', $tip_id)->delete('EssentialsStorePopupDetails');
        return $status;
    }
   public  function add_wash_image($wash_image,$state,$city,$brand_code,$site_url,$category)
    {
        $array=array('title' => 'Nill','url' => $wash_image,'category' => $category,'state' => $state,'city' => $city,'brandcode' => $brand_code,'site_url' => $site_url);
        $res = $this->db->insert('EssentialsStorePopupDetails',$array);
        return $res;
    }
 public function delete_essentialpopups($popup_id)
    {
        $status = $this->db->where_in('id', $popup_id)->delete('EssentialsStorePopupDetails');
        return $status;
    }
    public function delete_wash_image($wash_id)
    {
        $status = $this->db->where_in('id', $wash_id)->delete('EssentialsStorePopupDetails');
        return $status;
    }
    public function add_coupon($state, $city ,$promo_code, $discount_code, $app_remarks, $expiry_date)
    {
        $array = array('state' => $state, 'city' => $city, 'PromoCode' => $promo_code, 'DiscountCode' => $discount_code, 'AppRemarks' => $app_remarks, 'ExpiryDate' => $expiry_date);
        $res = $this->db->insert('Coupons', $array);
        return $res;
    }
    public function delete_coupon($id)
    {
        $status = $this->db->where_in('Id', $id)->delete('Coupons');
        return $status;
    }
    /**
     * Adding a new essential popup
     * @param $title
     * $param $essential_image
     */
     public function add_essentialpopups($title,$essential_image,$site_url,$state,$city,$brand_code)
    {
        $array = array('title' => $title,'url' => $essential_image,'category' => 'essentialpopup','site_url' => $site_url,'state' => $state,'city' => $city,'brandcode' => $brand_code);
        $res = $this->db->insert('EssentialsStorePopupDetails',$array);
        return $res;
    }
    public function add_popup($popup_image,$site_url,$state,$city,$expiry)
    {
        $array = array('title' => 'Nill','url' => $popup_image,'category' => 'homepage-popup','site_url' => $site_url,'state' => $state,'city' => $city,'expiry_date' => $expiry);
        $res = $this->db->insert('EssentialsStorePopupDetails',$array);
        return $res;
    }
    public function get_popup_details()
    {
        $query = $this->db->select('*')->from('EssentialsStorePopupDetails')->where('category' ,'homepage-popup')->get()->result_array();
        return $query;
    }
    public function delete_popup($id)
    {
        $status = $this->db->where_in('Id',$id)->delete('EssentialsStorePopupDetails');
        return $status;
    }
    /**
     * Saving an offer
     * @param $offer_id
     * @param $title
     * @param $description
     */
    public function save_offer($offer_id, $title, $description, $expiry, $brand_code)
    {
        $array = array('offer_heading' => $title, 'offer_description' => $description, 'expiry' => $expiry, 'brand_code' => $brand_code);
        $res = $this->db->where('id', $offer_id)->update('Offers', $array);
        return $res;
    }

    /**
     * Adding a new offer
     *
     * @param $title
     * @param $description
     */
    public function add_offer($title, $description, $expiry, $brand_code, $offer_image)
    {
        $array = array('offer_heading' => $title, 'offer_description' => $description, 'expiry' => $expiry, 'brand_code' => $brand_code, 'offer_img' => $offer_image);
        $res = $this->db->insert('Offers', $array);
        return $res;
    }

    public function delete_offer($offer_id)
    {
        $status = $this->db->where_in('id', $offer_id)->delete('Offers');
        return $status;
    }

    /*Getting the all feedbacks from the db*/
    public function get_feedbacks()
    {
        $user_feedbacks = $this->db->limit(50)->select('f.date,f.name,f.feedback,u.customer_id')->from('feedback f')->join('users u', 'u.id=f.user_id')->order_by('date', 'desc')->get()->result_array();
        $order_feedbacks = $this->db->limit(50)->select('*')->from('CompletedOrderFeedback')->order_by('date', 'desc')->get()->result_array();
        $data = array('user_feedbacks' => $user_feedbacks, 'order_feedbacks' => $order_feedbacks);

        return $data;

    }

    /**
     * Getting all the details from TransactionInfo details based on EGRN
     * @param $egrn
     */
    public function get_transaction_info_details_from_egrn($egrn)
    {
        $this->db->select("TI.TransactionDate, TI. PaymentId, TI.EGRNNo");
        $this->db->select("(CASE WHEN TI.DCNo IS NULL THEN 'N/A' WHEN TI.DCNo ='' THEN 'N/A' ELSE TI.DCNo END) as DCNo");
        $this->db->select("TI.TotalPayableAmount");
        $this->db->select("(CASE WHEN TPI.InvoiceNo IS NULL THEN 'N/A' WHEN TPI.InvoiceNo ='' THEN 'N/A' ELSE TPI.InvoiceNo END) as InvoiceNo");
        $this->db->select("(CASE WHEN TI.Gateway IS NULL THEN 'N/A' WHEN TI.Gateway ='' THEN 'N/A' ELSE TI.Gateway END) as Gateway");
        $this->db->from('TransactionInfoLogs TI');
        $this->db->join('TransactionPaymentInfoLogs TPI', 'TI.TransactionId=TPI.TransactionId AND (TI.PaymentId=TPI.PaymentId)', 'left');
        $this->db->where('TI.EGRNNo', $egrn);
        $this->db->order_by('TI.TransactionDate', 'desc');
        $details=$this->db->get()->result_array();
        return $details;
    }

    /**
     * Getting all the details from TransactionInfo details based on $payment_id
     * @param $payment_id
     */
    public function get_transaction_info_details_from_payment_id($payment_id)
    {
        $this->db->select("TI.TransactionDate, TI. PaymentId, TI.EGRNNo");
        $this->db->select("(CASE WHEN TI.DCNo IS NULL THEN 'N/A' WHEN TI.DCNo ='' THEN 'N/A' ELSE TI.DCNo END) as DCNo");
        $this->db->select("TI.TotalPayableAmount");
        $this->db->select("(CASE WHEN TPI.InvoiceNo IS NULL THEN 'N/A' WHEN TPI.InvoiceNo ='' THEN 'N/A' ELSE TPI.InvoiceNo END) as InvoiceNo");
        $this->db->select("(CASE WHEN TI.Gateway IS NULL THEN 'N/A' WHEN TI.Gateway ='' THEN 'N/A' ELSE TI.Gateway END) as Gateway");
        $this->db->from('TransactionInfoLogs TI');
        $this->db->join('TransactionPaymentInfoLogs TPI', 'TI.TransactionId=TPI.TransactionId AND (TI.PaymentId=TPI.PaymentId)', 'left');
        $this->db->where('TI.PaymentId', $payment_id);
        $this->db->order_by('TI.TransactionDate', 'desc');
        $details=$this->db->get()->result_array();
        return $details;
    }

    /**
     * Getting customer details from a given EGRN.
     * @param $egrn
     */
    public function get_customer_details_from_egrn($egrn)
    {
        $customer_details = $this->db->query("EXEC " . SERVER_DB . ".dbo.GetCustomerDataForPaymentCompletion @NO='" . $egrn . "'")->row_array();
        return $customer_details;
    }


    /**
     * Getting customer details from a given $payment_id.
     * @param $payment_id
     */
    public function get_customer_details_from_payment_id($payment_id)
    {
        $egrn = $this->db->select('EGRNNo')->from('TransactionInfoLogs')->where('PaymentId', $payment_id)->get()->row_array();
        $customer_details = $this->db->query("EXEC " . SERVER_DB . ".dbo.GetCustomerDataForPaymentCompletion @NO='" . $egrn['EGRNNo'] . "'")->row_array();
        return $customer_details;
    }

    /**
     * Getting the entries in the TransactionPaymentInfo table.
     * @param $payment_ids
     * @return mixed
     */
    public function get_transaction_payment_info_details($payment_ids)
    {
        $details = $this->db->select('*')->from('TransactionPaymentInfo')->where_in('PaymentId', $payment_ids)->order_by('CreatedOn', 'desc')->get()->result_array();
        return $details;
    }

    /**
     * Getting the unsettled orders of a customer.
     * @param $customer_id
     * @param $brand_code
     * @return mixed
     */
    public function get_unsettled_orders($customer_id, $brand_code)
    {
        $unsettled_orders = $this->db->query("EXEC " . SERVER_DB . ".dbo.[OrderListForGenerateInvoice] @CustomerCode='" . $customer_id . "', @BrandCode='" . $brand_code . "'")->result_array();
        return $unsettled_orders;
    }

    /**
     * Updating the DCN value in the TransactionInfo table.
     * @param $id
     * @param $dcn
     * @return mixed
     */
    public function update_dcn($id, $dcn)
    {
        $status = $this->db->where('Id', $id)->update('TransactionInfo', array('DCNo' => $dcn));
        return $status;
    }

    /**
     * Inserting a new entry row in the TransactionPaymentInfo table.
     * @param $data
     * @return mixed
     */
    public function save_transaction_payment_info($data)
    {
        $status = $this->db->insert('TransactionPaymentInfo', $data);
        return $status;
    }

    /**
     * Settle the order from the id of the TransactionPaymentInfo table.
     * @param $id
     * @return mixed
     */
    public function settle_order($id)
    {
        $transaction_payment_info_details = $this->db->select('TransactionId,PaymentId')->from('TransactionInfo')->where('Id', $id)->get()->row_array();

        $query = "EXEC " . SERVER_DB . ".dbo.[INVOICEANDSETTLEMENTFORMOBILEAPP] @TransactionId='" . $transaction_payment_info_details['TransactionId'] . "',@PAYMENTID='" . $transaction_payment_info_details['PaymentId'] . "'";

        $result = $this->db->query($query)->result_array();


        /*Updating the table with the invoice no if the order is settled.*/
        if ($result) {
            if ($result[0]['RESULT'] == 'SUCCESS') {
                $this->db->where(array('TransactionId' => $transaction_payment_info_details['TransactionId'], 'PaymentId' => $transaction_payment_info_details['PaymentId']))->update('TransactionPaymentInfo', array('InvoiceNo' => $result[0]['INVOICENO'], 'Remarks' => $result[0]['REMARKS'], 'SettlementProcedure' => 'Manually by ' . ADMIN_USERNAME));
            } else {
                $result = NULL;
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
    public function get_incomplete_payment_details($limit = FALSE, $offset = FALSE, $start_date, $end_date)
    {
        if ($start_date && $end_date) {

            /*$this->db->limit($limit,$offset)->select("TransactionInfo.TransactionDate,TransactionInfo.CustomerCode,TransactionInfo.EGRNNo,IsNull(TransactionInfo.DCNo,'N/A') as DCNo,TransactionInfo.PaymentId")->from('TransactionInfo')->where(array('TransactionInfo.TransactionDate>=' => $start_date, 'TransactionInfo.TransactionDate<=' => $end_date));
            $details = $this->db->where_not_in('PaymentId',$this->db->select('PaymentId')->from('TransactionInfo'))->order_by('TransactionInfo.TransactionDate','desc')->get()->result_array();*/
            $details = $this->db->query("select TransactionDate,CustomerCode,EGRNNo,IsNull(DCNo,'N/A') as DCNo,PaymentId from transactioninfo where paymentid not in (select paymentid from transactionpaymentinfo) and transactiondate >='" . $start_date . "' and transactiondate <='" . $end_date . "' order by transactiondate desc  offset " . $offset . " rows fetch next " . $limit . " ROWS ONLY")->result_array();


        } else {

            $details = NULL;
        }


        return $details;
    }


    /**
     *Loading the appspa campaign stats. (Campaign start date: March 9,2019. End date: 31st March, 2019)
     */
    public function load_appspa_campaign_stats()
    {

        $last_24hrs_registrations_with_coupon = $this->db->query("select count(*) as TotalRegistrationsInLast24HrsWhoGotTheCoupon from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001' and Promocode='APPSPA'")->row_array();
        $last_24hrs_registrations_without_coupon = $this->db->query("select COUNT(*) as TotalRegistrationsInLast24HrsWithoutCoupon  from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001' and ISNULL(Promocode,'') <> 'APPSPA'")->row_array();
        $last_24hrs_registrations = $this->db->query("select count(*) as TotalRegistrationsInLast24Hrs from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001' ")->row_array();
        $last_24hrs_pickups_with_coupon = $this->db->query("select count(*) as TotalPickupsByNewlyRegisteredUsersWhoAppliedCoupon from orders where user_id in (select id from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001') and status!='cancelled' and Coupon='APPSPA'")->row_array();
        $last_24hrs_pickups_without_coupon = $this->db->query("select count(*) as TotalPickupsByNewlyRegisteredUsersWithoutCoupon  from orders where user_id in (select id from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001') and status!='cancelled' AND ISNULL(coupon,'') <> 'APPSPA'")->row_array();
        $last_24hrs_pickups = $this->db->query("select count(*) as TotalPickupsByNewlyRegisteredUsers from orders where user_id in (select id from users where   date >= GETDATE() - 1 and Brandcode='PCT0000001') and status!='cancelled'")->row_array();


        $total_registrations_with_coupon = $this->db->query("select count(*) as TotalRegistrationsSinceCampaignWhoGotCoupon from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001' and Promocode='APPSPA'")->row_array();
        $total_registrations_without_coupon = $this->db->query("select count(*) as TotalRegistrationsSinceCampaignWithoutCoupon from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001' and ISNULL(Promocode,'') <> 'APPSPA'")->row_array();
        $total_registrations = $this->db->query("select count(*) as TotalRegistrationsSinceCampaign from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001'")->row_array();
        $total_pickups_with_appspa = $this->db->query("select count(*) as TotalPickupsSinceCampaignWhoAppliedCoupon from orders where user_id in (select id from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001') and status!='cancelled' and Coupon='APPSPA'")->row_array();
        $total_pickups_without_appspa = $this->db->query("select count(*) as TotalPickupsSinceCampaignWithoutCoupon from orders where user_id in (select id from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001') and status!='cancelled' and ISNULL(coupon,'') <> 'APPSPA'")->row_array();
        $total_pickups = $this->db->query("select count(*) as TotalPickupsSinceCampaign from orders where user_id in (select id from users where   date >= '2019-03-09 00:00:00' and Brandcode='PCT0000001') and status!='cancelled' ")->row_array();
        $result = array(

            'last_24hrs_registrations_with_coupon' => $last_24hrs_registrations_with_coupon,
            'last_24hrs_registrations_without_coupon' => $last_24hrs_registrations_without_coupon,
            'last_24hrs_registrations' => $last_24hrs_registrations,
            'last_24hrs_pickups_with_coupon' => $last_24hrs_pickups_with_coupon,
            'last_24hrs_pickups_without_coupon' => $last_24hrs_pickups_without_coupon,
            'last_24hrs_pickups' => $last_24hrs_pickups,

            'total_registrations_with_coupon' => $total_registrations_with_coupon,
            'total_registrations_without_coupon' => $total_registrations_without_coupon,
            'total_registrations' => $total_registrations,
            'total_pickups_with_appspa' => $total_pickups_with_appspa,
            'total_pickups_without_appspa' => $total_pickups_without_appspa,
            'total_pickups' => $total_pickups

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
    public function total_cycle($brand, $location, $device)
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

        if ($location != '') {

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
    public function update_user_details($user_id, $name, $mobile_number, $email)
    {

        /*Adding details to the log table.*/
        $existing_user_details = $this->db->select('name,email,mobile_number')->from('Users')->where('id', $user_id)->get()->row_array();


        /*Adding the details into log table*/

        $log_data = array('Date' => date('Y-m-d H:i:s'), 'UserId' => $user_id, 'OldName' => $existing_user_details['name'],
            'OldEmail' => $existing_user_details['email'],
            'MobileNumber' => $mobile_number,
            'NewName' => $name,
            'NewEmail' => $email,
            'UpdatedBy' => ADMIN_USERNAME
        );

        $log = $this->db->insert('UsersUpdateLog', $log_data);


        /*Updating the table with new data*/
        $this->db->set('name', $name);
        //$this->db->set('mobile_number', $mobile_number); No need as of now.
        $this->db->set('email', $email);
        $this->db->where('id', $user_id);
        $res = $this->db->update('Users');

        if ($res) {
            $updated_user = $this->db->select('name,mobile_number,id,email')->from('users')->where('id', $user_id)->get()->row_array();
            $result['status'] = TRUE;
            $result['user_details'] = $updated_user;
        } else {
            $result['status'] = FALSE;
        }


        return $result;
    }

    /**
     * Getting the QA Logs
     * @return mixed
     */
    public function get_qa_logs()
    {
        $result = $this->db->select('*')->from('QA_Logs')->order_by('CreatedDate', 'desc')->get()->result_array();
        return $result;
    }

    /**
     * Getting the QC Logs
     * @return mixed
     */
    public function get_qc_logs()
    {
        $result = $this->db->select('*')->from('QC_Logs')->order_by('CreatedDate', 'desc')->get()->result_array();
        return $result;
    }

    /**
     * Getting the QA Logs based on tag id
     * @return mixed
     */
    public function qa_search_tag_id($tag_id)
    {
        $result = $this->db->select('*')->from('QA_Logs')->where('TagNo', $tag_id)->order_by('CreatedDate', 'desc')->get()->result_array();
        return $result;
    }

    /**
     * Getting the QC Logs based on tag id
     * @return mixed
     */
    public function qc_search_tag_id($tag_id)
    {
        $result = $this->db->select('*')->from('QC_Logs')->where('TagNo', $tag_id)->order_by('CreatedDate', 'desc')->get()->result_array();
        return $result;
    }


    /**
     * Total QA_Logs count.
     * @return mixed
     */
    public function get_qa_logs_count()
    {
        $count = $this->db->count_all_results('QA_Logs');
        return $count;
    }

    /**
     * Total QA_Logs count.
     * @return mixed
     */
    public function get_qc_logs_count()
    {
        $count = $this->db->count_all_results('QC_Logs');
        return $count;
    }

    /**
     * Paginated result set for QA Logs
     * @param $per_page
     * @param $offset
     * @return mixed
     */
    public function get_qa_paginated_logs($per_page, $offset)
    {
        $result = $this->db->limit($per_page, $offset)->select('*')->from('QA_Logs')->order_by('CreatedDate', 'desc')->get()->result_array();

        return $result;
    }

    /**
     * Paginated result set for QC Logs
     * @param $per_page
     * @param $offset
     * @return mixed
     */
    public function get_qc_paginated_logs($per_page, $offset)
    {
        $result = $this->db->limit($per_page, $offset)->select('*')->from('QC_Logs')->order_by('CreatedDate', 'desc')->get()->result_array();

        return $result;
    }


    /**
     * Returns all the registered qa reason in the DB.
     * @return mixed
     */
    public function get_qa_reasons()
    {
        $qa_reasons = $this->db->select('*')->from('qa_reasons')->order_by('Id', 'asc')->get()->result_array();
        return $qa_reasons;
    }

    /**
     * Returns all the registered qa reason in the DB. Right now QC reasons is same as QA reasons
     * @return mixed
     */
    public function get_qc_reasons()
    {
        $qa_reasons = $this->db->select('*')->from('qa_reasons')->order_by('Id', 'asc')->get()->result_array();
        return $qa_reasons;
    }


    /*Saving qa_reasons*/
    /**
     * Saving a qa reason
     * @param $qa_reason
     * @return mixed
     */
    public function save_qa_reason($qa_reason)
    {

        $status = $this->db->insert('qa_reasons', $qa_reason);
        $id = $this->db->insert_id();
        $saved_qa_reason = $this->db->select('*')->from('qa_reasons')->where('Id', $id)->get()->result_array();
        return $saved_qa_reason;
    }

    /**
     * Updating a qa reason details from the console.
     * @param $qa_reason
     * @param $qa_reason_id
     * @return mixed
     */
    public function update_qa_reason($qa_reason, $qa_reason_id)
    {

        $this->db->where('Id', $qa_reason_id);
        $updated_status = $this->db->update('qa_reasons', $qa_reason);
        $updated_qa_reason = $this->db->select('*')->from('qa_reasons')->where('Id', $qa_reason_id)->get()->result_array();
        return $updated_qa_reason;
    }

    /**
     * Deleting a qa reason from the console.
     * @param $qa_reasons
     * @return mixed
     */
    public function delete_qa_reasons($qa_reasons)
    {

        $delete_status = $this->db->where_in('Id', $qa_reasons)->delete('qa_reasons');
        return $delete_status;

    }

    /**
     *Getting the data for genraring pie charts based on reasons.
     */
    public function get_qa_data_for_chart($reason)
    {

        $count = $this->db->like('Reason', $reason)->from('QA_Logs')->count_all_results();

        return $count;

    }


    /**
     * Getting the logs data for the excel report generation
     * @param $start_datetime datetime exmp: 2019-07-05 05:00:00
     * @param $end_datetime datetime exmp: 2019-07-15 15:30:00
     */
    public function get_qa_logs_for_generate_report($start_datetime, $end_datetime)
    {

        if ($start_datetime && $end_datetime)
            $logs = $this->db->select('*')->from('QA_Logs')->where(array('CreatedDate>=' => $start_datetime, 'CreatedDate<=' => $end_datetime))->get()->result_array();
        else
            $logs = $this->db->select('*')->from('QA_Logs')->get()->result_array();
        return $logs;
    }

    /**
     * Getting all the saved images attached to a qc row id.
     * @param $qc_log_id
     * @return mixed
     */
    public function get_qc_log_images($qc_log_id)
    {
        $images = $this->db->select('*')->from('QC_Log_Images')->where('QC_Log_ID', $qc_log_id)->get()->result_array();
        return $images;
    }


    public function test()
    {
        $query = $this->db->query("select customer_id,count(customer_id) from V_Order where customer_id not in (select customer_id from V_Order where cutomerorderstatus='Cancelled' or Status='Cancelled')  group by customer_id having count(customer_id)>1")->result_array();
        echo sizeof($query);
    }

    //Getting a log data based on tag no.
    public function get_log_data($tag_no)
    {

        $log = $this->db->select('*')->from('QC_Logs')->where('TagNo', $tag_no)->get()->row_array();
        return $log;
    }

    //Method to get a cipher of a customer.
    public function get_cipher_code($customer_id)
    {
        $code = $this->db->select('UniqueCode')->from('QC_Customer_Details')->where('CustomerCode', $customer_id)->get()->row_array();
        return $code;
    }

    //Loading the captured images for a particular log ID.
    public function get_log_images($qc_log_id)
    {
        $images = $this->db->select('*')->from('QC_Log_Images')->where('QC_Log_Id', $qc_log_id)->get()->result_array();
        return $images;
    }

    /**
     * Getting the garment details from the QC Master view
     * @param $tag_no -- Unique tag Number
     * @return mixed
     */
    public function get_garment_details($tag_no)
    {
        $result = $this->db->select('*')->from('V_QC_Master')->where('TagNo', $tag_no)->get()->row_array();
        return $result;
    }


    /**
     * Getting the transaction details from the TransactionInfo table.
     * @return mixed
     */
    public function get_transaction_details($start_date = FALSE, $end_date = FALSE)
    {


        $this->db->select('TI.TransactionDate,TI.PaymentId,TI.CustomerCode,TI.EGRNNo,TI.PaymentSource,TI.GarmentsCount,TI.TotalPayableAmount,TI.TransactionId');
        $this->db->select("(CASE WHEN TI.DCNo IS NULL THEN 'No DC' ELSE TI.DCNo END) as DCNo");
        $this->db->select("(CASE WHEN TI.ConsoleVerify IS NULL THEN 'Not verified' ELSE 'Verified' END) as ConsoleVerify");
        $this->db->select("(CASE WHEN TPI.PaymentMode IS NULL THEN 'Did not receive the response from the Mobikwik on time.' ELSE TPI.PaymentMode END) as PaymentMode");
        $this->db->select("(CASE WHEN TPI.PaymentGatewayStatus IS NULL THEN 'Did not receive the response from the Mobikwik on time.' ELSE TPI.PaymentGatewayStatus END) as PaymentGatewayStatus");
        $this->db->select("(CASE WHEN TPI.PaymentGatewayStatusDescription IS NULL THEN 'Did not receive the response from the Mobikwik on time.' ELSE TPI.PaymentGatewayStatusDescription END) as PaymentGatewayStatusDescription");
        $this->db->select("(CASE WHEN TPI.InvoiceNo IS NULL THEN 'N/A' WHEN TPI.InvoiceNo ='' THEN 'N/A' ELSE TPI.InvoiceNo END) as InvoiceNo");
        $this->db->select("(CASE WHEN TPI.PgTransId IS NULL THEN 'Did not receive the response from the Mobikwik on time.' ELSE TPI.PgTransId END) as PgTransId");
        $this->db->from('TransactionInfoLogs TI')->join('TransactionPaymentInfoLogs TPI', 'TI.TransactionId=TPI.TransactionId AND (TI.PaymentId=TPI.PaymentId)', 'left');

        $this->db->group_start();
        $this->db->where('TPI.InvoiceNo', NULL);
        $this->db->or_where('TPI.InvoiceNo=','');
        $this->db->group_end();

        $this->db->where('TI.ConsoleVerify',NULL);

        if ($start_date) {
            $this->db->where(array("TI.TransactionDate>=" => $start_date, "TI.TransactionDate<=" => $end_date));
        } else {
            //Default. Last 100 details are needed to be loaded.
            $this->db->limit(100)->order_by('TI.TransactionDate', 'desc');
        }

        $details = $this->db->get()->result_array();

        return $details;
    }

    /**
     * Verifying the transaction from the Console.
     * @param $transaction_id
     * @param $payment_id
     * @param $settle -- 1 if the transaction needs to be manually checked and settled. 0 for no need of a settlement.
     * @param $verification_date -- date of the verification
     * @return mixed
     */
    public function verify_transaction($transaction_id, $payment_id, $settle,$verification_date)
    {
        $data = array('ConsoleVerify' => 1, 'ManualVerify' => $settle,'VerifiedBy'=> ADMIN_USERNAME,'VerificationDate'=>$verification_date);
        $status = $this->db->where(array('TransactionId' => $transaction_id, 'PaymentId' => $payment_id))->update('TransactionInfoLogs', $data);
        return $status;
    }

    /**
     * Getting the verify status from the TID and PID.
     * @param $payment_id
     * @param $transaction_id
     * @return mixed
     */
    public function get_transaction_verify_status($payment_id,$transaction_id){
        $status=$this->db->select('ConsoleVerify')->where(array('TransactionId' => $transaction_id, 'PaymentId' => $payment_id))->from('TransactionInfoLogs')->get()->row_array();
        return $status;
    }
    /**
     * Getting rate details of fabhome
     */
    public function get_fabhome_rate_data($service_type)
    {
       $data = $this->db->select('*')->from('Fabhome_Rate_Master')->where('type', $service_type)->order_by('Id','desc')->get()->result_array();
       $active_data = array();
       $j=0;
       for($i=0;$i<sizeof($data);$i++){
           if($data[$i]['expiry'] >= date('Y-m-d')){
                $active_data[$j] = $data[$i];
                $j++;
           }
       }
       return $active_data; 
    }
    /**
     * Saving rate details to Fabhome_Rate_Master
     */
    public function save_rate_details($service_type,$service,$category,$uom,$rate_per_uom,$discount_perc,$discount,$tax,$expiry)
    {
        if($service_type == "Deep Cleaning")
            $unique_code = "DEP00";
        else if($service_type == "Office Cleaning")
            $unique_code = "OFC00";
        else
            $unique_code = "HOM00";
        $date = date('Y-m-d H:i:s');
        $insert_data = array(
            'date' => $date,
            'type' => $service_type,
            'service' => $service,
            'category' => $category,
            'input_uom' => $uom,
            'rate_per_uom' => $rate_per_uom,
            'discount_percentage' => $discount_perc,
            'discount_value' => $discount,
            'tax_percentage' => $tax,
            'expiry' => $expiry,
            'active' => 1
        );
        $status = $this->db->insert('Fabhome_Rate_Master',$insert_data);
        $id=$this->db->insert_id(); 
        $this->db->set('SKU', $unique_code.$id);
        $this->db->where('Id', $id);
        $res = $this->db->update('Fabhome_Rate_Master');

        if($status)
            return TRUE;
        else
            return FALSE;
    }
    public function get_rate_details_from_id($id)
    {
        $data = $this->db->select('*')->from('Fabhome_Rate_Master')->where('Id',$id)->get()->result_array();
        return $data;
    }
    public function deactivate_fabhome_rate($id)
    {
        $this->db->set('active', 0);
        $this->db->where('Id', $id);
        $res = $this->db->update('Fabhome_Rate_Master');
        return $res;
    }
      public function get_fabhome_cart_data()
    {
        //$res = $this->db->select('*')->from('fabhome_cart_dtls')->order_by('cart_id','desc')->get()->result_array();
       // return $res;
         $res = $this->db->select("cart.*,users.name,users.mobile_number,CAST( cart.pick_up_date AS Date ) as pick_up_date")
        ->from('fabhome_cart_dtls cart')
            ->join('Users users', 'users.customer_id =cart.customer_id', 'left')
            ->order_by('cart.cart_id','desc')->limit('1000', '0')->get()->result_array();
        return $res;
    }
    public function get_fabhome_cart_items($id)
    {
        $res = $this->db->select('*')->from('fabhome_cart_itm_dtls')->where('cart_id',$id)->get()->result_array();
        return $res;
    }
     public function get_customer_address($id)
    {
        $res = $this->db->select('*')->from('fabhome_cart_dtls')->where('cart_id',$id)->get()->result_array();
        return $res;
    }
    public function update_order_status($id,$status,$user)
    {
        $this->db->set(array('status'=> $status,'updated_by' => $user,'updtd_date' => date('Y-m-d H:i:s')));
        $this->db->where('cart_id', $id);
        $res = $this->db->update('fabhome_cart_dtls');
        return $res;
    }
    public function activate_fabhome_rate($id)
    {
        $this->db->set('active', 1);
        $this->db->where('Id', $id);
        $res = $this->db->update('Fabhome_Rate_Master');
        return $res;
    }
    public function get_fabhome_banner()
    {
        $query = $this->db->select('*')->from('EssentialsStorePopupDetails')->where('category' ,'fabhome')->get()->result_array();
        return $query;
    }
    public function add_fbhmbanner($fbhm_banner)
    {
        $array=array('title' => 'Nill','url' => $fbhm_banner,'category' => 'fabhome');
        $res = $this->db->insert('EssentialsStorePopupDetails',$array);
        return $res;
    }
    public function delete_fbhmbanner($id)
    {
        $status = $this->db->where_in('id', $id)->delete('EssentialsStorePopupDetails');
        return $status;
    }
    public function check_fbhmbanner()
    {
        $query = $this->db->select('*')->from('EssentialsStorePopupDetails')->where('category' ,'fabhome')->get()->num_rows();
        return $query;
    }
    public function get_users_mobileno($device)
    {
      $data = $this->db->select('mobile_number')->from('Users_Auth_Details')->where('device' , $device)->get()->result_array();
        return $data;
    }
    public function check_sign_up_source_valid($mobile,$device)
    {
      $user_data= "";
      $is_valid=0;
      $user_data = $this->db->select('id')->from('Users')->where(array('mobile_number' => $mobile,'sign_up_source' =>$device))->get()->result_array();
      if(sizeof($user_data) > 0)
            $is_valid = 1;
      return $is_valid;
    }
    public function get_banner($popup_id,$category)
    {
        $query = $this->db->select('*')->from('EssentialsStorePopupDetails')->where(array('category' => $category,'id' => $popup_id ))->get()->result_array();
        return $query;
    }
         public function check_payment_status($cart_id)
    {
        $newTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -10 minutes"));
        $data = $this->db->select('*')->from('fabhome_payment_summary_details')->where(array('cart_id' => $cart_id,'response_status' => NULL,'cretn_date<' => $newTime))->get()->result_array();
        return $data;
    }
    public function get_fbh_paytm_payment_status_single($payment_id)
    {
        $query=$this->db->select('*')->from('fabhome_payment_summary_details')->where('payment_id', $payment_id)->get()->result_array();
        return $query;
    } 
    public function update_fbh_payment_summary($payment_id,$status)
    {
        $date = date('d-M-Y H:i:s');
        $data=array('response_status' => $status,'updtd_date' => $date);
        $result=$this->db->where('payment_id',$payment_id)->update('fabhome_payment_summary_details',$data);
        return $result;
    }
    public function get_fbh_checksum_details($bdy,$PAYTM_SECRET_KEY)
    {
       $this->load->library('Paytm');
       $checksum =  $this->paytm->getChecksumFromString($bdy,$PAYTM_SECRET_KEY);
       return $checksum;
        
    }
    public function update_fabhome_payment_status($id,$user_name)
    {
        $date = date('d-M-Y H:i:s');
        $data=array('cart_status' => 'Paid','cart_status_updated_at' => $date,'cart_status_updated_by' => $user_name);
        $result=$this->db->where('cart_id',$id)->update('fabhome_cart_dtls',$data);
        return $result;
    }
     /**
     * Function to fetch fabricspa gcmid
     */
    public function get_gcmid($mob_no,$device)
    {
        if($device == 'android')
            $this->db->select('fabricspa_android_gcmid as gcmid');
        else
            $this->db->select('fabricspa_ios_gcmid as gcmid');
        $data = $this->db->from('Users')->where('mobile_number',$mob_no)->get()->result_array();
        return $data[0]['gcmid'];
    }
    /**
     * Getting user location from mobile number
     */
    public function get_location_from_mobileno($mob_no)
    {
        $data = $this->db->select('location')->from('Users')->where('mobile_number',$mob_no)->get()->result_array();
        return $data[0]['location'];
    }
    public function save_deleted_users_details($mobile_no,$device,$location,$delete_id)
    {
        
         $today = date('Y-m-d') ;
        $data = $this->db->select('Id')->from('Fabricspa_Uninstalled_Users')->where(array('mobile_number' => $mobile_no,'dt>=' => $today))->get()->result_array();
        $is_exist = 0;
        $status = 0;
        if(sizeof($data) > 0){
                $is_exist = 1;
        }else{
            $is_exist = 0; 
        }   
        if($is_exist == 0){
            $insert_data = array(
                'device' => $device,
                'mobile_number' => $mobile_no,
                'location' => $location,
                'date' => date('Y-m-d H:i:s'),
                'delete_id' => $delete_id,
                'dt' => date('Y-m-d')
            );
            $status = $this->db->insert('Fabricspa_Uninstalled_Users',$insert_data);
        }
        return $status; 
    }
    public function get_delete_id()
    {
        $data = $this->db->select('delete_id')->from('Fabricspa_Uninstalled_Users')->order_by('Id','desc')->limit(1)->get()->result_array();
        if($data[0]['delete_id'] != "")
            return $data[0]['delete_id'];
        else
            return 1;
    }
    /**
     * Fetching coupons that will expire after 2 days
     */
    public function get_expiring_coupons($expiry_date)
    {
        $discounts = $this->db->select('Id,PromoCode,DiscountCode,AppRemarks,ExpiryDate,start_date,total_users,used_count,count')->from('Coupons')->where(array('ExpiryDate' => $expiry_date,'status_flg' => 'A'))->get()->result_array();
        return $discounts;
    }
     /**
     * check the same banner is added for other cities in the same state
     */
    public function get_duplicate_banners($state,$city,$url,$category)
    {
        $multiple_where = array('state' => $state,'city !=' => NULL, 'city !=' => $city,'category' => $category,'url' => $url);
        $data = $this->db->select('id')->from('EssentialsStorePopupDetails')->where($multiple_where)->get()->result_array();
        return $data;
    }
}