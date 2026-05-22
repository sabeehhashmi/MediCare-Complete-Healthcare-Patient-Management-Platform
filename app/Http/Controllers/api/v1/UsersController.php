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
class UsersController extends Controller
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

    public function get_insurencey_policy(REQUEST $request){
        $status = "1";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
            
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $list = InsurencePolicy::withCount(['sub_insurence_policy'])->where(['status'=>1])->orderBy('title','asc');
            if($request->language==2){
                $list = $list->whereRaw("title_ar ilike '%".$request->search_text."%'");
            }else{
                $list = $list->whereRaw("title ilike '%".$request->search_text."%'");
            }
            $list=$list->take($limit)->skip($offset)->get();
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_sub_insurencey_policy(REQUEST $request){
        $status = "1";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'insurence_id'=>'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $list = SubInsurencePolicy::where(['status'=>1]);
            if($request->language==2){
                $list = $list->whereRaw("title_ar ilike '%".$request->search_text."%'");
            }else{
                $list = $list->whereRaw("title ilike '%".$request->search_text."%'");
            }
            $list=$list->orderBy('title','asc')->take($limit)->skip($offset)->get();
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }


    public function signup(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $sanitizedWhatsapNumber = preg_replace('/\D/', '', $request->whatsap_number ?? null);
        $request->merge(['phone' => $sanitizedPhone, 'whatsap_number' => $sanitizedWhatsapNumber]);

        $validator = Validator::make($request->all(), [
            //'user_image'=>'required|mimes:jpeg,jpg|max:2048',
            'first_name'=> 'required',
            'last_name' => 'required',
            'dial_code' => 'required|numeric',
            'phone' => 'required|numeric|digits_between:8,12',
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $first_name = $request->first_name;
            $last_name  = $request->last_name;
            $dial_code  = str_replace("+","",$request->dial_code);
            $phone      = ltrim($request->phone,"0");
            $gender     = $request->gender??0;
            $dob        = ($request->dob != '')?date('Y-m-d',strtotime($request->dob)):'';
            $email      = strtolower($request->email);
            $whatsap_dial_code = str_replace("+","",$request->whatsap_dial_code);
            $whatsap_number = ltrim($request->whatsap_number,"0");
            $insurence_id = $request->insurence_id??0;
            $sub_inusurence_id = $request->sub_insurence_id??0;

            //check phone number exist
            $check_phone = User::where(['dial_code'=>$dial_code,'phone'=>$phone])->get();
            if($check_phone->count() > 0){
                $message = "Phone number already registred with us please login to continue";//trans('messages.phone_already_exist');
                $errors['phone'] = trans('messages.phone_already_exist');
                return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
            }
            if($email){
                $check_email = User::where(['email'=>$email])->get();
                if($check_email->count() > 0){
                    $message = "Email id already registred";//trans('messages.email_already_exist');
                    $errors['email'] = "Email id already registred";//trans('messages.email_already_exist');
                    return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
                }
            }
            TempUsers::where(['dial_code'=>$dial_code,'phone'=>$phone])->delete();
            $temp_user =  new TempUsers();
            $temp_user->first_name  = $first_name;
            $temp_user->last_name   = $last_name;
            $temp_user->gender      = $gender;
            if($dob){
                $temp_user->dob         = $dob;
            }
            
            $temp_user->email       = $email;
            $temp_user->dial_code   = $dial_code;
            $temp_user->phone       = $phone;
            $temp_user->whatsap_dial_code = $whatsap_dial_code;
            $temp_user->whatsap_phone      = $whatsap_number;
            $temp_user->insurence_id = $insurence_id??0;
            $temp_user->is_social = $request->is_social??0;
            $temp_user->sub_insurence_id = $sub_inusurence_id??0;
            $temp_user->created_at      = gmdate('Y-m-d H:i:s');
            $temp_user->updated_at      = gmdate('Y-m-d H:i:s');
            if($file = $request->file("user_image")){
                $file_name = time().uniqid().".".$file->getClientOriginalExtension();
                $file->storeAs(config('global.user_image_upload_dir'),$file_name,config('global.upload_bucket'));
                $temp_user->user_image = $file_name;
            }
            $otp = generate_otp();
            $temp_user->phone_otp = $otp;
            $temp_user->email_otp = $otp;
            $temp_user->save();

            send_normal_SMS("Thank you for using Mednero. Your OTP is ".$otp." and valid for 10 minutes.",$temp_user->dial_code.$temp_user->phone );
            exec("php " . base_path() . "/artisan app:send-login-otp-mail " . $temp_user->id . " temp > /dev/null 2>&1 & ");
            $status = "1";
            if($email !=''){
                $message = "Please enter the otp to verify your email address or phone number";
            }else{
                $message = "Please enter the otp to verify your phone number";
            }
            
            $o_data = convert_all_elements_to_string($temp_user->toArray());
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function resend_signup_otp(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'user_id'=> 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{

            $check_user = TempUsers::find($request->user_id);
            if($check_user){
                $check_user->phone_otp = generate_otp();
                send_normal_SMS("Thank you for using Mednero. Your OTP is ".$check_user->phone_otp." and valid for 10 minutes." ,$check_user->dial_code.$check_user->phone );
                $check_user->save();
                $status = "1";
                $message = "Otp sent to your registred phone number";
                $o_data = convert_all_elements_to_string($check_user->toArray());
            }else{
                $message = "invalid user id passed";
            }
            return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
        }
    }
    
    public function resend_phone_otp(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'user_id'=> 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{

            $check_user = User::where('deleted', 0)->find($request->user_id);
            if($check_user){
                $check_user->user_phone_otp = generate_otp();
                send_normal_SMS("Thank you for using Mednero. Your OTP is ".$check_user->user_phone_otp." and valid for 10 minutes." ,$check_user->dial_code.$check_user->phone );
                $check_user->save();
                $status = "1";
                $message = "Otp sent to your registred phone number";
                $o_data = convert_all_elements_to_string($check_user->toArray());
            }else{
                $message = "invalid user id passed";
            }
            return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
        }
    }

    public function verify_signup_otp(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'user_id'=> 'required',
            'otp'=>'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $check_user = TempUsers::find($request->user_id);
            if($check_user){
                if($check_user->phone_otp == $request->otp){
                    //check phone number exist
                    $check_phone = User::where(['dial_code'=>$check_user->dial_code,'phone'=>$check_user->phone])->get();
                    if($check_phone->count() > 0){
                        $message = trans('messages.phone_already_exist');
                        $errors['phone'] = trans('messages.phone_already_exist');
                        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
                    }

                    $user = new User();
                    $user->user_image = $check_user->user_image;
                    $user->email      = $check_user->email;
                    $user->dial_code  = $check_user->dial_code;
                    $user->phone      = $check_user->phone;
                    $user->phone_verified = 1;
                    $user->password   = Hash::make($check_user->phone);
                    $user->name       = $check_user->first_name." ".$check_user->last_name;
                    $user->role       = USER_ROLE;
                    $user->active     = 1;
                    $user->first_name = $check_user->first_name;
                    $user->last_name  = $check_user->last_name;
                    $user->created_at = gmdate('Y-m-d H:i:s');
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    $user->user_device_token = $request->user_device_token;
                    $user->device_type = $request->device_type;
                    $user->dob        = $check_user->dob;
                    $user->gender     = $check_user->gender;
                    $user->whatsap_dial_code = $check_user->whatsap_dial_code;
                    $user->whatsap_phone = $check_user->whatsap_phone;
                    $user->insurence_id = $check_user->insurence_id;
                    $user->sub_insurence_id = $check_user->sub_insurence_id;
                    $user->save();

                    $token = $user->createToken($user->id.$user->name.$user->email);
                    $user->access_token = $token->plainTextToken;
                    $user->user_code = config('global.user_code_prefix').$user->id;
                    $user->save();
                    TempUsers::where(['id'=>$request->user_id])->delete();
                    $user = User::where(['id'=>$user->id])->get()->first();
                    $o_data = convert_all_elements_to_string($user->toArray());
                    $message = "Phone number verified successfully";
                    $status = "1";
                    exec("php " . base_path() . "/artisan update:firebase_node " . $user->id . " > /dev/null 2>&1 & ");
                }else{
                    $message = "invalid otp provided";
                }
            }else{
                $message = "invalid user id";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    
    public function verify_signup_otp_web(Request $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'user_id'=> 'required',
            'otp'=>'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $check_user = TempUsers::find($request->user_id);
            if($check_user){
                if($check_user->phone_otp == $request->otp){
                    //check phone number exist
                    $check_phone = User::where(['dial_code'=>$check_user->dial_code,'phone'=>$check_user->phone])->get();
                    if($check_phone->count() > 0){
                        $message = trans('messages.phone_already_exist');
                        $errors['phone'] = trans('messages.phone_already_exist');
                        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
                    }

                    $user = new User();
                    $user->user_image = $check_user->user_image;
                    $user->email      = $check_user->email;
                    $user->dial_code  = $check_user->dial_code;
                    $user->phone      = $check_user->phone;
                    $user->phone_verified = 1;
                    $user->password   = Hash::make($check_user->phone);
                    $user->name       = $check_user->first_name." ".$check_user->last_name;
                    $user->role       = USER_ROLE;
                    $user->active     = 1;
                    $user->first_name = $check_user->first_name;
                    $user->last_name  = $check_user->last_name;
                    $user->created_at = gmdate('Y-m-d H:i:s');
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    $user->user_device_token = $request->user_device_token;
                    $user->device_type = $request->device_type;
                    $user->dob        = $check_user->dob;
                    $user->gender     = $check_user->gender;
                    $user->whatsap_dial_code = $check_user->whatsap_dial_code;
                    $user->whatsap_phone = $check_user->whatsap_phone;
                    $user->insurence_id = $check_user->insurence_id;
                    $user->sub_insurence_id = $check_user->sub_insurence_id;
                    $user->is_social = $check_user->is_social;
                    $user->save();

                    $token = $user->createToken($user->id.$user->name.$user->email);
                    $user->access_token = $token->plainTextToken;
                    $user->user_code = config('global.user_code_prefix').$user->id;
                    $user->save();
                    TempUsers::where(['id'=>$request->user_id])->delete();
                    $user = User::where(['id'=>$user->id])->get()->first();
                    Auth::login($user);
                    $request->session()->put('user_id', $user->id);
                    $o_data = convert_all_elements_to_string($user->toArray());
                    $o_data['guest_booking'] = null;
                    if (($request->guest_booking_id ?? null) && session()->has($request->guest_booking_id)) {
                        $o_data['guest_booking'] = session()->get($request->guest_booking_id);
                    }
                    $message = "Phone number verified successfully";
                    $status = "1";
                    exec("php " . base_path() . "/artisan update:firebase_node " . $user->id . " > /dev/null 2>&1 & ");
                }else{
                    $message = "invalid otp provided";
                }
            }else{
                $message = "invalid user id";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function email_login_web(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            'email'=> 'required',
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $email = str_replace("+","",$request->email);
            $phone = ltrim($request->phone,"0");
            $user = User::where(['email'=>$email,'role'=>USER_ROLE])->get();
            if($user->count() > 0){
                if($user->first()->is_social == 1){
                    $message = "The email address you entered associated with social account. Kindly use social login option.";
                }else{
                    $otp = generate_otp();
                    
                    $user = User::find($user->first()->id);
                    $o_data = convert_all_elements_to_string($user->toArray());
                    //send_normal_SMS($user->dial_code.$user->phone,"OTP for verifying your account at ".config('global.site_name')." is ".$otp );
                    $user->user_email_otp = $otp;
                    $user->save();
                    $status = "1";
                    $message = "Please verify the otp sent to ur email";
                }
            }else{
                $status = "3";
                $message = "Account not found please register to continue";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function sign_in_with_phone(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'dial_code'=> 'required',
            'phone'    => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $dial_code = str_replace("+","",$request->dial_code);
            $phone = ltrim($request->phone,"0");
            $user = User::where(['dial_code'=>$dial_code,'phone'=>$phone,'role'=>USER_ROLE,'deleted'=>0])->get();
            if($user->count() > 0){
                $otp = generate_otp();
                
                $user = User::find($user->first()->id);
                send_normal_SMS("Thank you for using Mednero. Your OTP is ".$otp." and valid for 10 minutes." ,$user->dial_code.$user->phone );
                $user->user_phone_otp = $otp;
                $user->save();
                $status = "1";
                $message = "Please verify the otp sent to ur mobile number";
            }else{
                $user = User::where(['dial_code'=>$dial_code,'phone'=>$phone])->get();
                if($user->count() > 0){
                    $status = "0";
                    $message = "Sorry this account is registred as another role";
                }else{
                    $status = "3";
                    $message = "Sorry this account is not found in our  application. Please sign up to continue";
                }
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    
    public function sign_in_with_phone_web(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'dial_code'=> 'required',
            'phone'    => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $dial_code = str_replace("+","",$request->dial_code);
            $phone = ltrim($request->phone,"0");
            $phone = str_replace(" ","",$phone);
            $user = User::where(['dial_code'=>$dial_code,'phone'=>$phone,'role'=>USER_ROLE])->get();
            if($user->count() > 0){
                $otp = generate_otp();
                
                $user = User::find($user->first()->id);
                $o_data = convert_all_elements_to_string($user->toArray());
                send_normal_SMS("Thank you for using Mednero. Your OTP is ".$otp." and valid for 10 minutes." ,$user->dial_code.$user->phone );
                $user->user_phone_otp = $otp;
                $user->save();
                $status = "1";
                $message = "Please verify the otp sent to ur mobile number";
            }else{
                $status = "3";
                $message = "Sorry this account is not found in our  application. Please sign up to continue";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function verify_sign_in_with_phone_otp(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'dial_code'=> 'required',
            'phone'    => 'required',
            'otp'       => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $dial_code = str_replace("+","",$request->dial_code);
            $phone = ltrim($request->phone,"0");
            $user = User::where(['dial_code'=>$dial_code,'phone'=>$phone,'role'=>USER_ROLE])->get();
            if($user->count() > 0){
                $user =$user->first();
                if($user->user_phone_otp == $request->otp){
                    if($request->user_device_token){
                        User::where(['user_device_token'=>$request->user_device_token])->update(['user_device_token'=>'']);
                    }
                    $token = $user->createToken($user->id.$user->name.$user->email);
                    $user = User::find($user->id);
                    $user->user_device_token = $request->user_device_token;
                    $user->device_type = $request->device_type;
                    $user->access_token = $token->plainTextToken;
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    $user->save();

                    $user = User::where(['id'=>$user->id])->get()->first();
                    $o_data = convert_all_elements_to_string($user->toArray());
                    $message = "Phone number verified successfully";
                    $status = "1";
                    exec("php " . base_path() . "/artisan update:firebase_node " . $user->id . " > /dev/null 2>&1 & ");
                }else{
                    $message = "Invalid otp provided";
                }
                
            }else{
                $message = "Account not found";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
     public function confirm_email_code_web(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'email'=> 'required',
            'otp'       => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $email = str_replace("+","",$request->email);
            $user = User::where(['email'=>$email,'role'=>USER_ROLE])->get();
            if($user->count() > 0){
                $user =$user->first();
                if($user->user_email_otp == $request->otp){
                    if($request->user_device_token){
                        User::where(['user_device_token'=>$request->user_device_token])->update(['user_device_token'=>'']);
                    }
                    $token = $user->createToken($user->id.$user->name.$user->email);
                    $user = User::find($user->id);
                    $user->user_device_token = $request->user_device_token;
                    $user->device_type = $request->device_type;
                    $user->access_token = $token->plainTextToken;
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    $user->save();

                    $user = User::where(['id'=>$user->id])->get()->first();
                    Auth::login($user);
                    $request->session()->put('user_id', $user->id);
                    $o_data = convert_all_elements_to_string($user->toArray());
                    $o_data['guest_booking'] = null;
                    if (($request->guest_booking_id ?? null) && session()->has($request->guest_booking_id)) {
                        $o_data['guest_booking'] = session()->get($request->guest_booking_id);
                    }
                    $message = "Email verified successfully";
                    $status = "1";
                    exec("php " . base_path() . "/artisan update:firebase_node " . $user->id . " > /dev/null 2>&1 & ");
                }else{
                    $message = "Invalid otp provided";
                }
                
            }else{
                $message = "Account not found";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    
    public function verify_sign_in_with_phone_otp_web(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'dial_code'=> 'required',
            'phone'    => 'required',
            'otp'       => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $dial_code = str_replace("+","",$request->dial_code);
            $phone = ltrim($request->phone,"0");
            $user = User::where(['dial_code'=>$dial_code,'phone'=>$phone,'role'=>USER_ROLE])->get();
            if($user->count() > 0){
                $user =$user->first();
                if($user->user_phone_otp == $request->otp){
                    if($request->user_device_token){
                        User::where(['user_device_token'=>$request->user_device_token])->update(['user_device_token'=>'']);
                    }
                    $token = $user->createToken($user->id.$user->name.$user->email);
                    $user = User::find($user->id);
                    $user->user_device_token = $request->user_device_token;
                    $user->device_type = $request->device_type;
                    $user->access_token = $token->plainTextToken;
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    $user->save();

                    $user = User::where(['id'=>$user->id])->get()->first();
                    Auth::login($user);
                    $request->session()->put('user_id', $user->id);
                    $o_data = convert_all_elements_to_string($user->toArray());
                    $o_data['guest_booking'] = null;
                    if (($request->guest_booking_id ?? null) && session()->has($request->guest_booking_id)) {
                        $o_data['guest_booking'] = session()->get($request->guest_booking_id);
                    }

                    $message = "Phone number verified successfully";
                    $status = "1";
                    exec("php " . base_path() . "/artisan update:firebase_node " . $user->id . " > /dev/null 2>&1 & ");
                }else{
                    $message = "Invalid otp provided";
                }
                
            }else{
                $message = "Account not found";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function add_members(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'full_name.*'=> 'required',
            'gender.*'=>'required',
            'age.*'=>'required',
            'access_token'=>'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
            $full_names = $request->full_name??[];
            $gender     = $request->gender??[];
            $ages       = $request->age;
            $insurence_ids = $request->insurence_id??[];
            $sub_insurence_ids = $request->sub_insurence_id??[];
            foreach($full_names as $index=>$key){
                $member = new Members();
                $member->user_id = $user_id;
                $member->full_name = $key;
                $member->gender = $gender[$index]??0;
                $member->age    = $ages[$index]??0;
                $member->insurence_id = $insurence_ids[$index]??0;
                $member->sub_insurence_id = $sub_insurence_ids[$index]??0;
                $member->created_at = gmdate('Y-m-d H:i:s');
                $member->updated_at = gmdate('Y-m-d H:i:s');
                $member->save();
            }
            $status = "1";
            $message = "Patient added successfully";
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_my_members(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'access_token'=>'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
            $page = $request->page??1;
            $limit=$request->limit??10;
            if($page==1){
                $limit = $limit-1;
            }
            $offset = ($page -  1) * $limit;
            $list = Members::with(['insurence_policy','sub_insurence_policy'])->where(['user_id'=>$user_id])->orderBy('id','desc')->take($limit)->skip($offset)->get();
            $is_added_me = 0;
            
            if($list->count() > 0){
                $status = "1";
                $message = "data fetcehed successfully";
                
                $o_data['list'] = convert_all_elements_to_string($list->toArray());
                
                
                foreach($o_data['list'] as $k=>$v){
                    
                    
                    
                    foreach($o_data['list'] as $k=>$v){
                        if(empty($v['insurence_policy'])){
                            $o_data['list'][$k]['insurence_policy'] = (object)[];
                        }
                        if(empty($v['sub_insurence_policy'])){
                            $o_data['list'][$k]['sub_insurence_policy'] = (object)[];
                        }
                    }
                }
                if($page==1){
                    $user = User::find($user_id);
                    $insurence_p = (object)[];
                    if($user->insurence_polic){
                        $insurence_p=convert_all_elements_to_string($user->insurence_policy->toArray());
                    }
                    $sub_insurence_policy = (object)[];
                    if($user->sub_insurence_policy){
                        $sub_insurence_policy=convert_all_elements_to_string($user->sub_insurence_policy->toArray());
                    }
                    $age = '';
                    if($user->dob != ''){
                        $age = (date('Y') - date('Y',strtotime($user->dob)));
                    }
                    $me = (object)[
                        'id'=>"0",
                        'full_name'=>(string)$user->name,
                        'user_id'=>(string)$user->id,
                        'gender'=>(string)$user->gender,
                        'age'=>(string)$age,
                        'insurence_id'=>(string)$user->insurence_id,
                        'sub_insurence_id'=>(string)$user->sub_insurence_id,
                        'created_at'=>(string)$user->created_at,
                        'updated_at'=>(string)$user->updated_at,
                        'user_image'=>(string)$user->user_image,
                        'user_img_url'=>(string)$user->user_img_url,
                        'full_name_ar'=>'',
                        'insurence_policy'=>$insurence_p,
                        'sub_insurence_policy'=>$sub_insurence_policy
                    ];
                    //$list->prepend($me);
                    $is_added_me = 1;
                    array_unshift($o_data['list'],$me);
                }
            }else{
                if($page==1){
                    $user = User::find($user_id);
                    $insurence_p = (object)[];
                    if($user->insurence_polic){
                        $insurence_p=convert_all_elements_to_string($user->insurence_policy->toArray());
                    }
                    $sub_insurence_policy = (object)[];
                    if($user->sub_insurence_policy){
                        $sub_insurence_policy=convert_all_elements_to_string($user->sub_insurence_policy->toArray());
                    }
                    $age = '';
                    if($user->dob != ''){
                        $age = (date('Y') - date('Y',strtotime($user->dob)));
                    }
                    $me[] = (object)[
                        'id'=>"0",
                        'full_name'=>(string)$user->name,
                        'user_id'=>(string)$user->id,
                        'gender'=>(string)$user->gender,
                        'age'=>(string)$age,
                        'insurence_id'=>(string)$user->insurence_id,
                        'sub_insurence_id'=>(string)$user->sub_insurence_id,
                        'created_at'=>(string)$user->created_at,
                        'updated_at'=>(string)$user->updated_at,
                        'user_image'=>(string)$user->user_image,
                        'user_img_url'=>(string)$user->user_img_url,
                        'full_name_ar'=>'',
                        'insurence_policy'=>$insurence_p,
                        'sub_insurence_policy'=>$sub_insurence_policy
                    ];
                    //$list->prepend($me);
                    $is_added_me = 1;
                    //array_unshift($o_data['list'],$me);
                    $o_data['list'] = (array)$me;
                }
                $message = "no data to list";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function delete_member(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'access_token'=>'required',
            'id'    => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
            $list = Members::where(['user_id'=>$user_id,'id'=>$request->id])->get();
            if($list->count() > 0){
                Members::where(['user_id'=>$user_id,'id'=>$request->id])->delete();
                $status = "1";
                $message = "Patient deleted successfully";
            }else{
                $message = "invalid id passed, or its not your member";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_member_details(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'access_token'=>'required',
            'id'    => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
            $list = Members::with(['insurence_policy','sub_insurence_policy'])->where(['user_id'=>$user_id,'id'=>$request->id])->get();
            if($list->count() > 0){
                $o_data = convert_all_elements_to_string($list->first()->toArray());
                if(empty($o_data['insurence_policy'])){
                    $o_data['insurence_policy'] =  (object)[];
                }
                if(empty($o_data['sub_insurence_policy'])){
                    $o_data['sub_insurence_policy'] =  (object)[];
                }
                $status = "1";
                $message = "member data fetched successfully";
            }else{
                $message = "invalid id passed, or its not your member";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function update_member(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'access_token'=>'required',
            'id'    => 'required',
            'full_name'=> 'required',
            'gender'=>'required',
            'age'=>'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
            $list = Members::with(['insurence_policy','sub_insurence_policy'])->where(['user_id'=>$user_id,'id'=>$request->id])->get();
            if($list->count() > 0){
                $member = Members::find($request->id);
                $member->full_name = $request->full_name;
                $member->gender = $request->gender;
                $member->age    = $request->age??0;
                $member->insurence_id = $request->insurence_id??0;
                $member->sub_insurence_id = $request->sub_insurence_id??0;
                $member->updated_at = gmdate('Y-m-d H:i:s');
                if($file = $request->file("user_image")){
                    $file_name = time().uniqid().".".$file->getClientOriginalExtension();
                    $file->storeAs(config('global.user_image_upload_dir'),$file_name,config('global.upload_bucket'));
                    $member->user_image = $file_name;
                }
                $member->save();
                $status = "1";
                $message = "Patient updated successfully";
                $member = Members::with(['insurence_policy','sub_insurence_policy'])->where(['user_id'=>$user_id,'id'=>$request->id])->get();
                $o_data = convert_all_elements_to_string($member->first()->toArray());
            }else{
                $message = "invalid id passed, or its not your member";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_doctor_specialty(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
            
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $list = Specialty::where(['active'=>1])->orderBy('name_en','asc')->take($limit)->skip($offset)->get();
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_medical_condition(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
            
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $list = MedicalCondition::where(['status'=>1])->orderBy('title','asc')->take($limit)->skip($offset)->get();
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    
    public function get_doctor_language(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
            
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $list = Languages::where(['status'=>1])->orderBy('title','asc')->take($limit)->skip($offset)->get();
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function get_doctor_gender(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
            
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
           // $list = Languages::where(['status'=>1])->orderBy('title','asc')->take($limit)->skip($offset)->get();
           $list =["1"=>"Male","2"=>"Female","3"=>"Others"]; 
           if($list){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = $list;
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function get_doctor_country(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
            
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $list = CountryModel::where(['active'=>1])->orderBy('name','asc')->take($limit)->skip($offset)->get();
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    
    public function get_doctor_emirates(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'country_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $list = Emirate::where(['active'=>1])
            ->where('country_id',$request->country_id)
            ->take($limit)->skip($offset)->get();
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    
    public function get_doctor_area(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'emirate_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $list = Area::where(['active'=>1])
            ->where('country_id',$request->country_id)
            ->where('emirate_id',$request->emirate_id)
            ->take($limit)->skip($offset)->get();
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function get_doctor_name(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
           
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $list = $list = Doctor::with('user')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->pluck('user.name','user.id');
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function get_hospital_name(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
           
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $list = $list = Hospital::with('user')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->pluck('user.name','user.id');
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
}