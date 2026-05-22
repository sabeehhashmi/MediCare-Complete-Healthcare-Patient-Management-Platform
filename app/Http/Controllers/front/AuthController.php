<?php
// app/Http/Controllers/front/AuthController.php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InsurencePolicy;
use App\Models\SubInsurencePolicy;
use App\Models\Languages;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class AuthController extends Controller
{
    public function showAuthPage()
    {
         if (!url()->previous() || str_contains(url()->previous(), 'auth')) {
        } else {
            //session(['redirect_after_login' => url()->previous()]);
            session(['redirect_after_login' => route('front.bookings')]);
            
        }
        $insurence_list = InsurencePolicy::where(['status'=>1])->orderBy('title','asc')->get();
        $language_spoken = Languages::where(['status'=>1])->get();
        return view('front.auth', compact('insurence_list', 'language_spoken'));
    }

    private function redirectAfterLogin()
    {
        return session()->pull('redirect_after_login', route('front.bookings'));
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits_between:7,12',
            'dial_code' => 'required',
            'login_type' => 'required|in:mobile,email'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'message' => 'All fields are required!', 'errors' => $validator->messages()]);
        }

        try {
            $dial_code = $request->dial_code;
            $phone = $request->phone;
            
            // Check if user exists
            $user = User::where('dial_code', $dial_code)
                        ->where('phone', $phone)
                        ->where('role', USER_ROLE)
                        ->where('deleted', 0)
                        ->first();

            // Generate OTP
            $otp = generate_otp(4);
            
            // Store in session
            Session::put('auth_temp', [
                'dial_code' => $dial_code,
                'phone' => $phone,
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
                'user_exists' => $user ? true : false,
                'user_id' => $user->id ?? null
            ]);
            

            if($user){
                if($user->email != ''){
                    $mailbody = view('mail.login-otp', compact('user','otp'));
                    send_email($user->email,"Mednero Verification",$mailbody);
                }
            }

            // TODO: Send OTP via SMS/Email
            // For now, we'll just return the OTP for testing

            return response()->json([
                'status' => '1', 
                'message' => 'OTP sent successfully',
                'otp' => $otp, // Remove in production
                'is_new_user' => !$user
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => '0', 'message' => 'Failed to send OTP: ' . $e->getMessage()]);
        }
    }

    public function loginWithEmail(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
        'agree_terms' => 'required|accepted'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => '0',
            'message' => 'All fields are required!',
            'errors' => $validator->messages()
        ]);
    }

    try {

        $user = User::where('email', strtolower($request->email))
            ->where('role', USER_ROLE)
            ->where('deleted', 0)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => '0',
                'message' => 'Email not registered'
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => '0',
                'message' => 'Invalid password'
            ]);
        }

        // ✅ Generate OTP
        $otp = generate_otp();

        $user->user_email_otp = $otp;
    //    $user->otp_expire_at = now()->addMinutes(5);
        $user->save();

        // store guest booking temporarily
        session([
            'guest_booking_id_temp' => $request->guest_booking_id
        ]);

        // ✅ Send Email
        $mailbody = view("mail.otp_login", compact('otp'));

        send_email($user->email, 'Your Login OTP', $mailbody);

        return response()->json([
            'status' => '1',
            'message' => 'OTP sent successfully',
            'user_id' => $user->id,
            'email' => $user->email,
            'otp_required' => true
        ]);

    } catch (Exception $e) {

        return response()->json([
            'status' => '0',
            'message' => 'Login failed: ' . $e->getMessage()
        ]);
    }
}

public function verifyEmailLoginOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required',
        'otp' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => '0',
            'message' => 'Invalid request'
        ]);
    }

    $user = User::where('id', $request->user_id)
        ->where('role', USER_ROLE)
        ->first();

    if (!$user || $user->user_email_otp != $request->otp) {

        return response()->json([
            'status' => '0',
            'message' => 'Invalid OTP'
        ]);
    }

    // optional expiry check
    if ($user->otp_expire_at && now()->gt($user->otp_expire_at)) {

        return response()->json([
            'status' => '0',
            'message' => 'OTP expired'
        ]);
    }

    // ✅ Clear OTP
    $user->user_email_otp = null;
   // $user->otp_expire_at = null;
    $user->save();

    // ✅ Login
    Session::put('guest_cart_session', Session::getId());

    Auth::login($user);

    $this->mergeGuestCart();

    $guest_booking = "";

    $guest_booking_id = session('guest_booking_id_temp');

    if ($guest_booking_id && session()->has($guest_booking_id)) {
        $guest_booking = session()->get($guest_booking_id);
    }

    return response()->json([
        'status' => '1',
        'message' => 'Login successful',
        'guest_booking' => $guest_booking,
        'redirect' => $this->redirectAfterLogin()
    ]);
}

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|array|min:4',
            'otp.*' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'message' => 'Please enter valid OTP', 'errors' => $validator->messages()]);
        }

        try {
            $otp = implode('', $request->otp);
            $sessionData = Session::get('auth_temp');

            if (!$sessionData) {
                return response()->json(['status' => '0', 'message' => 'Session expired. Please try again.']);
            }

            if (Carbon::now()->gt(Carbon::parse($sessionData['expires_at']))) {
                Session::forget('auth_temp');
                return response()->json(['status' => '0', 'message' => 'OTP expired. Please request again.']);
            }

            if ($sessionData['otp'] != $otp) {
                return response()->json(['status' => '0', 'message' => 'Invalid OTP']);
            }

            // OTP verified successfully
            if ($sessionData['user_exists']) {
                // Login existing user
                $user = User::find($sessionData['user_id']);
                if ($user) {
                    Session::put('guest_cart_session', Session::getId());
                    Auth::login($user);
                    $this->mergeGuestCart();
                    Session::forget('auth_temp');
                    $guest_booking="";
                    if (($request->guest_booking_id ?? null) && session()->has($request->guest_booking_id)) {
                        $guest_booking = session()->get($request->guest_booking_id);
                    }
                    return response()->json([
                        'status' => '1',
                        'message' => 'Login successful',
                        'guest_booking' => $guest_booking,
                        //'redirect' => route('front.bookings')
                        'redirect' => $this->redirectAfterLogin()
                    ]);
                }
            }

            // New user - proceed to registration with pre-filled phone
            Session::put('auth_verified', true);
            
            return response()->json([
                'status' => '1',
                'message' => 'OTP verified successfully',
                'redirect' => 'registration',
                'guest_booking' => '',
                'phone' => $sessionData['phone'],
                'dial_code' => $sessionData['dial_code']
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => '0', 'message' => 'Verification failed: ' . $e->getMessage()]);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:1,2,3',
            'dob' => 'required|date_format:d-m-Y|before:today',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|numeric|digits_between:7,12',
            'dial_code' => 'required',
            'password' => 'nullable|min:8',
            'insurence_id' => 'nullable|integer|exists:insurence_policies,id',
            'sub_insurence_id' => 'nullable|integer|exists:sub_insurence_policies,id',
            'agree_terms' => 'required|accepted',
            'identification_type' => 'required|in:national_id,passport,driving_license,other',
            'identification_number' => 'required|string|max:100|unique:users,identification_number',
            'identification_document' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'message' => 'All fields are required!', 'errors' => $validator->messages()]);
        }

        try {
            $sessionData = Session::get('auth_temp');
            
            // If coming from OTP flow, verify the phone matches
            if ($sessionData && Session::get('auth_verified')) {
                if ($sessionData['phone'] != $request->phone || $sessionData['dial_code'] != $request->dial_code) {
                    return response()->json([
                        'status' => '0',
                        'message' => 'Phone number mismatch with verified OTP',
                        'errors' => ['phone' => 'Phone number cannot be changed after OTP verification']
                    ]);
                }
            } else {
                // Direct registration without OTP - check if phone already exists
                $existingUser = User::where('dial_code', $request->dial_code)
                                    ->where('phone', $request->phone)
                                    ->where('role', USER_ROLE)
                                    ->where('deleted', 0)
                                    ->first();
                
                if ($existingUser) {
                    return response()->json([
                        'status' => '0', 
                        'message' => 'Phone number already registered', 
                        'errors' => ['phone' => 'Phone number already registered']
                    ]);
                }
            }

            // Check if email already exists
            if ($request->email) {
                $check_email = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->first();
                if ($check_email) {
                    return response()->json([
                        'status' => '0', 
                        'message' => 'Email already registered', 
                        'errors' => ['email' => 'Email already registered']
                    ]);
                }
            }

            $documentPath = null;
            if ($request->hasFile('identification_document')) {
                $file = $request->file('identification_document');
                $fileName = 'id_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Store the file
                $file->storeAs(config('global.user_documents_dir', 'documents'), $fileName, config('global.upload_bucket'));
                $documentPath = $fileName;
            }

            // Create new user
            $user = new User();
            $user->role = USER_ROLE;
            $user->patient_id = \App\Helpers\PatientIdHelper::generatePatientId();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->gender = $request->gender;
            $user->dob = Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d');
            $user->email = strtolower($request->email);
            $user->dial_code = $request->dial_code;
            $user->phone = $request->phone;
            
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            
            $user->insurence_id = $request->insurence_id;
            $user->sub_insurence_id = $request->sub_insurence_id;
            $user->identification_type = $request->identification_type;
            $user->identification_number = $request->identification_number;
            $user->identification_document = $documentPath;
            $user->active = 1;
            $user->verified = 1;
            $user->save();

            // Clear session and login
            Session::forget(['auth_temp', 'auth_verified']);
            Session::put('guest_cart_session', Session::getId());
            Auth::login($user);
            $this->mergeGuestCart();
            // Send welcome email
            if ($user->email) {
                $this->sendWelcomeEmail($user);
            }

            return response()->json([
                'status' => '1',
                'message' => 'Registration successful',
                'redirect' => route('front.bookings')
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => '0', 'message' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            $sessionData = Session::get('auth_temp');
            
            if (!$sessionData) {
                return response()->json(['status' => '0', 'message' => 'Session expired. Please try again.']);
            }

            // Generate new OTP
            $otp = generate_otp(4);
            
            // Update session
            $sessionData['otp'] = $otp;
            $sessionData['expires_at'] = Carbon::now()->addMinutes(5);
            Session::put('auth_temp', $sessionData);

            // TODO: Send new OTP via SMS

            return response()->json([
                'status' => '1', 
                'message' => 'OTP resent successfully',
                'otp' => $otp // Remove in production
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => '0', 'message' => 'Failed to resend OTP: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('front.auth');
    }

    private function sendWelcomeEmail($user)
    {
        if($user){
            if($user->email != ''){
                $mailbody = view('mail.welcome-registration', compact('user'));
                send_email($user->email, "Welcome to " . env('APP_NAME'), $mailbody);
            }
        }
    }

    public function getSubInsurances($insurence_id)
    {
        $sub_insurances = SubInsurencePolicy::where('insurence_id', $insurence_id)
                                            ->where('status', 1)
                                            ->orderBy('title', 'asc')
                                            ->get();
        
        return response()->json($sub_insurances);
    }

    private function mergeGuestCart()
    {
        if (!Auth::check()) return;

        // Store the old session ID before login
        $oldSessionId = session()->get('guest_cart_session', session()->getId());

        // Get guest cart items
        $guest_carts = Cart::where('session_id', $oldSessionId)->get();

        foreach ($guest_carts as $guest_cart) {
            $user_cart = Cart::where('user_id', Auth::id())
                ->where('medicine_id', $guest_cart->medicine_id)
                ->first();

            if ($user_cart) {
                // Merge quantities
                $user_cart->quantity += $guest_cart->quantity;
                $user_cart->total = $user_cart->quantity * $user_cart->price;
                $user_cart->save();

                // Remove guest cart
                $guest_cart->delete();
            } else {
                // Assign to logged-in user
                $guest_cart->user_id = Auth::id();
                $guest_cart->session_id = null;
                $guest_cart->save();
            }
        }
    }
}