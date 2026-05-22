<?php

namespace App\Http\Controllers\front;
use App\Exports\DoctorsExport;
use App\Models\DoctorAppointmentsStatus;
use App\Models\Hospital;
use App\Models\Members;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\DoctorSpecialities;
use App\Models\BookingType;
use App\Models\DoctorTemporaryUnavailable;
use App\Models\DoctorPatientAppointment;
use App\Models\PointHistory;
use App\Models\DoctorRescheduleAppointment;
use App\Models\DoctorHolidays;
use App\Models\DoctorInstantAppointment;
use App\Models\DoctorIntrests;
use App\Models\DoctorAvailability;
use App\Models\DoctorLanguageSpoken;
use App\Models\DoctorQualifications;
use App\Models\SpecialIntrests;
use App\Models\Languages;
use App\Models\LicenceType;
use App\Models\Qualifications;
use App\Models\InsurencePolicy;
use App\Models\SubInsurencePolicy;
use App\Models\SettingsModel;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator,DB;
use App\Models\Area;
use App\Models\CountryModel;
use App\Models\Emirate;
use App\Models\User;
use App\Models\Referral;
use App\Models\HospitalImage;
use App\Models\CountryOfOrigin;
use Illuminate\Support\Facades\Hash;
use App\Models\DepartmentModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DoctorExport;
use App\Imports\DoctorImport;
use App\Models\AppointmentDoc;
use App\Models\HospitalDoctorFeedback;
use Illuminate\Http\Request as MiniRequest;
use DataTables;
use App\Mail\ActivateAccountEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Libraries\Agora\RtcTokenBuilder;

class DoctorController extends Controller
{

public function saveFeedback(Request $request)
{
    $status = "0";
    $message = "";
    $errors = [];

    $validator = Validator::make($request->all(), [
        'appointment_id' => 'required',
        'review_doctor_id' => 'required|exists:doctors,id',
        'rating' => 'required|integer|min:1|max:5'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => "0",
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors()
        ]);
    }

    $user = auth()->user();

    // ❗ Prevent duplicate review
    $exists = HospitalDoctorFeedback::where('user_id', $user->id)
        ->where('appointment_id', $request->appointment_id)
        ->exists();

    if ($exists) {
        return response()->json([
            'status' => "0",
            'message' => "You already submitted feedback"
        ]);
    }

    HospitalDoctorFeedback::create([
        'user_id' => $user->id,
        'doctor_id' => $request->review_doctor_id,
        'hospital_id' => 0,
        'appointment_id' => $request->appointment_id,
        'feeback_message' => $request->feeback_message,
        'rating' => $request->rating,
    ]);

    return response()->json([
        'status' => "1",
        'message' => "Feedback submitted successfully"
    ]);
}

    public function index_old(Request $request)
{
    $page_heading = "Doctors";
    $hospital = null;
    $clinic = null;

    if ($request->hospital_id) {
        $hospital = Hospital::find($request->hospital_id);
        $page_heading .= '- ' . $hospital->name_en . ' hospital';
    }

    if ($request->clinic_id) {
        $clinic = Hospital::find($request->clinic_id);
        $page_heading .= '- ' . $clinic->name_en . ' clinic';
    }

    $query = Doctor::query();

    $query->select(
        'doctors.*',
        'users.name as user_name',
        'users.email',
        'users.first_name',
        'users.last_name',
        'users.dial_code',
        'users.phone',
        'country.name as country_name',
        'hospitals.name_en as hospital_name'
    )
    ->leftJoin('users', 'users.id', '=', 'doctors.user_id')
    ->leftJoin('country', 'country.id', '=', 'doctors.country_id')
    ->leftJoin('hospitals', 'hospitals.id', '=', 'doctors.hospital_id')
    ->leftJoin('department_doctors', 'department_doctors.doctor_id', '=', 'doctors.id')
    ->leftJoin('doctor_specialities', 'doctor_specialities.doctor_id', '=', 'doctors.id')
    ->leftJoin('doctor_intrests', 'doctor_intrests.doctor_id', '=', 'doctors.id')
    ->groupBy('doctors.id', 'users.id', 'country.id', 'hospitals.id');

    /*
    |--------------------------------------------------------------------------
    | Simple Filters (hospital / clinic)
    |--------------------------------------------------------------------------
    */

    if ($request->hospital_id) {
        $query->where('doctors.hospital_id', $request->hospital_id);
    }

    if ($request->clinic_id) {
        $query->where('doctors.hospital_id', $request->clinic_id);
    }

    /*
    |--------------------------------------------------------------------------
    | Advanced Filters
    |--------------------------------------------------------------------------
    */

    if ($request->booking_from) {
        $fromDate = Carbon::createFromFormat('d-m-Y', $request->booking_from)
            ->startOfDay()
            ->format('Y-m-d');

        $query->whereDate('doctors.created_at', '>=', $fromDate);
    }

    if ($request->booking_to) {
        $toDate = Carbon::createFromFormat('d-m-Y', $request->booking_to);

        if (!$toDate->isToday()) {
            $query->whereDate('doctors.created_at', '<=', $toDate->format('Y-m-d'));
        }
    }

    if ($request->department_id) {
        $query->where('department_doctors.department_id', $request->department_id);
    }

    if ($request->speciality_id) {
        $query->where('doctor_specialities.speciality_id', $request->speciality_id);
    }

    if ($request->special_interest_id) {
        $query->where('doctor_intrests.special_intrest_id', $request->special_interest_id);
    }

    if ($request->country_id) {
        $query->where('doctors.country_id', $request->country_id);
    }

    if ($request->clinic_status !== null && $request->clinic_status !== "") {
        $query->where('users.active', $request->clinic_status);
    }

    /*
    |--------------------------------------------------------------------------
    | Final Order + Pagination
    |--------------------------------------------------------------------------
    */

    $doctors = $query
        ->orderBy('doctors.id', 'desc')
        ->paginate(9) // 9 per page for grid layout
        ->withQueryString(); // keep filters in pagination

    /*
    |--------------------------------------------------------------------------
    | Dropdown Data
    |--------------------------------------------------------------------------
    */

    $hospitals = Hospital::join('users', 'users.id', '=', 'hospitals.user_id')
        ->where('users.active', 1)
        ->orderBy('hospitals.name_en', 'asc')
        ->select('hospitals.*')
        ->get();

    $departments = DepartmentModel::where(['status'=>1])->orderBy('title','asc')->get();
    $specialities = Specialty::where(['active'=>1])->orderBy('name_en','asc')->get();
    $special_interestes = SpecialIntrests::where(['status'=>1])->orderBy('title','asc')->get();
    $countries = CountryOfOrigin::where(['status'=>1])->orderBy('name','asc')->get();

    return view('front.doctor.index', compact(
        'page_heading',
        'hospital',
        'clinic',
        'hospitals',
        'departments',
        'specialities',
        'special_interestes',
        'countries',
        'doctors'
    ));
}

public function index(Request $request) {
    // dd($request->all());
    $requestParams = $request->all();

$current_lattiude = session()->get('current_latitude');
$current_longitude = session()->get('current_longitude');


    
    if ($request->has('current_latitude') && $request->has('current_longitude')) {
        // $request->session()->put('current_latitude', $request->current_latitude);
        // $request->session()->put('current_longitude', $request->current_longitude);
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

    $list = Doctor::get();
    
    
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
        ->whereHas('user', function ($q) {
            $q->where('active', 1);
        })
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
        $sort_by = $request->sort_by ?? 'nearest';

        $list = $list->withCount([
            'appointments as popularity_count'
        ]);
        
        if ($sort_by === 'popular') {
            $doctors = $list->orderByDesc('popularity_count')->paginate(10);
        } else {
            $doctors = $list->orderBy('distance', 'asc')->paginate(10);
        }

    $page_heading = "Doctor List";
    return view('front.doctor.index', compact('page_heading', 
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
    //  $currentLatitude = 25.2048;;
    //  $currentLongitude = 55.2708;
     
      $currentLatitude = session()->get('current_latitude');
      $currentLongitude = session()->get('current_longitude');
      

    $query = Doctor::with(['feedbacks','hospital.location' => function($q) use($currentLatitude, $currentLongitude){
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
    return view('front.doctor.detail', compact('page_heading', 
        'doctor',
    ));
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
    $bookingTypes = BookingType::where('status', 1)->get();
    $settings = SettingsModel::first();
    
    return view('front.doctor.book-appointment',compact('page_heading','doctor', 'time_slot', 'patient', 'insurencePolicies','bookingTypes','settings'));
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
                $isToday = ($booking_date == date('Y-m-d'));
                date_default_timezone_set('Asia/Dubai');
                $currentTime = date('H:i');
                if ($timeSlot) {
                    $takenAppointment = DoctorPatientAppointment::where('doctor_id', $doctor->id)
                        ->where('booking_date', $booking_date)
                        ->where('booking_status', '!=', BOOKING_STATUS_CANCELLED)
                        ->pluck('booking_time_slot')->toArray();
                    $unavailable_timeslot = DoctorTemporaryUnavailable::where('unavailable_date', $booking_date)
                        ->where('doctor_id', $doctor->id)
                        ->pluck('unavailable_timeslot')->toArray();
                    $unavailable_timeslot = array_merge(...array_map('json_decode', $unavailable_timeslot));
                        $is_today_available='no';
                    foreach ($timeSlot as $key => $value) {
                        $slotTime = date('H:i', strtotime($timeSlot[$key]));
                    
                        // If booking date is today and slot time already passed
                        if ($isToday && $slotTime <= $currentTime) {
                            $isAvailable = false;
                        } else {
                            $isAvailable = (
                                !in_array($timeSlot[$key], $takenAppointment) &&
                                !in_array($timeSlot[$key], $unavailable_timeslot)
                            );
                            $is_today_available='yes';
                        }
                    
                        $doctor_time_slot[] = [
                            "slot_text" => $timeSlot[$key],
                            "is_available" => $isAvailable
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
            $o_data['is_today_available'] = $is_today_available;
        } else {
            $message = $messageResponse;
        }
    }

    return response()->json(['status' => $status, 'message' => $message, 'oData' => (object)$o_data, 'errors' => (object)$errors]);
}
    public function instantAppointmentSaveold(REQUEST $request){
        DB::beginTransaction();
        try {

           $instantAppointment = $request->instant_appointment_date;
            foreach($instantAppointment as $instantAppointment){

                $doctor = new DoctorInstantAppointment();
                $doctor->doctor_id   =  $request->doctor_id;
                $doctor->instant_appointment_date    =(string)$instantAppointment;
                $doctor->created_at = gmdate('Y-m-d H:i:s');
                $doctor->updated_at = gmdate('Y-m-d H:i:s');
                $doctor->save();
            }
            DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor InstantAppointment Save Successfully";
            return view('admin.doctors.index',compact('page_heading'));

        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }


    }

    public function instantAppointment($id){
        $page_heading="Instant Appointment";
        $module_heading="Doctors";
        $doctor_id = $id;
        $doctor = Doctor::find($doctor_id);
        // dd($doctor->doctorInstantAppointment);
        return view('admin.doctors.instantAppointment',compact('page_heading','module_heading',
        'doctor_id', 'doctor'));
    }

    public function instantAppointmentSave(Request $request) {
        // Define validation rules
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'instant_appointment_date' => 'required|array',
            'instant_appointment_date.*' => 'required|date_format:d-m-Y'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => "0",
                'errors' => $validator->messages(),
                'message' => "Validation error occurred"
            ]);
        }

        $instantAppointmentDates = $request->instant_appointment_date;

        foreach($instantAppointmentDates as &$date) {
            $date = \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
        }

        $existDates = DoctorInstantAppointment::where('doctor_id', $request->doctor_id)->whereIn('instant_appointment_date', $instantAppointmentDates)->get();

        if($existDates && count($existDates)){
            $existDates = array_column($existDates->toArray(), 'instant_appointment_date');
            return response()->json([
                'status' => "3",
                'errors' => 'Duplicate Date',
                'message' => "Sorry, selected date is already added before",
                "dates" => $existDates
            ]);
        }

        DB::beginTransaction();
        try {
            foreach($instantAppointmentDates as $select_date) {
                $doctor = new DoctorInstantAppointment();
                $doctor->doctor_id = $request->doctor_id;
                $doctor->instant_appointment_date = $select_date;
                $doctor->created_at = now();
                $doctor->updated_at = now();
                $doctor->save();
            }

            DB::commit();

            return response()->json([
                'status' => "1",
                'errors' => [],
                'message' => "Doctor InstantAppointment saved successfully!",
                'oData' => [
                    'redirect' => route('admin.doctors.index')
                ]
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => "0",
                'errors' => [],
                'message' => "Failed to save doctor instant appointment. Error: " . $e->getMessage()
            ]);
        }
    }

    public function instantAppointmentDelete($id) {
        $status = "0";
        $message = "";
        $o_data = [];

        $id = decrypt($id);
        $row = DoctorInstantAppointment::find($id);
        if ($row) {
            $row->delete();
            $status = "1";
            $message = "Doctor instant appointment removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }

    public function holiday($id){
        if (!get_user_permission('doctors', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Holiday";
        $doctor_id = $id;
        $doctor = Doctor::find($id);
        $holidays = DoctorHolidays::where('doctor_id', $doctor_id)->get();
        return view('admin.doctors.holiday',compact('page_heading','doctor_id', 'doctor', 'holidays'));
    }
    public function holiday_saveold(REQUEST $request){
        DB::beginTransaction();
        try {
            $holidayNames = $request->holiday_name;
            $dates = $request->date;
            // Combine arrays
            $combinedArray = [];
            for ($i = 0; $i < count($holidayNames); $i++) {
                $combinedArray[] = [
                    "holiday_name" => $holidayNames[$i],
                    "date" => $dates[$i]
                ];
            }
            foreach($combinedArray as $combinedArray){

                $doctor = new DoctorHolidays();
                $doctor->doctor_id   =  $request->doctor_id;
                $doctor->holiday_name    = $combinedArray['holiday_name'];
                $doctor->holiday_date    =   $combinedArray['date'];
                $doctor->created_at = gmdate('Y-m-d H:i:s');
                $doctor->updated_at = gmdate('Y-m-d H:i:s');
                $doctor->save();
            }
            DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor holiday Save Successfully";
            return view('admin.doctors.index',compact('page_heading'));

        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }

    }

    public function holiday_save(Request $request) {
        // Custom validation rule for unique dates
        Validator::extend('unique_dates', function($attribute, $value, $parameters, $validator) {
            return count($value) === count(array_unique($value));
        }, 'The :attribute field has duplicate dates.');

        // Define base validation rules
        $rules = [
            'doctor_id' => 'required|exists:doctors,id',
        ];

        // Conditionally add rules for holiday_date fields if they exist
        if (!empty($request->holiday_date)) {
            $rules['holiday_name'] = 'required|array';
            $rules['holiday_name.*'] = 'required|string';
            $rules['holiday_date'] = 'required|array';
            $rules['holiday_date.*'] = 'required|date_format:d-m-Y';
        }

        // Perform validation
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => "0",
                'errors' => $validator->messages(),
                'message' => "Validation error occurred"
            ]);
        }

        DB::beginTransaction();
        try {
            // Delete existing holidays for the doctor
            DoctorHolidays::where('doctor_id', $request->doctor_id)->delete();

            // Save the new holidays if dates are provided
            if (!empty($request->holiday_date)) {
                $holidayNames = $request->holiday_name;
                $dates = $request->holiday_date;
                $combinedArray = [];

                for ($i = 0; $i < count($holidayNames); $i++) {
                    $combinedArray[] = [
                        "holiday_name" => $holidayNames[$i],
                        "date" => \Carbon\Carbon::createFromFormat('d-m-Y', $dates[$i])->format('Y-m-d')
                    ];
                }

                foreach ($combinedArray as $data) {
                    $doctorHoliday = new DoctorHolidays();
                    $doctorHoliday->doctor_id = $request->doctor_id;
                    $doctorHoliday->holiday_name = $data['holiday_name'];
                    $doctorHoliday->holiday_date = $data['date'];
                    $doctorHoliday->created_at = now();
                    $doctorHoliday->updated_at = now();
                    $doctorHoliday->save();
                }
            }

            DB::commit();
            return response()->json([
                'status' => "1",
                'errors' => [],
                'message' => "Doctor holiday saved successfully!",
                'oData' => [
                    'redirect' => route('admin.doctors.index')
                ]
            ]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => "0",
                'errors' => [],
                'message' => "Failed to save doctor holiday. Error: " . $e->getMessage()
            ]);
        }
    }


    public function holiday_delete($id) {
        $status = "0";
        $message = "";
        $o_data['redirect'] = route('admin.doctors.index');

        $id = decrypt($id);
        $row = DoctorHolidays::where('doctor_id', $id)->get();
        if (count($row)) {
            DoctorHolidays::where('doctor_id', $id)->delete();
            $status = "1";
            $message = "Doctor Holidays removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
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
        // dd($unavailable_date);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();

        }else{

            $messageResponse = 'No record found';
            $list =  [];
            $data = DoctorTemporaryUnavailable::where('doctor_id', $request->doctor_id)->where('unavailable_date', $unavailable_date)->first();
            // dd($data);
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

    public function temporaryUnavailableSave(REQUEST $request){
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'unavailable_date' => 'required|date_format:d-m-Y',
            'unavailable_timeslot' => 'array',
            'unavailable_timeslot.*' => 'required|string' // Add any specific validation for the timeslot array elements
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();

            return response()->json([
                'status' => $status,
                'message' => $message,
                'errors' => $errors,
                'oData' => []
            ], 200);
        }

        DB::beginTransaction();
        try {
            // dd($request->all());
            $unavailable_date = \DateTime::createFromFormat('d-m-Y', $request->unavailable_date)->format('Y-m-d');
            $doctor = DoctorTemporaryUnavailable::where('unavailable_date', $unavailable_date)->where('doctor_id', $request->doctor_id)->first();
            if($request->id ?? null){
                $doctor = DoctorTemporaryUnavailable::find($request->id);
            }

            if((!$request->unavailable_timeslot || !count($request->unavailable_timeslot)) && $doctor){
                $doctor->delete();
                $status = "1";
                $page_heading="Doctors";
                $message = "Doctor temporary Unavailable Removed Successfully";
                DB::commit();
                return response()->json([
                    'status' => "1",
                    'errors' => [],
                    'message' => $message,
                    'oData' => [
                        'redirect' => route('admin.doctors.index')
                    ]
                ]);
            }

            if(!$doctor){
                $doctor = new DoctorTemporaryUnavailable();
            }
            // dd($doctor->toArray());
            $doctor->doctor_id   =  $request->doctor_id;
            $doctor->unavailable_date    = $unavailable_date;
            $doctor->unavailable_timeslot    =  json_encode($request->unavailable_timeslot);
            $doctor->created_at = gmdate('Y-m-d H:i:s');
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->save();

            DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor temporary Unavailable Save Successfully";
            return response()->json([
                'status' => "1",
                'errors' => [],
                'message' => $message,
                'oData' => [
                    'redirect' => route('admin.doctors.index')
                ]
            ]);

        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to save temporary Unavailable " . $e->getMessage();
        }

    }
    public function availability_save(REQUEST $request){
        DB::beginTransaction();
        try {
        //   dd($request->all());
            $doctor = DoctorAvailability::find($request->doctor_id);

            if($doctor === null){
                $doctor = new DoctorAvailability();
            }

            $doctor->doctor_id = $request->doctor_id;
            $doctor->sunday_availability = $request->sunday_availability ?? "0";
            $doctor->sunday_time_slot = ($doctor->sunday_availability && isset($request->booking_time_slot['sun'])) ? json_encode($request->booking_time_slot['sun']) : null;
            $doctor->monday_availability = $request->monday_availability ?? "0";
            $doctor->monday_time_slot = ($doctor->monday_availability && isset($request->booking_time_slot['mon'])) ? json_encode($request->booking_time_slot['mon']) : null;
            $doctor->tuesday_availability = $request->tuesday_availability ?? "0";
            $doctor->tuesday_time_slot = ($doctor->tuesday_availability && isset($request->booking_time_slot['tue'])) ? json_encode($request->booking_time_slot['tue']) : null;
            $doctor->wednesday_availability = $request->wednesday_availability ?? "0";
            $doctor->wednesday_time_slot = ($doctor->wednesday_availability && isset($request->booking_time_slot['wed'])) ? json_encode($request->booking_time_slot['wed']) : null;
            $doctor->thursday_availability = $request->thursday_availability ?? "0";
            $doctor->thursday_time_slot = ($doctor->thursday_availability && isset($request->booking_time_slot['thu'])) ? json_encode($request->booking_time_slot['thu']) : null;
            $doctor->friday_availability = $request->friday_availability ?? "0";
            $doctor->friday_time_slot = ($doctor->friday_availability && isset($request->booking_time_slot['fri'])) ? json_encode($request->booking_time_slot['fri']) : null;
            $doctor->saturday_availability = $request->saturday_availability ?? "0";
            $doctor->saturday_time_slot = ($doctor->saturday_availability && isset($request->booking_time_slot['sat'])) ? json_encode($request->booking_time_slot['sat']) : null;
            $doctor->created_at = gmdate('Y-m-d H:i:s');
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            // dd($doctor);
            $doctor->save();

            DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor Availability Save Successfully";
            return redirect()->back()->with('success',  $message);

        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destory($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];

        $id = decrypt($id);
        $row = Doctor::find($id);
        $user = User::where('id', $row->user_id)->first();
        if ($row) {
            $row->delete();
            // User::where('id', $row->user_id)->update(['deleted' => 1]);
            $user->user_device_token = "";
            $user->email = $user->email . "__deleted_account_" . $user->id;
            $user->phone = $user->phone . "__deleted_account_" . $user->id;
            $user->deleted = 1;
            $user->access_token = "";
            $user->update();
            $status = "1";
            $message = "Doctor removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }

    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        $user = User::find($request->id);
        if($user){
            if (User::where('id', $request->id)->update(['active' => $request->status])) {
                $status = "1";
                $msg = "Successfully activated";
                if (!$request->status) {
                    $msg = "Successfully deactivated";
                }
                $message = $msg;
            } else {
                $message = "Something went wrong";
            }
        }else{
            $message = "Record Not Exist!";
        }

        echo json_encode(['status' => $status, 'message' => $message]);

        if($request->status && $user){
            Mail::to($user->email)->send(new ActivateAccountEmail($user, 'doctorlogin'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function temporaryUnavailableDelete($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];

        $id = decrypt($id);
        $row = DoctorTemporaryUnavailable::find($id);
        if ($row) {
            $row->delete();
            $status = "1";
            $message = "Record removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }



public function viewAppointment($id){
    if (!get_user_permission('doctors', 'r')) {
        return redirect()->route('admin.restricted_page');
    }
    $page_heading="Doctors";
    $users = DoctorPatientAppointment::query()
    ->where('doctor_patient_appointments.id','=',$id)
    ->leftJoin('users', 'users.id', '=', 'doctor_patient_appointments.user_id')
    ->leftJoin('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
    ->leftJoin('hospitals', 'hospitals.id', '=', 'doctors.hospital_id')
    ->select('users.*','doctor_patient_appointments.*','hospitals.name_en','hospitals.address')
    ->orderBy('doctor_patient_appointments.id','desc')->get()->toArray();

$doctor_id =$users[0]['doctor_id'];
$booking_time_slot = (array)$users[0]['booking_time_slot'];

$time_slot = [
    "08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30",
    "13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
    "18:00","18:30","19:00","19:30","20:00"
];
    return view('admin.doctors.patientAppointment.viewAppointment',compact('page_heading','booking_time_slot','users','time_slot','doctor_id'));
}

public function getDepartmentDoctors ($department_id, $hospital_id = null) {
    $query = Doctor::join('users', 'doctors.user_id', '=', 'users.id')
        ->with('user')
        ->join('department_doctors', 'doctors.id', '=', 'department_doctors.doctor_id')
        ->where('department_doctors.department_id', $department_id)
        ->where('users.active', 1);

if (isset($hospital_id) && !is_null($hospital_id) && $hospital_id!='undefined') {
    $query->where('doctors.hospital_id', $hospital_id);  // Use the correct table name for the hospital_id column
}

$data = $query->orderBy('users.name', 'asc')
        ->select('doctors.*', 'users.name as user_name') // Include the user name in the selection
        ->get()
        ->toArray();

    return response()->json($data);
}

public function getHospitalDoctors($hospital_id) {
    $data = Doctor::join('users', 'doctors.user_id', '=', 'users.id')->with('user')
    ->where('doctors.hospital_id', $hospital_id)
    ->where('users.active', 1)
    ->orderBy('users.name', 'asc')
    ->select('doctors.*', 'users.name as user_name')
    ->get();

    return response()->json($data);
}
public function import_export(){
    $page_heading = "Bulk Upload";
    $hospital_list = Hospital::join('users', 'users.id', '=', 'hospitals.user_id')
            ->where(['users.deleted' => 0])
            ->select(['hospitals.id', 'hospitals.name_en'])
            ->orderBy('name_en', 'asc')
            ->get();
    return view('admin.bulkupload.index',compact('page_heading','hospital_list'));
}
public function export_excel(){
    $hospital_id = $_GET['hospital_id']??0;
    $hospital = Hospital::find($hospital_id);
    $file_name = str_replace(" ","_",$hospital->name_en);
    $file_name = strtolower($file_name).'.xlsx';
    return Excel::download(new DoctorExport(1,$hospital_id), $file_name);
}

public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $import = new DoctorImport();
            Excel::import($import, $request->file('file'));

            $validRecords = $import->getValidRecords();
            //printr($validRecords); exit;
            $err_msg='';
            $any_succes=0;
            foreach($validRecords as $record){
                $check_email = User::whereRaw('Lower(email) = ?', [strtolower($record['email'])])->get();
                if($check_email->count() > 0){
                    $user_data = $check_email->first();
                    if($user_data->role == DOCTOR_ROLE){
                        DB::beginTransaction();
                        try {
                            $gender = 3;
                            if($record['gender'] == 'Female'){
                                $gender = 2;
                            }else if($record['gender'] == 'male'){
                                $gender = 1;
                            }
                            $user = User::find($user_data->id);
                            //$user->email = strtolower($record['email']);
                            $user->name = $record['first_name']." ".$record['last_name'];
                            $user->first_name = $record['first_name'];
                            $user->last_name = $record['last_name'];
                            $user->gender = $gender;
                            $user->dial_code = $record['clinic_dialcode']??'';
                            $user->phone = str_replace(" ", "", ltrim($record['clinic_number'], "0"));
                            if($record['password'] != ''){
                                $user->password = Hash::make($record['password']);
                            }
                            
                            $user->email_verified_at = now();
                            $user->last_updated_by = Auth::user()->id;
                            $user->updated_at = now();
                            $user->save();

                            $check_doctor = Doctor::where(['user_id'=>$user->id])->get();
                            if($check_doctor->count() > 0){
                                $doctor = Doctor::find($check_doctor->first()->id);
                            }else{
                                $doctor = new Doctor();
                                $doctor->user_id = $user->id;
                            }
                                $doctor->country_id = $record['country_of_origin'];
                                $doctor->hospital_id = $record['hospital_id'];
                                $doctor->profile_desciription = $record['profle'];
                                $doctor->year_of_experiance = $record['year_of_experience'];
                                // $doctor->license_no = $record['dha_license_no'];
                                // $doctor->license_no_moh = $record['moh_license_no'];
                                // $doctor->license_no_doh = $record['doh_license_no'];
                                // $doctor->license_no_dhcc = $record['dhcc_license_no'];
                                $doctor->gender = $gender;
                                $doctor->appointment_dial_code = $record['direct_dial_code'];
                                $doctor->appointment_phone = str_replace(" ", "", $record['direct_contact_number_for_appoitment']);
                                $doctor->temp_photo_file_name = $record['photo_file_name']??'';
                                $doctor->save();

                                if($record['department']){
                                    $doctor->departments()->sync([$record['department']]);
                                }

                                DoctorLanguageSpoken::where(['doctor_id'=>$doctor->id])->delete();
                                if(!empty($record['language_spoken'])){
                                    foreach ($record['language_spoken'] as $language_spoken) {
                                        $doctorLanguageSpoken = new DoctorLanguageSpoken();
                                        $doctorLanguageSpoken->doctor_id = $doctor->id;
                                        $doctorLanguageSpoken->language_spoken_id = (int)$language_spoken;
                                        $doctor->doctorLanguageSpoken()->save($doctorLanguageSpoken);
                                    }
                                }

                                DoctorQualifications::where(['doctor_id'=>$doctor->id])->delete();
                                if(!empty($record['qualification'])){
                                    foreach ($record['qualification'] as $qualification) {
                                        $doctorQualification = new DoctorQualifications();
                                        $doctorQualification->doctor_id = $doctor->id;
                                        $doctorQualification->qualification_id = (int)$qualification;
                                        $doctor->doctorQualifications()->save($doctorQualification);
                                    }
                                }

                                DoctorSpecialities::where(['doctor_id'=>$doctor->id])->delete();
                                if(!empty($record['speciality'])){
                                    foreach ($record['speciality'] as $speciality) {
                                        $doctorSpeciality = new DoctorSpecialities();
                                        $doctorSpeciality->doctor_id = $doctor->id;
                                        $doctorSpeciality->speciality_id = (int)$speciality;
                                        $doctor->doctorSpecialities()->save($doctorSpeciality);
                                    }
                                }

                                DoctorIntrests::where(['doctor_id'=>$doctor->id])->delete();
                                if(!empty($record['special_intrests'])){
                                    foreach ($record['special_intrests'] as $language_interest) {
                                        $doctorInterest = new DoctorIntrests();
                                        $doctorInterest->doctor_id = $doctor->id;
                                        $doctorInterest->special_intrest_id = (int)$language_interest;
                                        $doctor->doctorIntrests()->save($doctorInterest);
                                    }
                                }

                                

                            DB::commit();
                            $any_succes = 1;
                        } catch (Exception $e) {
                            DB::rollback();
                            $err_msg.=$record['first_name'].' faild to create due to '.$e->getMessage().'<br>';
                        }
                    }else{
                        $err_msg.=$record['email'].' already exist in our db'.'<br>';
                    }
                }else{
                    DB::beginTransaction();
                    try {
                        $gender = 1;
                        if($record['gender'] == 'Female'){
                            $gender = 2;
                        }else if($record['gender'] == 'Others'){
                            $gender = 3;
                        }
                        $user = new User();
                        $user->email = strtolower($record['email']);
                        $user->name = $record['first_name']." ".$record['last_name'];
                        $user->first_name = $record['first_name'];
                        $user->last_name = $record['last_name'];
                        $user->gender = $gender;
                        $user->dial_code = $record['clinic_dialcode']??'';
                        $user->phone = str_replace(" ", "", ltrim($record['clinic_number'], "0"));
                        $user->password = Hash::make($record['password']);
                        $user->role = DOCTOR_ROLE;
                        $user->active = 0;
                        $user->email_verified_at = now();
                        $user->created_by = Auth::user()->id;
                        $user->last_updated_by = Auth::user()->id;
                        $user->updated_at = now();
                        $user->save();

                        $doctor = new Doctor();
                        $doctor->user_id = $user->id;
                        $doctor->country_id = $record['country_of_origin'];
                        $doctor->hospital_id = $record['hospital_id'];
                        $doctor->profile_desciription = $record['profle'];
                        $doctor->year_of_experiance = $record['year_of_experience'];
                        // $doctor->license_no = $record['dha_license_no'];
                        // $doctor->license_no_moh = $record['moh_license_no'];
                        // $doctor->license_no_doh = $record['doh_license_no'];
                        // $doctor->license_no_dhcc = $record['dhcc_license_no'];
                        $doctor->gender = $gender;
                        $doctor->appointment_dial_code = $record['direct_dial_code'];
                        $doctor->appointment_phone = str_replace(" ", "", $record['direct_contact_number_for_appoitment']);
                        $doctor->temp_photo_file_name = $record['photo_file_name']??'';
                        $doctor->save();

                        if($record['department']){
                            $doctor->departments()->sync([$record['department']]);
                        }

                        if(!empty($record['language_spoken'])){
                            foreach ($record['language_spoken'] as $language_spoken) {
                                $doctorLanguageSpoken = new DoctorLanguageSpoken();
                                $doctorLanguageSpoken->doctor_id = $doctor->id;
                                $doctorLanguageSpoken->language_spoken_id = (int)$language_spoken;
                                $doctor->doctorLanguageSpoken()->save($doctorLanguageSpoken);
                            }
                        }

                        if(!empty($record['qualification'])){
                            foreach ($record['qualification'] as $qualification) {
                                $doctorQualification = new DoctorQualifications();
                                $doctorQualification->doctor_id = $doctor->id;
                                $doctorQualification->qualification_id = (int)$qualification;
                                $doctor->doctorQualifications()->save($doctorQualification);
                            }
                        }

                        if(!empty($record['speciality'])){
                            foreach ($record['speciality'] as $speciality) {
                                $doctorSpeciality = new DoctorSpecialities();
                                $doctorSpeciality->doctor_id = $doctor->id;
                                $doctorSpeciality->speciality_id = (int)$speciality;
                                $doctor->doctorSpecialities()->save($doctorSpeciality);
                            }
                        }

                        if(!empty($record['special_intrests'])){
                            foreach ($record['special_intrests'] as $language_interest) {
                                $doctorInterest = new DoctorIntrests();
                                $doctorInterest->doctor_id = $doctor->id;
                                $doctorInterest->special_intrest_id = (int)$language_interest;
                                $doctor->doctorIntrests()->save($doctorInterest);
                            }
                        }

                        DB::commit();
                        $any_succes = 1;
                    } catch (Exception $e) {
                        DB::rollback();
                        $err_msg.=$record['first_name'].' faild to create due to '.$e->getMessage().'<br>';
                    }

                }
            }
            if($any_succes == 1){

                return redirect()->back()->with('success', 'Data imported successfully.'.$err_msg);
            }else{
                return redirect()->back()->with('error', $err_msg);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            // Handle validation failures
            return redirect()->back()->with('error', 'There were validation errors.');
        }
    }
public function uploadAndExtractZip(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|mimes:zip',
        ]);

        // Get the uploaded ZIP file
        $file = $request->file('zip_file');

        // Define the storage paths
        $zipFilePath = $file->storeAs('uploads/temp', $file->getClientOriginalName());
        $extractToPath = storage_path('app/uploads/extracted_doctor');

        // Create the directory if it doesn't exist
        if (!file_exists($extractToPath)) {
            mkdir($extractToPath, 0777, true);
        }

        // Initialize ZipArchive
        $zip = new ZipArchive;

        // Open the ZIP file
        if ($zip->open(storage_path('app/' . $zipFilePath)) === TRUE) {
            // Extract the contents to the specified directory
            $zip->extractTo($extractToPath);
            $zip->close();
            exec("php " . base_path() . "/artisan app:extract-doctor-images > /dev/null 2>&1 & ");

            // Optionally, delete the uploaded ZIP file after extraction
            Storage::delete($zipFilePath);

            return back()->with('success', 'ZIP file extracted successfully!');
        } else {
            return back()->with('error', 'Failed to open the ZIP file.');
        }
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

    public function uploadAppointmentDocs(Request $request)
{
    $bookingId = $request->booking_id ?? null;

    if (!$bookingId) {
        return false;
    }

    // Lab Reports Upload
    if ($request->hasFile('lab_report')) {

        foreach ($request->file('lab_report') as $file) {

            $res = image_front_s3_upload(
                $file,
                config('global.appointment')
            );
            
            if ($res['status']) {

                if ($res['status']) {
                    $doc=new AppointmentDoc;
                    $doc->docment = $res['link'];
                    $doc->appointment_id = $bookingId;
                    $doc->type = 'lab_test';
                    $doc->save();

                    
                }
            }
        }
        exec("php " . base_path() . "/artisan app:send-lab-result-notification " . $bookingId . " > /dev/null 2>&1 & ");
    }

    // Xray Upload
    if ($request->hasFile('xray')) {

        foreach ($request->file('xray') as $file) {

            $res = image_front_s3_upload(
                $file,
                config('global.appointment')
            );
                   
            if ($res['status']) {

                $doc=new AppointmentDoc;
                            $doc->docment = $res['link'];
                            $doc->appointment_id = $bookingId;
                            $doc->type = 'xray';
                            $doc->save();
            }
        }
        exec("php " . base_path() . "/artisan app:send-lab-result-notification " . $bookingId . " > /dev/null 2>&1 & ");
    }

    return true;
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
    'booking_type' => 'required',
    'booking_time_slot' => 'required',
    'booking_date' => 'required|date_format:d-m-Y',

    'lab_report' => 'required_without:no_reports|array',
    'lab_report.*' => 'file|mimes:jpg,jpeg,png,pdf',

    'xray' => 'required_without:no_reports|array',
    'xray.*' => 'file|mimes:jpg,jpeg,png,pdf',

], [
    // ✅ Custom messages
    'lab_report.required_without' => 'Please upload lab report.',
    'xray.required_without' => 'Please upload X-ray / imaging report.',

    'lab_report.*.mimes' => 'Lab report must be a file of type: jpg, jpeg, png, pdf.',
    'xray.*.mimes' => 'X-ray / imaging report must be a file of type: jpg, jpeg, png, pdf.',
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
            $bookingTypes = BookingType::where('status', 1)->get();
            $settings = SettingsModel::first();
            
            return view('front.doctor.book-appointment', compact('page_heading', 'settings','bookingTypes','doctor', 'time_slot', 'patient', 'insurencePolicies'))
                    ->withErrors($validator);
        }
    
       $upload= $this->uploadAppointmentDocs($request);
       $consent_file='';
       if ($request->hasFile('consent')) {

    $res = image_upload(
        $request,
        config('global.user_image_upload_dir'),
        'consent'
    );

    if ($res['status']) {

        $consent_file = $res['link'];

        // optional
        // $bookingData['consent_file'] = $res['link'];
    }
}

       $bookingData = $request->except(['lab_report', 'xray','consent']);;
        $bookingData['hospital_id'] = $doctor->hospital_id;
        $bookingData['consent_file'] = $consent_file;
       
       
        if (!Auth::check() || Auth::User()->role != USER_ROLE) {
            $guestBookingId = uniqid('guest_booking_', true);
            session([$guestBookingId => $bookingData]);

           
            
            $page_heading = "Patient Login";
            $insurence_list = InsurencePolicy::where(['status'=>1])->orderBy('title','asc')->get();
            $language_spoken = Languages::where(['status'=>1])->get();
            return view('front.auth', compact('page_heading', 'guestBookingId','insurence_list','language_spoken'));
        }
        
        $bookingData['patient'] = Auth::User();
        
        if($request->patient != Auth::User()->id){
            $bookingData['member_id'] = $request->patient_id;
            $bookingData['member'] = Members::find($request->patient_id);
        }
        
        $request->session()->put(Auth::User()->id.'_booking', $bookingData);
        $page_heading = 'Overview Booking';
         $amount = $doctor ? $doctor->user->consultation_fee : 0;
         $use_points = $request->use_points;

        $discount = 0;
        $finalAmount = $amount;

        $settings = getSettings();

        if (
            $use_points &&
            $settings &&
            $settings->loyallty_points_enable == 1 &&
            !empty($settings->loyallty_points_for_percentage)
        ) {
            $discount = ($amount * $settings->loyallty_points_percentage) / 100;
            $finalAmount = $amount - $discount;
        }
        
        return view('front.doctor.overview-booking', compact('page_heading', 'bookingData', 'doctor','amount','discount','finalAmount'));
    }


    public function redirectToStripe(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
       // consultation_fee
        // Get cart items for line items
       $bookingData = $request->session()->get(Auth::User()->id.'_booking');
       $doctor=User::where('id',$bookingData['doctor_id'])->first();
       $amount = ($doctor) ? $doctor->consultation_fee : 0;

            if (isset($bookingData['use_points']) && $bookingData['use_points']) {

            $percentage = getSettings()->loyallty_points_percentage;

            // CALCULATE DISCOUNT
            $discountAmount = ($amount * $percentage) / 100;

            // FINAL AMOUNT AFTER DISCOUNT
            $amount = $amount - $discountAmount;
            }
       
       
        $booking_id=$bookingData['booking_id'];
        $lineItems[] = [
            'price_data' => [
                'currency' => 'aed',
                'product_data' => [
                    'name' => 'Doctor Appointment',
                ],
                'unit_amount' => round($amount* 100),
            ],
            'quantity' => 1,
        ];
       

        try {
            $checkoutSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('front.booking-confirm') . '?stripe_session={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('front.payment.cancel', [], true),
                'client_reference_id' => Auth::id(),
                'metadata' => [
                    'temp_order_id' =>$booking_id,
                    'order_number' => $booking_id,
                    'user_id' => Auth::id()
                ]
            ]);


            return redirect()->away($checkoutSession->url);

        } catch (Exception $e) {
            return redirect()->route('front.checkout')->with('error', 'Stripe session creation failed: ' . $e->getMessage());
        }
    }
      private function calculateCommission($consultation_fee)
    {
        $settings = \App\Models\SettingsModel::first();
        $commission_percentage = $settings->comission ?? 10;
        $admin_commission = ($consultation_fee * $commission_percentage) / 100;
        $doctor_earning = $consultation_fee - $admin_commission;
        
        return [
            'admin_commission' => $admin_commission,
            'doctor_earning' => $doctor_earning
        ];
    }
    public function book_appointment_save(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $message = "Appointment Booked Successfully";
        $bookingData = $request->session()->get(Auth::User()->id.'_booking');

         $sessionId = $request->query('stripe_session');
        
        if (!$sessionId) {
            return 'Invalid payment session';
        }

        DB::beginTransaction();
        
        try {
            // Verify session with Stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $checkoutSession = StripeSession::retrieve($sessionId);

            if ($checkoutSession->payment_status !== 'paid') {
                return 'Payment not completed';
            }
        
        
        $booking_id=$bookingData['booking_id'];
        $request->session()->pull(Auth::User()->id.'_booking');
        
        if($bookingData && $bookingData['booking_date'] && $bookingData['doctor_id'] && $bookingData['booking_time_slot']){
            $FourDigitRandomNumber = rand(1231, 7879);
            // Create a new appointment
            $doctor = new DoctorPatientAppointment();
            $doctor->booking_id = '#MED' . $FourDigitRandomNumber;
            
            // $doctor->member_id = '0';

            $doctor->created_at = gmdate('Y-m-d H:i:s');
            
            $doctor_data = Doctor::where('user_id', $bookingData['doctor_id'])->first();
            $consultation_fee = $doctor_data->user->consultation_fee ?? 0;
            if (isset($bookingData['use_points']) && $bookingData['use_points']) {

            $settings= getSettings();

            $percentage = $settings->loyallty_points_percentage;

            // CALCULATE DISCOUNT
            $discountAmount = ($consultation_fee * $percentage) / 100;

            // FINAL AMOUNT AFTER DISCOUNT
            $consultation_fee = $consultation_fee - $discountAmount;

            $user = auth()->user();
             PointHistory::create([
        'user_id'        => $user->id,
        'appointment_id' => $doctor->id,
        'type'           => 'Debit',
        'points'         => $settings->loyallty_points_for_percentage,
        'description'    => "Spend {$settings->loyallty_points_for_percentage} loyalty credits on appointment payment of {$doctor_data->user->consultation_fee} AED"
    ]);
                $user->used_points=$user->used_points+$settings->loyallty_points_for_percentage;
                $user->points=$user->points-$settings->loyallty_points_for_percentage;
                $user->save();
            }
            $commission = $this->calculateCommission($consultation_fee);
            // Common fields for both add and update
            $doctor->doctor_id = $doctor_data->id;
            $doctor->hospital_id = $bookingData['hospital_id'];
            $doctor->booking_type = $bookingData['booking_type'];
            
            $doctor->consent = $bookingData['consent_file'];

            
            
            
            $doctor->user_id = Auth::User()->id;
            $doctor->booking_date = \Carbon\Carbon::createFromFormat('d-m-Y', $bookingData['booking_date'])->format('Y-m-d');
            $doctor->booking_time_slot = $bookingData['booking_time_slot'];
             $doctor->booking_status = DoctorPatientAppointment::PAYMENT_STATUS_PENDING;
            $doctor->is_urgent =  false;
            $doctor->consultation_fee = $consultation_fee;
            $doctor->admin_commission = $commission['admin_commission'];
            $doctor->doctor_earning = $commission['doctor_earning'];
            
            $doctor->payment_status = DoctorPatientAppointment::PAYMENT_STATUS_PAID;
            $doctor->payment_completed_at = now();
    
            if($bookingData['member_id'] ?? null){
                $member = Members::find($bookingData['member_id']);
                $doctor->member_id = $member->id??0;
            }

            if($bookingData['patient_id'] != Auth::User()->id){
                $doctor->member_id = $bookingData['patient_id'];
            }
    
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
    
            // Update member_id if provided
            // if ($bookingData['has']('member')) {
            //     $doctor->member_id = $bookingData['member'];
            // }
            $doctor->created_by = Auth::User()->id;
            $doctor->save();

            if (isset($bookingData['use_points']) && $bookingData['use_points']) {

            $settings= getSettings();

            $percentage = $settings->loyallty_points_percentage;

            $user = auth()->user();
             PointHistory::create([
                'user_id'        => $user->id,
                'appointment_id' => $doctor->id,
                'type'           => 'Debit',
                'points'         => $settings->loyallty_points_for_percentage,
                'description'    => "Spend {$settings->loyallty_points_for_percentage} loyalty credits on appointment payment of {$doctor_data->user->consultation_fee} AED"
            ]);
               
            }

            addLoyaltyPoints( $doctor->created_by, $consultation_fee, $doctor->id);
           
            AppointmentDoc::where('appointment_id',$booking_id)->update(['appointment_id'=>$doctor->id]);
            $this->generateToken('MED' . $FourDigitRandomNumber);
           DB::commit();
           exec("php " . base_path() . "/artisan app:send-email-notification-patient " . $doctor->id . " > /dev/null 2>&1 & ");
           exec("php " . base_path() . "/artisan app:send-order-payment-notification " . $doctor->id . " > /dev/null 2>&1 & ");
            $status = "1";
            $o_data['redirect'] = '';
            $o_data['data'] = $doctor;
            
        }else{
            $status = "0";
            $o_data['redirect'] = '';
            $message = "Cannot Book this Appointment";
           
        }
        }catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('front.checkout')->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
         return view('front.appointment-success-message', compact('doctor'));
        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data]);
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
        $bookingTypes = BookingType::where('status', 1)->get();

        $amount = $doctor ? $doctor->user->consultation_fee : 0;

        $use_points = $request->session()->get('use_points');

        $discount = 0;
        $finalAmount = $amount;

        $settings = getSettings();

        if (
            $use_points &&
            $settings &&
            $settings->loyallty_points_enable == 1 &&
            !empty($settings->loyallty_points_for_percentage)
        ) {
            $discount = ($amount * $settings->loyallty_points_percentage) / 100;
            $finalAmount = $amount - $discount;
        }
        // dd($bookingData);
        return view('front.doctor.overview-booking', compact('page_heading', 'bookingData', 'doctor', 'patient','bookingTypes','use_points','amount','discount','finalAmount'));
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
            'reason' => 'required|string'
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

    public function addAppointmentHistory($appointmentId, $status, $changedBy)
    {
        return DoctorAppointmentsStatus::create([
            'appointment_id' => $appointmentId,
            'status' => $status,
            'changed_by' => $changedBy,
            'changed_at' => now(),
        ]);
    }

    public function generateToken($channelName)
{
    $appID = config('services.agora.app_id');
    $appCertificate = config('services.agora.app_certificate');

    $uid = auth()->id() ?? rand(1, 9999);

    $role = \App\Libraries\Agora\RtcTokenBuilder::RoleAttendee;
    $expireTimeInSeconds = 3600;

    $currentTimestamp = now()->timestamp;
    $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

    $token = \App\Libraries\Agora\RtcTokenBuilder::buildTokenWithUid(
        $appID,
        $appCertificate,
        $channelName,
        $uid,
        $role,
        $privilegeExpiredTs
    );

    return [
        'token' => $token,
        'uid' => $uid,
        'app_id' => $appID
    ];
}
public function updateFeedback(Request $request)
{
    $validator = Validator::make($request->all(), [
        'feedback_id' => 'required|exists:hospital_doctor_feedback,id',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => "0",
            'message' => $validator->errors()->first()
        ]);
    }

    $feedback = HospitalDoctorFeedback::where('id', $request->feedback_id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$feedback) {
        return response()->json([
            'status' => "0",
            'message' => "Feedback not found"
        ]);
    }

    $feedback->update([
        'rating' => $request->rating,
        'feeback_message' => $request->feeback_message,
    ]);

    return response()->json([
        'status' => "1",
        'message' => "Feedback updated successfully"
    ]);
}

public function deleteFeedback(Request $request)
{
    $feedback = HospitalDoctorFeedback::where('id', $request->id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$feedback) {
        return response()->json([
            'status' => "0",
            'message' => "Feedback not found"
        ]);
    }

    $feedback->delete();

    return response()->json([
        'status' => "1",
        'message' => "Feedback deleted successfully"
    ]);
}

public function approveDocument(Request $request)
{
    $appointment = DoctorPatientAppointment::find($request->id);

    if (!$appointment) {
        return response()->json([
            'status' => 0,
            'message' => 'Appointment not found'
        ]);
    }

    $appointment->document_permission = 1;
    $appointment->save();

    return response()->json([
        'status' => 1,
        'message' => 'Document request approved successfully'
    ]);
}
}

?>
