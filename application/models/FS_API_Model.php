<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class FS_API_Model extends CI_Model
{
    /**
     *Constructor, use for globally loading libraries, variables, helpers
     */
    function __construct()
    {
        parent::__construct();

        // loading database
        $this->load->database();

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
     * For getting store-wise rate card by giving branch code.
     * @param $branch_code
     * @return mixed
     */
    public function get_storewise_prices_sp($branch_code)
    {
        $query = $this->db->query("EXEC " . LOCAL_DB . ".dbo.GetStoresRateCard @BranchCode ='" . $branch_code . "'")->result_array();
        // print_r("EXEC " . LOCAL_DB . ".dbo.GetStoresRateCard @BranchCode ='" . $branch_code . "'");
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
     * For getting googlemaplink of each stores by passing contact number
     *  @param $contact_number
     */
   public function get_location_link($branch_code)
    {
        $query=$this->db->select('map')->from('Stores')->where('BranchCode',$branch_code)->get()->result_array();
        return $query;
    }
    /*POST SECTION*/



    /*A public function for getting all the essential details for the new post editor page.*/
public function new_post_essentials(){



  $tags = $this->db->select('id,tag')->from('FabricspaBlogTags')->get()->result_array();

 

  $essentials = array(

   
    'tags'  => $tags,
 

  );
  return $essentials;

}


/*A public method for saving a post*/
public function save_post($post_data){
  $status = $this->db->insert('FabricspaBlogPosts',$post_data);
  return $status;
}

/*A public method for updating a post.*/
public function update_post($post_data,$id){
 $this->db->where('id',$id);
 $updated_status = $this->db->update('FabricspaBlogPosts',$post_data);
 return $updated_status;
}


/*A public method for deleting selected post(s)*/
public function delete_posts($posts){

  $delete_status = $this->db->where_in('id',$posts)->delete('FabricspaBlogPosts');
  return $delete_status;

}

//Loading posts in the admin page
public function get_posts(){
  $posts = $this->db->select('p.id,p.post_title,p.post_slug')->from('FabricspaBlogPosts p')->order_by('post_date','desc')->get()->result_array();
  return $posts;
}

    //Loading posts in profile page
public function load_posts($author_id,$per_page=FALSE,$offset=FALSE){

 $result = $this->db->limit($per_page,$offset)->select('p.id,p.post_date,p.post_title,p.post_slug,a.author_name,a.author_name_ml,p.post_feature_image,p.post_excerpt')->from('FabricspaBlogPosts p')->where('post_author_id',$author_id)->join('authors a','p.post_author_id = a.author_id')->get()->result_array();
 

 return $result;
}



//Loading all the post details in the individual Post page.
public function load_post_details($post_id){
 $result = $this->db->select('p.id,p.post_title,p.post_date,p.post_content,p.post_excerpt,p.post_tags,p.post_feature_image,p.post_feature_image')->from('FabricspaBlogPosts p')->where('id',$post_id)->get()->row_array();


 return $result;
}

//Getting the previous and next post_slugs(if any) based on a post id
function get_next_and_previous_post_links($post_id){

  $next_post_link=$this->db->query('select post_slug from FabricspaBlogPosts where id = (select min(id) from FabricspaBlogPosts where id > '.$post_id.')')->row_array();
  $previous_post_link=$this->db->query('select post_slug from FabricspaBlogPosts where id = (select max(id) from FabricspaBlogPosts where id < '.$post_id.')')->row_array();
  $data=array('next'=>$next_post_link,'previous'=>$previous_post_link);
  return $data;
}

//Getting all the essential data for the public posts page.

public function get_all_posts($per_page,$offset){

  $result = $this->db->limit($per_page,$offset)->select('p.id,p.post_date,p.post_title,p.post_slug,p.post_excerpt,p.post_tags,p.post_feature_image,p.post_excerpt')->from('FabricspaBlogPosts p')->order_by('p.post_date','desc')->get()->result_array();

  return $result;

}

public function get_all_months_for_archives(){
  $months=$this->db->distinct()->select('datename(month, post_date) as month')->from('FabricspaBlogPosts')->where('datename(month, post_date)!=',NULL)->get()->result_array();
  return $months;
}

public function get_all_posts_for_archive($month,$per_page,$offset){

  $result = $this->db->limit($per_page,$offset)->select('p.id,p.post_date,p.post_title,p.post_slug,p.post_excerpt,p.post_tags,p.post_feature_image,p.post_excerpt')->from('FabricspaBlogPosts p')->where('datename(month, post_date)=',$month)->order_by('p.post_date','desc')->get()->result_array();

  return $result;

}

/*A public method for getting total number of posts */
public function total_posts_count(){
  $count=$this->db->count_all_results('FabricspaBlogPosts');

  return $count;
}

/*A public method for getting total number of posts */
public function total_month_posts($month){
  $count=$this->db->where('datename(month, post_date)=',$month)->count_all_results('FabricspaBlogPosts');

  return $count;
}

//Returns ID of the post after receiving the URL slug
public function get_post_id($slug){
  $row = $this->db->select('id')->from('FabricspaBlogPosts')->where('post_slug',$slug)->get()->row_array();
  return $row;
}

//A function to check whether the slug is already exists/registered or not.
public function slug_exists_for_post($slug,$mode){
  $status = $this->db->select('*')->from('FabricspaBlogPosts')->where('post_slug',$slug)->get()->result_array();
  $total = sizeof($status);
  $next = $total+1;
  if($status&&$mode=='save')
    return $slug.'-'.$next;
  else
    return $slug;
}

//Load posts for calendaring the posts in profile page
public function get_posts_calendar_years($username){
  $author_id = $this->get_author_id($username);
  $years = $this->db->select('EXTRACT(YEAR FROM p.post_date) as calendar_year')->distinct()->from('posts p')->join('authors a','p.post_author_id = a.author_id')->where(array('a.author_id'=>$author_id['author_id']))->order_by('EXTRACT(YEAR FROM p.post_date) asc')->get()->result_array();
  
  //Equivalent query is below.

  //$years = $this->db->query('select distinct EXTRACT(YEAR FROM p.post_date) as calendar_year from posts p join authors a on p.post_author_id=a.author_id where a.author_id='.$author_id['author_id'].' order by EXTRACT(YEAR FROM p.post_date) asc')->result_array();
  
  return $years;
}

public function get_posts_calendar_months($username,$year){
  $author_id = $this->get_author_id($username);
  $months = $this->db->select('EXTRACT(MONTH FROM p.post_date) as month_index')->distinct()->from('posts p')->join('authors a','p.post_author_id = a.author_id')->where(array('YEAR(p.post_date)='=>$year,'a.author_id'=>$author_id['author_id']))->order_by('EXTRACT(MONTH FROM p.post_date) asc')->get()->result_array();
  //Equivalent query is below.

  //$months=$this->db->query('select distinct EXTRACT(MONTH FROM p.post_date) as month_index from posts p join authors a on p.post_author_id=a.author_id where YEAR(p.post_date)='.$year.' and a.author_id='.$author_id['author_id'].' order by EXTRACT(MONTH FROM p.post_date) asc')->result_array();
  
  return $months;
}

//Getting post details from a calendar month
public function get_posts_details_from_a_calendar_month($username,$year,$month){
  $author_id = $this->get_author_id($username);
  $posts=$this->db->select('p.id,p.post_title,p.post_slug')->from('posts p')->join('authors a','p.post_author_id = a.author_id')->where(array('YEAR(p.post_date)='=>$year,'MONTH(p.post_date)'=>$month,'a.author_id'=>$author_id['author_id']))->order_by('p.post_date asc')->get()->result_array();
  return $posts;
}


//Loading sub categories at the add/edit post window
public function load_sub_categories($category){

  $row = $this->db->select('id')->from('categories')->where('category',$category)->get()->row_array();
  $sub_categories = $this->db->select('*')->from('sub_categories')->where('category_id',$row['id'])->get()->result_array();
  return $sub_categories;
}

//Getting all the post related to the tag
public function get_all_posts_with_this_tag($tag,$per_page,$offset){

  $res =  $result = $this->db->limit($per_page,$offset)->select('p.id,p.post_date,p.post_title,p.post_slug,p.post_excerpt,p.post_tags,p.post_feature_image,p.post_excerpt')->from('FabricspaBlogPosts p')->like('p.post_tags',$tag)->order_by('p.post_date','desc')->get()->result_array();

  return $res;

}


/*A public method for getting total number of tags */
public function total_tags($tag){
  $count=$this->db->like('post_tags',$tag)->from('FabricspaBlogPosts')->count_all_results();
  return $count;
}


//Getting all the post related to the tag
public function get_all_posts_with_this_search($search,$per_page,$offset){

  $res =  $result = $this->db->limit($per_page,$offset)->select('p.id,p.post_date,p.post_title,p.post_slug,p.post_excerpt,p.post_tags,p.post_feature_image,p.post_excerpt')->from('FabricspaBlogPosts p')->like('p.post_content',$search)->order_by('p.post_date','desc')->get()->result_array();

  return $res;

}


/*A public method for getting total number of tags */
public function total_searches($search){
  $count=$this->db->like('post_content',$search)->from('FabricspaBlogPosts')->count_all_results();
  return $count;
}

/**/


}