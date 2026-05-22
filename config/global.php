<?php

$config['server_mode']                  = 'live'; //live

$config['site_name']                                    = env("APP_NAME", 'Mednero');
$config['date_timezone']                                = 'Asia/Dubai';
$config['datetime_format']                                = 'M d, Y h:i A';
$config['date_format']                                    = 'M d, Y';
$config['date_format_excel']                            = 'd/m/Y';
$config['default_currency_code']                        = 'USDT';
$config['ticket_prefix']                        = 'BLT';
$config['user_code_prefix']                        = 'MEDN-P-';
$config['booking_prefix']                         = '#MEDN';

//$config['upload_bucket']                        = 's3'; //'s3';//s3
$config['upload_bucket'] = 's3'; // use Laravel's public disk
$config['medicine_image_upload_dir'] = 'mednero/medicines/'; // folder inside storage/app/public
$config['prescription_upload_dir'] = 'mednero/prescriptions/';

$config['order_status'] = [
    1 => 'Pending',
    2 => 'Confirmed',
    3 => 'Processing',
    4 => 'Dispatched',
    5 => 'Delivered',
    6 => 'Cancelled',
    7 => 'Refunded'
];

// Payment Status Constants
$config['payment_status'] = [
    0 => 'Pending',
    1 => 'Paid',
    2 => 'Failed',
    3 => 'Refunded',
];
$config['upload_path']                                  = 'storage/';
$config['user_image_upload_dir']                        = 'mednero/users/';
$config['user_documents_dir']                        = 'mednero/documents/';
$config['member_image_upload_dir']                        = 'mednero/users/';
$config['company']                                        = 'mednero/company/';
$config['category_image_upload_dir']                    = 'mednero/category/';
$config['food_category_image_upload_dir']                    = 'mednero/food_category/';
$config['deligates_upload_dir']                         = 'mednero/deligates/';
$config['facilities_upload_dir']                        = 'mednero/facilities/';
$config['website_services_dir']                         = 'mednero/website_services/';
$config['product_image_upload_dir']                        = 'mednero/products/';
$config['reservation_product_upload_dir']               = 'mednero/reservation_products/';
$config['hospital_image_upload_dir']                        = 'mednero/posts/';
$config['agents_image_upload_dir']                        = 'mednero/agents/';
$config['banner_image_upload_dir']                      = 'mednero/banner_images/';
$config['trade_licenece_image_upload_dir']              = 'mednero/trade_licence/';
$config['homepage_image_upload_dir']                    = 'mednero/homepage/';
$config['appointment']                    = 'mednero/appointment/';



if (!defined('AGENT_USER_TYPE_ID')) {
    define('AGENT_USER_TYPE_ID', 3);
}

if (!defined('PAYMENT_STATUS_PENDING')) {
    define('PAYMENT_STATUS_PENDING', 'pending');
}

if (!defined('PAYMENT_STATUS_PAID')) {
    define('PAYMENT_STATUS_PAID', 'paid');
}

if (!defined('PAYMENT_STATUS_FAILED')) {
    define('PAYMENT_STATUS_FAILED', 'failed');
}

if (!defined('PAYMENT_TYPE_CARD')) {
    define('PAYMENT_TYPE_CARD', 1);
}

if (!defined('PAYMENT_TYPE_CASH')) {
    define('PAYMENT_TYPE_CASH', 2);
}

if (!defined('PAYMENT_TYPE_WALLET')) {
    define('PAYMENT_TYPE_WALLET', 3);
}

if (!defined('PAYMENT_TYPE_APPLE_PAY')) {
    define('PAYMENT_TYPE_APPLE_PAY', 4);
}

if (!defined('ADMIN_ROLE')) {
    define('ADMIN_ROLE', 1);
}

if (!defined('STAFF_ROLE')) {
    define('STAFF_ROLE', 2);
}

if (!defined('AGENT_ROLE')) {
    define('AGENT_ROLE', 3);
}

if (!defined('CALL_CENTER_ROLE')) {
    define('CALL_CENTER_ROLE', 4);
}

if (!defined('HOSPITAL_ROLE')) {
    define('HOSPITAL_ROLE', 5);
}

if (!defined('DOCTOR_ROLE')) {
    define('DOCTOR_ROLE', 6);
}

if (!defined('USER_ROLE')) {
    define('USER_ROLE', 7);
}

if (!defined('CLINIC_ROLE')) {
    define('CLINIC_ROLE', 8);
}

if (!defined('TIME_SLOTS')) {
    define('TIME_SLOTS', [
        "08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30",
        "13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
        "18:00","18:30","19:00","19:30","20:00"
    ]);
}

if (!defined('GENDERS')) {
    define('GENDERS', [
        1 => 'Male',
        2 => 'Female',
        3 => 'Other'
    ]);
}

// Booking Status Constants
if (!defined('BOOKING_STATUS_PENDING')) {
    define('BOOKING_STATUS_PENDING', "Pending");
}

if (!defined('BOOKING_STATUS_COMPLETED')) {
    define('BOOKING_STATUS_COMPLETED', "Completed");
}

if (!defined('BOOKING_STATUS_CANCELLED')) {
    define('BOOKING_STATUS_CANCELLED', "Cancelled");
}

if (!defined('BOOKING_STATUS_CONFIRMED')) {
    define('BOOKING_STATUS_CONFIRMED', "Confirmed");
}

if (!defined('BOOKING_STATUS_RESCHEDULED')) {
    define('BOOKING_STATUS_RESCHEDULED', "Rescheduled");
}

// Type Constants
if (!defined('TYPE_HOSPITAL')) {
    define('TYPE_HOSPITAL', 10);
}

if (!defined('TYPE_CLINIC')) {
    define('TYPE_CLINIC', 20);
}

if (!defined('ALLOWED_COUNTRIES_PREF')) {
    define('ALLOWED_COUNTRIES_PREF', ['AE']);
}

if (!defined('INIT_PHONE_C_CODE')) {
    define('INIT_PHONE_C_CODE', 'AE');
}

if (!defined('ONLY_COUNTRY_PHONE')) {
    define('ONLY_COUNTRY_PHONE', ['AE']);
}

if (!defined('DEFAULT_DIAL_CODE')) {
    define('DEFAULT_DIAL_CODE', 971);
}



//FIrebase admin notifcaitons
$config['apiKey']               =   'dfc7345af3e6a211e29be063d5efca414bc35143';
$config['authDomain']           =   "mednero-default-rtdb.firebaseio.com";
$config['databaseURL']          =   'https://mednero-default-rtdb.firebaseio.com';
$config['projectId']            =   "mednero";
$config['storageBucket']         =    "mednero.appspot.com";
$config['messagingSenderId']    =    "690636094583";
$confg['appId']                 =  '1:690636094583:web:2d6d47f409aa90bad01a72';
return $config;
