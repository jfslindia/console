<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WhatsAppOpt_Controller extends CI_Controller
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

        //loading URL helper
        $this->load->helper('url');

        /*Loading DCR_Model for Database Operations*/
        $this->load->model('WhatsAppOpt_Model');

        $this->load->library('session');

        //Loading the project settings
        $this->load->library('Settings/ProjectSettings');

        //Loading the Generic Functions
        $this->load->library('GenericFunctions/Generic');

        $this->load->library('DBKit/DBKit');


        define('QC_LINK_API_KEY', 'a_0_=+Se3de25fD#@$Fcd+=');
        define('WHATSAPP_Opt_API_KEY', 'ha7Qoh^&73jxIO3OUWxi2');

    }

    public function index($unique_code = FALSE)
    {
        if ($unique_code) {

            $details = $this->WhatsAppOpt_Model->get_details($unique_code);

            if ($details) {

                if (!$details['Opt']) {
                    //$details['Opt'] = 0;
                    $customer_details=$this->WhatsAppOpt_Model->get_customer_details($details['CustomerCode']);
                    if($customer_details){
                        if(array_key_exists('IsOptIn',$customer_details)){
                            $details['Opt']=$customer_details['IsOptIn'];
                        }else{
                            $details['Opt']=0;
                        }
                    }
                }

                $data = array('details' => $details);

                $this->load->view('WhatsAppOpt/Base/header');
                $this->load->view('WhatsAppOpt/Pages/Opt', $data);
                $this->load->view('WhatsAppOpt/Base/footer');
            } else {

                $data=$this->generic->final_data('DATA_NOT_FOUND');
                $this->generic->json_output($data);
            }

        } else {
            $data=$this->generic->final_data('INVALID_REQUEST');
            $this->generic->json_output($data);
        }
    }

    public function Opt()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);

        $customer_id = $body['customer_id'];
        $opt = $body['opt'];

        $update_data = $this->WhatsAppOpt_Model->update_opt($customer_id, $opt);

        //Updating the Fabricare the opt in/opt out data.
        if($update_data)
            $update_data=$this->WhatsAppOpt_Model->update_opt_in_fabricare($customer_id,$opt);

        if ($update_data) {
            $data = $this->generic->final_data('DATA_UPDATED');
            $this->generic->json_output($data);
        } else {
            $data = $this->generic->final_data('DATA_UPDATE_FAILED');
            $this->generic->json_output($data);

        }
    }
}