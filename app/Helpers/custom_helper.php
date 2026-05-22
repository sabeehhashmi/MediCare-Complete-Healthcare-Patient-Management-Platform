<?php

use App\Models\ActivityType;
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Auth;
use Twilio\Exceptions\TwilioException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\ActivityLog;

use App\Libraries\Agora\RtcTokenBuilder;
use App\Models\PointHistory;
use App\Models\SettingsModel;


function getSettings(){
    $settings = SettingsModel::first();
    return $settings;
}
function addLoyaltyPoints($userId, $amount, $appointmentId = null)
{
    // =========================================
    // GET SETTINGS
    // =========================================
    $settings = SettingsModel::first();

    // =========================================
    // CHECK ENABLE
    // =========================================
    if (
        !$settings ||
        $settings->loyallty_points_enable != 1
    ) {
        return false;
    }

    // =========================================
    // CONFIG VALUES
    // Example:
    // 100 AED = 10 Credits
    // =========================================
    $requiredAmount = (float) $settings->loyallty_points_amount;
    $rewardPoints   = (float) $settings->loyallty_points_on_amount;

    // SAFETY CHECK
    if (
        $requiredAmount <= 0 ||
        $rewardPoints <= 0
    ) {
        return false;
    }

    // =========================================
    // CALCULATE MULTIPLES ONLY
    // =========================================
    // 150 => 1 time
    // 250 => 2 times
    // =========================================
    $multiples = floor($amount / $requiredAmount);

    // =========================================
    // NO POINTS
    // =========================================
    if ($multiples <= 0) {
        return false;
    }

    // =========================================
    // TOTAL POINTS
    // =========================================
    $totalPoints = $multiples * $rewardPoints;

    // =========================================
    // PREVENT DUPLICATE ENTRY
    // =========================================
    $alreadyExists = PointHistory::where(
        'appointment_id',
        $appointmentId
    )
    ->where('type', 'credit')
    ->exists();

    if ($alreadyExists) {
        return false;
    }

    // =========================================
    // SAVE HISTORY
    // =========================================
    PointHistory::create([
        'user_id'        => $userId,
        'appointment_id' => $appointmentId,
        'type'           => 'credit',
        'points'         => $totalPoints,
        'description'    => "Earned {$totalPoints} loyalty credits on appointment payment of {$amount} AED"
    ]);

    $user = auth()->user();
    $user->points=$user->points+$totalPoints;
    $user->save();

    return $totalPoints;

    
}

function generateAgoraToken($channel, $uid)
    {
        $appID = config('services.agora.app_id');
        $appCertificate = config('services.agora.app_certificate');

        $role = RtcTokenBuilder::RoleAttendee;

        $expireTimeInSeconds = 3600;
        $privilegeExpiredTs = now()->timestamp + $expireTimeInSeconds;

        return RtcTokenBuilder::buildTokenWithUid(
            $appID,
            $appCertificate,
            $channel,
            $uid,
            $role,
            $privilegeExpiredTs
        );
    }

if (!function_exists('activity_log')) {

    function activity_log($action, $description = null, $meta = [])
    {
        $user = auth()->user();

        ActivityLog::create([
            'user_id'    => $user->id ?? null,
            'user_type'  => $user->role, // or custom role
            'action'     => $action,
            'description'=> $description,
            'meta'       => $meta
        ]);
    }
}
function image_front_upload($file, $model, $file_name = null, $mb_file_size = 25)
{
    if (!$file) {
        return ['status' => false, 'link' => null, 'message' => 'No file found'];
    }

    if (empty($model)) $model = 'category';

    return file_save($file, $model, $mb_file_size);
}

function image_front_s3_upload($file, $model = 'category', $file_name = "", $mb_file_size = 25)
{
    if (!$file) {
        return ['status' => false, 'link' => null, 'message' => 'No file found'];
    }

    return file_save_to_s3($file, $model, $mb_file_size);
}


if (! function_exists('get_storage_path') ) {
    function get_storage_path( $filename='', $dir='' )
    {
        if ( !empty($filename) ) {

            $upload_dir = config('global.upload_path');
            if (! empty($dir) ) {
                $dir= config("global.{$dir}");
            }
            if ( \Storage::disk(config('global.upload_bucket'))->exists($dir.$filename) ) {
               return \Storage::url("{$dir}{$filename}");
           }
        }


        return '';
    }
}
if (! function_exists('get_uploaded_image_url') ) {
    function get_uploaded_image_url( $filename='', $dir='', $default_file='placeholder.png' )
    {

        if ( !empty($filename) ) {

            $upload_dir = config('global.upload_path');
            if (! empty($dir) ) {
                $dir= config("global.{$dir}");

            }
            //return "https://d27k3316b49gzy.cloudfront.net/".$dir.$filename;
            return \Storage::disk(config('global.upload_bucket'))->url($dir.$filename);
        //     if ( \Storage::disk(config('global.upload_bucket'))->exists($dir.$filename) ) {
                
        //         return \Storage::disk(config('global.upload_bucket'))->url($dir.$filename);
                
        //    }else{

        //     return asset(\Storage::url("{$dir}{$filename}"));
        //    }
        }
        return '';
        if ( !empty($default_file) ) {
            if (! empty($dir) ) {
                $dir= config("global.{$dir}");
            }
            $default_file = asset('admin-assets/assets/img/placeholder.jpg');
        }
        if (! empty($default_file) ) {
            return $default_file;
        }


        return \Storage::url("logo.png");
    }
}
if (! function_exists('time_ago') ) {
    function time_ago( $datetime, $now=NULL, $timezone='Etc/GMT' )
    {
        if (! $now ) {
            $now = time();
        }
        $timezone_user  = new DateTimeZone($timezone);
        $date           = new DateTime($datetime, $timezone_user);
        $timestamp      = $date->getTimestamp();
        $timespan       = explode(', ', timespan($timestamp, $now));
        $timespan       = $timespan[0] ?? '';
        $timespan       = strtolower($timespan);

        if (! empty($timespan) ) {
            if ( stripos($timespan, 'second') !== FALSE ) {
                $timespan = 'few seconds ago';
            } else {
                $timespan .= " ago";
            }
        }

        return $timespan;
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function mapArrayByIndex($array, $index) {
    $result = [];
    foreach ($array as $arr) {
        $result[$arr[$index]] = $arr;
    }
    return $result;
}

if (!function_exists('getAppointmentStatusClass')) {
    function getAppointmentStatusClass($status)
    {
        switch ($status) {
            case BOOKING_STATUS_PENDING:
                return 'pending-badge';
            case BOOKING_STATUS_COMPLETED:
                return 'completed-badge';
            case BOOKING_STATUS_CANCELLED:
                return 'cancelled-badge';
            case BOOKING_STATUS_CONFIRMED:
                return 'confirmed-badge';
            case BOOKING_STATUS_RESCHEDULED:
                return 'reschedule-badge';
            default:
                return '';
        }
    }
}

if (!function_exists('getAppointmentStatusClassBox')) {
    function getAppointmentStatusClassBox($status)
    {
        switch ($status) {
            case BOOKING_STATUS_PENDING:
                return 'pending';
            case BOOKING_STATUS_COMPLETED:
                return 'completed';
            case BOOKING_STATUS_CANCELLED:
                return 'cancelled';
            case BOOKING_STATUS_CONFIRMED:
                return 'confirmed';
            case BOOKING_STATUS_RESCHEDULED:
                return 'reschedule';
            default:
                return '';
        }
    }
}

function convertToAmPm($timeSlot) {
    $dateTime = DateTime::createFromFormat('H:i', $timeSlot);
    return $dateTime->format('h:i A');
}

function bookingStatusCheck($status) {
    $actions = [
        'view_appointment' => false,
        'cancel_appointment' => false,
        'confirm_appointment' => false,
        'reschedule_appointment' => false,
        'complete_appointment' => false,
    ];

    switch (strtolower($status)) {
        case 'pending':
            $actions['view_appointment'] = true;
            $actions['cancel_appointment'] = true;
            $actions['confirm_appointment'] = true;
            $actions['reschedule_appointment'] = true;
            break;
        case 'confirmed':
            $actions['view_appointment'] = true;
            $actions['cancel_appointment'] = true;
            $actions['reschedule_appointment'] = true;
            $actions['complete_appointment'] = true;
            break;
        case 'rescheduled':
            $actions['view_appointment'] = true;
            $actions['cancel_appointment'] = true;
            $actions['complete_appointment'] = true;
            $actions['confirm_appointment'] = true;
            break;
        case 'cancelled':
            $actions['view_appointment'] = true;
            break;
        case 'completed':
            $actions['view_appointment'] = true;
            break;
    }

    return $actions;
}

if (! function_exists('get_date_in_timezone') ) {
    function get_date_in_timezone($date, $format="d-M-Y h:i a",$timezone='',$server_time_zone="Etc/GMT")
    {
        if($timezone == ''){
            $timezone = config('global.date_timezone');
        }
        try {
            $timezone_server    = new DateTimeZone($server_time_zone);
            $timezone_user      = new DateTimeZone($timezone);
        }
        catch (Exception $e) {
            $timezone_server    = new DateTimeZone($server_time_zone);
            $timezone_user      = new DateTimeZone($server_time_zone);
        }


        $dt = new DateTime($date, $timezone_server);

        $dt->setTimezone($timezone_user);

        return $dt->format($format);
    }
}
function public_url()
{
    if (config('app.url') == 'http://127.0.0.1:8000') {
        return str_replace('/public', '', config('app.url'));
    }
    return config('app.asset_url');
}
function image_upload($request, $model, $file_name, $mb_file_size = 25)
{
    if (empty($model)) {
        $model = 'category';
    }

    if ($request->file($file_name)) {

        $file = $request->file($file_name);

        // Optional: validate size (MB)
        $maxSize = $mb_file_size * 1024 * 1024;
        if ($file->getSize() > $maxSize) {
            return [
                'status' => false,
                'link' => null,
                'message' => 'File size exceeds limit'
            ];
        }

        // Generate filename
        $file_name_new = time() . uniqid() . "." . $file->getClientOriginalExtension();

        // Store file (same as your new logic)
        $file->storeAs($model, $file_name_new, config('global.upload_bucket'));

        return [
            'status' => true,
            'link' => $file_name_new,
            'message' => 'File uploaded successfully'
        ];
    }

    return [
        'status' => false,
        'link' => null,
        'message' => 'Unable to upload file'
    ];
}
function image_upload_bk($request,$model,$file_name, $mb_file_size = 25)
{
    if(empty($model)) $model = 'category';
    if($request->file($file_name ))
    {
        $file = $request->file($file_name);
        return  file_save($file,$model, $mb_file_size);
    }
    return ['status' =>false,'link'=>null,'message' => 'Unable to upload file'];
}
function image_s3_upload($request,$model = 'category',$file_name="", $mb_file_size = 25)
{
    if($request->file($file_name ))
    {
        $file = $request->file($file_name);
         return  file_save_to_s3($file,$model, $mb_file_size);
        return  file_save($file,$model, $mb_file_size);
    }
    return ['status' =>false,'link'=>null,'message' => 'Unable to upload file'];
}
function file_save_to_s3($file, $model, $mb_file_size = 25)
{
    try {
        $model = str_replace('/', '', $model);
        //validateSize
        $precision = 2;
        $size = $file->getSize();
        $size = (int) $size;
        $base = log($size) / log(1024);
        $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
        $dSize = round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];

        $aSizeArray = explode(' ', $dSize);
        if ($aSizeArray[0] > $mb_file_size && ($aSizeArray[1] == 'MB' || $aSizeArray[1] == 'GB' || $aSizeArray[1] == 'TB')) {
            return ['status' => false, 'link' => null, 'message' => 'Image size should be less than equal ' . $mb_file_size . ' MB'];
        }
        // rename & upload files to upload folder
        $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($model, $fileName, config('global.upload_bucket'));
        $image_url = $fileName;
        return ['status' => true, 'link' => $image_url, 'message' => 'file uploaded'];
    } catch (\Exception $e) {
        return ['status' => false, 'link' => null, 'message' => $e->getMessage()];
    }
}

if (! function_exists('array_combination') ) {
    function array_combination($arrays, $i = 0)
    {
        if (!isset($arrays[$i])) {
            return array();
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = array_combination($arrays, $i + 1);

        $result = array();

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                    array_merge(array($v), $t) :
                    array($v, $t);
            }
        }

        return $result;
    }
}

function file_save($file,$model,$mb_file_size=25)
{
     try {
        $model = str_replace('/','',$model);
        //validateSize
        $precision = 2;
        $size = $file->getSize();
        $size = (int) $size;
        $base = log($size) / log(1024);
        $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
        $dSize = round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];

        $aSizeArray = explode(' ', $dSize);
        if ($aSizeArray[0] > $mb_file_size && ($aSizeArray[1] == 'MB' || $aSizeArray[1] == 'GB' || $aSizeArray[1] == 'TB')) {
            return ['status' =>false,'link'=>null,'message' => 'Image size should be less than equal '.$mb_file_size.' MB'];
        }
        // rename & upload files to upload folder

        $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($model,$fileName,config('global.upload_bucket'));
        $image_url = $fileName;

        return ['status' =>true,'link'=>$image_url,'message' => 'file uploaded'];

    } catch (\Exception $e) {
        return ['status' =>false,'link'=> null ,'message' => $e->getMessage()];
    }
}
if (! function_exists('deleteFile') ) {
    function deleteFile($path)
    {
        try {
            $root_path = base_path() . $path;
            if (file_exists($root_path))
                unlink($root_path);
        } catch (\Exception $e) {
            return false;
        }
    }
}

function printr($data){
  echo '<pre>';
  var_dump($data);
  echo '</pre>';
}
function url_title($str, $separator = '-', $lowercase = FALSE)
{
    if ($separator == 'dash')
    {
        $separator = '-';
    }
    else if ($separator == 'underscore')
    {
        $separator = '_';
    }

    $q_separator = preg_quote($separator);

    $trans = array(
        '&.+?;'                 => '',
        '[^a-z0-9 _-]'          => '',
        '\s+'                   => $separator,
        '('.$q_separator.')+'   => $separator
    );

    $str = strip_tags($str);

    foreach ($trans as $key => $val)
    {
        $str = preg_replace("#".$key."#i", $val, $str);
    }

    if ($lowercase === TRUE)
    {
        $str = strtolower($str);
    }

    return trim($str, $separator);
}

function send_email($to, $subject, $mailbody)
{
    require base_path("vendor/autoload.php");
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;
        $mail->setFrom(env('MAIL_FROM_ADDRESS'));
        $mail->addAddress($to);
        $mail->addBCC("sabeeh.hashmi2@gmail.com");
        $mail->addBCC("navisanil@gmail.com");
       $mail->addBCC("dev.ahmad28@gmail.com");
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $mailbody;
        // $mail->SMTPOptions = array(
        //     'ssl' => array(
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //         'allow_self_signed' => true
        //     )
        // );
        if (!$mail->send()) {
            // dd($e->getMessage());
            return 0;
        } else {
            return 1;
        }
    } catch (Exception $e) {
         //dd($e->getMessage());
        return 0;
    }
}
function send_email_test($to, $subject, $mailbody)
{
    require base_path("./vendor/autoload.php");
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "info@dealsdrive.app";
        $mail->Password = "fywaxsgggjgwxvjw";
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;
        $mail->setFrom("info@dealsdrive.app", "DealDrive");
        $mail->addAddress($to);
        //$mail->addCC('binshambrs@gmail.com');
        $mail->addBcc("sooraj.a2solution@gmail.com");
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $mailbody;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        if (!$mail->send()) {
            return 0;
        } else {
            return 1;
        }
    } catch (Exception $e) {
        return 0;
    }
}
function send_normal_SMS($message, $mobile_numbers, $sender_id = "")
{
    $curl = curl_init();
    $token = env('SMS_API_KEY'); // Replace it with your API Token
    $originator = "Mednero"; // Replace it with your Sender ID
    $recipients = array("+".str_replace("+","",$mobile_numbers)); // Replace it with real recipients
    $content = $message;
    $message_obj =  array( 
        "channel"=> "sms",
        "msg_type"=> "text",
        "recipients"=> $recipients,
        "content"=> $content,
        "data_coding"=> "auto"
    );
    $globals_obj = array( 
        "originator"=> $originator,
        "report_url"=> "https://the_url_to_recieve_delivery_report.com", 
    );
    $payload = json_encode( 
        array( 
            "messages"=> array($message_obj),
            "message_globals"=> $globals_obj 
        ) 
    );
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.d7networks.com/messages/v1/send',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>$payload,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer '.$token
    ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return  $response;
}

function send_normal_SMSTwilo($message, $receiverNumber, $sender_id = "")
{
    return true;
    try {
        $receiverNumber = '+'.str_replace("+","",$receiverNumber);
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_TOKEN");
        $twilio_number = getenv("TWILIO_FROM");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($receiverNumber, [
            'from' => $twilio_number,
            'body' => $message]
        );
        return 1;
    } catch (TwilioException $e) {
        return $e->getMessage();
        // return 0;
    }
}

function convert_all_elements_to_string($data = null, $emptyArrayShouldBeObject = false)
{
    if ($data != null) {
        array_walk_recursive($data, function (&$value, $key) use ($emptyArrayShouldBeObject) {
            if (!is_object($value)) {
                if ($value) {
                    $value = (string) $value;
                } else {
                    $value = (string) $value;
                }
            } else {
                $json = json_encode($value);
                $array = json_decode($json, true);

                array_walk_recursive($array, function (&$obj_val, $obj_key) use ($emptyArrayShouldBeObject) {
                    $obj_val = (string) $obj_val;
                });

                if (!empty($array)) {
                    $json = json_encode($array);
                    $value = json_decode($json);
                } else {
                    if ($emptyArrayShouldBeObject) {
                        $value = (object)[];
                    } else {
                        $value = [];
                    }
                }
            }
        });
    }
    return $data;
}
function thousandsCurrencyFormat($num) {

    if( $num > 1000 ) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('k', 'm', 'b', 't');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];
        return $x_display;
    }

    return $num;
}
function order_status($id)
   {
        $status_string = "Pending";
        if($id == config('global.order_status_pending'))
                {
                   $status_string = "Waiting For Confirmation";
                }
                if($id == config('global.order_status_accepted'))
                {
                   $status_string = "Order Confirmed";
                }
                if($id == config('global.order_payment_completed'))
                {
                   $status_string = "Order Confirmed";
                }
                if($id == config('global.order_status_ready_for_delivery'))
                {
                   $status_string = "Preparing Order";
                }

                if($id == config('global.order_status_driver_accepted'))
                {
                   $status_string = "Preparing Order";
                }
                if($id == config('global.order_status_dispatched'))
                {
                   $status_string = "On the way";
                }
                if($id == config('global.order_status_delivered'))
                {
                   $status_string = "Order Delivered";
                }
                if($id == config('global.order_status_cancelled'))
                {
                   $status_string = "Cancelled";
                }
                if($id == config('global.order_status_returned'))
                {
                   $status_string = "Returned";
                }
                if($id == config('global.order_status_rejected'))
                {
                   $status_string = "Rejected";
                }
                if($id == config('global.order_payment_completed'))
                {
                   $status_string = "Payment Completed";
                }
    return $status_string;
   }
   function seller_order_status($id,$deligate=0)
   {
        $status_string = "Pending";
        if($id == config('global.order_status_pending'))
                {
                   $status_string = "Waiting For Confirmation";
                }
                if($id == config('global.order_status_accepted'))
                {
                   $status_string = "Payment Pending";
                }
                if($id == config('global.order_payment_completed'))
                {
                   $status_string = "Order Confirmed";
                }
                if($id == config('global.order_status_ready_for_delivery'))
                {
                   $status_string = "Driver Waiting";
                   if($deligate==2){
                       $status_string = "Customer Waiting";
                   }
                }

                if($id == config('global.order_status_driver_accepted'))
                {
                   $status_string = "Driver Accepted";
                }
                if($id == config('global.order_status_dispatched'))
                {
                   $status_string = "On the way";
                   $status_string = "Dispatched";
                }
                if($id == config('global.order_status_delivered'))
                {
                   $status_string = "Order Delivered";
                   $status_string = "Dispatched";
                }
                if($id == config('global.order_status_cancelled'))
                {
                   $status_string = "Cancelled";
                }
                if($id == config('global.order_status_returned'))
                {
                   $status_string = "Returned";
                }
                if($id == config('global.order_status_rejected'))
                {
                   $status_string = "Rejected";
                }
                if($id == config('global.order_payment_completed'))
                {
                   $status_string = "Payment Completed";
                }
    return $status_string;
   }
   function food_seller_order_status($id)
   {
        $status_string = "Pending";
        if($id == config('global.order_status_pending'))
                {
                   $status_string = "Waiting For Confirmation";
                }
                if($id == config('global.order_status_accepted'))
                {
                   $status_string = "Payment Pending";
                }
                if($id == config('global.order_payment_completed'))
                {
                   $status_string = "Payment Completed";
                }
                if($id == config('global.order_status_ready_for_delivery'))
                {
                   $status_string = "Preparing Order";
                }

                if($id == config('global.order_status_driver_accepted'))
                {
                   $status_string = "Driver Accepted";
                }
                if($id == config('global.order_status_dispatched'))
                {
                   $status_string = "On the way";
                   $status_string = "Dispatched";
                }
                if($id == config('global.order_status_delivered'))
                {
                   $status_string = "Order Delivered";
                   $status_string = "Dispatched";
                }
                if($id == config('global.order_status_cancelled'))
                {
                   $status_string = "Cancelled";
                }
                if($id == config('global.order_status_returned'))
                {
                   $status_string = "Returned";
                }
                if($id == config('global.order_status_rejected'))
                {
                   $status_string = "Rejected";
                }
                if($id == config('global.order_payment_completed'))
                {
                   $status_string = "Payment Completed";
                }
    return $status_string;
   }

    function get_payment_mode_text($id){
        $status_string = "N/A";
        if($id == 1)
        {
            $status_string = "Wallet";
        }
        if($id == 2)
        {
            $status_string = "Card";
        }
        if($id == 3)
        {
            $status_string = "Wallet";
        }
        return $status_string;
    }

    function reservation_status($id)
    {
        $status_string = "Pending";
        if ($id == config('global.booking_status_waiting_for_confirmation')) {
            $status_string = "Waiting For Confirmation";
        } elseif ($id == config('global.booking_status_booking_confirmed')) {
            $status_string = "Booking Confirmed";
        } elseif ($id == config('global.booking_status_wait_for_schedule')) {
            $status_string = "Wait for your Schedule";
        } elseif ($id == config('global.booking_status_reserved')) {
            $status_string = "Wait for your Schedule";
        } elseif ($id == config('global.booking_status_completed')) {
            $status_string = "Completed";
        } elseif ($id == config('global.reservation_status_cancelled')) {
            $status_string = "Cancelled";
        } elseif ($id == config('global.booking_status_rejected')) {
            $status_string = "Rejected";
        }

        return $status_string;
    }

    function seller_reservation_status($id)
    {
        $status_string = "Pending";
        if ($id == config('global.booking_status_waiting_for_confirmation')) {
            $status_string = "Waiting For Confirmation";
        } elseif ($id == config('global.booking_status_booking_confirmed')) {
            $status_string = "Pending Payment";
        } elseif ($id == config('global.booking_status_wait_for_schedule')) {
            $status_string = "Reserved";
        } elseif ($id == config('global.booking_status_reserved')) {
            $status_string = "Reserved";
        } elseif ($id == config('global.booking_status_completed')) {
            $status_string = "Completed";
        } elseif ($id == config('global.reservation_status_cancelled')) {
            $status_string = "Cancelled";
        } elseif ($id == config('global.booking_status_rejected')) {
            $status_string = "Rejected";
        }

        return $status_string;
    }

   function service_status($id)
   {
        $status_string = "Pending";
        if($id == config('global.service_status_pending'))
        {
            $status_string = "Waiting For Confirmation";
        }
        if($id == config('global.service_status_rejected'))
        {
            $status_string = "Request Rejected";
        }
        if($id == config('global.service_quote_added'))
        {
            $status_string = "Request Confirmed";
        }
        if($id == config('global.service_quote_accepted'))
        {
            $status_string = "Offer Accepted";
        }

        if($id == config('global.service_quote_rejected'))
        {
            $status_string = "Offer Rejected";
        }
        if($id == config('global.service_location_added'))
        {
            $status_string = "Waiting For Service Provider";
        }
        if($id == config('global.service_on_the_way'))
        {
            $status_string = "On the way to site";
        }
        if($id == config('global.service_work_started'))
        {
            $status_string = "Work On Progress";
        }
        if($id == config('global.service_work_completed'))
        {
            $status_string = "Work Finished";
        }
        if($id == config('global.service_payment_completed'))
        {
            $status_string = "Payment Done";
        }
        if($id == config('global.service_service_completed'))
        {
            $status_string = "Completed";
        }
        return $status_string;
   }
   function service_store_status($id)
   {
        $status_string = "Pending";
        if($id == config('global.service_status_pending'))
        {
            $status_string = "Waiting For Confirmation";
        }
        if($id == config('global.service_status_rejected'))
        {
            $status_string = "Request Rejected";
        }
        if($id == config('global.service_quote_added'))
        {
            $status_string = "Waiting for approval";
        }
        if($id == config('global.service_quote_accepted'))
        {
            $status_string = "Offer Accepted";
        }

        if($id == config('global.service_quote_rejected'))
        {
            $status_string = "Offer Rejected";
        }
        if($id == config('global.service_location_added'))
        {
            $status_string = "Offer Accepted";
        }
        if($id == config('global.service_on_the_way'))
        {
            $status_string = "On the way to site";
        }
        if($id == config('global.service_work_started'))
        {
            $status_string = "Work On Progress";
        }
        if($id == config('global.service_work_completed'))
        {
            $status_string = "Pay in cash";
        }
        if($id == config('global.service_payment_completed'))
        {
            $status_string = "Pay in cash";
        }
        if($id == config('global.service_service_completed'))
        {
            $status_string = "Completed";
        }
        return $status_string;
   }

   function wholesale_status($id)
   {
        $status_string = "Pending";
        if($id == config('global.wholesale_status_pending'))
        {
            $status_string = "Waiting For Confirmation";
        }
        if($id == config('global.wholesale_status_rejected'))
        {
            $status_string = "Request Rejected";
        }
        if($id == config('global.wholesale_quote_added'))
        {
            $status_string = "Request Confirmed";
        }
        if($id == config('global.wholesale_quote_accepted'))
        {
            $status_string = "Offer Accepted";
        }

        if($id == config('global.wholesale_quote_rejected'))
        {
            $status_string = "Offer Rejected";
        }
        if($id == config('global.wholesale_payment_completed'))
        {
            $status_string = "Payment Completed";
        }
        if($id == config('global.wholesale_on_the_way'))
        {
            $status_string = "Ready for delivery";
        }

        if($id == config('global.wholesale_completed'))
        {
            $status_string = "Order Completed";
        }

        return $status_string;
   }
   function wholesale_store_status($id)
   {
        $status_string = "Pending";
        if($id == config('global.wholesale_status_pending'))
        {
            $status_string = "Waiting For Confirmation";
        }
        if($id == config('global.wholesale_status_rejected'))
        {
            $status_string = "Request Rejected";
        }
        if($id == config('global.wholesale_quote_added'))
        {
            $status_string = "Request Confirmed";
        }
        if($id == config('global.wholesale_quote_accepted'))
        {
            $status_string = "Offer Accepted";
        }

        if($id == config('global.wholesale_quote_rejected'))
        {
            $status_string = "Offer Rejected";
        }
        if($id == config('global.wholesale_payment_completed'))
        {
            $status_string = "Payment Completed";
        }
        if($id == config('global.wholesale_on_the_way'))
        {
            $status_string = "Ready for delivery";
        }

        if($id == config('global.wholesale_completed'))
        {
            $status_string = "Order Completed";
        }

        return $status_string;
   }

   function driver_order_status($id)
   {
        $status_string = "Pending";
        if($id == config('global.order_status_pending'))
                {
                   $status_string = "Pending";
                }
                if($id == config('global.order_status_driver_accepted'))
                {
                   $status_string = "Accepted";
                }
                if($id == config('global.order_status_ready_for_delivery'))
                {
                   $status_string = "Pending";
                }
                if($id == config('global.order_status_dispatched'))
                {
                   $status_string = "Dispatched";
                }
                if($id == config('global.order_status_delivered'))
                {
                   $status_string = "Delivered";
                }
                if($id == config('global.order_status_cancelled'))
                {
                   $status_string = "Cancelled";
                }
                if($id == config('global.order_status_returned'))
                {
                   $status_string = "Returned";
                }
                if($id == config('global.order_status_rejected'))
                {
                   $status_string = "Rejected";
                }
                if($id == config('global.order_payment_completed'))
                {
                   $status_string = "Payment Completed";
                }
    return $status_string;
   }

   function gym_status($id)
   {
        $status_string = "Pending";
        if($id == config('global.gym_status_pending'))
                {
                   $status_string = "Waiting For Confirmation";
                }
                if($id == config('global.gym_status_rejected'))
                {
                   $status_string = "Subscription Rejected";
                }
                if($id == config('global.gym_status_completed'))
                {
                   $status_string = "Subscription Confirmed";
                }
                if($id == config('global.gym_status_cancelled'))
                {
                   $status_string = "Subscription Cancelled";
                }
    return $status_string;
   }
   function get_deligate_service_status_text($id)
   {
        $status_string = "Pending";
        if($id == config('global.deligate_status_waiting_for_confirmation'))
                {
                   $status_string = "Waiting For Confirmation";
                }
                if($id == config('global.deligate_status_cancelled'))
                {
                   $status_string = " Cancelled";
                }
                if($id == config('global.deligate_status_rejected'))
                {
                   $status_string = " Rejected";
                }
                if($id == config('global.deligate_status_booking_confirmed'))
                {
                   $status_string = "Request Confirmed";
                }
                if($id == config('global.deligate_status_waiting_for_payment'))
                {
                   $status_string = "Waiting For Payment";
                }
                if($id == config('global.deligate_status_payment_completed'))
                {
                   $status_string = "Payment Completed";
                }
                if($id == config('global.deligate_status_on_the_way'))
                {
                   $status_string = "On the way";
                }
                if($id == config('global.deligate_status_delivered'))
                {
                   $status_string = "Order Delivered";
                }
                if($id == 11)
                {
                   $status_string = " Rejected";
                }
    return $status_string;
   }
   function get_deligate_driver_service_status_text($id)
   {
        $status_string = "Pending";
        if($id == config('global.deligate_status_waiting_for_confirmation'))
                {
                   $status_string = "Pending";
                }
                if($id == config('global.deligate_status_cancelled'))
                {
                   $status_string = " Cancelled";
                }
                if($id == config('global.deligate_status_rejected'))
                {
                   $status_string = " Rejected";
                }
                if($id == config('global.deligate_status_booking_confirmed'))
                {
                   $status_string = "Ready For Collection";
                }
                if($id == config('global.deligate_status_waiting_for_payment'))
                {
                   $status_string = "Waiting For Payment";
                }
                if($id == config('global.deligate_status_payment_completed'))
                {
                   $status_string = "Payment Completed";
                }
                if($id == config('global.deligate_status_on_the_way'))
                {
                   $status_string = "On the way";
                }
                if($id == config('global.deligate_status_delivered'))
                {
                   $status_string = "Order Delivered";
                }
    return $status_string;
   }

   function report_user_problem($id)
   {
        $problems = config('global.report_user_problems');
        return isset($problems[$id]) ? $problems[$id] : '';
   }
function process_order($list, $lang_code = "1")
   {
            foreach($list as $key=>$value)
            {
                $list[$key]->status = $value->status_id;
                if($value->status == config('global.order_status_pending'))
                {
                   $list[$key]->status_string = trans('order.pending');
                }
                if($value->status == config('global.order_status_accepted'))
                {
                   $list[$key]->status_string = trans('order.accepted');
                }
                if($value->status == config('global.order_status_ready_for_delivery'))
                {
                   $list[$key]->status_string = trans('order.ready_for_delivery');
                }
                if($value->status == config('global.order_status_dispatched'))
                {
                   $list[$key]->status_string = trans('order.dispatched');
                }
                if($value->status == config('global.order_status_delivered'))
                {
                   $list[$key]->status_string = trans('order.delivered');
                }
                if($value->status == config('global.order_status_cancelled'))
                {
                   $list[$key]->status_string = trans('order.cancelled');
                }


               if(!empty($value->address_id))
               {
                $list[$key]->shipping_address = App\Models\UserAdress::get_address_details($value->address_id);
               }

               $order_products  = App\Models\WholesaleOrderItem::product_details(['wholesale_order_id'=>$value->id]);
               $list[$key]->order_products = $order_products;//process_product_data($order_products);
           }
           return $list;
    }
    function process_product_data($row, $lang_code = "1")
   {

      $ratings  = [];
      $product_row_data = $row;
      $sl = 0;
      foreach($row as $item) {

      if($lang_code == 2)
      {
      $product_row_data[$sl]->product_name      = (string) $item->product_name_arabic;
      $product_row_data[$sl]->product_desc_full      = (string) $item->product_desc_full_arabic;
      $product_row_data[$sl]->product_desc_short      = (string) $item->product_desc_short_arabic;
      }
      $product_images = [];

      if(!empty($item->image))
      {
         $imagesarray = explode(",",$item->image);
         foreach($imagesarray as $img)
         {
           $product_images[] = (string) url(config('global.upload_path').config('global.product_image_upload_dir').$img);
         }
      }
      else
      {
          $product_images[] = (string) url(config('global.upload_path').'placeholder.jpg');
      }
      if(isset($item->order_status))
      {
      $product_row_data[$sl]->status_string = order_status($item->order_status);
      }

      $stock_status = 0;
      $stock_status_string = "Out of stock";
      if(isset($item->stock_quantity))
      {
      if($item->stock_quantity > 0)
      {
        $stock_status = 1;
        $stock_status_string = "In stock";
      }
      }


      $discountper = 0;
      if(isset($item->sale_price) && isset($item->regular_price))
      {
          $diff = $item->regular_price - $item->sale_price;
          $discountper = ($diff/$item->regular_price)*100;
          $discountper = round($discountper);
      }
      $product_row_data[$sl]->stock_status        = $stock_status;
      $product_row_data[$sl]->stock_status_string = $stock_status_string;
      $product_row_data[$sl]->product_images      = $product_images;
      $product_row_data[$sl]->discount_per        = $discountper;
      $product_row_data[$sl]->share_url           = url('share/product/'.encryptor($item->id));





      $sl++;
   }
   $product_row_data = process_vendor($product_row_data);
   return $product_row_data;

   }
   function encryptor($string) {
    $output = false;

    $encrypt_method = "AES-128-CBC";
    //pls set your unique hashing key
    $secret_key = 'muni';
    $secret_iv = 'muni123';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    //do the encyption given text/string/number

        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);


    return $output;
}

function decryptor( $string) {
    $output = false;

    $encrypt_method = "AES-128-CBC";
    //pls set your unique hashing key
    $secret_key = 'muni';
    $secret_iv = 'muni123';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);


        //decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);


    return $output;
}
 function process_vendor($row, $lang_code = "1")
   {
      foreach($row as $key=>$item) {

        $row[$key]->vendor_img_path              = (string) image_link("placeholder.png",config('global.user_image_upload_dir'));
      if(!empty($item->vendorimage))
      {
        $row[$key]->vendor_img_path             = (string) url(config('global.upload_path').config('global.user_image_upload_dir').$item->vendorimage);
      }

      }
      return $row;
   }
    function image_link($image ,$directory )
    {
    if($image !="") {
        return url(config('global.upload_path').$directory.$image);

    }
   }

   function process_product_data_api($row)
{
    // printr($row);
    $product_row_data = [];
    $product_row_data["product_id"]         = (string) $row->product_id;
    if (isset($row->product_attribute_id)) {
        $product_row_data["product_variant_id"] = (string) $row->product_attribute_id;
    }
    $product_row_data["product_name"]       = (string) $row->product_name;
    $product_row_data["product_desc_short"] = (string) $row->product_desc;
    $product_row_data["product_desc"]       = (string) $row->product_full_descr;
    $product_row_data["stock_quantity"]       = (int) $row->stock_quantity;
    if ( isset($row->product_vender_id) ) {
        $product_row_data["product_seller_id"]  = (string) $row->product_vender_id;
    }
    $product_row_data["allow_back_order"]   = (string) $row->allow_back_order;

    if (isset($row->category_id)) {
        $product_row_data["category_id"] = (string) $row->category_id;
    }
    if (isset($row->category_name)) {
        $product_row_data["category_name"] = (string) $row->category_name;
    }
    if (isset($row->product_brand_id) && ($row->product_brand_id > 0) ) {
        $product_row_data["product_brand_id"] = (string) $row->product_brand_id;
    } else {
        $product_row_data["product_brand_id"] = '';
    }

    if (isset($row->brand_name)) {
        $product_row_data["brand_name"] = (string) $row->brand_name;
    } else {
        $product_row_data["brand_name"] = '';
    }

    $product_row_data["product_type"]       = (string) $row->product_type;
    //echo  $row->image;
    $product_images = explode(",", $row->image);
    //printr($product_images);
    $product_images = array_values(array_filter($product_images));
    $product_image  = (count($product_images) > 0) ? $product_images[0] : $row->image;
    $product_row_data["product_image"] = get_uploaded_image_url( $product_image, 'product_image_upload_dir', 'placeholder.png' );//url(config('global.upload_path') . '/' . config('global.product_image_upload_dir') . str_replace(' ', '%20', $product_image));

    $product_row_data["product_images"]     = array();
    if (is_array($product_images)) {
        foreach($product_images as $key=>$image) {
            $product_row_data["product_images"][] = get_uploaded_image_url( $image, 'product_image_upload_dir', 'placeholder.png' );//$image;//url(config('global.upload_path') . '/' . config('global.product_image_upload_dir') . str_replace(' ', '%20', $image));
        }
    }
    $product_row_data["rated_users"] = (!empty($row->rated_users)) ? (string) $row->rated_users : "0";
    $product_row_data["rating"]      = (!empty($row->rated_users)) ? (string) $row->rating: "0";
    $product_row_data["sale_price"]         = number_format((float) $row->sale_price,2,".", "");
    $product_row_data["regular_price"]      = number_format((float) $row->regular_price,2,".", "");
    $product_row_data["product_vendor_id"] = $row->product_vender_id;
    $product_row_data["moda_sub_category"] = $row->moda_sub_category;
    $product_row_data["moda_main_category"] = $row->moda_main_category;
    $product_row_data["moda_sub_category"] = $row->moda_sub_category;

    if ($row->size_chart) {
        $product_row_data["size_chart"] = 'https://1805025482.rsc.cdn77.org/products/'.$row->size_chart;//asset($row->size_chart);
    }else{
        $product_row_data["size_chart"] = '';
    }

    if ( isset($row->seller_id) ) {
        $product_row_data["seller_id"] = $row->seller_id;
    }
    if ( isset($row->store_id) ) {
        $product_row_data["store_id"] = $row->store_id;
    }
    if ( isset($row->store_name) ) {
        $product_row_data["store_name"] = substr($row->store_name,0,19).".";
    }

    if ( $product_row_data["sale_price"] < $product_row_data["regular_price"] ) {
        $product_row_data['offer_enabled'] = 1;
        $price_diff = $product_row_data["regular_price"] - $product_row_data["sale_price"];
        $offer_percentage = ($price_diff / $product_row_data["regular_price"]) * 100;
        $offer_percentage = ceil($offer_percentage);
        $product_row_data['offer_percentage'] = $offer_percentage;
    } else {
        $product_row_data['offer_enabled'] = 0;
        $product_row_data['offer_percentage'] = 0;
    }
    $product_row_data["vendor_rating"]  = "0";
    if(isset($row->vendor_rating)){
        $product_row_data["vendor_rating"]  =   (string) $row->vendor_rating;
    }
    return $product_row_data;
}

function generate_otp($count=4){
//     if($count == 3){
//         return 111;
//     }

   return 1111;
  //return rand(1111,9999);
}
function wallet_history($data=[])
    {
        $data = (object)$data;
        $WalletHistory = new \App\Models\WalletHistory();
        $WalletHistory->user_id	        = $data->user_id;
        $WalletHistory->wallet_amount	= $data->wallet_amount;
        $WalletHistory->pay_type	    = $data->pay_type;
        $WalletHistory->description	    = $data->description;
        $WalletHistory->is_earning	    = isset($data->is_earning) ? $data->is_earning : 0;
        $WalletHistory->pay_method	    = isset($data->pay_method) ? $data->pay_method : 0;
        $WalletHistory->created_at	    = gmdate('Y-m-d H:i:00');
        $WalletHistory->updated_at	    = gmdate('Y-m-d H:i:00');

        if($WalletHistory->save()){
            exec("php " . base_path() . "/artisan wallet_history:push " . $WalletHistory->id . " > /dev/null 2>&1 & ");
            return 1;
        }

        return 0;
    }
    if (! function_exists('web_date_in_timezone') ) {
        function web_date_in_timezone($date, $format="d M Y h:i A",$server_time_zone="Etc/GMT")
        {
//            $timezone = session('user_timezone');
             // $timezone = 'Asia/Dubai';
            $timezone = 'Asia/Dubai';
            if(!$timezone){
                $timezone = $server_time_zone;
            }
            $timezone_server    = new DateTimeZone($server_time_zone);
            $timezone_user      = new DateTimeZone($timezone);
            $dt = new DateTime($date, $timezone_server);
            $dt->setTimezone($timezone_user);
            return $dt->format($format);
        }
    }

    if (! function_exists('api_date_in_timezone') ) {
        function api_date_in_timezone($date, $format,$timezone,$server_time_zone="Etc/GMT")
        {

            if($timezone ==''){
                $timezone ='Asia/Dubai';
            }
            if(empty( $format)) $format="d M Y h:i A";
            $timezone_server    = new DateTimeZone($server_time_zone);
            $timezone_user      = new DateTimeZone($timezone);
            $dt = new DateTime($date, $timezone_server);
            $dt->setTimezone($timezone_user);
            return $dt->format($format);
        }
    }

    function removeNamespaceFromXML( $xml )
{
    // Because I know all of the the namespaces that will possibly appear in
    // in the XML string I can just hard code them and check for
    // them to remove them
    $toRemove = ['rap', 'turss', 'crim', 'cred', 'j', 'rap-code', 'evic'];
    // This is part of a regex I will use to remove the namespace declaration from string
    $nameSpaceDefRegEx = '(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?';

    // Cycle through each namespace and remove it from the XML string
   foreach( $toRemove as $remove ) {
        // First remove the namespace from the opening of the tag
        $xml = str_replace('<' . $remove . ':', '<', $xml);
        // Now remove the namespace from the closing of the tag
        $xml = str_replace('</' . $remove . ':', '</', $xml);
        // This XML uses the name space with CommentText, so remove that too
        $xml = str_replace($remove . ':commentText', 'commentText', $xml);
        // Complete the pattern for RegEx to remove this namespace declaration
        $pattern = "/xmlns:{$remove}{$nameSpaceDefRegEx}/";
        // Remove the actual namespace declaration using the Pattern
        $xml = preg_replace($pattern, '', $xml, 1);
    }

    // Return sanitized and cleaned up XML with no namespaces
    return $xml;
}

function namespacedXMLToArray($xml)
{
    // One function to both clean the XML string and return an array
    return json_decode(json_encode(simplexml_load_string(removeNamespaceFromXML($xml))), true);
}
function check_permission($module,$permission){
    $userid = Auth::user()->id;
    $privilege = 0;
    if ($userid > 1) {
        $privileges = \App\Models\UserPrivileges::privilege();
        $privileges = json_decode($privileges, true);
        if (!empty($privileges[$module][$permission])) {
            if ($privileges[$module][$permission] == 1) {
                $privilege = 1;
            }
        }
    } else {
        $privilege = 1;
    }
    return $privilege;
}

function get_user_permission($model, $operation = 'r')
{
    $return = false;
    if(Auth::user()){

        if (Auth::user()->role_id == '1' || Auth::user()->id == '1') {
            $return = true;
        } else/* if (Auth::user()->is_admin_access == 1) */ {
            
            $user_permissions = App\Models\RolePermissions::where(['user_role_id_fk' => Auth::user()->role_id])->get()->toArray();
            $user_permissions = array_column($user_permissions, 'permissions', 'module_key');
            //dd($user_permissions);
            if (isset($user_permissions[strtolower($model)])) {
                $permissions = json_decode($user_permissions[strtolower($model)] ?? '');
                if (in_array($operation, $permissions)) {
                    $return = true;
                }
            }
        }
    }else{
        
    }

    return $return;
}

function retrive_hash_tags($data=''){
    $d = explode(" ",$data);
    $words=[];
    foreach($d as $k){
        if(substr($k,0,1) == '#'){
          $words[]=ltrim($k,'#');
        }

    }
    return $words;
}
function paytab_init($data=[])
    {
        
            $url      = "https://secure.paytabs.sa/payment/request";
            //  $post     = array(
            //          "profile_id"=>(string)config('global.paytab_profile_id'), 
            //          "tran_type"=>"auth",
            //          "tran_class"=>"ecom",
            //          "cart_id"=> $invoice_id,
            //          "cart_description"=>"live market order purchase",
            //          "cart_currency"=>config('global.default_currency_code'),
            //          "cart_amount"=> (string) number_format($grand_total, 2, '.', ''),
            //          "callback"=>asset('paytabs/payment'),
            //          "return"=>asset('paytabs/payment')
            //      );

             $header   = array("authorization:".config('global.paytab_auth_key')."","content-type:application/json");

             $postdata = json_encode($data);
             $curlHandle = curl_init($url);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            
            $curlResponse = curl_exec($curlHandle);
            
            curl_close($curlHandle);
            return $curlResponse;
    }
function GetDrivingDistance($lat1, $lat2, $long1, $long2)
    {
        
        $dist = '-';
        $time = '-';
        $km=$tm=0;
        if( $lat1 != '' && $lat2 != '' && $long1 != '' && $long2 != ''){
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&key=AIzaSyD3vBoHrfM_Hz0LPzkJjfwC-EJK6eCEzgo";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response_a = json_decode($response, true);
            //printr($response_a);

            if(isset($response_a['rows'][0]['elements'][0]['distance']['text'])){
                $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
                $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
                $km = $response_a['rows'][0]['elements'][0]['distance']['value'];
                $tm = $response_a['rows'][0]['elements'][0]['duration']['value'];
            }
        }
        return array('distance' => $dist, 'time' => $time,'km'=>$km,'tm'=>$tm);
    }
    function GetDrivingDistanceToMultipleLocations($from_latlong, $destinations)
    {
        $distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$from_latlong.'&destinations='.$destinations.'&mode=driving&key=AIzaSyCtugJ9XvE2MvkXCBeynQDFKq-XN_5xsxM');
        return json_decode($distance_data, true);
    }

    function get_price_type(){
        return [
             'per_hour' => 'Per Hour',
             'per_day' => 'Per Day',
             'per_week' => 'Per Week',
             'per_month' => 'Per Month',
             'per_year' => 'Per Year',
             'fixed' => 'Fixed',
        ];
    }
    function get_price_type_new(){
        return [

             'per_day' => 'Per Day'
        ];
    }

    function get_booking_id($id){
       return '#LM-'.sprintf("%05d", $id);
    }

if (!function_exists('validateAccesToken')) {
    function validateAccesToken($access_token)
    {
        $user = App\Models\User::where(['user_access_token' => $access_token])->get();

        if ($user->count() == 0) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => 'Invalid login',
                'oData' => (object)[],
                'errors' => (object) [],
            ]);
            exit;
        } else {
            $user = $user->first();
            if ($user != null) { //$user->active == 1
                return $user->id;
            } else {
                http_response_code(401);
                echo json_encode([
                    'status' => "0",
                    'message' => 'Invalid login',
                    'oData' => (object)[],
                    'errors' => (object) [],
                ]);
                exit;
            }
        }
    }
}

if (!function_exists('sendResponseV1')) {
    function sendResponseV1($status, $message, $errors, $oData,  $code = 200)
    {
        $errors = convert_all_elements_to_string($errors);
        $o_data = convert_all_elements_to_string($oData, true);
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data], $code);
    }
}

if (!function_exists('sendStatusNotification')) {
    function sendStatusNotification($order_id)
    {
        if (config('global.server_mode') == 'local') {
            Artisan::call('food_send_status_nottification:order ' . $order_id);
        } else {
            exec("php " . base_path() . "/artisan food_send_status_nottification:order " . $order_id . " > /dev/null 2>&1 & ");
        }
    }
}

if (!function_exists('sendDriverStatusNotification')) {
    function sendDriverStatusNotification($order_id)
    {
        if (config('global.server_mode') == 'local') {
            Artisan::call('food_send_status_nottification:driver ' . $order_id);
        } else {
            exec("php " . base_path() . "/artisan food_send_status_nottification:driver " . $order_id . " > /dev/null 2>&1 & ");
        }
    }
}

if (!function_exists('sendSellerStatusNotification')) {
    function sendSellerStatusNotification($order_id)
    {
        if (config('global.server_mode') == 'local') {
            Artisan::call('food_send_status_nottification:seller ' . $order_id);
        } else {
            exec("php " . base_path() . "/artisan food_send_status_nottification:seller " . $order_id . " > /dev/null 2>&1 & ");
        }
    }
}

if (!function_exists('convertNullToEmptyString')) {
    function convertNullToEmptyString($data, $isArray)
    {
        if ($isArray) {
            foreach ($data as $index => $value) {
                foreach ($value as $key => $property) {
                    if ($property == null) {
                        $data[$index][$key] = "";
                    } else {
                        $data[$index][$key] = (string) $property;
                    }
                }
            }
        } else {
            foreach ($data as $key => $property) {
                if ($property == null) {
                    $data[$key] = "";
                } else {
                    $data[$key] = (string) $property;
                }
            }
        }
        return $data;
    }
}

if (!function_exists('getDiffInDays')) {
    function getDiffInDays($start_date, $end_date)
    {
        $start_date = \Carbon\Carbon::parse($start_date);
        $end_date = \Carbon\Carbon::parse($end_date);

        $days = $end_date->diffInDays($start_date);
        $days = $days > 0 ? $days : 1;
        return $days;
    }
}

if (!function_exists('getRemainingDaysToGo')) {
    function getRemainingDaysToGO($date)
    {
        $remaining = [
            'd' => 0,
            'h' => 0,
            'm' => 0,
            's' => 0,
        ];

        try {
            $targetDate = \Carbon\Carbon::parse($date);
            $now = \Carbon\Carbon::now();

            $diff = $targetDate->diff($now);

            $remaining = [
                'd' => $diff->d,
                'h' => $diff->h,
                'm' => $diff->i,
                's' => $diff->s,
            ];
        } catch (\Throwable $th) {
            //
        }

        return $remaining;
    }
}

if (!function_exists('getRemainingDaysToGOChaletBooking')) {
    function getRemainingDaysToGOChaletBooking($date)
    {
        $remaining = [
            'd' => 0,
            'h' => 0,
            'm' => 0,
            's' => 0,
        ];

        try {
            $targetDate = \Carbon\Carbon::parse($date);
            $now = \Carbon\Carbon::now();

            if($date < $now){
                return $remaining;
            }

            $diff = $targetDate->diff($now);

            $remaining = [
                'd' => $diff->d,
                'h' => $diff->h,
                'm' => $diff->i,
                's' => $diff->s,
            ];
        } catch (\Throwable $th) {
            //
        }

        return $remaining;
    }
}

if (!function_exists('sendReservationStatusNotification')) {
    function sendReservationStatusNotification($id, $booking_name = ' ')
    {
        if (config('global.server_mode') == 'local') {
            Artisan::call('reservation_user:booking ' . $id . ' ' . $booking_name);
        } else {
            exec("php " . base_path() . "/artisan reservation_user:booking " . $id . " " . $booking_name . " > /dev/null 2>&1 & ");
        }
    }
}

if (!function_exists('sendSellerReservationStatusNotification')) {
    function sendSellerReservationStatusNotification($id, $booking_name = ' ')
    {
        if (config('global.server_mode') == 'local') {
            Artisan::call('reservation_seller:booking ' . $id . ' ' . $booking_name);
        } else {
            exec("php " . base_path() . "/artisan reservation_seller:booking " . $id . " " . $booking_name . " > /dev/null 2>&1 & ");
        }
    }
}

if (!function_exists('convertIntToTime')) {
    function convertIntToTime($int)
    {
        if ($int == '0') {
            $time = '00:00';
        } elseif ($int == '1') {
            $time = '01:00';
        } elseif ($int == '2') {
            $time = '02:00';
        } elseif ($int == '3') {
            $time = '03:00';
        } elseif ($int == '4') {
            $time = '04:00';
        } elseif ($int == '5') {
            $time = '05:00';
        } elseif ($int == '6') {
            $time = '06:00';
        } elseif ($int == '7') {
            $time = '07:00';
        } elseif ($int == '8') {
            $time = '08:00';
        } elseif ($int == '9') {
            $time = '09:00';
        } elseif ($int == '10') {
            $time = '10:00';
        } elseif ($int == '11') {
            $time = '11:00';
        } elseif ($int == '12') {
            $time = '12:00';
        } elseif ($int == '13') {
            $time = '13:00';
        } elseif ($int == '14') {
            $time = '14:00';
        } elseif ($int == '15') {
            $time = '15:00';
        } elseif ($int == '16') {
            $time = '16:00';
        } elseif ($int == '17') {
            $time = '17:00';
        } elseif ($int == '18') {
            $time = '18:00';
        } elseif ($int == '19') {
            $time = '19:00';
        } elseif ($int == '20') {
            $time = '20:00';
        } elseif ($int == '21') {
            $time = '21:00';
        } elseif ($int == '22') {
            $time = '22:00';
        } elseif ($int == '23') {
            $time = '23:00';
        } elseif ($int == '24') {
            $time = '24:00';
        }

        return $time;
    }
}
function compareDates($date1, $date2)
{
    return strtotime($date1) - strtotime($date2);
}

if (!function_exists('snakeToTitle')) {
    function snakeToTitle($string)
    {
        $string = str_replace('_', ' ', $string);
        return ucwords($string);
    }
}

if (!function_exists('remove_common_elements')) {
    function remove_common_elements($array1, $array2)
    {
        $commonValues = array_intersect_assoc($array1, $array2);
        $commonKeys = array_keys($commonValues);

        $array1 = array_filter($array1, function ($key) use ($commonKeys) {
            return !in_array($key, $commonKeys);
        }, ARRAY_FILTER_USE_KEY);
        $array2 = array_filter($array2, function ($key) use ($commonKeys) {
            return !in_array($key, $commonKeys);
        }, ARRAY_FILTER_USE_KEY);

        return [
            'array1' => $array1,
            'array2' => $array2
        ];
    }
}

function get_payment_status($is_paid){
    if($is_paid == "1"){
        $status = 'Payment Received';
    }else{
        $status = '';
    }
    return $status;
}

function getPaymentMode($mode)
{
    if ($mode == PAYMENT_TYPE_CARD) {
        $status = 'Card';
    } elseif ($mode == PAYMENT_TYPE_WALLET) {
        $status = 'Wallet';
    } elseif ($mode == PAYMENT_TYPE_APPLE_PAY) {
        $status = 'Apple Pay';
    } elseif ($mode == PAYMENT_TYPE_CASH) {
        $status = 'Cash';
    } else {
        $status = 'Unknown';
    }
    return $status;
}

function capitalizeAndRemoveAWordInArray($array, $word = null)
{
    $array = array_map(function ($string) use ($word) {
        if (!empty($word)) {
            $string = str_replace($word, '', $string);
        }
        $string = ucwords($string);
        $string = str_replace('_', ' ', $string);
        return $string;
    }, $array);

    return $array;
}

function is_food_vendor($user_id)
{
    $food_activity_types = [
        ActivityType::CAFE,
        ActivityType::RESTAURANT,
        ActivityType::RESTAURANTS
    ];

    $user = \App\Models\User::select('id', 'activity_type_id')->find($user_id);

    return (in_array($user->activity_type_id, $food_activity_types));
}

function findClosest($arr, $n, $target)
{
    // Corner cases
    if ($target <= $arr[0])
        return $arr[0];
    if ($target >= $arr[$n - 1])
        return $arr[$n - 1];

    // Doing binary search
    $i = 0;
    $j = $n;
    $mid = 0;
    while ($i < $j)
    {
        $mid = ($i + $j) / 2;

        if ($arr[$mid] == $target)
            return $arr[$mid];

        /* If target is less than array element,
            then search in left */
        if ($target < $arr[$mid])
        {

            // If target is greater than previous
            // to mid, return closest of two
            if ($mid > 0 && $target > $arr[$mid - 1])
                return getClosest($arr[$mid - 1],
                                  $arr[$mid], $target);

            /* Repeat for left half */
            $j = $mid;
        }

        // If target is greater than mid
        else
        {
            if ($mid < $n - 1 &&
                $target < $arr[$mid + 1])
                return getClosest($arr[$mid],
                                  $arr[$mid + 1], $target);
            // update i
            $i = $mid + 1;
        }
    }

    // Only single element left after search
    return $arr[$mid];
}

// Method to compare which one is the more close.
// We find the closest by taking the difference
// between the target and both values. It assumes
// that val2 is greater than val1 and target lies
// between these two.
function getClosest($val1, $val2, $target)
{
    if ($target - $val1 >= $val2 - $target)
        return $val2;
    else
        return $val1;
}

function get_payment_text($key){
    if($key==PAYMENT_TYPE_CARD){
        $text = 'Card';
    }else if($key==PAYMENT_TYPE_CASH){
        $text = 'Cash';
    }else if($key==PAYMENT_TYPE_WALLET){
        $text = 'Wallet';
    }
    else if($key==PAYMENT_TYPE_APPLE_PAY){
        $text = 'Apple Pay';
    }else{
        $text = 'NA';
    }
    return $text;
}
function cms_type($key){
    if($key==1){
        $text = 'Hospital';
    }else if($key==2){
        $text = 'App/Website';
    }else if($key==3){
        $text = 'Clinic';
    }
    else if($key==4){
        $text = 'Doctor';
    }
    else if($key==5){
            $text = 'Agent';
    
    }
    else if($key==6){
        $text = 'Cancellation Policy';
    }else{
        $text = 'NA';
    }
    return $text;
}

function SplitTime($StartTime, $EndTime, $Duration="60",$slot_difference=0){
    $ReturnArray = array ();// Define output
    $StartTime    = strtotime ($StartTime); //Get Timestamp
    $EndTime      = strtotime ($EndTime); //Get Timestamp

    $AddMins  = $Duration * 60;
    $AddDiff  = $slot_difference * 60;

    while ($StartTime <= $EndTime) //Run loop
    {
        $ReturnArray[] = date ("G:i", $StartTime);
        $StartTime += $AddMins; //Endtime check
    }
    return $ReturnArray;
}
function get_table_booking_status_text($id)
{
    $status_string = "Pending";
    if($id == config('global.table_booking_status_pending'))
            {
                $status_string = "Waiting For Confirmation";
            }
            if($id == config('global.table_booking_status_accepted'))
            {
                $status_string = "Booking Confirmed";
            }
            if($id == config('global.table_booking_status_rejected'))
            {
                $status_string = "Booking Rejected";
            }
            if($id == config('global.table_booking_status_completed'))
            {
                $status_string = "Completed";
            }
    return $status_string;
}

if (!function_exists('calculate_age')) {
    if (!function_exists('calculate_age')) {
        function calculate_age($dob) {
            $dob = Carbon::parse($dob);
            $now = Carbon::now();
            if ($dob->greaterThan($now)) {
                return 'N/A'; 
            }
    
            return $dob->age;
        }
    }
}

   