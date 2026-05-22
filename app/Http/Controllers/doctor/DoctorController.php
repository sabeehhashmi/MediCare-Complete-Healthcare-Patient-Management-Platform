<?php

namespace App\Http\Controllers\doctor;
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
use App\Models\Languages;
use App\Models\DoctorPatientAppointment;
use App\Models\LicenceType;
use App\Models\Qualifications;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator,DB;
use App\Models\Area;
use App\Models\CountryModel;
use App\Models\HospitalDepartmentModel;
use App\Models\DoctorAppointmentFollowup;
use App\Models\DoctorAppointmentsStatus;
use App\Models\Emirate;
use App\Models\User;
use App\Models\HospitalImage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DataTables;

class DoctorController extends Controller
{
    public function index(){
       
        $page_heading="Doctors";
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $doctor->id;
        
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
            ->get();
            
            

        return view('doctor.doctors',compact('page_heading','doctors'));
    }

    public function appointmentCancel(REQUEST $request){
        DB::beginTransaction();
        try {
        
            $doctor = DoctorPatientAppointment::find($request->appointment_id);     
             
            $doctor->booking_status   = "Cancelled";
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->reason_cancel  = $request->reason_cancel;
            $doctor->save();

            $this->addAppointmentHistory($doctor->id, $doctor->booking_status, auth()->user()->id);
             activity_log('appointment_canceled', 'Appointment Canceled', [
                'appointment_id' => $doctor->booking_id
            ]);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
            
            DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor InstantAppointment Save Successfully";
         return response()->json( [ 'success' => 'Doctor Appointment Save Successfully!' ] );
        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }


    }

    public function appointmentCompleted(REQUEST $request){
        DB::beginTransaction();
        try {
        
            $doctor = DoctorPatientAppointment::find($request->appointment_id);
            $doctor->booking_status   = "Completed";
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->save();

            $this->addAppointmentHistory($doctor->id, $doctor->booking_status, auth()->user()->id);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
             activity_log('appointment_completed', 'Appointment Completed', [
                'appointment_id' => $doctor->booking_id
            ]);
            DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor InstantAppointment Save Successfully";
         return response()->json( [ 'success' => 'Doctor Appointment Save Successfully!' ] );
        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }



    }

    public function appointmentConfirmed(REQUEST $request){
        DB::beginTransaction();
        try {
            $doctor = DoctorPatientAppointment::find($request->appointment_id);           
            $doctor->booking_status   = 'Confirmed'?? null;
            $doctor->updated_at = gmdate('Y-m-d H:i:s');

            $doctor->save();
            $this->addAppointmentHistory($doctor->id, $doctor->booking_status, auth()->user()->id);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
            DB::commit();
             activity_log('appointment_confirmed', 'Appointment Confirmed', [
                'appointment_id' => $doctor->booking_id
            ]);
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor InstantAppointment Save Successfully";
         return response()->json( [ 'success' => 'Doctor Appointment Save Successfully!' ] );
        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }


    }

    public function detail($id){
       
        $page_heading="Doctors";
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
            ->get();
            
            

        return view('doctor.doctordetail',compact('page_heading','doctors'));
    }

  
    public function appointments(Request $request,$id){
        
        $page_heading="Appointments";
        $query = DoctorPatientAppointment::join('doctors', 'doctor_patient_appointments.doctor_id', '=', 'doctors.id')
                 ->select('doctor_patient_appointments.*'); 
       
            
        $query->where('doctor_patient_appointments.doctor_id', $id);
        

           
            // Filter by booking_status if provided
            if ($request->filled('booking_status')) {
                $query->where('doctor_patient_appointments.booking_status', $request->booking_status);
            }

            // Filter by booking_date range if provided
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('doctor_patient_appointments.booking_date', [$request->from_date, $request->to_date]);
            }
        
        $sqlQuery = $query->toSql();
        
        $appointments = $query->get();
        $todayDate = Carbon::now()->format('d-m-Y');

        $todayAppointments = Doctor::where('doctors.id',$id)
                            ->with(['doctor_patient_appointments' => function ($query) use ($todayDate) {
                                $query->whereRaw("to_char(to_date(booking_date, 'DD-MM-YYYY'), 'DD-MM-YYYY') = ?", [$todayDate])
                                ->whereNull('deleted_at');
                            }])
                            ->with('user') 
                            ->get();

        $tomorrowDate = Carbon::now()->addDay()->format('d-m-Y');

        $tomorrowAppointments = Doctor::where('doctors.id',$id)
            ->with(['doctor_patient_appointments' => function ($query) use ($tomorrowDate) {
                $query->whereRaw("to_char(to_date(booking_date, 'DD-MM-YYYY'), 'DD-MM-YYYY') = ?", [$tomorrowDate])
                    ->whereNull('deleted_at');
            }])
            ->with('user')
            ->get();
        $dayAfterTomorrowDate = Carbon::now()->addDays(2)->format('d-m-Y');

        $dayAfterTomorrowAppointments = Doctor::where('doctors.id',$id)
        ->with(['doctor_patient_appointments' => function ($query) use ($dayAfterTomorrowDate) {
            $query->whereRaw("to_char(to_date(booking_date, 'DD-MM-YYYY'), 'DD-MM-YYYY') = ?", [$dayAfterTomorrowDate])
                ->whereNull('deleted_at');
        }])
        ->with('user')
        ->get();

        return view('doctor.appointment',compact('page_heading','id','appointments','todayAppointments','tomorrowAppointments','dayAfterTomorrowAppointments'));

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
    
    public function availability(){
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->get()->first();
        $page_heading="Availability";
        $module_heading="Availability";
        $doctor_id = $doctor->id;
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

        $doctor = Doctor::find($doctor_id);
        $doctorAble = DoctorAvailability::where('doctor_id', $doctor_id)->first();
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
        return view('doctor.availability',compact('page_heading','module_heading','time_slot',
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
                // 'redirect' => route('clinic.doctors')
            ];
    
            return response()->json(['status' => $status, 'errors' => [], 'message' => $message, 'oData' => $o_data]);
    
        } catch (Exception $e) {
            DB::rollback();
            $status = "0";
            $message = "Failed to save doctor availability: " . $e->getMessage();
            return response()->json(['status' => $status, 'errors' => [$message], 'message' => $message, 'oData' => []]);
        }
    }

    public function instantAppointment(){
        $page_heading="Instant Appointment";
        $module_heading="Instant Appointment";
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->get()->first();
        $doctor_id = $doctor->id;
        // dd($doctor->doctorInstantAppointment);
        return view('doctor.instantAppointment',compact('page_heading','module_heading',
        'doctor_id', 'doctor'));
    }

    public function temporaryUnavailable(){
       
        $page_heading="Temporary Unavailable";
        $module_heading="Temporary Unavailable";
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->get()->first();
        $doctor_id = $doctor->id;
        // $doctor = Doctor::find($doctor_id);
        $time_slot = TIME_SLOTS;

        return view('doctor.temporaryUnavailable',compact('page_heading','module_heading','time_slot',
        'doctor_id', 'doctor'));
    }

    public function holiday(){
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->get()->first();
        $page_heading="Holiday";
        $module_heading="Holiday";
        $doctor_id = $doctor->id;
        return view('doctor.holiday',compact('page_heading','module_heading','doctor_id', 'doctor'));
    }

    public function instantAppointmentSave(Request $request) {
        Validator::extend('unique_dates', function($attribute, $value, $parameters, $validator) {
            return count($value) === count(array_unique($value));
        }, 'The :attribute field has duplicate dates.');
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'instant_appointment_date' => 'required|array|unique_dates',
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
                    'redirect' => route('clinic.doctors')
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
                    'redirect' => route('clinic.doctors')
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
        $o_data['redirect'] = route('doctor.doctors.index');

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
                'redirect' => route('clinic.doctors')
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

    // public function saveAppointmentFollowup(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required|exists:doctor_patient_appointments,id',
    //         'followup_date' => 'required|date_format:d-m-Y H:i',
    //         'followup_details' => 'required|string|max:255',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => '0',
    //             'message' => 'Validation error occurred',
    //             'errors' => $validator->messages()
    //         ]);
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $appointment = DoctorPatientAppointment::find($request->id);
    //         $appointment->followup_date = \Carbon\Carbon::createFromFormat('d-m-Y H:i', $request->followup_date);
    //         $appointment->followup_details = $request->followup_details;
    //         $appointment->updated_at = now();
    //         $appointment->save();

    //         DB::commit();

    //         return response()->json([
    //             'status' => '1',
    //             'message' => 'Appointment follow-up Save Successfully',
    //             'oData' => [
    //                 'redirect' => route('clinic.doctors')
    //             ]
    //         ]);
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         return response()->json([
    //             'status' => '0',
    //             'message' => 'Failed to save appointment follow-up: ' . $e->getMessage(),
    //             'errors' => []
    //         ]);
    //     }
    // }

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