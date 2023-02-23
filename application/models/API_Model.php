<?php

/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 8/22/2019
 * Time: 11:54 AM
 */
class API_Model extends CI_Model
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
     * Getting the fabricare data of the customer.
     * @param $customer_code
     */
    public function get_qc_customer_link($customer_code)
    {

        $details = $this->db->select('UniqueCode')->from('QC_Customer_Details')->where('CustomerCode', $customer_code)->get()->row_array();
        return $details;
    }

    /**
     * Getting the unique code of the customer.
     * @param $customer_code
     */
    public function get_whatspapp_opt_out_link($customer_code)
    {

        $details = $this->db->select('UniqueCode')->from('WhatsAppDetails')->where('CustomerCode', $customer_code)->get()->row_array();
        return $details;
    }

    /**
     * Saving a newly generated unique code and customer id to the table.
     * @param $new_entry
     */
    public function save_whatspapp_opt_out_link($new_entry)
    {
        $this->db->insert('WhatsAppDetails', $new_entry);
    }


    /**
     * Save image link and corresponding tag no in the QC_Images table.
     * @param $tag_no
     * @param $image_link
     * @return bool
     */
    public function save_image($tag_no, $image_link)
    {

        $data = array('TagNo' => $tag_no, 'Image_URL' => $image_link, 'Source' => 'Fabricare');

        $data_exists = $this->db->select('*')->from('QC_Images')->where($data)->get()->row_array();

        if (!$data_exists) {
            //If no such data is present, insert the data.
            /*Creating unix time stamp*/
            $date = date('Y-m-d H:i:s');
            $data['Date'] = $date;

            $save = $this->db->insert('QC_Images', $data);
        } else {
            //Here file is exists. So saving is also considered as success.
            $save = TRUE;
        }

        return $save;
    }

    /**
     * Getting the all public urls of uploaded images of a tag no.
     * @param $tag_no
     * @return mixed
     */
    public function get_image_links($tag_no)
    {
        $links = $this->db->select('*')->from('QC_Images')->where('TagNo', $tag_no)->order_by('Date', 'Desc')->limit(3)->get()->result_array();
        return $links;
    }

    /**
     * Getting the fabricare data of the customer.
     * @param $customer_code
     */
    public function get_fabricare_customer_details($customer_code)
    {

        $details = $this->db->select('*')->from(SERVER_DB . '..CustomerInfo')->where('CustomerCode', $customer_code)->get()->row_array();
        return $details;
    }

    /**
     * Getting the QC customer details from the QC_Customer_Details table.
     * @param $customer_id
     * @return mixed
     */
    public function get_qc_customer_details($customer_id)
    {
        $customer_details = $this->db->select('*')->from('QC_Customer_Details')->where('CustomerCode', $customer_id)->get()->row_array();
        return $customer_details;
    }

    /**
     * Saving the customer details into the table.
     * @param $customer_details
     * @return mixed
     */
    public function save_customer_details($customer_details)
    {

        $existing_customer_details = $this->db->select('*')->from('QC_Customer_Details')->where('CustomerCode', $customer_details['CustomerCode'])->get()->row_array();
        //If no customer details present, then insert new details.
        if (!$existing_customer_details)
            $result = $this->db->insert('QC_Customer_Details', $customer_details);
        else
            $result = NULL;
        return $result;
    }

    /**
     * Updating the QC_Customer_Details table with shortened URL
     * @param $customer_code
     * @param $shortened_url
     */
    public function update_customer_unique_url($customer_code, $shortened_url)
    {
        $data = array('CustomerUniqueLink' => $shortened_url);
        $this->db->where('CustomerCode', $customer_code)->update('QC_Customer_Details', $data);
    }

    /**
     * Getting the android/ios gcmid of a customer by passing the customer code.
     * @param $mobile_number
     * @return mixed
     */
    public function get_gcmids_of_a_customer($mobile_number)
    {

        $gcmids = $this->db->select('fabricspa_android_gcmid,fabricspa_ios_gcmid')->from('Users')->where('mobile_number', $mobile_number)->get()->row_array();

        return $gcmids;

    }

    /**
     * Getting the list of transactions with the Manual Verify as 1.
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function get_transactions_with_manual_verify($limit, $offset)
    {

        $result = $this->db->limit($limit, $offset)->select('PaymentId')->from('transactionInfo ')->where('ManualVerify', 1)->order_by('TransactionDate', 'desc')->get()->result_array();
        return $result;

    }

    /**
     * Check whether the payment id is settled or not.
     * @param $payment_id
     * @return mixed
     */
    public function check_settlement($payment_id)
    {
        $result = $this->db->select('*')->from('TransactionPaymentInfo')->where(array('PaymentId' => $payment_id, 'InvoiceNo!=' => NULL))->get()->row_array();
        return $result;
    }

    /**
     * Reseting the ManualVerify to 0 in case of failed transactions if any.
     * @param $payment_id
     */
    public function reset_manual_verify($payment_id)
    {
        $data = array('ManualVerify' => 0);
        $this->db->where('PaymentId', $payment_id)->update('TransactionInfo', $data);

    }

    /**
     * For getting store-wise rate card by giving branch code.
     * @param $branch_code
     * @return mixed
     */
    public function get_storewise_prices_sp($branch_code)
    {
        $query = $this->db->query("EXEC " . LOCAL_DB . ".dbo.GetStoresRateCard2 @BranchCode ='" . $branch_code . "'")->result_array();
        return $query;
    }

    /**
     *Getting the posts from the blog
     */
    public function get_blog()
    {

        $posts = $this->db->select('post_date,post_title,post_slug,post_content,post_feature_image')->from('FabricspaBlogPosts')->get()->result_array();
        return $posts;
    }

    /**
     * Getting the nearest 5 stores by passing latitude and longitude.
     * @param $lat
     * @param $long
     * @return mixed
     */
    public function get_nearest_stores($lat, $long)
    {
        $result = $this->db->query("EXEC " . LOCAL_DB . ".dbo.GET_NEAREST_STORES @Latitude='" . $lat . "',@Longitude='" . $long . "'")->result_array();
        return $result;

    }

    /**
     * @param $order_number  EGRNNo/DCNo/Monthly Invoice No
     */
    public function customer_details_from_order_number($order_number)
    {
        $customer_details = $this->db->query("EXEC " . SERVER_DB . ".dbo.GetCustomerDataForPaymentCompletion @NO='" . $order_number . "'")->row_array();
        return $customer_details;
    }

    /**
     * Checking whether a payment link exists for a given EGRN or not.
     * @param $egrn
     * @return mixed
     */
    public function link_check($egrn)
    {
        $link_check = $this->db->select('*')->from('PaymentLinks')->where('EGRN', $egrn)->get()->row_array();
        return $link_check;
    }


    /**
     * Saving the newly generated short url for the payment link.
     * @param $egrn
     * @param $cipher
     * @param $customer_code
     * @param $short_url
     */
    public function save_payment_link($egrn, $cipher, $customer_code, $short_url,$source_from=FALSE)
    {
        $data = array('EGRN' => $egrn, 'Cipher' => $cipher, 'CustomerCode' => $customer_code, 'ShortURL' => $short_url, 'SourceFrom' => $source_from);
        $result = $this->db->insert('PaymentLinks', $data);
    }


   /**
     * Checking whether a short url exists for a given Invoice no or not.
     * @param $egrn
     * @return mixed
     */
    public function is_url_exists($invoice_no)
    {
        $url_check=$this->db->select('*')->from('Invoice_Links')->where('InvoiceNo',$invoice_no)->get()->row_array();
        return $url_check;
    }


   public function save_short_url($cipher,$customer_code,$invoice_no,$short_url)
    {
        $data = array('Cipher' => $cipher, 'CustomerCode' => $customer_code, 'Invoiceno' => $invoice_no,'ShortURL' => $short_url);
        $result = $this->db->insert('Invoice_Links', $data);
    }

}