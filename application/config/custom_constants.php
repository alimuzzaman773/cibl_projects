<?php
date_default_timezone_set('Asia/Dhaka');

define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

define('API_SERVER_PATH', 'http://192.168.5.81/eblapi/image_save/imageSave');

define("SITE_FOLDER", "");
define("ABS_SERVER_PATH", rtrim(rtrim(getcwd(), "\/" . SITE_FOLDER), '/') . '/');
define("TABLE_THEME2", "datatables");
define("TABLE_THEME", "flexigrid");

define('TEMPLATES_FOLDER', 'templates/');
define('CUSTOMER_TEMPLATES_FOLDER', 'customer_templates/');
define('ASSETS_FOLDER', 'assets/');
/*
** Define Tables 
*/
define("DB_PREFIX", "");

define("TBL_ADMIN_USERS", DB_PREFIX . "admin_users");
define("TBL_ADMIN_USERS_GROUP", DB_PREFIX . "admin_users_group");
define("TBL_PERMISSIONS", DB_PREFIX . "permissions");
define("TBL_ACTION", DB_PREFIX . "action");
define("TBL_PRODUCT_SETUP", DB_PREFIX . "product_setup");
define("TBL_PRODUCT_TYPE_SETUP", DB_PREFIX . "product_type_setup");


// All Constant Variables:
define ("PRODUCT_REQUEST_REPORTING_MAIL", serialize(array( 'mail1'=>"abc@gmail.com", 'mail2'=> "abc@yahoo.com")));
define ("IDLE_TIME", "300");

// **** All Modules ***

define ("apps_user_module", "01");
define ("device_module", "02");
define ("pin_module", "03");
define ("admin_user_module", "04");
define ("admin_user_group_module", "05");
define ("limit_package_module", "06");
define ("routing_number_module", "07");
define ("biller_setup_module", "08");
define ("bill_type_setup_module", "09");
define("password_policy_module", "10");

// ****** Authorization Modules ****
define ("apps_users_authorization", "01");
define ("device_authorization", "02");
define ("pin_reset_authorization", "03");
define ("admin_user_authorization", "04");
define ("admin_user_group_authorization", "05");
define ("limit_package_authorization", "06");
define ("biller_setup_authorization", "07");
define ("pin_create_authorization", "08");
define ("password_policy_authorization", "09");
define ("apps_user_delete_authorization", "10");


// ***** Content Setup Modules ****
define('product', "01");
define('location', "02");
define('zip', "03");
define('priority', "04");
define('benifit', "05");
define('newsEvents', "06");
define('notification', "07");
define('advertisement', "08");
define('help', '09');


// **** Service Request Modules *****
define('priority_sr', "01");
define('product_sr', "02");
define('banking_sr', "03");
//** Added by Sanjit 21-06-2016****

define("BANK_NAME","EBL");
// **** Report Type Modules *****
define('user_status', "01");
define('customer_info', "02");
define('user_login_info', "03");
define('fund_transfer', "04");
define('other_fund_transfer', "05");
define('user_id_modification', "06"); 
define('billing_info', "07");
define('priority_request', "08");
define('product_request', "09");
define('banking_request', "10");
//**Ended*****


// *** ENCRYPTION KEY **** //
define('TRANSMISSION_PRIVATE_KEY', 'EblCibl123456789');
define('BO_PRIVATE_KEY', 'EblCibl123456789');


// ** card number masking ** //
define('MASK', '6');

// ** FILE transfer related **//

define('ROOT_DIR', dirname(dirname(dirname(__FILE__))));
define('DS', DIRECTORY_SEPARATOR);

if(!defined('cbs_data_from_dummy')):
    define('cbs_data_from_dummy', TRUE);    
endif;
define('API_URL', "http://172.20.163.19/pbl_api/");
