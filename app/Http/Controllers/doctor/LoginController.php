<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Hash;
use Illuminate\Support\Str;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    //
    public function login()
    {
        
        if (Auth::check() && (Auth::user()->role == DOCTOR_ROLE)) {
            return redirect()->route('admin.dashboard');
        }
        // echo Hash::make('Hello@1985');
        return view('doctor.login');
    }

    public function check_login(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)
        ->where('deleted', 0)
        ->where('role', DOCTOR_ROLE)
        ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => "Invalid Credentials!"
        ]);
    }

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
    public function check_login_(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        // Validate request
        $roles = [1, 2, 3, 4, 5, 6];
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'deleted' => 0, 'role' => DOCTOR_ROLE])) {

            if (Auth::check()) {
                if(!Auth::user()->email_verified_at){
                    Auth::logout();
                    return response()->json(['success' => false, 'message' => "Sorry you are not allowed to login right now.\nKindly verify your account to get admin approval"]);
                }
                if(!Auth::user()->active){
                    Auth::logout();
                    return response()->json(['success' => false, 'message' => "Currently your account is under review.\nKindly wait for the admin approval."]);
                }
                
                    $request->session()->put('user_id', Auth::user()->id);
                    if ($request->timezone) {
                        $request->session()->put('user_timezone', $request->timezone);
                    }
                    return response()->json(['success' => true, 'message' => "Logged in successfully."]);
                
            } elseif (Auth::check() && (Auth::user()->active == '0')) {
                return response()->json(['success' => false, 'message' => "You are blocked by admin!"]);
            }
        }

        return response()->json(['success' => false, 'message' => "Invalid Credentials!"]);
    }

    public function forgotpassword()
    {
        if (Auth::check() && (Auth::user()->role == DOCTOR_ROLE)) {
            return redirect()->route('doctor.dashboard');
        }
        
        if (Auth::check() && (Auth::user()->email_verified_at == null)) {
            return redirect()->route('doctorLogin');
        }
        
        return view('doctor.forgot');
    }

    public function check_user(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::where(['email' => $request->email])->get();
        if ($user->isNotEmpty()) {

            $token = $this->get_user_token('password_reset_code');
            $password_reset_time = gmdate('Y-m-d H:i:s');
            $user_id = User::where("email", '=', $request->email)->update(['password_reset_code' => $token, 'password_reset_time' => $password_reset_time]);

            $link = url('reset_password/' . $token);
            $mailbody =  view("emai_templates.reset_password", compact('link'));

            if (send_email($request->email, 'Reset Your Password', $mailbody)) {
                $status = "1";
                $message = "A link has been sent to your email to reset your password";
            } else {
                $status = "0";
                $message = "Email not sent";
            }

            return response()->json(['success' => true, 'message' => "We have e-mailed your password reset link. Please check your inbox."]);
        } else {
            return response()->json(['success' => false, 'message' => "E-mail not exist"]);
        }
    }

    public function get_user_token($type = '')
    {
        $tok = bin2hex(random_bytes(32));
        if (User::where($type, '=', $tok)->first()) {
            $this->get_user_token($type);
        }
        return $tok;
    }

    public function Register()
    {
        //send_email_test("soorajsabu117@gmail.com","hi","test");
        if (Auth::check() && (Auth::user()->role == DOCTOR_ROLE)) {
            return redirect()->route('doctor.dashboard');
        }

        $terms = Article::where('type', 4)->first();
        $country_list =  [];
        $emirates_list=[];
        $area_list = []; 
        $selected_country = 229; 
        
        return view('doctor.register',compact('country_list','emirates_list','area_list','terms', 'selected_country'));
    }

    public function save_doctor(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';
        //sanitize pone number value
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $request->merge(['phone' => $sanitizedPhone]);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'last_name' => 'required',
            'dial_code'=>'nullable|numeric',
            'password' => 'required|min:8',
            'confpassword' => 'required',
            'phone'=>'required|numeric|digits_between:8,12',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
                $check_exist_email = Doctor::whereHas('user', function($q) use($request){
                    $q->whereRaw('Lower(email) = ?', [strtolower($request->email)]);
                })->first();

                $check_exist_phone = Doctor::whereHas('user', function($q) use($request){
                    $q->where('phone', $request->phone);
                })->first();

                if(!$check_exist_phone && !$check_exist_email)
                {
                    $name =  $request->first_name.' '.$request->last_name;

                    $user = new User();
                    $user->name     = $name;
                    $user->first_name    = $request->first_name;
                    $user->last_name     = $request->last_name;
                    $user->email     = $request->email;
                    $user->dial_code     = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                    $user->phone         = str_replace(" ","",ltrim($request->phone,"0"));
                    $user->role          = DOCTOR_ROLE;
                    $user->password = Hash::make($request->password);
                    $user->active    = 0;
                    $user->created_by = 0;
                    $user->last_updated_by = 0;
                    $user->created_at = gmdate('Y-m-d H:i:s');
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    $user->role_id     = 0;
                    $user->deleted = 0;
                    $verificationToken = Str::random(60);
                    $user->email_verification_token = $verificationToken;
                    $user->save();

                    $doctor   = new Doctor;
                    $doctor->user_id   = $user->id;
                    // $doctor->name_en   = $name;
                    $doctor->save();
                    
                    //Mail::to($user->email)->send(new VerifyEmail($user, 'doctor'));
                    exec("php " . base_path() . "/artisan app:send-verification-mail " . $user->id . " doctor > /dev/null 2>&1 & ");

                    // $email_status = send_email($send_email_id, 'Deals Drive: Email Verification Instructions', view('mail.registration_successful', compact('user')));
                    //$new_reg = send_email('info@dealsdrive.app', 'New Registration', view('mail.new_registration_admin', compact('user')));
                    $status = "1";
                    $message = "You have successfully registered with Mednero.\nKindly check your email to verify your account.";
                }
                else
                {
                    $status = "0";
                    $message = "Phone number already using";
                    if($check_exist_phone){
                        $errors['phone'] = "Phone number already using";
                    }
                    
                    if($check_exist_email){
                        $message = "Email already using";
                        $errors['email'] = "Email already using";
                    }
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
        // $user->active = 1; // Set user as active
        $user->save();
        $username = $user->name ?? 'N/A';
        return view('success-email-verification', compact('username'));
    }
    public function logout()
    {
        session()->pull("user_id");
        activity_log('logout', 'User Logged Out');
        Auth::logout();
        
        return redirect()->route('doctorlogin.login');
    }
}
