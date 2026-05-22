<?php

namespace App\Http\Controllers\admin;

use App\Models\DepartmentDoctor;
use App\Models\OrderModel;
use App\Models\AccountType;
use App\Models\VendorModel;
use App\Models\ActivityType;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\AppointmentDoc;
use App\Models\DepartmentModel;
use App\Models\DoctorPatientAppointment;
use App\Models\DoctorAppointmentFollowup;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\GymSubscription;
use App\Models\WholeSaleRequests;
use App\Models\OrderProductsModel;
use App\Models\ReservationBooking;
use App\Models\PrescriptionDetail;
use App\Models\Appointments;
use App\Models\User;
use App\Models\Members;
use App\Models\DoctorHolidays;
use App\Models\DoctorAvailability;
use App\Models\DoctorRescheduleAppointment;
use App\Models\DoctorTemporaryUnavailable;
use App\Models\DoctorAppointmentsStatus;
use App\Models\Prescription;
use App\Models\Medicin;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\AppointmentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Direction;
use App\Models\Frequency;
use App\Models\Duration;
use App\Models\Dosage;
use App\Models\ReferralDetail;
use App\Models\Referral;
use App\Models\ClinicalSummary;
use App\Models\BookingType;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Output\Destination;
use Illuminate\Http\Request as MiniRequest;


class AppointmentsController extends Controller
{
    protected $order_detail_route;

    public function index(Request $request) {
       
        if (!get_user_permission('appoitments', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $params = [];
        $page_heading = "Appointments";
        $hospitals = Hospital::whereHas('user', function ($q){
            $q->where('active', 1);
        })
        // ->where('is_contract_signed', 1)
        ->orderBy('name_en', 'asc')->get();
        $patients = User::with('Members')->where('role', USER_ROLE)->where('active', 1)->where('deleted', 0)->get();
        $patientMembers = [];

        foreach ($patients as $patient) {
            // Add the patient to the patientMembers array with a 'type' key
            $patientArray = $patient->toArray();
            $patientArray['type'] = 'patient';
            $patientArray['fullname'] = $patientArray['first_name'] . ' ' . $patientArray['last_name'];
            $patientArray['phone'] = '+'.$patientArray['dial_code'] . ' ' . $patientArray['phone'];
            $patientMembers[] = $patientArray;

            // If the patient has members, loop through them and add to the patientMembers array with a 'type' key
            if ($patient->Members) {
                foreach ($patient->Members as $member) {
                    $memberArray = $member->toArray();
                    $memberArray['type'] = 'member';
                    $memberArray['fullname'] = $memberArray['full_name'];
                    $memberArray['phone'] = '+'.$patient->dial_code. ' ' . $patient->phone;
                    $patientMembers[] = $memberArray;
                }
            }
        }

        usort($patientMembers, function ($a, $b) {
            return strcmp($a['fullname'], $b['fullname']);
        });
        // dd($patientMembers[0]['phone']);
        // Sorting departments by title
        $departments = DepartmentModel::orderBy('title', 'asc')->get();

        if ($request->has('doctor_id')) {
            $doctor_departments = DepartmentDoctor::where('doctor_id', $request->doctor_id)->get();
            $doctor_departments = $doctor_departments->pluck('department_id')->toArray();
            $departments = $departments->whereIn('id', $doctor_departments);
        }
        // Sorting doctors by user name
        $doctors = Doctor::join('users', 'doctors.user_id', '=', 'users.id')
            ->join('hospitals', 'hospitals.id', '=', 'doctors.hospital_id')
            ->with('user')
            ->orderBy('users.name', 'asc')
            ->where('users.active', 1)
            ->where('hospitals.is_contract_signed', 1)  // Use standard where clause
            ->select('doctors.*', 'users.name as user_name')
            ->get();

        $spc_hospital_id = null;
        $hospital = null;
        $patient = null;
        $clinic = null;
        $doctor = null;
        $is_hospital = false;
        $is_clinic = false;

        if ($request->hospital_id) {
            $hospital = Hospital::find($request->hospital_id);
            $spc_hospital_id = $hospital->id;
            $departments = $hospital->departments->sortBy('title');
            $doctors = Doctor::join('users', 'doctors.user_id', '=', 'users.id')->with('user')
            ->where('doctors.hospital_id', $hospital->id)
            ->where('users.active', 1)
            ->orderBy('users.name', 'asc')
            ->select('doctors.*', 'users.name as user_name')
            ->get();
            $page_heading .= '- ' . $hospital->name_en . ' hospital';
            $params['hospital_id'] = $hospital->id;
            $is_hospital = true;
            $is_clinic = false;
        }

        if ($request->clinic_id) {
            $clinic = Hospital::find($request->clinic_id);
            $spc_hospital_id = $clinic->id;
            $departments = [];
            $doctors = Doctor::join('users', 'doctors.user_id', '=', 'users.id')->with('user')
            ->where('doctors.hospital_id', $clinic->id)
            ->where('users.active', 1)
            ->orderBy('users.name', 'asc')
            ->select('doctors.*', 'users.name as user_name')
            ->get();
            $page_heading .= '- ' . $clinic->name_en . ' clinic';
            $params['clinic_id'] = $clinic->id;
            $is_hospital = false;
            $is_clinic = true;
        }

        if ($request->doctor_id) {
            $doctor = Doctor::with(['user', 'hospital'])->find($request->doctor_id);
            if ($doctor->hospital->type == TYPE_HOSPITAL) {
                $is_hospital = true;
                $is_clinic = false;
            }

            if ($doctor->hospital->type == TYPE_CLINIC) {
                $is_hospital = false;
                $is_clinic = true;
            }

            $spc_hospital_id = $doctor->hospital_id;
            $page_heading .= '- DR ' . $doctor->user->name;
            $params['doctor_id'] = $doctor->id;
        }

        if ($request->patient_id) {
            $patient = User::find($request->patient_id);
            $page_heading .= '- ' . $patient->first_name . ' ' . $patient->last_name;
        }

        $time_slot = TIME_SLOTS;
        $bookingTypes = BookingType::where('status', 1)->get();
        
        $booking_type=$request->booking_type??'';
        return view('admin.appointments.index', compact('bookingTypes','booking_type','page_heading', 'hospital', 'is_hospital', 'is_clinic', 'clinic', 'doctor', 'departments', 'doctors', 'hospitals', 'time_slot', 'patients', 'spc_hospital_id', 'patient', 'patientMembers', 'params'));
    }

    public function urgent_appointments(Request $request)
{
    if (!get_user_permission('appoitments', 'r')) {
        return redirect()->route('admin.restricted_page');
    }
    
    $page_heading = "Urgent Appointments";
    
    $hospitals = Hospital::whereHas('user', function ($q) {
        $q->where('active', 1);
    })->orderBy('name_en', 'asc')->get();
    
    $doctors = Doctor::join('users', 'doctors.user_id', '=', 'users.id')
        ->with('user')
        ->orderBy('users.name', 'asc')
        ->where('users.active', 1)
        ->select('doctors.*', 'users.name as user_name')
        ->get();
    
    $bookingTypes = BookingType::where('status', 1)->get();
    
    return view('admin.appointments.urgent_index', compact('page_heading', 'hospitals', 'doctors', 'bookingTypes'));
}

    public function loadUrgentData(Request $request)
    {
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;

        $query = DoctorPatientAppointment::query()
            ->where('is_urgent', true)
            ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
            ->join('users as doctorUsers', 'doctorUsers.id', '=', 'doctors.user_id')
            ->join('users as patients', 'patients.id', '=', 'doctor_patient_appointments.user_id')
            ->leftJoin('members', 'members.id', '=', \DB::raw('CAST(doctor_patient_appointments.member_id AS bigint)'));

        $users = $query->select([
            'doctor_patient_appointments.*',
            'doctorUsers.first_name as doctor_first_name',
            'doctorUsers.last_name as doctor_last_name',
            'patients.first_name as patient_first_name',
            'patients.last_name as patient_last_name',
            'members.full_name as member_full_name'
        ])->orderBy('doctor_patient_appointments.id', 'desc');

        return DataTables::eloquent($users)
            ->addColumn('action', function ($user) {
                return '<div class="dropdown mt-4 mt-sm-0">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-dots-horizontal-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="' . route('admin.appointments.view', ['id' => $user->id]) . '">View Appointment</a>
                        
                    </div>
                </div>';
            })
            ->addColumn('booking_id', function ($item) {
                $html = $item->booking_id;
                
                $booking_types = [
                    'New Consultation'        => 'text-bg-success',
                    'Follow-up Consultation'  => 'text-bg-primary',
                    'Second Opinion'          => 'text-bg-warning',
                    'Online Consultation'     => 'text-bg-info',
                    'Emergency Consultation'  => 'text-bg-danger',
                ];
            
                if (isset($booking_types[$item->booking_type])) {
                    $badgeClass = $booking_types[$item->booking_type];
                    $html .= ' <span class="badge rounded-pill ' . $badgeClass . ' p-1 px-2 font-size-10 fw-normal">' . $item->booking_type . '</span>';
                }
                
                return $html;
            })
            ->addColumn('dr_name', function ($item) {
                return ($item->doctor_first_name ?? '') . ' ' . ($item->doctor_last_name ?? '');
            })
            ->addColumn('patient_name', function ($item) {
                return $item->member_id ? $item->member_full_name : ($item->patient_first_name ?? '') . ' ' . ($item->patient_last_name ?? '');
            })
            ->addColumn('consultation_fee', function ($item) {
                return $item->formatted_consultation_fee ?? '0.00';
            })
            ->addColumn('payment_status', function ($item) {
                // You'll need to implement this based on your payment logic
                $paymentStatus = 'Pending';
                if (isset($item->payment_status) && $item->payment_status == 'paid') {
                    $paymentStatus = 'Paid';
                }
                return $paymentStatus;
            })
            ->rawColumns(['action', 'booking_id'])
            ->toJson();
    }
    
    public function approval_index(Request $request) {
       
        if (!get_user_permission('appoitments', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $params = [];
        $page_heading = "Document Requests";
        $hospitals = Hospital::whereHas('user', function ($q){
            $q->where('active', 1);
        })
        // ->where('is_contract_signed', 1)
        ->orderBy('name_en', 'asc')->get();
        $patients = User::with('Members')->where('role', USER_ROLE)->where('active', 1)->where('deleted', 0)->get();
        $patientMembers = [];

        foreach ($patients as $patient) {
            // Add the patient to the patientMembers array with a 'type' key
            $patientArray = $patient->toArray();
            $patientArray['type'] = 'patient';
            $patientArray['fullname'] = $patientArray['first_name'] . ' ' . $patientArray['last_name'];
            $patientArray['phone'] = '+'.$patientArray['dial_code'] . ' ' . $patientArray['phone'];
            $patientMembers[] = $patientArray;

            // If the patient has members, loop through them and add to the patientMembers array with a 'type' key
            if ($patient->Members) {
                foreach ($patient->Members as $member) {
                    $memberArray = $member->toArray();
                    $memberArray['type'] = 'member';
                    $memberArray['fullname'] = $memberArray['full_name'];
                    $memberArray['phone'] = '+'.$patient->dial_code. ' ' . $patient->phone;
                    $patientMembers[] = $memberArray;
                }
            }
        }

        usort($patientMembers, function ($a, $b) {
            return strcmp($a['fullname'], $b['fullname']);
        });
        // dd($patientMembers[0]['phone']);
        // Sorting departments by title
        $departments = DepartmentModel::orderBy('title', 'asc')->get();

        if ($request->has('doctor_id')) {
            $doctor_departments = DepartmentDoctor::where('doctor_id', $request->doctor_id)->get();
            $doctor_departments = $doctor_departments->pluck('department_id')->toArray();
            $departments = $departments->whereIn('id', $doctor_departments);
        }
        // Sorting doctors by user name
        $doctors = Doctor::join('users', 'doctors.user_id', '=', 'users.id')
            ->join('hospitals', 'hospitals.id', '=', 'doctors.hospital_id')
            ->with('user')
            ->orderBy('users.name', 'asc')
            ->where('users.active', 1)
            ->where('hospitals.is_contract_signed', 1)  // Use standard where clause
            ->select('doctors.*', 'users.name as user_name')
            ->get();

        $spc_hospital_id = null;
        $hospital = null;
        $patient = null;
        $clinic = null;
        $doctor = null;
        $is_hospital = false;
        $is_clinic = false;

        if ($request->hospital_id) {
            $hospital = Hospital::find($request->hospital_id);
            $spc_hospital_id = $hospital->id;
            $departments = $hospital->departments->sortBy('title');
            $doctors = Doctor::join('users', 'doctors.user_id', '=', 'users.id')->with('user')
            ->where('doctors.hospital_id', $hospital->id)
            ->where('users.active', 1)
            ->orderBy('users.name', 'asc')
            ->select('doctors.*', 'users.name as user_name')
            ->get();
            $page_heading .= '- ' . $hospital->name_en . ' hospital';
            $params['hospital_id'] = $hospital->id;
            $is_hospital = true;
            $is_clinic = false;
        }

        if ($request->clinic_id) {
            $clinic = Hospital::find($request->clinic_id);
            $spc_hospital_id = $clinic->id;
            $departments = [];
            $doctors = Doctor::join('users', 'doctors.user_id', '=', 'users.id')->with('user')
            ->where('doctors.hospital_id', $clinic->id)
            ->where('users.active', 1)
            ->orderBy('users.name', 'asc')
            ->select('doctors.*', 'users.name as user_name')
            ->get();
            $page_heading .= '- ' . $clinic->name_en . ' clinic';
            $params['clinic_id'] = $clinic->id;
            $is_hospital = false;
            $is_clinic = true;
        }

        if ($request->doctor_id) {
            $doctor = Doctor::with(['user', 'hospital'])->find($request->doctor_id);
            if ($doctor->hospital->type == TYPE_HOSPITAL) {
                $is_hospital = true;
                $is_clinic = false;
            }

            if ($doctor->hospital->type == TYPE_CLINIC) {
                $is_hospital = false;
                $is_clinic = true;
            }

            $spc_hospital_id = $doctor->hospital_id;
            $page_heading .= '- DR ' . $doctor->user->name;
            $params['doctor_id'] = $doctor->id;
        }

        if ($request->patient_id) {
            $patient = User::find($request->patient_id);
            $page_heading .= '- ' . $patient->first_name . ' ' . $patient->last_name;
        }

        $time_slot = TIME_SLOTS;
        $bookingTypes = BookingType::where('status', 1)->get();
        
        $booking_type=$request->booking_type??'';
        return view('admin.appointments.approval_index', compact('bookingTypes','booking_type','page_heading', 'hospital', 'is_hospital', 'is_clinic', 'clinic', 'doctor', 'departments', 'doctors', 'hospitals', 'time_slot', 'patients', 'spc_hospital_id', 'patient', 'patientMembers', 'params'));
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


    public function save(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        // Validation rules
        $validator = Validator::make($request->all(), [
            'doctor' => 'required|exists:users,id',
            'hospital' => 'required|exists:hospitals,id',
            'department' => [
                'nullable',
                'exists:departments,id',
            ],
            'patient' => 'required|numeric',
            'booking_time_slot' => 'required',
            'bookTypeSelect' => 'required',
            'booking_date' => 'required|date_format:d-m-Y',
            'member' => 'nullable|exists:members,id'
        ]);

        $validator->sometimes('department', 'required', function ($input) {
            return $input->hospital_type && $input->hospital_type !== 'clinic';
        });

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
             $consultation_fee = $doctor_data->user->consultation_fee ?? 0;
            $commission = $this->calculateCommission($consultation_fee);
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

            if($request->patient_type == 'member'){
                $member = Members::find($request->patient);
                $doctor->user_id = $member->user_id;
                $doctor->member_id = $member->id;
            }

            $doctor->updated_at = gmdate('Y-m-d H:i:s');

            // Update member_id if provided
            // if ($request->has('member')) {
            //     $doctor->member_id = $request->member;
            // }
            $doctor->created_by = Auth::User()->id;
            $doctor->save();
            DoctorAppointmentsStatus::create([
                'appointment_id' => $doctor->id,
                'status' => 'Created',
                'changed_by' => Auth::id(),
                'changed_at' => Carbon::now()
            ]);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
             $payment_url = route('front.appointment-payment', ['token' => $payment_token]);
            exec("php " . base_path() . "/artisan app:send-payment-email " . $doctor->id . " " . base64_encode($payment_url) . " > /dev/null 2>&1 & ");
            $status = "1";
            $o_data['redirect'] = route('admin.appointments.index');
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    private function getAppointmentsByDate($doctor_id, $date) {
        return Doctor::whereHas('doctor_patient_appointments', function($query) use ($date) {
                $query->where('doctor_patient_appointments.booking_date', $date)
                      ->whereNull('deleted_at');
            })
            ->with(['doctor_patient_appointments' => function ($query) use ($date) {
                $query->where('doctor_patient_appointments.booking_date', $date)
                      ->whereNull('deleted_at');
            }])
            ->with('user')
            ->where('id', $doctor_id)
            ->get();
    }


    public function loadData(Request $request)
    {
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;

        $query = DoctorPatientAppointment::query()
            ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
            ->join('users as doctorUsers', 'doctorUsers.id', '=', 'doctors.user_id')
            ->join('users as patients', 'patients.id', '=', 'doctor_patient_appointments.user_id')
            ->leftJoin('members', 'members.id', '=', \DB::raw('CAST(doctor_patient_appointments.member_id AS bigint)')); // Type casting
//            ->join('hospitals','hospitals.id','=', 'doctor_patient_appointments.hospital_id');
        //    $query->where('hospitals.is_contract_signed', 1);

        $params = [];

        if ($request->doctor_id) {
            $doctor = Doctor::find($request->doctor_id);
            $query->where('doctor_patient_appointments.doctor_id', $request->doctor_id)->where('doctor_patient_appointments.hospital_id', $doctor->hospital_id);
            $params['doctor_id'] = $request->doctor_id;
        }

        if ($request->hospital_id) {
            $query->where('doctor_patient_appointments.hospital_id', $request->hospital_id);
            $params['hospital_id'] = $request->hospital_id;
        
        
        }if ($request->booking_type) {
            $query->where('doctor_patient_appointments.booking_type', $request->booking_type);
            $params['booking_type'] = $request->booking_type;
        }

        if ($request->clinic_id) {
            $query->where('doctor_patient_appointments.hospital_id', $request->clinic_id); // Corrected field name
            $params['clinic_id'] = $request->clinic_id;
        }

        if ($request->patient_id) {
            $query->where('doctor_patient_appointments.user_id', $request->patient_id);
            $params['patient_id'] = $request->patient_id;
        }

        if ($request->has('search') && ($request->search['filters'] ?? null)) {
            if ($request->search['filters']['booking_from'] ?? null) {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->search['filters']['booking_from'])->format('Y-m-d');
                $query->where('doctor_patient_appointments.booking_date', '>=', $date);
            }

            if ($request->search['filters']['booking_to'] ?? null) {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->search['filters']['booking_to'])->format('Y-m-d');
                $query->where('doctor_patient_appointments.booking_date', '<=', $date);
            }

            if ($request->search['filters']['hospital_id'] ?? null) {
                $query->where('doctor_patient_appointments.hospital_id', $request->search['filters']['hospital_id']);
            }

            if ($request->search['filters']['department_id'] ?? null) {
                $query->where('doctor_patient_appointments.department_id', $request->search['filters']['department_id']);
            }

            if ($request->search['filters']['doctor_id'] ?? null) {
                $query->where('doctor_patient_appointments.doctor_id', $request->search['filters']['doctor_id']);
            }

            if ($request->search['filters']['booking_status'] ?? null) {
                $query->where('doctor_patient_appointments.booking_status', $request->search['filters']['booking_status']);
            }
        }

        $users = $query->select([
            'doctor_patient_appointments.*',
            'doctorUsers.first_name as doctor_first_name',
            'doctorUsers.last_name as doctor_last_name',
            'patients.first_name as patient_first_name',
            'patients.patient_id as patient_id',
            'members.full_name as member_full_name'
        ])->orderBy('doctor_patient_appointments.id', 'desc');

        return DataTables::eloquent($users)
            ->addColumn('action', function ($user) use ($params) {
                $action = '<div class="dropdown mt-4 mt-sm-0">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-dots-horizontal-rounded"></i>
                    </button>
                    <div class="dropdown-menu">';

                if (get_user_permission('appoitments', 'u')) {
                    $action .= '<a class="dropdown-item" href="' . route('admin.appointments.view', array_merge(['id' => $user->id], $params)) . '" >View Appointment</a>';
                }

                if ((strtolower($user->booking_status) === 'pending' || strtolower($user->booking_status) === 'confirmed' || strtolower($user->booking_status) === 'rescheduled') && get_user_permission('appointments', 'u')) {
                    $action .= '<a class="dropdown-item cancel-link" href="#!" data-bs-toggle="modal" data-bs-target="#cancel-appointment" data-booking-id="' . $user->booking_id . '" data-appointment-id="' . $user->id . '">Cancel Appointment</a>';
                }

                if ((strtolower($user->booking_status) === 'pending' || strtolower($user->booking_status) === 'rescheduled') && get_user_permission('appoitments', 'u')) {
                    $action .= '<a class="dropdown-item accept-link" href="#!" data-bs-toggle="modal" data-bs-target="#confirm-appointment" data-booking-id="' . $user->booking_id . '" data-appointment-id="' . $user->id . '">Confirm Appointment</a>';
                }

                if (strtolower($user->booking_status) === 'confirmed' && get_user_permission('appoitments', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="#!" data-bs-toggle="modal" data-bs-target="#completed-appointment" data-booking-id="' . $user->booking_id . '" data-appointment-id="' . $user->id . '">Complete Appointment</a>';
                }

                if ((strtolower($user->booking_status) === 'pending' || strtolower($user->booking_status) === 'confirmed') && get_user_permission('appoitments', 'u')) {
                    $userData = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
                    $doctorId = $user->doctor->user_id ?? '';
                    $action .= '<a class="dropdown-item reschedule-link" href="#!" data-bs-toggle="modal" data-bs-target="#reschedule-modal" data-booking-data="' . $userData . '" data-appointment-doctor_id="' . $doctorId . '">Reschedule Appointment</a>';
                }
                if ((strtolower($user->booking_status) === 'pending' || strtolower($user->booking_status) === 'confirmed') && get_user_permission('appoitments', 'u')) {
                    $userData = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
                    $doctorId = $user->doctor->user_id ?? '';
                    $action .= '<a class="dropdown-item upload-link" href="#!" data-bs-toggle="modal" data-bs-target="#upload-docs" data-booking-id="' . $user->id . '" data-booking-data="' . $userData . '" data-appointment-doctor_id="' . $doctorId . '">Upload Documents</a>';
                }

                $action .= '</div></div>';
                return $action;
            })
            ->addColumn('sl_no', function($user) use (&$startIndex) {
                return ++$startIndex;
            })
            ->addColumn('booking_id', function ($item) {

                $html = $item->booking_id;
            
                $booking_types = [
                    'New Consultation'        => 'text-bg-success',
                    'Follow-up Consultation'  => 'text-bg-primary',
                    'Second Opinion'          => 'text-bg-warning',
                    'Online Consultation'     => 'text-bg-info',
                    'Emergency Consultation'  => 'text-bg-danger',
                ];
            
                if (isset($booking_types[$item->booking_type])) {
            
                    $badgeClass = $booking_types[$item->booking_type];
            
                    $html .= ' 
                        <span class="badge rounded-pill '.$badgeClass.' p-1 px-2 font-size-10 fw-normal">
                            '.$item->booking_type.'
                        </span>';
                }
            
                return $html;
            })
            ->addColumn('dr_name', function ($item) {
                return ($item->doctor_first_name ?? '') . ' ' . ($item->doctor_last_name ?? '');
            })
            ->addColumn('patient_name', function ($item) {
                return $item->member_id ? $item->member_full_name : ($item->patient_first_name ?? '') . ' ' . ($item->patient_last_name ?? '');
            })
            ->addColumn('booking_status', function ($item) {
                $class = 'default-badge';

                switch ($item->booking_status) {
                    case BOOKING_STATUS_PENDING:
                        $class = 'pending-badge';
                        break;
                    case BOOKING_STATUS_COMPLETED:
                        $class = 'completed-badge';
                        break;
                    case BOOKING_STATUS_CANCELLED:
                        $class = 'cancelled-badge';
                        break;
                    case BOOKING_STATUS_CONFIRMED:
                        $class = 'confirmed-badge';
                        break;
                    case BOOKING_STATUS_RESCHEDULED:
                        $class = 'reschedule-badge';
                        break;
                }

                return '<div class="status-badge ' . $class . '">
                    <span></span>' . strtoupper($item->booking_status) . '
                </div>';
            })
            ->addColumn('processed_by', function ($appointment) {
                if ($appointment->status_history->count()) {
                    $history = $appointment->status_history->first();
                    return  !empty($history['changedBy']) ? ($history['changedBy']['name'] != '' ? $history['changedBy']['name'] : 'N/A') : 'N/A';
                } else {
                    return $appointment->created_by_user->name;
                }
            })
            ->addColumn('call_live_status', function ($item) {

                if ($item->is_call_live) {

                    return '
                        <span class="badge bg-success">
                            LIVE
                        </span>
                    ';
                }

                return '
                    <span class="badge bg-secondary">
                        OFFLINE
                    </span>
                ';
            })
            ->rawColumns(['action', 'dr_name', 'patient_name', 'booking_status', 'processed_by','booking_id','call_live_status'])
            ->toJson();
    }
    
    public function loadApprovalData(Request $request)
    {
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;

        $query = DoctorPatientAppointment::query()
            ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
            ->join('users as doctorUsers', 'doctorUsers.id', '=', 'doctors.user_id')
            ->join('users as patients', 'patients.id', '=', 'doctor_patient_appointments.user_id')
            ->leftJoin('members', 'members.id', '=', \DB::raw('CAST(doctor_patient_appointments.member_id AS bigint)')); // Type casting
//            ->join('hospitals','hospitals.id','=', 'doctor_patient_appointments.hospital_id');
            $query->where('doctor_patient_appointments.document_permission', 2);

        $params = [];

        if ($request->doctor_id) {
            $doctor = Doctor::find($request->doctor_id);
            $query->where('doctor_patient_appointments.doctor_id', $request->doctor_id)->where('doctor_patient_appointments.hospital_id', $doctor->hospital_id);
            $params['doctor_id'] = $request->doctor_id;
        }

        if ($request->hospital_id) {
            $query->where('doctor_patient_appointments.hospital_id', $request->hospital_id);
            $params['hospital_id'] = $request->hospital_id;
        
        
        }if ($request->booking_type) {
            $query->where('doctor_patient_appointments.booking_type', $request->booking_type);
            $params['booking_type'] = $request->booking_type;
        }

        if ($request->clinic_id) {
            $query->where('doctor_patient_appointments.hospital_id', $request->clinic_id); // Corrected field name
            $params['clinic_id'] = $request->clinic_id;
        }

        if ($request->patient_id) {
            $query->where('doctor_patient_appointments.user_id', $request->patient_id);
            $params['patient_id'] = $request->patient_id;
        }

        if ($request->has('search') && ($request->search['filters'] ?? null)) {
            if ($request->search['filters']['booking_from'] ?? null) {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->search['filters']['booking_from'])->format('Y-m-d');
                $query->where('doctor_patient_appointments.booking_date', '>=', $date);
            }

            if ($request->search['filters']['booking_to'] ?? null) {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->search['filters']['booking_to'])->format('Y-m-d');
                $query->where('doctor_patient_appointments.booking_date', '<=', $date);
            }

            if ($request->search['filters']['hospital_id'] ?? null) {
                $query->where('doctor_patient_appointments.hospital_id', $request->search['filters']['hospital_id']);
            }

            if ($request->search['filters']['department_id'] ?? null) {
                $query->where('doctor_patient_appointments.department_id', $request->search['filters']['department_id']);
            }

            if ($request->search['filters']['doctor_id'] ?? null) {
                $query->where('doctor_patient_appointments.doctor_id', $request->search['filters']['doctor_id']);
            }

            if ($request->search['filters']['booking_status'] ?? null) {
                $query->where('doctor_patient_appointments.booking_status', $request->search['filters']['booking_status']);
            }
        }

        $users = $query->select([
            'doctor_patient_appointments.*',
            'doctorUsers.first_name as doctor_first_name',
            'doctorUsers.last_name as doctor_last_name',
            'patients.first_name as patient_first_name',
            'members.full_name as member_full_name'
        ])->orderBy('doctor_patient_appointments.id', 'desc');

        return DataTables::eloquent($users)
        ->addColumn('status', function($item) {
                return '<div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                        <input type="checkbox" class="form-check-input change_status" data-id="'.$item->id.'"
                                    data-url="'.url('admin/appointments/change_status').'"
                                    '.( $item->document_permission == 1 ? 'checked' : '').'>
                    </div>';
            })
            ->addColumn('action', function ($user) use ($params) {
                $action = '<div class="dropdown mt-4 mt-sm-0">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-dots-horizontal-rounded"></i>
                    </button>
                    <div class="dropdown-menu">';

                if (get_user_permission('appoitments', 'u')) {
                    $action .= '<a class="dropdown-item" href="' . route('admin.appointments.view', array_merge(['id' => $user->id], $params)) . '" >View Appointment</a>';
                }

                if ((strtolower($user->booking_status) === 'pending' || strtolower($user->booking_status) === 'confirmed' || strtolower($user->booking_status) === 'rescheduled') && get_user_permission('appointments', 'u')) {
                    $action .= '<a class="dropdown-item cancel-link" href="#!" data-bs-toggle="modal" data-bs-target="#cancel-appointment" data-booking-id="' . $user->booking_id . '" data-appointment-id="' . $user->id . '">Cancel Appointment</a>';
                }

                if ((strtolower($user->booking_status) === 'pending' || strtolower($user->booking_status) === 'rescheduled') && get_user_permission('appoitments', 'u')) {
                    $action .= '<a class="dropdown-item accept-link" href="#!" data-bs-toggle="modal" data-bs-target="#confirm-appointment" data-booking-id="' . $user->booking_id . '" data-appointment-id="' . $user->id . '">Confirm Appointment</a>';
                }

                if (strtolower($user->booking_status) === 'confirmed' && get_user_permission('appoitments', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="#!" data-bs-toggle="modal" data-bs-target="#completed-appointment" data-booking-id="' . $user->booking_id . '" data-appointment-id="' . $user->id . '">Complete Appointment</a>';
                }

                if ((strtolower($user->booking_status) === 'pending' || strtolower($user->booking_status) === 'confirmed') && get_user_permission('appoitments', 'u')) {
                    $userData = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
                    $doctorId = $user->doctor->user_id ?? '';
                    $action .= '<a class="dropdown-item reschedule-link" href="#!" data-bs-toggle="modal" data-bs-target="#reschedule-modal" data-booking-data="' . $userData . '" data-appointment-doctor_id="' . $doctorId . '">Reschedule Appointment</a>';
                }
                if ((strtolower($user->booking_status) === 'pending' || strtolower($user->booking_status) === 'confirmed') && get_user_permission('appoitments', 'u')) {
                    $userData = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
                    $doctorId = $user->doctor->user_id ?? '';
                    $action .= '<a class="dropdown-item upload-link" href="#!" data-bs-toggle="modal" data-bs-target="#upload-docs" data-booking-id="' . $user->id . '" data-booking-data="' . $userData . '" data-appointment-doctor_id="' . $doctorId . '">Upload Documents</a>';
                }

                $action .= '</div></div>';
                return $action;
            })
            ->addColumn('sl_no', function($user) use (&$startIndex) {
                return ++$startIndex;
            })
            ->addColumn('booking_id', function ($item) {

                $html = $item->booking_id;
            
                $booking_types = [
                    'New Consultation'        => 'text-bg-success',
                    'Follow-up Consultation'  => 'text-bg-primary',
                    'Second Opinion'          => 'text-bg-warning',
                    'Online Consultation'     => 'text-bg-info',
                    'Emergency Consultation'  => 'text-bg-danger',
                ];
            
                if (isset($booking_types[$item->booking_type])) {
            
                    $badgeClass = $booking_types[$item->booking_type];
            
                    $html .= ' 
                        <span class="badge rounded-pill '.$badgeClass.' p-1 px-2 font-size-10 fw-normal">
                            '.$item->booking_type.'
                        </span>';
                }
            
                return $html;
            })
            ->addColumn('dr_name', function ($item) {
                return ($item->doctor_first_name ?? '') . ' ' . ($item->doctor_last_name ?? '');
            })
            ->addColumn('patient_name', function ($item) {
                return $item->member_id ? $item->member_full_name : ($item->patient_first_name ?? '') . ' ' . ($item->patient_last_name ?? '');
            })
            ->addColumn('booking_status', function ($item) {
                $class = 'default-badge';

                switch ($item->booking_status) {
                    case BOOKING_STATUS_PENDING:
                        $class = 'pending-badge';
                        break;
                    case BOOKING_STATUS_COMPLETED:
                        $class = 'completed-badge';
                        break;
                    case BOOKING_STATUS_CANCELLED:
                        $class = 'cancelled-badge';
                        break;
                    case BOOKING_STATUS_CONFIRMED:
                        $class = 'confirmed-badge';
                        break;
                    case BOOKING_STATUS_RESCHEDULED:
                        $class = 'reschedule-badge';
                        break;
                }

                return '<div class="status-badge ' . $class . '">
                    <span></span>' . strtoupper($item->booking_status) . '
                </div>';
            })
            ->addColumn('processed_by', function ($appointment) {
                if ($appointment->status_history->count()) {
                    $history = $appointment->status_history->first();
                    return  !empty($history['changedBy']) ? ($history['changedBy']['name'] != '' ? $history['changedBy']['name'] : 'N/A') : 'N/A';
                } else {
                    return $appointment->created_by_user->name;
                }
            })
            ->rawColumns(['action', 'dr_name', 'patient_name', 'booking_status', 'processed_by','booking_id','status'])
            ->toJson();
    }


    public function create(Request $request, $id = null)
    {
        $loginuserid = Auth::id();
        $hospital = null;
        $clinic = null;
        $doctor = null;
        $row = null;
        $params = [];
        $page_heading = "Book Appointment";
        $module_heading = "Appointments";
        $doctors = [];
        $departments = [];
        $members = [];
        if ($id) {
            $row = DoctorPatientAppointment::with(['user', 'doctor_reschedule_appointments'])->where('id', $id)->first();
            $row->booking_date = date('d-m-Y', strtotime($row->booking_date));
        }


        if($request->hospital_id){
            $hospital = Hospital::find($request->hospital_id);
            $departments = $hospital->departments;
            $doctors = Doctor::where('hospital_id', $request->hospital_id)->get();
            $page_heading.= '- '.$hospital->name_en. 'Hospital';
            $params['hospital_id'] = $hospital->id;
        }

        if($request->clinic_id){
            $clinic = Hospital::find($request->clinic_id);
            $departments = $clinic->departments;
            $doctors = Doctor::where('hospital_id', $request->clinic_id)->get();
            $page_heading.= '- '.$clinic->name_en. 'Clinic';
            $params['clinic_id'] = $clinic->id;
        }

        if($request->doctor_id){
            $doctor = Doctor::find($request->doctor_id);
            $hospital = $doctor->hospital;
            $departments = $doctor->departments;
            $doctors = Doctor::where('id', $request->doctor_id)->get();
            $page_heading.= '- DR'.$clinic->name_en;
            $params['doctor_id'] = $doctor->id;
        }

        if ($row->department_id ?? null) {
            $doctors = Doctor::whereHas('departments', function ($query) use ($row) {
                $query->where('department_id', $row->department_id);
            })->get();
        }

        $patients = User::where('role', USER_ROLE)->where('deleted', 0)->get();

        if($row->user_id ?? null){
            $members = Members::where('user_id', $row->user_id)->get();
        }
        // dd($members->toArray());
        $time_slot = TIME_SLOTS;
        // dd($row);
        return view('admin.appointments.create', compact(
            'page_heading', 'module_heading',
            'id',
            'patients',
            'hospital',
            'clinic',
            'departments',
            'doctors',
            'time_slot',
            'members',
            'row',
            'params'
        ));
    }

    public function saveAppointment(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->first();
        $hospital_id  = $doctor->hospital_id;
        // Validation rules
        $validator = Validator::make($request->all(), [
            'department' => 'nullable|exists:departments,id',
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
            $appointment->booking_status = BOOKING_STATUS_PENDING;
            $appointment->updated_at = gmdate('Y-m-d H:i:s');

            // Update member_id if provided
            if ($request->has('member')) {
                $appointment->member_id = $request->member;
            }

            // dd($appointment);
            $appointment->save();

            $status = "1";
            $o_data['redirect'] = route('doctor.totalappointments', $doctor->doctor_id);
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    public function rescheduleAppointment(Request $request)
    {
        // dd($request->all());
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        // $loginuserid = Auth::id();
        // $doctor = Doctor::where('user_id',$loginuserid)->first();
        // $hospital_id  = $doctor->hospital_id;
        // dd($request->all());
        // Validation rules
        $validator = Validator::make($request->all(), [
            // 'doctor_id' => 'required|exists:users,id',
            'appointment_id' => 'required|exists:doctor_patient_appointments,id',
            'booking_time_slot' => 'required',
            'booking_date' => 'required|date_format:d-m-Y',
            'reason_reschedule' => 'nullable'
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $bookingId = $request->appointment_id ?? null;

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
            $appointment->reason_reschedule = $request->reason_reschedule;
            $appointment->previous_booking_date = $appointment->booking_date;
            $appointment->previous_booking_time_slot = $appointment->booking_time_slot;
            $appointment->booking_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->booking_date)->format('Y-m-d');
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
            $o_data['redirect'] = route('doctor.totalappointments');
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    public function reports(Request $request)
    {

        $page_heading = "Hospital Dashboard";
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $hospital->id;
        $doctorIds = Doctor::where('hospital_id', $hospitalId)->pluck('id');
        $totaldoctors = Doctor::whereIn('hospital_id', $doctorIds)->count();
        $doctors = Doctor::whereIn('doctors.id', $doctorIds)
        ->join('users', 'doctors.user_id', '=', 'users.id')
        ->select('doctors.*', 'users.name as doct_name')
        ->get();

        // $doctors = Doctor::whereIn('hospital_id', $doctorIds ->join('users', 'doctors.user_id', '=', 'users.id'))->select('doctors.*', 'users.name as doct_name')->get();
        $totaldepartments = DepartmentModel::where('deleted',0)->count();
        $departments  = DepartmentModel::where('deleted',0)->get();
        $appointments = DoctorPatientAppointment::whereIn('doctor_id', $doctorIds)->orderBy('id', 'desc')->get();
        //$totalappointments = DoctorPatientAppointment::whereIn('doctor_id', $doctorIds)->count();
        $pendingappointments = DoctorPatientAppointment::whereIn('doctor_id', $doctorIds)->where('booking_status', 1)->count();
        $confirmappointments = DoctorPatientAppointment::whereIn('doctor_id', $doctorIds)->where('booking_status', 4)->count();
        $completedappointments = DoctorPatientAppointment::whereIn('doctor_id', $doctorIds)->where('booking_status', 2)->count();
        $cancelledappointments = DoctorPatientAppointment::whereIn('doctor_id', $doctorIds)->where('booking_status', 3)->count();
        // $appointments = DoctorPatientAppointment::whereIn('doctor_id', $doctorIds)->orderBy('id', 'desc')->take(5)->with('patient')->get();
        $query = DoctorPatientAppointment::join('doctors', 'doctor_patient_appointments.doctor_id', '=', 'doctors.id')
        ->select('doctor_patient_appointments.*');
        if ($request->filled('department_id')) {
            $query->where('doctors.department_id', $request->department_id);
        }
        if ($request->filled('doctor_id')) {
            echo $request->doctor_id;
            $query->where('doctor_patient_appointments.doctor_id', $request->doctor_id);
        }else{

            $query->whereIn('doctor_patient_appointments.doctor_id', $doctorIds);
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

        return view('doctor.reports', compact('page_heading','doctors','departments','totaldepartments','totaldoctors','pendingappointments','confirmappointments','completedappointments','cancelledappointments','appointments'));
    }

    public function booking_details(Request $request, $id){
        $appointment = DoctorPatientAppointment::where('id', $id)->with('followups','docs')->first();
        $hospital = null;
        $spc_hospital_id = null;
        $clinic = null;
        $doctor = null;
        $patient = null;
        $module_heading = 'Appointment';
        $page_heading = 'Appointment';

        if($request->hospital_id){
            $hospital = Hospital::find($request->hospital_id);
            $spc_hospital_id = $hospital->id;
            $departments = $hospital->departments;
            $page_heading.= '- '.$hospital->name_en.' hospital';
        }

        if($request->clinic_id){
            $clinic = Hospital::find($request->clinic_id);
            $spc_hospital_id = $clinic->id;
            $page_heading.= '- '.$clinic->name_en.' clinic';
        }

        if($request->doctor_id){
            $doctor = Doctor::find($request->doctor_id);
            $spc_hospital_id = $doctor->hospital_id;
            $page_heading.= '- DR'.$doctor->name_en.'';
        }
        if($request->patient_id){
            $patient = User::find($request->patient_id);
            $page_heading.= '- '.$patient->first_name.' '.$patient->last_name;
        }
        // dd($appointment->latestStatus->changedBy);
        $time_slot = TIME_SLOTS;
        $prescription = Prescription::with('details')
    ->where('appointment_id', $appointment->id)
    ->first();


    // Lookup tables
    $medicines   = Medicin::where('status', 1)->orderBy('title')->get();
    $directions  = Direction::where('status', 1)->orderBy('title')->get();
    $frequencies = Frequency::where('status', 1)->orderBy('title')->get();
    $durations   = Duration::where('status', 1)->orderBy('title')->get();
    $dosages   =Dosage::where('status', 1)->orderBy('title')->get();
    $clinic_summary = ClinicalSummary::where('appointment_id', $appointment->id)
    ->first();
    $appointment->clinic_summary=$clinic_summary;
    $referral_details   =ReferralDetail::where('appointment_id', $appointment->id)
    ->with('refferal_doctor','department','referral')
    ->first();
    $referrals   =Referral::where('status', 1)->orderBy('title')->get();

        return view('admin.appointments.view',compact('id','appointment', 'module_heading',
         'doctor', 'clinic', 'hospital', 'time_slot', 'spc_hospital_id', 'patient',
         'prescription','medicines','directions','frequencies','durations','dosages','clinic_summary','referral_details','referrals'));
    }

    public function booking_history(Request $request, $id){
        

       
        $appointments = DoctorPatientAppointment::where('user_id', $id)
        ->where('booking_status','Completed')
        ->with('followups','docs')->orderBy('id','desc')->get();
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
        return view('admin.appointments.history',compact('id','appointments', 'module_heading',
         'doctor', 'clinic', 'hospital', 'time_slot', 'spc_hospital_id', 'patient',
         ));
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

    public function patientAppointmentConfirmed(REQUEST $request){
        try {
            $doctor = DoctorPatientAppointment::find($request->appointment_id);
            $doctor->booking_status   = BOOKING_STATUS_CONFIRMED;
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->save();
            // save history
            $this->addAppointmentHistory($doctor->id, $doctor->booking_status, auth()->user()->id);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor InstantAppointment Confirmed Successfully";
            return response()->json( [ 'success' => $message ] );
        } catch (Exception $e) {
            return response()->json( [ 'sorry' => 'Unable to confirm this appointment!' ] );
        }

    }

    public function patientAppointmentCompleted(REQUEST $request){
        try {
            $doctor = DoctorPatientAppointment::find($request->appointment_id);
            $doctor->booking_status   = BOOKING_STATUS_COMPLETED;
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->save();
            $this->addAppointmentHistory($doctor->id, $doctor->booking_status, auth()->user()->id);
            exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor InstantAppointment Completed Successfully";
            return response()->json( [ 'success' => $message ] );
        } catch (Exception $e) {
            return response()->json( [ 'sorry' => 'Unable to complete this appointment!' ] );
        }
    }

    public function patientAppointmentRescheduled(REQUEST $request){
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

            return response()->json(['status' => $status, 'message' => $message, 'errors' => (object)$errors]);
        }

        DB::beginTransaction();
        try {
                $booking_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->booking_date)->format('Y-m-d');
                $doctor = DoctorPatientAppointment::find($request->appointment_id);
                $doctor->previous_booking_date = $doctor->booking_date;
                $doctor->previous_booking_time_slot    =  $doctor->booking_time_slot;
                $doctor->reason_reschedule  = $request->reason_reschedule;
                // $doctor->booking_date = $request->booking_date;
                $doctor->booking_date = $booking_date;
                $doctor->booking_time_slot    =  $request->booking_time_slot;
                $doctor->booking_status   = BOOKING_STATUS_RESCHEDULED;
                $doctor->created_at = gmdate('Y-m-d H:i:s');
                $doctor->updated_at = gmdate('Y-m-d H:i:s');
                $doctor->save();
                $this->addAppointmentHistory($doctor->id, $doctor->booking_status, auth()->user()->id);
                $RescheduleAppointment = new DoctorRescheduleAppointment();

                $RescheduleAppointment->doctor_id = $doctor->doctor_id;
                $RescheduleAppointment->patient_appointment_id = $request->appointment_id;
                $RescheduleAppointment->reschedule_patient_booking_date = $booking_date;
                $RescheduleAppointment->reschedule_patient_time_slot    =  $request->booking_time_slot;
                $RescheduleAppointment->save();
                exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
                DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor Appointment Updated Successfully";

         return response()->json( [ 'success' => 'Doctor Appointment Updated Successfully!' ] );
        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
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

                            $currentTime = Carbon::now()->setTimezone(config('global.date_timezone'));

                            foreach ($timeSlot as $key => $value) {
                                $specifiedTime = Carbon::createFromFormat('H:i', $value, 'Asia/Karachi');
                                if ($currentTime->format('Y-m-d') === $booking_date) {
                                    if (!$specifiedTime->lessThan($currentTime)) {
                                        $doctor_time_slot[] =[
                                            "slot_text" => $value,
                                            "is_available" => (!in_array($value, $takenAppointment) && !in_array($value, $unavailable_timeslot))
                                        ];
                                    }
                                } else {
                                    $doctor_time_slot[] =[
                                        "slot_text" => $value,
                                        "is_available" => (!in_array($value, $takenAppointment) && !in_array($value, $unavailable_timeslot))
                                    ];
                                }
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

    public function export(Request $request)
    {
        $filters = $request->all();
        // dd($filters);
        $exporter = new AppointmentsExport();
        $fileName = $exporter->export($filters);

        return response()->download($fileName)->deleteFileAfterSend(true);
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

public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        $user = DoctorPatientAppointment::find($request->id);
        if($user){
            if (DoctorPatientAppointment::where('id', $request->id)->update(['document_permission' => $request->status])) {
                $status = "1";
                $msg = "Request Approved";
                if (!$request->status) {
                    $msg = "Request Rejected";
                }
                $message = $msg;
            } else {
                $message = "Something went wrong";
            }
        }else{
            $message = "Record Not Exist!";
        }

        echo json_encode(['status' => $status, 'message' => $message]);

        
    }

}
