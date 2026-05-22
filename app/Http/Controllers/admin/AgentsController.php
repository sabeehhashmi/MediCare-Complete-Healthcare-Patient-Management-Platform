<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hospital;
use App\Models\Doctor;
use App\Http\Controllers\Controller;
use App\Models\AgentModal;
use App\Models\AgentModel;
use App\Models\DoctorPatientAppointment;
use App\Models\AgentUserDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\SpecialIntrests;
use App\Models\Languages;
use App\Models\LicenceType;
use App\Models\HospitalLocation;
use App\Models\Qualifications;
use App\Models\DoctorIntrests;
use App\Models\DoctorAvailability;
use App\Models\DoctorLanguageSpoken;
use App\Models\DoctorQualifications;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Validator;
use App\Models\Area;
use App\Models\CountryModel;
use App\Models\CallCenterUserDetail;
use App\Models\Emirate;
use App\Models\User;
use App\Models\HospitalImage;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Exception;
use App\Models\DoctorSpecialities;
use Illuminate\Support\Facades\DB;

class AgentsController extends Controller
{
    public function index(REQUEST $request)
    {
        if (!get_user_permission('agents', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Agents";

        // Get the agents list
        $agents = User::with(['agentDetails', 'agentDetails.emirate', 'agentDetails.area', 'agentDetails.country'])
            ->where('role', AGENT_ROLE) // @todo use the AGENT_USER_TYPE_ID
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.agents.index', compact('page_heading', 'agents'));
    }

    public function create($id = '')
    {
         // $user = AgentUserDetail::where('id', $id)->first();
            // dd($user);

        if (!get_user_permission('agents', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = $id ? "Update Agent" : "Create Agent";


        $country_list =  CountryModel::where(['active' => 1])->get();
        $emirates_list = [];
        $area_list = [];
        $city_list = [];
        $country_id = '';
        $callcenter_id = '';
        $emirate_id = '';
        $area_id    = '';
        $name       = '';
        $gender    = '';
        $photo = '';
        $phone = '';
        $dial_code = '';
        $email = '';
        $address = '';


        $callcenters = CallCenterUserDetail::with('user')
        ->whereHas('user', function ($query) {
            $query->where('active', 1);
        })
        ->get();



        if ($id) {
            $user = AgentUserDetail::with('user')->where('id', $id)->first();
            // dd($user);
            if ($user) {
                $email = $user->user->email;
                $name       = $user->user->name;
                $gender    = $user->gender;
                $area_id    = $user->area_id;
                $emirate_id = $user->emirate_id;
                $country_id = $user->country_id;
                $callcenter_id = $user->callcenter_id;
                // if($user->user_img_url){
                // $photo = $user->user->user_img_url;
                if($user->user->user_image){
                $photo = $user->user->user_img_url;
                }else{
                 $photo = "";
                }
                $phone = $user->user->phone;
                $dial_code = $user->user->dial_code;
                $address = $user->address;
            }

            // dd($phone);

        }

        // get emirates list
//        $emirates_list  = Emirate::where('active', 1)
//            ->when($country_id, fn ($query) => $query->where('country_id', $country_id))
//            ->orderBy("name_en", 'desc')
//            ->get();
//
//        // $emirate_id  = $emirates_list->first()->id ?? 0;
//        $area_list   = Area::where('active', 1)
//            ->when($emirate_id, fn ($query) => $query->where('emirate_id', $emirate_id))
//            ->get();

            // dd($emirate_id,$area_id);

            // dd($photo);

        return view('admin.agents.create', compact(
            'page_heading',
            'id',
            'country_list',
            'callcenters',
            'emirates_list',
            'area_list',
            'country_id',
            'callcenter_id',
            'emirate_id',
            'area_id',
            'email',
            'name',
            'photo',
            'gender',
            'city_list',
            'phone',
            'dial_code',
            'address'

        ));
    }
    public function save(REQUEST $request)
    {

        // dd($request->all());
        $message    = "Agent Added Successfully";
        $o_data['redirect'] = $request->has('call_center_redirect') ? route('admin.callcenter.agent', ['id' => $request->callcenter_id]) : route('admin.agents.index');
        //sanitize pone number value
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $sanitizedDirectPhone = preg_replace('/\D/', '', $request->direct_phone ?? null);
        $request->merge(['phone' => $sanitizedPhone, 'direct_phone' => $sanitizedDirectPhone]);



        $rules = [
            'name' => 'required',
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            // 'image.*' => 'mimes:jpeg,png,jpg|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'dial_code' => 'nullable|numeric',
            'phone' => 'nullable|numeric|digits_between:7,12',
            'password' => !$request->id ? 'required' : '',
            'gender' => 'numeric',
            'country_id' => 'required:numeric',
            'emirate_id' => 'required:numeric',
            'callcenter_id' => 'required:numeric',
            'area_id' => 'required:numeric',
            'address' => 'string',

        ];

        try {

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return response()->error("Validation error occured", $validator->messages());


            // check email
            $id  = $request->id;




            // $check_email = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->whereHas('agent', function($q) use($id){
            //     $q->where('id', '!=', $id);
            // })->first();
        $agent=AgentUserDetail::where('id',$id)->first();
        $doctorUserId = null;
             if (!empty($id)) {
            $doctorUserId = AgentUserDetail::where('id', $id)->value('user_id');
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


            // If email already exists
            if ($check_email){
                return response()->error("Email id already registred with us", ['email' => 'Email id already registred with us']);
            }



            // if dial code and phone number provided then check the phone number
            if ($request->dial_code && $request->phone) {
                // $check_phone = User::where('dial_code', $request->dial_code)->where('phone', $request->phone)->where('id', '!=', $agent->user_id)->get();

                if($agent){
                    $check_phone = User::where('dial_code', $request->dial_code)->where('phone', $request->phone)->where('id', '!=', $agent->user_id)->exists();
                }else{
                    $check_phone = User::where('dial_code', $request->dial_code)->where('phone', $request->phone)->exists();
                }


                if($check_phone){
                    return response()->error("Phone number already registred with us", ['phone' => 'Phone number already registred with us']);
                }
            }








            if ($id) {

                $message = "Agent Updated Successfully";

                $agent = AgentUserDetail::find($id);
                $user = $agent->user;

                    if (!$user){
                    // return response()->error("User not found", ['id' => 'User not found']);
                    $user->role = 3; //AGENT_ROLE; @todo undefined constant fix it,
                    $user->user_type_id = 3; // AGENT_USER_TYPE_ID; @todo undefined constant fix it

                    }
                } else {
                    $user = new User();
                    $user->role = 3; //AGENT_ROLE; @todo undefined constant fix it,
                    $user->user_type_id = 3;
                }


                // If image provided then upload the image
                if ($request->hasfile('image')) {
                    $file = $request->file('image');
                    $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                    $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                    $user->user_image = $file_name;
                }


            DB::beginTransaction();

            // ------------ User Data ----------------

            if ($request->name)
                $user->name = $request->name;

            if ($request->email)
                $user->email = strtolower($request->email);

            if ($request->dial_code)
                $user->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;

            if ($request->phone)
                $user->phone = str_replace(" ", "", ltrim($request->phone, "0"));

            if ($request->password)
                $user->password = Hash::make($request->password);

                $user->active = 1;
            $user->save();

            // ---------------------------------------


            // ------------ Agent Data ----------------

            // check if agent details already exists in the $user object if yes then use it else new object
            $agentDetails = $request->id ? $user->agentDetails : new AgentUserDetail();

            // dd($agentDetails);
            $agentDetails->user_id = $user->id;

            if ($request->gender)
                $agentDetails->gender = $request->gender;

            if ($request->country_id)
                $agentDetails->country_id = $request->country_id;

            if ($request->callcenter_id)
                $agentDetails->callcenter_id = $request->callcenter_id;

            if ($request->emirate_id)
                $agentDetails->emirate_id = $request->emirate_id;

            if ($request->area_id)
                $agentDetails->area_id = $request->area_id;

            if ($request->address)
                $agentDetails->address = $request->address;

                // dd($agentDetails);

            $agentDetails->save();

            // ----------------------------------------

            DB::commit();

            return response()->success($message, $o_data);
        } catch (Exception $e) {
            DB::rollback();

            return response()->error("Faild to create agent " . $e->getMessage());
        }
    }

    public function load_data(REQUEST $request)
    {
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;

        $users = AgentUserDetail::query()->with('call_center')->whereHas('call_center')
            ->join('users', 'users.id', '=', 'agent_user_details.user_id')
            ->join('country', 'country.id', '=', 'agent_user_details.country_id')
            ->join('emirates', 'emirates.id', '=', 'agent_user_details.emirate_id')
            ->join('areas', 'areas.id', '=', 'agent_user_details.area_id')
            ->select('agent_user_details.*', 'users.email','users.name', 'users.dial_code', 'users.phone', 'country.name as country_name', 'emirates.name_en as emirate_name', 'areas.name_en as area_name')
            ->orderBy('agent_user_details.id', 'desc');

        return DataTables::eloquent($users)
            ->addColumn('call_center_name', function ($user) {
                return !empty($user->call_center) ? $user->call_center->user->name : 'N/A';
            })
            ->addColumn('action', function ($user) {
                $action = '<div class="dropdown mt-4 mt-sm-0">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu">';

                if (get_user_permission('agents', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.agents.edit', ['id' => $user->id]) . '">Edit Agent</a>';
                }
                if (get_user_permission('agents', 'd')) {
                    $action .= '<a class="dropdown-item" data-role="unlink"
                            data-message="Do you want to remove the agent? This may be linked with other sections"
                            href="' . route('admin.agents.delete', ['id' => encrypt($user->id)]) . '">
                            <i class="flaticon-delete-1"></i> Delete Agent
                          </a>';
                }
                if (get_user_permission('agents', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.hospitals.index', ['agent_id' => $user->id]).'">Hospital </a>';
                }
                if (get_user_permission('agents', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.clinics.index', ['agent_id' => $user->id]).'">Clinic </a>';
                }
                if (get_user_permission('agents', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.doctors.index', ['agent_id' => $user->id]) . '">Doctors</a>';
                }
                if (get_user_permission('agents', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.appointments.index', ['agent_id' => $user->id]) . '">Total Appointments </a>';
                }

                $action .= '</div>
            </div>';

                return $action;
            })
            ->addColumn('sl_no', function($user) use (&$startIndex) {
                return ++$startIndex;
            })
            ->addColumn('phone_number', function ($item) {
                return  ($item->phone)?'+' . $item->dial_code . $item->phone:'';
            })
            ->addColumn('status', function($item) {
                return '<div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                        <input type="checkbox" class="form-check-input change_status" data-id="'.$item->user_id.'"
                                    data-url="'.url('admin/agents/change_status').'"
                                    '.($item->user->active == 1 ? 'checked' : '').'>
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
  public function appointments($id){
    if (!get_user_permission('doctors', 'r')) {
        return redirect()->route('admin.restricted_page');
    }
    $page_heading="Appointments";
    $agent_id = '3';
    $patient = User::with('user_role')
    ->where(['users.deleted' => '0', 'role' => '7'])
    ->orderBy('users.id', 'desc')
    ->get();
   $id = '1';
    $hospitals = Hospital::find($id)->get();
   // dd($hospital);
        $hospital = Hospital::find($id);
       // dd($hospital->departments);
            $departments = isset($hospital->departments)?$hospital->departments:'';
            $doctors = Doctor::with('user')->where('hospital_id', $id)->get();
    $time_slot = [
        "08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30",
        "13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
        "18:00","18:30","19:00","19:30","20:00"
    ];
    return view('admin.agents.appointment',compact('page_heading','hospitals','patient','departments','doctors','time_slot','agent_id'));



  }
  public function hospital($id){
    if (!get_user_permission('hospitals', 'r')) {
        return redirect()->route('admin.restricted_page');
    }
    $agent_id = $id;
    $page_heading="Hospitals";

    return view('admin.agents.hospitals.index',compact('page_heading','agent_id'));
}
public function doctor($id){
    if (!get_user_permission('doctors', 'r')) {
        return redirect()->route('admin.restricted_page');
    }
    $page_heading="Doctors";
    $hospital = null;
    $agent_id = $id;
    return view('admin.agents.doctors.index',compact('page_heading', 'agent_id','hospital'));
}
public function doctor_load_data(REQUEST $request){
    $users = Doctor::query();
    $users->leftJoin('users', 'users.id', '=', 'doctors.user_id')
    ->where('doctors.agent_id', $request->agent_id)
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
                $action.='<a class="dropdown-item complete-link" href="'.route('admin.doctors.edit',['hospital_id' => $user->hospital_id, 'id'=>$user->id]).'">Edit </a>';
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
        return '+'.$item->dial_code.$item->phone;
    })


    ->toJson();
}
public function doctorCreate($agent_id = null, $id=''){
    if (!get_user_permission('doctors', 'c')) {
        return redirect()->route('admin.restricted_page');
    }
    if($agent_id){
        $agent_id = $agent_id;
    }

    $page_heading="Create Doctor";
    $country_list =  CountryModel::where(['active'=>1])->get();
    $emirates_list=[];
    $department_list=[];
    $selected_departments = [];
    $area_list = [];
    $country_id = '';
    $emirate_id = '';
    $area_id    = '';
    $first_name = '';
    $hospital_name = Hospital::get();
    $last_name  = '';
    $qualification = Qualifications::where(['status'=>1])->get();
    $specialty = Specialty::where(['active'=>1])->get();
    $special_interest = SpecialIntrests::where(['status'=>1])->get();
    $experiences = '';
    $license_no = '';
    $license_no_moh = '';
    $license_no_doh = '';
    $license_no_dhcc = '';
    // $license_type = LicenceType::where(['status'=>1])->get();
    $qualification_id = '';
    // $license_type_id = '';
    $language_spoken_id = '';
    $speciality_id = '';
    $special_intrest_id = '';
    $language_spoken = Languages::where(['status'=>1])->get();
    $gender = '';
    $phone = '';
    $email = '';
    $profile_bio = '';
    $hospital_id = '';
    $direct_phone = '';
    $direct_dial_code='';
    $dial_code='';
    $doctor = null;
    $hospital = null;
    $user= null;

        $country_id     = 229; //$country_list->first()->id??0;
        $emirates_list  = Emirate::where(['active'=>1,'country_id'=>$country_id])->get();
        $emirate_id     = $emirates_list->first()->id??0;
        $area_list      = Area::where(['active'=>1,'emirate_id'=>$emirate_id])->get();



    return view('admin.agents.doctors.create',compact(
        'page_heading',
        'id',
        'agent_id',
        'country_list',
        'emirates_list',
        'department_list',
        'selected_departments',
        'area_list',
        'country_id',
        'emirate_id',
        'area_id',
        'hospital_id',
        'hospital',
        'hospital_name',
        'first_name',
        'last_name',
        'dial_code',
        'qualification_id',
        // 'license_type_id',
        'language_spoken_id',
        'speciality_id',
        'special_intrest_id',
        'qualification',
        'specialty',
        'special_interest',
        'experiences',
        'license_no',
        'license_no_moh',
        'license_no_doh',
        'license_no_dhcc',
        // 'license_type',
        'language_spoken',
        'gender',
        'phone',
        'email',
        'profile_bio',
        'direct_phone',
        'direct_dial_code',
        'doctor',
        'user'
    ));
}
public function doctorSave(REQUEST $request)
{
  //  dd($request->all());
    $status     = "0";
    $message    = "";
    $o_data     = [];
    $errors     = [];
    $o_data['redirect'] = route('admin.agents.doctors', $request->agent_id);
    $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        'dial_code'=>'numeric',
        'phone'=>'numeric|digits_between:8,12',
        'hospital_id'=>'required|numeric|exists:hospitals,id',
        'license_no_dha' => 'nullable|alpha_num',
        'license_no_moh' => 'nullable|alpha_num',
        'license_no_doh' => 'nullable|alpha_num',
        'license_no_dhcc' => 'nullable|alpha_num',
        'departments' => 'required|array|min:1',
        'departments.*' => 'exists:departments,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    $validator = Validator::make($request->all(), $rules);


    $validator->after(function ($validator) use ($request) {
        if (!$request->license_no_dha && !$request->license_no_moh && !$request->license_no_doh) {
            $validator->errors()->add('license_no_dha', 'At least one license number is required.');
            $validator->errors()->add('license_no_moh', 'At least one license number is required.');
            $validator->errors()->add('license_no_doh', 'At least one license number is required.');
        }
    });

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
                    $name =  $request->first_name.''.$request->last_name;
                    $language_spoken_id = $request->language_spoken_id;
                    $qualification_id = $request->qualification;
                    $speciality_id = $request->specialty;
                    $special_intrest_id = $request->special_interest;
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
                    $user->password  =   Hash::make($request->password);
                    $user->role      =   DOCTOR_ROLE;
                    $user->active    =   1;
                    $user->created_by = Auth::user()->id;
                    $user->last_updated_by = Auth::user()->id;
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    $user->save();

                    $doctor   = Doctor::find($id);
                    $doctor->user_id   = $user->id;
                    $doctor->country_id = $request->country;
                    $doctor->hospital_id = $request->hospital_id;
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
                    $doctor->doctorQualifications()->delete();
                    $doctor->doctorSpecialities()->delete();
                    $doctor->doctorIntrests()->delete();
                    $doctor->doctorLanguageSpoken()->delete();
                    foreach ($language_spoken_id as $language_spoken) {
                        $doctorLanguageSpoken = new DoctorLanguageSpoken;
                        $doctorLanguageSpoken->doctor_id = $doctor->id;
                        $doctorLanguageSpoken->language_spoken_id = (int)$language_spoken;
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
                        $doctorSpeciality->speciality_id =  (int)$speciality;
                        $doctorSpeciality->save();
                        $doctor->doctorSpecialities()->save($doctorSpeciality);
                    }
                    foreach ($special_intrest_id as $language_intrest) {
                        $doctorIntrest = new DoctorIntrests;
                        $doctorIntrest->doctor_id = $doctor->id;
                        $doctorIntrest->special_intrest_id = (int)$language_intrest;
                        $doctor->doctorIntrests()->save($doctorIntrest);
                    }

                    DB::commit();
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
                    $name =  $request->first_name.''.$request->last_name;
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
                    $user->password  =   Hash::make($request->password);
                    $user->role      =   DOCTOR_ROLE;
                    $user->active    =   1;
                    $user->created_by = Auth::user()->id;
                    $user->last_updated_by = Auth::user()->id;
                    $user->created_at = gmdate('Y-m-d H:i:s');
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    $user->save();

                    $doctor =  new Doctor();
                    $doctor->user_id   = $user->id;
                    $doctor->agent_id = $request->agent_id;
                    $doctor->country_id = $request->country;
                    $doctor->hospital_id = $request->hospital_id;
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
                    $status = "1";
                    $message = "Doctor Added Successfully";
                } catch (EXCEPTION $e) {
                    DB::rollback();
                    $message = "Faild to create hospital " . $e->getMessage();
                }
            }
        }
    }

    echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
}

public function hospital_load_data(REQUEST $request){
    $users = Hospital::query()
    ->leftJoin('users', 'users.id', '=', 'hospitals.user_id')
    ->where('agent_id',$request->agent_id)
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

            if (get_user_permission('hospitals', 'u')) {
                $action.='<a class="dropdown-item complete-link" href="'.route('admin.hospitals.edit',['id'=>$user->id]).'">Edit </a>';
            }

            if (get_user_permission('departments', 'r')) {
                $action.='<a class="dropdown-item complete-link" href="'.route('admin.hospitals.departments',['id'=>$user->id]).'">Departments </a>';
            }
            if (get_user_permission('hospitals', 'r')) {
                $action.='<a class="dropdown-item complete-link" href="'.route('admin.hospitals.appointments',['id'=>$user->id]).'">Appointments </a>';
            }
            if (get_user_permission('insurence_policy', 'r')) {
                $action.='<a class="dropdown-item complete-link" href="'.route('admin.hospitals.insurances',['id'=>$user->id]).'">Our Insurance </a>';
            }
            if (get_user_permission('hospitals', 'u')) {
                $action.='<a class="dropdown-item complete-link" href="'.route('admin.hospitals.locations',['id'=>$user->id]).'">Our Locations </a>';
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
        return '+'.$item->dial_code.$item->phone;
    })


    ->toJson();
}

public function hospitalCreate($agent_id = null,$id=''){
    if (!get_user_permission('hospitals', 'c')) {
        return redirect()->route('admin.restricted_page');
    }
     $agent_id = $agent_id;
    $page_heading="Create Hospital";

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
    $page = 'admin.agents.hospitals.create';
    $selected_department = [];
    // $country_id     = 229; //$country_list->first()->id ?? 0;
    // $emirates_list  = Emirate::where(['active'=>1,'country_id'=>$country_id])->get();
    // $emirate_id     = $emirates_list->first()->id ?? 0;
    // $area_list      = Area::where(['active'=>1,'emirate_id'=>$emirate_id])->get();

    if($id){
        $hospital = Hospital::where('id',$id)->first();

        $page_heading = "Update Hospital";
        // if($hospital){
        //     $name       = $hospital->name_en;
        //     $area_id    = $hospital->area_id;
        //     $emirate_id = $hospital->emirate_id;
            $country_id = $hospital->country_id;
            $selected_departments_data = $hospital->departments->toArray();
            if(count($selected_departments_data)){
                $selected_department = array_keys(mapArrayByIndex($selected_departments_data, 'id'));
            }

            $userPhoneNumber = $hospital->user->phone ? '+'.$hospital->user->dial_code.$hospital->user->phone : '';
            $appointment_phone = $hospital->appointment_phone ? '+'.$hospital->appointment_dial_code.$hospital->appointment_phone : '';
            $country_id = $hospital->country_id;
        //     $name_ar    = $hospital->name_ar;
        //     $trade_licenece = $hospital->trade_licence_url;
        // }
    }
    // dd($hospital->images->toArray());
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
        'agent_id',
        'name_ar',
        'userPhoneNumber',
        'appointment_phone',
        'selected_department',
        'trade_licenece'
    ));
}

public function HospitalSave(REQUEST $request)
{
    //dd($request->agent_id);
    $status     = "0";
    $message    = "";
    $o_data     = [];
    $errors     = [];
    // dd($request->all());
    $o_data['redirect'] = route('admin.agents.hospital',$request->agent_id);
    $rules = [
        'name_en' => 'required',
        'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        'image.*' => 'mimes:jpeg,png,pdf|max:2048',
        'trade_licenece' => 'mimes:jpg,jpeg,png,pdf|max:2048',
        // 'department' => 'required|array|min:1',
        // 'department.*' => 'exists:departments,id',
        'dial_code'=>'numeric',
        'direct_dial_code'=>'numeric',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $status = "0";
        $message = "Validation error occurred";
        $errors = $validator->messages();
    } else {
        $id = $request->id;
        $check_email = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->where('id', '!=', $id)->first();

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
                    $hospital = Hospital::where('id', $id)->first();
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

                    if ($file = $request->file("trade_licenece")) {
                        $file_name = time().uniqid().".".$file->getClientOriginalExtension();
                        $file->storeAs(config('global.trade_licenece_image_upload_dir'), $file_name, config('global.upload_bucket'));
                        $hospital->trade_licenece = $file_name;
                    }

                    if ($request->hasfile('images')) {
                        // Delete existing images
                        HospitalImage::where('hospital_id', $hospital->id)->delete();

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

                    DB::commit();
                    $status = "1";
                    $message = "Hospital updated successfully";
                } else {
                    // dd($request->all());
                    // Create logic
                    $user = new User();
                    $user->email    = strtolower($request->email);
                    $user->name     = $request->name_en;
                    $user->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                    $user->phone     = str_replace(" ","",ltrim($request->phone,"0"));
                    $user->password  = Hash::make($request->password);
                    $user->role      = HOSPITAL_ROLE;
                    $user->active    = 1;
                    $user->created_by = Auth::user()->id;
                    $user->last_updated_by = Auth::user()->id;
                    $user->created_at = gmdate('Y-m-d H:i:s');
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    $user->role_id     = 0;
                    $user->deleted = 0;
                    $user->save();

                    $hospital = new Hospital();
                    $hospital->user_id   = $user->id;
                    $hospital->agent_id  = $request->agent_id;
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





                    DB::commit();
                    $status = "1";
                    $message = "Hospital added successfully";
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
                dd($e);
                $message = $id ? "Failed to update hospital " . $e->getMessage() : "Failed to create hospital " . $e->getMessage();
            }
        }
    }

    echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
}
public function patientAppointmentSave(REQUEST $request){
    //   dd($request->all());
        DB::beginTransaction();
        try {


                $FourDigitRandomNumber = rand(1231,7879);
                $doctor = new DoctorPatientAppointment();
                $doctor->doctor_id     =  (int)$request->doctor_id;
                $doctor->agent_id     =  (int)$request->agent_id;
                $doctor->user_id     =   (int)$request->patient_id;
                $doctor->booking_id    = '#MYDW'.$FourDigitRandomNumber;

                $doctor->hospital_id  =  (int)$request->hospital_id;
                $doctor->department_id = (int)$request->department_id ?? null;
                $doctor->booking_date = $request->booking_date;
                $doctor->booking_time_slot    =  $request->booking_time_slot;
                $doctor->booking_status   = BOOKING_STATUS_PENDING;
                $doctor->created_at = gmdate('Y-m-d H:i:s');
                $doctor->updated_at = gmdate('Y-m-d H:i:s');
                $doctor->save();
                if (!$doctor->save()) {
                    // Output validation errors
                    dd($doctor->getErrors()->toArray());
                }
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
    public function appointmentLoadData(REQUEST $request){
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;

        $users = DoctorPatientAppointment::query()
        ->where('doctor_patient_appointments.agent_id','=',$request->agent_id)
        ->leftJoin('users', 'users.id', '=', 'doctor_patient_appointments.user_id')
        ->select( 'users.first_name','users.last_name','doctor_patient_appointments.booking_id' ,'doctor_patient_appointments.booking_status','doctor_patient_appointments.booking_date','doctor_patient_appointments.id','doctor_patient_appointments.booking_time_slot')
        ->orderBy('doctor_patient_appointments.id','desc');

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
                    $action.='<a class="dropdown-item complete-link" onclick="passDataToViewModel(\'' . $user->booking_id . '\', \'' . $user->id . '\')"  href="'.route('admin.agents.viewAppointment',['id'=>$user->id]).'">View Appointment</a>';
                }
                if (get_user_permission('doctors', 'u')) {
                    $action .= '<a class="dropdown-item cancel-link" href="#!" data-bs-toggle="modal" onclick="passDataToCancelModel(\'' . $user->booking_id . '\', \'' . $user->id . '\')" data-bs-target="#cancel-appointment">Cancel Appointment</a>';
                }
                if (get_user_permission('doctors', 'u')) {
                    $action.='<a class="dropdown-item accept-link" href="#!" data-bs-toggle="modal" onclick="passDataToConfirmModel(\'' . $user->booking_id . '\', \'' . $user->id . '\')" data-bs-target="#confirm-appointment">Confirm Appointment</a>';
                }
                if (get_user_permission('doctors', 'u')) {
                    $action.='<a class="dropdown-item reschdule-link" href="#!" data-bs-toggle="modal" onclick="passDataToRescheduleModel(\'' . $user->booking_id . '\', \'' . $user->id . '\',\'' . $user->booking_time_slot . '\',\'' . $user->booking_date . '\')"
                     data-bs-target="#reschedule-modal">Reschedule Appointment</a>';
                }
                if (get_user_permission('doctors', 'u')) {
                    $action.='<a class="dropdown-item followup-link" href="#!" data-bs-toggle="modal" onclick="passDataToCompletedModel(\'' . $user->booking_id . '\', \'' . $user->id . '\')" data-bs-target="#completed-appointment">Complete Appointment</a>';
                }

           $action .='</div>
            </div>';

            return $action;
        })
        ->addColumn('sl_no', function($user) use (&$startIndex) {
            return ++$startIndex;
        })
        ->addColumn('name', function($item) {
            return $item->first_name.''.$item->last_name;
        })


        ->toJson();
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
    $agent_id = $users[0]['agent_id'];
     $agent = AgentUserDetail::find($agent_id);
    $doctor = User::find($doctor_id);
    //dd($agent->user);
    $booking_time_slot = (array)$users[0]['booking_time_slot'];

    $time_slot = [
        "08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30",
        "13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
        "18:00","18:30","19:00","19:30","20:00"
    ];
        return view('admin.agents.patientAppointment.viewAppointment',compact('page_heading','agent','doctor','booking_time_slot','users','time_slot','doctor_id'));
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

        $agent = AgentUserDetail::find($id);
        $user = User::where('id', $agent->user_id)->first();
        if ($agent) {
            $agent->delete();
            // User::where('id', $agent->user_id)->update(['deleted' => 1]);
            $user->user_device_token = "";
            $user->email = $user->email . "__deleted_account_" . $user->id;
            $user->phone = $user->phone . "__deleted_account_" . $user->id;
            $user->deleted = 1;
            $user->access_token = "";
            $user->update();

            $status = "1";
            $message = "Agent removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
}
