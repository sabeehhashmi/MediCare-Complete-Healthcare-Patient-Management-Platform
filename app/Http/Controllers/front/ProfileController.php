<?php
// app/Http/Controllers/front/ProfileController.php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InsurencePolicy;
use App\Models\SubInsurencePolicy;
use App\Models\Languages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Exception;

class ProfileController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        $user = Auth::user();
        $insurence_list = InsurencePolicy::where(['status'=>1])->orderBy('title','asc')->get();
        $sub_insurence_list = [];
        
        if ($user->insurence_id) {
            $sub_insurence_list = SubInsurencePolicy::where('insurence_id', $user->insurence_id)
                                                    ->where('status', 1)
                                                    ->orderBy('title', 'asc')
                                                    ->get();
        }
        
        $language_spoken = Languages::where(['status'=>1])->get();
        
        return view('front.profile', compact('user', 'insurence_list', 'sub_insurence_list', 'language_spoken'));
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,phone',
            'value' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'message' => 'Validation error', 'errors' => $validator->messages()]);
        }

        try {
            $user = Auth::user();
            
            // Generate OTP
            $otp = generate_otp(4);
            
            // Store in session
            Session::put('profile_verification', [
                'type' => $request->type,
                'new_value' => $request->value,
                'old_value' => $request->type == 'email' ? $user->email : $user->phone,
                'dial_code' => $request->type == 'phone' ? $request->dial_code : null,
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5)
            ]);

            // TODO: Send OTP via SMS/Email
            // For email: send OTP to new email
            // For phone: send OTP to new phone number

            return response()->json([
                'status' => '1',
                'message' => 'OTP sent successfully to ' . ($request->type == 'email' ? 'your new email' : 'your new phone number'),
                'otp' => $otp // Remove in production
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => '0', 'message' => 'Failed to send OTP: ' . $e->getMessage()]);
        }
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
            $verificationData = Session::get('profile_verification');

            if (!$verificationData) {
                return response()->json(['status' => '0', 'message' => 'Session expired. Please try again.']);
            }

            if (Carbon::now()->gt(Carbon::parse($verificationData['expires_at']))) {
                Session::forget('profile_verification');
                return response()->json(['status' => '0', 'message' => 'OTP expired. Please request again.']);
            }

            if ($verificationData['otp'] != $otp) {
                return response()->json(['status' => '0', 'message' => 'Invalid OTP']);
            }

            // OTP verified successfully
            Session::put('profile_verified', $verificationData);
            Session::forget('profile_verification');

            return response()->json([
                'status' => '1',
                'message' => 'OTP verified successfully',
                'type' => $verificationData['type']
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => '0', 'message' => 'Verification failed: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:1,2,3',
            'dob' => 'required|date_format:d-m-Y|before:today',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'insurence_id' => 'nullable|integer|exists:insurence_policies,id',
            'sub_insurence_id' => 'nullable|integer|exists:sub_insurence_policies,id',
            'identification_type' => 'required|in:national_id,passport,driving_license,other',
            'identification_number' => 'required|string|max:100',
            'identification_document' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120' // 5MB max
        ];

        // Check if email is being changed
        if ($request->email != $user->email) {
            $rules['email'] = 'required|email|unique:users,email';
            
            // Verify that email OTP was verified
            $verifiedData = Session::get('profile_verified');
            if (!$verifiedData || $verifiedData['type'] != 'email' || $verifiedData['new_value'] != $request->email) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Email change requires OTP verification',
                    'errors' => ['email' => 'Please verify your new email with OTP first']
                ]);
            }
        }

        // Check if phone is being changed
        if ($request->phone != $user->phone || $request->dial_code != $user->dial_code) {
            $rules['phone'] = 'required|numeric|digits_between:7,12';
            $rules['dial_code'] = 'required';
            
            // Check if phone already exists for another user
            $existingUser = User::where('dial_code', $request->dial_code)
                                ->where('phone', $request->phone)
                                ->where('id', '!=', $user->id)
                                ->where('role', USER_ROLE)
                                ->where('deleted', 0)
                                ->first();
            
            if ($existingUser) {
                return response()->json([
                    'status' => '0', 
                    'message' => 'Phone number already registered with another account', 
                    'errors' => ['phone' => 'Phone number already registered']
                ]);
            }
            
            // Verify that phone OTP was verified
            $verifiedData = Session::get('profile_verified');
            if (!$verifiedData || $verifiedData['type'] != 'phone' || 
                $verifiedData['new_value'] != $request->phone || 
                $verifiedData['dial_code'] != $request->dial_code) {
                     $user = Auth::user();
                // ✅ Generate OTP
                    $otp = generate_otp();

                    $user->user_email_otp = $otp;
                // $user->otp_expire_at = now()->addMinutes(5); // optional
                    $user->save();

                    // ✅ Send email
                    $mailbody = view("mail.phone_change_otp", compact('otp'));

                    send_email($user->email, 'Mobile Verify Otp', $mailbody);
                return response()->json([
                    'status' => '2',
                    'message' => 'Phone number change requires OTP verification',
                    'errors' => ['phone' => 'Please verify your new phone number with OTP first']
                ]);
            }
            
        }
        if ($request->identification_number != $user->identification_number) {
            $checkIdNumber = User::where('identification_number', $request->identification_number)
                                ->where('id', '!=', $user->id)
                                ->first();
            if ($checkIdNumber) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Identification number already registered with another account',
                    'errors' => ['identification_number' => 'This document number is already registered']
                ]);
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'message' => 'Validation error', 'errors' => $validator->messages()]);
        }

        try {
            // Verify current password if changing password
            if ($request->current_password) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'status' => '0',
                        'message' => 'Current password is incorrect',
                        'errors' => ['current_password' => 'Current password is incorrect']
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
            
            // Update user
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->emergency_information = $request->emergency_information;
            $user->gender = $request->gender;
            $user->dob = Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d');
            
            // Update email only if changed and verified
            if ($request->email != $user->email) {
                $user->email = strtolower($request->email);
                $user->email_verified_at = Carbon::now(); // Mark as verified
            }
            
            // Update phone only if changed and verified
            if ($request->phone != $user->phone || $request->dial_code != $user->dial_code) {
                $user->dial_code = $request->dial_code;
                $user->phone = $request->phone;
            }
            
            if ($request->new_password) {
                $user->password = Hash::make($request->new_password);
            }
            $user->insurence_id = $request->insurence_id;
            $user->sub_insurence_id = $request->sub_insurence_id;
            $user->identification_type = $request->identification_type;
            $user->identification_number = $request->identification_number;
            $user->identification_document = $documentPath;
            $user->save();

            // Clear verification session
            Session::forget('profile_verified');

            return response()->json([
                'status' => '1',
                'message' => 'Profile updated successfully',
                'redirect' => route('front.profile')
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => '0', 'message' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            $verificationData = Session::get('profile_verification');
            
            if (!$verificationData) {
                return response()->json(['status' => '0', 'message' => 'Session expired. Please try again.']);
            }

            // Generate new OTP
            $otp = generate_otp(4);
            
            // Update session
            $verificationData['otp'] = $otp;
            $verificationData['expires_at'] = Carbon::now()->addMinutes(5);
            Session::put('profile_verification', $verificationData);

            // TODO: Send new OTP via SMS/Email

            return response()->json([
                'status' => '1',
                'message' => 'OTP resent successfully',
                'otp' => $otp // Remove in production
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => '0', 'message' => 'Failed to resend OTP: ' . $e->getMessage()]);
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

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '0',
                'message' => 'Validation error',
                'errors' => $validator->messages()
            ]);
        }

        try {
            $user = Auth::user();
            
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                
                // Delete old image if exists (optional)
                // if ($user->user_image) {
                //     Storage::disk(config('global.upload_bucket'))->delete(config('global.user_image_upload_dir') . '/' . $user->user_image);
                // }
                
                $user->user_image = $file_name;
                $user->save();
                
                return response()->json([
                    'status' => '1',
                    'message' => 'Profile image updated successfully',
                    'image_url' => $user->user_img_url
                ]);
            }
            
            return response()->json([
                'status' => '0',
                'message' => 'No image file uploaded'
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ]);
        }
    }

        public function sendPhoneOtp(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'dial_code' => 'required'
    ]);

    $user = Auth::user();

    // already same phone
    if (
        $user->phone == $request->phone &&
        $user->dial_code == $request->dial_code
    ) {

        return response()->json([
            'status' => '0',
            'message' => 'This is already your current phone number'
        ]);
    }

    // generate otp
    $otp = generate_otp();

    $user->user_email_otp = $otp;

    $user->save();

    // send email
    $mailbody = view(
        'mail.phone_change_otp',
        compact('otp')
    )->render();

    send_email(
        $user->email,
        'Phone Change Verification OTP',
        $mailbody
    );

    return response()->json([
        'status' => '1',
        'message' => 'OTP sent successfully',
        'user_id' => $user->id
    ]);
}

public function verifyPhoneOtp(Request $request)
{
    
    $request->validate([
        'phone' => 'required',
        'dial_code' => 'required',
        'otp' => 'required|array'
    ]);

    $otp = implode('', $request->otp);

   $user = Auth::user();

    if (!$user || $user->user_email_otp != $otp) {

        return response()->json([
            'status' => '0',
            'message' => 'Invalid OTP'
        ]);
    }

    // expiry check

    /*
    |--------------------------------------------------------------------------
    | VERIFIED
    |--------------------------------------------------------------------------
    */

   

    $user->phone = $request->phone;

    $user->dial_code = $request->dial_code;


    $user->save();

    return response()->json([
        'status' => '1',
        'message' => 'OTP verified successfully'
    ]);
}
}