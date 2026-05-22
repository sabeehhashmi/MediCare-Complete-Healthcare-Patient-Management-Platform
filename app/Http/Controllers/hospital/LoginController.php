<?php

namespace App\Http\Controllers\hospital;

use DB,Validator;
use Illuminate\Http\Request;
use App\Models\RolePermissions;
use App\Models\CountryModel;
use App\Models\Emirate;
use App\Models\Area;
use App\Models\User;
use App\Models\Hospital;
use App\Models\HospitalImage;
use App\Models\Article;
use App\Models\HospitalLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerifyEmail;

class LoginController extends Controller
{
    // update
    public function login()
    {

        
        //send_email_test("soorajsabu117@gmail.com","hi","test");
        if (Auth::check() && (Auth::user()->role == HOSPITAL_ROLE)) {
            return redirect()->route('hospital.dashboard');
        }
        
        // echo Hash::make('Hello@1985');
        return view('hospital.login');
    }
    public function Register()
    {

        
        //send_email_test("soorajsabu117@gmail.com","hi","test");
        if (Auth::check() && (Auth::user()->role == HOSPITAL_ROLE)) {
            return redirect()->route('hospital.dashboard');
        }
        $terms = Article::where('type', 1)->first();
        //  $country_list =  CountryModel::where(['active'=>1])->whereIn('prefix', ALLOWED_COUNTRIES_PREF)->get();
        $country_list =  CountryModel::where(['active'=>1])->get();
        $emirates_list=[];
        $area_list = []; 
        $selected_country = 229; 
        
        // echo Hash::make('Hello@1985');
        return view('hospital.register',compact('country_list','emirates_list','area_list','terms', 'selected_country'));
    }

    public function check_login(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)
        ->where('deleted', 0)
        ->where('role', HOSPITAL_ROLE)
        ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => "Invalid Credentials!"
        ]);
    }
    
   $user->email_verified_at=date('Y-m-d');
        
    // ✅ checks
    if (!$user->email_verified_at) {
        return response()->json([
            'success' => false,
            'message' => "Please verify your email first."
        ]);
    }

    if (!$user->active) {
        return response()->json([
            'success' => false,
            'message' => "Account under review."
        ]);
    }
    if ($user->aprroval_status!='approved') {
        return response()->json([
            'success' => false,
            'message' => "Currently your account is under review.Kindly wait for the admin approval."
        ]);
    }

    // ✅ Generate OTP
    $otp = generate_otp();

    $user->user_email_otp = $otp;
   // $user->otp_expire_at = now()->addMinutes(5); // optional
    $user->save();

    // ✅ Send email
    $mailbody = view("mail.otp_login", compact('otp'));

    send_email($user->email, 'Your Login OTP', $mailbody);

    return response()->json([
        'success' => true,
        'message' => "OTP sent to your email",
        'user_id' => $user->id,
        'email' => $user->email
    ]);
}
public function verifyLoginOtp(Request $request)
{
    $request->validate([
        'user_id' => 'required',
        'otp' => 'required'
    ]);

    $otp = $request->otp;

    $user = User::find($request->user_id);

    if (!$user || $user->user_email_otp != $otp) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid OTP'
        ]);
    }

    // ✅ optional expiry check
    if ($user->otp_expire_at && now()->gt($user->otp_expire_at)) {
        return response()->json([
            'success' => false,
            'message' => 'OTP expired'
        ]);
    }

    // ✅ LOGIN USER HERE
    Auth::login($user);

    // clear OTP
    $user->user_email_otp = null;
    $user->save();
    activity_log('login', 'User logged in');

    return response()->json([
        'success' => true,
        'message' => 'Login successful'
    ]);
}
    public function check_login_b(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password'=>'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
            return response()->json(['success' => false, 'message' => $errorString = implode(",",$validator->messages()->all())]);
        }else{

            // Validate request
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'deleted' => 0, 'role' => HOSPITAL_ROLE])) {
               
            if (Auth::check()) {
                    $request->session()->put('user_id', Auth::user()->id);
                    
                    // if(!Auth::user()->email_verified_at){
                    //     Auth::logout();
                    //     return response()->json(['success' => false, 'message' => "Sorry you are not allowed to login right now.\nKindly verify your account to get admin approval"]);
                    // }
                    if(!Auth::user()->active){
                        Auth::logout();
                        return response()->json(['success' => false, 'message' => "Currently your account is under review.\nKindly wait for the admin approval."]);
                    }
                    
                    if(Auth::user()->aprroval_status!='approved'){
                        
                        Auth::logout();
                        return response()->json(['success' => false, 'message' => "Currently your account is under review.\nKindly wait for the admin approval."]);
                    }

                    if ($request->timezone) {
                        $request->session()->put('user_timezone', $request->timezone);
                    }
                    try {
                        $permission = RolePermissions::where(['user_role_id_fk' => Auth::user()->role_id])->get();

                        if ($permission && $permission->count() > 0) {
                            $permission = $permission->toArray();
                            $user_permissions = array_column($permission, 'permissions', 'module_key');
                            $request->session()->put('user_permissions', $user_permissions);
                        } else {
                            $request->session()->put('user_permissions', []);
                        }
                    } catch (\Throwable $th) {
                        info('Error in getting permissions:: ');
                        info($th->getMessage());
                    }

                    return response()->json(['success' => true, 'message' => "Logged in successfully."]);
                } else {
                    return response()->json(['success' => false, 'message' => "Invalid Credentials!"]);
                }

            }

            return response()->json(['success' => false, 'message' => "Invalid Credentials!"]);
        }
    }
    public function logout(){
        session()->pull("user_id");
        activity_log('logout', 'User Logged Out');
        Auth::logout();
        return redirect()->route('hospital.login');
    }

    public function save_hospital(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';
        
        //sanitize pone number value
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $sanitizedDirectPhone = preg_replace('/\D/', '', $request->direct_phone ?? null);
        $request->merge(['phone' => $sanitizedPhone, 'direct_phone' => $sanitizedDirectPhone]);

        $validator = Validator::make($request->all(), [
            'name_en' => 'required',
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'image.*' => 'mimes:jpeg,png,pdf|max:2048',
            'trade_licenece' => 'mimes:jpg,jpeg,png,pdf|max:2048',
            'password' => 'required',
            'confpassword' => 'required',
            'website' => 'nullable|url',
            'dial_code' => 'nullable|numeric',
            'phone' => 'required|numeric|digits_between:8,12',
            'direct_dial_code' => 'nullable|numeric',
            'direct_phone' => 'nullable|numeric|digits_between:8,12',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $location = explode(",",$request->location);
            $latitude = $location[0];
            $longitude  = $location[1];
             $check_exist = User::where('email', $request->email)->first();
            // $check_exist = Hospital::whereHas('user', function($q) use($request){
            //     $q->whereRaw('Lower(email) = ?', [strtolower($request->email)]);
            // })
            // ->first();
           
            if(!$check_exist)
            {
                 $check_exist_phone = User::where('phone', $request->phone)->where('dial_code',$request->dial_code)->first();
                 
                // $check_exist_phone = Hospital::whereHas('user', function($q) use($request){
                //     $q->where('phone', $request->phone);
                // })
                // ->where('type', TYPE_HOSPITAL)
                // ->first();
                
                if(!$check_exist_phone)
                {
                        $user = new User();
                        $user->email    = strtolower($request->email);
                        $user->name     = $request->name_en;
                        $user->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                        $user->phone     = str_replace(" ","",ltrim($request->phone,"0"));
                        $user->password  = Hash::make($request->password);
                        $user->role      = HOSPITAL_ROLE;
                        $user->active    = 0;
                        $user->created_by = 0;
                        $user->last_updated_by = 0;
                        $user->created_at = gmdate('Y-m-d H:i:s');
                        $user->updated_at = gmdate('Y-m-d H:i:s');
                        $user->role_id     = 0;
                        $user->deleted = 0;
                        // Generate verification token
                        $verificationToken = Str::random(60);
                        $user->email_verification_token = $verificationToken;
                        if ($request->hasfile('image')) {
                            $file = $request->file('image');
                            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $user->user_image = $file_name;
                        }
                        $user->save();

                        $hospital = new Hospital();
                        $hospital->user_id   = $user->id;
                        $hospital->country_id = $request->country;
                        $hospital->emirate_id = $request->emirate_id;
                        $hospital->area_id    = $request->area_id;
                        $hospital->address    = $request->address;
                        $hospital->txt_location    = $request->txt_location;
                        $hospital->latitude    = $latitude;
                        $hospital->longitude    = $longitude;
                        $hospital->website    = $request->website;
                        $hospital->profile_description = $request->profile_bio;
                        $hospital->profile_description_ar = $request->profile_bio_ar;
                        $hospital->appointment_dial_code  = $request->direct_dial_code ? $request->direct_dial_code : DEFAULT_DIAL_CODE;
                        $hospital->appointment_phone      = str_replace(" ","",$request->direct_phone);
                        $hospital->name_en    = $request->name_en;
                        $hospital->name_ar    = $request->name_ar;

                        if ($file = $request->file("trade_licenece")) {
                            $file_name = time().uniqid().".".$file->getClientOriginalExtension();
                            $file->storeAs(config('global.trade_licenece_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $hospital->trade_licenece = $file_name;
                        }

                        $hospital->save();
                        if ($request->has('department')) {
                            // $hospital->departments()->sync($request->department);
                        }
                        if ($request->hasfile('images')) {
                            foreach ($request->file('images') as $file) {
                                $file_name2 = time().uniqid().".".$file->getClientOriginalExtension();
                                $file->storeAs(config('global.hospital_image_upload_dir'), $file_name2, config('global.upload_bucket'));
                                $image = new HospitalImage();
                                $image->hospital_id = $hospital->id;
                                $image->image_name = $file_name2;
                                $image->created_at = gmdate('Y-m-d H:i:s');
                                $image->updated_at = gmdate('Y-m-d H:i:s');
                                $image->save();
                            }
                        }

                        $locations = new HospitalLocation;
                        $locations->hospital_id = $hospital->id;
                        $locations->location = $request->txt_location;
                        $locations->latitude = $latitude;
                        $locations->longitude = $longitude;
                        $locations->created_at = gmdate('Y-m-d H:i:s');
                        $locations->updated_at = gmdate('Y-m-d H:i:s');
                        $locations->save();

                       // Send verification email
                        //Mail::to($user->email)->send(new VerifyEmail($user, 'hospital'));
                        exec("php " . base_path() . "/artisan app:send-verification-mail " . $user->id . " hospital > /dev/null 2>&1 & ");

                        $status = "1";
                        $message = "You have successfully registered with Mednero.\nKindly check your email to verify your account.";
                    }
                    else
                    {
                        $status = "0";
                        $message = "Phone number already in use";
                        $errors['phone'] = "Phone number already in use";
                    }
                }
                else
                {
                    $status = "0";
                $message = "Email already in use";
                $errors['email'] = "Email already in use";
                }


        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return view('fail-email-verification');
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        // $user->active = 0; // Set user as active
        $user->save();
        $username = $user->name ?? 'N/A';
        return view('success-email-verification', compact('username'));
    }

    public function getEmirates($countryId) {
        $emirates = Emirate::where('country_id', $countryId)->orderBy('name_en', 'asc')->get();
        return response()->json($emirates);
    }
    public function getAreas($emirateId) {
        $areas = Area::where('emirate_id', $emirateId)->get();
        return response()->json($areas);
    }

    public function terms_conditions()
    {
        $datamain = Article::find(5);
        return view('hospital.terms_and_conditions',compact('datamain'));
    }

}
