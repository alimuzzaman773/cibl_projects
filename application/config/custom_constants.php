<?php
require 'custom/custom_config.php';

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
 * * Define Tables 
 */
define("DB_PREFIX", "");

define("TBL_ADMIN_SETTINGS", DB_PREFIX . "admin_settings");
define("TBL_ADMIN_USERS", DB_PREFIX . "admin_users");
define("TBL_ADMIN_USERS_GROUP", DB_PREFIX . "admin_users_group");
define("TBL_ADMIN_USERS_GROUP_MC", DB_PREFIX . "admin_users_group_mc");
define("TBL_PERMISSIONS", DB_PREFIX . "permissions");
define("TBL_ACTION", DB_PREFIX . "action");
define("TBL_PRODUCT_SETUP", DB_PREFIX . "product_setup");
define("TBL_PRODUCT_CATEGORIES", DB_PREFIX . "product_categories");
define("TBL_PRODUCT_TYPE_SETUP", DB_PREFIX . "product_type_setup");
define("TBL_CBS_PRODUCT", DB_PREFIX . "cbs_products");
define("TBL_ZIP_PARTNERS", "zip_partners");
define("TBL_DISCOUNT_PARTNERS", "discount_partners");
define("TBL_APPOINTMENT", DB_PREFIX . "appointment");
define("TBL_ATMS", DB_PREFIX . "atms");
define("TBL_ADVERTISEMENT", DB_PREFIX . "advertisement");
define("TBL_VALIDATION", DB_PREFIX . "validation");
define("TBL_APP_USERS", DB_PREFIX . "apps_users");
define("TBL_APP_USER_ACTIVITY_LOG", DB_PREFIX . "app_user_activity_log");
define("TBL_DEVICE_INFO", DB_PREFIX . "device_info");
define("TBL_COMPLAINT_INFO", DB_PREFIX . "complaint_info");
define("TBL_APP_USER_ACTIVITY_LOG_TYPE", DB_PREFIX . "app_user_activity_log_type");
define("TBL_APP_USERS_MC", DB_PREFIX . "apps_users_mc");
define("TBL_APP_USERS_GROUP", DB_PREFIX . "apps_users_group");
define("TBL_BO_ACTIVITY_LOG", DB_PREFIX . "bo_activity_log");
define("TBL_SSL_BILL_PAYMENT", DB_PREFIX . "ssl_bill_payment");
define('TBL_APPS_TRANSACTION', DB_PREFIX . "apps_transaction");
define("TBL_FILES", DB_PREFIX . "files");


// All Constant Variables:
define("PRODUCT_REQUEST_REPORTING_MAIL", serialize(array('mail1' => "abc@gmail.com", 'mail2' => "abc@yahoo.com")));
define("IDLE_TIME", "300");

// **** All Modules ***

define("apps_user_module", "01");
define("device_module", "02");
define("pin_module", "03");
define("admin_user_module", "04");
define("admin_user_group_module", "05");
define("limit_package_module", "06");
define("routing_number_module", "07");
define("biller_setup_module", "08");
define("bill_type_setup_module", "09");
define("password_policy_module", "10");

// ****** Authorization Modules ****
define("apps_users_authorization", "01");
define("device_authorization", "02");
define("pin_reset_authorization", "03");
define("admin_user_authorization", "04");
define("admin_user_group_authorization", "05");
define("limit_package_authorization", "06");
define("biller_setup_authorization", "07");
define("pin_create_authorization", "08");
define("password_policy_authorization", "09");
define("apps_user_delete_authorization", "10");


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

define("BANK_NAME", "PBL");
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

if (!defined('cbs_data_from_dummy')):
    define('cbs_data_from_dummy', false);
endif;

define('LIVE_URL', "http://192.168.0.72/");
define('API_URL', "http://172.20.163.19/pbl_api/");
define('UTILITY_URL', "http://api.sslwireless.com/api/");

define('CARD_AUTH', "PBL\\APPSAPI:!Pblit7469");
define('CARD_URL', "https://prmweb2.pbl.com/PRIME4API/Issuer/PrimeIssuerServices.asmx?WSDL");

define('CBS_URL', "http://192.168.1.153:86/api/");
define('CBS_STACK_HOLDER', "Admin");
define('CBS_USER_ID', "admin");
define('CBS_PASSWORD', "1");

//Lankabangla Configuration
define("LANKABANGLA_GL_ACCOUNT", "010413100002837");
define('LANKABANGLA_URL', "http://ws.lankabangla.com:8010/lbflapis/");
define('LANKABANGLA_FTP', "/home/lankabangla/");
define('LANKABANGLA_USERNAME', "lbfl");
define('LANKABANGLA_PASSWORD', "559bc2a7b989d0a4325b7661d077a2f6");
define('LANKABANGLA_TO_EMAIL', array("ccpayment@lankabangla.com", "mohtasim@lankabangla.com", "saif@lankabangla.com"));
define('LANKABANGLA_CC_EMAIL', array("grpmis@premierbankltd.com"));

define('LANKABANGLA_FTP_HOST', "103.122.100.68");
define('LANKABANGLA_FTP_PORT', "1000");
define('LANKABANGLA_FTP_USERNAME', "primerbank01");
define('LANKABANGLA_FTP_PASSWORD', "primerbank@01#lbfl");

define('INHOUSE_CBS_URL', "http://192.168.1.153:50912/api/");
