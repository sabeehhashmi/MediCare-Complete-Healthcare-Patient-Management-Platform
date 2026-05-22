<?php

namespace App\Http\Controllers\callcenter;

use App\Models\DoctorAppointmentsStatus;
use App\Exports\PatientsExport;
use App\Models\Hospital;
use App\Http\Controllers\Controller;
use App\Models\AgentModal;
use App\Models\AgentModel;
use App\Models\AgentUserDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\Area;
use App\Models\CountryModel;
use App\Models\Languages;
use App\Models\Emirate;
use App\Models\User;
use App\Models\Members;
use App\Models\Doctor;
use App\Models\HospitalImage;
use App\Models\DoctorPatientAppointment;
use App\Models\InsurencePolicy;
use App\Models\SubInsurencePolicy;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Exception;
use Illuminate\Support\Facades\DB;

class PatientsController extends Controller
{
    public function index()
    {
        

        $page_heading = "Patients";

        // Get the agents list
//        $patients = User::where('role', USER_ROLE)->where('deleted', 0)
//            ->orderBy('id', 'desc')
//            ->get();

        return view('callcenter.patients.index', compact('page_heading'));
    }

    public function members($id)
    {
        
        $patient_id = $id;
        $page_heading = "My Patients";
        $patient = User::where('deleted', 0)->where('role', USER_ROLE)->where('id', $patient_id)->first();
        $page_heading.= $patient ? ('- '.$patient->first_name.' '.$patient->last_name) : '';
        // Get the agents list
        $records = Members::where('user_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(10);
            // dd($records->data);

        return view('callcenter.patients.members', compact('page_heading', 'records', 'patient_id'));
    }

    public function appointments($id){
        
        $page_heading="Appointments";
        $patient_id = $id;
        $patient = User::with('user_role')
        ->where(['users.deleted' => '0', 'role' => USER_ROLE])
        ->orderBy('users.id', 'desc')
        ->get();
        return view('callcenter.patients.appointment',compact('page_heading','patient','patient_id'));

    }

    public function create($id = '')
    {
       
        $page_heading = $id ? "Edit Patient" : "Add Patient";
        $patient = null;
        $language_spoken = Languages::where(['status'=>1])->get();
        $insurence_list = InsurencePolicy::where(['status'=>1])->orderBy('title','asc')->get();
        $sub_insurence_list = [];
        if ($id) {
            $patient = User::with('agentDetails')->where('id', $id)->first();
            if($patient->insurence_id){
                $sub_insurence_list = SubInsurencePolicy::where('insurence_id', $patient->insurence_id)->orderBy('title','asc')->get();
            }
        }

        return view('callcenter.patients.create', compact(
            'page_heading',
            'language_spoken',
            'insurence_list',
            'sub_insurence_list',
            'id',
            'patient',
        ));
    }

    public function create_appointment($patient_id, $id = '')
    {
       
        $row = null;
        $page_heading = $id ? "Edit Appointment" : "Book Appointment";
        $hospitals = Hospital::get();
        $doctors = [];
        $departments = [];
        if ($id) {
            $row = DoctorPatientAppointment::with(['user', 'doctor_reschedule_appointments'])->where('id', $id)->first();
            $row->booking_date = date('d-m-Y', strtotime($row->booking_date));

            if($row->hospital_id){
                $hospitls = Hospital::find($row->hospital_id);
                $departments = $hospitls->departments;
                $doctors = Doctor::with('user')->where('hospital_id', $row->hospital_id)->get();
            }
            if ($row->department_id ?? null) {
                $doctors = Doctor::with('user')->whereHas('departments', function ($query) use ($row) {
                    $query->where('department_id', $row->department_id);
                })->get();
            }

            if ($patient_id != $row->user_id) {
                return redirect()->route('callcenter.patients.create_appointment', $patient_id);
            }
        }

        $patients = User::where('role', USER_ROLE)->where('deleted', 0)->where('id', $patient_id)->get();


        $members = Members::where('user_id', $patient_id)->get();
        // dd($members->toArray());
        $time_slot = TIME_SLOTS;

        return view('callcenter.patients.make_appointment', compact(
            'page_heading',
            'id',
            'patients',
            'patient_id',
            'hospitals',
            'departments',
            'doctors',
            'time_slot',
            'members',
            'row'
        ));
    }

    public function createMember($patient_id, $id = '')
    {
        
        $page_heading = $id ? "Edit My Patient" : "Add My Patient";
        $row = null;
        $language_spoken = Languages::where(['status'=>1])->get();
        $patient = User::where('deleted', 0)->where('role', USER_ROLE)->where('id', $patient_id)->first();
        $page_heading.= $patient ? ('- '.$patient->first_name.' '.$patient->last_name) : '';
        $insurence_list = InsurencePolicy::orderBy('title','asc')->get();
        $sub_insurence_list = [];
        if ($id) {
            $id = decrypt($id);
            $row = Members::where('id', $id)->first();
            $patient_id = $row->user_id;

            if($row->insurence_id){
                $sub_insurence_list = SubInsurencePolicy::where('insurence_id', $row->insurence_id)->orderBy('title','asc')->get();
            }

            if ($patient_id != $row->user_id) {
                return redirect()->route('callcenter.patients.createMember', $patient_id);
            }
        }


        return view('callcenter.patients.create_member', compact(
            'page_heading',
            'language_spoken',
            'insurence_list',
            'patient',
            'sub_insurence_list',
            'id',
            'row',
            'patient_id'
        ));
    }
    public function save(Request $request)
    {
        $message = "Patient Added Successfully";
        $o_data['redirect'] = route('callcenter.patients.index');

        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $sanitizedWhatsappPhone = preg_replace('/\D/', '', $request->whatsapp_phone ?? null);
        $request->merge(['phone' => $sanitizedPhone, 'whatsapp_phone' => $sanitizedWhatsappPhone]);
       // dd($request->all());
        // Validation rules
        $rules = [
            'id' => 'nullable|integer',
            'first_name' => 'required|string|max:255',
            'gender' => 'required|in:1,2', // Assuming 1 and 2 are valid gender codes
            'dob' => 'required|date_format:d-m-Y|before:today',
            'email' => ['nullable', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => 'nullable|min:8', // Password is nullable for updates
            'phone' => 'required|numeric|digits_between:7,12',
            'whatsap_dial_code' => 'nullable|numeric',
            'whatsapp_phone' => 'nullable|numeric|digits_between:8,12',
            'insurence_id' => 'nullable|integer|exists:insurence_policies,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        $subInsuranceExists = \DB::table('sub_insurence_policies')
        ->where('insurence_id', $request->insurence_id)
        ->exists();
        if($subInsuranceExists){
        $rules['sub_insurence_id'] = 'required|integer|exists:sub_insurence_policies,id';
        }
        try {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => '0', 'message' => 'Validation error occurred', 'errors' => $validator->messages()]);
            }

            // Check for unique email
            $id = $request->id;
            $check_email = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->where('id', '!=', $id)->first();

            if ($check_email && $request->email!='') {
                return response()->json(['status' => '0', 'message' => 'Email id already registered with us', 'errors' => ['email' => 'Email id already registered with us']]);
            }

            // Check for unique phone number
            if ($request->dial_code && $request->phone) {
                $check_phone = User::where('dial_code', $request->dial_code)->where('phone', $request->phone)->where('id', '!=', $id)->first();

                if ($check_phone) {
                    return response()->json(['status' => '0', 'message' => 'Phone number already registered with us', 'errors' => ['phone' => 'Phone number already registered with us']]);
                }
            }

            // Find or create the user
            if ($id) {
                $message = "Patient Updated Successfully";
                $user = User::where('deleted', 0)->find($id);

                if (!$user) {
                    return response()->json(['status' => '0', 'message' => 'User not found', 'errors' => ['id' => 'User not found']]);
                }
            } else {
                $user = new User();
                $user->role = USER_ROLE;
                // $user->user_type_id = USER_ROLE;
            }
            $user->email = strtolower($request->email);
            // Handle image upload
            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $user->user_image = $file_name;
            }

            DB::beginTransaction();

            // Update user data
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->gender = $request->gender;
            $user->dob = \Carbon\Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d');
            $user->active = 1;

            if ($request->password) {
                $user->password = Hash::make($request->password);
            }

            $user->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
            $user->phone = str_replace(" ", "", ltrim($request->phone, "0"));
            $user->whatsap_phone = str_replace(" ", "", ltrim($request->whatsapp_phone, "0"));
            $user->whatsap_dial_code = $request->whatsap_dial_code;

            $user->insurence_id = $request->insurence_id;
            $user->sub_insurence_id = $request->sub_insurence_id;
            $user->deleted = 0;
            $user->save();
            
           if($id){
                activity_log('patient_profile_updated', "$user->name Profile Updated");
           }
           else{
             activity_log('patient_profile_created', "$user->name Profile Created");
           }

            DB::commit();
            echo json_encode(['status' => 1, 'message' => $message, 'oData' => $o_data]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => '0', 'message' => 'Failed to save patient: ' . $e->getMessage()]);
        }
    }


    public function saveMember(Request $request)
    {
        $message = "Member Added Successfully";
        $o_data['redirect'] = route('callcenter.patients.members', $request->patient);

        $rules = [
            'id' => 'nullable|integer',
            'full_name' => 'required|string|max:255',
            // 'full_name_ar' => 'nullable|string|max:255',
            'age' => 'required|integer|min:0',
            'gender' => 'required|in:1,2,3',
            'insurence_id' => 'nullable|integer|exists:insurence_policies,id',
            'patient' => 'required|integer|exists:users,id',
        ];

        $subInsuranceExists = \DB::table('sub_insurence_policies')
        ->where('insurence_id', $request->insurence_id)
        ->exists();
        if($subInsuranceExists){
        $rules['sub_insurence_id'] = 'required|integer|exists:sub_insurence_policies,id';
        }

        try {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => '0', 'message' => 'Validation error occurred', 'errors' => $validator->messages()]);
            }

            $id = $request->id;

            // Find or create the patient
            if ($id) {
                $message = "Member Updated Successfully";
                $member = Members::find($id);

                if (!$member) {
                    return response()->json(['status' => '0', 'message' => 'Member not found', 'errors' => ['id' => 'Member not found']]);
                }
            } else {
                $member = new Members();
            }

            DB::beginTransaction();

            // Update patient data
            $member->full_name = $request->full_name;
            $member->full_name_ar = $request->full_name_ar;
            $member->age = $request->age;
            $member->gender = $request->gender;
            $member->user_id = $request->patient;
            $member->insurence_id = $request->insurence_id;
            $member->sub_insurence_id = $request->sub_insurence_id;
            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $member->user_image = $file_name;
            }

            $member->save();

            DB::commit();
            return response()->json(['status' => '1', 'message' => $message, 'oData' => $o_data]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => '0', 'message' => 'Failed to save member: ' . $e->getMessage()]);
        }
    }

    public function saveAppointment(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        // Validation rules
        $validator = Validator::make($request->all(), [
            'doctor' => 'required|exists:users,id',
            'hospital' => 'required|exists:hospitals,id',
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
                $doctor = DoctorPatientAppointment::find($bookingId);

                if (!$doctor) {
                    return response()->json(['status' => '0', 'message' => 'Appointment not found', 'errors' => ['id' => 'Appointment not found']]);
                }

                $message = "Appointment Updated Successfully";
            } else {
                // Create a new appointment
                $doctor = new DoctorPatientAppointment();
                $doctor->booking_id = '#MYDW' . $FourDigitRandomNumber;
                $doctor->member_id = '0';
                $doctor->created_at = gmdate('Y-m-d H:i:s');
                $message = "Appointment Booked Successfully";
            }

            // Common fields for both add and update
            $doctor->doctor_id = $request->doctor;
            $doctor->department_id = $request->department ?? null;
            $doctor->hospital_id = $request->hospital;
            $doctor->user_id = $request->patient;
            $doctor->booking_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->booking_date)->format('Y-m-d');
            $doctor->booking_time_slot = $request->booking_time_slot;
            $doctor->booking_status = 'Pending';
            $doctor->updated_at = gmdate('Y-m-d H:i:s');

            // Update member_id if provided
            if ($request->has('member')) {
                $doctor->member_id = $request->member;
            }

            $doctor->save();

            DoctorAppointmentsStatus::create([
                'appointment_id' => $doctor->id,
                'status' => 'Created',
                'changed_by' => Auth::id(),
                'changed_at' => Carbon::now()
            ]);
            $status = "1";
            $o_data['redirect'] = route('callcenter.patients.appointments', $request->patient);
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        // dd($filters);
        $exporter = new PatientsExport();
        $fileName = $exporter->export($filters);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }


    public function load_data(REQUEST $request)
    {
        $query = User::query()
            ->where('role', USER_ROLE)->where('deleted', 0);
        if ($request->has('search') && ($request->search['filters'] ?? null)) {
            $filters = $request->search['filters'];

            if ($filters['booking_from'] ?? null) {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $filters['booking_from'])->format('Y-m-d');
                $query->where('created_at', '>=', $date);
            }
            
            if ($filters['booking_to'] ?? null) {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $filters['booking_to'])->endOfDay()->format('Y-m-d H:i:s');
                $query->where('created_at', '<=', $date);
            }

            if (isset($filters['active'])) {
                $query->where('active', $filters['active']);
            }
            if ($filters['gender'] != "") {
                $query->where('gender', $filters['gender']);
            }
        }


        $users = $query->select([
            'id', 'name', 'dial_code', 'phone', 'email', 'whatsap_dial_code', 'gender', 'whatsap_phone', 'active',
             'first_name', 'last_name','patient_id'])
            ->orderBy('id', 'desc');


        return DataTables::eloquent($users)
            ->addColumn('action', function ($user) {
                $action = '<div class="dropdown mt-4 mt-sm-0">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu">';

//                $action .= '<a class="dropdown-item complete-link" href="' . route('callcenter.patients.show', ['id' => $user->id]) . '">View </a>';
                $action .= '<a class="dropdown-item complete-link" href="' . route('callcenter.patients.edit', ['id' => $user->id]) . '">Edit </a>';
                $action .= '<a class="dropdown-item complete-link" href="'. route('callcenter.patients.members',['id'=>$user->id]) . '">Members</a>';
                $action .= '<a class="dropdown-item complete-link" href="' . route('callcenter.hospitalAppointments',['patient_id'=>$user->id]) . '">Appointments</a>';
                
                    $action .= '<a class="dropdown-item" data-role="unlink"
                                            data-message="Do you want to remove the patients?  This may be linked with other sections"
                                            href="' . route('callcenter.patients.delete', ['id' => encrypt($user->id)]) . '">
                                            <i class="flaticon-delete-1"></i> Delete
                                        </a>';
                

                $action .= '</div>
            </div>';

                return $action;
            })
            ->addColumn('sl_no', function ($user) {
                static $index = 0;
                return ++$index;
            })
            ->addColumn('name', function ($user) {
                return ($user->name ? $user->name : $user->first_name . ' ' . $user->last_name);
            })
            ->addColumn('phone_number', function ($item) {
                return '+' . $item->dial_code . $item->phone;
            })
            ->addColumn('whatsapp_number', function ($item) {
                return '+' . $item->whatsap_dial_code . $item->whatsap_phone;
            })
            ->addColumn('gender', function ($item) {
                $gender = '';
                if ($item->gender === 1)
                    $gender = 'Male';
                if ($item->gender === 2)
                    $gender = 'Female';
                if ($item->gender === 3)
                    $gender = 'Other';

                return $gender;
            })
            ->addColumn('status', function($item) {
                return '<div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                        <input type="checkbox" class="form-check-input change_status" data-id="'.$item->user_id.'"
                                    data-url="'.url('admin/patients/change_status').'"
                                    '.($item->active == 1 ? 'checked' : '').'>
                    </div>';
            })
            ->rawColumns(['status', 'action'])

            ->toJson();
    }

    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
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
        echo json_encode(['status' => $status, 'message' => $message]);
    }

    // public function appointmentLoadData(REQUEST $request){

    //     $users = DoctorPatientAppointment::query()
    //     ->where('doctor_patient_appointments.user_id','=',$request->patient_id)
    //     ->leftJoin('users', 'users.id', '=', 'doctor_patient_appointments.doctor_id')
    //     ->select( 'users.first_name','users.last_name','doctor_patient_appointments.booking_id' ,'doctor_patient_appointments.booking_status','doctor_patient_appointments.booking_date','doctor_patient_appointments.id','doctor_patient_appointments.booking_time_slot', 'user_id')
    //     ->orderBy('doctor_patient_appointments.id','desc');
    //     // dd($users->toArray());
    //     return DataTables::eloquent($users)
    //     ->addColumn('action', function($user) {
    //          $action = '<div class="dropdown mt-4 mt-sm-0">
    //             <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    //                 <i class="bx bx-dots-horizontal-rounded"></i>
    //             </button>
    //             <div class="dropdown-menu">';

    //             if (get_user_permission('patients', 'r')) {
    //                 $action .= '<a class="dropdown-item complete-link" href="'.route('callcenter.patients.edit_appointment', ['patient_id' => $user->user_id ?? null, 'id' => $user->id]).'">Edit</a>';
    //                 $action .= '<button class="dropdown-item complete-link delete-appointment" data-id="'.encrypt($user->id).'">Delete</button>';
    //             }


    //        $action .='</div>
    //         </div>';

    //         return $action;
    //     })
    //     ->addColumn('sl_no', function($user) {
    //         static $index = 0;
    //         return ++$index;
    //     })
    //     ->addColumn('name', function($item) {
    //         return $item->first_name.''.$item->last_name;
    //     })


    //     ->toJson();
    // }

    public function appointmentLoadData(REQUEST $request){
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;

        $users = DoctorPatientAppointment::query()
        ->where('doctor_patient_appointments.user_id','=',$request->patient_id)
        ->join('doctors', 'doctors.id', '=', 'doctor_patient_appointments.doctor_id')
            ->join('users as doctorUsers', 'doctorUsers.id', '=', 'doctors.user_id')
            ->join('users as patients', 'patients.id', '=', 'doctor_patient_appointments.user_id')
            ->leftJoin('members', 'members.id', '=', \DB::raw('CAST(doctor_patient_appointments.member_id AS bigint)'))
        ->select([
            'doctor_patient_appointments.*',
            'doctorUsers.first_name as doctor_first_name',
            'doctorUsers.last_name as doctor_last_name',
            'patients.first_name as patient_first_name',
            'members.full_name as member_full_name'
        ])->orderBy('doctor_patient_appointments.id', 'desc');
        // dd($users->toArray());
        return DataTables::eloquent($users)
        ->addColumn('action', function($user) {
             return '<a href="'.route('patient.appointment_detail', ['id' => $user->id]).'" class="btn btn-icon btn-primary"><i class="mdi mdi-eye"></i></a>';
        })
        ->addColumn('sl_no', function($user) use (&$startIndex) {
            return ++$startIndex;
        })
        ->addColumn('doctor_first_name', function ($item) {
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
        ->rawColumns(['action', 'doctor_first_name', 'patient_name', 'booking_status'])
        ->toJson();
    }

    public function delete(Request $request, $id)
    {
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

        $user_data = User::where('role', USER_ROLE)->where('id', $id)->first();
        if ($user_data) {

            $user_data->user_device_token = "";
            $user_data->email = $user_data->email . "__deleted_account_" . $user_data->id;
            $user_data->phone = $user_data->phone . "__deleted_account_" . $user_data->id;
            $user_data->deleted = 1;
            $user_data->access_token = "";
            $user_data->update();

            // $user_data->deleted = 1;
            // $user_data->save();
            Members::where('user_id', $id)->delete();
            $message = "Patient deleted successfully";
            $status = "1";
        } else {
            $message = "Invalid User data";
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }


    public function deleteMember(Request $request, $id)
    {
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

        $row = Members::find($id);
        if ($row) {
            $row->delete();
            $message = "Member deleted successfully";
            $status = "1";
        } else {
            $message = "Invalid Member data";
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function delete_appointment(Request $request, $id)
    {
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
        if ($row) {
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

    public function getmembers($parent_id) {
        $data = Members::where('user_id', $parent_id)->get();
        return response()->json($data);
    }

}
