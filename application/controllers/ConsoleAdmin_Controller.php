<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * (c) Jyothy Laboratories Ltd
 * Created by Manju Vibin
 * Date: 11/10/2022
 * Time: 10:36 AM
 * @property ConsoleAdmin_Model $ConsoleAdmin_Model
 * @property generic $generic
 */
ini_set('max_execution_time', 0);
ini_set('memory_limit','2048M');
ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288'); // Setting to 512M - for pdo_sqlsrv
class ConsoleAdmin_Controller extends CI_Controller
{

	/**
	 *Constructor for the admin controller
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('ConsoleAdmin_Model');
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
		if($this->session->userdata('username') == '') {
			redirect(console);
		}
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


	/* NEW UI */
	/* JSON data to get all data for fabhome order pages */
	public function get_fabhome_orders()
	{
		$cart_data = $this->ConsoleAdmin_Model->get_fabhome_cart_data();
		for($i=0;$i<sizeof($cart_data);$i++){
			$cart_data[$i]['pick_up_date'] = date('d-m-Y',strtotime($cart_data[$i]['pick_up_date']));
			$newtime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -15 minutes"));
			if(strtolower($cart_data[$i]['status']) != 'completed'  &&  strtolower($cart_data[$i]['status']) != 'cancelled'  && strtolower($cart_data[$i]['cart_status']) != 'paid' && $cart_data[$i]['cretn_date'] < $newtime){
				$cart_data[$i]['not_paid'] =1;
			}else{
				$cart_data[$i]['not_paid'] =0;
			}
		}
		$data = $this->generic->final_data('DATA_FOUND');
		$data['data'] = $cart_data;
		$this->generic->json_output($data);
	}


	/*  data to load the page of fabhome orders */
	public function admin_fabhome_orders()
	{
		$cart_data = $this->ConsoleAdmin_Model->get_fabhome_cart_data();
		$data = array('cart' => $cart_data);
		$this->load->view('Admin/BaseNew/admin_header');
		$this->load->view('Admin/PagesNew/admin_fabhome_cart',$data);
		$this->load->view('Admin/BaseNew/admin_footer');

	}

	/**
	 *updating te status of cart for console
	 */
	public function update_order_status()
	{
		$request = $this->input->post();
		$status = $request['status'];
		$id = $request['id'];
		$result = $this->ConsoleAdmin_Model->update_order_status($id,$status,ADMIN_USERNAME);
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
	/**
	 *To load the cart item details page  for console
	 */
	public function admin_fabhome_view_cartitems($id)
	{
		$cart_itm_data = $this->ConsoleAdmin_Model->get_fabhome_cart_items($id);
		$cart_data = $this->ConsoleAdmin_Model->get_fabhome_cart($id);
		//$this->ConsoleAdmin_Model->get_fabhome_cart_payment_details($id)
		$payment_details = '';
		$data = array('cart_items' => $cart_itm_data,'cart_data'=>$cart_data,'address' => $cart_data[0]['pick_up_address'],'order_status' => $cart_data[0]['status']);
		$this->load->view('Admin/BaseNew/admin_header');
		$this->load->view('Admin/PagesNew/admin_fabhome_cart_items',$data);
		$this->load->view('Admin/BaseNew/admin_footer');
	}

	/**
	 *To load the the essential popup data for console
	 */
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
			$popups = $this->ConsoleAdmin_Model->get_essentialpopups();
			$states = $this->ConsoleAdmin_Model->get_states_sp();
			$cities =[];
			$cities[0][0] = array(
				"citycode"=>"1234",
				"cityname"=> "test"
			);
			$j = 1;
			for($i=0;$i<sizeof($states);$i++){
				$cities[$j] = $this->ConsoleAdmin_Model->get_state_cities_sp($states[$i]['statecode']);
				$j++;
			}
			$data = array('popups' => $popups,'states' => $states,'cities' => $cities);
			$this->load->view('Admin/BaseNew/admin_header');
			$this->load->view('Admin/PagesNew/admin_essentialpopup', $data);
			$this->load->view('Admin/BaseNew/admin_footer');
		} else {

			$this->home();
		}
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
	 *Coupon page for admin console
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
			$states = $this->ConsoleAdmin_Model->get_states_sp();
			$cities=[];
			for($i=0;$i<sizeof($states);$i++){
				$cities[$i] = $this->ConsoleAdmin_Model->get_state_cities_sp($states[$i]['statecode']);
			}
			$data = array('states' => $states,'cities' => $cities);
			$this->load->view('Admin/BaseNew/admin_header');
			$this->load->view('Admin/PagesNew/admin_coupons', $data);
			$this->load->view('Admin/BaseNew/admin_footer');
		} else {

			$this->home();
		}
	}

	/* JSON data to get all coupons */
	public function get_all_coupons()
	{
		$cart_data = $this->ConsoleAdmin_Model->get_coupons();
		$data = $this->generic->final_data('DATA_FOUND');
		$coupon_data = array();
		$j = 0;
		$campaign_codes= array();
		$k=0;
		for($i=0;$i<sizeof($cart_data);$i++){
			if($cart_data[$i]['PromoCode'] != "")
				$cart_data[$i]['CouponCode'] = $cart_data[$i]['PromoCode'];
			else
				$cart_data[$i]['CouponCode'] = $cart_data[$i]['DiscountCode'];
			if($cart_data[$i]['start_date'] !='')
				$cart_data[$i]['start_date'] = date('d-m-Y',strtotime($cart_data[$i]['start_date']));
			else
				$cart_data[$i]['start_date'] = "";
			if($cart_data[$i]['ExpiryDate'] != NULL)
				$cart_data[$i]['ExpiryDate'] = date('d-m-Y',strtotime($cart_data[$i]['ExpiryDate']));
			else
				$cart_data[$i]['ExpiryDate'] = "NULL";
			
			if($cart_data[$i]['created_date'] != "")
				$cart_data[$i]['created_date'] = date('d-m-Y-H-i-s',strtotime($cart_data[$i]['created_date']));
			else
				$cart_data[$i]['created_date'] = "";
			if($cart_data[$i]['updated_date'] != "")
				$cart_data[$i]['updated_date'] = date('d-m-Y-H-i-s',strtotime($cart_data[$i]['updated_date']));
			else
				$cart_data[$i]['updated_date'] = "";
			if($cart_data[$i]['status_flg'] == "A")
				$cart_data[$i]['status_value'] = "Active";
			else
				$cart_data[$i]['status_value'] = "Inactive";
			$cart_data[$i]['no'] = $i+1;
			if($cart_data[$i]['used_count'] !=''){
				if($cart_data[$i]['used_count'] > 0 && $cart_data[$i]['used_count'] < $cart_data[$i]['count'])
					$cart_data[$i]['usage_status']	= 'Partially used';
				else if($cart_data[$i]['used_count'] == $cart_data[$i]['count'])
					$cart_data[$i]['usage_status']	= 'Completely used';
				else
					$cart_data[$i]['usage_status']	= 'Not used';
			}else{
				$cart_data[$i]['usage_status'] = '';
			}
			if($cart_data[$i]['Campaign'] != ""){
				$cart_data[$i]['CouponCode'] = $cart_data[$i]['Campaign'];
				$cart_data[$i]['ExpiryDate'] = "NULL";
				$cart_data[$i]['start_date'] = "";
				$cart_data[$i]['used_count'] = "";
				$cart_data[$i]['total_users'] = $this->ConsoleAdmin_Model->get_campaign_userscount($cart_data[$i]['Campaign']);
				
				if(!in_array($cart_data[$i]['CouponCode'], $campaign_codes)){
					$cart_data[$i]['campaign'] = 1;
					$coupon_data[$j] = $cart_data[$i];
					$j++;
					$campaign_codes[$k] = $cart_data[$i]['CouponCode'];
					$k++;
				}
			}else{
				$cart_data[$i]['campaign'] = 0;
				$coupon_data[$j] = $cart_data[$i];
				$j++;
			}
			
		}
	

		$data['data'] = $coupon_data;
		$this->generic->json_output($data);
	}

	/* Change the status coupons */
	public function change_coupons_status()
	{
		$id=$_POST['id'];
		$cart_data = $this->ConsoleAdmin_Model->change_coupons_status($id);
		$data['status'] = 'Successfully Updated';
		$this->generic->json_output($data);
	}
	/**
	 *Adding new coupons
	 */
	public function add_coupon()
	{	
		$request = $this->input->post();
		$statecode = $request['state'];
		$cities = $request['cities'];
		$promo_code = $request['promo_code'];
		$discount_code = $request['discount_code'];
		$app_remarks = $request['app_remarks'];
		$expiry_date = $request['expiry_date'];
		$mobile_numbers = $request['list'];
		$contacts = $request['contacts'];
        $coupons = $request['coupons'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $validity = $request['validity'];
		$campaign = $request['campaign'];
		if($statecode != ""){
			$total = "";
			if($statecode == "all"){
				$res = $this->ConsoleAdmin_Model->add_coupon($statecode, $cities ,$promo_code, $discount_code, $app_remarks, $expiry_date,$total,NULL,NULL,$campaign);
			}else{
				$all_states = $this->ConsoleAdmin_Model->get_states_sp();
				for($i=0;$i<sizeof($cities);$i++){
					for($j=0;$j<sizeof($all_states);$j++){
						$city[$j] = $this->ConsoleAdmin_Model->get_state_cities_sp($all_states[$j]['statecode']);
						for($c=0;$c<sizeof($city[$j]);$c++){
							if($city[$j][$c]['cityname'] == $cities[$i]){
								$res = $this->ConsoleAdmin_Model->add_coupon($all_states[$j]['statename'], $cities[$i] ,$promo_code, $discount_code, $app_remarks, $expiry_date,$total,NULL,NULL,$campaign);
							}
						}
					}
					
				}
			}
		}elseif($mobile_numbers != ""){
			$total = sizeof($mobile_numbers) -1;
			$res = $this->ConsoleAdmin_Model->add_coupon($statecode, $cities ,$promo_code, $discount_code, $app_remarks, $expiry_date,$total,NULL,NULL,$campaign);

			if($res){
				for($k=0;$k<sizeof($mobile_numbers)-1;$k++){
					$this->ConsoleAdmin_Model->add_coupon_users($mobile_numbers[$k],$res);
				}
			}
		
		}else{
				for($i=0;$i<sizeof($contacts)-1;$i++){
					$start_date[$i] = date_format (new DateTime($start_date[$i]), 'Y-m-d');
					$end_date[$i] = date_format (new DateTime($end_date[$i]), 'Y-m-d');
					$res = $this->ConsoleAdmin_Model->add_coupon($statecode, $cities,$coupons[$i],  $discount_code, $app_remarks, $end_date[$i],1,$start_date[$i],$validity[$i],$campaign);
					if($res){
						$this->ConsoleAdmin_Model->add_coupon_users($contacts[$i],$res);
					}
				}
			
        }
		if ($res) {
			$data = array('status' => 'success', 'message' => 'successfully saved');
		} else {
			$data = array('status' => 'failed', 'message' => 'Failed to save');
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
		$states = $this->ConsoleAdmin_Model->get_states_sp();
		for($i=0;$i<sizeof($states);$i++){
			if($states[$i]['statecode'] == $statecode){
				$row = $i;
			}
		}
		$res=array('row'=> $row,'size'=> sizeof($states));
		echo json_encode($res);
	}


	public function edit_coupon($id)
	{

		$coupons_details = $this->ConsoleAdmin_Model->get_coupon_details($id);
		$data = $this->generic->final_data('DATA_FOUND');
		$data['data'] = $coupons_details;
		$this->generic->json_output($data);
		//$this->load->view('Admin/Base/admin_header');
		//$this->load->view('Admin/PagesNew/admin_coupon_edit',$data);
		//$this->load->view('Admin/Base/admin_footer');

	}
	public function update_coupon()
	{
		$request = $this->input->post();
		$id = $request['coupon_id'];
		$promo_code = $request['promo_code'];
		$discount_code = $request['discount_code'];
		$app_remarks = $request['app_remarks'];
		$expiry_date = $request['expiry_date'];
		$start_date = $request['start_date'];
		$count = $request['count'];
		$coupons_details = $this->ConsoleAdmin_Model->get_coupon_details($id);
		if($coupons_details[0]['count'] == NULL && $coupons_details[0]['start_date'] == NULL){
			$start_date = NULL;
			$count = NULL;
		}
			$res = $this->ConsoleAdmin_Model->update_coupon($id,$promo_code, $discount_code, $app_remarks, $expiry_date,$start_date,$count);
			if ($res) {
				$data = array('status' => 'success', 'message' => 'successfully updated');
			} else {
				$data = array('status' => 'failed', 'message' => 'Failed to update');
			}
		echo json_encode($data);
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
			$service_details = array();
			for($i = 0;$i<sizeof($service_type);$i++){
				$rate_details[$i] = $this->ConsoleAdmin_Model->get_fabhome_rate_data($service_type[$i]);
			}
			$data = array('deepcleaning_rates' => $rate_details[0],'officecleaning_rates' => $rate_details[1],'homecleaning_rates' => $rate_details[2]);
			$this->load->view('Admin/BaseNew/admin_header');
			$this->load->view('Admin/PagesNew/admin_fabhome_rates', $data);
			$this->load->view('Admin/BaseNew/admin_footer');
		} else {

			$this->home();
		}
	}

	/* JSON data to get all get_all_fabhome_deep_rates */
	public function get_all_fabhome_deep_rates()
	{
		$service_type=array("Deep Cleaning","Office Cleaning","Home Cleaning");
		$rate_details = array();
		for($i = 0;$i<sizeof($service_type);$i++){
			$rate_details[$i] = $this->ConsoleAdmin_Model->get_fabhome_rate_data("Deep Cleaning");
			for($j=0;$j<sizeof($rate_details[$i]);$j++){
				$rate_details[$i][$j]['expiry'] = date('d-m-Y',strtotime($rate_details[$i][$j]['expiry']));
				if($rate_details[$i][$j]['created_date'] != "")
					$rate_details[$i][$j]['created_date'] = date('d-m-Y-H-i-s',strtotime($rate_details[$i][$j]['created_date']));
				else
					$rate_details[$i][$j]['created_date'] = "";
				if($rate_details[$i][$j]['updated_date'] != "")
					$rate_details[$i][$j]['updated_date'] = date('d-m-Y-H-i-s',strtotime($rate_details[$i][$j]['updated_date']));
				else
					$rate_details[$i][$j]['updated_date'] = "";
				if($rate_details[$i][$j]['start_date'] != "")
					$rate_details[$i][$j]['start_date'] = date('d-m-Y',strtotime($rate_details[$i][$j]['start_date']));
				else
					$rate_details[$i][$j]['start_date'] = "";
			}
		}
		$data = $this->generic->final_data('DATA_FOUND');
		$data['data'] =  $rate_details[0];
		$this->generic->json_output($data);
	}

	/* JSON data to get all get_all_fabhome_home_rates */
	public function get_all_fabhome_home_rates()
	{
		$service_type=array("Deep Cleaning","Office Cleaning","Home Cleaning");
		$rate_details = array();
		for($i = 0;$i<sizeof($service_type);$i++){
			$rate_details[$i] = $this->ConsoleAdmin_Model->get_fabhome_rate_data("Home Cleaning");
			for($j=0;$j<sizeof($rate_details[$i]);$j++){
				$rate_details[$i][$j]['expiry'] = date('d-m-Y',strtotime($rate_details[$i][$j]['expiry']));
				if($rate_details[$i][$j]['created_date'] != "")
					$rate_details[$i][$j]['created_date'] = date('d-m-Y-H-i-s',strtotime($rate_details[$i][$j]['created_date']));
				else
					$rate_details[$i][$j]['created_date'] = "";
				if($rate_details[$i][$j]['updated_date'] != "")
					$rate_details[$i][$j]['updated_date'] = date('d-m-Y-H-i-s',strtotime($rate_details[$i][$j]['updated_date']));
				else
					$rate_details[$i][$j]['updated_date'] = "";
				if($rate_details[$i][$j]['start_date'] != "")
					$rate_details[$i][$j]['start_date'] = date('d-m-Y',strtotime($rate_details[$i][$j]['start_date']));
				else
					$rate_details[$i][$j]['start_date'] = "";
			}
		}
		$data = $this->generic->final_data('DATA_FOUND');
		$data['data'] =  $rate_details[1];
		$this->generic->json_output($data);
	}

	/* JSON data to get all get_all_fabhome_office_rates */
	public function get_all_fabhome_office_rates()
	{
		$service_type=array("Deep Cleaning","Office Cleaning","Home Cleaning");
		$rate_details = array();
		for($i = 0;$i<sizeof($service_type);$i++){
			$rate_details[$i] = $this->ConsoleAdmin_Model->get_fabhome_rate_data("Office Cleaning");
			for($j=0;$j<sizeof($rate_details[$i]);$j++){
				$rate_details[$i][$j]['expiry'] = date('d-m-Y',strtotime($rate_details[$i][$j]['expiry']));
				if($rate_details[$i][$j]['created_date'] != "")
					$rate_details[$i][$j]['created_date'] = date('d-m-Y-H-i-s',strtotime($rate_details[$i][$j]['created_date']));
				else
					$rate_details[$i][$j]['created_date'] = "";
				if($rate_details[$i][$j]['updated_date'] != "")
					$rate_details[$i][$j]['updated_date'] = date('d-m-Y-H-i-s',strtotime($rate_details[$i][$j]['updated_date']));
				else
					$rate_details[$i][$j]['updated_date'] = "";
				if($rate_details[$i][$j]['start_date'] != "")
					$rate_details[$i][$j]['start_date'] = date('d-m-Y',strtotime($rate_details[$i][$j]['start_date']));
				else
					$rate_details[$i][$j]['start_date'] = "";
			}
		}
		$data = $this->generic->final_data('DATA_FOUND');
		//$rates = array('deepcleaning_rates' => $rate_details[0],'officecleaning_rates' => $rate_details[1],'homecleaning_rates' => $rate_details[2]);

		$data['data'] =  $rate_details[2];
		$this->generic->json_output($data);
	}



	/* edit_fabhome_rate */
	public function edit_fabhome_rate($id)
	{

		$rate_details = $this->ConsoleAdmin_Model->get_rate_details_from_id($id);
		$data = $this->generic->final_data('DATA_FOUND');
		$data['data'] = $rate_details;
		$this->generic->json_output($data);

	}

	/* deactivate_fabhome_rate */
	public function deactivate_fabhome_rate($id)
	{

		$status = $this->ConsoleAdmin_Model->deactivate_fabhome_rate($id);
		if($status){
			$data = array(
				'status' => 'success',
				'message' => "Succesfully Updated"
			);
		}else{
			$data = array(
				'status' => 'failed',
				'message' => 'Updation Failed'
			);
		}
		$this->generic->json_output($data);

	}


	/* activate_fabhome_rate */
	public function activate_fabhome_rate($id)
	{
		$message = $this->ConsoleAdmin_Model->activate_fabhome_rate($id);
		if($message == "Successfully Updated"){
			$data = array(
				'status' => 'success',
				'message' => $message
			);
		}else{
			$data = array(
				'status' => 'failed',
				'message' => $message
			);
		}
		$this->generic->json_output($data);
	}


	public function show_fabhome_rates()
	{
		$service_type=array("Deep Cleaning","Office Cleaning","Home Cleaning");
		$rate_details = array();
		for($i = 0;$i<sizeof($service_type);$i++){
			$rate_details[$i] = $this->ConsoleAdmin_Model->get_fabhome_rate_data($service_type[$i]);
		}
		$data = array('deepcleaning_rates' => $rate_details[0],'officecleaning_rates' => $rate_details[1],'homecleaning_rates' => $rate_details[2]);
		$this->load->view('Admin/Base/admin_header');
		$this->load->view('Admin/Pages/admin_fabhome_rates_details', $data);
		$this->load->view('Admin/Base/admin_footer');
	}
	/**
	 * Function for saving new rates into db
	 */
	
	public function add_rates()
	{
		$request = $this->input->post();
		$service_type = $request['service_type'];
		if($service_type == "deepcleaning")
			$service_type = "Deep Cleaning";
		else if($service_type == "officecleaning")
			$service_type = "Office Cleaning";
		else
			$service_type = "Home Cleaning";
		$service = $request['service'];
		$category = $request['category'];
		$uom = $request['input_uom'];
		$rate_per_uom = $request['rate_per_uom'];
		$discount_perc = $request['discount_perc'];
		$discount = $request['discount'];
		$tax = $request['tax'];
		$start_date = $request['start_date'];
		$expiry = $request['expiry_date'];
		$existing_active_data = $this->ConsoleAdmin_Model->get_fabhome_rate_data($service_type);
		$existing=0;
		for($i=0;$i<sizeof($existing_active_data);$i++){
			if(strtolower($existing_active_data[$i]['service']) == strtolower($service) && strtolower($existing_active_data[$i]['category']) == strtolower($category) && $existing_active_data[$i]['active'] == 1 && $existing_active_data[$i]['expiry'] >= date('Y-m-d')){
				if($start_date <= $existing_active_data[$i]['start_date'] && $expiry >= $existing_active_data[$i]['start_date'])
					$existing= 1;
				else if($start_date >= $existing_active_data[$i]['start_date'] && $expiry <= $existing_active_data[$i]['expiry'])
					$existing= 1;
				else if($start_date <= $existing_active_data[$i]['expiry'] && $expiry >= $existing_active_data[$i]['expiry'])
					$existing= 1;
			}
		}
		if($existing == 0){
			$status = $this->ConsoleAdmin_Model->save_rate_details($service_type,$service,$category,$uom,$rate_per_uom,$discount_perc,$discount,$tax,$expiry,$start_date);
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
	/**
	 * Function for updating new rates into db
	 */
	
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
		$start_date = $request['start_date'];
		$expiry = $request['expiry_date'];
		$id = $request['Id'];
		$existing_active_data = $this->ConsoleAdmin_Model->get_rate_data($id,$service_type,$service,$category);
		$existing=0;
		if(sizeof($existing_active_data) > 0){
			for($i=0;$i<sizeof($existing_active_data);$i++){
				if($existing_active_data[$i]['expiry'] >= date('Y-m-d')){
					if($start_date <= $existing_active_data[$i]['start_date'] && $expiry >= $existing_active_data[$i]['start_date'])
						$existing= 1;
					else if($start_date >= $existing_active_data[$i]['start_date'] && $expiry <= $existing_active_data[$i]['expiry'])
						$existing= 1;
					else if($start_date <= $existing_active_data[$i]['expiry'] && $expiry >= $existing_active_data[$i]['expiry'])
						$existing= 1;
				}
			}
		}
		if($existing == 0){
			$status = $this->ConsoleAdmin_Model->deactivate_fabhome_rate($id);
			if($status){
				$status = $this->ConsoleAdmin_Model->save_rate_details($service_type,$service,$category,$uom,$rate_per_uom,$discount_perc,$discount,$tax,$expiry,$start_date);
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
		}else{
			$data = array(
				"status" => "failed",
				"message" => "Sorry,an active rate is already present "
			);
		}
		$this->generic->json_output($data);
	}
	/**
	 * Function to download each coupon users' list
	 */
	public function download_coupon_users()
	{
		$request = $this->input->post();
        $id = $request['Id'];
		$coupons = $this->ConsoleAdmin_Model->get_coupon_details($id);
		if($coupons[0]['Campaign'] == ""){
			$users = $this->ConsoleAdmin_Model->get_coupon_users($id);
			if($users){
				if($coupons[0]['start_date'] != NULL)
					$start_date = date('d-m-Y',strtotime($coupons[0]['start_date']));
				else
					$start_date = "";
				if($coupons[0]['ExpiryDate'] != ""){
					$expiry_date = date('d-m-Y',strtotime($coupons[0]['ExpiryDate']));
					$is_campaign = 0;
				}else{
					$expiry_date = "";
					$is_campaign = 1;
				}
				$n='';
				$j = 2;

				if($coupons[0]['start_date'] != NULL && $coupons[0]['count'] != NULL){

					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'SI.No');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'PromoCode');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'DiscountCode');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'AppRemarks');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Valid From');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Valid Till');
					$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'No Of Times');
					$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Total applied pickups');
					$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Mobile Number');
					for($n =0;$n < sizeof($users);$n++){
						if(isset($users[$n])){
							$objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $j-1);
							$objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $coupons[0]['PromoCode']);
							$objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $coupons[0]['DiscountCode']);
							$objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $coupons[0]['AppRemarks']);
							$objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $start_date);
							$objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $expiry_date);
							$objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $coupons[0]['count']);
							$objPHPExcel->getActiveSheet()->SetCellValue('H' . $j, $coupons[0]['used_count']);
							$objPHPExcel->getActiveSheet()->SetCellValue('I' . $j, $users[$n]['mobile_number']);
							$j++;   
						}    
					}
				}else if($is_campaign == 1){

					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'SI.No');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Campaign Code');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Mobile Number');
					for($n =0;$n < sizeof($users);$n++){
						if(isset($users[$n])){
							$objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $j-1);
							$objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $coupons[0]['PromoCode']);
							$objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $users[$n]['mobile_number']);
							$j++;   
						}    
					}
				}else{
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'SI.No');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'PromoCode');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'DiscountCode');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'AppRemarks');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Valid From');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Valid Till');
					$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Mobile Number');
					for($n =0;$n < sizeof($users);$n++){
						if(isset($users[$n])){
							$objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $j-1);
							$objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $coupons[0]['PromoCode']);
							$objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $coupons[0]['DiscountCode']);
							$objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $coupons[0]['AppRemarks']);
							$objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $start_date);
							$objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $expiry_date);
							$objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $users[$n]['mobile_number']);
							$j++;   
						}    
					}
				}
				if($coupons[0]['PromoCode'] != "")
					$title = $coupons[0]['PromoCode'];
				else
					$title = $coupons[0]['DiscountCode'];
				$objPHPExcel->getActiveSheet()->setTitle($title." Coupon Users");

				//Determining file name based on brand_code
				$folder_name = "Fabricspa_Coupon_Users";
				// Set properties

				$objPHPExcel->getProperties()->setCreator(ADMIN_USERNAME);
				$objPHPExcel->getProperties()->setLastModifiedBy(ADMIN_USERNAME);
				$objPHPExcel->getProperties()->setTitle($title." Coupon Users");
				$objPHPExcel->getProperties()->setSubject($title." Coupon Users");
				$objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				//Final file name would be;
					
				$file_name = $title." Users.xls";
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
		}else{
			$promo_ids= $this->ConsoleAdmin_Model->get_promo_ids_from_campaign($coupons[0]['Campaign']);
			$j = 2;
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'SI.No');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'PromoCode');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Valid From');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Valid Till');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'No Of Times');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Total applied pickups');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Mobile Number');
			for($n =0;$n < sizeof($promo_ids);$n++){
				$promo[$n] = $this->ConsoleAdmin_Model->get_coupon_details($promo_ids[$n]['Id']);	
				$users[$n] = $this->ConsoleAdmin_Model->get_coupon_users($promo_ids[$n]['Id']);	
				$start_date = date('d-m-Y',strtotime($promo[$n][0]['start_date']));
				$expiry_date = date('d-m-Y',strtotime($promo[$n][0]['ExpiryDate']));
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $j-1);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $promo[$n][0]['PromoCode']);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $start_date);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, $expiry_date);
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, $promo[$n][0]['count']);
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $promo[$n][0]['used_count']);
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $users[$n][0]['mobile_number']);
				$j++; 

			}

			// $objPHPExcel->getActiveSheet()->setTitle($coupons[0]['Campaign']);

				//Determining file name based on brand_code
				$folder_name = "Fabricspa_Coupon_Users";
				// Set properties

				$objPHPExcel->getProperties()->setCreator(ADMIN_USERNAME);
				$objPHPExcel->getProperties()->setLastModifiedBy(ADMIN_USERNAME);
				$objPHPExcel->getProperties()->setTitle($coupons[0]['Campaign']." Users");
				$objPHPExcel->getProperties()->setSubject($coupons[0]['Campaign']." Users");
				$objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				//Final file name would be;
					
				$file_name = $coupons[0]['Campaign']." Users.xls";

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
		}
		echo json_encode($data);
	}
	/**
	 * Function to load fabhome services adding master page
	 */
	public function admin_fabhome_services()
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
			$this->load->view('Admin/BaseNew/admin_header');
			$this->load->view('Admin/PagesNew/admin_fabhome_services');
			$this->load->view('Admin/BaseNew/admin_footer');
		} else {

			$this->home();
		}
	}
	/**
	 * Function to add new fabhome services
	 */
	public function add_fabhome_service()
	{
		$request = $this->input->post();
        $services = $request['services'];
		$type = ucfirst(strtolower($request['service_type']));
		$service = $request['service'];
		$category = $request['category'];
		$uom = $request['uom'];
		$is_misssing = 0;
		if($services != ""){
			for($j=0;$j<sizeof($services);$j++){
				if(isset($services[$j])){
					if(!isset($services[$j][0]) || $services[$j][0] == "")
						$is_misssing++;
					else if(!isset($services[$j][1]) || $services[$j][1] == "")
						$is_misssing++;	
					else if(!isset($services[$j][2]) ||$services[$j][2] == "")
						$is_misssing++;
					else if(!isset($services[$j][3]) ||$services[$j][3] == "")
						$is_misssing++;
				}
			}
		}
		if($is_misssing == 0){
			$status = $this->ConsoleAdmin_Model->add_fabhome_services($services,$type,$service,$category,$uom);
			if ($status) {
				$data = array('status' => 'success', 'message' => 'successfully saved');
			} else {
				$data = array('status' => 'failed', 'message' => 'Failed to save');
			}
		}else{
			$data = array('status' => 'failed', 'message' => 'Please add all data correctly');
		}
		echo json_encode($data);
	}
	/**
	 * Function to fetch fahome services
	 */
	public function get_all_fabhome_services()
	{
		$services = $this->ConsoleAdmin_Model->get_fabhome_services();
		$data['services'] = "";
		if(sizeof($services) > 0){
			for($i=0;$i<sizeof($services);$i++){
				$services[$i]['service_type'] = ucfirst($services[$i]['service_type']);
				if($services[$i]['created_date'] != "")
					$services[$i]['created_date'] = date('d-M-Y',strtotime($services[$i]['created_date']));
				if($services[$i]['updated_date'] != "")
					$services[$i]['updated_date'] = date('d-M-Y',strtotime($services[$i]['updated_date']));
			}
		}
		$data['data'] = $services;
		$this->generic->json_output($data);

	}
		/* Function for updating fabhome services */
		public function edit_fabhome_service($id)
		{
	
			$rate_details = $this->ConsoleAdmin_Model->get_service_details_from_id($id);
			if(sizeof($rate_details) > 0){
				for($i=0;$i<sizeof($rate_details);$i++){
					$rate_details[$i]['service_type'] = ucfirst($rate_details[$i]['service_type']);
				}
			}
			$data = $this->generic->final_data('DATA_FOUND');
			$data['data'] = $rate_details;
			$this->generic->json_output($data);
	
		}
		/**
	 * Function for updating fabhome services into db
	 */
	public function update_service()
	{
		$request = $this->input->post();
		$service_type = $request['service_type'];
		$service = $request['service'];
		$category = $request['category'];
		$uom = $request['input_uom'];
		$id = $request['Id'];
		$status = $this->ConsoleAdmin_Model->update_fabhome_service($service_type,$service,$category,$uom,$id);
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
		$this->generic->json_output($data);
	}
	/**
	 * Function to delete fabhome service
	 */
	public function delete_service()
	{
		$request = $this->input->post();
		$id = $request['Id'];
		$status = $this->ConsoleAdmin_Model->delete_fabhome_service($id);
		if($status){
			$data = array(
				"status" => "success",
				"message" => "Deleted successfully"
			);
		}else{
			$data = array(
				"status" => "failed",
				"message" => "Failed to delete"
			);
		}
		$this->generic->json_output($data);
	}
		/**
	 * Function to get services by passing service_type
	 */
	public function get_fabhome_services_fromservicetype()
	{
		$request = $this->input->post();
		$type = $request['service_type'];
		$servicedata = $this->ConsoleAdmin_Model->get_fabhome_services_fromservicetype($type);
		for($i=0;$i<sizeof($servicedata);$i++){
			$services[$i] = $servicedata[$i]['service'];
		}
		$unique_data = array_unique($services);
		$j=0;
		foreach($unique_data as $a){
				$data[$j] = $a;
				$j++;
		}
		$data['data'] = $data;
		$this->generic->json_output($data);
	}
	/**
	 * Function to fetch categories avaialble by passing service typr and service
	 *
	 */
	public function get_fabhome_category_from_service()
	{
		$request = $this->input->post();
		$type = $request['service_type'];
		$service = $request['service'];
		$categorydata = $this->ConsoleAdmin_Model->get_fabhome_category_from_service($type,$service);
		for($i=0;$i<sizeof($categorydata);$i++){
			$categories[$i] = $categorydata[$i]['category'];
		}
		$unique_data = array_unique($categories);
		$j=0;
		foreach($unique_data as $a){
				$data[$j] = $a;
				$j++;
		}
		$data['category_data'] = $data;
		$this->generic->json_output($data);
	}
	/**
	 * Function to fetch input uom avaialble by passing service type,service and category
	 *
	 */
	public function get_fabhome_uom_from_category()
	{
		$request = $this->input->post();
		$type = $request['service_type'];
		$service = $request['service'];
		$category = $request['category'];
		$uomdata = $this->ConsoleAdmin_Model->get_fabhome_uom_from_category($type,$service,$category);
		for($i=0;$i<sizeof($uomdata);$i++){
			$uom[$i] = $uomdata[$i]['uom'];
		}
		$unique_data = array_unique($uom);
		$j=0;
		foreach($unique_data as $a){
				$data[$j] = $a;
				$j++;
		}
		$data['uom_data'] = $data;
		$this->generic->json_output($data);
	}
	/**
	 * Function to fetch fabricspa app deleted users list
	 */
	public function admin_fabricspa_users()
	{
		if (ADMIN) {

			/*Admin can access this page, but others needed to be checked.*/
			if (ADMIN_PREVILIGE != 'root') {

				/*Checking the validity of the access based on user accessibility.*/
				$page = 'FABRICSPA DELETED USERS';
				$validiy = $this->check_accessibility($page);

				if ($validiy == FALSE) {
					echo 'invalid access';
					exit(0);
				}
			}
			$states = $this->ConsoleAdmin_Model->get_states_sp();
			$cities=[];
			for($i=0;$i<sizeof($states);$i++){
				$cities[$i] = $this->ConsoleAdmin_Model->get_state_cities_sp($states[$i]['statecode']);
			}
			$data = array('states' => $states,'cities' => $cities);
			$this->load->view('Admin/BaseNew/admin_header');
			$this->load->view('Admin/PagesNew/admin_users_list',$data);
			$this->load->view('Admin/BaseNew/admin_footer');
		} else {

			$this->home();
		}
		
	}
	/**
	 * Function to get count of customers who deleted fabricspa app
	 */
	public function get_fabricspa_deleted_count()
	{
		$request = $this->input->post();
		$state = $request['state'];
		$cities = $request['cities'];
		$mobile_no = $request['mobile_number'];
		$sign_up_device = $request['device'];
		$library_params = array('brand_code' => "PCT0000001");
		$this->load->library('push_notification/firebase', $library_params);
		$firebase = new Firebase($library_params);
        $firebase = $this->firebase;		
		$count = 0;
		$n = 0;
		$mobile_numbers = array();
		$not_exists = 0;
		if($state == "all" && $cities == "all"){
			if($sign_up_device != "" && $sign_up_device != "all"){
				$mob_no = array();
				$regIds = array();
				$k = 0;
				$g= 0;
				
				$user_data = $this->ConsoleAdmin_Model->get_users_mobileno($sign_up_device);
				for($j=0;$j<sizeof($user_data);$j++){
					if(isset($user_data[$j]) && $user_data[$j]['mobile_number'] != NULL){
						if(!in_array($user_data[$j]['mobile_number'], $mob_no)){
								$is_valid = $this->ConsoleAdmin_Model->check_sign_up_source_valid($user_data[$j]['mobile_number'],$sign_up_device);
								if($is_valid == 1){
									$mob_no[$k] = $user_data[$j]['mobile_number'];
									$gcmid = $this->ConsoleAdmin_Model->get_gcmids($mob_no[$k],$sign_up_device);
									array_push($regIds, $gcmid);
									$response = $firebase->sendsilentnotification($regIds);
									if(strpos($response,'error')){
										$count++;
										$mobile_numbers[$n] = $mob_no[$k];
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
			}else{
				$device = array("android","ios");
				for($i=0;$i<sizeof($device);$i++){
					$mob_no = array();
					$regIds = array();
					$k = 0;
					$g= 0;
					
					$user_data[$i] = $this->ConsoleAdmin_Model->get_users_mobileno($device[$i]);
					for($j=0;$j<sizeof($user_data[$i]);$j++){
						if(isset($user_data[$i][$j]) && $user_data[$i][$j]['mobile_number'] != NULL){
							if(!in_array($user_data[$i][$j]['mobile_number'], $mob_no)){
									$is_valid = $this->ConsoleAdmin_Model->check_sign_up_source_valid($user_data[$i][$j]['mobile_number'],$device[$i]);
									if($is_valid == 1){
										$mob_no[$k] = $user_data[$i][$j]['mobile_number'];
										$gcmid = $this->ConsoleAdmin_Model->get_gcmids($mob_no[$k],$device[$i]);
										array_push($regIds, $gcmid);
										$response = $firebase->sendsilentnotification($regIds);
										if(strpos($response,'error')){
											$count++;
											$mobile_numbers[$n] = $mob_no[$k];
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
				}
			}
			if($count == 1)
				$message = "1 customer deleted Fabricspa App";
			else
				$message = $count .' customers deleted Fabricspa App';
			
		}else if($mobile_no != ""){
			$count = 0;
			$k =0;
			$mobile_numbers = array();
			$gcmid = $this->ConsoleAdmin_Model->get_gcmid_from_mobileno($mobile_no);
			$regIds = array();
			if($gcmid != ""){
				array_push($regIds, $gcmid);
				$response = $firebase->sendsilentnotification($regIds);
				if(strpos($response,'error')){
					$count++;
					$mobile_numbers[$k] = $mobile_no;
					$k++;
				}
			}else{
				$data = array(
					'status' => "success",
					'message' => "Failed to check",
					'mob_no' => ""
				);
				$not_exists = 1;
			}
			if($count == 1)
				$message = "This customer deleted Fabricspa App";
			else
				$message = $count .' customers deleted Fabricspa App';
		}else if($cities != ""){
			if($sign_up_device != "" && $sign_up_device != "all"){
				$regIds = array();
				$count = 0;
				$k=0;
				$mobile_numbers = array();
				for($i=0;$i<sizeof($cities);$i++){
					$user_data[$i] = $this->ConsoleAdmin_Model->get_users_via_location_and_device($cities[$i],$sign_up_device);
					for($j=0;$j<sizeof($user_data[$i]);$j++){
						if(isset($user_data[$i][$j]) && $user_data[$i][$j]['mobile_number'] != NULL){
							$gcmid = $this->ConsoleAdmin_Model->get_gcmid_from_mobileno($user_data[$i][$j]['mobile_number']);
							if($gcmid != ""){
								array_push($regIds, $gcmid);
								$response = $firebase->sendsilentnotification($regIds);
								if(strpos($response,'error')){
									$count++;
									$mobile_numbers[$k] = $user_data[$i][$j]['mobile_number'];
									$k++;
								}
								$regIds = array();
								$response = "";
							}
						}
					}
				}
			}else{
				$regIds = array();
				$count = 0;
				$k=0;
				$mobile_numbers = array();
				for($i=0;$i<sizeof($cities);$i++){
					$user_data[$i] = $this->ConsoleAdmin_Model->get_users_via_location($cities[$i]);
					for($j=0;$j<sizeof($user_data[$i]);$j++){
						if(isset($user_data[$i][$j]) && $user_data[$i][$j]['mobile_number'] != NULL){
							$gcmid = $this->ConsoleAdmin_Model->get_gcmid_from_mobileno($user_data[$i][$j]['mobile_number']);
							if($gcmid != ""){
								array_push($regIds, $gcmid);
								$response = $firebase->sendsilentnotification($regIds);
								if(strpos($response,'error')){
									$count++;
									$mobile_numbers[$k] = $user_data[$i][$j]['mobile_number'];
									$k++;
								}
								$regIds = array();
								$response = "";
							}
						}
					}
				}
			}
			if($count == 1)
				$message = "1 customer deleted Fabricspa App";
			else
				$message = $count .' customers deleted Fabricspa App';

		}else{
			$mob_no = array();
			$regIds = array();
			$k = 0;
			$g= 0;
			if($sign_up_device != "all"){
			$user_data = $this->ConsoleAdmin_Model->get_users_mobileno($sign_up_device);
				for($j=0;$j<sizeof($user_data);$j++){
					if(isset($user_data[$j]) && $user_data[$j]['mobile_number'] != NULL){
						if(!in_array($user_data[$j]['mobile_number'], $mob_no)){
								$is_valid = $this->ConsoleAdmin_Model->check_sign_up_source_valid($user_data[$j]['mobile_number'],$sign_up_device);
								if($is_valid == 1){
									$mob_no[$k] = $user_data[$j]['mobile_number'];
									$gcmid = $this->ConsoleAdmin_Model->get_gcmids($mob_no[$k],$sign_up_device);
									array_push($regIds, $gcmid);
									$response = $firebase->sendsilentnotification($regIds);
									if(strpos($response,'error')){
										$count++;
										$mobile_numbers[$n] = $mob_no[$k];
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
			}else{
				$device = array("android","ios");
				for($i=0;$i<sizeof($device);$i++){
					$mob_no = array();
					$regIds = array();
					$k = 0;
					$g= 0;
					
					$user_data[$i] = $this->ConsoleAdmin_Model->get_users_mobileno($device[$i]);
					for($j=0;$j<sizeof($user_data[$i]);$j++){
						if(isset($user_data[$i][$j]) && $user_data[$i][$j]['mobile_number'] != NULL){
							if(!in_array($user_data[$i][$j]['mobile_number'], $mob_no)){
									$is_valid = $this->ConsoleAdmin_Model->check_sign_up_source_valid($user_data[$i][$j]['mobile_number'],$device[$i]);
									if($is_valid == 1){
										$mob_no[$k] = $user_data[$i][$j]['mobile_number'];
										$gcmid = $this->ConsoleAdmin_Model->get_gcmids($mob_no[$k],$device[$i]);
										array_push($regIds, $gcmid);
										$response = $firebase->sendsilentnotification($regIds);
										if(strpos($response,'error')){
											$count++;
											$mobile_numbers[$n] = $mob_no[$k];
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
				}
			}
			if($count == 1)
				$message = "1 customer deleted Fabricspa App";
			else
				$message = $count .' customers deleted Fabricspa App';
		}
		if($count > 0){
			$n='';
            $f = 2;
			$location = array();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'SI.No');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Mobile Number');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Sign Up Device');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'City');

			for($n =0;$n < sizeof($mobile_numbers);$n++){
				$location[$n] = $this->ConsoleAdmin_Model->get_location_from_mobileno($mobile_numbers[$n]);
				if(isset($mobile_numbers[$n])){
					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $f, $f-1);
					$objPHPExcel->getActiveSheet()->SetCellValue('B' . $f, $mobile_numbers[$n]);
					if($sign_up_device != "" && $sign_up_device != "all")
						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $f, strtoupper($sign_up_device));
					else
						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $f, strtoupper($location[$n][0]['sign_up_source']));
					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $f, strtoupper($location[$n][0]['location']));
					$f++;   
				}    
			}
			$title = "Fabricspa_deleted_customers";
            $objPHPExcel->getActiveSheet()->setTitle($title);

            //Determining file name based on brand_code
            $folder_name = "fabricspa_deleted_customers";
            // Set properties

            $objPHPExcel->getProperties()->setCreator(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setLastModifiedBy(ADMIN_USERNAME);
            $objPHPExcel->getProperties()->setTitle($title);
            $objPHPExcel->getProperties()->setSubject($title);
            $objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            //Final file name would be;
                
            $file_name = $title.date('Y-m-d-H-i-s').".xls";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $target_file = 'excel_reports/' . $folder_name . '/' . $file_name;
            $save_status = $objWriter->save($target_file);
            $data = file_get_contents($target_file);
			$data = array(
                'status' => 'success',
                'file' => $file_name,
				'message' =>$message
            );
		}else{
			if($not_exists == 1)
				$message = "No user found";
			else if($mobile_no != "")
				$message = "This customer is still using the Fabricspa app";
			else
				$message = "No such deleted users found";
			$data = array(
                'status' => 'failed',
                'file' => '',
				'message' =>$message
            );
		}
		
        echo json_encode($data);

	}
	 /**
     * Function for updating payment status of orders with no response from paytm
     */
    public function update_fabhome_payment_status()
    {
        $request = $this->input->post();
        $id = $request['cart_id'];
        $order_details = $this->ConsoleAdmin_Model->check_payment_status($id);

        if( isset($order_details) && sizeof($order_details) >0){
            $payment_id = $order_details[0]['payment_id'];
            $data = $this->get_fbh_paytm_payment_status_single($payment_id);
        if($data = TRUE){
            $this->ConsoleAdmin_Model->update_fabhome_payment_status($id,ADMIN_USERNAME);
            $data = array('status' => 'success', 'message' => 'successfully updated');
			$customer_id = $this->ConsoleAdmin_Model->get_customerid_from_transaction_info_logs($id);
			$transaction_details = $this->ConsoleAdmin_Model->get_details_from_transaction_payment_infologs($payment_id);
			$cart_dtls = $this->ConsoleAdmin_Model->get_all_orders_bycart_id($id);
			$customer_details = $this->ConsoleAdmin_Model->get_customer_details_from_customerid($customer_id);
		   $this->ConsoleAdmin_Model->send_fabhome_payment_success_mail($customer_id,$customer_details[0]['name'],$customer_details[0]['mobile_number'],$cart_dtls[0]['order_id'],$cart_dtls[0]['service_type'],$transaction_details[0]['payment_amount'],$customer_details[0]['email'],$transaction_details[0]['payment_mode']);        
        }else{
            $data = array('status' => 'failed', 'message' => 'failed to update');
			$customer_id = $this->ConsoleAdmin_Model->get_customerid_from_transaction_info_logs($id);
			$transaction_details = $this->ConsoleAdmin_Model->get_details_from_transaction_payment_infologs($payment_id);
			$cart_dtls = $this->ConsoleAdmin_Model->get_all_orders_bycart_id($id);
			$customer_details = $this->ConsoleAdmin_Model->get_customer_details_from_customerid($customer_id);
		   $this->ConsoleAdmin_Model->send_fabhome_payment_failure_mail($customer_id,$customer_details[0]['name'],$customer_details[0]['mobile_number'],$cart_dtls[0]['order_id'],$cart_dtls[0]['service_type'],$transaction_details[0]['payment_amount'],$customer_details[0]['email'],$transaction_details[0]['payment_mode']);        
        }
        }else{
        	            $data = array('status' => 'success', 'message' => 'successfully updated');

        }

      
        echo json_encode($data);
    }
    public function get_fbh_paytm_payment_status_single($payment_id='')
    {    
        if($payment_id=='')
        $payment_id=$_GET['payment_id'];
        $data=$this->ConsoleAdmin_Model->get_fbh_paytm_payment_status_single($payment_id);
        $i='';
        $size = sizeof($data);
        if($size>1)
        $size=1;
    	$result = '';
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
            $checksum = $this->ConsoleAdmin_Model->get_fbh_checksum_details($bdy,PAYTM_SECRET_KEY);          
            /* head parameters */
            $paytmParams["head"] = array(           
                /* put generated checksum value here */
                "signature" => $checksum
            );
            
            /* prepare JSON string for request */
            $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
            
            /* for Staging */
            // $url = "https://securegw-stage.paytm.in/v3/order/status";
    
            
            
            /* for Production */
            $url = "https://securegw.paytm.in/v3/order/status";  
    
            ///https://securegw-stage.paytm.in/theia/processTransaction

            $this->write_pg_response($post_data);  
            $this->write_pg_response($url);

            
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
            $this->write_pg_response("response from PAYTM ...........<br>".$response);
            if($response!=''){
                $response = json_decode($response);
                $result = json_decode(json_encode($response), true);
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
                    $result_status=$status['resultMsg'];
                    $query=$this->ConsoleAdmin_Model->update_fbh_payment_summary($payment_id,$result_status);
                }
            }
        }
        if($result != "" && $status['resultStatus'] == 'TXN_SUCCESS') 
        	return true;  
        else
        	return false;
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
     * Fetching FR coupons available for a customer from fabricare
     */
    public function get_userwise_fr_coupons()
    {
       $request = $this->input->post();
        $start_date = $request['start_date'];
		$end_date = $request['end_date'];
		$contact_no = $request['contact_no'];
		$search_coupon = $request['coupon'];
		$reward_name = $request['reward_name'];
		$coupon_status = $request['coupon_status'];
		$final_coupons= array();
		$coupons = $this->ConsoleAdmin_Model->get_userwise_FR_coupons($start_date,$end_date);
		if($coupons != ""){
			$contact_filtered_coupon = array();
			$k=0;
			if($contact_no != ""){
				for($j=0;$j<sizeof($coupons);$j++){
					if($coupons[$j]['mobileno'] == $contact_no){
						$contact_filtered_coupon[$k] = $coupons[$j];
						$k++;
					}
				}
			}else{
				$contact_filtered_coupon = $coupons;
			}
		
			$filtered_coupon = array();
			$k=0;
			if($search_coupon != ""){
				for($j=0;$j<sizeof($contact_filtered_coupon);$j++){
					if($contact_filtered_coupon[$j]['discountCode'] == $search_coupon){
						$filtered_coupon[$k] = $contact_filtered_coupon[$j];
						$k++;
					}
				}
			}else{
				$filtered_coupon = $contact_filtered_coupon;
			}
			$reward_filtered_coupon = array();
			$k=0;
			if($reward_name != ""){
				for($j=0;$j<sizeof($filtered_coupon);$j++){
					if($filtered_coupon[$j]['Reward Name'] == $reward_name){
						$reward_filtered_coupon[$k] = $filtered_coupon[$j];
						$k++;
					}
				}
			}else{
				$reward_filtered_coupon = $filtered_coupon;
			}
			$final_coupons = array();
			$k=0;
			if($coupon_status != ""){
				for($j=0;$j<sizeof($reward_filtered_coupon);$j++){
					if(strtolower($reward_filtered_coupon[$j]['status']) == strtolower($coupon_status)){
						$final_coupons[$k] = $reward_filtered_coupon[$j];
						$k++;
					}
				}
			}else{
				$final_coupons = $reward_filtered_coupon;
			}
			$j =2;
			if(sizeof($final_coupons) > 0){
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'SI.No');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Discount from');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Discount to');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Coupon redeemption start date');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Coupon redeemption end date');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Customer ID');
					$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Contact #');
					$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Coupon #');
					$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Coupon status');
					$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'BlockingType');
					$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'AssignedOrUnassigned');
					$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'CouponOfferCode');
					$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'RewardName');
					$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Created by');
					for($n =0;$n < sizeof($final_coupons);$n++){
						if(isset($final_coupons[$n])){
							$objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $j-1);
							$objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $final_coupons[$n]['Discount From']);
							$objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $final_coupons[$n]['Discount To']);
							$objPHPExcel->getActiveSheet()->SetCellValue('D' . $j, date('d-m-Y',strtotime($final_coupons[$n]['CouponRedemptionStartDate'])));
							$objPHPExcel->getActiveSheet()->SetCellValue('E' . $j, date('d-m-Y',strtotime($final_coupons[$n]['CouponRedemptionEndDate'])));							
							$objPHPExcel->getActiveSheet()->SetCellValue('F' . $j, $final_coupons[$n]['CustomerName']);
							$objPHPExcel->getActiveSheet()->SetCellValue('G' . $j, $final_coupons[$n]['mobileno']);
							$objPHPExcel->getActiveSheet()->SetCellValue('H' . $j, $final_coupons[$n]['discountCode']);
							$objPHPExcel->getActiveSheet()->SetCellValue('I' . $j, $final_coupons[$n]['status']);
							$objPHPExcel->getActiveSheet()->SetCellValue('J' . $j, $final_coupons[$n]['BlockingType']);
							$objPHPExcel->getActiveSheet()->SetCellValue('K' . $j, $final_coupons[$n]['AssignedOrUnassigned']);
							$objPHPExcel->getActiveSheet()->SetCellValue('L' . $j, $final_coupons[$n]['Promocode']);
							$objPHPExcel->getActiveSheet()->SetCellValue('M' . $j, $final_coupons[$n]['Reward Name']);
							$objPHPExcel->getActiveSheet()->SetCellValue('N' . $j, $final_coupons[$n]['Created By']);					
							$j++;   
						}    
					}
				$title = "FR COUPONS";
				$objPHPExcel->getActiveSheet()->setTitle($title);

				//Determining file name based on brand_code
				$folder_name = "Fabricspa_Coupon_Users";
				// Set properties

				$objPHPExcel->getProperties()->setCreator(ADMIN_USERNAME);
				$objPHPExcel->getProperties()->setLastModifiedBy(ADMIN_USERNAME);
				$objPHPExcel->getProperties()->setTitle($title." Coupon Users");
				$objPHPExcel->getProperties()->setSubject($title." Coupon Users");
				$objPHPExcel->getProperties()->setDescription("Auto generated Excel file.");

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				//Final file name would be;
					
				$file_name = $title." Users.xls";
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
			}else{
				$data = array('status' => 'failed', 'message' => 'No data found');
			}
        } else {
            $data = array('status' => 'failed', 'message' => 'No data found');
        }
        // echo json_encode($data);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
   
    }
	public function edit_campaign()
	{
		$request = $this->input->post();
		$id = $request['Id'];
		$coupons_details = $this->ConsoleAdmin_Model->get_campaign_details($id);
		for($i=0;$i<sizeof($coupons_details);$i++){
				$coupons_details[$i]['Mobile_Number'] = $this->ConsoleAdmin_Model->get_mobileno_from_campaignid($coupons_details[$i]['Id']);
				$coupons_details[$i]['ExpiryDate'] = date('d-m-Y',strtotime($coupons_details[$i]['ExpiryDate']));
				$coupons_details[$i]['start_date'] = date('d-m-Y',strtotime($coupons_details[$i]['start_date']));
		}
		$data = $this->generic->final_data('DATA_FOUND');
		$data['data'] = $coupons_details;
		$this->generic->json_output($data);
	}
	public function get_campaign_from_id()
	{
		$request = $this->input->post();
		$id = $request['id'];
		$coupons_details = $this->ConsoleAdmin_Model->get_campaign_details($id);
		$data = $this->generic->final_data('DATA_FOUND');
		$data['campaign_name'] = $coupons_details[0]['Campaign'];
		$this->generic->json_output($data);
	}
	public function update_campaign()
	{
		$request = $this->input->post();
		$contacts = $request['contacts'];
        $coupons = $request['coupons'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $validity = $request['validity'];
		$campaign = $request['campaign'];
		$campaign_id = $request['campaign_id'];
		$old_campaign = $request['old_campaign'];
		$status = $this->ConsoleAdmin_Model->delete_campaigns($old_campaign);
		if($status){
			$cities = "NULL";
			$statecodes = "NULL";
			for($i=0;$i<sizeof($contacts)-1;$i++){
				$start_date[$i] = date_format (new DateTime($start_date[$i]), 'Y-m-d');
				$end_date[$i] = date_format (new DateTime($end_date[$i]), 'Y-m-d');
				$res = $this->ConsoleAdmin_Model->add_coupon($statecodes, $cities,$coupons[$i],  NULL, NULL, $end_date[$i],1,$start_date[$i],$validity[$i],$campaign);
				if($res){
					$this->ConsoleAdmin_Model->add_coupon_users($contacts[$i],$res);
				}
			}
		}
		if($res){
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
	/**
     *Fabdaily(DCR) users page
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
            $result = $this->ConsoleAdmin_Model->get_brands();
			$states = $this->ConsoleAdmin_Model->get_states_sp();
			$cities=[];
			for($i=0;$i<sizeof($states);$i++){
				$cities[$i] = $this->ConsoleAdmin_Model->get_state_cities_sp($states[$i]['statecode']);
			}
			$stores = $this->ConsoleAdmin_Model->get_all_stores();
			$active_stores = array();
			$j = 0;
			$inactive_stores = array();
			$k = 0;
			for($i=0;$i<sizeof($stores);$i++){
				if(strtolower($stores[$i]['branchstatus']) == "active"){
					$active_stores[$j] = $stores[$i];
					$j++;
				}else{
					$inactive_stores[$k] = $stores[$i];
					$k++;
				}
			}
            $data = array('brands' => $result,'states' => $states,'cities' => $cities,'active_stores' => $active_stores,'inactive_stores' => $inactive_stores);
            $this->load->view('Admin/BaseNew/admin_header');
            $this->load->view('Admin/PagesNew/admin_dcr_users', $data);
            $this->load->view('Admin/BaseNew/admin_footer');
        } else {
            $this->home();
        }
    }
	/**
     * Load DCR report page
     */
    public function admin_dcr_reports()
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
            $this->load->view('Admin/BaseNew/admin_header');
            $this->load->view('Admin/PagesNew/admin_dcr_report');
            $this->load->view('Admin/BaseNew/admin_footer');
        } else {

            $this->home();
        }
    }
	/**
	 * Fetching all DCR users from db
	 */
	public function get_dcr_users()
    {
		$dcr_users = array();
    	$dcr_users = $this->ConsoleAdmin_Model->get_fabdaily_users("DCR");
		$today = date('Y-m-d');
		if(sizeof($dcr_users) > 0){
			for($i=0;$i<sizeof($dcr_users);$i++){
				$dcr_users[$i]['SI_no'] = $i+1;
				$dcr_users[$i]['BranchNames'] = json_decode($dcr_users[$i]['BranchNames']);
				$access_today = $this->db->query("select store_access from ".LOCAL_DB.".dbo.DCR_Branch_Access where user_id = ".$dcr_users[$i]['Id']." and '".$today."' between date and end_date")->result_array();
				if(sizeof($access_today) > 0){
					$dcr_users[$i]['store_access_limit'] = $access_today[0]['store_access'];
				}
				if($dcr_users[$i]['store_access_limit'] == 1)
					$dcr_users[$i]['store_access'] = 'Within 100 meters from Store';
				else
					$dcr_users[$i]['store_access'] = 'From Anywhere';
				$dcr_users[$i]['Date'] = date('d-m-Y H:i:s',strtotime($dcr_users[$i]['Date']));
				if($dcr_users[$i]['ModifiedOn'] != '')
					$dcr_users[$i]['ModifiedOn'] = date('d-m-Y H:i:s',strtotime($dcr_users[$i]['ModifiedOn']));
			}
		}
        $data = $this->generic->final_data('DATA_FOUND');
		$data['data'] = $dcr_users;
		$this->generic->json_output($data);
    }
	/**
     *Saving a DCR user from the console.
     */
	public function save_dcr_user()
    {
        if (ADMIN) {
			$request = $this->input->post();
            $saved_dcr_user = array();
            $mode = $request['mode'];
            $dcr_user_name = $request['name'];
            $dcr_user_contactno = $request['contactno'];
            $dcr_user_branches = json_encode($request['stores']);
            $dcr_user_branch_names = json_encode($request['store_names']);
			$dcr_user_store_access = $request['store_access'];			
			if($dcr_user_contactno != ""){
				if (array_key_exists('id', $request)) {
					$dcr_user_id = $request['id'];
					$is_mobileno_exists = array();
					$is_mobileno_exists = $this->db->select('Id')->from('DCR_Users')->where(array('Phone' => $dcr_user_contactno,'Id !=' =>$dcr_user_id ))->get()->result_array();
				} else {
					$dcr_user_id = NULL;
					$is_mobileno_exists = array();
					$is_mobileno_exists = $this->db->select('Id')->from('DCR_Users')->where('Phone',$dcr_user_contactno)->get()->result_array();
				}
			}else{
				$dcr_user_id = $request['id'];
				$is_mobileno_exists = array();
			}
			if(sizeof($is_mobileno_exists) > 0){
				$data = array('status' => 'failed', 'message' => 'Another user exists with this mobile number');
			}else{
				/*Loading Encryption library*/
				$this->load->library('encryption');
				/*Creating unix time stamp*/
				$date = date('Y-m-d H:i:s');
				if($dcr_user_name != ""  && $dcr_user_contactno != ""){
					if($dcr_user_branches != "" && $dcr_user_branch_names != ""){
						$dcr_user = array(
							'Date' => $date,
							'Name' => $dcr_user_name,
							'Phone' => $dcr_user_contactno,
							'Privilege' => '',
							'Password' => '',
							'CreatedBy' => ADMIN_USERNAME,
							'store_access_limit' => $dcr_user_store_access,
							'is_active' => 1,
							'IsDeleted' => 0
						);
					}else{
						$dcr_user = array(
							'Date' => $date,
							'Name' => $dcr_user_name,
							'Phone' => $dcr_user_contactno,
							'Privilege' => '',
							'Password' => '',
							'CreatedBy' => ADMIN_USERNAME,
							'store_access_limit' => $dcr_user_store_access,
							'is_active' => 1,
							'IsDeleted' => 0
						);
					}
				}else{
					$dcr_user = array(
						'CreatedBy' => ADMIN_USERNAME,
						'IsDeleted' => 0
					);
				}
				if ($mode == 'add') {
					$inserted_id = $this->ConsoleAdmin_Model->save_fabdaily_user($dcr_user,'DCR');
					if($inserted_id){
						for($i=0;$i<sizeof($request['stores']);$i++){
							if (strpos($request['store_names'][$i],'INACT') !== false) {
								$request['store_names'][$i] = str_replace("INACT", " ",$request['store_names'][$i]);
							}
							$insert_data = array(
								'UserId' => $inserted_id,
								'BranchCode' => $request['stores'][$i],
								'BranchName' => $request['store_names'][$i],
								'CreatedOn' =>  date('Y-m-d H:i:s'),
								'CreatedBy' => ADMIN_USERNAME
							);
							$this->db->insert('DCR_User_Branches',$insert_data);
						}
						$status = TRUE;
					}
				} else if ($mode == 'edit') {
					$dcr_user = array(
						'Name' => $dcr_user_name,
						'Phone' => $dcr_user_contactno,
						'ModifiedOn' => date('Y-m-d H:i:s'),
						'ModifiedBy' => ADMIN_USERNAME,
						'store_access_limit' => $dcr_user_store_access,
						'is_active' => 1
					);
					$status = $this->ConsoleAdmin_Model->update_fabdaily_user($dcr_user, $dcr_user_id,'DCR');
				}
				if ($status) {
					$data = array('status' => 'success', 'message' => 'successfully saved');
				} else {
					$data = array('status' => 'failed', 'message' => 'Failed to save');
				}
			}
			echo json_encode($data);
        } else {
            $this->home();
        }

    }
	/**
	 * Fetch branches accessable by a dcr user /audit user and a particular question available branches
	 */
	public function show_accessable_branches($id)
	{
		$request = $this->input->post();
		$user_type = $request['user_type'];
		$branches = $this->ConsoleAdmin_Model->get_fabdaily_user_branches($id,$user_type);
		for($i=0;$i<sizeof($branches);$i++){
			if($branches[$i]['CreatedOn'] != NULL){
				$branches[$i]['CreatedOn'] = date('d-m-Y H:i:s',strtotime($branches[$i]['CreatedOn']));
			}
		}
		if(sizeof($branches) ==  0){
			if($user_type == "DCR"){
				$old_branches = $this->db->select('Branches,BranchNames')->from('DCR_Users')->where('Id',$id)->get()->result_array();
				if($old_branches[0]['Branches'] != "" && sizeof($old_branches) > 0){
					$branch_codes = json_decode($old_branches[0]['Branches']);
					$branch_names = json_decode($old_branches[0]['BranchNames']);

					for($i=0;$i<sizeof($branch_codes);$i++){
						$branches[$i]['BranchName'] =  $branch_names[$i];
						$branches[$i]['BranchCode'] =  $branch_codes[$i];
						$branches[$i]['CreatedOn'] =  '';
						$branches[$i]['CreatedBy'] =  '';
					}
				}
			}
		}
		$data = $this->generic->final_data('DATA_FOUND');
		$data['branches'] = $branches;
		$this->generic->json_output($data);
	}
	/**
	 * Fetch details of a dcr users
	 */
	public function get_fabdaily_user_details($id)
	{
		$request = $this->input->post();
		$user_type = $request['user_type'];
		if($user_type == "DCR")
			$data = $this->db->select('Id,Name,Phone,store_access_limit')->from('DCR_Users')->where('Id',$id)->get()->result_array();
		else if($user_type == "AUDIT")
			$data = $this->db->select('Id,Name,Phone,store_access_limit')->from('Audit_Users')->where('Id',$id)->get()->result_array();
		$final_data['user_details'] = $data[0];
		$this->generic->json_output($final_data);
	}	
	/* Change fabdaily user status(active/inactive) */
	public function change_fabdaily_user_status()
	{
		$id=$_POST['id'];
		$cart_data = $this->ConsoleAdmin_Model->change_fabdaily_user_status($id);
		$data['status'] = 'Successfully Updated';
		$this->generic->json_output($data);
	}
	/**
	 * Check any access already exist for this user in the selected time period
	 */
	public function check_datewise_access()
	{
		$request = $this->input->post();
		$id = $request['id'];
		$access = $request['access'];
		$start_date = $request['start_date'];
		$end_date = $request['end_date'];
		$is_data_exists = $this->ConsoleAdmin_Model->check_datewise_access($id,$access,$start_date,$end_date);
		if($is_data_exists['is_exist'] == 1){
			$data = array('status' => 'success', 'is_exist' => 1,'existing_ids' => $is_data_exists['ids']);
		}else{
			$data = array('status' => 'success', 'is_exist' => 0,'existing_ids' => '');
		}
		echo json_encode($data);
	}
	/**
	 * Fetch date wise store access details
	 */
	public function get_datewise_access()
	{
		$request = $this->input->post();
		$id = $request['id'];
		$access_data = $this->ConsoleAdmin_Model->get_datewise_access($id);
		if ($access_data) {
			$today = date('Y-m-d');
			$access_today = $this->db->query("select Id,store_access from ".LOCAL_DB.".dbo.DCR_Branch_Access where user_id = ".$id." and '".$today."' between date and end_date")->result_array();
			if(sizeof($access_today) > 0){
				$existing_id = $access_today[0]['Id'];
				$general_access_data = $this->db->select('store_access_limit')->from('DCR_Users')->where('Id',$id)->get()->result_array();
				$general_access = $general_access_data[0]['store_access_limit'];
			}else{
				$existing_id = '';
				$general_access = '';
			}
			$final_data = array();
			$k=0;
			for($i=0;$i<sizeof($access_data);$i++){
				$access_data[$i]['date'] = date('d-m-Y',strtotime($access_data[$i]['date']));
				if($access_data[$i]['end_date'] != NULL){
					$access_data[$i]['end_date'] = date('d-m-Y',strtotime($access_data[$i]['end_date']));
				}
				if($access_data[$i]['CreatedOn'] != NULL)
					$access_data[$i]['CreatedOn'] = date('d-m-Y H:i:s',strtotime($access_data[$i]['CreatedOn']));
				if($existing_id != $access_data[$i]['Id']){
					$final_data[$k] = $access_data[$i];
					$k++;
				}
			}
			$data = array('status' => 'success', 'access_data' => $final_data,'general_access' => $general_access,'existing_id' => $existing_id);
		} else {
			$data = array('status' => 'failed', 'access_data' => $final_data,'general_access' => '','existing_id' => $existing_id);
		}	
		echo json_encode($data);
	}
	/**
	 * Remove store access of user in particular dates
	 */
	public function remove_access()
	{
		$request = $this->input->post();
		$id = $request['id'];
		$user_data = $this->db->select('user_id')->from('DCR_Branch_Access')->where('Id',$id)->get()->row_array();
		$status = $this->db->where_in('Id',$id)->delete('DCR_Branch_Access');
		if($status){
			$update_data = array(
				'ModifiedBy' => ADMIN_USERNAME,
				'ModifiedOn' => date('Y-m-d H:i:s')
			);
			$this->db->where('Id',$user_data['user_id']);
			$this->db->update('DCR_Users',$update_data);
		}
		if($status){
			$data = array(
				"status" => "success",
				"message" => "Deleted successfully"
			);
		}else{
			$data = array(
				"status" => "failed",
				"message" => "Failed to delete"
			);
		}
		$this->generic->json_output($data);
	}
	/**
	 * Change dcr aapp ccess of users on particular dates 
	 */
	public function save_datewise_access()
	{
		$request = $this->input->post();
		$id = $request['id'];
		$access = $request['access'];
		$start_date = $request['start_date'];
		$end_date = $request['end_date'];
		$existing_ids = $request['existing_ids'];
		if($existing_ids != ''){
			for($i=0;$i<sizeof($existing_ids);$i++){
				$this->db->where_in('Id',$existing_ids[$i])->delete('DCR_Branch_Access');
			}
		}
		$status = $this->ConsoleAdmin_Model->save_datewise_access($id,$access,$start_date,$end_date);
		if ($status) {
			$data = array('status' => 'success', 'message' => 'successfully saved');
		} else {
			$data = array('status' => 'failed', 'message' => 'Failed to save');
		}
		echo json_encode($data);
	}
	/**
	 * Remove fabdaiy user
	 */
	// public function delete_fabdaily_user()
	// {
	// 	$request = $this->input->post();
	// 	$id = $request['id'];
	// 	$status = $this->db->where_in('Id',$id)->delete('DCR_Users');
	// 	if($status){
	// 		$data = array(
	// 			"status" => "success",
	// 			"message" => "Deleted successfully"
	// 		);
	// 	}else{
	// 		$data = array(
	// 			"status" => "failed",
	// 			"message" => "Failed to delete"
	// 		);
	// 	}
	// 	$this->generic->json_output($data);
	// }
	/**
	 * Fetch branches in a city by passing city code
	 */
	public function get_citywise_branches()
	{
		$request = $this->input->post();
		$city_code = $request['city_code'];
		$brand_code = array(
			"PCT0000001", //fabricspa brandcode
			"PCT0000002", //snoways
			"PCT0000007",//expert
			"PCT0000014" //click2wash
		);
		$stores = array();
		for($i=0;$i<sizeof($brand_code);$i++){
			$branches = $this->ConsoleAdmin_Model->get_stores_sp($city_code,$brand_code[$i]);
			$size = sizeof($stores);
			if(sizeof($branches) > 0){
				for($j=0;$j<sizeof($branches);$j++){
					$stores[$size] = $branches[$j];
					$size++;
				}
			}
		}
		$active_stores = array();
		$j = 0;
		$inactive_stores = array();
		$k = 0;
		for($i=0;$i<sizeof($stores);$i++){
			if(strtolower($stores[$i]['branchstatus']) == "active"){
				$active_stores[$j] = $stores[$i];
				$j++;
			}else{
				$inactive_stores[$k] = $stores[$i];
				$k++;
			}
		}
		if($stores){
			$data = array(
				"status" => "success",
				"message" => 'Stores found successfully',
				"active_stores" => $active_stores,
				"inactive_stores" => $inactive_stores
			);
		}else{
			$data = array(
				"status" => "failed",
				"message" => "No stores found",
				"active_stores" => "",
				"inactive_stores" => ""
			);
		}
		$this->generic->json_output($data);
	}
	/**
	 * Adding access to new stores for a dcr user
	 */
	public function update_dcr_accessable_stores()
	{
		$request = $this->input->post();
		$user_id = $request['user_id'];
		$is_row_found = 0;
		$is_row_found= $this->db->select('*')->from('DCR_User_Branches')->where('UserId',$user_id)->get()->num_rows();
		if($is_row_found == 0){
			$branches = $this->db->select('Branches,BranchNames')->from('DCR_Users')->where('Id',$user_id)->get()->result_array();
			if($branches[0]['Branches'] != "" && sizeof($branches) > 0){
				$branch_codes = json_decode($branches[0]['Branches']);
				$branch_names = json_decode($branches[0]['BranchNames']);
				for($i=0;$i<sizeof($branch_codes);$i++){
					$insert_data = array(
						'UserId' => $user_id,
						'BranchCode' => $branch_codes[$i],
						'BranchName' => $branch_names[$i],
						'CreatedOn' =>  date('Y-m-d H:i:s'),
						'CreatedBy' => ADMIN_USERNAME
					);
					$this->db->insert('DCR_User_Branches',$insert_data);
				}
			}
		}
		for($i=0;$i<sizeof($request['stores']);$i++){
			if (strpos($request['store_names'][$i],'INACT') !== false) {
				$request['store_names'][$i] = str_replace("INACT", " ",$request['store_names'][$i]);
			}
			$is_exists = $this->db->select('Id')->from('DCR_User_Branches')->where(array('UserId' => $user_id,'BranchCode' => $request['stores'][$i]))->get()->num_rows();
			if($is_exists == 0){
				$insert_data = array(
					'UserId' => $user_id,
					'BranchCode' => $request['stores'][$i],
					'BranchName' => $request['store_names'][$i],
					'CreatedOn' =>  date('Y-m-d H:i:s'),
					'CreatedBy' => ADMIN_USERNAME
				);
				$this->db->insert('DCR_User_Branches',$insert_data);	
			}
		}
		$update_data = array(
			'ModifiedBy' => ADMIN_USERNAME,
			'ModifiedOn' => date('Y-m-d H:i:s')
		);
		$this->db->where('Id',$user_id);
		$this->db->update('DCR_Users',$update_data);
		$data = array('status' => 'success', 'message' => 'successfully saved');
		$this->generic->json_output($data);
	}
	/**
	 * Removing accessable stores of a dcr user
	 */
	public function remove_dcr_store_access()
	{
		$request = $this->input->post();
		$user_id = $request['user_id'];
		$is_exists = 0;
		$is_exists= $this->db->select('*')->from('DCR_User_Branches')->where('UserId',$user_id)->get()->num_rows();
		if($is_exists == 0){
			$branches = $this->db->select('Branches,BranchNames')->from('DCR_Users')->where('Id',$user_id)->get()->result_array();
			if(sizeof($branches) > 0){
				$branch_codes = json_decode($branches[0]['Branches']);
				$branch_names = json_decode($branches[0]['BranchNames']);
				for($i=0;$i<sizeof($branch_codes);$i++){
					$insert_data = array(
						'UserId' => $user_id,
						'BranchCode' => $branch_codes[$i],
						'BranchName' => $branch_names[$i],
						'CreatedOn' =>  date('Y-m-d H:i:s'),
						'CreatedBy' => ADMIN_USERNAME
					);
					$this->db->insert('DCR_User_Branches',$insert_data);
				}
			}
		}
		for($i=0;$i<sizeof($request['stores']);$i++){
			$status= $this->db->where(array('UserId'=> $user_id,'BranchCode' => $request['stores'][$i]))->delete('DCR_User_Branches');
		}
		if($status){
			$update_data = array(
				'Branches' => NULL,
				'BranchNames' => NULL,
				'ModifiedBy' => ADMIN_USERNAME,
				'ModifiedOn' => date('Y-m-d H:i:s')
			);
			$this->db->where('Id',$user_id);
			$this->db->update('DCR_Users',$update_data);
		}
		$data = array('status' => 'success', 'message' => 'Removed successfully ');
		$this->generic->json_output($data);
	}
	/**
	 * Fetch dcr reports in a time period
	 */
	public function get_dcr_reports_from_timeperiod($start_date = FALSE,$end_date = FALSE)
	{
		$request = $this->input->post();
		$collections = $this->ConsoleAdmin_Model->get_latest_dcr_collections($start_date,$end_date);
		$dcr_data = array();
		$d=0;
		if($start_date != $end_date){
			$period = new DatePeriod(
				new DateTime($start_date),
				new DateInterval('P1D'),
				new DateTime($end_date)
			);
		}else{
			$period = "sameday";
		}
		if(sizeof($collections) > 0){
			for($i=0;$i<sizeof($collections);$i++){
				$branch_details = $this->ConsoleAdmin_Model->get_branch_details_from_branchcode($collections[$i]['StoreBranchCode']);
				$collections[$i]['State'] = $branch_details[0]['StateName'];
				$collections[$i]['City'] = $branch_details[0]['CityName'];
				$collections[$i]['Brand'] = $branch_details[0]['BrandDescription'];
				$collections[$i]['Fabricaredate'] = date('d-m-Y',strtotime($collections[$i]['Date']));
				$collections[$i]['cash_settlement_in_fabricare'] = $this->ConsoleAdmin_Model->get_datewise_tobe_collected_amount(date('Y-m-d',strtotime($collections[$i]['Date'])),$collections[$i]['StoreBranchCode']);
				$collections[$i]['total_cash_tobe_collected_till_date'] = $this->ConsoleAdmin_Model->get_last_two_month_tobe_collected($collections[$i]['Fabricaredate'],$collections[$i]['StoreBranchCode']);
				$collections[$i]['DateFrom'] = date('d-m-Y',strtotime($collections[$i]['DateFrom']));
				$collections[$i]['DateTo'] = date('d-m-Y',strtotime($collections[$i]['DateTo']));
				// $collections[$i]['Difference_to_collect'] = $collections[$i]['TotalAmount'] - $collections[$i]['CollectedAmount'];
				$collections[$i]['Difference_to_collect'] = $collections[$i]['total_cash_tobe_collected_till_date'] - $collections[$i]['CollectedAmount'];
				if($collections[$i]['IsDeposited'] == 1){
					$deposit_details = $this->ConsoleAdmin_Model->get_deposit_details_from_id($collections[$i]['DepositId']);
					$collections[$i]['image'] = $deposit_details[0]['Image'];
				}else{
					$collections[$i]['image'] = '';
				}
				
			}
		}
		$size = sizeof($collections);
		if($size > 0)
			$k = $size -1;
		else
			$k = 0;
		$deposited_collections = $this->ConsoleAdmin_Model->get_latest_dcr_deposits($start_date,$end_date);
		$k = 0;
		if(sizeof($deposited_collections) > 0){
			for($i=0;$i<sizeof($deposited_collections);$i++){
				$collections[$k]['StoreBranchName'] = $deposited_collections[$i]['StoreBranchName'];
				$branch_details = $this->ConsoleAdmin_Model->get_branch_details_from_branchcode($deposited_collections[$i]['StoreBranchCode']);
				$collections[$k]['State'] = $branch_details[0]['StateName'];
				$collections[$k]['City'] = $branch_details[0]['CityName'];
				$collections[$k]['Brand'] = $branch_details[0]['BrandDescription'];
				$collections[$k]['Fabricaredate'] = date('d-m-Y',strtotime($deposited_collections[$i]['Date']));
				$collections[$k]['cash_settlement_in_fabricare'] = $this->ConsoleAdmin_Model->get_datewise_tobe_collected_amount(date('Y-m-d',strtotime($deposited_collections[$i]['Date'])),$deposited_collections[$i]['StoreBranchCode']);
				$collections[$k]['total_cash_tobe_collected_till_date'] = $this->ConsoleAdmin_Model->get_last_two_month_tobe_collected($collections[$i]['Fabricaredate'],$deposited_collections[$i]['StoreBranchCode']);
				$collections[$k]['DateFrom'] = date('d-m-Y',strtotime($deposited_collections[$i]['DateFrom']));
				$collections[$k]['DateTo'] = date('d-m-Y',strtotime($deposited_collections[$i]['DateTo']));
				//$collections[$k]['Difference_to_collect'] = $deposited_collections[$i]['TotalAmount'] - $deposited_collections[$i]['CollectedAmount'];
				$collections[$k]['Difference_to_collect'] = $collections[$k]['total_cash_tobe_collected_till_date'] - $deposited_collections[$i]['CollectedAmount'];
				$collections[$k]['IsDeposited'] = 1;
				$collections[$k]['TotalAmount'] = $deposited_collections[$i]['TotalAmount'];
				$collections[$k]['CollectedAmount'] = $deposited_collections[$i]['CollectedAmount'];
				$collections[$k]['Name'] = $deposited_collections[$i]['Name'];
				$deposit_details = $this->ConsoleAdmin_Model->get_deposit_details_from_id($deposited_collections[$i]['DepositId']);
				$collections[$k]['image'] = $deposit_details[0]['Image'];
				$k++;
			}
		}
		$final_data = $this->generic->final_data('DATA_FOUND');
		$final_data['data'] =  $collections;
		$this->generic->json_output($final_data);
	}
}

