<?php

namespace App\Http\Controllers\doctor;

use App\Models\OrderModel;
use App\Models\AccountType;
use App\Models\VendorModel;
use App\Models\ActivityType;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\DepartmentModel;
use App\Models\DoctorPatientAppointment;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\GymSubscription;
use App\Models\WholeSaleRequests;
use App\Models\AppointmentDoc;
use App\Models\OrderProductsModel;
use App\Models\ReservationBooking;
use App\Models\Appointments;
use App\Models\Members;
use App\Models\DoctorAppointmentsStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\AppointmentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Direction;
use App\Models\Frequency;
use App\Models\Duration;
use App\Models\Dosage;
use App\Models\Prescription;
use App\Models\PrescriptionDetail;
use App\Models\Medicin;
use App\Models\ReferralDetail;
use App\Models\ClinicalSummary;
use App\Models\Referral;
use App\Models\RefferalDoctor;
use App\Models\ClinicalAssessmentAndDocumentation;
use Illuminate\Http\Request as MiniRequest;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Output\Destination;
use App\Models\BookingType;


class DoctorAppointmenstsController extends Controller
{
    protected $order_detail_route;
    
    public function index2(Request $request)
    {
        $page_heading = "Hospital Dashboard";
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->get()->first();
        $doctorId  = $doctor->id;
        //$doctorIds = Doctor::where('hospital_id', $hospitalId)->pluck('id');
       // $totaldoctors = Doctor::whereIn('hospital_id', $doctorIds)->count();
       // $doctors = Doctor::whereIn('doctors.id', $doctorIds)
       // ->join('users', 'doctors.user_id', '=', 'users.id')
       // ->select('doctors.*', 'users.name as doct_name')
       // ->get();
        
        // $doctors = Doctor::whereIn('hospital_id', $doctorIds ->join('users', 'doctors.user_id', '=', 'users.id'))->select('doctors.*', 'users.name as doct_name')->get();
        $totaldepartments = DepartmentModel::where('deleted',0)->count();
        $departments  = DepartmentModel::where('deleted',0)->get();
        $appointments = DoctorPatientAppointment::where('doctor_id', $doctorId)->orderBy('id', 'desc')->get();
        //$totalappointments = DoctorPatientAppointment::whereIn('doctor_id', $doctorIds)->count();
        $pendingappointments = DoctorPatientAppointment::where('doctor_id', $doctorId)->where('booking_status', 1)->count();
        $confirmappointments = DoctorPatientAppointment::where('doctor_id', $doctorId)->where('booking_status', 4)->count();
        $completedappointments = DoctorPatientAppointment::where('doctor_id', $doctorId)->where('booking_status', 2)->count();
        $cancelledappointments = DoctorPatientAppointment::where('doctor_id', $doctorId)->where('booking_status', 3)->count();
        // $appointments = DoctorPatientAppointment::whereIn('doctor_id', $doctorIds)->orderBy('id', 'desc')->take(5)->with('patient')->get();
        $query = DoctorPatientAppointment::join('doctors', 'doctor_patient_appointments.doctor_id', '=', 'doctors.id')
        ->select('doctor_patient_appointments.*'); 
        if ($request->filled('department_id')) {
            $query->where('doctors.department_id', $request->department_id);
        }
        if ($request->filled('doctor_id')) {
            
            $query->where('doctor_patient_appointments.doctor_id', $request->doctor_id);
        }else{
            
            $query->where('doctor_patient_appointments.doctor_id', $doctorId);
            if ($request->filled('department_id')) {
                $query->where('doctors.department_id', $request->department_id);
            }
            
            // Filter by booking_status if provided
            if ($request->filled('booking_status')) {
                $query->where('doctor_patient_appointments.booking_status', $request->booking_status);
            }
            
            // Filter by booking_date range if provided
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('doctor_patient_appointments.booking_date', [$request->from_date, $request->to_date]);
            }
        }
        $sqlQuery = $query->toSql();
        
        $appointments = $query->get();
        
        $todayDate = Carbon::now()->format('d-m-Y');

        $todayAppointments = Doctor::whereHas('doctor_patient_appointments', function ($query) use ($todayDate) {
                                $query->whereRaw("to_char(to_date(booking_date, 'DD-MM-YYYY'), 'DD-MM-YYYY') = ?", [$todayDate])
                                ->whereNull('deleted_at');
                            })
                            ->with(['doctor_patient_appointments' => function ($query) use ($todayDate) {
                                $query->whereRaw("to_char(to_date(booking_date, 'DD-MM-YYYY'), 'DD-MM-YYYY') = ?", [$todayDate])
                                ->whereNull('deleted_at');
                            }])
                            ->with('user') 
                            ->get();

        $tomorrowDate = Carbon::now()->addDay()->format('d-m-Y');

        $tomorrowAppointments = Doctor::whereHas('doctor_patient_appointments', function ($query) use ($tomorrowDate) {
                $query->whereRaw("to_char(to_date(booking_date, 'DD-MM-YYYY'), 'DD-MM-YYYY') = ?", [$tomorrowDate])
                    ->whereNull('deleted_at');
            })
            ->with(['doctor_patient_appointments' => function ($query) use ($tomorrowDate) {
                $query->whereRaw("to_char(to_date(booking_date, 'DD-MM-YYYY'), 'DD-MM-YYYY') = ?", [$tomorrowDate])
                    ->whereNull('deleted_at');
            }])
            ->with('user')
            ->get();
        $dayAfterTomorrowDate = Carbon::now()->addDays(2)->format('d-m-Y');

        $dayAfterTomorrowAppointments = Doctor::whereHas('doctor_patient_appointments', function ($query) use ($dayAfterTomorrowDate) {
            $query->whereRaw("to_char(to_date(booking_date, 'DD-MM-YYYY'), 'DD-MM-YYYY') = ?", [$dayAfterTomorrowDate])
                ->whereNull('deleted_at');
        })
        ->with(['doctor_patient_appointments' => function ($query) use ($dayAfterTomorrowDate) {
            $query->whereRaw("to_char(to_date(booking_date, 'DD-MM-YYYY'), 'DD-MM-YYYY') = ?", [$dayAfterTomorrowDate])
                ->whereNull('deleted_at');
        }])
        ->with('user')
        ->get();
        
        return view('doctor.totalappointments', compact('page_heading','doctor','dayAfterTomorrowAppointments','tomorrowAppointments','todayAppointments','departments','totaldepartments','pendingappointments','confirmappointments','completedappointments','cancelledappointments','appointments'));
    }

    public function index(Request $request) {
        $page_heading = "Appointments";
        $module_heading = "Total Appointments";
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->get()->first();
        $doctorId  = $doctor->id;
        
        $patientsOnly = User::with('Members')->where('role', USER_ROLE)->where('active', 1)->get();
        $patients = [];
    
        foreach ($patientsOnly as $patient) {
            // Add the patient to the patientMembers array with a 'type' key
            $patientArray = $patient->toArray();
            $patientArray['type'] = 'patient';
            $patientArray['fullname'] = $patientArray['first_name'] . ' ' . $patientArray['last_name'];
            $patientArray['phone'] = '+'.$patientArray['dial_code'] . ' ' . $patientArray['phone'];
            $patients[] = $patientArray;
    
            // If the patient has members, loop through them and add to the patients array with a 'type' key
            if ($patient->Members) {
                foreach ($patient->Members as $member) {
                    $memberArray = $member->toArray();
                    $memberArray['type'] = 'member';
                    $memberArray['fullname'] = $memberArray['full_name'];
                    $memberArray['phone'] = '+'.$patient->dial_code. ' ' . $patient->phone;
                    $patients[] = $memberArray;
                }
            }
        }
    
        usort($patients, function ($a, $b) {
            return strcmp($a['fullname'], $b['fullname']);
        });

        // Get departments
        $departments = $doctor->departments;
        // dd($departments->toArray());
        $totalDepartments = $departments->count();
        
        // Get appointments with eager loaded doctor and patient data
        $appointmentsQuery = DoctorPatientAppointment::with(['doctor.user', 'user']);
            // ->whereIn('doctor_id', $doctorIds);
        
        $whereClauses = [];
        $whereClausesCalender = [];
    
        if($doctorId){
            $whereClauses[] = ['doctor_id', $doctorId];
            // $whereClausesCalender[] = ['doctor_patient_appointments.hospital_id', $hospitalId];
        }
    
        // if ($request->filled('department_id')) {
        //     $whereClauses[] = ['department_id', $request->department_id];
        //     $whereClausesCalender[] = ['doctor_patient_appointments.department_id', $request->department_id];
        // }

        if ($request->filled('patient_id')) {

                $userIds = User::where('patient_id', $request->patient_id)
                    ->pluck('id');

                if ($userIds->isNotEmpty()) {
                    $appointmentsQuery->whereIn('user_id', $userIds);
                } else {
                    $appointmentsQuery->whereRaw('1 = 0'); // force no results
                }
            }
        if ($request->filled('doctor_id')) {
            $whereClauses[] = ['doctor_id', $request->doctor_id];
            $whereClausesCalender[] = ['doctor_patient_appointments.doctor_id', $request->doctor_id];
        }

        if ($request->filled('booking_type')) {
            $whereClauses[] = ['booking_type', $request->booking_type];
            $whereClausesCalender[] = ['doctor_patient_appointments.booking_type', $request->booking_type];
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
        
        $appointments = $appointmentsQuery->where($whereClauses)->orderBy('id', 'desc')->paginate(10);
        // $todayDate = Carbon::now()->format('Y-m-d');
        // $tomorrowDate = Carbon::now()->addDay()->format('Y-m-d');
        // $dayAfterTomorrowDate = Carbon::now()->addDays(2)->format('Y-m-d');
        
        // $todayAppointments = $this->getAppointmentsByDate($doctorId, $todayDate);
        // $tomorrowAppointments = $this->getAppointmentsByDate($doctorId, $tomorrowDate);
        // $dayAfterTomorrowAppointments = $this->getAppointmentsByDate($doctorId, $dayAfterTomorrowDate);
        $restAllAppointments = $this->getSortedAndGroupedAppointments($doctorId, $whereClausesCalender);
        $bookingTypes = BookingType::where('status', 1)->get();
        $booking_type=$request->booking_type??'';
        return view('doctor.totalappointments', compact(
            'page_heading', 'module_heading', 
            'doctorId', 
            'restAllAppointments',
            'doctor', 
            'patients', 
            'departments', 
            'totalDepartments', 
            'appointments',
            'bookingTypes',
            'booking_type'
        ));
    }

    private function getAppointmentsByDate($hospital_id, $date, $where) {
        return Doctor::whereHas('doctor_patient_appointments', function($query) use ($date, $where) {
                $query->where('doctor_patient_appointments.booking_date', $date)
                ->whereNull('deleted_at')->where($where);
            })
            ->with(['doctor_patient_appointments' => function ($query) use ($date, $where) {
                $query->where('doctor_patient_appointments.booking_date', $date)
                ->whereNull('deleted_at')->where($where);
            }])
            ->with('user')
            ->where('hospital_id', $hospital_id)
            ->get();
    }
    
    private function getAppointmentsAboveDate($doctor_id, $where) {
        return Doctor::whereHas('doctor_patient_appointments', function($query) use ($where) {
                // $query->where('doctor_patient_appointments.booking_date', '>', $date)
                //       ->whereNull('deleted_at')->where($where);
                $query->whereNull('deleted_at')->where($where);
            })
            ->with(['doctor_patient_appointments' => function ($query) use ($where) {
                // $query->where('doctor_patient_appointments.booking_date', '>', $date)
                //     ->whereNull('deleted_at')->where($where);
                $query->whereNull('deleted_at')->where($where);
            }])
            ->with('user')
            ->where('id', $doctor_id)
            ->get();
    }
    
    public function getSortedAndGroupedAppointments($doctor_id, $where)
    {
        $restAllAppointments = [];
        $allAppointments = $this->getAppointmentsAboveDate($doctor_id, $where);
        
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
    

    public function appointmentLoadData(REQUEST $request){
    
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->first();
        $hospital_id  = $hospital->id;
        // dd($hospital_id);
        $users = DoctorPatientAppointment::query()
        ->where('doctor_patient_appointments.hospital_id', '=', $hospital_id)
        ->join('users as doctor', 'doctor.id', '=', 'doctor_patient_appointments.doctor_id')
        ->join('users as patient', 'patient.id', '=', 'doctor_patient_appointments.user_id')
        ->select(
            'doctor.first_name as doctor_first_name',
            'doctor.last_name as doctor_last_name',
            'patient.first_name as patient_first_name',
            'patient.last_name as patient_last_name',
            'doctor_patient_appointments.hospital_id',
            'doctor_patient_appointments.booking_id',
            'doctor_patient_appointments.booking_status',
            'doctor_patient_appointments.booking_date',
            'doctor_patient_appointments.id',
            'doctor_patient_appointments.booking_time_slot',
            'doctor_patient_appointments.user_id'
        )
        ->orderBy('doctor_patient_appointments.id', 'desc');
    
        // dd($users->toArray());
        return DataTables::eloquent($users)
        ->addColumn('action', function($user) {
             $action = '<div class="dropdown mt-4 mt-sm-0">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu">';

                // if (get_user_permission('patients', 'r')) {
                    $action .= '<a class="dropdown-item complete-link" href="'.route('doctor.edit_appointment', ['id' => $user->id]).'">Edit</a>';
                    $action .= '<button class="dropdown-item complete-link delete-appointment" data-id="'.encrypt($user->id).'">Delete</button>';
                // }
                
           $action .='</div>
            </div>';

            return $action;
        })
        ->addColumn('processed_by', function ($appointment) {
            if ($appointment->status_history->count()) {
                $history = $appointment->status_history->first();
                return  !empty($history['changedBy']) ? ($history['changedBy']['name'] != '' ? $history['changedBy']['name'] : 'N/A') : 'N/A';
            } else {
                return $appointment->user->name;
            }
        })
        ->addColumn('sl_no', function($user) {
            static $index = 0;
            return ++$index;
        })
        ->addColumn('dr_name', function($item) {
            return $item->doctor_first_name.''.$item->doctor_last_name;
        })
        ->addColumn('patient_name', function($item) {
            return $item->patient_first_name.''.$item->patient_last_name;
        })
        
        
        ->toJson();
    }

    public function create_appointment($id = '')
    {
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->first();
        $hospital_id  = $hospital->id;

        $row = null;
        $page_heading = $id ? "Edit Appointment" : "Book Appointment";
        $module_heading = "Appointments";
        $doctors = [];
        $departments = [];
        $members = [];
        if ($id) {
            $row = DoctorPatientAppointment::with(['user', 'doctor_reschedule_appointments'])->where('id', $id)->first();
            $row->booking_date = date('d-m-Y', strtotime($row->booking_date));
        }
        

        if($hospital_id){
            $departments = $hospital->departments;
            $doctors = Doctor::with('user')->where('hospital_id', $hospital_id)->get();
        }

        if ($row->department_id ?? null) {
            $doctors = Doctor::with('user')->whereHas('departments', function ($query) use ($row) {
                $query->where('department_id', $row->department_id);
            })->get();
        }
        
        $patients = User::where('role', USER_ROLE)->where('deleted', 0)->get();
        
        if($row->user_id ?? null){
            $members = Members::where('user_id', $row->user_id)->get();
        }
        // dd($members->toArray());
        $time_slot = TIME_SLOTS;
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
        // dd($row->toArray());
        return view('doctor.createAppointment', compact(
            'page_heading', 'module_heading',
            'id',
            'patients',
            'hospital_id',
            'hospital',
            'departments',
            'doctors',
            'time_slot',
            'members',
            'row'
        ));
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

    public function saveAppointment(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->first();
         $consultation_fee = $doctor->user->consultation_fee ?? 0;
        $commission = $this->calculateCommission($consultation_fee);
        
        $payment_token = \Illuminate\Support\Str::random(64);
        $hospital_id  = $doctor->hospital_id;
        // Validation rules
        $validator = Validator::make($request->all(), [
            'department' => 'nullable|exists:departments,id',
            'patient' => 'required|numeric',
            'booking_time_slot' => 'required',
            'booking_date' => 'required|date_format:d-m-Y',
            'member' => 'nullable|exists:members,id'
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $bookingId = $request->id ?? null;

            // Generate a random booking ID for new appointments
            $FourDigitRandomNumber = rand(1231, 7879);

            if ($bookingId) {
                // Update existing appointment
                $appointment = DoctorPatientAppointment::find($bookingId);

                if (!$appointment) {
                    return response()->json(['status' => '0', 'message' => 'Appointment not found', 'errors' => ['id' => 'Appointment not found']]);
                }

                $message = "Appointment Updated Successfully";
            } else {
                // Create a new appointment
                $appointment = new DoctorPatientAppointment();
                $appointment->booking_id = '#MEDN' . $FourDigitRandomNumber;
                $appointment->member_id = '0';
                $appointment->created_at = gmdate('Y-m-d H:i:s');
                $message = "Appointment Booked Successfully";
            }
            // $doctor_data = Doctor::where('user_id', $request->doctor)->first();
            // Common fields for both add and update
            $appointment->doctor_id = $doctor->id;
            $appointment->department_id = $request->department ?? null;
            $appointment->hospital_id = $hospital_id;
            $appointment->user_id = $request->patient;
            $appointment->booking_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->booking_date)->format('Y-m-d');
            $appointment->booking_time_slot = $request->booking_time_slot;
            $appointment->booking_status = DoctorPatientAppointment::PAYMENT_STATUS_PENDING;
            $appointment->is_urgent =  false;
            $appointment->consultation_fee = $consultation_fee;
            $appointment->admin_commission = $commission['admin_commission'];
            $appointment->doctor_earning = $commission['doctor_earning'];
            $appointment->payment_token = $payment_token;
            $appointment->payment_status = DoctorPatientAppointment::PAYMENT_STATUS_PENDING;
            $appointment->updated_at = gmdate('Y-m-d H:i:s');

            // Update member_id if provided
            if ($request->has('member')) {
                $appointment->member_id = $request->member;
            }
            $appointment->created_by = Auth::User()->id;
            $member = Members::find($request->patient);
            $patient = User::where('role', USER_ROLE)->where('id', $request->patient)->first();
            if($member && !$patient){
                $appointment->user_id = $member->user_id;
                $appointment->member_id = $member->id;
            }
            $appointment->save();

            $payment_url = route('front.appointment-payment', ['token' => $payment_token]);
            exec("php " . base_path() . "/artisan app:send-payment-email " . $appointment->id . " " . base64_encode($payment_url) . " > /dev/null 2>&1 & ");
            // EXTRA: If urgent, also notify admin
           

            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $appointment->id . " > /dev/null 2>&1 & ");


            $status = "1";
            $o_data['redirect'] = route('doctor.totalappointments', $doctor->doctor_id);
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    public function rescheduleAppointment(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->first();
        $hospital_id  = $doctor->hospital_id;
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

            // Generate a random booking ID for new appointments
            // $FourDigitRandomNumber = rand(1231, 7879);

            if ($bookingId) {
                // Update existing appointment
                $appointment = DoctorPatientAppointment::find($bookingId);

                if (!$appointment) {
                    return response()->json(['status' => '0', 'message' => 'Appointment not found', 'errors' => ['id' => 'Appointment not found']]);
                }

                $message = "Appointment Updated Successfully";
            }

            // Common fields for both add and update
            // $appointment->doctor_id = $doctor->id;
            // $appointment->department_id = $appointment->department_id ?? null;
            // $appointment->hospital_id = $hospital_id;
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
            activity_log('appointment_rescheduled', 'Appointment Rescheduled', [
                'appointment_id' => $appointment->booking_id
            ]);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $appointment->id . " > /dev/null 2>&1 & ");

            $status = "1";
            $o_data['redirect'] = route('doctor.totalappointments');
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    public function requestAccess(Request $request)
{
    // validation
    $request->validate([
        'appointment_id' => 'required',
        'patient_id' => 'required',
        'doctor_id' => 'required',
    ]);

    $appointment = DoctorPatientAppointment::find($request->appointment_id);
    $appointment->document_permission=2;
    $appointment->save();

    // For now just return success (you said approval flow later)
    
    return response()->json([
        'status' => 1,
        'message' => 'Access request sent successfully'
    ]);
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

    public function reports(Request $request)
    {
        $page_heading = "Reports";
        $module_heading = "Reports";
        $loginUserId = Auth::id();
        $doctor = Doctor::where('user_id', $loginUserId)->firstOrFail();
        $doctorId = $doctor->id;
        $hospital = $doctor->hospital;
        // Get doctors and their associated user data
        // $doctors = Doctor::with('user')->whereHas('user',function($q){
        //     $q->where('active', 1);
        // })->where('hospital_id', $hospitalId)
        //     ->get();
    
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
    
        // Get departments
        $departments = $doctor->departments;
    
        $appointmentsQuery = DoctorPatientAppointment::with(['doctor.user', 'user']);
    
        $whereClauses = [];
    
        if($doctorId){
            $whereClauses[] = ['doctor_id', $doctorId];
        }
        if ($request->filled('department_id')) {
            $whereClauses[] = ['department_id', $request->department_id];
        }
    
        if ($request->filled('doctor_id')) {
            $whereClauses[] = ['doctor_id', $request->doctor_id];
        }
    
        if ($request->filled('clinic_id')) {
            $whereClauses[] = ['hospital_id', $request->clinic_id];
        }
        
        if ($request->filled('hospital_id')) {
            $whereClauses[] = ['hospital_id', $request->hospital_id];
        }
    
        if ($request->filled('booking_status')) {
            $whereClauses[] = ['booking_status',  $request->booking_status];
        }
    
        if ($request->filled('from_date')) {
            $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
            $whereClauses[] = ['booking_date', '>=', $from_date];
        }
    
        if ($request->filled('to_date')) {
            $to_date = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
            $whereClauses[] = ['booking_date', '<=', $to_date];
        }
    
        $appointments = $appointmentsQuery->where($whereClauses)->orderBy('id', 'desc')->paginate(10);

        return view('doctor.reports', compact('page_heading','departments','appointments', 'hospital', 'module_heading'));
    }
    
    public function booking_details($id){
        $appointment = DoctorPatientAppointment::where('id', $id)->with('followups','docs','clinicalAssessment')->first();
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->first();
        // dd($appointment->user->user_img_url);
        $module_heading = 'Appointments';
        $prescription = Prescription::with('details')
            ->where('appointment_id', $appointment->id)
            ->first();
           
            $clinic_summary = ClinicalSummary::where('appointment_id', $appointment->id)
            ->first();
            
            

            // Lookup tables
            $medicines   = Medicin::where('status', 1)->orderBy('title_en')->get();
            $directions  = Direction::where('status', 1)->orderBy('title')->get();
            $frequencies = Frequency::where('status', 1)->orderBy('title')->get();
            $durations   = Duration::where('status', 1)->orderBy('title')->get();
            $dosages   =Dosage::where('status', 1)->orderBy('title')->get();
            $referral_details   =ReferralDetail::where('appointment_id', $appointment->id)
            ->first();
           
            $referrals   =Referral::where('status', 1)->orderBy('title')->get();
        return view('doctor.appointment_detail',compact('id','appointment', 'module_heading', 'doctor',
        'prescription','medicines','directions','frequencies','durations','dosages','clinic_summary','referral_details','referrals'));
        
    }

    
    public function getOrders()
    {
        $vendor_id = auth()->user()->id;
        $user_tye_id = auth()->user()->user_type_id;
        $activity_type_id = auth()->user()->activity_type_id;
        
        if ($user_tye_id == AccountType::RESERVATIONS) {
            if ($activity_type_id == ActivityType::GYM) {
                $this->order_detail_route = url('vendor/gym/subscription_details/');
                
                $orders = GymSubscription::select('gym_subscriptions.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name"))
                ->leftjoin('users', 'users.id', 'gym_subscriptions.user_id')
                ->where('store_id', $vendor_id)
                ->with(['customer'])
                ->orderBy('gym_subscriptions.id', 'DESC')
                ->limit(10)->get();
            } else {
                $this->order_detail_route = url('vendor/reservation/order_details/');
                
                $orders = ReservationBooking::select('reservation_bookings.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name, reservation_bookings.total_amount as grand_total"))
                ->leftjoin('users', 'users.id', 'reservation_bookings.user_id')
                ->where('vendor_id', $vendor_id)
                ->with(['customer'])
                ->orderBy('reservation_bookings.id', 'DESC')
                ->limit(10)->get();
            }
        } elseif ($user_tye_id == AccountType::SERVICE_PROVIDERS) {
            $this->order_detail_route = url('vendor/service/order_details/');
            $orders = ServiceRequest::select('service_requests.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name, service_requests.total_amount as grand_total"))
            ->leftjoin('users', 'users.id', 'service_requests.user_id')
            ->where('store_id', $vendor_id)
            ->with(['customer'])
            ->orderBy('service_requests.id', 'DESC')
            ->limit(10)->get();
        } elseif ($user_tye_id == AccountType::WHOLE_SELLERS) {
            $this->order_detail_route = url('vendor/wholesale/order_details/');
            $orders = WholeSaleRequests::select('whole_sale_requests.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name, whole_sale_requests.grand_total as grand_total"))
            ->leftjoin('users', 'users.id', 'whole_sale_requests.user_id')
            ->where('store_id', $vendor_id)
            ->with(['customer'])
            ->orderBy('whole_sale_requests.id', 'DESC')
            ->limit(10)->get();
        } else {
            //if ($user_tye_id == AccountType::COMMERCIAL_CENTER)
            if ($activity_type_id == ActivityType::RESTAURANTS || $activity_type_id == ActivityType::RESTAURANT) {
                $this->order_detail_route = url('vendor/food/order_details/');
                $orders = OrderModel::foodProductsOnly()->select('orders.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name, orders.order_id as id"))
                ->leftjoin('users', 'users.id', 'orders.user_id')
                ->where('store_id', $vendor_id)
                ->with(['customer'])
                ->orderBy('orders.order_id', 'DESC')
                ->limit(10)->get();
            } else {
                $this->order_detail_route = url('vendor/order_details/');
                $orders = OrderModel::select('orders.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name, orders.order_id as id"))
                ->leftjoin('users', 'users.id', 'orders.user_id')
                ->where('store_id', $vendor_id)
                ->with(['customer'])
                ->orderBy('orders.order_id', 'DESC')
                ->limit(10)->get();
            }
        }
        
        return $orders;
    }
    
    public function getOrderStatusCount()
    {
        $vendor_id = auth()->user()->id;
        $user_tye_id = auth()->user()->user_type_id;
        $activity_type_id = auth()->user()->activity_type_id;
        
        if ($user_tye_id == AccountType::RESERVATIONS) {
            if ($activity_type_id == ActivityType::GYM) {
                $st_count['pending'] = GymSubscription::where('store_id', $vendor_id)->where('subscription_status', config('global.gym_status_pending'))->count();
                // $st_count['cancelled'] = GymSubscription::where('store_id', $vendor_id)->where('subscription_status', config('global.gym_status_cancelled'))->count();
                $st_count['rejected'] = GymSubscription::where('store_id', $vendor_id)->where('subscription_status', config('global.gym_status_rejected'))->count();
                $st_count['completed'] = GymSubscription::where('store_id', $vendor_id)->where('subscription_status', config('global.gym_status_completed'))->count();
            } else {
                $st_count['pending'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.booking_status_waiting_for_confirmation'))->count();
                $st_count['confirmed'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.booking_status_booking_confirmed'))->count();
                $st_count['reserved'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.booking_status_reserved'))->count();
                $st_count['completed'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.booking_status_completed'))->count();
                $st_count['rejected'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.booking_status_rejected'))->count();
                // $st_count['cancelled'] = ReservationBooking::where('vendor_id', $vendor_id)->where('status', config('global.reservation_status_cancelled'))->count();
            }
        } elseif ($user_tye_id == AccountType::SERVICE_PROVIDERS) {
            $st_count['pending'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_status_pending'))->count();
            $st_count['quote_added'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_quote_added'))->count();
            $st_count['quote_accepted'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_quote_accepted'))->count();
            $st_count['quote_rejected'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_quote_rejected'))->count();
            $st_count['service_rejected'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_status_rejected'))->count();
            $st_count['on_the_way'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_on_the_way'))->count();
            $st_count['work_started'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_work_started'))->count();
            $st_count['work_completed'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_work_completed'))->count();
            $st_count['payment_completed'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_payment_completed'))->count();
            $st_count['service_completed'] = ServiceRequest::where('store_id', $vendor_id)->where('status', config('global.service_service_completed'))->count();
        } elseif ($user_tye_id == AccountType::WHOLE_SELLERS) {
            $st_count['pending'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_status_pending'))->count();
            $st_count['accepted'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_quote_added'))->count();
            $st_count['rejected'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_status_rejected'))->count();
            $st_count['quote_accepted'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_quote_accepted'))->count();
            $st_count['quote_rejected'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_quote_rejected'))->count();
            $st_count['payment_completed'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_payment_completed'))->count();
            $st_count['on_the_way'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_on_the_way'))->count();
            $st_count['completed'] = WholeSaleRequests::where('store_id', $vendor_id)->where('request_status', config('global.wholesale_completed'))->count();
        } else {
            if ($activity_type_id == ActivityType::RESTAURANTS || $activity_type_id == ActivityType::RESTAURANT) {
                $st_count['pending'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_pending'))->count();
                $st_count['accepted'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_accepted'))->count();
                $st_count['preparing_order'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_ready_for_delivery'))->count();
                $st_count['dispatched'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_dispatched'))->count();
                $st_count['delivered'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_delivered'))->count();
                $st_count['rejected'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_rejected'))->count();
            } else {
                $st_count['pending'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_pending'))->count();
                $st_count['accepted'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_accepted'))->count();
                $st_count['ready_for_delivery'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_ready_for_delivery'))->count();
                $st_count['dispatched'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_dispatched'))->count();
                $st_count['delivered'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_delivered'))->count();
                $st_count['rejected'] = OrderModel::where('store_id', $vendor_id)->where('status', config('global.order_status_rejected'))->count();
            }
        }
        
        $labels = capitalizeAndRemoveAWordInArray(array_keys($st_count));
        
        return ['data' => $st_count, 'labels' => $labels];
    }
    
    public function getLastNDays($days, $format = 'd/m')
    {
        $m = gmdate("m");
        $de = gmdate("d");
        $y = gmdate("Y");
        $dateArray = array();
        for ($i = 0; $i <= $days - 1; $i++) {
            $dateArray[] =  gmdate($format, mktime(0, 0, 0, $m, ($de - $i), $y));
        }
        return array_reverse($dateArray);
    }

    public function export(Request $request)
    {
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->first();
        $filters = $request->all();
        $filters['doctor_id'] = $doctor->id;
        $exporter = new AppointmentsExport();
        $fileName = $exporter->export($filters);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }

    public function uploadAppointmentDocs(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $loginuserid = Auth::id();
        // $hospital = Hospital::where('user_id',$loginuserid)->first();
        // $hospital_id  = $hospital->id;
        // dd($request->all());
        // Validation rules
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:doctor_patient_appointments,id',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $bookingId = $request->booking_id ?? null;
            
            // Generate a random booking ID for new appointments
            // $FourDigitRandomNumber = rand(1231, 7879);

            if ($bookingId) {
                // Update existing appointment
                $appointment = DoctorPatientAppointment::find($bookingId);
                
                if (!$appointment) {
                    return response()->json(['status' => '0', 'message' => 'Appointment not found', 'errors' => ['id' => 'Appointment not found']]);
                }
                if ($request->hasFile('lab_report')) {
                    foreach ($request->file('lab_report') as $file) {
                        $mini = new MiniRequest();
                        $mini->files->set('lab_report', $file);
                
                        $res = image_upload($mini, config('global.appointment'), 'lab_report');
                        if ($res['status']) {
                            $doc=new AppointmentDoc;
                            $doc->docment = $res['link'];
                            $doc->appointment_id = $bookingId;
                            $doc->type = 'lab_test';
                            $doc->save();
                        }
                    }
                    exec("php " . base_path() . "/artisan app:send-lab-result-notification " . $bookingId . " > /dev/null 2>&1 & ");
                }

                if ($request->hasFile('xray')) {

                    foreach ($request->file('xray') as $file) {
                        $mini = new MiniRequest();
                        $mini->files->set('xray', $file);
                
                        $res = image_upload($mini, config('global.appointment'), 'xray');
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
               
                $appointment->save();

                activity_log("reports_added", "New Reports added for $appointment->booking_id", [
                'appointment_id' => $appointment->booking_id
            ]);
                $message = "Documents uploaded successfully";
            }
                
            
            
            
            
          //  $this->addAppointmentHistory($appointment->id, $appointment->booking_status, auth()->user()->id);
         //   exec("php " . base_path() . "/artisan app:send-notitications-patient " . $appointment->id . " > /dev/null 2>&1 & ");
            $status = "1";
            $o_data['redirect'] = route('callcenter.hospitalAppointments');
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }
    public function deleteDocs(Request $request,$id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $loginuserid = Auth::id();
        // $hospital = Hospital::where('user_id',$loginuserid)->first();
        // $hospital_id  = $hospital->id;
        // dd($request->all());
        // Validation rules
        $validator = Validator::make($request->all(), [
           // 'booking_id' => 'required|exists:doctor_patient_appointments,id',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $bookingId = $id ?? null;
            
            // Generate a random booking ID for new appointments
            // $FourDigitRandomNumber = rand(1231, 7879);

            if ($bookingId) {
                // Update existing appointment
                $appointment = AppointmentDoc::find($id);
                
                if (!$appointment) {
                    return response()->json(['status' => '0', 'message' => 'Docment not found', 'errors' => ['id' => 'Appointment not found']]);
                }
               
                $appointment->delete();
                $message = "Document Delete Successfully";
            }
                
            
            
            
            
          //  $this->addAppointmentHistory($appointment->id, $appointment->booking_status, auth()->user()->id);
         //   exec("php " . base_path() . "/artisan app:send-notitications-patient " . $appointment->id . " > /dev/null 2>&1 & ");
            $status = "1";
            $o_data['redirect'] = route('callcenter.hospitalAppointments');
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    public function Prescriptionstore(Request $request)
{
    DB::transaction(function () use ($request) {
        $prescription = Prescription::updateOrCreate(
            [
                'appointment_id' => $request->appointment_id
            ],
            [
                'language' => $request->language,
                'created_by' => auth()->id(),
            ]
        );

        // Delete old details (edit case)
        $prescription->details()->delete();

        foreach ($request->medicine_id as $index => $medicineId) {
            PrescriptionDetail::create([
                'prescription_id' => $prescription->id,
                'medicine_id'     => $medicineId,
                'direction_id'    => $request->direction_id[$index] ?? null,
                'frquency_id'     => $request->frquency_id[$index] ?? null,
                'duration_id'     => $request->duration_id[$index] ?? null,
                'duration_value'     => $request->duration_value[$index] ?? null,
                'dosage_value'     => $request->dosage_value[$index] ?? null,
                'dosage_id'     => $request->dosage_id[$index] ?? null,
                'quantity'        => $request->quantity[$index] ?? null,
                'instructions'    => $request->instructions[$index] ?? null,
                'created_by'      => auth()->id(),
            ]);
        }
    });

     $appointment = DoctorPatientAppointment::where('id', $request->appointment_id)
        ->first();

    activity_log("prescription_created", "Prescription Created For $appointment->booking_id", [
                'appointment_id' => $appointment->booking_id
            ]);

    exec("php " . base_path() . "/artisan app:send-prescription-notification " . $request->appointment_id . " > /dev/null 2>&1 & ");

    return response()->json([
        'status' => 1,
        'message' => 'Prescription saved successfully'
    ]);
}

public function generatePdf(Request $request)
{
    $request->validate([
        'appointment_id' => 'required|exists:doctor_patient_appointments,id'
    ]);

    // Get appointment
    $appointment = DoctorPatientAppointment::findOrFail($request->appointment_id);
   
    // Get prescription (SINGLE record)
    $prescription = Prescription::where('appointment_id', $request->appointment_id)
        ->with([
            'details.medicine',
            'details.direction',
            'details.frequency',
            'details.duration',
            'details.dosage'
        ])
        ->firstOrFail();
    if ($request->has('language')) {
        $prescription->language = $request->language;
        $prescription->save();
    }
    
    if (!$prescription->language) {
        $prescription->language = 'en';
        $prescription->save();
    }
       

    // Render Blade
    $html = view('prescriptions.pdf', compact('prescription', 'appointment'))->render();

    /*
    |--------------------------------------------------------------------------
    | mPDF Configuration
    |--------------------------------------------------------------------------
    */

    $defaultConfig = (new ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'autoScriptToLang' => true,
        'autoLangToFont'   => true,
        'fontDir' => array_merge($fontDirs, [
            storage_path('fonts'),
        ]),
        'fontdata' => $fontData + [
            'notosansbengali' => [
                'R' => 'NotoSansBengali-Regular.ttf',
            ],
            'notosansarabic' => [
                'R' => 'NotoSansArabic-Regular.ttf',
            ],
        ],
        'default_font' => 'dejavusans'
    ]);
    
    // RTL Support
    if ($prescription->language === 'ar') {
        $mpdf->SetDirectionality('rtl');
    }

    $mpdf->WriteHTML($html);

    $fileName = 'Prescription_' . $prescription->id . '.pdf';

    return response(
        $mpdf->Output($fileName, \Mpdf\Output\Destination::STRING_RETURN),
        200
    )
    ->header('Content-Type', 'application/pdf')
    ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"');
}

public function printPdf(Request $request)
{
    $request->validate([
        'appointment_id' => 'required|exists:doctor_patient_appointments,id'
    ]);

    $appointment = DoctorPatientAppointment::findOrFail($request->appointment_id);

    $prescription = Prescription::where('appointment_id', $request->appointment_id)
        ->with(['details.medicine','details.direction','details.frequency','details.duration','details.dosage'])
        ->firstOrFail();
         if ($request->has('language')) {
            $prescription->language = $request->language;
            $prescription->save();
        }

    // RETURN HTML VIEW (NOT PDF)
    return view('prescriptions.print', compact('prescription', 'appointment'));
}


public function Summarystore(Request $request)
{
    $request->validate([
        'appointment_id' => 'required|integer',
        'summary'        => 'nullable|string',
        'follow_up'      => 'nullable|string',
    ]);
    
    $summary = ClinicalSummary::updateOrCreate(
        [
            'id' => $request->summary_id // null = create, value = update
        ],
        [
            'appointment_id'   => $request->appointment_id,
            'summary'          => $request->summary,
            'follow_up'        => $request->follow_up,
            'last_updated_by'  => auth()->id(),
            'created_by'       => auth()->id(),
        ]
    );

     $appointment = DoctorPatientAppointment::where('id', $request->appointment_id)
        ->first();

     activity_log("clinical_summary", "Clinical summary saved $appointment->booking_id", [
                'appointment_id' => $appointment->booking_id
            ]);

    return response()->json([
        'status'  => 1,
        'message' => 'Clinical summary saved successfully',
        'data'    => $summary
    ]);
}

public function storeReferral(Request $request)
{
    $request->validate([
        'referral_detail_id' => 'nullable|integer',
        'appointment_id'    => 'required|integer',
        'refferal_id'       => 'required|integer',
        'department_id'     => 'required|integer',
        'doctor_id'         => 'nullable|integer',
        'reason'            => 'nullable|string',
        'summery'           => 'nullable|string',
        'reason_for_second_opinion' => 'nullable|string',
    ]);

    $detail = ReferralDetail::updateOrCreate(
        ['id' => $request->referral_detail_id],
        [
            'refferal_id' => $request->refferal_id,
            'department_id' => $request->department_id,
            'appointment_id' => $request->appointment_id,
            'doctor_id' => $request->doctor_id,
            'reason' => $request->reason,
            'summery' => $request->summery,
            'reason_for_second_opinion' => $request->reason_for_second_opinion,
            'created_by' => auth()->id(),
            'last_updated_by' => auth()->id(),
        ]
    );

    $appointment = DoctorPatientAppointment::where('id', $request->appointment_id)
        ->first();

     activity_log("referral_details_stored", "Referral details saved For  $appointment->booking_id", [
                'appointment_id' => $appointment->booking_id
            ]);

    return response()->json([
        'status' => 1,
        'message' => 'Referral details saved successfully',
        'data' => $detail
    ]);
}
public function departmentsByReferral(Request $request)
{
    return DepartmentModel::orderby('id', 'desc')->get();
}

public function doctorsByDepartment_bk(Request $request)
{
    return RefferalDoctor::where('department_id', $request->department_id)
    ->where('referral_id', $request->referral_id)
        ->get();
}

public function doctorsByDepartment(Request $request)
{
    return Doctor::whereHas('departments', function ($q) use ($request) {
            $q->where('departments.id', $request->department_id);
        })
        ->with('user')
        ->get();
}






    public function clinicalAssessmentStore(Request $request)
    {
        $request->validate([
            'appointment_id'   => 'required',
            'symptoms'         => 'nullable|string',
            'present_illness'  => 'nullable|string',
            'past_history'     => 'nullable|string',
        ]);

        $data = [
            'appointment_id'  => $request->appointment_id,
            'symptoms'        => $request->symptoms,
            'present_illness' => $request->present_illness,
            'past_history'    => $request->past_history,
        ];

         $appointment = DoctorPatientAppointment::where('id', $request->appointment_id)
        ->first();

        // EDIT CASE
        if ($request->clinical_assessment_id) {
            $assessment = ClinicalAssessmentAndDocumentation::findOrFail($request->clinical_assessment_id);
            $data['last_updated_by'] = auth()->id();
            $assessment->update($data);
             activity_log('clinical_assesment_and_doumentation ', "Clinical Assesment AND Doumentation Updated For $appointment->booking_id", [
                'appointment_id' => $appointment->booking_id
            ]);
        } 
        // CREATE CASE
        else {
            $data['created_by'] = auth()->id();
            ClinicalAssessmentAndDocumentation::create($data);
             activity_log('clinical_assesment_and_doumentation ', "Clinical Assesment AND Doumentation Added $appointment->booking_id", [
                'appointment_id' => $appointment->booking_id
            ]);
        }
       
        return response()->json([
            'status'  => 1,
            'message' => 'Clinical assessment saved successfully'
        ]);
    }




    public function booking_history(Request $request, $id){
        

       
        $appointments = DoctorPatientAppointment::where('user_id', $id)
        ->where('booking_status','Completed')
        ->with('followups','docs')->orderBy('booking_date','desc')->get();
        $hospital = null;
        $spc_hospital_id = null;
        $clinic = null;
        $doctor = null;
        $patient = null;
        $module_heading = 'Past History';
        $page_heading = 'Past History';

    
        // dd($appointment->latestStatus->changedBy);
        $time_slot = TIME_SLOTS;
        

    foreach($appointments as $appointment ){
    $appointment->clinic_summary = ClinicalSummary::where('appointment_id', $appointment->id)
    ->first();
    
    }
        return view('doctor.history',compact('id','appointments', 'module_heading',
         'doctor', 'clinic', 'hospital', 'time_slot', 'spc_hospital_id', 'patient',
         ));
    }


}
