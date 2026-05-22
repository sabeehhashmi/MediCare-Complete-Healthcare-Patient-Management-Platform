<?php

namespace App\Http\Controllers\hospital;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\DoctorSpecialities;

use App\Models\DepartmentModel;
use App\Models\DoctorTemporaryUnavailable;
use App\Models\DoctorHolidays;
use App\Models\DoctorInstantAppointment;
use App\Models\DoctorIntrests;
use App\Models\DoctorAvailability;
use App\Models\DoctorLanguageSpoken;
use App\Models\DoctorQualifications;
use App\Models\SpecialIntrests;
use App\Models\CountryOfOrigin;
use App\Models\Languages;
use App\Models\DoctorPatientAppointment;
use App\Models\DoctorAppointmentFollowup;
use App\Models\LicenceType;
use App\Models\Qualifications;
use App\Models\DoctorDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator,DB;
use App\Models\Area;
use App\Models\CountryModel;
use App\Models\HospitalDepartmentModel;
use App\Models\Emirate;
use App\Models\User;
use App\Models\HospitalImage;
use App\Models\SubInsurencePolicy;
use App\Models\DoctorAppointmentsStatus;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DataTables;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index(Request $request){
       
        $page_heading="Doctors";
        $module_heading="Doctors";
        $loginuserid = Auth::id(); 
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $hospital->id;
        
        $doctors = Doctor::query()
            ->leftJoin('users', 'users.id', '=', 'doctors.user_id')
            ->leftJoin('country', 'country.id', '=', 'doctors.country_id')        
            ->select(
                'doctors.*', 
                'users.email',
                'users.first_name',
                'users.last_name', 
                'users.dial_code',
                'users.phone',
                'country.name as country_name'
            )
            ->where('doctors.hospital_id', $hospitalId)
            ->orderBy('doctors.id', 'desc')
            ->paginate(100);
            
            // dd($doctors[0]->specialities->pluck('title'));

        return view('hospital.doctors.doctors',compact('page_heading','module_heading', 'doctors'));
    }
    public function patientAppointmentCancel(REQUEST $request){
        DB::beginTransaction();
        try {
        
            $doctor = DoctorPatientAppointment::find($request->appointment_id);     
             
            $doctor->booking_status   = BOOKING_STATUS_CANCELLED;
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->reason_cancel  = $request->reason_cancel;
            $doctor->save();
            $this->addAppointmentHistory($doctor->id, $doctor->booking_status, auth()->user()->id);
            DB::commit();
            activity_log('appointment_canceled', 'Appointment Canceled', [
                'appointment_id' => $doctor->booking_id
            ]);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
            $status = "1";
            $page_heading="Doctors";
            $module_heading="Doctors";
            $message = "Appointment Canceled Save Successfully";
         return response()->json( [ 'success' => $message ] );
        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }


    }

    public function patientAppointmentCompleted(REQUEST $request){
        DB::beginTransaction();
        try {
            $doctor = DoctorPatientAppointment::find($request->appointment_id);
            $doctor->booking_status   = BOOKING_STATUS_COMPLETED;
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->save();
            
            $this->addAppointmentHistory($doctor->id, $doctor->booking_status, auth()->user()->id);
            DB::commit();
             activity_log('appointment_completed', 'Appointment Completed', [
                'appointment_id' => $doctor->booking_id
            ]);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
            $status = "1";
            $page_heading="Doctors";
            $module_heading="Doctors";
            $message = "Appointment Completed Save Successfully";
         return response()->json( [ 'success' => $message ] );
        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }



    }

    public function saveAppointmentFollowup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:doctor_patient_appointments,id',
            'followup_date' => 'required|date_format:d-m-Y H:i',
            'followup_details' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '0',
                'message' => 'Validation error occurred',
                'errors' => $validator->messages()
            ]);
        }

        DB::beginTransaction();
        try {
            $appointment = DoctorPatientAppointment::find($request->id);
            $followup = new DoctorAppointmentFollowup();
            $followup->followup_date = \Carbon\Carbon::createFromFormat('d-m-Y H:i', $request->followup_date);
            $followup->appointment_id = $appointment->id;
            $followup->doctor_id = $appointment->doctor_id;
            $followup->notes = $request->followup_details;
            $followup->updated_at = now();
            $followup->save();

            DB::commit();

            return response()->json([
                'status' => '1',
                'message' => 'Appointment follow-up Save Successfully',
                'oData' => [
                    'redirect' => route('hospital.doctors')
                ]
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => '0',
                'message' => 'Failed to save appointment follow-up: ' . $e->getMessage(),
                'errors' => []
            ]);
        }
    }

    public function patientAppointmentConfirmed(REQUEST $request){
        DB::beginTransaction();
        try {
  
            $doctor = DoctorPatientAppointment::find($request->appointment_id);     
            $doctor->booking_status   = BOOKING_STATUS_CONFIRMED;
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->save();
            
            $this->addAppointmentHistory($doctor->id, $doctor->booking_status, auth()->user()->id);
            DB::commit();
            activity_log('appointment_confirmed', 'Appointment Confirmed', [
                'appointment_id' => $doctor->booking_id
            ]);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");

            $status = "1";
            $page_heading="Doctors";
            $module_heading="Doctors";
            $message = "Appointment Confirmed Save Successfully";
         return response()->json( [ 'success' => $message ] );
        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }


    }

    public function detail($id){
       
        $page_heading="Doctors";
        $module_heading="Doctors";
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $hospital->id;
        $doctor = Doctor::find($id);
        $insurances = [];
        if($hospital->insurences && count($hospital->insurences)){
            $sub_insurances_ids = $hospital->insurences->pluck('sub_insurance_id');
            $subinsurancesData = SubInsurencePolicy::whereIn('id', $sub_insurances_ids)->get();
            foreach($subinsurancesData as $sub_insurance){
                $insurances[$sub_insurance->insurence_id]['insurance'] = $sub_insurance->insurence_with_trashed ;
                $insurances[$sub_insurance->insurence_id]['sub_insurances'][] = $sub_insurance;
            }
        }
        // dd($doctor);
        $totalappointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)->count();
        $pendingappointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)->whereRaw('LOWER(booking_status) = ?', ['pending'])->count();
        $confirmappointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)->whereRaw('LOWER(booking_status) = ?', ['confirmed'])->count();
        $completedappointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)->whereRaw('LOWER(booking_status) = ?', ['completed'])->count();
        $cancelledappointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)->whereRaw('LOWER(booking_status) = ?', ['cancelled'])->count();
        return view('hospital.doctordetail',compact('page_heading','module_heading','doctor', 'totalappointments', 'pendingappointments', 'confirmappointments', 'completedappointments', 'cancelledappointments', 'insurances'));
    }

    public function create($id=''){
        
        $page_heading="Create Doctor";
        $module_heading="Doctors";
        $country_list = CountryOfOrigin::where(['status'=>1])->orderBy('name','asc')->get();
        $emirates_list=[];
        $doctor=null;
        $area_list = [];
        $country_id = '';
        $emirate_id = '';
        $area_id    = '';
        $first_name = '';
        
        $hospital_name = Hospital::whereHas('user',function($q){
            $q->where('active', 1);
        })->orderBy('hospitals.name_en', 'asc')->get();

        $last_name  = '';
        $qualification = Qualifications::where(['status'=>1])->orderBy('title','asc')->get();
        $specialty = Specialty::where(['active'=>1])->orderBy('name_en','asc')->get();
        $special_interest = SpecialIntrests::where(['status'=>1])->orderBy('title','asc')->get();
        $experiences = '';
        $license_no = '';
        $license_type = LicenceType::where(['status'=>1])->orderBy('title','asc')->get();
        $qualification_id = '';
        $license_type_id = '';
        $language_spoken_id = '';
        $speciality_id = '';
        $special_intrest_id = '';
        $language_spoken = Languages::where(['status'=>1])->orderBy('title','asc')->get();
        $gender = '';
        $phone = '';
        $email = '';
        $profile_bio = '';
        $direct_phone = '';
        $department_id = [];
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $hospital->id;
        $departments = $hospital->departments;
// dd($departments);
        if($id){
            $page_heading="Edit Doctor";
            $module_heading="Doctors";
            $doctor = Doctor::find($id);
            // dd($doctor->user);
            $user = User::where('id',$doctor->user_id)->get()->first();
            if($doctor){       
                $first_name = $user->first_name;
                $last_name = $user->last_name;
                $country_id = $doctor->country_id;
                $language_spoken_id =  $doctor->doctorLanguageSpoken->pluck('language_spoken_id')->toArray();
                $profile_bio = $doctor->profile_desciription;
                $qualification_id = $doctor->doctorQualifications->pluck('qualification_id')->toArray();
                $speciality_id = $doctor->doctorSpecialities->pluck('speciality_id')->toArray();
                $special_intrest_id = $doctor->doctorIntrests->pluck('special_intrest_id')->toArray();
                $experiences=$doctor->year_of_experiance;
                $license_no = $doctor->license_no;
                $license_type_id =json_decode($doctor->license_type_id);
                $gender =$doctor->gender;
                $department_id = $doctor->departments->pluck('id')->toArray();
            }
        }else{
            $country_id     = 229; //$country_list->first()->id??0;
            $emirates_list  = Emirate::where(['active'=>1,'country_id'=>$country_id])->get();
            $emirate_id     = $emirates_list->first()->id??0;
            $area_list      = Area::where(['active'=>1,'emirate_id'=>$emirate_id])->get();
        }

        return view('hospital.doctors.createdoctor',compact(
            'page_heading','module_heading',
            'id',
            'country_list',
            'emirates_list',
            'area_list',
            'country_id',
            'emirate_id',
            'area_id',
            'hospital_name',
            'first_name',
            'last_name', 
            'qualification_id',
            'license_type_id',
            'language_spoken_id',
            'speciality_id',
            'special_intrest_id',
            'qualification',
            'specialty',
            'special_interest',
            'experiences',
            'license_no',
            'license_type',
            'language_spoken',
            'gender',
            'phone',
            'email',
            'profile_bio',
            'direct_phone',
            'departments',
            'department_id',
            'doctor'
        ));
    }
    public function appointments(Request $request,$id){
        $loginUserId = Auth::id();
        $hospital = Hospital::where('user_id', $loginUserId)->firstOrFail();
        $hospitalId = $hospital->id;
        
        // Get doctors and their associated user data
        $doctor = Doctor::find($id);
        $doctor_id = $doctor->id;
        $doctor_user_id = $doctor->user_id;
        $patientsOnly = User::with('Members')->where('role', USER_ROLE)->where('active', 1)->get();
        $patients = [];
        
        foreach ($patientsOnly as $patient) {
            // Add the patient to the patientMembers array with a 'type' key
            $patientArray = $patient->toArray();
            $patientArray['type'] = 'patient';
            $patientArray['fullname'] = $patientArray['first_name'] . ' ' . $patientArray['last_name'];
            $patients[] = $patientArray;
    
            // If the patient has members, loop through them and add to the patients array with a 'type' key
            if ($patient->Members) {
                foreach ($patient->Members as $member) {
                    $memberArray = $member->toArray();
                    $memberArray['type'] = 'member';
                    $memberArray['fullname'] = $memberArray['full_name'];
                    $patients[] = $memberArray;
                }
            }
        }
    
        usort($patients, function ($a, $b) {
            return strcmp($a['fullname'], $b['fullname']);
        });
        $page_heading = $doctor->user->name." Appointments";
        $module_heading="Appointments";
        // $doctorIds = $doctors->pluck('id');
        $doctors = [$doctor];
        // dd($hospital);
        $hospitals = [$hospital];
        // Get departments
        $departments = $hospital->departments;
        // dd($departments->toArray());
        $totalDepartments = $departments->count();
        
        // Get appointments with eager loaded doctor and patient data
        $appointmentsQuery = DoctorPatientAppointment::with(['doctor.user', 'user']);
            // ->where('doctor_id', $id);
            // ->whereIn('doctor_id', $doctorIds);
        
        // Filter appointments based on request parameters
        $whereClauses = [];
        $whereClausesCalender = ['hospital_id'=>$hospitalId];

        $whereClauses[] = ['doctor_id', $id,'hospital_id'=>$hospitalId];

        if ($request->filled('department_id')) { 
            $whereClauses[] = ['department_id', $request->department_id];
            $whereClausesCalender[] = ['doctor_patient_appointments.department_id', $request->department_id];
        }

        if ($request->filled('doctor_id')) {
            $whereClauses[] = ['doctor_id', $request->doctor_id];
            $whereClausesCalender[] = ['doctor_patient_appointments.doctor_id', $request->doctor_id];
        }

        if ($request->filled('hospital_id')) {
            $whereClauses[] = ['hospital_id', $request->hospital_id];
            $whereClausesCalender[] = ['doctor_patient_appointments.hospital_id', $request->hospital_id];
        }

        if ($request->filled('clinic_id')) {
            $whereClauses[] = ['hospital_id', $request->clinic_id];
            $whereClausesCalender[] = ['doctor_patient_appointments.hospital_id', $request->clinic_id];
        }

        if ($request->filled('booking_status')) {
            $whereClauses[] = ['booking_status',  $request->booking_status];
            $whereClausesCalender[] = ['doctor_patient_appointments.booking_status', $request->booking_status];
        }

        if ($request->filled('from_date')) {
            $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
            $whereClauses[] = ['booking_date', '>=', $from_date];
            $whereClausesCalender[] = ['doctor_patient_appointments.booking_date', '>=', $from_date];
        }
    
        if ($request->filled('to_date')) {
            $to_date = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
            $whereClauses[] = ['booking_date', '<=', $to_date];
            $whereClausesCalender[] = ['doctor_patient_appointments.booking_date', '<=', $to_date];
        }
        
        $appointments = DoctorPatientAppointment::with(['doctor.user', 'user'])
        ->where($whereClauses)
        ->where(['hospital_id'=>$hospitalId])
        ->orderBy('id', 'desc')
        ->paginate(10);

        // dd($appointments->toArray());
        // Calculate appointment counts
        $appointmentCounts = DoctorPatientAppointment::select('booking_status', DB::raw('count(*) as count'))
            ->where('doctor_id', $id)
            ->groupBy('booking_status')
            ->get()
            ->pluck('count', 'booking_status');
        
        $pendingAppointments = $appointmentCounts['pending'] ?? 0;
        $confirmedAppointments = $appointmentCounts['Confirmed'] ?? 0;
        $completedAppointments = $appointmentCounts['Completed'] ?? 0;
        $cancelledAppointments = $appointmentCounts['Cancelled'] ?? 0;
        
        // Get today's, tomorrow's, and the day after tomorrow's appointments
        // $todayDate = Carbon::now()->format('Y-m-d');
        // $tomorrowDate = Carbon::now()->addDay()->format('Y-m-d');
        // $dayAfterTomorrowDate = Carbon::now()->addDays(2)->format('Y-m-d');
        
        // $todayAppointments = $this->getAppointmentsByDate($id, $todayDate);
        // $tomorrowAppointments = $this->getAppointmentsByDate($id, $tomorrowDate);
        // $dayAfterTomorrowAppointments = $this->getAppointmentsByDate($id, $dayAfterTomorrowDate);

        $restAllAppointments = $this->getSortedAndGroupedAppointments($doctor_id, $whereClausesCalender);

        return view('hospital.appointment', compact(
            'page_heading','module_heading', 
            // 'todayAppointments', 
            // 'tomorrowAppointments', 
            // 'dayAfterTomorrowAppointments', 
            'restAllAppointments',
            'doctor',
            'doctors', 
            'hospitals', 
            'patients', 
            'departments', 
            'totalDepartments', 
            'pendingAppointments', 
            'confirmedAppointments', 
            'completedAppointments', 
            'cancelledAppointments', 
            'appointments',
            'doctor_id',
            'doctor_user_id'
        ));
    }

    private function getAppointmentsAboveDate($id, $where) {
        return Doctor::whereHas('doctor_patient_appointments', function($query) use ($where) {
                $query->whereNull('deleted_at')->where($where);
            })
            ->with(['doctor_patient_appointments' => function ($query) use ($where) {
                $query->whereNull('deleted_at')->where($where);
            }])
            ->with('user')
            ->where('id', $id)
            ->get();
    }
    
    public function getSortedAndGroupedAppointments($id, $where)
    {
        $restAllAppointments = [];
        $allAppointments = $this->getAppointmentsAboveDate($id, $where);
        foreach ($allAppointments as $doctor) {
            foreach ($doctor->doctor_patient_appointments as $patientAppointment) {
                if ($patientAppointment) {
                    $bookingDate = $patientAppointment->booking_date;
                    $doctorId = $doctor->id;

                    if (!isset($restAllAppointments[$bookingDate])) {
                        $restAllAppointments[$bookingDate] = [];
                    }
                    if (!isset($restAllAppointments[$bookingDate][$doctorId])) {
                        $restAllAppointments[$bookingDate][$doctorId] = [
                            'user' => $doctor,
                            'doctor_patient_appointments' => []
                        ];
                    }
                    $restAllAppointments[$bookingDate][$doctorId]['doctor_patient_appointments'][] = $patientAppointment;
                }
            }
        }

        ksort($restAllAppointments);

        return $restAllAppointments;
    }

    private function getAppointmentsByDate($doctorId, $date) {
        return Doctor::whereHas('doctor_patient_appointments', function($query) use ($date) {
                $query->where('doctor_patient_appointments.booking_date', $date)
                      ->whereNull('deleted_at');
            })
            ->with(['doctor_patient_appointments' => function ($query) use ($date) {
                $query->where('doctor_patient_appointments.booking_date', $date)
                      ->whereNull('deleted_at');
            }])
            ->with('user')
            ->where('id', $doctorId)
            ->get();
    }
    public function availability($id){
       
        $page_heading="Availability";
        $module_heading="Doctors";
        $doctor_id = $id;
        $sunday_availability = 0;
        $sunday_time_slot = [];
        $monday_availability = 0;
        $monday_time_slot = [];
        $tuesday_availability = 0;
        $tuesday_time_slot = [];
        $wednesday_availability = 0;
        $wednesday_time_slot = [];
        $thursday_availability = 0;
        $thursday_time_slot = [];
        $friday_availability = 0;
        $friday_time_slot = [];
        $saturday_availability = 0;
        $saturday_time_slot = [];
        $days = [
            'Sun' => 'Sunday',
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday'
        ];

        $doctor = Doctor::find($id);
        $doctorAble = DoctorAvailability::where('doctor_id', $id)->first();
        if($doctorAble){
          
            $sunday_availability = $doctorAble->sunday_availability;
            if($doctorAble->sunday_time_slot && $doctorAble->sunday_time_slot != 'null' ){
                $sunday_time_slot = json_decode($doctorAble->sunday_time_slot);
            }
            $monday_availability = $doctorAble->monday_availability;
            if($doctorAble->monday_time_slot && $doctorAble->monday_time_slot != 'null' ){
                $monday_time_slot = json_decode($doctorAble->monday_time_slot);
            }
            $tuesday_availability = $doctorAble->tuesday_availability;
            if($doctorAble->tuesday_time_slot && $doctorAble->tuesday_time_slot != 'null' ){
                $tuesday_time_slot = json_decode($doctorAble->tuesday_time_slot);   
            }
            $wednesday_availability = $doctorAble->wednesday_availability;
            if($doctorAble->wednesday_time_slot && $doctorAble->wednesday_time_slot != 'null' ){
                $wednesday_time_slot = json_decode($doctorAble->wednesday_time_slot);
            }
            $thursday_availability = $doctorAble->thursday_availability;
            if($doctorAble->thursday_time_slot && $doctorAble->thursday_time_slot != 'null' ){
                $thursday_time_slot = json_decode($doctorAble->thursday_time_slot);
            }
            $friday_availability = $doctorAble->friday_availability;
            if($doctorAble->friday_time_slot && $doctorAble->friday_time_slot != 'null' ){
                $friday_time_slot = json_decode($doctorAble->friday_time_slot);
            }
            $saturday_availability = $doctorAble->saturday_availability;
            if($doctorAble->saturday_time_slot && $doctorAble->saturday_time_slot != 'null' ){
                $saturday_time_slot = json_decode($doctorAble->saturday_time_slot);
            }
        }
        $time_slot = TIME_SLOTS;
// dd($friday_time_slot);
        return view('hospital.availability',compact('page_heading','module_heading','time_slot',
        'sunday_availability',
        'sunday_time_slot',
        'monday_availability',
        'monday_time_slot',
        'tuesday_availability',
        'tuesday_time_slot',   
        'wednesday_availability',
        'wednesday_time_slot',
        'thursday_availability',
        'thursday_time_slot',
        'friday_availability',
        'friday_time_slot',
        'saturday_availability',
        'saturday_time_slot',
        'doctor_id',
        'doctor',
        'days'
    ));

    }

    public function instantAppointment($id){
        $page_heading="Instant Appointment";
        $module_heading="Doctors";
        $doctor_id = $id;
        $doctor = Doctor::find($doctor_id);
        // dd($doctor->doctorInstantAppointment);
        return view('hospital.instantAppointment',compact('page_heading','module_heading',
        'doctor_id', 'doctor'));
    }
    public function temporaryUnavailable($id){
       
        $page_heading="Temporary Unavailable";
        $module_heading="Doctors";
        $doctor_id = $id;
        $doctor = Doctor::find($doctor_id);
        $time_slot = TIME_SLOTS;

        return view('hospital.temporaryUnavailable',compact('page_heading','module_heading','time_slot',
        'doctor_id', 'doctor'));
    }

    public function holiday($id){
       
        $page_heading="Holiday";
        $module_heading="Doctors";
        $doctor_id = $id;
        $doctor = Doctor::find($doctor_id);
        return view('hospital.holiday',compact('page_heading','module_heading','doctor_id', 'doctor'));
    }
    
    public function save(REQUEST $request)
    {
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospital_id  = $hospital->id;
        
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $sanitizedDirectPhone = preg_replace('/\D/', '', $request->direct_phone ?? null);
        $request->merge(['phone' => $sanitizedPhone, 'direct_phone' => $sanitizedDirectPhone]);

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('hospital.doctors');
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],     
            'gender'=>'required|numeric',
            'password' => !$request->id ? 'required|min:8' : '',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'language_spoken_id' => 'required|array',
            'language_spoken_id.*' => 'required|numeric',
            'qualification' => 'required|array',
            'qualification.*' => 'required|numeric',
            'specialty' => 'required|array',
            'specialty.*' => 'required|numeric',
            'special_interest' => 'required|array',
            'special_interest.*' => 'required|numeric',
        ];
    
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $id         = $request->id;
            $check_email      = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->where('id', '!=', $id)->get();

         
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $name =  $request->first_name.' '.$request->last_name;
                        $language_spoken_id = array_unique($request->language_spoken_id);
                        $qualification_id = array_unique($request->qualification);
                        $speciality_id = array_unique($request->specialty);
                        $special_intrest_id = array_unique($request->special_interest);
                        $doctor   = Doctor::find($id);
                        $user   = User::find($doctor->user_id);
                        $user->email    =   $request->email;
                        $user->name     =   $name;
                        $user->first_name     =   $request->first_name;
                        $user->last_name     =    $request->last_name; 
                        if ($request->hasfile('image')) {
                            $file = $request->file('image');
                            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $user->user_image = $file_name;
                        }
                        $user->gender = $request->gender;
                        $user->dial_code =  $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                        $user->phone     =   str_replace(" ","",ltrim($request->phone,"0"));
                        if($request->password){
                            $user->password  =   Hash::make($request->password);
                        }
                        $user->role      =   DOCTOR_ROLE;
                        $user->active    =   0;
                        $user->created_by = Auth::user()->id;
                        $user->last_updated_by = Auth::user()->id;
                        $user->updated_at = gmdate('Y-m-d H:i:s');
                        $user->save();

                        $doctor->user_id   = $user->id;
                        $doctor->country_id = $request->country;
                        $doctor->hospital_id = $hospital_id;
                        $doctor->profile_desciription = $request->profile_bio;
                        $doctor->year_of_experiance =$request->experiences;
                        $doctor->license_no = $request->license_no_dha;
                        $doctor->license_no_moh = $request->license_no_moh;
                        $doctor->license_no_doh = $request->license_no_doh;
                        $doctor->license_no_dhcc = $request->license_no_dhcc;
                        // $doctor->license_type_id = json_encode($request->license_type);
                        $doctor->gender = $request->gender;
                        $doctor->appointment_dial_code  = $request->direct_dial_code ? $request->direct_dial_code : DEFAULT_DIAL_CODE;
                        $doctor->appointment_phone      = str_replace(" ","",$request->direct_phone);
                        $doctor->save();
                        if ($request->has('departments')) {
                            $doctor->departments()->sync($request->departments);
                        }
                        if ($request->has('document_titles')) {

                        $existingIds = $request->document_ids ?? [];

DoctorDocument::where('doctor_id', $doctor->id)
    ->whereNotIn('id', array_filter($existingIds))
    ->delete();

    foreach ($request->document_titles as $index => $title) {

        if (
            empty($title)
            && empty($request->file('documents')[$index])
        ) {
            continue;
        }

        $docId = $request->document_ids[$index] ?? null;

        if ($docId) {

            $doctorDocument = DoctorDocument::find($docId);

        } else {

            $doctorDocument = new DoctorDocument();

            $doctorDocument->doctor_id = $doctor->id;
        }

        $doctorDocument->title = $title;

        if (
            $request->hasFile('documents')
            && isset($request->file('documents')[$index])
        ) {

            $file = $request->file('documents')[$index];

            $mini = new \Illuminate\Http\Request();

            $mini->files->set('document', $file);

            $res = image_upload(
                $mini,
                config('global.user_image_upload_dir'),
                'document'
            );

            if ($res['status']) {

                $doctorDocument->document = $res['link'];
            }
        }

        $doctorDocument->save();
    }
}
                        // $doctor->doctorQualifications()->delete();
                        // $doctor->doctorSpecialities()->delete();
                        // $doctor->doctorIntrests()->delete();
                        // $doctor->doctorLanguageSpoken()->delete();
                        DoctorLanguageSpoken::where('doctor_id', $doctor->id)->delete();
                        foreach ($language_spoken_id as $language_spoken) {
                            $doctorLanguageSpoken = new DoctorLanguageSpoken;
                            $doctorLanguageSpoken->doctor_id = $doctor->id;
                            $doctorLanguageSpoken->language_spoken_id = (int)$language_spoken;
                            $doctor->doctorLanguageSpoken()->save($doctorLanguageSpoken);
                        }

                        DoctorQualifications::where('doctor_id', $doctor->id)->delete();
                        foreach ($qualification_id as $qualification) {
                            $doctorQualification = new DoctorQualifications;
                            $doctorQualification->doctor_id = $doctor->id;
                            $doctorQualification->qualification_id = (int)$qualification;
                            $doctor->doctorQualifications()->save($doctorQualification);
                        }

                        DoctorSpecialities::where('doctor_id', $doctor->id)->delete();
                        foreach ($speciality_id as $speciality) {
                            $doctorSpeciality = new DoctorSpecialities;
                            $doctorSpeciality->doctor_id = $doctor->id;
                            $doctorSpeciality->speciality_id = (int)$speciality;
                            $doctor->doctorSpecialities()->save($doctorSpeciality);
                        }

                        DoctorIntrests::where('doctor_id', $doctor->id)->delete();
                        foreach ($special_intrest_id as $language_intrest) {
                            $doctorIntrest = new DoctorIntrests;
                            $doctorIntrest->doctor_id = $doctor->id;
                            $doctorIntrest->special_intrest_id = (int)$language_intrest;
                            $doctor->doctorIntrests()->save($doctorIntrest);
                        }
                        
                        DB::commit();

                        activity_log('doctor_profile_updated', "Doctor $user->name Profile Updated", [
                'doctor_id' => $user->name
            ]);
                        $status = "1";
                        $message = "Doctor updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create special intrest " . $e->getMessage();
                    }
                } else {
                    if ($check_email->count() > 0) {
                        $status = "0";
                        $message = "Email id already registred with us";
                        $errors['email'] = 'Email id already registred with us';
                    } else {
                    DB::beginTransaction();
                    try {
                        $language_spoken_id = $request->language_spoken_id;
                        $qualification_id = $request->qualification;
                        $speciality_id = $request->specialty;
                        $special_intrest_id = $request->special_interest;
                        $user = new User();
                        $name =  $request->first_name.' '.$request->last_name;
                        $user->email    =   strtolower($request->email);
                        $user->name     =  $name;
                        $user->first_name     =   $request->first_name;
                        $user->last_name     =    $request->last_name; 
                        $user->gender = $request->gender;
                        $user->dial_code =  $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                        if ($request->hasfile('image')) {
                            $file = $request->file('image');
                            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $user->user_image = $file_name;
                        }
                        $user->phone     =   str_replace(" ","",ltrim($request->phone,"0"));
                        if($request->password){
                            $user->password  =   Hash::make($request->password);
                        }
                        $user->role      =   DOCTOR_ROLE;
                        $user->active    =   0;
                        $user->created_by = Auth::user()->id;
                        $user->last_updated_by = Auth::user()->id;
                        $user->created_at = gmdate('Y-m-d H:i:s');
                        $user->updated_at = gmdate('Y-m-d H:i:s');
                        $verificationToken = Str::random(60);
                        $user->email_verification_token = $verificationToken;
                        $user->save();

                        $doctor =  new Doctor();
                        $doctor->user_id   = $user->id;
                        $doctor->country_id = $request->country;
                        $doctor->hospital_id = $hospital_id;
                        $doctor->profile_desciription = $request->profile_bio;
                        $doctor->year_of_experiance =$request->experiences;
                        $doctor->license_no = $request->license_no_dha;
                        $doctor->license_no_moh = $request->license_no_moh;
                        $doctor->license_no_doh = $request->license_no_doh;
                        $doctor->license_no_dhcc = $request->license_no_dhcc;
                        // $doctor->license_type_id = json_encode($request->license_type);
                        $doctor->gender = $request->gender;
                        $doctor->appointment_dial_code  = $request->direct_dial_code ? $request->direct_dial_code : DEFAULT_DIAL_CODE;
                        $doctor->appointment_phone      = str_replace(" ","",$request->direct_phone);
                        $doctor->save();

                        if ($request->has('document_titles')) {
        $existingIds = $request->document_ids ?? [];

        DoctorDocument::where('doctor_id', $doctor->id)
            ->whereNotIn('id', array_filter($existingIds))
            ->delete();
    foreach ($request->document_titles as $index => $title) {

        if (
            empty($title)
            && empty($request->file('documents')[$index])
        ) {
            continue;
        }

        $docId = $request->document_ids[$index] ?? null;

        if ($docId) {

            $doctorDocument = DoctorDocument::find($docId);

        } else {

            $doctorDocument = new DoctorDocument();

            $doctorDocument->doctor_id = $doctor->id;
        }

        $doctorDocument->title = $title;

        if (
            $request->hasFile('documents')
            && isset($request->file('documents')[$index])
        ) {

            $file = $request->file('documents')[$index];

            $mini = new \Illuminate\Http\Request();

            $mini->files->set('document', $file);

            $res = image_upload(
                $mini,
                config('global.user_image_upload_dir'),
                'document'
            );

            if ($res['status']) {

                $doctorDocument->document = $res['link'];
            }
        }

        $doctorDocument->save();
    }
}
                        
                        if ($request->has('departments')) {
                            $doctor->departments()->sync($request->departments);
                        }

                         foreach ($language_spoken_id as $language_spoken) {
    
                            $doctorLanguageSpoken = new DoctorLanguageSpoken;
                            $doctorLanguageSpoken->doctor_id = $doctor->id;
                            $doctorLanguageSpoken->language_spoken_id =(int)$language_spoken;
                            $doctor->doctorLanguageSpoken()->save($doctorLanguageSpoken);
                        }
                        foreach ($qualification_id as $qualification) {
                             
                            $doctorQualification = new DoctorQualifications;
                            $doctorQualification->doctor_id = $doctor->id;
                            $doctorQualification->qualification_id = (int)$qualification;
                            $doctor->doctorQualifications()->save($doctorQualification);
                        }
                        foreach ($speciality_id as $speciality) {
                
                            $doctorSpeciality = new DoctorSpecialities;
                            $doctorSpeciality->doctor_id = $doctor->id;
                            $doctorSpeciality->speciality_id = (int)$speciality;
                            $doctor->doctorSpecialities()->save($doctorSpeciality);
                        }
                        foreach ($special_intrest_id as $language_intrest) {
                           
                            $doctorIntrest = new DoctorIntrests;
                            $doctorIntrest->doctor_id = $doctor->id;
                            $doctorIntrest->special_intrest_id = (int)$language_intrest;
                            $doctor->doctorIntrests()->save($doctorIntrest);
                        }
                        DB::commit();
                         activity_log('doctor_profile_created', "Doctor $user->name Profile Created", [
                'doctor_id' => $user->name
            ]);
                         exec("php " . base_path() . "/artisan app:send-verification-mail " . $user->id . " doctor > /dev/null 2>&1 & ");
                        $status = "1";
                        $message = "Doctor Added Successfully";
                      //  Mail::to($user->email)->send(new VerifyEmail($user, 'doctor'));
                    } catch (EXCEPTION $e) {
                        DB::rollback();
                        $message = "Faild to create hospital " . $e->getMessage();
                    }
                }
            }
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function load_data(REQUEST $request){
        $users = Doctor::query()
        ->leftJoin('users', 'users.id', '=', 'doctors.user_id')
        ->leftJoin('country', 'country.id', '=', 'doctors.country_id')        
        ->select('doctors.*', 'users.email','users.first_name','users.last_name', 'users.dial_code','users.phone','country.name as country_name')
        ->orderBy('doctors.id','desc');

    return DataTables::eloquent($users)
        ->addColumn('action', function($user) {
             $action = '<div class="dropdown mt-4 mt-sm-0">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu">';

                // if (get_user_permission('doctors', 'r')) {
                //     $action.='<a class="dropdown-item complete-link" href="'.route('admin.doctors.show',['id'=>$user->id]).'">View </a>';
                // }
                if (get_user_permission('doctors', 'u')) {
                    $action.='<a class="dropdown-item complete-link" href="'.route('admin.doctors.edit',['id'=>$user->id]).'">Edit </a>';
                }
              
                if (get_user_permission('doctors', 'r')) {
                    $action.='<a class="dropdown-item complete-link" href="'.route('admin.doctors.appointments',['id'=>$user->id]).'">Total Appointments </a>';
                }
                if (get_user_permission('doctors', 'r')) {
                    $action.='<a class="dropdown-item complete-link" href="'.route('admin.doctors.availability',['id'=>$user->id]).'">Availability</a>';
                }
              
                if (get_user_permission('doctors', 'r')) {
                    $action.='<a class="dropdown-item complete-link" href="'.route('admin.doctors.holiday',['id'=>$user->id]).'">Holiday</a>';
                }
                if (get_user_permission('doctors', 'r')) {
                    $action.='<a class="dropdown-item complete-link" href="'.route('admin.doctors.temporaryUnavailable',['id'=>$user->id]).'">Temporary Unavailable</a>';
                }
                if (get_user_permission('doctors', 'r')) {
                    $action.='<a class="dropdown-item complete-link" href="'.route('admin.doctors.instantAppointment',['id'=>$user->id]).'">Schedule Instant Appointment Date</a>';
                }
                if (get_user_permission('doctors', 'r')) {
                    $action.='<a class="dropdown-item complete-link" href="">Reports </a>';
                }
             
                    
           $action .='</div>
            </div>';

            return $action;
        })
        ->addColumn('sl_no', function($user) {
            static $index = 0;
            return ++$index;
        })
        ->addColumn('phone_number', function($item) {
            return ($item->phone)?'+'.$item->dial_code.$item->phone:'';
        })
        
        
        ->toJson();
    }
    public function instantAppointmentSave(Request $request) {
        // Define validation rules
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
    
        DB::beginTransaction();
        try {
            $instantAppointmentDates = $request->instant_appointment_date;
            // DoctorInstantAppointment::where('doctor_id', $request->doctor_id)->delete();
            foreach($instantAppointmentDates as $date) {
                $doctor = new DoctorInstantAppointment();
                $doctor->doctor_id = $request->doctor_id;
                $doctor->instant_appointment_date = \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d'); // Convert to Y-m-d format
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
                    'redirect' => route('hospital.doctors')
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

    public function holiday_save(Request $request) {
        // Define validation rules
        Validator::extend('unique_dates', function($attribute, $value, $parameters, $validator) {
            // Check if there are duplicate values in the array
            return count($value) === count(array_unique($value));
        }, 'The :attribute field has duplicate dates.');
        
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'holiday_name' => 'required|array',
            'holiday_name.*' => 'required|string',
            'holiday_date' => 'required|array|unique_dates',
            'holiday_date.*' => 'required|date_format:d-m-Y'
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => "0",
                'errors' => $validator->messages(),
                'message' => "Validation error occurred"
            ]);
        }
        DB::beginTransaction();
        try {
            DoctorHolidays::where('doctor_id', $request->doctor_id)->delete();
            $holidayNames = $request->holiday_name;
            $dates = $request->holiday_date;
    
            // Combine arrays
            $combinedArray = [];
            for ($i = 0; $i < count($holidayNames); $i++) {
                $combinedArray[] = [
                    "holiday_name" => $holidayNames[$i],
                    "date" => Carbon::createFromFormat('d-m-Y', $dates[$i])->format('Y-m-d') // Convert to Y-m-d format
                ];
            }
    
            foreach($combinedArray as $data) {
                $doctorHoliday = new DoctorHolidays();
                $doctorHoliday->doctor_id = $request->doctor_id;
                $doctorHoliday->holiday_name = $data['holiday_name'];
                $doctorHoliday->holiday_date = $data['date'];
                $doctorHoliday->created_at = now();
                $doctorHoliday->updated_at = now();
                $doctorHoliday->save();
            }
    
            DB::commit();
    
            return response()->json([
                'status' => "1",
                'errors' => [],
                'message' => "Doctor holiday saved successfully!",
                'oData' => [
                    'redirect' => route('hospital.doctors')
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
        $o_data['redirect'] = route('hospital.doctors');

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
    
    public function temporaryUnavailableSave(Request $request) {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'unavailable_date' => 'required|date_format:d-m-Y',
            'unavailable_timeslot' => 'required|array',
            'unavailable_timeslot.*' => 'required|string' // Add any specific validation for the timeslot array elements
        ]);
    
        // Check if validation fails
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
            $unavailable_date = \DateTime::createFromFormat('d-m-Y', $request->unavailable_date)->format('Y-m-d');
            DoctorTemporaryUnavailable::where('doctor_id', $request->doctor_id)->delete();
            $doctor = new DoctorTemporaryUnavailable();
            $doctor->doctor_id = $request->doctor_id;
            $doctor->unavailable_date = $unavailable_date;
            $doctor->unavailable_timeslot = json_encode($request->unavailable_timeslot);
            $doctor->created_at = gmdate('Y-m-d H:i:s');
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->save();
    
            DB::commit();
    
            $status = "1";
            $message = "Doctor Unavailability saved successfully!";
            $o_data = [
                'redirect' => route('hospital.doctors')
            ];
    
            return response()->json([
                'status' => $status,
                'errors' => [],
                'message' => $message,
                'oData' => $o_data
            ], 200);
    
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => "0",
                'message' => 'Failed to save doctor unavailability: ' . $e->getMessage(),
                'errors' => [],
                'oData' => []
            ], 500);
        }
    }

    
    // public function temporaryUnavailableSave(REQUEST $request){
    //     DB::beginTransaction();

    //     try {   
    //     // $unavailable_date = $request->unavailable_date;
    //         // $unavailable_timeslot = $request->unavailable_timeslot;
    //         // // Combine arrays
    //         // $combinedArray = [];
    //         // for ($i = 0; $i < count($unavailable_date); $i++) {
    //         //     $combinedArray[] = [
    //         //         "holiday_name" => $unavailable_date[$i],
    //         //         "date" => $unavailable_timeslot[$i]
    //         //     ];
    //         // }
    //         // foreach($combinedArray as $combinedArray){
                
    //             $doctor = new DoctorTemporaryUnavailable();
    //             $doctor->doctor_id   =  $request->doctor_id;
    //             $doctor->unavailable_date    = $request->unavailable_date;
    //             $doctor->unavailable_timeslot    =  json_encode($request->unavailable_timeslot);
    //             $doctor->created_at = gmdate('Y-m-d H:i:s');
    //             $doctor->updated_at = gmdate('Y-m-d H:i:s');
    //             $doctor->save();
    //         // }
    //         DB::commit();
    //         $status = "1";
    //         $page_heading="Doctors";
    //         $message = "Doctor Unavailability Save Successfully";
    //         return view('admin.doctors.index',compact('page_heading'));
         
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         $message = "Failed to create special intrest " . $e->getMessage();
    //     }

    // }

    public function availability_save(Request $request){
        DB::beginTransaction();
        try {
            // dd($request->all());
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
            $message = "Doctor Availability saved successfully!";
            $o_data = [
                'redirect' => route('hospital.doctors')
            ];
    
            return response()->json(['status' => $status, 'errors' => [], 'message' => $message, 'oData' => $o_data]);
    
        } catch (Exception $e) {
            DB::rollback();
            $status = "0";
            $message = "Failed to save doctor availability: " . $e->getMessage();
            return response()->json(['status' => $status, 'errors' => [$message], 'message' => $message, 'oData' => []]);
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
        if ($row) {
            $row->delete();
            User::where('id', $row->user_id)->update(['deleted' => 1]);
            $status = "1";
            $message = "Doctor removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }

    public function getDepartmentDoctors($department_id) {
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $data = Doctor::join('users', 'doctors.user_id', '=', 'users.id')->with('user')
            ->join('department_doctors', 'doctors.id', '=', 'department_doctors.doctor_id')
            ->where('department_doctors.department_id', $department_id)
            ->where('users.active', 1)
            ->where('hospital_id', $hospital->id)
            ->orderBy('users.name', 'asc')
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
 
    public function check_doctor_availability(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            //'access_token'=>'required',
            'booking_date'=>'required',
            'doctor_user_id'=> 'required'

        ]);
        $doctor = Doctor::where('user_id', $request->doctor_user_id)->first();
        // dd($doctor);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
            
        }else{
            $booking_date = \DateTime::createFromFormat('d-m-Y', $request->booking_date)->format('Y-m-d');
        // $user_id = $this->validateAccesToken($request->access_token);
            $page = $request->page??1;
            $limit=$request->limit??10;
            $offset = ($page -  1) * $limit;
        
        $list =  [];
        $doctor_time_slot = array();
            $checkHoliday = DoctorHolidays::where('doctor_id',$doctor->id)
            ->where('holiday_date', $booking_date )
            ->get();
            // dd($checkHoliday);
            $messageResponse = 'Doctor is not available';
            if($checkHoliday->count() == 0){
            
                $dayName = strtolower(date('l', strtotime($booking_date)));
                $list = DoctorAvailability::where('doctor_id', $doctor->id)
                    ->where($dayName.'_availability', 1)
                    ->select($dayName.'_availability',$dayName.'_time_slot')
                    ->orderBy('id','desc')->take($limit)->skip($offset)->first();
                    if($list){
                        $timeSlot = json_decode($list->{$dayName.'_time_slot'});
                        if($timeSlot){
                            $takenAppointment = DoctorPatientAppointment::where('doctor_id',$doctor->id)
                            ->where('booking_date',$booking_date )
                            ->where('booking_status', '!=', BOOKING_STATUS_CANCELLED )
                            ->pluck('booking_time_slot')->toArray();
                            $unavailable_timeslot = DoctorTemporaryUnavailable::where('unavailable_date', $booking_date)
                            ->where('doctor_id',$doctor->id)
                            ->pluck('unavailable_timeslot')->toArray();
                            $unavailable_timeslot = array_merge(...array_map('json_decode', $unavailable_timeslot));

                            foreach ($timeSlot as $key => $value) {
                                $doctor_time_slot[] =[
                                            "slot_text" => $timeSlot[$key],
                                            "is_available" => (!in_array($timeSlot[$key], $takenAppointment) && !in_array($timeSlot[$key], $unavailable_timeslot))
                                        ];
                            }
                        }
                    }

            }else{
                $messageResponse = "Doctor are on holiday";
            }
        
            if(!empty($doctor_time_slot)){
                $status = "1";
                $message = "data fetcehed successfully";
                $o_data['list'] = convert_all_elements_to_string($doctor_time_slot);
            }else{
                $message = $messageResponse;
            }
        }

        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
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

    public function addAppointmentHistory($appointmentId, $status, $changedBy)
    {
        return DoctorAppointmentsStatus::create([
            'appointment_id' => $appointmentId,
            'status' => $status,
            'changed_by' => $changedBy,
            'changed_at' => now(),
        ]);
    }
    
}
?>
