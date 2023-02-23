<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FS_API extends CI_Controller {

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

	/*FABRICSPA WEBSITE API CONTROLLER*/
	function __construct()
	{
		parent::__construct();

        /*Loading Form helper*/
        $this->load->helper('form');
        /*Loading Session library*/
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('file');
        $this->load->helper('captcha');
        $this->load->helper('directory');
        $this->load->library("pagination");
        //loading URL helper
        $this->load->helper('url');    
        $this->load->model('FS_API_Model');

        $is_testing = $this->config->item('is_testing');
        define("IS_TESTING", $is_testing);


        define("BRAND_CODE", "PCT0000001");
        define("ADMIN", $this->session->userdata('admin'));

        define("API_KEY","ONO23Ohx34)(*&T2xjnr#HIFIIHxw");


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


       $request=$this->input->post();

       if($request['API_KEY'] !=API_KEY){
        echo 'invalid';
        exit();

    }
}




 /**
     *For getting prices for each stores by passing branch code.
     */
 public function get_storewise_prices()
 {

 	$branch_code = $this->input->post('BranchCode');

 	$prices = $this->FS_API_Model->get_storewise_prices_sp($branch_code);

 	if($prices){
 		echo json_encode($prices);
 	}
 	else
 		return FALSE;

 }

	 /**
     *For getting all the cities along with the city name and city code of the currently active stores
     */
	 public function get_store_cities()
	 {


	 	$brand_code = BRAND_CODE;

	 	$cities = $this->FS_API_Model->get_stores_cities_sp($brand_code);

        //Manipulating the results for changing the first letters to capitals
	 	for ($i = 0; $i < sizeof($cities); $i++) {
	 		$cities[$i]['CITYNAME'] = ucwords(strtolower($cities[$i]['CITYNAME']));
	 	}

	 	if ($cities)
	 		echo json_encode($cities);
	 	else
	 		return FALSE;

	 }

    /**
     *Getting currently active stores from stored procedure
     */
      public function get_stores()
    {

    	$city_code = $this->input->post('CityCode');
        if($city_code == ''){
            $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
            $body = file_get_contents('php://input', FALSE, $context);
            $body = json_decode($body, TRUE);
            // print_r($body);
            //$this->output->enable_profiler(TRUE);
		    $city_code=$body['CityCode'];
            // exit();
        }
    	$brand_code = BRAND_CODE;
    	$stores = $this->FS_API_Model->get_stores_sp($city_code, $brand_code);

            //Manipulating the results for changing the first letters to capitals
    	for ($i = 0; $i < sizeof($stores); $i++) {
    		if (in_array($stores[$i]['BRANCHCODE'], $this->block_stores()) == FALSE) {
    			//$stores[$i]['BRANCHNAME'] = ucwords(strtolower($stores[$i]['BRANCHNAME']));
    			//$stores[$i]['BRANCHADDRESS'] = ucwords(strtolower($stores[$i]['BRANCHADDRESS']));
                $map_link = $this->FS_API_Model->get_location_link($stores[$i]['CONTACTNUMBER']);
                $size=sizeof($map_link);
                for($j="0";$j<$size;$j++)
                {
                    $link=$map_link[$j]['map'];
                   
                }
                $stores[$i]['GOOGLEMAPLINK']=$link;
    		} else {
                    //Removing the store is that branch codes falls into the blocked criteria. Array will be re-indexed here.
    			array_splice($stores, $i, 1);
    		}
    	}
    	if ($stores) {
    		echo json_encode($stores);
    	} else {
    		return FALSE;

    	}

    }

     /**
     *A private method for blocking stores based on custom constraints.
     */
     private function block_stores()
     {
     	$blocked_branch_codes = array('SPEC000001');
     	return $blocked_branch_codes;
     }

     public function new_post_essentials(){
     	$new_post_essentials = $this->FS_API_Model->new_post_essentials();

     	echo json_encode($new_post_essentials);
     }

     public function load_post_details(){
     	$id=$this->input->post('post_id');
     	$result = $this->FS_API_Model->load_post_details($id);
     	echo json_encode($result);
     }

     public function get_post_id(){
     	$slug_or_id=$this->input->post('slug_or_id');
     	$post_id_row = $this->FS_API_Model->get_post_id($slug_or_id);
     	echo json_encode($post_id_row);
     }

     public function get_next_and_previous_post_links(){
     	$post_id=$this->input->post('post_id');

     	$links=$this->FS_API_Model->get_next_and_previous_post_links($post_id);
     	echo json_encode($links);

     }

     public function get_all_months_for_archives(){

     	$months=$this->FS_API_Model->get_all_months_for_archives();

     	echo json_encode($months);
     }

     public function get_all_posts_for_archive(){
     	$month=$this->input->post('month');
     	$per_page=$this->input->post('per_page');
     	$offset=$this->input->post('offset');
     	$all_posts_essentials = $this->FS_API_Model->get_all_posts_for_archive($month,$per_page,$offset);

     	echo json_encode($all_posts_essentials);
     }

     public function get_all_posts(){
     	$per_page=$this->input->post('per_page');
     	$offset=$this->input->post('offset');
     	$all_posts_essentials = $this->FS_API_Model->get_all_posts($per_page,$offset);
     	echo json_encode($all_posts_essentials);
     }


     public function total_posts_count(){
     	$count=$this->FS_API_Model->total_posts_count();
     	echo json_encode($count);
     }

     public function get_all_posts_with_this_tag(){
     	$tag=$this->input->post('tag');
     	$per_page=$this->input->post('per_page');
     	$offset=$this->input->post('offset');
     	$posts=$this->FS_API_Model->get_all_posts_with_this_tag($tag,$per_page,$offset);
     	echo json_encode($posts);
     }

     public function total_tags(){
     	$tag=$this->input->post('tag');
     	$count=$this->FS_API_Model->total_tags($tag);
     	echo json_encode($count);
     }

     public function get_all_posts_with_this_search(){
     	$search=$this->input->post('search');
     	$per_page=$this->input->post('per_page');
     	$offset=$this->input->post('offset');
     	$all_posts_with_this_search = $this->FS_API_Model->get_all_posts_with_this_search($search,$per_page,$offset);
     	echo json_encode($all_posts_with_this_search);
     }

     public function total_searches(){
     	$search=$this->input->post('search');
     	$count=$this->FS_API_Model->total_searches($search);
     	echo json_encode($count);
     }

     public function total_month_posts(){
     	$month=$this->input->post('month');
     	$posts=$this->FS_API_Model->total_month_posts($month);
     	echo json_encode($posts);
     }

     /*A public method for getting all post details*/
     public function get_posts(){
     	$posts = $this->FS_API_Model->get_posts();
     	echo json_encode($posts);
     }

     public function save_post(){

        

     	$post_date=$this->input->post('post_date');
     	$post_title=$this->input->post('post_title');
     	$post_slug=$this->input->post('post_slug');
     	$post_tags=$this->input->post('post_tags');
     	$post_feature_image=$this->input->post('post_feature_image');
     	$post_excerpt=$this->input->post('post_excerpt');
     	$post_content=$this->input->post('post_content');


        $new_post_data = array(

          'post_date' => $post_date,
          'post_title'    => $post_title,
          'post_slug'  => $post_slug,

          'post_tags'  => $post_tags,

          'post_feature_image'  => $post_feature_image,
          'post_excerpt'  => $post_excerpt,
          'post_content'  => $post_content
      );

        

        $save_status = $this->FS_API_Model->save_post($new_post_data);

      

        return $save_status;

    }

    public function update_post(){

//     	$post_date=$this->input->post('post_date');
      $post_title=$this->input->post('post_title');
      $post_slug=$this->input->post('post_slug');
      $post_tags=$this->input->post('post_tags');
      $post_feature_image=$this->input->post('post_feature_image');
      $post_excerpt=$this->input->post('post_excerpt');
      $post_content=$this->input->post('post_content');

      $post_id=$this->input->post('post_id');

      $post_data = array(

          /*'post_date' => $date,*/
          'post_title'    => $post_title,

          'post_slug' => $post_slug,

          'post_tags'    => $post_tags,

          'post_feature_image'  => $post_feature_image,
          'post_excerpt'  => $post_excerpt,
          'post_content'  => $post_content
      );



      $save_status = $this->FS_API_Model->update_post($post_data,$post_id);

      return $save_status;
  }

  public function  delete_posts(){
      $selected_posts=$this->input->post('selected_posts');
      $selected_posts=json_decode($selected_posts,TRUE);
      $delete_status = $this->FS_API_Model->delete_posts($selected_posts);
      echo json_encode($delete_status);
  }


  public function slug_exists_for_post(){
      $slug=$this->input->post('slug');
      $mode=$this->input->post('mode');
      $slug_exists = $this->FS_API_Model->slug_exists_for_post($slug,$mode);
      echo json_encode($slug_exists);

  }






}
