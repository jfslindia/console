<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class QC_SUI_Interface
 * @property QC_SUI_Model $QC_SUI_Model
 * @property generic $generic
 */
class QC_SUI_Interface extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
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

        $this->load->model('QC_SUI_Model');
        $this->load->helper('url');
        $this->load->helper('form');

        //Loading the project settings
        $this->load->library('Settings/ProjectSettings');

        //Loading the Generic Functions
        $this->load->library('GenericFunctions/Generic');

    }


    public function index($code = FALSE, $function = FALSE)
    {

        if (isset($code)) {

            // if (strlen($code) == 8) {

                $code_validity = $this->QC_SUI_Model->check_validity($code);

                if ($code_validity)
                    redirect(base_url() . 'sui/' . $code . '/logs');
                else
                    $this->Custom_404();

            // } else {
            //     $this->Custom_404();
            // }
        } else {
            $this->Custom_404();
        }

    }


    //Index function. Checking whether the given code is valid or not.
    public function logs($code = FALSE)
    {

        $code_validity = $this->QC_SUI_Model->check_validity($code);

        if ($code_validity) {
            //Code is valid. show the logs to the user.

            //Getting logs of a particular user.


            $logs = $this->QC_SUI_Model->get_logs($code_validity['CustomerCode']);

            if ($logs) {
                $this->load->view('SUI/base/header');
                $this->load->view('SUI/pages/list', array('logs' => $logs, 'name' => $code_validity['Name'], 'cipher' => $code));
                $this->load->view('SUI/base/footer');
            } else {
                //No logs found.
            }
        } else {
            $this->Custom_404();
        }
    }

    //Loading a log detail.
    public function log($tag_no = FALSE)
    {

        if ($tag_no) {

            $given_cipher = $this->uri->segment(2, 0);

            $log_data = $this->QC_SUI_Model->get_log_data($tag_no);

            $cipher = $this->QC_SUI_Model->get_cipher_code($log_data[0]['CustomerCode']);

            $garment_details = $this->QC_SUI_Model->get_garment_details($tag_no);

            $images = $this->QC_SUI_Model->get_image_links($tag_no);


            if ($given_cipher == $cipher['UniqueCode']) {

                $this->load->view('SUI/base/header', array('cipher' => $cipher['UniqueCode']));
                $this->load->view('SUI/pages/log', array('garment_details' => $garment_details, 'log_data' => $log_data[0], 'cipher' => $cipher, 'tag_no' => $tag_no, 'images' => $images));
                $this->load->view('SUI/base/footer');
            } else {
                $this->Custom_404();
            }


        } else {
            $this->Custom_404();
        }
    }


    //Saving customer response
    public function save_response($cipher = FALSE)
    {


        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $post_response = json_decode($rawdata, TRUE);


        $response = $post_response['response'];
        //$detailed_response=$this->clean_string((string)$post_response['detailed_response']);
        $detailed_response = '';

        $tag_no = $post_response['tag_no'];
        $customer_id = $post_response['customer_id'];

        if (strtoupper($response) == 'APPROVE') {
            $customer_response = 2;
        } else if (strtoupper($response) == 'REJECT') {
            $customer_response = 1;
        } else {
            $customer_response = 3;
        }

        $log_data = $this->QC_SUI_Model->get_log_data($tag_no);
        $cipher = $this->QC_SUI_Model->get_cipher_code($log_data[0]['CustomerCode']);

        //Update the QC status in the Fabricare.
        if ($log_data[0]['QCStatus'] == 'QCPending') {
            if (in_array('ImageLink1', $log_data[0])) {
                $image_link_1 = $log_data[0]['ImageLink1'];
            } else {
                $image_link_1 = NULL;
            }
            if (in_array('ImageLink2', $log_data[0])) {
                $image_link_2 = $log_data[0]['ImageLink2'];
            } else {
                $image_link_2 = NULL;
            }
            if (in_array('ImageLink3', $log_data[0])) {
                $image_link_3 = $log_data[0]['ImageLink3'];
            } else {
                $image_link_3 = NULL;
            }
            
            $fabricare_update_status = $this->QC_SUI_Model->update_qc_status_in_fabricare($tag_no, $customer_id, $customer_response, $log_data[0]['Remarks'], '', $image_link_1, $image_link_2, $image_link_3, $detailed_response);
        } else {
            $fabricare_update_status = NULL;
        }

        if ($fabricare_update_status) {
            $data = $this->generic->final_data('DATA_SAVED');
        } else {
            $data = $this->generic->final_data('DATA_SAVE_FAILED');
        }

        $this->generic->json_output($data);
        exit(0);

    }


    //Method for generating custom 404 error dispaly.
    public function Custom_404()
    {

        $this->load->view('SUI/base/header');
        $this->load->view('SUI/pages/404');
        $this->load->view('SUI/base/footer');
    }

    /**
     *Cleaning the user input string. Stripping all special characters from a given string.
     */
    private function clean_string($string)
    {
        return preg_replace('/[^A-Za-z0-9\-?\'. ]/', '', $string);
    }

}
