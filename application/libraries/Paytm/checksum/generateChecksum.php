<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");
 
$context = stream_context_create(array( 'http' => array( 'header' => 'Connection: close\r\n' ) ));
$rawdata = file_get_contents('php://input', FALSE, $context);
$body = json_decode($rawdata, TRUE);

$checkSum = "";

// below code snippet is mandatory, so that no one can use your checksumgeneration url for other purpose .

$paramList = array();

$paramList["MID"] = 'rcQZOc46701670234165'; //Provided by Paytm
$paramList["ORDER_ID"] =$body['ORDER_ID']; //unique OrderId for every request
$paramList["CUST_ID"] = $body['CUST_ID']; // unique customer identifier 
$paramList["INDUSTRY_TYPE_ID"] = 'Retail'; //Provided by Paytm
$paramList["CHANNEL_ID"] = 'WAP'; //Provided by Paytm
$paramList["TXN_AMOUNT"] =$body['TXN_AMOUNT']; // transaction amount
$paramList["WEBSITE"] = 'WEBSTAGING'; // transaction amount
$paramList["CALLBACK_URL"] = 'https://securegw-stage.paytm.in/theia/paytmCallback?ORDER_ID='.$body['ORDER_ID'];//Provided by Paytm

$checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
$paramList["CHECKSUMHASH"] = $checkSum;

$resultJSON = json_encode($paramList);
print_r($resultJSON );

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>