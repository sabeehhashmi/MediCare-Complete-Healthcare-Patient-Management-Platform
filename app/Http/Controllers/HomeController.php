<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CountryModel;
use App\Models\Specialty;
use App\Models\InsurencePolicy;
use App\Models\SubInsurencePolicy;
use App\Models\MedicalCondition;
use App\Models\Languages;
use App\Models\CountryOfOrigin;
use App\Models\Emirate;
use App\Models\Area;
use App\Models\ContactUsSetting;
use App\Models\Hospital;
use App\Models\SpecialIntrests;
use App\Models\Doctor;
use App\Models\DoctorHolidays;
use App\Models\DoctorAvailability;
use App\Models\DoctorPatientAppointment;
use App\Models\DoctorTemporaryUnavailable;
use App\Models\HpManagement;
use App\Models\HpPartnerLogo;
use App\Models\SettingsModel;
use App\Models\User;
use App\Models\Members;
use App\Models\DoctorAppointmentsStatus;
use Carbon\Carbon;
use Validator,DB;
use DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\DeleteAccountEmail;
use App\Models\Article;
use App\Models\FaqForDoctorModel;
use App\Models\FaqForHospitaModel;
use App\Models\FaqModel;
use App\Models\HpSlide;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\ClinicInstruction;
use App\Models\HospitalInstruction;
use App\Models\DoctorInstruction;
use App\Models\UserInstruction;

class HomeController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->scopes(['profile', 'email'])->redirect();
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(REQUEST $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $email =  $googleUser->getEmail();
            $name = $googleUser->getName();
            $user_check = User::where(['email'=>strtolower($email)])->get();
            if($user_check->count() > 0){
                $user=$user_check->first();
                if($user->is_social == 1){
                    if($user->role == USER_ROLE){
                         Auth::login($user);
                         return redirect('/website');
                    }else{
                        return redirect(route('patient.login'))->with('message', 'Your are not registred as a patient');
                    }
                }else{
                    return redirect(route('patient.login'))->with('message', 'The email address you entered is not  associated with social account. Kindly use email login option.');
                }
            }else{
                $nameParts = explode(' ', $name);
                $last_name =array_pop($nameParts);

                $first_name = str_replace($last_name,"",$name);
                $page_heading = "Patient Signup";
                $is_social = 1;
                $insurencePolicies = InsurencePolicy::withCount(['sub_insurence_policy'])->where(['status'=>1])->orderBy('title','asc')->get();
                return view('web.create-account', compact('page_heading','is_social','email','last_name','first_name','insurencePolicies'));
            }
        } catch (\Exception $e) {
           return redirect(route('patient.login'));
        }
    }
    public function web(){
        $page_heading = "Home";
        return redirect(route('admin.login'));
        return view('frond.home',compact('page_heading'));
    }
    
    public function index(Request $request){
        return redirect(route('admin.login'));
        $specialties = Specialty::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
        $insurencePolicies = InsurencePolicy::where('status', 1)->orderBy('title')->get()->pluck('title', 'id');
        $subInsurencePolicies = SubInsurencePolicy::where('status', 1)->orderBy('title')->get()->pluck('title', 'id');
        $medicalConditions = SpecialIntrests::where('status', 1)->orderBy('title')->get()->pluck('title', 'id');
        $languages = Languages::orderBy('title')->get()->pluck('title', 'id');
        $countries = CountryOfOrigin::orderBy('name')->get()->pluck('name', 'id');
        $genders = [1 => 'Male', 2 => 'Female', 3 => 'Others'];
        $emirates = Emirate::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
        $areas = Area::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
        $hospitals = Hospital::whereHas('user', function ($q) {
            $q->where('active', 1);
        })->has('doctors')
        ->whereHas('doctors',function($q){
            $need_date = date('Y-m-d');
            // $q->when($need_date,function($query) use($need_date){
            //     $dayName = strtolower(date('l', strtotime($need_date)));
            //     $query->whereIn('doctors.id', DoctorAvailability::where($dayName . '_availability', 1)->select('doctor_id'))
            //     ->whereNotIn('doctors.id', DoctorHolidays::whereDate('holiday_date', '=', $need_date)->select('doctor_id'));
            // });
        })
        ->orderBy('name_en')->get()->pluck('name_en', 'id');


        // Get all homepage data
        $homepageData = HpManagement::getAllMeta();

        // Partner logos
        $partnerLogos = HpPartnerLogo::where('status', 1)->get();
        //printr($partnerLogos->toArray());
        // Homepage slides
        $homepageSlides = HpSlide::where('status', 1)->get();

       $contactUsSettings = ContactUsSetting::first()->toArray();

       $settings = SettingsModel::first();
        $max_radius = $settings->doctor_search_radius;

        // $doctors = User::whereHas('doctor', function ($q) {
        //     $q->where('active', 1);
        // })->where('role', DOCTOR_ROLE)->get()->pluck('name', 'id');
        $page_heading = "Home";

        return view('web.home',compact('page_heading', 
        'specialties',
        'insurencePolicies',
        'subInsurencePolicies',
        'medicalConditions',
        'languages',
        'countries',
        'genders',
        'emirates',
        'areas',
        'hospitals',
        'homepageData',
        'contactUsSettings',
        'partnerLogos',
        'homepageSlides',
        'max_radius'
        
    ));
    }
    public function register(){
        $page_heading = "Register";
        $country_list = CountryModel::where(['active'=>1])->get();
        return view('frond.register',compact('page_heading','country_list'));
    }
    
    public function book_appointment(Request $request, $doctor_id){
        $page_heading = "Book Appointment";
        $currentLatitude = $request->session()->get('current_latitude');
        $currentLongitude = $request->session()->get('current_longitude');
        // dd($currentLatitude);
        $doctor = Doctor::with(['hospital.location' => function($q) use($currentLatitude, $currentLongitude) {
            $q->select('*');
            if($currentLatitude && $currentLongitude){
                $distance =
                    "6371 * acos (
                    cos ( radians( CAST (latitude AS double precision) ) )
                    * cos( radians( CAST ({$currentLatitude} AS double precision) ) )
                    * cos( radians( CAST ({$currentLongitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                    + sin ( radians( CAST (latitude AS double precision) ) )
                    * sin( radians ( CAST ({$currentLatitude} AS double precision) ) )
                )";
                $q->selectRaw("$distance as distance");
            }
        }])->whereHas('user', function ($q) {
            $q->where('active', 1)->where('deleted', 0);
        })->where('id', $doctor_id)->first();
    
        $patient = Auth::User();
        $time_slot = TIME_SLOTS;
        $insurencePolicies = InsurencePolicy::get();
        return view('web.book-appointment',compact('page_heading','doctor', 'time_slot', 'patient', 'insurencePolicies'));
    }
    
    public function overview_booking(Request $request){
        $currentLatitude = $request->session()->get('current_latitude');
        $currentLongitude = $request->session()->get('current_longitude');
    
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        
        // Validation rules
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'numeric',
            'booking_time_slot' => 'required',
            'booking_date' => 'required|date_format:d-m-Y'
        ]);
    
        $doctor = Doctor::with(['hospital.location' => function($q) use($currentLatitude, $currentLongitude) {
            $q->select('*');
            if($currentLatitude && $currentLongitude){
                $distance =
                    "6371 * acos (
                    cos ( radians( CAST (latitude AS double precision) ) )
                    * cos( radians( CAST ({$currentLatitude} AS double precision) ) )
                    * cos( radians( CAST ({$currentLongitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                    + sin ( radians( CAST (latitude AS double precision) ) )
                    * sin( radians ( CAST ({$currentLatitude} AS double precision) ) )
                )";
                $q->selectRaw("$distance as distance");
            }
        }])->whereHas('user', function ($q) {
            $q->where('active', 1)->where('deleted', 0);
        })->where('user_id', $request->doctor_id ?? null)->first();
    
        if ($validator->fails()) {
            $page_heading = "Book Appointment";
            $patient = Auth::User();
            $time_slot = TIME_SLOTS;
            $insurencePolicies = InsurencePolicy::get();
            
            return view('web.book-appointment', compact('page_heading', 'doctor', 'time_slot', 'patient', 'insurencePolicies'))
                    ->withErrors($validator);
        }
    
        $bookingData = $request->all();
        $bookingData['hospital_id'] = $doctor->hospital_id;
        
        if (!Auth::check() || Auth::User()->role != USER_ROLE) {
            $guestBookingId = uniqid('guest_booking_', true);
            session([$guestBookingId => $bookingData]);
            
            $page_heading = "Patient Login";
            return view('web.login', compact('page_heading', 'guestBookingId'));
        }
        
        $bookingData['patient'] = Auth::User();
        
        if($request->patient != Auth::User()->id){
            $bookingData['member_id'] = $request->patient_id;
            $bookingData['member'] = Members::find($request->patient_id);
        }
        $request->session()->put(Auth::User()->id.'_booking', $bookingData);
        $page_heading = 'Overview Booking';
        return view('web.overview-booking', compact('page_heading', 'bookingData', 'doctor'));
    }
    
    public function guest_overview_booking(Request $request){
        $bookingData = $request->session()->get(Auth::User()->id.'_booking');
        $currentLatitude = $request->session()->get('current_latitude');
        $currentLongitude = $request->session()->get('current_longitude');
        $patient = Auth::User();
        $member = null;
        if($bookingData){
            if(($bookingData['patient_id'] ?? null) != $patient->id){
                $member = Members::find($bookingData['patient']);
            }
        }else{
            $bookingData = $request->session()->get($request->guest_booking_id ?? null);
            if(!$bookingData){
                return redirect()->route('home');
            }
            $bookingData['patient_id'] = $patient->id;
            $request->session()->pull($request->guest_booking_id);
            $request->session()->put(Auth::User()->id.'_booking', $bookingData);
        }

        $bookingData['patient'] = $patient;
        $bookingData['member_id'] = $member->id ?? null;
        $bookingData['member'] = $member;
    
        $doctor = Doctor::with(['hospital.location' => function($q) use($currentLatitude, $currentLongitude) {
            $q->select('*');
            if($currentLatitude && $currentLongitude){
                $distance =
                    "6371 * acos (
                    cos ( radians( CAST (latitude AS double precision) ) )
                    * cos( radians( CAST ({$currentLatitude} AS double precision) ) )
                    * cos( radians( CAST ({$currentLongitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                    + sin ( radians( CAST (latitude AS double precision) ) )
                    * sin( radians ( CAST ({$currentLatitude} AS double precision) ) )
                )";
                $q->selectRaw("$distance as distance");
            }
        }])->whereHas('user', function ($q) {
            $q->where('active', 1)->where('deleted', 0);
        })->where('user_id', $bookingData['doctor_id'] ?? null)->first();

        $page_heading = 'Overview Booking';
        // dd($bookingData);
        return view('web.overview-booking', compact('page_heading', 'bookingData', 'doctor', 'patient'));
    }
     
    public function book_appointment_save(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $message = "Appointment Booked Successfully";
        $bookingData = $request->session()->get(Auth::User()->id.'_booking');
        $request->session()->pull(Auth::User()->id.'_booking');
        if($bookingData && $bookingData['booking_date'] && $bookingData['doctor_id'] && $bookingData['booking_time_slot']){
            $FourDigitRandomNumber = rand(1231, 7879);
            // Create a new appointment
            $doctor = new DoctorPatientAppointment();
            $doctor->booking_id = '#MED' . $FourDigitRandomNumber;
            $doctor->member_id = '0';
            $doctor->created_at = gmdate('Y-m-d H:i:s');
            
            $doctor_data = Doctor::where('user_id', $bookingData['doctor_id'])->first();
            // Common fields for both add and update
            $doctor->doctor_id = $doctor_data->id;
            $doctor->hospital_id = $bookingData['hospital_id'];
            $doctor->user_id = $bookingData['patient_id'] ?? Auth::User()->id;
            $doctor->booking_date = \Carbon\Carbon::createFromFormat('d-m-Y', $bookingData['booking_date'])->format('Y-m-d');
            $doctor->booking_time_slot = $bookingData['booking_time_slot'];
            $doctor->booking_status = BOOKING_STATUS_PENDING;
    
            if($bookingData['member_id'] ?? null){
                $member = Members::find($bookingData['member_id']);
                $doctor->member_id = $member->id??0;
            }
    
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
    
            // Update member_id if provided
            // if ($bookingData['has']('member')) {
            //     $doctor->member_id = $bookingData['member'];
            // }
            $doctor->created_by = Auth::User()->id;
            $doctor->save();
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
            $status = "1";
            $o_data['redirect'] = '';
            $o_data['data'] = $doctor;
        }else{
            $status = "0";
            $o_data['redirect'] = '';
            $message = "Cannot Book this Appointment";
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data]);
    }
    
    public function signup(REQUEST $request){
        if (Auth::check() && Auth::User()->role == USER_ROLE) {
            return redirect('/website');
        }
        $page_heading = "Patient Signup";
        $insurencePolicies = InsurencePolicy::withCount(['sub_insurence_policy'])->where(['status'=>1])->orderBy('title','asc')->get();
        return view('web.create-account', compact('page_heading','insurencePolicies'));
    }
    public function login(Request $request)
    {
        if (Auth::check() && Auth::User()->role == USER_ROLE) {
            return redirect('/website');
        }
        $page_heading = "Patient Login";
        return view('web.login', compact('page_heading'));
    }
    
    public function deleteAccount(Request $request)
    {
        $page_heading = "Delete Account";
        return view('web.deleteAccount', compact('page_heading'));
    }
    
    public function profile(Request $request)
    {
        if (!Auth::check() || Auth::User()->role != USER_ROLE) {
            return redirect('/website/patient-login');
        }

        $page_heading = "Profile";
        $user = User::find(Auth::User()->id);
        $insurencePolicies = InsurencePolicy::withCount(['sub_insurence_policy'])->where(['status'=>1])->orderBy('title','asc')->get();
        $sub_insurence = SubInsurencePolicy::where(['insurence_id'=>$user->insurence_id,'status'=>1])->get();
        return view('web.user-profile', compact('page_heading', 'user','insurencePolicies','sub_insurence'));
    }
    
    public function profileSave(Request $request)
    {
        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $sanitizedWhatsapPhone = preg_replace('/\D/', '', $request->whatsap_phone ?? null);
        $request->merge(['phone' => $sanitizedPhone, 'whatsap_phone' => $sanitizedWhatsapPhone]);

        $o_data['redirect'] = route('hospital.get_profile');
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['nullable', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'image' => 'mimes:jpeg,png,pdf|max:2048',
            'dial_code' => 'required|numeric',
            'phone' => 'required|numeric|digits_between:8,12',
            'whatsap_dial_code' => 'nullable|numeric',
            'whatsap_phone' => '|numeric|digits_between:8,12',
            'dob' => 'required|date_format:d-m-Y|before:today'
        ];
        // dd($request->all());
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => '0', 'message' => 'Kindly check the filed validations', 'errors' => $validator->messages()]);
        }

        // Check for unique email
        $id = Auth::User()->id;
        if($request->email != ''){
            $check_email = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->where('id', '!=', $id)->first();

            if ($check_email) {
                return response()->json(['success' => '0', 'message' => 'Email id already registered with us', 'errors' => ['email' => 'Email id already registered with us']]);
            }
        }

        // Check for unique phone number
        if ($request->dial_code && $request->phone) {
            $check_phone = User::where('dial_code', $request->dial_code)->where('phone', $request->phone)->where('id', '!=', $id)->first();

            if ($check_phone) {
                return response()->json(['success' => '0', 'message' => 'Phone number already registered with us', 'errors' => ['phone' => 'Phone number already registered with us']]);
            }
        }
        $user = User::find($id);
        
        if(($request->phone != $user->phone || $request->email != $user->email)){
            if(!$request->otp){
                $otp = generate_otp();
                $user->user_phone_otp = $otp;
                $user->user_email_otp = $otp;
                $user->save();
                return response()->json(['success' => '3', 'message' => 'OTP is Required.']);
            }else{
                if($user->user_phone_otp == $request->otp  && $user->user_email_otp == $request->otp){
                    $user->user_phone_otp = null;
                    $user->user_email_otp = null;
                    $user->save();
                }else{
                    // return response()->json(['success' => '0', 'message' => 'OTP is Invalid.']);
                }
            }
        }

        $user->email = strtolower($request->email);
        // Handle image upload
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $user->user_image = $file_name;
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->gender = $request->gender;
        // dd(\Carbon\Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d'));
        $user->dob = \Carbon\Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d');
        $user->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
        $user->phone = str_replace(" ", "", ltrim($request->phone, "0"));
        $user->whatsap_phone = str_replace(" ", "", ltrim($request->whatsap_phone, "0"));
        $user->whatsap_dial_code = $request->whatsap_dial_code;
        $user->insurence_id = $request->insurence_id??0;
        $user->sub_insurence_id = $request->sub_insurence_id??0;
        $user->deleted = 0;
        $user->save();

        $status = '1';
        $message = "Profile Updated Successfully";
        return response()->json(['success' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function appointments(Request $request)
    {
        if (!Auth::check() || Auth::User()->role != USER_ROLE) {
            return redirect('/website/patient-login');
        }

        $page_heading = "Appointments";
        $user = User::find(Auth::User()->id);
        return view('web.user-appointments', compact('page_heading', 'user'));
    }

    public function bookingDetail(Request $request, $id){
        $appointment = DoctorPatientAppointment::where('id', $id)->first();
        $hospital = null;
        $spc_hospital_id = null;
        $clinic = null;
        $doctor = null;
        $patient = null;
        $page_heading = 'Appointments';

        $time_slot = TIME_SLOTS;
        return view('web.appointment-detail',compact('id','appointment', 'page_heading', 'doctor', 'clinic', 'hospital', 'time_slot', 'spc_hospital_id', 'patient'));
    }

    public function rescheduleAppointment(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $loginuserid = Auth::id();
        // dd($request->all());
        // Validation rules
        $validator = Validator::make($request->all(), [
            // 'doctor_id' => 'required|exists:users,id',
            'id' => 'required|exists:doctor_patient_appointments,id',
            'booking_time_slot' => 'required',
            'reschedule_date' => 'required|date_format:d-m-Y',
            'reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $bookingId = $request->id ?? null;
            $appointment = DoctorPatientAppointment::find($bookingId);

            if (!$appointment) {
                return response()->json(['status' => '0', 'message' => 'Appointment not found', 'errors' => ['id' => 'Appointment not found']]);
            }

            $message = "Appointment Updated Successfully";

            $appointment->reason_reschedule = $request->reason;
            $appointment->previous_booking_date = $appointment->booking_date;
            $appointment->previous_booking_time_slot = $appointment->booking_time_slot;
            $appointment->booking_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->reschedule_date)->format('Y-m-d');
            $appointment->booking_time_slot = $request->booking_time_slot;
            $appointment->booking_status = BOOKING_STATUS_RESCHEDULED;
            $appointment->updated_at = gmdate('Y-m-d H:i:s');

            // Update member_id if provided
            // if ($request->has('member')) {
            //     $appointment->member_id = $request->member;
            // }

            $appointment->save();
            $this->addAppointmentHistory($appointment->id, $appointment->booking_status, auth()->user()->id);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $appointment->id . " > /dev/null 2>&1 & ");

            $status = "1";
            $o_data['redirect'] = url('/website/patient-appointment');
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    public function patientAppointmentCancel(REQUEST $request){
        try {
            $doctor = DoctorPatientAppointment::find($request->appointment_id);     
            $doctor->booking_status   = BOOKING_STATUS_CANCELLED;
            $doctor->reason_cancel  = $request->reason_cancel;
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->save();
            $this->addAppointmentHistory($doctor->id, $doctor->booking_status, auth()->user()->id);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor InstantAppointment Canceled Successfully";
            return response()->json( [ 'success' => $message ] );
        } catch (Exception $e) {
            return response()->json( [ 'sorry' => 'Unable to cancel this appointment!' ] );
        }
    }

    public function patientMembers(Request $request)
    {
        if (!Auth::check() || Auth::User()->role != USER_ROLE) {
            return redirect('/website/patient-login');
        }

        $page_heading = "Patients";
        $user = User::find(Auth::User()->id);
        $insurencePolicies = InsurencePolicy::get();
        return view('web.user-members', compact('page_heading', 'user', 'insurencePolicies'));
    }

    public function change_password(Request $request)
    {
        if ($request->isMethod('post')) {
            $status = "0";
            $message = "";
            $o_data['redirect'] = route('hospital.change_password');
            $errors = [];
            $validator = Validator::make($request->all(), [
                'cur_pswd' => 'required',
                'new_pswd' => 'required', 
                'confirm' => 'required|same:new_pswd', // Ensure new password and confirm match
            ], [
                'cur_pswd.required' => 'Current password is required',
                'new_pswd.required' => 'New password is required',
                'confirm.required' => 'Confirm password is required',
                'confirm.same' => 'New password and Confirm password must match',
            ]);
            if ($validator->fails()) {
                $status = "0";
                $message = "Validation error occured";
                $errors = $validator->messages();
            } else {
                $cur_pswd = $request->cur_pswd;
                $new_pswd = $request->new_pswd;
                $user_id = session("user_id");
                if (!Auth::attempt(['id' => $user_id, 'password' => $cur_pswd])) {
                    $validator->errors()->add('cur_pswd', 'Current password is not matched.');
                    $status = "0";
                    $message = "Validation error occured";
                    $errors = $validator->messages();
                } else{
                    $up['password'] = bcrypt($new_pswd);
                    $up['updated_on'] = gmdate('Y-m-d H:i:s');
                    if (User::update_password($user_id, $new_pswd)) {
                        $status = "1";
                        $message = "Password successfully changed";
                        $errors = '';
                    } else {
                        $status = "0";
                        $message = "Unable to change password. Please try again later";
                        $errors = '';
                    }
                }
            }
            echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors,'oData' => $o_data]);
            die();
        } else {
            $page_heading = "Change Password";
            $loginuserid = Auth::id();
            return view("web.change_password", compact('page_heading','page_heading'));
        }
    }
    
    public function deleteAccountSubmit(Request $request)
    {
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $request->merge(['phone' => $sanitizedPhone]);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'dial_code' => 'nullable|numeric',
            'phone' => 'nullable|numeric|digits_between:8,12'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => '0', 'message' => 'Validation error occurred.', 'errors' => $validator->messages()]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => '0', 'message' => 'User not found.']);
        }
        
        $o_data = ['id' => $user->id, 'email'=> $user->email];

        Mail::to(env('MAIL_USERNAME'))->send(new DeleteAccountEmail($request->all()));

        return response()->json(['success' => '1', 'message' => 'Your Request for Delete Account is Submitted.']);
    }

    public function logout(){
        session()->pull("user_id");
        session()->pull(Auth::User()->id.'_booking');
        Auth::logout();
        return redirect()->route('home');
    }

    public function doctor_list_old(Request $request) {
        // dd($request->all());
        $requestParams = $request->all();

        $current_lattiude = $request->current_latitude??'';
        $current_longitude = $request->current_longitude??'';
        
        if ($request->has('current_latitude') && $request->has('current_longitude')) {
            $request->session()->put('current_latitude', $request->current_latitude);
            $request->session()->put('current_longitude', $request->current_longitude);
        }

        $specialties = Specialty::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
        $insurencePolicies = InsurencePolicy::where('status', 1)->orderBy('title')->get()->pluck('title', 'id');
        $subInsurencePoliciesq = [];
        if ($request->insurance_id ?? null) {
            $subInsurencePolicies = SubInsurencePolicy::where('status', 1)->where('insurence_id', $request->insurance_id)->orderBy('title')->get()->pluck('title', 'id');
        }

        $medicalConditions = SpecialIntrests::where('status', 1)->orderBy('title')->get()->pluck('title', 'id');
        $languages = Languages::orderBy('title')->get()->pluck('title', 'id');
        $countries = CountryOfOrigin::orderBy('name')->get()->pluck('name', 'id');
        $genders = [1 => 'Male', 2 => 'Female', 3 => 'Others'];
        $emirates = Emirate::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
        $areas = Area::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
        // Hospital quesr
        $hospitalQ = Hospital::whereHas('user', function ($q) {
            $q->where('active', 1);
        });
        if ($request->insurance_id ?? null) {
            $hospitalQ->whereHas('insurences', function ($q) use ($request) {
                $q->where('insurance_id', $request->insurance_id);
            });
        }
        
        if ($request->sub_insurance_id ?? null) {
            $hospitalQ->whereHas('insurences', function ($q) use ($request) {
                $q->where('sub_insurance_id', $request->sub_insurance_id);
            });
        }
        
        if ($request->emirates_id ?? null) {
            $hospitalQ->where('emirate_id', $request->emirates_id);
        }
        
        if ($request->area_id ?? null) {
            $hospitalQ->where('area_id', $request->area_id);
        }

        $hospitals = $hospitalQ->orderBy('name_en')->get()->pluck('name_en', 'id');
        // Hospital query end

        $doctor_name = $request->doctor_name ?? null;
        $speciality_id = $request->speciality_id;
        $gender=$request->gender ?? null;
        $doctor_language=$request->doctor_language ?? null;
        $medical_condition = $request->medical_condition ?? null;
        $country_id = $request->country_id ?? null;
        $hospital_id =$request->hospital_id ?? null;
        $emirate_id=$request->emirate_id ?? null;
        $area_id = $request->area_id ?? null;
        $main_insurence_id = $request->insurence_id ?? null;
        $sub_insurance_id=$request->sub_insurance_id ?? null;
        $filter_distance = $request->distance;
        $direct_call_enabled = $request->dirent_call_for_appointment ?? null;
        $instend_need = $request->ready_to_consult_instantly ?? null;
        $need_date = $request->date;

        if($need_date){
            $need_date = date('Y-m-d',strtotime($need_date));
        }else{
            $need_date = date('Y-m-d');
        }
        
        $query = Doctor::with(['hospital.location' => function($q) use($request){
                $q->select('*');
                if($request->current_latitude ?? null && $request->current_longitude ?? null){
                    $distance =
                        "6371 * acos (
                        cos ( radians( CAST (latitude AS double precision) ) )
                        * cos( radians( CAST ({$request->current_latitude} AS double precision) ) )
                        * cos( radians( CAST ({$request->current_longitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                        + sin ( radians( CAST (latitude AS double precision) ) )
                        * sin( radians ( CAST ({$request->current_latitude} AS double precision) ) )
                    )";
                    $q->selectRaw("$distance as distance");
                }
            }])->whereHas('user', function ($q) {
            $q->where('active', 1)->where('deleted', 0);
        })->leftJoin('hospital_locations', 'doctors.hospital_id', '=', 'hospital_locations.hospital_id');
    
        // Apply filters
        if ($request->specialty_id ?? null) {
            $query->whereHas('doctorSpecialities', function ($q) use ($request) {
                $q->where('speciality_id', $request->specialty_id);
            });
        }
    
        if ($request->insurance_id ?? null) {
            $query->whereHas('hospital.insurences', function ($q) use ($request) {
                $q->where('insurance_id', $request->insurance_id);
            });
        }
    
        if ($request->sub_insurance_id ?? null) {
            $query->whereHas('hospital.insurences', function ($q) use ($request) {
                $q->where('sub_insurance_id', $request->sub_insurance_id);
            });
        }
    
        if ($request->medical_condition_id ?? null) {
            $query->whereHas('doctorIntrests', function ($q) use ($request) {
                $q->where('special_intrest_id', $request->medical_condition_id);
            });
        }
    
        if ($request->language_id ?? null) {
            $query->whereHas('doctorLanguageSpoken', function ($q) use ($request) {
                $q->where('language_spoken_id', $request->language_id);
            });
        }
    
        if ($request->cuntry_of_origin_id ?? null) {
            $query->where('country_of_orgin', $request->cuntry_of_origin_id);
        }
    
        if ($request->gender_id ?? null) {
            $query->where('gender', $request->gender_id);
        }
    
        if ($request->emirates_id ?? null) {
            $query->whereHas('hospital', function ($q) use ($request) {
                $q->where('emirate_id', $request->emirates_id);
            });
        }
    
        if ($request->area_id ?? null) {
            $query->whereHas('hospital', function ($q) use ($request) {
                $q->where('area_id', $request->area_id);
            });
        }
    
        if ($request->hospital_id ?? null) {
            $query->where('hospital_id',$request->hospital_id);
        }
    
        if ($request->doctor_name ?? null) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('user.name', 'like', '%' . $request->doctor_name . '%');
            });
        }
        
        if ($request->dirent_call_for_appointment ?? null) {
            $query->whereNotNull('appointment_phone');
        }
       
        if ($request->ready_to_consult_instantly ?? null) {
            $query->whereHas('doctorInstantAppointmentToday');
        }
    
        if (($request->distance ?? null) && ($request->current_latitude ?? null) && ($request->current_longitude)) {
            $query->whereHas('hospital.location',function($q) use($request){
                $distance =
                    "6371 * acos (
                    cos ( radians( CAST (latitude AS double precision) ) )
                    * cos( radians( CAST ({$request->current_latitude} AS double precision) ) )
                    * cos( radians( CAST ({$request->current_longitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                    + sin ( radians( CAST (latitude AS double precision) ) )
                    * sin( radians ( CAST ({$request->current_latitude} AS double precision) ) )
                )";
                $q->whereRaw("($distance) <= ".($request->distance));
            });
        }

        $query->select('doctors.*')
        ->selectRaw("6371 * acos (
            cos ( radians( CAST (hospital_locations.latitude AS double precision) ) )
            * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
            * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (hospital_locations.longitude AS double precision) ) )
            + sin ( radians( CAST (hospital_locations.latitude AS double precision) ) )
            * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
        ) as distance")->selectRaw("'' as instant_appoitment_number");
         $query = $query->orderBy('distance','asc');
        $doctors = $query->paginate(10);
        //dd($doctors->toArray());
        // foreach ($doctors as $key => $value) {
        //     print_r($value->user->name);
        //     print_r($value->specialities->pluck('name_en')->implode(', '));
        // }
        // exit;
        $page_heading = "Doctor List";
        return view('web.doctor', compact('page_heading', 
            'specialties', 
            'insurencePolicies', 
            'subInsurencePolicies', 
            'medicalConditions', 
            'languages', 
            'countries', 
            'genders', 
            'emirates', 
            'areas', 
            'hospitals', 
            'doctors',
            'requestParams'
        ));
    }
    
    public function doctor_list(Request $request) {
        // dd($request->all());
        $requestParams = $request->all();

        $current_lattiude = $request->current_latitude ?? null;
        $current_longitude = $request->current_longitude ?? null;
        
        if ($request->has('current_latitude') && $request->has('current_longitude')) {
            $request->session()->put('current_latitude', $request->current_latitude);
            $request->session()->put('current_longitude', $request->current_longitude);
        }

        $specialties = Specialty::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
        $insurencePolicies = InsurencePolicy::where('status', 1)->orderBy('title')->get()->pluck('title', 'id');
        $subInsurencePolicies = [];
        if ($request->insurance_id ?? null) {
            $subInsurencePolicies = SubInsurencePolicy::where('status', 1)->where('insurence_id', $request->insurance_id)->orderBy('title')->get()->pluck('title', 'id');
        }

        $medicalConditions = SpecialIntrests::where('status', 1)->orderBy('title')->get()->pluck('title', 'id');
        $languages = Languages::orderBy('title')->get()->pluck('title', 'id');
        $countries = CountryOfOrigin::orderBy('name')->get()->pluck('name', 'id');
        $genders = [1 => 'Male', 2 => 'Female', 3 => 'Others'];
        $emirates = Emirate::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
        $areas = Area::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
        // Hospital quesr
        $hospitalQ = Hospital::whereHas('user', function ($q) {
            $q->where('active', 1);
        });
        if ($request->insurance_id ?? null) {
            $hospitalQ->whereHas('insurences', function ($q) use ($request) {
                $q->where('insurance_id', $request->insurance_id);
            });
        }
        
        if ($request->sub_insurance_id ?? null) {
            $hospitalQ->whereHas('insurences', function ($q) use ($request) {
                $q->where('sub_insurance_id', $request->sub_insurance_id);
            });
        }
        
        if ($request->emirates_id ?? null) {
            $hospitalQ->where('emirate_id', $request->emirates_id);
        }
        
        if ($request->area_id ?? null) {
            $hospitalQ->where('area_id', $request->area_id);
        }

        $hospitals = $hospitalQ->orderBy('name_en')->get()->pluck('name_en', 'id');
        $doctor_name = $request->doctor_name ?? null;
        $speciality_id = $request->specialty_id;
        $gender=$request->gender_id ?? null;
        $doctor_language=$request->language_id ?? null;
        $medical_condition = $request->medical_condition_id ?? null;
        $country_id = $request->cuntry_of_origin_id ?? null;
        $hospital_id =$request->hospital_id ?? null;
        $emirate_id=$request->emirates_id ?? null;
        $area_id = $request->area_id ?? null;
        $main_insurence_id = $request->insurance_id ?? null;
        $sub_insurance_id=$request->sub_insurance_id ?? null;
        $filter_distance = $request->distance;
        $direct_call_enabled = $request->dirent_call_for_appointment ?? null;
        $instend_need = $request->ready_to_consult_instantly ?? null;
        $need_date = $request->date;

        if($need_date){
            $need_date = date('Y-m-d',strtotime($need_date));
        }else{
            $need_date = date('Y-m-d');
        }
        
        $list = Doctor::with(['country','user','hospital',
            'hospital.location'=>function($q) use($current_lattiude,$current_longitude){
                $q->select('*');
                $distance =
                    "6371 * acos (
                    cos ( radians( CAST (latitude AS double precision) ) )
                    * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
                    * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                    + sin ( radians( CAST (latitude AS double precision) ) )
                    * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
                )";
                $q->selectRaw("$distance as distance");
            }
            ,'doctorIntrests.specialInterest','doctorSpecialities.speciality','doctorQualifications.qualification'])
            ->leftJoin('hospital_locations', 'doctors.hospital_id', '=', 'hospital_locations.hospital_id');
            

            if($filter_distance){
                $list=$list->whereHas('hospital.location',function($q) use($filter_distance,$current_lattiude,$current_longitude){
                    $distance =
                        "6371 * acos (
                        cos ( radians( CAST (latitude AS double precision) ) )
                        * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
                        * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                        + sin ( radians( CAST (latitude AS double precision) ) )
                        * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
                    )";
                    $q->whereRaw("($distance) <= ".$filter_distance);
                });
            }
            if($direct_call_enabled){
                $list=$list->where('appointment_phone','!=','');
            }
            if($doctor_name != ''){
                $list=$list->whereHas('user',function($query) use($doctor_name){
                    $query->whereRaw("name ilike '%".strtolower($doctor_name)."%'");
                });
            }
            if($speciality_id !=''){
                $list=$list->whereHas('doctorSpecialities',function($query) use($speciality_id){
                    $query->where(['speciality_id'=>$speciality_id]);
                });
            }
            if($doctor_language !=''){
                $list=$list->whereHas('doctorLanguageSpoken',function($query) use($doctor_language){
                    $doctor_language = explode(",",$doctor_language);
                    $query->whereIn('language_spoken_id',$doctor_language);
                });
            }
            if($medical_condition !=''){
                $list=$list->whereHas('doctorIntrests',function($query) use($medical_condition){
                    $query->where(['special_intrest_id'=>$medical_condition]);
                });
            }
            if($gender){
                $list=$list->whereHas('user',function($q)use($gender){
                    $q->where(['gender'=>$gender]);
                });
            }
            if($country_id){
                $list=$list->where(['country_id'=>$country_id]);
            }
            if($hospital_id){
                $list=$list->where(['doctors.hospital_id'=>$hospital_id]);
            }
            if($emirate_id){
                $list=$list->whereHas('hospital',function($q) use($emirate_id){
                    $emirate_ids = explode(",",$emirate_id);
                    $q->whereIn('emirate_id',$emirate_ids);
                });
            }
            if($area_id){
                $list=$list->whereHas('hospital',function($q) use($area_id){
                    $areas = explode(",",$area_id);
                    $q->whereIn('area_id',$areas); 
                });
            }
            if($main_insurence_id){
                $list=$list->whereHas('hospital.insurences',function($q) use($main_insurence_id){
                    $q->where(['insurance_id'=>$main_insurence_id]);
                });
            }
            if($sub_insurance_id){
                $list=$list->whereHas('hospital.insurences',function($q) use($sub_insurance_id){
                    $q->where(['sub_insurance_id'=>$sub_insurance_id]);
                });
            }
             $settings = SettingsModel::first();
            if($instend_need){
                $list=$list->whereHas('doctorInstantAppointment',function($q) use($need_date){
                    $q->whereDate('instant_appointment_date','=',$need_date);
                })->addSelect(['*',DB::raw("'".$settings->instant_appoitment_number."' as instant_appoitment_number")]);
            }else{
                $dayName = strtolower(date('l', strtotime($need_date)));
                $list = $list->whereIn('doctors.id',DoctorAvailability::where($dayName.'_availability', 1)->select('doctor_id'))->addSelect(['*',DB::raw("'' as instant_appoitment_number")]);
            }

            $list=$list->whereNotIn('doctors.id',DoctorHolidays::whereDate('holiday_date','=',$need_date)->select('doctor_id'));
            
            if($instend_need){
            $list = $list->select('doctors.*')
            ->selectRaw("6371 * acos (
                cos ( radians( CAST (hospital_locations.latitude AS double precision) ) )
                * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
                * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (hospital_locations.longitude AS double precision) ) )
                + sin ( radians( CAST (hospital_locations.latitude AS double precision) ) )
                * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
            ) as distance")->selectRaw("'".$settings->instant_appoitment_number."' as instant_appoitment_number");
            }else{
                $list = $list->select('doctors.*')
                    ->selectRaw("6371 * acos (
                        cos ( radians( CAST (hospital_locations.latitude AS double precision) ) )
                        * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
                        * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (hospital_locations.longitude AS double precision) ) )
                        + sin ( radians( CAST (hospital_locations.latitude AS double precision) ) )
                        * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
                    ) as distance")->selectRaw("'' as instant_appoitment_number");
            }
        $doctors = $list->orderBy('distance','asc')->paginate(10);

        $page_heading = "Doctor List";
        return view('web.doctor', compact('page_heading', 
            'specialties', 
            'insurencePolicies', 
            'subInsurencePolicies', 
            'medicalConditions', 
            'languages', 
            'countries', 
            'genders', 
            'emirates', 
            'areas', 
            'hospitals', 
            'doctors',
            'requestParams'
        ));
    }

    
    public function doctor_profile($id) {
        $currentLatitude = session()->get('current_latitude');
        $currentLongitude = session()->get('current_longitude');

        $query = Doctor::with(['hospital.location' => function($q) use($currentLatitude, $currentLongitude){
                $q->select('*');
                if($currentLatitude ?? null && $currentLongitude ?? null){
                    $distance =
                        "6371 * acos (
                        cos ( radians( CAST (latitude AS double precision) ) )
                        * cos( radians( CAST ({$currentLatitude} AS double precision) ) )
                        * cos( radians( CAST ({$currentLongitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                        + sin ( radians( CAST (latitude AS double precision) ) )
                        * sin( radians ( CAST ({$currentLatitude} AS double precision) ) )
                    )";
                    $q->selectRaw("$distance as distance");
                }
            }])->whereHas('user', function ($q) {
            $q->where('active', 1)->where('deleted', 0);
        });
        
        $query->where('id', $id);
        
        $doctor = $query->first();
        $page_heading = "Doctor Profile";
        return view('web.doctor_profile', compact('page_heading', 
            'doctor',
        ));
    }

    public function load_Doctor(Request $request) {
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;
    
        $users = Hospital::query()
            ->where('type', TYPE_HOSPITAL)
            ->leftJoin('users', 'users.id', '=', 'hospitals.user_id')
            ->leftJoin('country', 'country.id', '=', 'hospitals.country_id')
            ->leftJoin('emirates', 'emirates.id', '=', 'hospitals.emirate_id')
            ->select('hospitals.*', 'users.email as email', 'users.dial_code', 'users.phone', 'country.name as country_name', 'emirates.name_en as emirate_name')
            ->orderBy('hospitals.id', 'desc');
    
        return DataTables::eloquent($users)
            ->addColumn('action', function($user) {
                $action = '<div class="dropdown mt-4 mt-sm-0">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-dots-horizontal-rounded"></i>
                    </button>
                    <div class="dropdown-menu">';
    
                if (get_user_permission('hospitals', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.hospitals.edit', ['id' => $user->id]) . '">Edit Hospital</a>';
                }
                if (get_user_permission('hospitals', 'd')) {
                    $action .= '<a class="dropdown-item" data-role="unlink"
                            data-message="Do you want to remove the hospital? This may be linked with other sections"
                            href="' . route('admin.hospitals.delete', ['id' => encrypt($user->id)]) . '">
                            <i class="flaticon-delete-1"></i> Delete Hospital
                          </a>';
                }
                if (get_user_permission('departments', 'r')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.hospitals.departments', ['id' => $user->id]) . '">Departments </a>';
                }
                if (get_user_permission('hospitals', 'r')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.doctors.index', ['hospital_id' => $user->id]) . '">Doctors </a>';
                }
                if (get_user_permission('hospitals', 'r')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.appointments.index', ['hospital_id' => $user->id]) . '">Total Appointments </a>';
                }
                if (get_user_permission('insurence_policy', 'r')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.hospitals.insurances', ['id' => $user->id]) . '">Insurances</a>';
                }
    
                $action .= '</div>
                </div>';
    
                return $action;
            })
            ->addColumn('sl_no', function($user) use (&$startIndex) {
                return ++$startIndex;
            })
            ->addColumn('name_en', function($item) {
                return '<span class="d-flex">' . $item->name_en . ' ' . ($item->user->email_verified_at ? '<img class="verified-account" src="' . asset('admin-assets/assets/images/verified-icon.png') . '" alt="verification Icon">' : '') . '</span>';
            })
            ->addColumn('phone_number', function($item) {
                return '+' . $item->dial_code . $item->phone;
            })
            ->addColumn('status', function($item) {
                return '<div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                        <input type="checkbox" class="form-check-input change_status" data-id="' . $item->user_id . '"
                                    data-url="' . url('admin/hospitals/change_status') . '"
                                    ' . ($item->user->active == 1 ? 'checked' : '') . '>
                    </div>';
            })
            ->rawColumns(['status', 'action', 'name_en'])
            ->toJson();
    }
    
    public function loadMembers(REQUEST $request){
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;

        $users = Members::query()
        ->where('user_id','=',$request->patient_id)
        ->orderBy('id', 'desc');
        // dd($users->toArray());
        return DataTables::eloquent($users)
        ->addColumn('action', function($user) {
            $memberData = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
             return '<div class="d-flex gap-2">
                        <a href="#!" data-member_data="' . $memberData . '" class="btn btn-icon btn-dark edit-member"><i class="mdi mdi-file-document-edit-outline"></i></a>
                        <a href="#!" class="btn btn-icon btn-primary delete-member" data-id="'.encrypt($user->id).'"><i class="mdi mdi-trash-can-outline"></i></a>
                    </div>';
        })
        ->addColumn('sl_no', function($user) use (&$startIndex) {
            return ++$startIndex;
        })
        ->addColumn('gender', function($item) {
            return $item->gender == 1 ? 'Male' : ($item->gender == 2 ? 'Female' : ($item->gender == 3 ? 'Other' : ''));
        })
        ->addColumn('insurence_policy', function($item) {
            return $item->insurence_policy->title ?? 'N/A';
        })
        ->addColumn('sub_insurence_policy', function($item) {
            return $item->sub_insurence_policy->title ?? 'N/A';
        })
        ->addColumn('image', function($user) {
            return '<img src="'.$user->user_img_url.'" class="img-rounded" width="50" height="50">';
          
        })
        ->rawColumns(['action'])
        ->toJson();
    }
    
    public function about_us(){

        $page_heading = "About Us";
        $content = Article::where('id', 1)->first()->desc_en;

        return view('web.page-styl1',compact('page_heading', 'content'));
    }
    
    public function privacy(){

       $page_heading = "Privacy & Internet Cookies Policy";
        $content = Article::where('id', 3)->first()->desc_en;

        return view('web.page-styl1',compact('page_heading', 'content'));
    }

    public function benefits_for_doctors_and_patients() {
            
            $page_heading = "Benefits For Doctors & Patients";
            $content = Article::where('id', 15)->first()->desc_en;
    
            return view('web.page-styl1',compact('page_heading', 'content'));
    }

    public function faq_for_patient() {

        $page_heading = "Faq For Patient";
        $faqs = FaqModel::get_faq_list()->get();

        return view('web.page-faq',compact('page_heading', 'faqs'));
    }

    public function faq_for_doctor() {

        $page_heading = "Faq For Doctors";
        $faqs = FaqForDoctorModel::get_faq_list()->get();

        return view('web.page-faq',compact('page_heading', 'faqs'));
    }

    public function faq_for_hospital() {

        $page_heading = "Faq For Clinic/Hospital";
        $faqs = FaqForHospitaModel::get_faq_list()->get();

        return view('web.page-faq',compact('page_heading', 'faqs'));
    }


    public function terms_condition(){

        $page_heading = "Terms & Conditions";
        $content = Article::where('id', 2)->first()->desc_en;

        return view('web.page-styl1',compact('page_heading', 'content'));
    }
    
    public function contact_us(){
        $page_heading = "Cuntact Us";

        // Get the contact us settings
        $contactUsSettings = ContactUsSetting::first()->toArray();

        return view('web.contact',compact('page_heading', 'contactUsSettings'));
    }

    public function patient_instructions(){

        $page_heading = "User Instructions for Patients";
        $instructions_type1 = UserInstruction::where('type',1)->get();
        $instructions_type2 = UserInstruction::where('type',2)->get();
        $instructions_type3 = UserInstruction::where('type',3)->get();
        $instructions_type4 = UserInstruction::where('type',4)->get();

        return view('web.instructions.patient',compact('instructions_type1','instructions_type2','instructions_type3','instructions_type4','page_heading'));
    }
    
    public function doctor_instructions(){

        $page_heading = "User Instructions Doctor Panel";

        $instructions_type1 = DoctorInstruction::where('type',1)->get();
        $instructions_type2 = DoctorInstruction::where('type',2)->get();
        $instructions_type3 = DoctorInstruction::where('type',3)->get();
        $instructions_type4 = DoctorInstruction::where('type',4)->get();

        return view('web.instructions.doctor',compact('instructions_type1','instructions_type2','instructions_type3','instructions_type4','page_heading'));
    }
    
    public function hospital_instructions(){

        $page_heading = "User Instructions Hospital Panel";

        $instructions_type1 = HospitalInstruction::where('type',1)->get();
        $instructions_type2 = HospitalInstruction::where('type',2)->get();
        $instructions_type3 = HospitalInstruction::where('type',3)->get();
        $instructions_type4 = HospitalInstruction::where('type',4)->get();
        $instructions_type5 = HospitalInstruction::where('type',5)->get();
        $instructions_type6 = HospitalInstruction::where('type',6)->get();

        return view('web.instructions.hospital',compact('instructions_type6','instructions_type5','instructions_type1','instructions_type2','instructions_type3','instructions_type4','page_heading'));
    }
    
    public function clinic_instructions(){

        $page_heading = "User Instructions Clinic Panel";

        $instructions_type1 = ClinicInstruction::where('type',1)->get();
        $instructions_type2 = ClinicInstruction::where('type',2)->get();
        $instructions_type3 = ClinicInstruction::where('type',3)->get();
        $instructions_type4 = ClinicInstruction::where('type',4)->get();
        $instructions_type5 = ClinicInstruction::where('type',5)->get();

        return view('web.instructions.clinic',compact('instructions_type5','instructions_type1','instructions_type2','instructions_type3','instructions_type4','page_heading'));
    }

    public function check_doctor_availability(Request $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            //'access_token'=>'required',
            'booking_date' => 'required',
            'doctor_user_id' => 'required'
        ]);
    
        $doctor = Doctor::where('user_id', $request->doctor_user_id)->first();
        // dd($doctor);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $booking_date = \DateTime::createFromFormat('d-m-Y', $request->booking_date);
            if ($booking_date === false) {
                $status = "0";
                $message = "Invalid booking date format. Please use 'd-m-Y'.";
                return response()->json(['status' => $status, 'message' => $message, 'oData' => (object)$o_data, 'errors' => (object)$errors]);
            }
            $booking_date = $booking_date->format('Y-m-d');
            // dd($booking_date);
            // $user_id = $this->validateAccesToken($request->access_token);
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 10;
            $offset = ($page -  1) * $limit;
    
            $list =  [];
            $doctor_time_slot = array();
            $checkHoliday = DoctorHolidays::where('doctor_id', $doctor->id)
                ->where('holiday_date', $booking_date)
                ->get();
            // dd($checkHoliday);
            $messageResponse = 'Doctor is not available';
            if ($checkHoliday->count() == 0) {
                $dayName = strtolower(date('l', strtotime($booking_date)));
                $list = DoctorAvailability::where('doctor_id', $doctor->id)
                    ->where($dayName . '_availability', 1)
                    ->select($dayName . '_availability', $dayName . '_time_slot')
                    ->orderBy('id', 'desc')->take($limit)->skip($offset)->first();
                if ($list) {
                    $timeSlot = json_decode($list->{$dayName . '_time_slot'});
                    if ($timeSlot) {
                        $takenAppointment = DoctorPatientAppointment::where('doctor_id', $doctor->id)
                            ->where('booking_date', $booking_date)
                            ->where('booking_status', '!=', BOOKING_STATUS_CANCELLED)
                            ->pluck('booking_time_slot')->toArray();
                        $unavailable_timeslot = DoctorTemporaryUnavailable::where('unavailable_date', $booking_date)
                            ->where('doctor_id', $doctor->id)
                            ->pluck('unavailable_timeslot')->toArray();
                        $unavailable_timeslot = array_merge(...array_map('json_decode', $unavailable_timeslot));
    
                        foreach ($timeSlot as $key => $value) {
                            $doctor_time_slot[] = [
                                "slot_text" => $timeSlot[$key],
                                "is_available" => (!in_array($timeSlot[$key], $takenAppointment) && !in_array($timeSlot[$key], $unavailable_timeslot))
                            ];
                        }
                    }
                }
            } else {
                $messageResponse = "Doctor is on holiday";
            }
    
            if (!empty($doctor_time_slot)) {
                $status = "1";
                $message = "Data fetched successfully";
                $o_data['list'] = convert_all_elements_to_string($doctor_time_slot);
            } else {
                $message = $messageResponse;
            }
        }
    
        return response()->json(['status' => $status, 'message' => $message, 'oData' => (object)$o_data, 'errors' => (object)$errors]);
    }
    
    
    public function check_doctor_unavailability(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            //'access_token'=>'required',
            'unavailable_date'=>'required',
            'doctor_id'=> 'required|exists:doctors,id'

        ]);
        $doctor = Doctor::where('user_id', $request->doctor_user_id)->first();
        $unavailable_date = \DateTime::createFromFormat('d-m-Y', $request->unavailable_date)->format('Y-m-d');
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
            
        }else{

            $messageResponse = 'No record found';    
            $list =  [];
            $data = DoctorTemporaryUnavailable::where('doctor_id', $request->doctor_id)->where('unavailable_date', $unavailable_date)->first();
            $unavailable_timeslot = json_decode($data->unavailable_timeslot ?? null);
            
            if(is_array($unavailable_timeslot) && count($unavailable_timeslot)){
                $list = convert_all_elements_to_string($unavailable_timeslot);
            }

            $status = "1";
            $message = "data fetcehed successfully";
            $o_data['list'] = $list;
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function getPatientMembers() {
        if(!Auth::check() || Auth::User()->role != USER_ROLE){
            return response()->json([]);
        }
        $patients = User::with('Members')->where('id', Auth::User()->id)->where('role', USER_ROLE)->where('active', 1)->get();
        $data = [];
        
        foreach ($patients as $patient) {
            // Add the patient to the data array with a 'type' key
            $patientArray = $patient->toArray();
            $patientArray['type'] = 'patient';
            $patientArray['fullname'] = $patientArray['first_name'] . ' ' . $patientArray['last_name'];
            $data[] = $patientArray;
    
            // If the patient has members, loop through them and add to the data array with a 'type' key
            if ($patient->Members) {
                foreach ($patient->Members as $member) {
                    $memberArray = $member->toArray();
                    $memberArray['type'] = 'member';
                    $memberArray['fullname'] = $memberArray['full_name'];
                    $data[] = $memberArray;
                }
            }
        }
        return response()->json($data);
    }

    public function addAppointmentHistory($appointmentId, $status, $changedBy)
    {
        return DoctorAppointmentsStatus::create([
            'appointment_id' => $appointmentId,
            'status' => $status,
            'changed_by' => $changedBy,
            'changed_at' => now(),
        ]);
    }
    public function notifications(){
        $page_heading = "Notifications";
        $module_heading = "Notifications";
        return view('web.notifications', compact('page_heading','module_heading'));
    }
    public function get_hospital_profile($id=0){
        
            $page_heading = "Hoispital Profile";
            $hospital_id = $id;
            
            $data = Hospital::with(['user','departments','images','emirate','area','country','hospital_specialities'])->where(['id'=>$hospital_id])->get();
            if($data->count() > 0){
                $status = "1";
                $message = "data fetched successfully";
                $data=$data->first();
                if($data->type == 20){
                    $page_heading = "Clinic Profile";
                }
                //printr($data->toArray()); exit;
            }else{
                abort(404);
            }

        
        return view('web.hospital_profile', compact('page_heading','data'));
    }
}
