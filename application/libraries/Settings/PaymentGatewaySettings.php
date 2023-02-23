<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 9/4/2019
 * Time: 1:35 PM
 */



/*Payment Gateway settings file*/
class PaymentGatewaySettings{

    //Protected variable for Codeigniter object.
    protected $CI;
    private $trusted_origins;

    function __construct()
    {
        //Setting up CI object in order to manipulate CI methods/features.
        $this->CI =& get_instance();


        /*Payment gateway credentials based on IS_TESTING*/
        if (IS_TESTING) {
            $mid = 'b19e8f103bce406cbd3476431b6b7973';
            $secret_key = '0678056d96914a8583fb518caf42828a';

            $pg_url = 'http://zaakpaystaging.centralindia.cloudapp.azure.com:8080/api/paymentTransact/V8';
            if (CURRENT_ENVIRONMENT == 'QA') {
                $pg_response_url_mobile = 'https://app.fabricspa.com/UAT2/Web_Controller/mobile_pg_response';
                $pg_response_url_web = 'https://app.fabricspa.com/UAT2/Web_Controller/web_pg_response';
            } else {
                $pg_response_url_mobile = 'https://app.fabricspa.com/UAT/Web_Controller/mobile_pg_response';
                $pg_response_url_web = 'https://app.fabricspa.com/UAT/Web_Controller/web_pg_response';
            }
        } else {
            $mid = '5b1989b4346942089f80295c560ed1f9';
            $secret_key = '5a31b174c2104ba2a020d5b75173aa11';
            $pg_url = 'https://api.zaakpay.com/api/paymentTransact/V8';
            $pg_response_url_mobile = 'https://app.fabricspa.com/Web_Controller/mobile_pg_response';
            $pg_response_url_web = 'https://app.fabricspa.com/Web_Controller/web_pg_response';
        }
        //Defining the global variables
        define("MERCHANT_IDENTIFIER", $mid);
        define("SECRET_KEY", $secret_key);
        define('PAYMENT_GATEWAY_URL', $pg_url);
        define('PAYMENT_GATEWAY_RESPONSE_URL_MOBILE', $pg_response_url_mobile);
        define('PAYMENT_GATEWAY_RESPONSE_URL_WEB', $pg_response_url_web);

        $status_descriptions = ['Fraud Detected', 'MerchantIdentifier field missing or blank', 'MerchantIdentifier not valid', 'OrderId field missing or blank', 'Mode field missing or blank', 'Mode received with request was not valid', 'Checksum received with request is not equal to what we calculated', 'Merchant Data not complete in our database', 'Checksum was blank', 'OrderId either not processed or Rejected', 'Merchant Identifier or Order Id was not valid', 'We could not find this transaction in our database', 'Transaction in Scheduled state', 'Transaction in Initiated state', 'Transaction in Processing state', 'Transaction has been authorized', 'Transaction has been put on hold', 'Transaction is incomplete', 'Transaction has been settled', 'Transaction has been cancelled', 'Data Validation success', 'Transaction has been captured', 'Transaction Refund Completed', 'Transaction Payout Initiated', 'Transaction Payout Completed', 'Transaction Payout Error', 'Transaction Refund Paid Out', 'Transaction Chargeback has been initiated', 'Transaction Chargeback is being processed', 'Transaction Chargeback has been accepted', 'Transaction Chargeback has been reverted', 'Transaction Chargeback revert is now complete', 'Transaction Refund Initiated', 'Your Bank has declined this transaction, please Retry this payment with another pay method','Transaction Refund Before Payout Completed'];

        //define('ZAAKPAY_STATUS_DESCRIPTIONS',$status_descriptions);
          /********
         *PAYTM SETTINGS
         *
         *
         ***********/
        if(IS_TESTING){
            $paytm_environment='TEST';
            $paytm_secret_key='hxIemOODx#r2UG9v';
            $paytm_mid='Jyothy14339901439242';
            $paytm_url='https://securegw-stage.paytm.in/theia/processTransaction';
            $paytm_website='WEBSTAGING';
      
        }else{
            $paytm_environment='PROD';
            $paytm_secret_key='0VctxU!w1qwOKF8V';
            $paytm_mid='Jyothy95729679751975';
            $paytm_url='https://securegw.paytm.in/order/process';
            $paytm_website='APPPROD';
        }
        define('PAYTM_ENVIRONMENT', $paytm_environment); // PROD
        define('PAYTM_SECRET_KEY', $paytm_secret_key); //Change this constant's value with Merchant key received from Paytm.
        define('PAYTM_MERCHANT_IDENTIFIER', $paytm_mid); //Change this constant's value with MID (Merchant ID) received from Paytm.
        define('PAYTM_MERCHANT_WEBSITE', $paytm_website); //Change this constant's value with Website name received from Paytm.
      /*  if (PAYTM_ENVIRONMENT == 'PROD') {
            $PAYTM_TXN_URL = 'https://securegw.paytm.in/theia/processTransaction';
        } else {
            $PAYTM_TXN_URL = 'https://securegw-stage.paytm.in/theia/processTransaction';
        }
        */
        define('PAYTM_PAYMENT_GATEWAY_URL', $paytm_url);
        //$PAYTM_STATUS_QUERY_NEW_URL = 'https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
        //$PAYTM_STATUS_QUERY_NEW_URL = 'https://securegw.paytm.in/merchant-status/getTxnStatus'; -- LIVE URL
        /*
        define('PAYTM_REFUND_URL', '');
        define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
        define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);*/
    }

}