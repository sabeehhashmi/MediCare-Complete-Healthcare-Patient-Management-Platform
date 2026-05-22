<?php

namespace App\Http\Controllers\callcenter;

use App\Models\OrderModel;
use App\Models\AccountType;
use App\Models\BookingType;
use App\Models\VendorModel;
use App\Models\ActivityType;
use App\Models\Doctor;
use App\Models\AgentUserDetail;
use App\Models\CallCenterUserDetail;
use App\Models\Hospital;
use App\Models\DepartmentModel;
use App\Models\DoctorPatientAppointment;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Validator;
use App\Models\ServiceRequest;
use App\Models\GymSubscription;
use App\Models\WholeSaleRequests;
use App\Models\OrderProductsModel;
use App\Models\ReservationBooking;
use App\Models\Appointments;
use App\Models\User;
use App\Models\Members;
use App\Models\AppointmentDoc;
use App\Models\DoctorAppointmentsStatus;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\AppointmentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DataTables;
use App\Models\Prescription;
use App\Models\Medicin;
use App\Models\Direction;
use App\Models\Frequency;
use App\Models\Duration;
use App\Models\Dosage;
use App\Models\ReferralDetail;
use App\Models\Referral;
use App\Models\ClinicalSummary;
use App\Models\RefferalDoctor;
use App\Models\PrescriptionDetail;
use App\Models\ClinicalAssessmentAndDocumentation;
use Illuminate\Http\Request as MiniRequest;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Output\Destination;


class HospitalAppointmenstsController extends Controller
{
    protected $order_detail_route;

    public function index(Request $request) {
        
        $page_heading = "Hospital Appointments";
        $module_heading = "Total Appointments";
        $user_id = Auth::user()->id;
        $callcenter =  CallCenterUserDetail::where('user_id',$user_id)->first();
        // $hospitalId = $hospital->id;

        // Get doctors and their associated user data
        $doctors = Doctor::with('user')
        ->whereHas('user', function ($q) {
            $q->where('active', 1);
        })
        ->orderByRaw('(SELECT first_name FROM users WHERE users.id = doctors.user_id) ASC')
        ->get();

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

        // $doctorIds = $doctors->pluck('id');

        // Get departments
        $hospitals = Hospital::with('user')->whereHas('user',function($q){
            $q->where('active', 1);
        })->get();

        $departments = DepartmentModel::where(['status'=>1])->orderBy('title','asc')->get();
        $totalDepartments = $departments->count();

        $whereClauses = [];
        $whereClausesCalender = [];

        $appointmentsQuery = DoctorPatientAppointment::with(['doctor.user', 'user', 'status_history']);

        if ($request->filled('doctor_id')) {
            $whereClauses[] = ['doctor_id', $request->doctor_id];
            $whereClausesCalender[] = ['doctor_patient_appointments.doctor_id', $request->doctor_id];
        }

        if ($request->filled('clinic_id')) {
            $whereClauses[] = ['hospital_id', $request->clinic_id];
            $whereClausesCalender[] = ['doctor_patient_appointments.hospital_id', $request->clinic_id];
        }

        if ($request->filled('booking_status')) {
            $whereClauses[] = ['booking_status',  $request->booking_status];
            $whereClausesCalender[] = ['doctor_patient_appointments.booking_status', $request->booking_status];
        }
         
        if ($request->filled('booking_type')) {
            $whereClauses[] = ['booking_type',  $request->booking_type];
            $whereClausesCalender[] = ['doctor_patient_appointments.booking_type', $request->booking_type];
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
       
       if ($request->filled('patient_id')) {

                $userIds = User::where('patient_id', $request->patient_id)
                    ->pluck('id');

                if ($userIds->isNotEmpty()) {
                    $appointmentsQuery->whereIn('user_id', $userIds);
                } else {
                    $appointmentsQuery->whereRaw('1 = 0'); // force no results
                }
            }
      //  $appointmentsQuery->where('callcenter_id',$user_id);
        $appointments = $appointmentsQuery->where($whereClauses)->orderBy('id', 'desc')->paginate(10);

        // $todayDate = Carbon::now()->format('Y-m-d');
        // $tomorrowDate = Carbon::now()->addDay()->format('Y-m-d');
        // $dayAfterTomorrowDate = Carbon::now()->addDays(2)->format('Y-m-d');

        // $todayAppointments = $this->getAppointmentsByDate($todayDate);
        // $tomorrowAppointments = $this->getAppointmentsByDate($tomorrowDate);
        // $dayAfterTomorrowAppointments = $this->getAppointmentsByDate($dayAfterTomorrowDate);
        $restAllAppointments = $this->getSortedAndGroupedAppointments($whereClausesCalender);

        $bookingTypes = BookingType::where('status', 1)->get();
        $booking_type=$request->booking_type??'';
        return view('callcenter.totalappointments', compact(
            'page_heading', 'module_heading',
            'restAllAppointments',
            'doctors',
            'patients',
            'departments',
            'hospitals',
            'totalDepartments',
            'appointments',
            'bookingTypes',
            'booking_type'
        ));
    }

    public function toggleUrgent(Request $request)
    {
        $status = "0";
        $message = "";
        
        try {
            $appointment = DoctorPatientAppointment::find($request->appointment_id);
            if ($appointment) {
                $appointment->is_urgent = $request->is_urgent;
                $appointment->save();
                
                DoctorAppointmentsStatus::create([
                    'appointment_id' => $appointment->id,
                    'status' => $request->is_urgent ? 'Marked as URGENT' : 'URGENT flag removed',
                    'changed_by' => Auth::id(),
                    'changed_at' => Carbon::now()
                ]);
                exec("php " . base_path() . "/artisan app:admin-urgent-notification " . $appointment->id . " > /dev/null 2>&1 & ");
                
                $status = "1";
                $message = $request->is_urgent ? "Appointment marked as urgent" : "Urgent flag removed";
            } else {
                $message = "Appointment not found";
            }
        } catch (\Exception $e) {
            $message = "Something went wrong";
        }
        
        return response()->json(['status' => $status, 'message' => $message]);
    }

    private function getAppointmentsByDate($date) {
        return Doctor::whereHas('doctor_patient_appointments', function($query) use ($date) {
                $query->where('doctor_patient_appointments.booking_date', $date)
                      ->whereNull('deleted_at');
            })
            ->with(['doctor_patient_appointments' => function ($query) use ($date) {
                $query->where('doctor_patient_appointments.booking_date', $date)
                      ->whereNull('deleted_at');
            }])
            ->with('user')
            // ->where('hospital_id', $hospital_id)
            ->get();
    }

    private function getAppointmentsAboveDate($where) {
        return Doctor::whereHas('doctor_patient_appointments', function($query) use ($where) {
                $query->whereNull('deleted_at')->where($where);
            })
            ->with(['doctor_patient_appointments' => function ($query) use ($where) {
                $query->whereNull('deleted_at')->where($where);
            }])
            ->with('user')
            // ->where('hospital_id', $hospital_id)
            ->get();
    }

    public function getSortedAndGroupedAppointments($where)
    {
        $restAllAppointments = [];
        $allAppointments = $this->getAppointmentsAboveDate($where);
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


    public function reportsappointmentLoadData(REQUEST $request){


     // dd($request->agent_id);
        $user_id = Auth::user()->id;
        $callcenter =  CallCenterUserDetail::where('user_id',$user_id)->first();
        $users = DoctorPatientAppointment::with([ 'user','agent.user'])
     //  ->where('doctor_patient_appointments.callcenter_id','=',$callcenter->id)
        ->orderBy('doctor_patient_appointments.id','desc');



           if ($request->from_date) {
            $from_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
            $users->where('doctor_patient_appointments.booking_date', '>=', $from_date);
           }

            if ($request->to_date) {
                $to_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
                $users->where('doctor_patient_appointments.booking_date', '<=', $to_date);
            }
            if ($request->booking_status) {
                $users->whereRaw('LOWER(doctor_patient_appointments.booking_status) = ?', [strtolower($request->booking_status)]);
            }
    if ($request->agent_id) {
        $users->where('doctor_patient_appointments.agent_id',$request->agent_id);
    }

        return DataTables::eloquent($users)
        ->addColumn('action', function($user) {

            $action ='<a class="btn btn-sm btn-icon btn-secondary text-white" href="'.route('callcenter.appointmentdetail', ['id' => $user->id]).'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" width="24" height="24" viewBox="0 0 24 24" class="eva eva-eye-outline icon nav-icon"><g data-name="Layer 2"><g data-name="eye"><rect width="24" height="24" opacity="0"></rect><path d="M21.87 11.5c-.64-1.11-4.16-6.68-10.14-6.5-5.53.14-8.73 5-9.6 6.5a1 1 0 0 0 0 1c.63 1.09 4 6.5 9.89 6.5h.25c5.53-.14 8.74-5 9.6-6.5a1 1 0 0 0 0-1zM12.22 17c-4.31.1-7.12-3.59-8-5 1-1.61 3.61-4.9 7.61-5 4.29-.11 7.11 3.59 8 5-1.03 1.61-3.61 4.9-7.61 5z"></path><path d="M12 8.5a3.5 3.5 0 1 0 3.5 3.5A3.5 3.5 0 0 0 12 8.5zm0 5a1.5 1.5 0 1 1 1.5-1.5 1.5 1.5 0 0 1-1.5 1.5z"></path></g></g></svg>
                          </a>';

            return $action;
        })
        ->addColumn('sl_no', function($user) {
            static $index = 0;
            return ++$index;
        })
        ->toJson();


    }

    public function appointmentLoadData(REQUEST $request){

        $user_id = Auth::user()->id;

        // dd($user_id);
        $callcenter =  CallCenterUserDetail::where('user_id',$user_id)->first();
        $users = DoctorPatientAppointment::with([ 'user','doctor.user','agent.user'])
       // ->where('doctor_patient_appointments.callcenter_id','=',$callcenter->id)
        ->orderBy('doctor_patient_appointments.id','desc');
        if ($request->from_date) {
            $from_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
            $users->where('doctor_patient_appointments.booking_date', '>=', $from_date);
           }

            if ($request->to_date) {
                $to_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
                $users->where('doctor_patient_appointments.booking_date', '<=', $to_date);
            }
            if ($request->booking_status) {
                $users->whereRaw('LOWER(doctor_patient_appointments.booking_status) = ?', [strtolower($request->booking_status)]);
            }
            if ($request->agent_id) {
                $users->where('doctor_patient_appointments.agent_id',$request->agent_id);
            }

            // dd($user->doctor->user_id);

        return DataTables::eloquent($users)
        ->addColumn('action', function($user) {

             $action = '<div class="dropdown mt-4 mt-sm-0">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu">';

             if((strtolower($user->booking_status) === 'cancelled') || (strtolower($user->booking_status) === 'completed')){
                    $action .= '<a class="dropdown-item complete-link" href="'.route('callcenter.appointmentdetail', ['id' => $user->id]).'">View Appointment</a>';
            }else{
                    $action .= '<a class="dropdown-item complete-link" href="'.route('callcenter.appointmentdetail', ['id' => $user->id]).'">View Appointment</a>';
                if(strtolower($user->booking_status) !== 'cancelled'){
                    $action .= '<a class="dropdown-item cancel-link" href="#!" data-bs-toggle="modal" onclick="passDataToCancelModel(\'' . $user->booking_id . '\', \'' . $user->id . '\')" data-bs-target="#cancel-appointment">Cancel Appointment</a>';
                 }
                 if(strtolower($user->booking_status) !== 'confirmed'){
                    $action.='<a class="dropdown-item accept-link" href="#!" data-bs-toggle="modal" onclick="passDataToConfirmModel(\'' . $user->booking_id . '\', \'' . $user->id . '\')" data-bs-target="#confirm-appointment">Confirm Appointment</a>';
                 }
                // if(strtolower($user->booking_status) !== 'rescheduled'){
                // $action.='<a class="dropdown-item reschdule-link" href="#!" data-bs-toggle="modal" onclick="passDataToRescheduleModel(\'' . $user->booking_id . '\', \'' . $user->doctor->user_id . '\',\'' . $user->booking_time_slot . '\',\'' . $user->booking_date . '\')"
                // data-bs-target="#reschedule-modal">Reschedule Appointment</a>';
                // }

                  if (strtolower($user->booking_status) !== 'rescheduled') {
                $doctor_user_id = $user->doctor ? ($user->doctor->user ? $user->doctor->user_id: null) : null;
                $action .= '<a class="dropdown-item reschdule-link" href="#!" data-bs-toggle="modal" onclick="passDataToRescheduleModel(\'' . $user->booking_id . '\', \'' . $doctor_user_id . '\', \'' . $user->booking_time_slot . '\', \'' . $user->booking_date . '\')" data-bs-target="#reschedule-modal">Reschedule Appointment</a>';
            }

           
                $doctor_user_id = $user->doctor ? ($user->doctor->user ? $user->doctor->user_id: null) : null;
                $action .= '<a class="dropdown-item reschdule-link" href="#!" data-bs-toggle="modal" onclick="passDataToRescheduleModel(\'' . $user->booking_id . '\', \'' . $doctor_user_id . '\', \'' . $user->booking_time_slot . '\', \'' . $user->booking_date . '\')" data-bs-target="#reschedule-modal">Upload Documents</a>';
            


                 if (strtolower($user->booking_status) !== 'completed')
                 {
                    $action.='<a class="dropdown-item followup-link" href="#!" data-bs-toggle="modal" onclick="passDataToCompletedModel(\'' . $user->booking_id . '\', \'' . $user->id . '\')" data-bs-target="#completed-appointment">Complete Appointment</a>';
                 }
                }







           $action .='</div>
            </div>';

            return $action;
        })
        ->addColumn('sl_no', function($user) {
            static $index = 0;
            return ++$index;
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
        return view('callcenter.createAppointment', compact(
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
        // $hospital = Hospital::where('user_id',$loginuserid)->first();
        // $hospital_id  = $hospital->id;
        // Validation rules
        $validator = Validator::make($request->all(), [
            'doctor' => 'required|exists:users,id',
            'hospital' => 'required|exists:hospitals,id',
            'department' => 'nullable|exists:departments,id',
            'patient' => 'required|numeric',
            'booking_time_slot' => 'required',
            'booking_date' => 'required|date_format:d-m-Y',
            'member' => 'nullable|exists:members,id',
            //'is_urgent' => 'boolean'
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
                $doctor = DoctorPatientAppointment::find($bookingId);

                if (!$doctor) {
                    return response()->json(['status' => '0', 'message' => 'Appointment not found', 'errors' => ['id' => 'Appointment not found']]);
                }

                $message = "Appointment Updated Successfully";
            } else {
                // Create a new appointment
                $doctor = new DoctorPatientAppointment();
                $doctor->booking_id = '#MEDN' . $FourDigitRandomNumber;
                $doctor->member_id = '0';
                $doctor->callcenter_id = $loginuserid;
                $doctor->created_at = gmdate('Y-m-d H:i:s');
                $message = "Appointment Booked Successfully";
            }
            $doctor_data = Doctor::where('user_id', $request->doctor)->first();
             $consultation_fee = $doctor_data->user->consultation_fee ?? 0;
            $commission = $this->calculateCommission($consultation_fee);
            
            // Generate payment token for ALL appointments
            $payment_token = \Illuminate\Support\Str::random(64);
            // Common fields for both add and update
            $doctor->doctor_id = $doctor_data->id;
            $doctor->department_id = $request->department ?? null;
            $doctor->hospital_id = $request->hospital;
            $doctor->user_id = $request->patient;
            $doctor->booking_type = $request->bookTypeSelect;
            $doctor->booking_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->booking_date)->format('Y-m-d');
            $doctor->booking_time_slot = $request->booking_time_slot;
            $doctor->booking_status = DoctorPatientAppointment::PAYMENT_STATUS_PENDING;
            $doctor->is_urgent =  false;
            $doctor->consultation_fee = $consultation_fee;
            $doctor->admin_commission = $commission['admin_commission'];
            $doctor->doctor_earning = $commission['doctor_earning'];
            $doctor->payment_token = $payment_token;
            $doctor->payment_status = DoctorPatientAppointment::PAYMENT_STATUS_PENDING;
            $doctor->updated_at = gmdate('Y-m-d H:i:s');

            // Update member_id if provided
            if ($request->has('member')) {
                $doctor->member_id = $request->member;
            }
            $doctor->created_by = Auth::User()->id;
            // TO DO : need to change this logic
            $member = Members::find($request->patient);
            $patient = User::where('role', USER_ROLE)->where('id', $request->patient)->first();
            if($member && !$patient){
                $doctor->user_id = $member->user_id;
                $doctor->member_id = $member->id;
            }
            
            $doctor->save();

             DoctorAppointmentsStatus::create([
                'appointment_id' => $doctor->id,
                'status' => 'Created - Payment Required' . ($request->is_urgent ? ' (URGENT)' : ''),
                'changed_by' => Auth::id(),
                'changed_at' => Carbon::now()
            ]);

            $payment_url = route('front.appointment-payment', ['token' => $payment_token]);
             activity_log('appointment_created', 'Appointment created', [
                'appointment_id' => $doctor->booking_id
            ]);
            exec("php " . base_path() . "/artisan app:send-payment-email " . $doctor->id . " " . base64_encode($payment_url) . " > /dev/null 2>&1 & ");
            // EXTRA: If urgent, also notify admin
           

            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
            $status = "1";
            $o_data['redirect'] = route('callcenter.hospitalAppointments');
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    public function create_dr_appointment($dr_id, $id = null)
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
        return view('callcenter.createAppointment', compact(
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

    public function saveDrAppointment(Request $request)
    {

        // dd($request->all());

        if($request->doctor){
             $Doctor = Doctor::where('user_id', $request->doctor)->first();
             $hospital = Hospital::where('id',$Doctor->hospital_id)->first();
        }else{

            $hospital = Hospital::first();
        }

        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $loginuserid = Auth::id();



        $hospital_id  = $hospital->id;
        // Validation rules
        $validator = Validator::make($request->all(), [
            'doctor' => 'required|exists:users,id',
            // 'hospital' => 'required|exists:hospitals,id',
            // 'department' => 'nullable|exists:departments,id',
            'patient' => 'required|exists:users,id',
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
                $doctor = DoctorPatientAppointment::find($bookingId);

                if (!$doctor) {
                    return response()->json(['status' => '0', 'message' => 'Appointment not found', 'errors' => ['id' => 'Appointment not found']]);
                }

                $message = "Appointment Updated Successfully";
            } else {
                // Create a new appointment
                $doctor = new DoctorPatientAppointment();
                $doctor->booking_id = '#MEDN' . $FourDigitRandomNumber;
                $doctor->member_id = '0';
                $doctor->created_at = gmdate('Y-m-d H:i:s');
                $message = "Appointment Booked Successfully";
            }
            $doctor_data = Doctor::where('user_id', $request->doctor)->first();
            // Common fields for both add and update
            $doctor->doctor_id = $doctor_data->id;
            $doctor->department_id = $doctor_data->department_id ?? null;
            $doctor->hospital_id = $hospital_id;
            $doctor->user_id = $request->patient;
            $doctor->booking_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->booking_date)->format('Y-m-d');
            $doctor->booking_time_slot = $request->booking_time_slot;
            $doctor->booking_status = 'Pending';
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->created_by = Auth::User()->id;

            // Update member_id if provided
            if ($request->has('member')) {
                $doctor->member_id = $request->member;
            }

            $doctor->save();

            $status = "1";
            $o_data['redirect'] = route('callcenter.appointments', $doctor->doctor_id);
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
        // $hospital = Hospital::where('user_id',$loginuserid)->first();
        // $hospital_id  = $hospital->id;
        // dd($request->all());
        // Validation rules
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:users,id',
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
            // $appointment->doctor_id = $appointment->doctor_id;
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
            $o_data['redirect'] = route('callcenter.hospitalAppointments');
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
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

    public function addAppointmentHistory($appointmentId, $status, $changedBy)
    {
        return DoctorAppointmentsStatus::create([
            'appointment_id' => $appointmentId,
            'status' => $status,
            'changed_by' => $changedBy,
            'changed_at' => now(),
        ]);
    }

    public function delete_appointment(Request $request, $id)
    {
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->first();
        $status = "0";
        $message = "";

        try {
            $id = decrypt($id);
        } catch (Exception $e) {
            return response()->json([
                'status' => $status,
                'message' => 'Invalid ID format'
            ]);
        }

        $row = DoctorPatientAppointment::find($id);
        if ($row && $row->hospital_id == $hospital->id) {
            $row->delete();
            $message = "Appointment deleted successfully";
            $status = "1";
        } else {
            $message = "Invalid Appointment data";
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function reports(Request $request)
    {
        $page_heading = "Reports";
        $module_heading = "Reports";
        $loginUserId = Auth::id();
        // $hospital = Hospital::where('user_id', $loginUserId)->firstOrFail();
        // $hospitalId = $hospital->id;

        // Get doctors and their associated user data
        $doctors = Doctor::with('user')
        ->whereHas('user', function ($q) {
            $q->where('active', 1);
        })
        ->orderByRaw('(SELECT first_name FROM users WHERE users.id = doctors.user_id) ASC')
        ->get();

        $hospitals = Hospital::with('user')->whereHas('user',function($q){
            $q->where('active', 1);
        })->get();

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
        $departments = [];

        $appointmentsQuery = DoctorPatientAppointment::with(['doctor.user', 'user']);

        $whereClauses = [];

        // if($hospitalId){
        //     $whereClauses[] = ['hospital_id', $hospitalId];
        // }
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
        return view('callcenter.reports', compact('page_heading','doctors','departments','appointments', 'hospitals', 'module_heading'));
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
            $medicines   = Medicin::where('status', 1)->orderBy('title')->get();
            $directions  = Direction::where('status', 1)->orderBy('title')->get();
            $frequencies = Frequency::where('status', 1)->orderBy('title')->get();
            $durations   = Duration::where('status', 1)->orderBy('title')->get();
            $dosages   =Dosage::where('status', 1)->orderBy('title')->get();
            $referral_details   =ReferralDetail::where('appointment_id', $appointment->id)
            ->first();
           
            $referrals   =Referral::where('status', 1)->orderBy('title')->get();
        return view('callcenter.appointment_detail',compact('id','appointment', 'module_heading', 'doctor',
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
        $filters = $request->all();
        $exporter = new AppointmentsExport();
        // dd($filters);
        $fileName = $exporter->export($filters);

        return response()->download($fileName)->deleteFileAfterSend(true);
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
        $prescription->language=$request->language;
        $prescription->save();
       

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

public function doctorsByDepartment(Request $request)
{
    return RefferalDoctor::where('department_id', $request->department_id)
    ->where('referral_id', $request->referral_id)
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

        // EDIT CASE
        if ($request->clinical_assessment_id) {
            $assessment = ClinicalAssessmentAndDocumentation::findOrFail($request->clinical_assessment_id);
            $data['last_updated_by'] = auth()->id();
            $assessment->update($data);
        } 
        // CREATE CASE
        else {
            $data['created_by'] = auth()->id();
            ClinicalAssessmentAndDocumentation::create($data);
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
        return view('callcenter.history',compact('id','appointments', 'module_heading',
         'doctor', 'clinic', 'hospital', 'time_slot', 'spc_hospital_id', 'patient',
         ));
    }
}
