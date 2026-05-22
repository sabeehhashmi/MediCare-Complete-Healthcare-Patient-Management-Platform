<?php

namespace App\Http\Controllers\agent;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\AgentUserDetail;
use Validator,DB;
use App\Models\Area;
use App\Models\CountryModel;
use App\Models\DepartmentModel;
use App\Models\Emirate;
use App\Models\User;
use App\Models\HospitalImage;
use App\Models\DepartmentHospital;
use App\Models\DoctorPatientAppointment;
use App\Models\CallCenterUserDetail;
use App\Models\HospitalInsurancePolicy;
use App\Models\Members;
use App\Models\InsurencePolicy;
use App\Models\HospitalLocation;
use App\Models\SubInsurencePolicy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DataTables;

class ClinicController extends Controller
{
    public function index(){


        $page_heading="Clinics";
        $module_heading="Clinics";

        return view('agent.clinics.index',compact('page_heading', 'module_heading'));
    }

    public function create($id=''){

        // dd($id);
        $page_heading="Create Clinics";

        //  $country_list =  CountryModel::where(['active'=>1])->whereIn('prefix', ALLOWED_COUNTRIES_PREF)->get();
        $country_list =  CountryModel::where(['active'=>1])->get();
        // $department_list =  DepartmentModel::where(['status'=>1])->get();
        $emirates_list=[];
        $area_list = [];
        $country_id = 229;
        $userPhoneNumber = '';
        $appointment_phone = '';
        $emirate_id = '';
        $area_id    = '';
        $name       = '';
        $name_ar    = '';
        $trade_licenece = '';
        $hospital = null;
        $page = 'agent.clinics.create';
        $selected_department = [];
        if($id){
            $hospital = Hospital::where('id',$id)->first();

            $page_heading = "Update Clinics";
                $country_id = $hospital->country_id;
                $selected_departments_data = $hospital->departments->toArray();
                if(count($selected_departments_data)){
                    $selected_department = array_keys(mapArrayByIndex($selected_departments_data, 'id'));
                }

                $userPhoneNumber = $hospital->user->phone ? '+'.$hospital->user->dial_code.$hospital->user->phone : '';
                $appointment_phone = $hospital->appointment_phone ? '+'.$hospital->appointment_dial_code.$hospital->appointment_phone : '';
                $country_id = $hospital->country_id;
        }

        return view($page,compact(
            'hospital',
            'page_heading',
            'id',
            'country_list',
            'emirates_list',
            'area_list',
            'country_id',
            'emirate_id',
            'area_id',
            'name',
            'name_ar',
            'userPhoneNumber',
            'appointment_phone',
            'selected_department',
            'trade_licenece'
        ));
    }

    public function save(REQUEST $request)
    {
        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];

        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $sanitizedDirectPhone = preg_replace('/\D/', '', $request->direct_phone ?? null);
        $request->merge(['phone' => $sanitizedPhone, 'direct_phone' => $sanitizedDirectPhone]);

        $o_data['redirect'] = route('agent.clinics.index');
        $rules = [
            'name_en' => 'required',
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'image.*' => 'mimes:jpeg,png,pdf|max:2048',
            'trade_licenece' => 'mimes:jpg,jpeg,png,pdf|max:2048',
            // 'department' => 'required|array|min:1',
            // 'department.*' => 'exists:departments,id',
            'website' => 'nullable|url',
            'dial_code'=>'nullable|numeric',
            'phone'=>'numeric|digits_between:8,12',
            'password' => !$request->id ? 'required|min:8' : '',
            'direct_dial_code'=>'nullable|numeric',
            'direct_phone'=>'nullable|numeric|digits_between:8,12',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $id = $request->id;
              $doctorUserId = null;
             if (!empty($id)) {
            $doctorUserId = Hospital::where('id', $id)->value('user_id');
        }

         $check_email = User::whereRaw('LOWER(email) = ?', [strtolower($request->email)])
            ->when($doctorUserId, function ($query) use ($doctorUserId) {
                $query->where('id', '!=', $doctorUserId);
            })
            ->first();

            
            
       
        if ($check_email) {
            return response()->json([
                'status' => 0,
                'errors' => ['email' => 'Email id already registered with us'],
                'message' => 'Email id already registered with us'
            ]);
        }
       
        $check_phone = User::where('phone', $request->phone)
            ->where('dial_code', $request->dial_code)
            ->when($doctorUserId, function ($query) use ($doctorUserId) {
                $query->where('id', '!=', $doctorUserId);
            })
            ->first();


        if ($check_phone) {
            return response()->json([
                'status' => 0,
                'errors' => ['phone' => 'Phone number already registered with us'],
                'message' => 'Phone number already registered with us'
            ]);
        }
            $location = explode(",",$request->location);
            $latitude = $location[0];
            $longitude  = $location[1];

            if ($check_email && !$id) {
                $status = "0";
                $message = "Email id already registered with us";
                $errors['email'] = 'Email id already registered with us';
            } else {
                DB::beginTransaction();
                try {
                    if ($id) {
                        // Update logic
                        // dd($request->country);
                        $hospital = Hospital::where('type', TYPE_CLINIC)->where('id', $id)->first();
                        $hospital->type = TYPE_CLINIC;
                        $hospital->country_id = $request->country;
                        $hospital->emirate_id = $request->emirate_id;
                        $hospital->area_id    = $request->area_id;
                        $hospital->address    = $request->address;
                        $hospital->txt_location    = $request->txt_location;
                        $hospital->latitude    = $latitude;
                        $hospital->longitude    = $longitude;
                        $hospital->website    = $request->website;
                        $hospital->profile_description = $request->profile_bio;
                        $hospital->profile_description_ar = $request->profile_bio_ar;
                        $hospital->appointment_dial_code  = $request->direct_dial_code ? $request->direct_dial_code : DEFAULT_DIAL_CODE;
                        $hospital->appointment_phone      = str_replace(" ","",$request->direct_phone);
                        $hospital->name_en    = $request->name_en;
                        $hospital->name_ar    = $request->name_ar;
                        $hospital->save();
                        if ($request->has('department')) {
                            // $hospital->departments()->sync($request->department);
                        }
                        $user = User::find($hospital->user_id);
                        // dd($user);
                        // $user->email    = strtolower($request->email);
                        $user->name     = $request->name_en;
                        $user->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                        $user->phone     = str_replace(" ","",ltrim($request->phone,"0"));
                        if($request->password){
                            $user->password  = Hash::make($request->password);
                        }
                        $user->last_updated_by = Auth::user()->id;
                        $user->updated_at = gmdate('Y-m-d H:i:s');
                        $user->deleted = 0;
                        $user->save();

                        activity_log('clinic_profile_updated', "Clinic $user->name Profile Updated", [
                            'user_id' => $user->name
                        ]);

                        if ($file = $request->file("trade_licenece")) {
                            $file_name = time().uniqid().".".$file->getClientOriginalExtension();
                            $file->storeAs(config('global.trade_licenece_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $hospital->trade_licenece = $file_name;
                        }

                        if ($request->hasfile('images')) {
                            // Delete existing images
//                            HospitalImage::where('hospital_id', $hospital->id)->delete();

                            // Add new images
                            foreach ($request->file('images') as $file) {
                                $file_name2 = time().uniqid().".".$file->getClientOriginalExtension();
                                $file->storeAs(config('global.hospital_image_upload_dir'), $file_name2, config('global.upload_bucket'));
                                $image = new HospitalImage();
                                $image->hospital_id = $hospital->id;
                                $image->image_name = $file_name2;
                                $image->created_at = gmdate('Y-m-d H:i:s');
                                $image->updated_at = gmdate('Y-m-d H:i:s');
                                $image->save();
                            }
                        }

                        $hospital->save();
                        DB::commit();
                        $status = "1";
                        $message = "Clinic updated successfully";
                    } else {
                        // dd($request->all());
                        // Create logic
                        $user = new User();
                        if (Auth::user()->role == AGENT_ROLE) {
                            $agent = AgentUserDetail::where('user_id', Auth::id())->first();
                            $user_id  = $agent->callcenter_id;
                        } else {
                            $user_id = Auth::user()->id;
                        }
                        $callcenter =  CallCenterUserDetail::where('id',$user_id)->first();
                        $user->email    = strtolower($request->email);
                        $user->name     = $request->name_en;
                        $user->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                        $user->phone     = str_replace(" ","",ltrim($request->phone,"0"));
                        $user->password  = Hash::make($request->password);
                        $user->role      = CLINIC_ROLE;
                        $user->active    = 1;
                        $user->created_by = Auth::user()->id;
                        $user->last_updated_by = Auth::user()->id;
                        $user->created_at = gmdate('Y-m-d H:i:s');
                        $user->updated_at = gmdate('Y-m-d H:i:s');
                        $user->role_id     = 0;
                        $user->deleted = 0;
                        $verificationToken = Str::random(60);
                        $user->email_verification_token = $verificationToken;
                        $user->save();

                        $hospital = new Hospital();
                        $hospital->callcenter_id = $callcenter->id;
                        $hospital->user_id   = $user->id;
                        $hospital->type = TYPE_CLINIC;
                        $hospital->country_id = $request->country;
                        $hospital->emirate_id = $request->emirate_id;
                        $hospital->area_id    = $request->area_id;
                        $hospital->address    = $request->address;
                        $hospital->txt_location    = $request->txt_location;
                        $hospital->latitude    = $latitude;
                        $hospital->longitude    = $longitude;
                        $hospital->website    = $request->website;
                        $hospital->profile_description = $request->profile_bio;
                        $hospital->profile_description_ar = $request->profile_bio_ar;
                        $hospital->appointment_dial_code  = $request->direct_dial_code ? $request->direct_dial_code : DEFAULT_DIAL_CODE;
                        $hospital->appointment_phone      = str_replace(" ","",$request->direct_phone);
                        $hospital->name_en    = $request->name_en;
                        $hospital->name_ar    = $request->name_ar;

                        if ($file = $request->file("trade_licenece")) {
                            $file_name = time().uniqid().".".$file->getClientOriginalExtension();
                            $file->storeAs(config('global.trade_licenece_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $hospital->trade_licenece = $file_name;
                        }

                        $hospital->save();
                        if ($request->has('department')) {
                            // $hospital->departments()->sync($request->department);
                        }
                        if ($request->hasfile('images')) {
                            foreach ($request->file('images') as $file) {
                                $file_name2 = time().uniqid().".".$file->getClientOriginalExtension();
                                $file->storeAs(config('global.hospital_image_upload_dir'), $file_name2, config('global.upload_bucket'));
                                $image = new HospitalImage();
                                $image->hospital_id = $hospital->id;
                                $image->image_name = $file_name2;
                                $image->created_at = gmdate('Y-m-d H:i:s');
                                $image->updated_at = gmdate('Y-m-d H:i:s');
                                $image->save();
                            }
                        }

                           activity_log('clinic_profile_created', "Clinic $user->name Profile Created", [
                            'user_id' => $user->name
                        ]);

                        DB::commit();
                        $status = "1";
                        $message = "Clinic added successfully";
                        exec("php " . base_path() . "/artisan app:send-verification-mail " . $user->id . " clinic > /dev/null 2>&1 & ");
                    }

                    HospitalLocation::where('hospital_id',$hospital->id)->delete();
                        $locations = new HospitalLocation;
                        $locations->hospital_id = $hospital->id;
                        $locations->location = $request->txt_location;
                        $locations->latitude = $latitude;
                        $locations->longitude = $longitude;
                        $locations->created_at = gmdate('Y-m-d H:i:s');
                        $locations->updated_at = gmdate('Y-m-d H:i:s');
                        $locations->save();
                } catch (Exception $e) {
                    DB::rollback();
                    // dd($e);
                    $message = $id ? "Failed to update clinic " . $e->getMessage() : "Failed to create clinic " . $e->getMessage();
                }
            }
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function show($id)
    {
        $hospital = Hospital::findOrFail($id); // Assuming Hospital is your model

        return view('clinics.show', compact('hospital'));
    }


    public function load_data(REQUEST $request){
        $user_id = Auth::user()->id;
        // $callcenter =  CallCenterUserDetail::where('user_id',$user_id)->first();

        $users = Hospital::query()
        ->where('type', TYPE_CLINIC)
       // ->where('hospitals.callcenter_id',$callcenter->id)
        ->leftJoin('users', 'users.id', '=', 'hospitals.user_id')
        ->leftJoin('country', 'country.id', '=', 'hospitals.country_id')
        ->leftJoin('emirates', 'emirates.id', '=', 'hospitals.emirate_id')
        ->select('hospitals.*', 'users.email', 'users.dial_code','users.phone','country.name as country_name','emirates.name_en as emirate_name')
        ->orderBy('hospitals.id','desc');

    return DataTables::eloquent($users)
        ->addColumn('action', function($user) {
             $action = '<div class="dropdown mt-4 mt-sm-0">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu">';

                // if (get_user_permission('clinics', 'u')) {
                    $action.='<a class="dropdown-item complete-link" href="'.route('agent.clinics.clinicDetails',['id'=>$user->id]).'">View Clinic </a>';
                    $action.='<a class="dropdown-item complete-link" href="'.route('agent.clinics.edit',['id'=>$user->id]).'">Edit Clinic </a>';
                    // }
                $action.='<a class="dropdown-item" data-role="unlink"
                data-message="Do you want to remove the Clinic?  This may be linked with other sections"
                href="'.route('agent.clinics.delete', ['id' => encrypt($user->id)]).'">
                <i class="flaticon-delete-1"></i> Delete Clinic
              </a>';

                $action.='<a class="dropdown-item complete-link" href="'.route('agent.doctors', ['clinic_id' => $user->id]).'">Doctors </a>';
                $action.='<a class="dropdown-item complete-link" href="'.route('agent.appointments',['clinic_id' =>$user->id]).'">View Appointments</a>';
                // if (get_user_permission('clinics', 'r')) {
                   // $action.='<a class="dropdown-item complete-link" href="'.route('agent.clinics.appointments',['id'=>$user->id]).'">Appointments </a>';
                // }
                // if (get_user_permission('insurence_policy', 'r')) {
                    $action.='<a class="dropdown-item complete-link" href="'.route('agent.clinics.insurances',['id'=>$user->id]).'">View Insurance </a>';
                // }


           $action .='</div>
            </div>';

            return $action;
        })
        ->addColumn('sl_no', function($user) {
            static $index = 0;
            return ++$index;
        })
        ->addColumn('phone_number', function($item) {
            return  ($item->phone)?'+'.$item->dial_code.$item->phone:'';
        })


        ->toJson();
    }

    public function doctors($id){
        $hospital_id = $id;
        $hospital = Hospital::find($hospital_id);
        // dd($hospital);

        $page_heading="Doctors";
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';

        return view('agent.doctors.doctors',compact('page_heading', 'hospital_id', 'hospital'));

    }



    // public function change_status(REQUEST $request)
    // {
    //     $status = "0";
    //     $message = "";
    //     $o_data  = [];
    //     $errors = [];

    //     $id = $request->id;

    //     $item = DepartmentModel::where(['id' => $id])->first();

    //     if ($item) {
    //         DepartmentModel::where('id', $id)->update(['status' => $request->status]);
    //         $status = "1";
    //         $message = "Status changed successfully";
    //     } else {
    //         $message = "Faild to change status";
    //     }

    //     echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    // }

    public function appointments($id){
        // if (!get_user_permission('clinics', 'r')) {
        //     return redirect()->route('admin.restricted_page');
        // }
        $page_heading="Appointments";
        $hospital_id = $id;
        $hospital = Hospital::find($hospital_id);
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
        return view('agent.clinics.appointment',compact('page_heading','hospital','hospital_id'));
    }

    public function appointmentLoadData(REQUEST $request){

        $users = DoctorPatientAppointment::query()
        ->where('doctor_patient_appointments.hospital_id', '=', $request->hospital_id)
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
                    $action .= '<a class="dropdown-item complete-link" href="'.route('agent.clinics.edit_appointment', ['hospital_id' => $user->hospital_id ?? null, 'id' => $user->id]).'">Edit</a>';
                    $action .= '<button class="dropdown-item complete-link delete-appointment" data-id="'.encrypt($user->id).'">Delete</button>';
                // }

           $action .='</div>
            </div>';

            return $action;
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

    public function create_appointment($hospital_id, $id = '')
    {
        // if (!get_user_permission('patients', 'c')) {
        //     return redirect()->route('admin.restricted_page');
        // }
        $row = null;
        $page_heading = $id ? "Edit Appointment" : "Book Appointment";
        $hospital = null;
        $doctors = [];
        $departments = [];
        $members = [];
        if ($id) {
            $row = DoctorPatientAppointment::with(['user', 'doctor_reschedule_appointments'])->where('id', $id)->first();
            $row->booking_date = date('d-m-Y', strtotime($row->booking_date));
        }

        $hospital_id = $row->hospital_id ?? $hospital_id;

        if($hospital_id){
            $hospital = Hospital::find($hospital_id);
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
        return view('agent.clinics.make_appointment', compact(
            'page_heading',
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
                $doctor->booking_id = '#MEDN' . $FourDigitRandomNumber;
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

            $status = "1";
            $o_data['redirect'] = route('agent.clinics.appointments', $request->hospital);
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    public function insurances($id){
        $hospital_id = $id;
        $hospital = Hospital::find($hospital_id);
        // if (!get_user_permission('insurence_policy', 'r')) {
        //     return redirect()->route('admin.restricted_page');
        // }
        $page_heading="Clinics Insurances";
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
        $list = HospitalInsurancePolicy::with(['insurance', 'subInsurance', 'hospital'])->where('hospital_id', $hospital_id)->orderBy('id','desc')->paginate(10);
        return view('agent.clinics.insurances',compact('page_heading', 'hospital_id', 'hospital', 'list'));

    }

    public function createInsurance($hospital_id, $id = '')
    {
        // if (!get_user_permission('insurence_policy', 'c')) {
        //     return redirect()->route('admin.restricted_page');
        // }
        $insurances = InsurencePolicy::get();
        $sub_insurance_list = [];
        $row = null;
        $hospital = Hospital::find($hospital_id);
        $page_heading = $id ? 'Edit Clinics Insurance' : 'Create Clinics Insurance';
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';

        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $row = HospitalInsurancePolicy::find($id);
        }
        if($row->insurance_id ?? null){
            $sub_insurance_list = SubInsurencePolicy::where('insurence_id', $row->insurance_id)->get();
        }
        return view('agent.clinics.createInsurance', compact('page_heading', 'id', 'hospital_id', 'row', 'insurances', 'sub_insurance_list'));
    }

    public function saveInsurance(Request $request)
    {
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];

        // Validation rules
        $rules = [
            'hospital_id' => 'required|exists:hospitals,id',
            'insurance_id' => 'required|exists:insurence_policies,id',
            'sub_insurance_id' => 'nullable|array',
            'sub_insurance_id.*' => 'exists:sub_insurence_policies,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        $o_data['redirect'] = route('agent.clinics.insurances', $request->hospital_id ?? null);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $id = $request->id;
            $check = HospitalInsurancePolicy::where('hospital_id', $request->hospital_id)
                ->whereIn('sub_insurance_id', $request->sub_insurance_id)
                ->where('id', '!=', $id)
                ->first();

            if ($check) {
                $status = "0";
                $message = "This insurance policy is already associated with the hospital.";
                $errors['sub_insurance_id[]'] = (($check->subInsurance->title ?? null) ? $check->subInsurance->title : 'this').' insurance policy is already associated with this hospital.';
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        if ($request->has('sub_insurance_id') && is_array($request->sub_insurance_id)) {
                            foreach ($request->sub_insurance_id as $sub_insurance_id) {
                                $insurancePolicy = new HospitalInsurancePolicy();
                                $insurancePolicy->hospital_id = $request->hospital_id;
                                $insurancePolicy->insurance_id = $request->insurance_id;
                                $insurancePolicy->sub_insurance_id = $sub_insurance_id;
                                $insurancePolicy->save();
                            }
                        }

                        DB::commit();
                        $status = "1";
                        $message = "Insurance policy updated successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to update insurance policy: " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        if ($request->has('sub_insurance_id') && is_array($request->sub_insurance_id)) {
                            foreach ($request->sub_insurance_id as $sub_insurance_id) {
                                $insurancePolicy = new HospitalInsurancePolicy();
                                $insurancePolicy->hospital_id = $request->hospital_id;
                                $insurancePolicy->insurance_id = $request->insurance_id;
                                $insurancePolicy->sub_insurance_id = $sub_insurance_id;
                                $insurancePolicy->save();
                            }
                        }

                        DB::commit();
                        $status = "1";
                        $message = "Insurance policy added successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to add insurance policy: " . $e->getMessage();
                    }
                }
            }
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function deleteInsurance(REQUEST $request, $id)
    {

        // dd("working");
        $status = "0";
        $message = "";

        $id = decrypt($id);

        $row = HospitalInsurancePolicy::where(['id' => $id])->first();

        if ($row) {
            HospitalInsurancePolicy::where(['id' => $id])->delete();
            $message = "Clinics Insurance deleted successfully";
            $status = "1";
        } else {
            $message = "Invalid Role data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }

    public function locations($id){
        $hospital_id = $id;
        $hospital = Hospital::find($hospital_id);
        // if (!get_user_permission('hospital', 'u')) {
        //     return redirect()->route('admin.restricted_page');
        // }
        $page_heading="Clinics Locations";
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
        $list = HospitalLocation::with('hospital')->where('hospital_id', $hospital_id)->orderBy('id','desc')->paginate(10);
        return view('agent.clinics.locations',compact('page_heading', 'hospital_id', 'hospital', 'list'));

    }

    public function createLocation($hospital_id, $id = '')
    {
        // if (!get_user_permission('hospital', 'u')) {
        //     return redirect()->route('admin.restricted_page');
        // }
        $row = null;
        $hospital = Hospital::find($hospital_id);
        $page_heading = $id ? 'Edit Clinics Location' : 'Create Clinics Location';
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';

        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $row = HospitalLocation::find($id);
        }

        return view('agent.clinics.createLocation', compact('page_heading', 'id', 'hospital_id', 'row'));
    }

    public function saveLocation(Request $request)
    {
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];

        // Validation rules
        $rules = [
            'hospital_id' => 'required|exists:hospitals,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'location' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        $o_data['redirect'] = route('agent.clinics.locations', $request->hospital_id ?? null);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $id = $request->id;
            // dd($request->all());
            // $check = HospitalLocation::where('hospital_id', $request->hospital_id)
            //     ->whereIn('sub_insurance_id', $request->sub_insurance_id)
            //     ->where('id', '!=', $id)
            //     ->first();

            // if ($check) {
            //     $status = "0";
            //     $message = "This insurance policy is already associated with the hospital.";
            //     $errors['sub_insurance_id[]'] = (($check->subInsurance->title ?? null) ? $check->subInsurance->title : 'this').' insurance policy is already associated with this hospital.';
            // } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $insurancePolicy = HospitalLocation::find($id);
                        $insurancePolicy->hospital_id = $request->hospital_id;
                        $insurancePolicy->latitude = $request->latitude;
                        $insurancePolicy->longitude = $request->longitude;
                        $insurancePolicy->location = $request->location;
                        $insurancePolicy->save();

                        DB::commit();
                        $status = "1";
                        $message = "Location updated successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to update Location: " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $insurancePolicy = new HospitalLocation();
                        $insurancePolicy->hospital_id = $request->hospital_id;
                        $insurancePolicy->latitude = $request->latitude;
                        $insurancePolicy->longitude = $request->longitude;
                        $insurancePolicy->location = $request->location;
                        $insurancePolicy->save();

                        DB::commit();
                        $status = "1";
                        $message = "Location added successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to add Location: " . $e->getMessage();
                    }
                }
            // }
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function deleteLocation(REQUEST $request, $id)
    {
        $status = "0";
        $message = "";

        $id = decrypt($id);

        $row = HospitalLocation::where(['id' => $id])->first();

        if ($row) {
            HospitalLocation::where(['id' => $id])->delete();
            $message = "Clinics Location deleted successfully";
            $status = "1";
        } else {
            $message = "Invalid Role data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
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
        $row = Hospital::find($id);
        if ($row) {
            $appointments = DoctorPatientAppointment::where(['hospital_id' => $id])->whereIn('booking_status', ['Pending', 'Confirmed', 'Rescheduled'])->count();
            if ($appointments) {
                $message = "You cannot delete this clinic";
            } else {
                $row->delete();
                User::where('id', $row->user_id)->update(['deleted' => 1]);
                $status = "1";
                $message = "Clinic removed successfully";
            }
        } else {
            $message = "Sorry!.. You cant do this?";
        }
        // return redirect()->route('callcenter.clinics');
       echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }

    public function clinicDetails($id){
        $page_heading="Clinics";
        $module_heading="Clinics";

        $hospital = Hospital::find($id);
        $insurences=[];
        foreach($hospital->insurences as $k){
            if(!isset($insurences[$k->insurance_id])){
                $insurences[$k->insurance_id] = [
                    'insurence_name' => $k->insurance->title
                ];
            }
            $insurences[$k->insurance_id]['sub_insurences'][] = $k->subInsurance->title;
        }
        return view('agent.hospitals.viewclinic',compact('insurences','page_heading','module_heading','hospital'));

    }

     public function getSubInsurence($parent_id) {
        $data = SubInsurencePolicy::get();
        return response()->json($data);
    }
}
?>
