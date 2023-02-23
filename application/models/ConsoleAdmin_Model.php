<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * (c) Jyothy Laboratories Ltd
 * Created by Manju Vibin
 * Date: 11/10/2022
 * Time: 10:36 AM
 */
class ConsoleAdmin_Model extends CI_Model
{
	/**
	 *Constructor for the console admin model
	 */
	function __construct()
	{
		parent::__construct();
		// loading database
		$this->load->database();
	}

	/* get all fabhome_cart_dtls data*/
	public function get_fabhome_cart_data()
	{
		$res = $this->db->select("cart.*,users.name,users.mobile_number,CAST( cart.pick_up_date AS Date ) as pick_up_date")
		->from('fabhome_cart_dtls cart')
			->join('Users users', 'users.customer_id =cart.customer_id', 'left')
			->order_by('cart.cart_id','desc')->limit('1000', '0')->get()->result_array();
		return $res;
	}


/* update status of fabhome_cart_dtls*/
	public function update_order_status($id,$status,$user)
    {
        $this->db->set(array('status'=> $status,'updated_by' => $user,'updtd_date' => date('Y-m-d H:i:s')));
        $this->db->where('cart_id', $id);
        $res = $this->db->update('fabhome_cart_dtls');
        return $res;
    }
    
    /* get all data from fabhome_cart_itm_dtls wrt cart id*/
	public function get_fabhome_cart_items($id)
	{
		$res = $this->db->select('*')->from('fabhome_cart_itm_dtls')->where('cart_id',$id)->get()->result_array();
		return $res;
	}
	/*  get all data from fabhome_cart_dtls wrt cart id */
	public function get_fabhome_cart($id)
	{
		$res = $this->db->select('*')->from('fabhome_cart_dtls')->where('cart_id',$id)->get()->result_array();
		return $res;
	}
	/* get all data from fabhome_transaction_payment_info wrt cart id */
	public function get_fabhome_cart_payment_details($id)
	{
		$res = $this->db->select('payment_info.payment_id ,payment_info.payment_mode,payment_info.payment_gateway_status_description ')->from('fabhome_transaction_info transaction_info')
			->join('fabhome_transaction_payment_info payment_info', 'payment_info.payment_id =transaction_info.payment_id', 'inner')
			->where('transaction_info.cart_id',$id)->get()->result_array();
		//print_r( $this->db->last_query());exit;
		return $res;
	}

	/* get all data from EssentialsStorePopupDetails */
	public function get_essentialpopups()
	{
		$query = $this->db->select('*')->from('EssentialsStorePopupDetails')->where('category' ,'essentialpopup')->get()->result_array();
		return $query;
	}
	/*  get all states from SP */
	public function get_states_sp()
	{
		$query =  $this->db->query('EXEC USP_GET_STATES_CITIES')->result_array();
		return $query;
	}
	/*  get all cities from SP passing statecode*/
	public function get_state_cities_sp($statecode)
	{
		$query =  $this->db->query("EXEC USP_GET_STATES_CITIES @statecode='".$statecode."'")->result_array();
		return $query;
	}

	/*  get all coupons*/
	public function get_coupons()
	{
		$res = $this->db->select("coupon.Id,coupon.state,coupon.city,coupon.PromoCode,coupon.DiscountCode,
		coupon.AppRemarks,CAST( coupon.ExpiryDate AS Date ) as ExpiryDate,coupon.status_flg,coupon.updated_by,coupon.created_by,coupon.updated_date,coupon.created_date,coupon.total_users,coupon.start_date,coupon.count,coupon.used_count,coupon.Campaign")
			->from('Coupons coupon')
			->order_by('coupon.Id','desc')->limit('1000', '0')->get()->result_array();

		return $res;
	}

    /* change_coupons_status */
	public function change_coupons_status($id)
	{
		$query = $this->db->select('*')->from('Coupons')->where('Id' ,$id)->get()->result_array();
		$date = date('d-M-Y H:i:s');

		if($query[0]['Campaign'] != ""){
			$coupon_data = $this->db->select('*')->from('Coupons')->where('Campaign',$query[0]['Campaign'])->get()->result_array();

			for($i=0;$i<sizeof($coupon_data);$i++){
				$status='A';
				if($coupon_data[$i]['status_flg']=='A')
					$status='I';
				$data = array('status_flg' => $status,'updated_date' => $date,'updated_by' => $_SESSION['username']);
				$result = $this->db->where('Id',$coupon_data[$i]['Id'])->update('Coupons', $data);
				
			}
		}else{
			$status='A';
			if($query[0]['status_flg']=='A')
				$status='I';
			$data = array('status_flg' => $status,'updated_date' => $date,'updated_by' => $_SESSION['username']);

			$result = $this->db->where('Id',$id)->update('Coupons', $data);
		}
		return $result;
	}

	/* add_coupon */
	public function add_coupon($state, $city ,$promo_code, $discount_code, $app_remarks, $expiry_date,$total,$start_date,$validity,$campaign)
	{
		$date = date('d-M-Y H:i:s');
		$array = array('state' => $state, 'city' => $city, 'PromoCode' => $promo_code, 'DiscountCode' => $discount_code, 'AppRemarks' => $app_remarks, 'ExpiryDate' => $expiry_date, 'created_date' => $date, 'created_by' => $_SESSION['username'], 'total_users' => $total,'start_date' =>$start_date,'count' => $validity,'used_count' => 0,'campaign' => $campaign);
		$res = $this->db->insert('Coupons', $array);
		return $this->db->insert_id();
	}


	/* add_coupon_users */
	public function add_coupon_users($mob_no,$id)
	{
		$insert_data = array(
			'coupon_id' => $id,
			'mobile_number' => $mob_no
		);
		$status = $this->db->insert('Coupon_Users',$insert_data);
		return $status;
	}
	/* get_coupon_details */
	public function get_coupon_details($id)
	{
		$data = $this->db->select('*,CAST( ExpiryDate AS Date ) as ExpiryDate')->from('Coupons')->where('Id',$id)->get()->result_array();
		return $data;
	}

	/* update_coupon */
	public function update_coupon($id,$promo_code, $discount_code, $app_remarks, $expiry_date,$start_date,$count)
	{
		$date = date('d-M-Y H:i:s');
		$expiry_date = date('Y-m-d',strtotime($expiry_date))." 00:00:00.000";
		$update_data =  $array = array('PromoCode' => $promo_code, 'DiscountCode' => $discount_code, 'AppRemarks' => $app_remarks, 'ExpiryDate' => $expiry_date, 'updated_date' => $date, 'updated_by' => $_SESSION['username'],'start_date' => $start_date,'count' => $count);
		$status = $this->db->where('Id',$id)->update('Coupons',$update_data);
			return $status;
	}
	/* update_coupon_users */
	public function update_coupon_users($id,$mob_no)
	{
		$insert_data = array(
			'coupon_id' => $id,
			'mobile_number' => $mob_no
		);
		$status = $this->db->insert('Coupon_Users',$insert_data);
		return $status;
	}
	/**
	 * Getting rate details of fabhome
	 */
	public function get_fabhome_rate_data($service_type)
	{
		$data = $this->db->select('*')->from('Fabhome_Rate_Master')->where('type', $service_type)->order_by('Id','desc')->get()->result_array();
		$extra_data = $this->db->select('*')->from('Fabhome_Rate_Master')->where('type', str_replace(' ','',strtolower($service_type)))->order_by('Id','desc')->get()->result_array();
				$active_data = array();
		if(sizeof($data) > 0){
			$size = sizeof($data);
			for($k=0;$k<sizeof($extra_data);$k++){
				$data[$size] = $extra_data[$k];
				$size++;
			}

		}else{
			$active_data = $extra_data;
		}

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
	 * get_rate_details_from_id
	 */
	public function get_rate_details_from_id($id)
	{
		$data = $this->db->select('*')->from('Fabhome_Rate_Master')->where('Id',$id)->get()->result_array();
		return $data;
	}


	/**
	 * deactivate_fabhome_rate
	 */
	public function deactivate_fabhome_rate($id)
	{

		$date = date('d-M-Y H:i:s');
		$data = array('active' => 0,'updated_date' => $date,'updated_by' => $_SESSION['username']);
		$res = $this->db->where('Id',$id)->update('Fabhome_Rate_Master', $data);
		return $res;
	}


	/**
	 * activate_fabhome_rate
	 */
	public function activate_fabhome_rate($id)
	{
		$old_data = $this->db->select('*')->from('Fabhome_Rate_Master')->where('Id',$id)->get()->result_array();
		$existing_data = $this->db->select('*')->from('Fabhome_Rate_Master')->where(array('type'=>$old_data[0]['type'],'service' =>$old_data[0]['service'],'category' => $old_data[0]['category'],'active' => 1,'Id !=' => $id))->get()->result_array();
		$is_existing = 0;
		if(sizeof($existing_data) > 0){
			for($i=0;$i<sizeof($existing_data);$i++){
				if($old_data[0]['start_date'] <= $existing_data[$i]['start_date'] && $old_data[0]['expiry'] >= $existing_data[$i]['start_date'])
					$is_existing++;
				else if($old_data[0]['start_date'] >= $existing_data[$i]['start_date'] && $old_data[0]['expiry'] <= $existing_data[$i]['expiry'])
					$is_existing++;
				else if($old_data[0]['start_date'] <= $existing_data[$i]['expiry'] && $old_data[0]['expiry'] >= $existing_data[$i]['expiry'])
					$is_existing++;
			}
		}
		if($is_existing == 0){
			$date = date('d-M-Y H:i:s');
			$data = array('active' => 1,'updated_date' => $date,'updated_by' => $_SESSION['username']);
			$res = $this->db->where('Id',$id)->update('Fabhome_Rate_Master', $data);
			if($res)
				$status = 'Successfully Updated';
			else
				$status = 'Updation Failed';
		}else{
			$status = 'An active rate is already present';
		}
		return $status;
	}

	/**
	 * Saving rate details to Fabhome_Rate_Master
	 */
	public function save_rate_details($service_type,$service,$category,$uom,$rate_per_uom,$discount_perc,$discount,$tax,$expiry,$start_date)
	{

		// if($service_type == "deepcleaning"){
		// 	$unique_code = "DEP00";
		// 	$service_type = "Deep Cleaning";
		// }else if($service_type == "officecleaning"){
		// 	$unique_code = "OFC00";
		// 	$service_type = "Office Cleaning";
		// }else{
		// 	$unique_code = "HOM00";
		// 	$service_type = "Home Cleaning";
		// }

		$date1 = date('d-M-Y H:i:s');
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
			'active' => 1,
			'created_date' => $date1, 
			'created_by' => $_SESSION['username'],
			'start_date' => $start_date
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
	public function get_coupon_users($id)
	{
		$data = $this->db->select('mobile_number')->from('Coupon_Users')->where('coupon_id',$id)->get()->result_array();
		return $data;
	}
	/**
	 * Saving fabhome new services
	 */
	public function add_fabhome_services($services,$type,$service,$category,$uom)
	{
		$time = date('d-M-Y H:i:s');
		if($services != ""){
			for($i=0;$i<sizeof($services);$i++){
				$insert_data = array(
					'service_type' => str_replace(' ','',strtolower($services[$i][0])),
					'service' => $services[$i][1],
					'category' => $services[$i][2],
					'uom' => $services[$i][3],
					'created_by' => $_SESSION['username'],
					'created_date' => $time
				);
				$status = $this->db->insert('Fabhome_Services',$insert_data);
			}

		}else{
			$insert_data = array(
				'service_type' => str_replace(' ','',strtolower($type)),
				'service' => $service,
				'category' => $category,
				'uom' => $uom,
				'created_by' => $_SESSION['username'],
				'created_date' => $time
			);
			$status = $this->db->insert('Fabhome_Services',$insert_data);
		}
		if($status)
			return TRUE;
		else
			return FALSE;
	}
	
	
	/**
	 * Function to get fabhome avialable services
	 */
	public function get_fabhome_services()
	{
		$data = $this->db->select('*')->from('Fabhome_Services')->order_by('Id','desc')->limit('1000')->get()->result_array();
		return $data;
	}
	/**
	 * Function to get service details by passing Id
	 */
	public function get_service_details_from_id($id)
	{
		$data = $this->db->select('*')->from('Fabhome_Services')->where('Id',$id)->get()->result_array();
		return $data;
	}
	/**
	 * Function for updating service details
	 */
	public function update_fabhome_service($service_type,$service,$category,$uom,$id)
	{
		$update_data= array(
			'service_type' => str_replace(' ','',strtolower($service_type)),
			'service' => $service,
			'category' => $category,
			'UOM' => $uom,
			'updated_by' => $_SESSION['username'],
			'updated_date' => date('d-M-Y H:i:s')
		);
		$this->db->where('Id',$id);
		$status = $this->db->update('Fabhome_Services',$update_data);
		return $status;
	}
	/**
	 * Function to remove fabhome services
	 */
	public function delete_fabhome_service($id)
	{
		$status = $this->db->where_in('Id',$id)->delete('Fabhome_Services');
		return $status;
	}
	/**
	 * Function to get services by passing service_type
	 */
	public function get_fabhome_services_fromservicetype($type)
	{
		// $data = $this->db->select('service')->from('Fabhome_Services')->where('service_type',$type)->get()->result_array();
		$data = $this->db->select('service')->from('Fabhome_Services')->where('service_type',$type)->get()->result_array();
		if(sizeof($data) > 0 ){
			$size = sizeof($data);
			$extra_data = $this->db->select('service')->from('Fabhome_Services')->where('service_type',str_replace(' ', '', trim($type)))->get()->result_array();
			if(sizeof($extra_data) > 0){
				for($i=0;$i<sizeof($extra_data);$i++){
					$data[$size] = $extra_data[$i];
					$size ++ ;
				}
			}
		}else{
			$data = $this->db->select('service')->from('Fabhome_Services')->where('service_type',str_replace(' ', '', trim($type)))->get()->result_array();
			
		}
		return $data;
	}
	/**
	 * Function to fetch categories avaialble by passing service typr and service

	 */
	public function get_fabhome_category_from_service($type,$service)
	{
		$data = $this->db->select('category')->from('Fabhome_Services')->where(array('service_type' => $type,'service' => $service))->get()->result_array();
		return $data;
	}
	/**
	 * Function to fetch input uom avaialble by passing service type,service and category
	 */
	public function get_fabhome_uom_from_category($type,$service,$category)
	{
		$data = $this->db->select('uom')->from('Fabhome_Services')->where(array('service_type' => $type,'service' => $service,'category' => $category))->get()->result_array();
		return $data;	
	}
	/**
	 * Function to fetch active rates 
	 */
	public  function get_rate_data($id,$service_type,$service,$category)
	{
		$data = $this->db->select('*')->from('Fabhome_Rate_Master')->where(array('type'=>$service_type,'service' =>$service,'category' => $category,'active' => 1,'Id !=' => $id))->get()->result_array();
		return $data;
	}
	/**
	 * Function to fetch android or ios users mobile number
	 */
	public function get_users_mobileno($device)
    {
      $data = $this->db->select('mobile_number')->from('Users_Auth_Details')->where('device' , $device)->get()->result_array();
       return $data;
    }
    /**
	  * Function to check whether sign_up_source is valid
	 */
    public function check_sign_up_source_valid($mobile,$device)
    {
      $user_data= "";
      $is_valid=0;
      $user_data = $this->db->select('id')->from('Users')->where(array('mobile_number' => $mobile,'sign_up_source' =>$device))->get()->result_array();
      if(sizeof($user_data) > 0)
            $is_valid = 1;
      return $is_valid;
    }
    /**
	 * Getting gcmid of a user by passing mobile number
	 */
	public function get_gcmid_from_mobileno($mobile_no)
	{
		$data = $this->db->select('sign_up_source,fabricspa_android_gcmid,fabricspa_ios_gcmid')->from('Users')->where('mobile_number',$mobile_no)->get()->result_array();
		if(strtolower($data[0]['sign_up_source']) == 'android'){
			/*Selecting ID from database*/
			$this->db->select('id');
			/*Getting data from database*/
			$query = $this->db->get_where('Users_Auth_Details', array('mobile_number' => $mobile_no,'device' => 'android'
			));
			/*If user exists*/
			if ($query->num_rows() > 0) 		
				$gcmid =  $data[0]['fabricspa_android_gcmid'];
			else
				$gcmid = "";
		}else if(strtolower($data[0]['sign_up_source']) == 'ios'){
			/*Selecting ID from database*/
			$this->db->select('id');
			/*Getting data from database*/
			$query = $this->db->get_where('Users_Auth_Details', array('mobile_number' => $mobile_no,'device' => 'ios'
			));
			/*If user exists*/
			if ($query->num_rows() > 0) 		
				$gcmid =  $data[0]['fabricspa_ios_gcmid'];
			else
				$gcmid = "";
		}else{
			$gcmid = "";
		}
		return $gcmid;
	}
	/**
	 * Getting users mobile number in a specific city
	 */
	public function get_users_via_location($city)
	{
		$data = $this->db->select('mobile_number')->from('Users')->where(array('location' => $city,'sign_up_source !=' => 'web'))->get()->result_array();
		return $data;
	}
	/**
	 * Getting user location from mobile number
	 */
	public function get_location_from_mobileno($mob_no)
	{
		$data = $this->db->select('sign_up_source,location')->from('Users')->where('mobile_number',$mob_no)->get()->result_array();
		return $data;
	}
	/** 
	 * Getting users's mobile number 
	 */
	public function get_users_via_location_and_device($city,$device)
	{
		$data = $this->db->select('mobile_number')->from('Users')->where(array('location' => $city,'sign_up_source ' => $device))->get()->result_array();
		return $data;
	}
	/**
	 * Function to fetch fabricspa gcmid
	 */
	public function get_gcmids($mob_no,$device)
	{
		if($device == 'android')
			$this->db->select('fabricspa_android_gcmid as gcmid');
		else
			$this->db->select('fabricspa_ios_gcmid as gcmid');
		$data = $this->db->from('Users')->where('mobile_number',$mob_no)->get()->result_array();
		return $data[0]['gcmid'];
	}
	public function check_payment_status($cart_id)
    {
         $newTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -15 minutes"));
        $data = $this->db->select('*')->from('fabhome_payment_summary_details')->where('cart_id' , $cart_id)->get()->result_array();
				if($data[0]['response_status'] == NULL || $data[0]['response_status'] == "" && $data[0]['cretn_date'] < $newTime )
        	return $data;
				else
					return false;
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
    public function get_all_orders_bycart_id($cart_id)
    {
        $data = $this->db
            ->select("*")
            ->from("fabhome_cart_dtls")
            ->where("cart_id", $cart_id)
            ->order_by("cart_id", "desc")
            ->get()
            ->result_array();
        return $data;
    }
     /**
     * Getting customer details via customer code
     */
    public function get_customer_details_from_customerid($customer_id)
    {
        $data = $this->db->select('name,mobile_number,email')->from('Users')->where('customer_id',$customer_id)->get()->result_array();
        return $data;
    }
    /**
     *Function for  sending payment success mail ,sms to customer  and mail to jfsl
     */
    public function send_fabhome_payment_success_mail($customer_id,$name,$mobile_number,$order_id,$service_type,$payment_amount,$email,$payment_mode)
    {
        $order_id = ltrim($order_id, '#');
        $service_type = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $service_type));
        if (CURRENT_ENVIRONMENT == 'Cloud' || CURRENT_ENVIRONMENT == 'Live') {
            $db = 'Alert_Engine';
        } else {
            $db = 'Alert_Engine_UAT';
        }
        $query1 = "Exec ".$db. "..ALERT_PROCESS @ALERT_CODE = 'JFSL_FABHOME_PAYMENT_SUCCESS_EMAIL',@EMAIL_ID = 'jfsl.mdm@jyothy.com',@MOBILE_NO=null,@SUBJECT = 'FabHome - Payment Successful' ,@DISPATCH_FLAG = 'OFF',@EMAIL_SENDER_ADD='no-reply@jfslcloud.in' ,@SMS_SENDER_ADD = 'FABHOM',@P1 ='" . $order_id . "', @P2 ='" . $payment_amount . "',@P3 ='" . $payment_mode . "' , @P4 ='Success',@P5 =null,@P6 ='" . $name ."',@P7 ='" . $customer_id . "',@P8 ='" . $mobile_number . "',@P9 ='" .$email . "', @P10 =null,@P11 ='Bangalore',@P12 =NULL,@P13 =NULL,@P14 =NULL,@P15 =NULL,@P16 =NULL,@P17 =NULL,@P18 =NULL,@P19 =NULL,@P20 =NULL,@REC_ID = 0";
        $this->write_pg_response($query1);
        $result1 = $this->db->query($query1);
        $this->write_pg_response($query1);
        $query2 = "EXEC " . $db . "..ALERT_PROCESS @ALERT_CODE = 'JFSL_DELIVERY_COMPLETED_FABHOME',@EMAIL_ID = '" . $email . "',@MOBILE_NO = '" . $mobile_number . "',@SUBJECT = NULL, @DISPATCH_FLAG = 'OFF',@EMAIL_SENDER_ADD = NULL, @SMS_SENDER_ADD = 'FABHOM',@P1 = " . $order_id . ",@P2 = '" . $service_type . "',@P3 = '" . $name . "',@P4 = null,@P5 = null,@P6 = '" . $payment_amount . "',@P7 = NULL,@P8 = '',@P9 = '',@P10 = NULL,@P11 = NULL,@P12 = '',@P13 = NULL,@P14 = NULL,@P15 = NULL,@P16 = NULL,@P17 = NULL,@P18 = NULL,@P19 = NULL,@P20 = NULL,@REC_ID = '0'";
      $this->write_pg_response($query2);
        $result2 = $this->db->query($query2);
        $this->write_pg_response($query2);
        return $result2;     
 
    }
    /**
     * Sending Payment failure mail to jfslmdm
     */
    public function send_fabhome_payment_failure_mail($customer_id,$name,$mobile_number,$order_id,$service_type,$payment_amount,$email,$payment_mode)
    {
        $order_id = ltrim($order_id, '#');
        $service_type = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $service_type));
        if (CURRENT_ENVIRONMENT == 'Cloud' || CURRENT_ENVIRONMENT == 'Live') {
            $db = 'Alert_Engine';
        } else {
            $db = 'Alert_Engine_UAT';
        }
        $query = "Exec ".$db. "..ALERT_PROCESS @ALERT_CODE = 'JFSL_FABHOME_PAYMENT_SUCCESS_EMAIL',@EMAIL_ID = 'jfsl.mdm@jyothy.com',@MOBILE_NO=null,@SUBJECT = 'FabHome - Payment Failed' ,@DISPATCH_FLAG = 'OFF',@EMAIL_SENDER_ADD='no-reply@jfslcloud.in' ,@SMS_SENDER_ADD = 'FABHOM',@P1 ='" . $order_id . "', @P2 ='" . $payment_amount . "',@P3 ='" . $payment_mode . "' , @P4 ='Failed',@P5 =null,@P6 ='" . $name ."',@P7 ='" . $customer_id . "',@P8 ='" . $mobile_number . "',@P9 ='" .$email . "', @P10 =null,@P11 ='Bangalore',@P12 =NULL,@P13 =NULL,@P14 =NULL,@P15 =NULL,@P16 =NULL,@P17 =NULL,@P18 =NULL,@P19 =NULL,@P20 =NULL,@REC_ID = 0";
        $this->write_pg_response($query);
        $result = $this->db->query($query);
        return $result;     
 
    }
    /**
     * //Writing the pg response log in a file.
     * @param $result
     */
    private function write_pg_response($response)
    {
        //Writing the stored procedure log in a file.
        $log_day = date("d-M-Y");
        $log_date = date("d-M-Y H:i:s");
        $json_response = json_encode($response);
        $request_time = $_SERVER["REQUEST_TIME"];
        $txt =
            "date: " .
            $log_date .
            ", response: " .
            $json_response .
            ", request time: " .
            $request_time .
            PHP_EOL;
        $underline = "";
        for ($i = 0; $i < strlen($txt); $i++) {
            $underline = $underline . "-";
        }
        $txt = $txt . $underline;
        $log_file = file_put_contents(
            "pg_response/" . $log_day . "_response.txt",
            $txt . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
	public function get_customerid_from_transaction_info_logs($id)
	{
		$data = $this->db->select('customer_id')->from('fabhome_transaction_info_logs')->where('cart_id',$id)->get()->result_array();
		return $data[0]['customer_id'];
	}
	public function get_details_from_transaction_payment_infologs($payment_id)
	{
		$data = $this->db->select('payment_amount,payment_mode')->from('fabhome_transaction_payment_info_logs')->where('payment_id',$payment_id)->get()->result_array();
		return $data;
	}
	/**
	 * Fetching Fabrewards coupons available for a customer
	 */
	public function get_userwise_FR_coupons($start_date,$end_date)
	{
		$start_date = date('d-m-Y',strtotime($start_date));
		$end_date = date('d-m-Y',strtotime($end_date));
		$query = "exec " . LOCAL_DB . ".dbo.GetFabRewardsDetails @CouponOfferStartDate='" . $start_date . "',@CouponOfferEndDate='". $end_date . "'";
		$data = $this->db->query($query)->result_array();
		if($data)
			return $data;
		else
			return FALSE;
	}
	public function get_campaign_userscount($campaign)
	{
		$query = $this->db->get_where('Coupons', array('Campaign' => $campaign));
        $ret = $query->num_rows();
		return $ret;
	}
	public function get_promo_ids_from_campaign($campaign)
	{
		$data = $this->db->select('Id')->from('Coupons')->where('Campaign',$campaign)->get()->result_array();
		if($data)
			return $data;
		else
			return FALSE;
	}
	public function get_campaign_details($id)
	{
		$campaign_data = $this->db->select('Campaign')->from('Coupons')->where('Id',$id)->get()->result_array();
		$data = $this->db->select('*')->from('Coupons')->where('Campaign',$campaign_data[0]['Campaign'])->get()->result_array();
		if($data)
			return $data;
		else
			return FALSE;
	}
	public function get_mobileno_from_campaignid($id)
	{
		$data = $this->db->select('mobile_number')->from('Coupon_Users')->where('coupon_id',$id)->get()->result_array();
		if($data)
			return $data[0]['mobile_number'];
		else
			return FALSE;
	}
	
	public function delete_campaigns($campaign)
	{
			$this->db->where_in('Campaign',$campaign)->delete('Coupons');
			return TRUE;
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
	/**
	 * Fetch all stores details
	 */
	public function get_all_stores()
    {
        $stores = $this->db->query('EXEC ' . SERVER_DB . '.dbo.sp_GetAllBranchDetails')->result_array();
        return $stores;
    }
	
    /**
     * Returns all the registered DCR user in the DB.
     * @return mixed
     */
    public function get_fabdaily_users($user_type)
    {
				if($user_type == "DCR")
		        	$users = $this->db->select('*')->from('DCR_Users')->order_by('Id', 'desc')->get()->result_array();
				else if($user_type == "AUDIT")
					$users = $this->db->select('*')->from('Audit_Users')->order_by('Id', 'desc')->get()->result_array();
        return $users;
    }
    /**
	 * Save dcr user 
	 */
	public function save_fabdaily_user($dcr_user,$user_type)
    {
		if($user_type == "DCR")
			$status = $this->db->insert('DCR_Users', $dcr_user);
		else if($user_type == "AUDIT")
			$status = $this->db->insert('Audit_Users', $dcr_user);
		$insert_id = $this->db->insert_id();
     return $insert_id;
    }
	
    /**
     * Updating a DCR user details from the console.
     * 
     */
    public function update_fabdaily_user($dcr_user, $dcr_user_id,$user_type)
    {
        $this->db->where('Id', $dcr_user_id);
		if($user_type == "DCR")
			$updated_status = $this->db->update('DCR_Users', $dcr_user);
		else if($user_type == "AUDIT")
			$updated_status = $this->db->update('Audit_Users', $dcr_user);
        return $updated_status;
    }
	
	/**
	 * Fetch branches accessable by a dcr user
	 */
	public function get_fabdaily_user_branches($id,$user_type)
	{
		if($user_type == "DCR")
			$data = $this->db->select('*')->from('DCR_User_Branches')->where('UserId',$id)->get()->result_array();
		else if($user_type == "AUDIT")
			$data = $this->db->select('Branches,BranchNames')->from('Audit_Users')->where('Id',$id)->get()->result_array();
		else if($user_type == "Audit_Complaints")
			$data = $this->db->select('Branches,BranchNames')->from('Audit_Complaints')->where('Id',$id)->get()->result_array();
		return $data;
	}
	/**
	 * Change fabdaily user status(active/inactive)
	 */
	public function change_fabdaily_user_status($id)
	{
		$data = $this->db->select('is_active')->from('DCR_Users')->where('Id',$id)->get()->result_array();
		$is_active = 1;
		if($data[0]['is_active'] == 1)
			$is_active = 0;
		$data = array(
			'is_active' => $is_active,
			'ModifiedOn' => date('Y-m-d H:i:s'),
			'ModifiedBy' => ADMIN_USERNAME
		);
		$this->db->where('Id',$id);
		$status = $this->db->update('DCR_Users',$data);
		if($is_active == 0){
			$data = array(
				'AuthKeyExpiry' => 1,
				'IsActive' => 0,
				'RecordLastUpdatedDate' => date('Y-m-d H:i:s')
			);
			$this->db->where(array('DUserId' => $id,'IsActive' => 1));
			$this->db->update('DcrUserLogins',$data);
		}
		return $status;
	}
	/**
	 * Check any access already exist for this user in the selected time period
	 */
	public function check_datewise_access($id,$access,$start_date,$end_date)
	{
		$existing_data = $this->db->select('*')->from('DCR_Branch_Access')->where('user_id', $id)->get()->result_array();
		$existing_ids = array();
		$j = 0;
		if(sizeof($existing_data) > 0){
			if($end_date != null){
				for($i=0;$i<sizeof($existing_data);$i++){
					if($start_date >= $existing_data[$i]['date'] && $end_date <= $existing_data[$i]['end_date']){
						$existing_ids[$j] = $existing_data[$i]['Id'];
						$j++;
					}else if($start_date <= $existing_data[$i]['date'] && $end_date >= $existing_data[$i]['end_date']){
						$existing_ids[$j] = $existing_data[$i]['Id'];
						$j++;
					}else if($start_date >= $existing_data[$i]['date'] && $start_date <= $existing_data[$i]['end_date']){
						$existing_ids[$j] = $existing_data[$i]['Id'];
						$j++;
					}else if($start_date <= $existing_data[$i]['date'] && $end_date >= $existing_data[$i]['end_date']){
						$existing_ids[$j] = $existing_data[$i]['Id'];
						$j++;
					}
				}
			}
		}
		if(sizeof($existing_ids) > 0){
			$data = array(
				'is_exist' => 1,
				'ids' => $existing_ids
			);
		}else {
			$data = array(
				'is_exist' => 0,
				'ids' => ''
			);
		}
		return $data;
	}
	/**
	 * Fetch date wise store access details
	 */
	public function get_datewise_access($id)
	{
		$access_data = $this->db->select('*')->from('DCR_Branch_Access')->where('user_id',$id)->get()->result_array();
		return $access_data; 
	}
	/**
	 * Change dcr aapp ccess of users on particular dates
	 */
	public function save_datewise_access($id,$access,$start_date,$end_date)
	{
		$is_exists = array();
		$existing_data = $this->db->select('*')->from('DCR_Branch_Access')->where('user_id', $id)->get()->result_array();
		$insert_data = array(
			'user_id' => $id,
			'date' => $start_date,
			'store_access' => $access,
			'end_date' => $end_date,
			'CreatedBy' => ADMIN_USERNAME,
			'CreatedOn' => date('Y-m-d H:i:s')
		);
		if(sizeof($is_exists) > 0){
			$this->db->where('Id',$is_exists[0]['Id']);
			$status = $this->db->update('DCR_Branch_Access',$insert_data);
		}else{
			$status = $this->db->insert('DCR_Branch_Access',$insert_data);
		}
		if($status){
			$update_data = array(
				'ModifiedBy' => ADMIN_USERNAME,
				'ModifiedOn' => date('Y-m-d H:i:s')
			);
			$this->db->where('Id',$id);
			$this->db->update('DCR_Users',$update_data);
		}
		return $status;
	}
	/**
     * For getting all the stores from Fabricare by passing brand code and city code.
     * @param $city_code
     * @param $brand_code
     * @return mixed
     */
    public function get_stores_sp($city_code)
    {
        $query = $this->db->query("EXEC " . LOCAL_DB . ".dbo.GetBranchDataForCityForDCR @CityCode ='" . $city_code . "', @BrandCode='PCT0000001'")->result_array();
        return $query;
    }
	/**
	 * Fetch latest dcr collections
	 */
	public function get_latest_dcr_collections($start_date,$end_date)
	{
		if($start_date == "" && $end_date == ""){
			$collections = $this->db->limit(10)->select('c.Id,c.Date,c.StoreBranchName,c.StoreBranchCode,c.CollectionType,c.DateFrom,c.DateTo,c.Remarks,c.TotalAmount,c.CollectedAmount,c.IsDeposited,c.DepositId,u.Name')->from('DCR_Collection c')->join('DCR_Users u', 'u.id=c.CollectedBy')->order_by('Id', 'desc')->get()->result_array();
		}else{
			$end_date = date('Y-m-d', strtotime($end_date. ' +1 days')); 
			$collections = $this->db->select('c.Id,c.Date,c.StoreBranchName,c.StoreBranchCode,c.CollectionType,c.DateFrom,c.DateTo,c.Remarks,c.TotalAmount,c.CollectedAmount,c.IsDeposited,c.DepositId,u.Name')->from('DCR_Collection c')->join('DCR_Users u', 'u.id=c.CollectedBy')->where("c.Date between '" .date('Y-m-d',strtotime($start_date))."' and '". date('Y-m-d',strtotime($end_date)) ."'")->get()->result_array();
		}
		return $collections;
	}
	/**
	 * Fetch branch details from fabricare via branch code
	 */
	public function get_branch_details_from_branchcode($branch_code)
	{
		$query ="exec " .SERVER_DB .".dbo.GetBranchInfoforDCR @branchcode='".$branch_code ."'";
		$data = $this->db->query($query)->result_array();
		return $data;
	}
	/**
	 * Fetch  datewise to be collected amount of a store
	 */
	public function get_datewise_tobe_collected_amount($date,$branch_code)
	{
		$query = "EXEC ". SERVER_DB .".dbo.RPT_StatementOfDailyCollection_Mobile_TotalAmount @InvoicePaymentFromDate='" . $date . "',@InvoicePaymentToDate='" . $date ."',@PaymentMode=1 ,@Branch='" .$branch_code ."'";
		$data = $this->db->query($query)->result_array();
		if(sizeof($data) > 0)
			$billamount = $data[0]['BillAmount'];
		else
			$billamount = '';
		return $billamount;
	}
	 /**
	 * Fetch  last 2 month(01-12-2022) tobe  collected amount of a store
	 */
	public function get_last_two_month_tobe_collected($end_date,$branch_code)
	{
		$start_date = '2022-12-01';
		$query = "EXEC ". SERVER_DB .".dbo.RPT_StatementOfDailyCollection_Mobile_TotalAmount @InvoicePaymentFromDate='" . $start_date . "',@InvoicePaymentToDate='" . date('Y-m-d',strtotime($end_date)) ."',@PaymentMode=1 ,@Branch='" .$branch_code ."'";
		$data = $this->db->query($query)->result_array();
		$billamount = 0;
		if(sizeof($data) > 0){
			for($i=0;$i<sizeof($data);$i++)
			{
				$billamount += $data[$i]['BillAmount'];
			}
		}else{
			$billamount = 0;
		}
		return $billamount;
	}
	/**
	 * Fetch fabdaily deposit details from deposit id
	 */
	public function get_deposit_details_from_id($id)
	{
		$deposit_data = $this->db->select('Image')->from('DCR_Deposit')->where('Id',$id)->get()->result_array();
		return $deposit_data;
	}
	/**
	 * Fetch  dcr deposits happened between a time period
	 */
	public function get_latest_dcr_deposits($start_date,$end_date)
	{
		$end_date = date('Y-m-d', strtotime($end_date. ' +1 days')); 
		$collections = array();
		$final_collections = array();
		$depositids = $this->db->query("select Id from ".LOCAL_DB.".dbo.DCR_Deposit where Date between  '" .date('Y-m-d',strtotime($start_date)) . " 00:00:00:000' and '".date('Y-m-d',strtotime($end_date)) ." 00:00:00:000'")->result_array();
		if(sizeof($depositids) > 0){
			$j =0;
			for($i=0;$i<sizeof($depositids);$i++){
				$collections[$j] = $this->db->select('c.Id,c.Date,c.StoreBranchName,c.StoreBranchCode,c.CollectionType,c.DateFrom,c.DateTo,c.Remarks,c.TotalAmount,c.CollectedAmount,c.IsDeposited,c.DepositId,u.Name')->from('DCR_Collection c')->join('DCR_Users u', 'u.id=c.CollectedBy')->where('DepositId',$depositids[$i]['Id'])->get()->result_array();
				$j++;
			}
		}
		$k=0;
		if(sizeof($collections) > 0){
			for($i=0;$i<sizeof($collections);$i++){
				if($collections[$i][0]['Date'] >=  date('Y-m-d H:i:s',strtotime($start_date)) && $collections[$i][0]['Date'] <=  date('Y-m-d H:i:s',strtotime($end_date))){
					$is_exists = 1;
				}else{
					$final_collections[$k] = $collections[$i][0];
					$k++;
				}
			}
		}
		return $final_collections;
	}
}
