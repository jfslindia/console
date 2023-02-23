<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 8/24/2019
 * Time: 11:36 AM
 */

//Generic Functions file.
//Some common functions are defined and can be called on various circumstances.
class Generic
{

    //Protected variable for Codeigniter object.
    protected $CI;

    public function __construct()
    {
        //Setting up CI object in order to manipulate CI methods/features.
        $this->CI =& get_instance();

    }

    /**
     * Parsing the json request
     * @return mixed
     */
    public function json_input()
    {
        /*Reading data from the body of the POST request,
        data is in json. It is decoded and stored in $body*/
        $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
        $rawdata = file_get_contents('php://input', FALSE, $context);
        $body = json_decode($rawdata, TRUE);
        return $body;
    }

    /**
     * Formatting and outputting the data in JSON format.
     * @param $data
     */
    public function json_output($data)
    {


        /*$this->CI->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));*/
        $this->CI->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }


    /**
     * Generic API status codes.
     * @param $status_code -- Common static string
     * @param $key -- Request key (Just in case No key found status happens)
     * @param $message
     * @return array
     */
    public function final_data($status_code, $key = FALSE, $message = FALSE)
    {

        switch ($status_code) {
            case 'DATA_FOUND':
                $status = array('status' => 'success', 'status_code' => '100', 'message' => 'Data retrieved successfully.');
                break;
            case 'DATA_NOT_FOUND':
                $status = array('status' => 'failed', 'status_code' => '101', 'message' => 'No data found.');
                break;
            case 'DATA_SAVED':
                $status = array('status' => 'success', 'status_code' => '102', 'message' => 'Saved successfully.');
                break;
            case 'DATA_SAVE_FAILED':
                $status = array('status' => 'failed', 'status_code' => '103', 'message' => 'Saving failed. Please try again later.');
                break;
            case 'DATA_UPDATED':
                $status = array('status' => 'success', 'status_code' => '104', 'message' => 'Updated successfully.');
                break;
            case 'DATA_UPDATE_FAILED':
                $status = array('status' => 'failed', 'status_code' => '105', 'message' => 'Update failed. Please try again later.');
                break;
            case 'DATA_DELETED':
                $status = array('status' => 'success', 'status_code' => '106', 'message' => 'Deleted successfully.');
                break;
            case 'DATA_DELETE_FAILED':
                $status = array('status' => 'failed', 'status_code' => '107', 'message' => 'Delete failed. Please try again later.');
                break;
            case 'INVALID_REQUEST':
                $status = array('status' => 'failed', 'status_code' => '000', 'message' => 'Invalid request.');
                break;
            case 'NO_API_KEY':
                $status = array('status' => 'failed', 'status_code' => '001', 'message' => 'No API key is provided.');
                break;
            case 'INVALID_API_KEY':
                $status = array('status' => 'failed', 'status_code' => '002', 'message' => 'Invalid API key.');
                break;
            case 'NO_KEY_FOUND':
                $status = array('status' => 'failed', 'status_code' => '003', 'message' => "Key '" . $key . "' is not provided.");
                break;
            //Custom 'failed' cases. Generic for no data found, saving failed, empty result...etc
            case 'FAILED':
                $status = array('status' => 'failed', 'status_code' => '004', 'message' => $message);
                break;
            //Custom 'success' cases. Generic for no data found, saving failed, empty result...etc
            case 'SUCCESS':
                $status = array('status' => 'success', 'status_code' => '005', 'message' => $message);
                break;
            default:
                $status = array('status' => 'failed', 'status_code' => '999', 'message' => 'Invalid.');

        }
        return $status;

    }


    /**
     * Generating a cipher code from a keyword
     * @param $keyword
     * @param $repeat_count
     * @param $shuffle_length
     * @param $include_hash
     * @return string
     */
    public function generate_cipher($keyword, $hash_length, $repeat_count, $shuffle_length, $include_hash)
    {

        /*Creating a hash based on MD5 and uniqid*/
        $hash = md5(uniqid($keyword, true));

        /*The last x characters of hash.*/
        $substring_hash = substr($hash, -$hash_length);

        /*A random x character string generator*/
        $shuffled_string = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz", $repeat_count)), 0, $shuffle_length);


        if ($include_hash) {
            /*Final x character unique cipher string*/
            $unique = $shuffled_string . $substring_hash;
        } else {
            $unique = $shuffled_string;
        }

        return $unique;
    }

    /**
     * Creating a hash based on keyword
     * @param $keyword
     * @param $hash_length
     * @return string
     */
    public function generate_hash($keyword, $hash_length)
    {

        /*Hashing techniques for api calls. Output will be fixed.*/

        $hmac = substr(hash_hmac('sha256', $keyword, 'JFSL@123'), 0, $hash_length);

        return $hmac;
    }

    /**
     * Calling the URL shortner API
     * @param $link -- Long link needs to be shortened
     * @return mixed
     */
    public function call_url_shortner_api($link)
    {

        $api_url = "https://fabspa.in/sui/get_link";

        //$api_url=CURRENT_SERVER_IP."x/sui/get_link";

        $post_data = json_encode(array('url' => $link));


        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode(array("url" => $link)),
            CURLOPT_HTTPHEADER => array(

                "Content-Type: application/json",
                "api_key: asdasHWod3(&22XK:Ji2))2j653&",
                "cache-control: no-cache"
            ),
        ));

        $data = curl_exec($ch);
        
        curl_close($ch);

        return json_decode($data, TRUE);
    }


}