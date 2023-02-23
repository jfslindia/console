<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = $route['default_controller'] . '/Custom_404';
$route['translate_uri_dashes'] = FALSE;

/***
 * Basic Controller ROUTES
 * */
$route['DCR'] = 'dcr_controller';
$route['api'] = 'api_controller';
$route['Survey'] = 'survey';
$route['console'] = 'console_controller';
$route['sui'] = 'qc_sui_interface';
$route['WAO'] = 'whatsappopt_controller';
$route['camp'] = 'missed_call_campaign';
$route['scheduler'] = 'scheduler';
$route['consoleadmin'] = 'consoleadmin_controller';


/***
 * DCR ROUTES
 **/

$route['DCR/get_cities'] = $route['DCR'] . '/get_cities';
$route['DCR/get_stores'] = $route['DCR'] . '/get_stores';
$route['DCR/login'] = $route['DCR'] . '/login';

//For saving daily collections
$route['DCR/save_daily_collection'] = $route['DCR'] . '/save_daily_collection';

//For saving pending collections only
$route['DCR/save_pending_collection'] = $route['DCR'] . '/save_pending_collection';
//Saving initial pending details
$route['DCR/save_initial_pending_details'] = $route['DCR'] . '/save_initial_pending_details';
$route['DCR/get_collection_details_from_fabricare'] = $route['DCR'] . '/get_collection_details_from_fabricare';
$route['DCR/get_collections'] = $route['DCR'] . '/get_collections';
$route['DCR/get_store_pending'] = $route['DCR'] . '/get_store_pending';
$route['DCR/deposit_collections'] = $route['DCR'] . '/deposit_collections';
$route['DCR/file_upload'] = $route['DCR'] . '/file_upload';
$route['DCR/test'] = $route['DCR'] . '/test';

/****
 * Survey routes
 * */
$route['Survey/gateway/(:any)/(:any)'] = $route['Survey'] . '/gateway/$1/$2';
$route['Survey/hash'] = $route['Survey'] . '/hash';
$route['Survey/hash/(:any)'] = $route['Survey'] . '/hash/$1';


/***
 * ADMIN CONSOLE ROUTES
 * */
$route['console/home'] = $route['console'] . '/home';
$route['console/dashboard'] = $route['console'] . '/admin_dashboard';
$route['console/registrations'] = $route['console'] . '/admin_registration';
$route['console/orders'] = $route['console'] . '/admin_orders';
$route['console/users'] = $route['console'] . '/admin_users';
$route['console/feedbacks'] = $route['console'] . '/admin_feedbacks';
$route['console/offers'] = $route['console'] . '/admin_offers';
$route['console/transactions_to_verify'] = $route['console'] . '/admin_transactions_to_verify';
$route['console/search'] = $route['console'] . '/admin_search_panel';
$route['console/stores'] = $route['console'] . '/admin_store_address';
$route['console/api_preferences'] = $route['console'] . '/admin_api_preferences';
$route['console/send_notifications'] = $route['console'] . '/admin_send_notifications';
$route['console/change_password'] = $route['console'] . '/admin_change_password';
$route['console/dcr'] = $route['console'] . '/admin_dcr';

$route['console/qa_users'] = $route['console'] . '/admin_qa_users';
$route['console/qa_finished_by_users'] = $route['console'] . '/admin_qa_finished_by_users';
$route['console/qa_reasons'] = $route['console'] . '/admin_qa_reasons';

$route['console/qa_logs'] = $route['console'] . '/admin_qa_logs';
$route['console/qa_logs/(:num)'] = $route['console'] . '/admin_qa_logs/$1';

$route['console/qc_logs'] = $route['console'] . '/admin_qc_logs';
$route['console/qc_logs/(:num)'] = $route['console'] . '/admin_qc_logs/$1';

$route['console/qc_log'] = $route['console'] . '/admin_qc_log';
$route['console/qc_log/(:any)'] = $route['console'] . '/admin_qc_log/$1';

$route['console/payment_gateway_center'] = $route['console'] . '/admin_payment_gateway_center';
$route['console/incomplete_transactions'] = $route['console'] . '/admin_incomplete_transactions';
$route['console/appspa_campaign'] = $route['console'] . '/admin_appspa_campaign';

$route['console/decode'] = $route['console'] . '/decode';
$route['console/decode_now'] = $route['console'] . '/decode_now';
$route['console/create'] = $route['console'] . '/admin_creation';
$route['console/create_admin'] = $route['console'] . '/admin_creation_pro';
$route['console/logout'] = $route['console'] . '/logout';
$route['console/login'] = $route['console'] . '/login';

$route['console/test'] = $route['console'] . '/test';
$route['console/test/(:num)'] = $route['console'] . '/test/$1';
$route['console/get_notification_details'] = $route['console'] . '/get_notification_details';
$route['console/cancel_scheduled_notifications/(:num)'] = $route['console'] . '/cancel_scheduled_notifications/$1';
$route['console/notification_download_options'] = $route['console'] . '/notification_download_options';
$route['console/essentialpopups'] = $route['console'] . '/admin_essentialpopup';
$route['console/tips'] = $route['console'] . '/admin_tip';
$route['console/wash_images'] = $route['console'] . '/admin_schedulewash_images';
$route['console/coupons'] = $route['console'] . '/admin_coupons';
$route['console/campaigns'] = $route['console']. '/admin_campaigns';
$route['console/campaign_details/(:num)'] = $route['console'].'/show_campaign_details/$1';
$route['console/delete_campaign/(:num)'] = $route['console'].'/delete_campaign_details/$1';
$route['console/popups'] = $route['console'].'/admin_popup';

$route['console/fab_home'] = $route['console'].'/admin_fab_home';
$route['console/edit_rate/(:num)'] = $route['console']. '/edit_fabhome_rate/$1';
$route['console/fabhome_orders'] = $route['console'].'/admin_fabhome_view_cart';
$route['console/show_fabhome_cart_details/(:num)'] = $route['console'].'/admin_fabhome_view_cartitems/$1';
$route['console/rate_master'] = $route['console'].'/admin_fbhm_rate_master';
$route['console/show_fabhome_rates'] = $route['console']. '/show_fabhome_rates';
$route['console/deactivate_rate/(:num)'] = $route['console']. '/deactivate_fabhome_rate/$1';
$route['console/activate_rate/(:num)'] = $route['console']. '/activate_fabhome_rate/$1';
$route['console/fabhome'] = $route['console']. '/admin_fabhome';




/* NEW UI */
$route['consoleadmin/get_fabhome_orders'] = $route['consoleadmin']. '/get_fabhome_orders';
$route['consoleadmin/fabhome_orders'] = $route['consoleadmin'].'/admin_fabhome_orders';
$route['consoleadmin/update_order_status'] = $route['consoleadmin'].'/update_order_status';
$route['consoleadmin/show_fabhome_cart_details/(:num)'] = $route['consoleadmin'].'/admin_fabhome_view_cartitems/$1';
$route['consoleadmin/essentialpopups'] = $route['consoleadmin'].'/admin_essentialpopup';


$route['consoleadmin/coupons'] = $route['consoleadmin'].'/admin_coupons';
$route['consoleadmin/get_all_coupons'] = $route['consoleadmin']. '/get_all_coupons';
$route['consoleadmin/change_coupons_status'] = $route['consoleadmin']. '/change_coupons_status';


$route['consoleadmin/add_coupon'] = $route['consoleadmin']. '/add_coupon';
$route['consoleadmin/fab_home'] = $route['consoleadmin'].'/admin_fab_home';
$route['consoleadmin/get_all_fabhome_deep_rates'] = $route['consoleadmin'].'/get_all_fabhome_deep_rates';
$route['consoleadmin/get_all_fabhome_home_rates'] = $route['consoleadmin'].'/get_all_fabhome_home_rates';
$route['consoleadmin/get_all_fabhome_office_rates'] = $route['consoleadmin'].'/get_all_fabhome_office_rates';
$route['consoleadmin/edit_rate/(:num)'] = $route['consoleadmin']. '/edit_fabhome_rate/$1';
$route['consoleadmin/fabhome_services'] = $route['consoleadmin'].'/admin_fabhome_services';
$route['consoleadmin/get_all_fabhome_services'] = $route['consoleadmin']. '/get_all_fabhome_services';
$route['consoleadmin/fabricspa_deleted_users'] = $route['consoleadmin'].'/admin_fabricspa_users';
$route['consoleadmin/get_deleted_count'] = $route['consoleadmin'].'/get_fabricspa_deleted_count';

/*****
 * API ROUTES
 * */
$route['api/get_qc_link'] = $route['api'] . '/get_customer_unique_link';
$route['api/get_whatspapp_opt_out_link'] = $route['api'] . '/get_whatspapp_opt_out_link';
$route['api/get_report'] = $route['api'] . '/get_report';
$route['api/upload_qc_image'] = $route['api'] . '/upload_qc_image';
$route['api/get_image_links'] = $route['api'] . '/get_image_links';
$route['api/notify'] = $route['api'] . '/notify';
$route['api/check_with_zaakpay'] = $route['api'] . '/check_with_zaakpay';
$route['api/get_rates'] = $route['api'] . '/get_rates';
$route['api/get_blog'] = $route['api'] . '/get_blog';
$route['api/get_nearest_stores'] = $route['api'] . '/get_nearest_stores';
$route['api/generate_payment_link'] = $route['api'] . '/generate_payment_link';
$route['api/generate_shorturl'] = $route['api']. '/generate_shorturl_from_invoiceno';
/*$route['api/report']=$route['api'].'/report';
$route['api/report/(:any)']=$route['api'].'/report'.'$1';*/
$route['api/report/(:any)/(:any)/(:any)'] = $route['api'] . '/report/' . '$1/$2/$3';

/***
 * WhatsAppOptOut Routes
 * */

$route['WAO/opt'] = $route['WAO'] . '/opt';
$route['WAO/(:any)'] = 'whatsappopt_controller/index/$1';


/*QC link cipher controller routes*/

$route['sui/save'] = $route['sui'] . '/save_response';

$route['sui/(:any)'] = $route['sui'] . '/index/$1';

$route['sui/(:any)/save_response'] = $route['sui'] . '/save_response/$1';

$route['sui/(:any)/logs'] = $route['sui'] . '/logs/$1';
$route['sui/(:any)/log'] = $route['sui'] . '/log';
$route['sui/(:any)/log/(:any)'] = $route['sui'] . '/log/$2';


$route['camp/register'] = $route['camp'] . '/register';
$route['camp/area_list'] = $route['camp'] . '/area_list';
$route['camp/(:any)'] = $route['camp'] . '/index/$1';

// 