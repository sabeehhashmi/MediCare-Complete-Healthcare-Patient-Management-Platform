<?php

namespace App\Http\Controllers\admin;

use DB, Validator;
use Illuminate\Http\Request;
use App\Models\RolePermissions;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ForgotPassEmail;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //
    public function login()
    {
        //echo Hash::make("Hello@2026");
        //send_email_test("soorajsabu117@gmail.com","hi","test");
        if (Auth::check() && (Auth::user()->role == '1')) {
            return redirect()->route('admin.dashboard');
        }


        // echo Hash::make('Hello@1985');
        return view('admin.login');
    }

    public function checkValidLogin(Request $request) {
        if (Auth::user()->role != $request->role && Auth::user()->email != $request->email) {
            $route = '';
            if ($request->role == ADMIN_ROLE) {
                $route = url('/admin');
            }
            if ($request->role == HOSPITAL_ROLE) {
                $route = url('/hospital/login');
            }
            if ($request->role == CLINIC_ROLE) {
                $route = url('/clinic/login');
            }
            if ($request->role == DOCTOR_ROLE) {
                $route = url('/doctorlogin');
            }
            if ($request->role == CALL_CENTER_ROLE) {
                $route = url('/callcenter/login');
            }
            if ($request->role == AGENT_ROLE) {
                $route = url('/agent/login');
            }
            if ($request->role == USER_ROLE) {
                $route = url('/website');
            }

            return ['status' => 1, 'url' => $route];
        }
    }


    public function check_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
            return response()->json(['success' => false, 'message' => $errorString = implode(",", $validator->messages()->all())]);
        } else {

            // Validate request
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'deleted' => 0, 'role' => ADMIN_ROLE])) {
                if (Auth::check()) {
                    $request->session()->put('user_id', Auth::user()->id);
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
    public function logout()
    {
        session()->pull("user_id");
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)->where('role', $request->role)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        if (!$user->email_verified_at || empty($user->email_verified_at)) {
            return response()->json(['success' => false, 'message' => 'Currently your account is not verified.']);
        }

        if (!$user->active) {
            return response()->json(['success' => false, 'message' => 'Currently your account is not activated.']);
        }


        $otp = generate_otp();
        $user->user_email_otp = $otp;
        $user->save();
        $o_data = ['id' => $user->id, 'email' => $user->email];

        $mailbody = view("mail.forgot", compact('otp','user'));

    send_email($user->email, 'Reset Password Verification Email', $mailbody);

        return response()->json(['success' => true, 'message' => 'OTP sent to your email.', 'oData' => (object)$o_data]);
    }

    public function resetPassword (Request $request) {
        if (!Hash::check($request->oldPassword, Auth::user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided password does not match our records.',
            ]);
        }

        Auth::user()->update([
            'password' => Hash::make($request->newPassword),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password successfully updated!',
        ]);
    }

    public function verify_and_reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'otp' => 'required',
            'new_password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "0",
                'message' => "Validation error occurred",
                'errors' => $validator->messages()
            ]);
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'status' => "0",
                'message' => "Invalid user"
            ]);
        }

        if ($user->user_email_otp != $request->otp) {
            return response()->json([
                'status' => "0",
                'message' => "Invalid OTP provided"
            ]);
        }

        // Update the user's password
        $userRecord = User::where(['id' => $user->id])->first();

        if ($userRecord) {
            $userRecord->password = Hash::make($request->new_password);
            $userRecord->save();

            // Optionally, delete temp user record
            // TempUsers::where(['id' => $request->user_id])->delete();

            return response()->json([
                'status' => "1",
                'message' => "Password reset successfully"
            ]);
        } else {
            return response()->json([
                'status' => "0",
                'message' => "User not found"
            ]);
        }
    }
}
