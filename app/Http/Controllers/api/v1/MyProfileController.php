<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator,DB;
use App\Models\User;
use App\Models\TempUsers;
use App\Models\Languages;
use App\Models\CountryModel;
use App\Models\Doctor;
use App\Models\Emirate;
use App\Models\Area;
use App\Models\Hospital;
use App\Models\InsurencePolicy;
use App\Models\SubInsurencePolicy;
use App\Models\Specialty;
use App\Models\MedicalCondition;
use App\Models\Members;
use Illuminate\Support\Facades\Hash;
class MyProfileController extends Controller
{
    //

    private function validateAccesToken($access_token)
    {

        $user = User::where(['access_token' => $access_token])->get();

        if ($user->count() == 0) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => 'Session Expired Please login to continue',
                'oData' => [],
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
                    'message' => 'Session Expired Please login to continue',
                    'oData' => [],
                    'errors' => (object) [],
                ]);
                exit;
                return response()->json([
                    'status' => "0",
                    'message' => 'Session Expired Please login to continue',
                    'oData' => [],
                    'errors' => (object) [],
                ], 401);
                exit;
            }
        }
    }

    public function my_profile(REQUEST $request)
    {
        $status = (string) 0;
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $status = (string) 0;
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
        } else {

            $user_id = $this->validateAccesToken($request->access_token);

            $data = User::where(['id'=>$user_id])->get();
            if ($data->count() > 0) {
                $o_data = $data->first();
                $o_data->insurence_policy = InsurencePolicy::find($o_data->insurence_id);
                $o_data->sub_insurence_policy = SubInsurencePolicy::find($o_data->sub_insurence_id);
                
             
                $o_data = convert_all_elements_to_string($o_data->toArray());
                if(empty($o_data['insurence_policy']))
                {
                    $o_data['insurence_policy'] = (object) [];
                }
                if(empty($o_data['sub_insurence_policy']))
                {
                    $o_data['sub_insurence_policy'] = (object) [];
                }
                $status = (string) 1;
                $message = "data fetched Successfully";
            } else {
                $message = "no data to show";
            }
        }

        return response()->json(['status' => $status, 'error' => $errors, 'message' => $message, 'oData' => $o_data], 200);
    }

    public function update_user_profile(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
         'access_token' => 'required',
         'first_name'=>'required',
         'last_name'=>'required',

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $change = 0;
            $first_name = $request->first_name;
            $last_name  = $request->last_name;
            $gender     = $request->gender??0;
            $dob        = ($request->dob != '')?date('Y-m-d',strtotime($request->dob)):'';
            $whatsap_dial_code = str_replace("+","",$request->whatsap_dial_code);
            $whatsap_number = ltrim($request->whatsap_number,"0");
            $insurence_id = $request->insurence_id??0;
            $sub_inusurence_id = $request->sub_insurence_id??0;
            $dial_code  = str_replace("+","",$request->dial_code);
            $phone      = ltrim($request->phone,"0");
            $email      = strtolower($request->email);

            $user_id = $this->validateAccesToken($request->access_token);
            $check_user = User::find($user_id);
            if($check_user){
               
                    $user = User::find($user_id);
                    if($file = $request->file("user_image")){
                        $file_name = time().uniqid().".".$file->getClientOriginalExtension();
                        $file->storeAs(config('global.user_image_upload_dir'),$file_name,config('global.upload_bucket'));
                        $user->user_image = $file_name;
                    }
                    $user->first_name = $first_name;
                    $user->last_name  = $last_name;
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    if($dob){
                    $user->dob        = $dob;
                    }
                    $user->name = $first_name." ".$last_name;
                    $user->gender     = $gender;
                    $user->whatsap_dial_code = $whatsap_dial_code;
                    $user->whatsap_phone = $whatsap_number;
                    $user->insurence_id = $insurence_id;
                    $user->sub_insurence_id = $sub_inusurence_id;
                    $otp = generate_otp();
                    $phone_change = $email_change=0;
                    if($user->email != $email)
                    {
                        if($email){
                            $check_email = User::where(['email'=>$email])->get();
                            if($check_email->count() > 0){
                            $message = trans('messages.email_already_exist');
                            $errors['email'] = trans('messages.email_already_exist');
                             return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
                         }
                         else
                         {
                             send_normal_SMS($dial_code.$phone,"OTP for verifying your account at ".config('global.site_name')." is ".$otp );
                             $change = 1;
                             $email_change = 1;
                             $user->user_email_otp = $user->user_phone_otp = $otp;
                         }
                        
                    }
                    }
                    if($user->dial_code != $dial_code || $user->phone != $phone)
                    {
                        $check_phone = User::where(['dial_code'=>$dial_code,'phone'=>$phone])->get();
                        if($check_phone->count() > 0){
                        $message = trans('messages.phone_already_exist');
                        $errors['phone'] = trans('messages.phone_already_exist');
                        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
                    }
                    else
                    {
                        send_normal_SMS($dial_code.$phone,"OTP for verifying your account at ".config('global.site_name')." is ".$otp );
                        $change = 1;
                        $phone_change = 1;
                        $user->user_email_otp = $user->user_phone_otp = $otp;
                    }
                    }
                    $user->save();
                    

                    

                   
                    $user = User::where(['id'=>$user_id])->get()->first();
                    $user->insurence_name = InsurencePolicy::find($insurence_id)->title??'';
                    $user->sub_insurence_name = SubInsurencePolicy::find($sub_inusurence_id)->title??'';
                    $o_data = convert_all_elements_to_string($user->toArray());
                    $message = "Profile updated successfully";
                    if($email_change==1){
                        exec("php " . base_path() . "/artisan app:send-update-email-otp " . $user->id . " ".base64_encode($email)." > /dev/null 2>&1 & ");
                    }else{
                        exec("php " . base_path() . "/artisan app:send-login-otp-mail " . $user->id . " normal > /dev/null 2>&1 & ");
                    }
                    
                    $status = "1";
                    if($change == 1)
                    {
                        $message = "Please Verify OTP";
                        $status = "3";
                        if($phone_change == 1 && $email_change == 1){
                            $status = "5";
                            $message="Please enter the otp to verify your email address or phone number";
                        }else if($phone_change == 1 && $email_change == 0){
                            $status = "3";
                            $message='Please enter the otp to verify your phone number';
                        }else if($phone_change == 0 && $email_change == 1){
                            $status = "4";
                            $message='Please enter the otp to verify your email address';
                        }
                    }
               
            }else{
                $message = "invalid user";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function verify_update_user_profile_otp(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
         'access_token' => 'required',
         'otp' => 'required',
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $dial_code  = str_replace("+","",$request->dial_code);
            $phone      = ltrim($request->phone,"0");
            $email      = strtolower($request->email);

            $user_id = $this->validateAccesToken($request->access_token);
            $check_user = User::find($user_id);
            if($check_user){
                if($check_user->user_phone_otp == $request->otp)
                {
                
                    if($check_user)
                    $user = User::find($user_id);
                    if($phone)
                    {
                        $user->dial_code = $dial_code;
                        $user->phone = $phone;
                    }
                    if($email)
                    {
                        $user->email = $email;
                    }
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    $user->save();
                    

                    

                   
                    $user = User::where(['id'=>$user_id])->get()->first();
                    $user->insurence_name = InsurencePolicy::find($user->insurence_id)->title??'';
                    $user->sub_insurence_name = SubInsurencePolicy::find($user->sub_inusurence_id)->title??'';
                    $o_data = convert_all_elements_to_string($user->toArray());
                    $message = "Profile updated successfully";
                    $status = "1";
                        
                }
                else
                {
                    $message = "Invalid OTP";
                    $status = "0";
                }
               
            }else{
                $message = "invalid user";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function updateFaceLoginId(Request $request)
    {
        //try{
           
            $validator = Validator::make($request->all(), [
                'access_token'  => 'required',
            ]);

            if ($validator->fails()) {
                $message = "Please fill all required fields";
                $errors = $validator->messages();
                return response()->json([
                    'status' => "0",
                    'message' => $message,
                    'error' => (object)$errors
                ], 200);
            }

            if ($request->access_token != null) {

                $user  = User::where(['access_token' => $request->access_token])->get()->first();

                if (empty($user)) {
                    $message = "Session Expired please login";
                    return response()->json([
                        'status' => "0",
                        'message' => $message,
                        'error' => (object)array()
                    ], 200);
                } else {

                    $face_login_id = $request->face_login_id ?? NULL;

                    if ($user->face_login_id == "" &&  $request->face_login_id == "" )
                        $message = "Face Login already removed!";
                    else{
                        $users_update = User::find($user->id)->update(['face_login_id' => $face_login_id]);

                        if( $face_login_id == "" )
                            $message = "Face Login removed successfully!";
                        else
                            $message = "Face Login ID Updated successfully!";
                    }

                    return response()->json([
                        'status' => "1",
                        'message' => $message,
                        'error' => (object)array()
                    ], 200);
                }
            }

            $message = "Session Expired.";
            return response()->json([
                'status' => "0",
                'message' => $message,
                'error' => (object)array()
            ], 200);

        // }catch(\Exception $exception)
        // {
        //     $sMessage = $exception->getMessage();

        //     $message = 'some thing went wrong please try again';

        //     return response()->json([
        //         'result' => false,
        //         'message' => $message,
        //         'error' => (object)array()
        //     ], 200);
        // }
    }
}