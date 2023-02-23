<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		parent::__construct();
		//loading URL helper
		$this->load->helper('url');
		/*Loading DCR_Model for Database Operations*/
		$this->load->model('Survey_Model');


		define('KEY','HOjjb$#I3RT2nxp4235dweXW2%%');


		$is_testing = FALSE;
		define("IS_TESTING", $is_testing);

		//Current environment. This can be UAT/Live/QA.
		$environment = 'LIVE';

		define("CURRENT_ENVIRONMENT", $environment);

		//Defining the DB IP address based on current environment.
		if (CURRENT_ENVIRONMENT == 'QA') {
			$db_server_ip = '192.168.5.28';
		} else {
			$db_server_ip = '192.168.5.78';
		}


		//Setting the Database for Live/UAT.
		if (IS_TESTING) {
			$server_db = 'JFSL_UAT';
			$local_db = 'Mobile_JFSL_UAT';
		} else {
			$server_db = 'JFSL';
			$local_db = 'Mobile_JFSL';
		}

		//Defining the global variables
		define("LOCAL_DB", $local_db);
		define("SERVER_DB", $server_db);


		
		

	}

	/**
	 * Gateway function for the survey.
	 * @param $customer_id
	 * @param $checksum
     */
	public function gateway($customer_id=FALSE,$checksum=FALSE)
	{

		if($customer_id&&$checksum) {
			/*Getting the customer details based on the customer id.*/
			$customer_details = $this->Survey_Model->get_customer_details($customer_id);


			if ($customer_details) {

				/*Validating the checksum based on name,email and phone number.*/
				$name = $customer_details['name'];
				$email = $customer_details['email'];
				$mobile_number = $customer_details['mobile_number'];
				$hmac = hash_hmac('sha256', $name . $email . $mobile_number, KEY);
				if ($checksum == $hmac) {
					/*Valid customer*/

					$this->survey($customer_id);
				} else {
					/*Invalid customer*/
					echo 'Invalid request.';
				}
			}else {
				/*Invalid customer*/
				echo 'Invalid request.';
			}
		}else {
			/*Invalid request*/
			echo 'Invalid request.';
		}

	}

	/**
	 * Creating a hmac hash for a customer.
	 * @param bool $customer_id
     */
	public function hash($customer_id=FALSE){

		echo $customer_id;

		/*Getting the customer details based on the customer id.*/
		$customer_details=$this->Survey_Model->get_customer_details($customer_id);
		/*Validating the checksum based on name,email and phone number.*/
		$name=$customer_details['name'];
		$email=$customer_details['email'];
		$mobile_number=$customer_details['mobile_number'];
		$hmac = hash_hmac('sha256', $name.$email.$mobile_number,KEY);
		echo $hmac;
	}

	/**
	 * Survey for the valid customer. Only valid calls access this method.
	 * @param $customer_id
     */
	private function survey($customer_id){


		/*Getting the current step of the customer.*/
		$step=$this->get_current_step($customer_id);
		/*Getting the customer details based on the customer id.*/
		$customer_details=$this->Survey_Model->get_customer_details($customer_id);


		if($step==0 || $step==FALSE){

			$questions=$this->questions();
			/*First question is the current question that needs to be asked.*/
			$current_question=$questions[1]['question'];
		}else {

			/*Getting all the previous answers if any.*/
			$answer_history=$this->Survey_Model->get_answer_history($customer_id);
			$answer_history=json_decode($answer_history['Data'],TRUE);



			/*Loading all the questions*/
			$questions = $this->questions();

			/*Switching based on steps*/
			switch($step['Step']){

				case 1:
					/*Latest answer*/
					$previous_answer=$answer_history['A'.$step['Step']];
					if($previous_answer>=7){
						$current_question=$questions[2]['question'];
						break;
					}else{
						$current_question=$questions[3]['question'];
						break;
					}
				case 2:
					/*Latest answer*/
					$previous_answer=$answer_history['A'.$step['Step']];

					break;

				default:
					return FALSE;

			}

		}
			$data=array('current_question'=>$current_question,'step'=>$step,'customer_details'=>$customer_details);


			/*Loading the view*/
			$this->view($data);

	}

	/**
	 *Set of questions
     */
	private function questions(){

		$questions[0]=NULL;//Skipping the first index.
		$questions[1]['question']="Based on the recent wash experience, how likely are you to recommend our service to your family and friends?";
		$questions[1]['positive']=2;
		$questions[1]['negative']=2;
		$questions[1]['neutral']=2;

		$questions[2]['question']="Please rate us on the following";
		$questions[2]['positive']=FALSE;
		$questions[2]['negative']=3;
		$questions[2]['neutral']=3;

		$questions[3]['question']="Your response didn't match our expectations. Please verify";
		$questions[3]['positive']=FALSE;
		$questions[3]['negative']=FALSE;
		$questions[3]['neutral']=FALSE;

		return $questions;

	}

	/**
	 * Getting the current step to be performed
	 * @param $customer_id
	 * @return mixed
     */
	private function get_current_step($customer_id){
		$step=$this->Survey_Model->get_current_step($customer_id);
		return $step;
	}

	/**
	 *A public method to save the answer
     */
	public function answer(){

	

		$request=$this->input->post();
		$question_number=$request['question_number'];
		$answer=$request['answer'];
		$customer_id=$request['customer_id'];

		/*Getting all the previous answers if any.*/
		$answer_history=$this->Survey_Model->get_answer_history($customer_id);


		if(!$answer_history){
			$answer_data=array('A'.$question_number=>$answer);
			$status=$this->Survey_Model->insert_answer($customer_id,json_encode($answer_data));
		}else {
			/*Getting the previous answer history*/
			$answer_data = json_decode($answer_history['Data'], TRUE);

			/*Appending the new answer to the array*/
			$answer_data['A' . $question_number] = $answer;

			/*Encoding the final json object.*/
			$new_answer_history = json_encode($answer_data);

			$status = $this->Survey_Model->update_answer_history($customer_id, $new_answer_history);
		}
			if($status){
				$data=array('status'=>'success');
			}else{
				$data=array('status'=>'failed');
			}

			echo json_encode($data);

		}

	private function view($data){



		$this->load->view('Survey/Base/header',$data['customer_details']);
		$this->load->view('Survey/Page/page',$data);
		$this->load->view('Survey/Base/footer');

	}


}
