<?php

namespace App\Http\Controllers\admin;

use App\Models\DoctorAppointmentsStatus;
use App\Models\Hospital;
use App\Http\Controllers\Controller;
use App\Models\AgentModal;
use App\Models\AgentModel;
use App\Models\DoctorPatientAppointment;
use App\Models\AgentUserDetail;
use App\Models\CallCenterUserDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\Area;

use App\Models\Doctor;
use App\Models\CountryModel;
use App\Models\Emirate;
use App\Models\User;
use App\Models\HospitalImage;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
class CallCenterController extends Controller
{
    public function index(){
        if (!get_user_permission('call_centers', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Service Center";
        $hospital = null;
        $countries = CountryModel::where('prefix','AE')->where(['active' => 1])->orderBy('name','asc')->get();
        $emirates = Emirate::where('active',1)->get();

        return view('admin.callcenter.index',compact('page_heading', 'hospital', 'countries','emirates'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('call_centers', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = $id ? "Update Service Center" : "Create Service Center";


        //  $country_list =  CountryModel::where(['active'=>1])->whereIn('prefix', ALLOWED_COUNTRIES_PREF)->get();
        $country_list =  CountryModel::where(['active'=>1])->get();
        $emirates_list = [];
        $area_list = [];
        $city_list = [];
        $country_id = '';
        $emirate_id = '';
        $area_id    = '';
        $name       = '';
        $gender    = '';
        $photo = '';
        $phone = '';
        $dial_code = '';
        $email = '';
        $address = '';
        $website='';
        $location='';
        $callcenterDetail = null;
       // $id= '';
        if ($id) {
            $callcenterDetail = CallCenterUserDetail::find($id);

            $user = User::with('callCenterDetails')->where('id', $callcenterDetail->user_id)->get()->first();
         //   dd($user);
            if ($user) {
                $email = $user->email;
                $name       = $user->name;
               // $gender    = $user->agentDetails->gender;
                $area_id    = $user->callCenterDetails->area_id;
                $emirate_id = $user->callCenterDetails->emirate_id;
                $gender = $user->callCenterDetails->gender;
                $country_id = $user->callCenterDetails->country_id;
                $location =$user->callCenterDetails->location;
                $website =$user->callCenterDetails->website;
                $photo = $user->user_img_url;
                $phone = $user->phone;
                $dial_code = $user->dial_code;
                $address = $user->callCenterDetails->address;
            }
        }

//        // get emirates list
//        $emirates_list  = Emirate::where('active', 1)
//            ->when($country_id, fn ($query) => $query->where('country_id', $country_id))
//            ->orderBy("name_en", 'desc')
//            ->get();
//
//            // dd($address);
//        // $emirate_id  = $emirates_list->first()->id ?? 0;
//        $area_list   = Area::where('active', 1)
//            ->when($emirate_id, fn ($query) => $query->where('emirate_id', $emirate_id))
//            ->get();

        return view('admin.callcenter.create', compact(
            'page_heading',
            'id',
            'country_list',
            'emirates_list',
            'area_list',
            'country_id',
            'emirate_id',
            'area_id',
            'email',
            'name',
            'photo',
            'gender',
            'city_list',
            'phone',
            'dial_code',
            'website',
            'address',
            'location',
            'callcenterDetail'
        ));
    }

    public function save(REQUEST $request)
    {
        $message    = "Service Center Added Successfully";
        $o_data['redirect'] = route('admin.callcenter.index');
        //sanitize pone number value
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $sanitizedDirectPhone = preg_replace('/\D/', '', $request->direct_phone ?? null);
        $request->merge(['phone' => $sanitizedPhone, 'direct_phone' => $sanitizedDirectPhone]);

        $rules = [
            'name' => 'required',
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'dial_code'=>'nullable|numeric',
            'phone' => 'nullable|numeric|digits_between:7,12',
            'password' => !$request->id ? 'required' : '',
            'gender' => 'in:male,female',
            'country_id' => 'required:numeric',
            'emirate_id' => 'required:numeric',
            'area_id' => 'required:numeric',
            'address' => 'string',

        ];

        try {

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return response()->error("Validation error occured", $validator->messages());


            // check email
            $id = $request->id;

             $doctorUserId = null;
             if (!empty($id)) {
            $doctorUserId = CallCenterUserDetail::where('id', $id)->value('user_id');
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
            $CallCenterUserDetail=CallCenterUserDetail::where('id',$id)->first();
            // $check_email = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->whereHas('callCenterDetails', function($q) use($id){
            //     $q->where('id', '!=', $id);
            // })->first();

            $check_email = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->where('id', '!=', ($CallCenterUserDetail->user_id ?? null))->first();

            // If email already exists
            if ($check_email){
                return response()->error("Email id already registred with us", ['email' => 'Email id already registred with us']);
            }

            // if dial code and phone number provided then check the phone number
            if ($request->dial_code && $request->phone) {
                // $check_phone = User::where('dial_code', $request->dial_code)->where('phone', $request->phone)->where('id', '!=', $id)->get();

                // if ($check_phone->count() > 0)
                //     return response()->error("Phone number already registred with us", ['phone' => 'Phone number already registred with us']);


                if($CallCenterUserDetail){
                    $check_phone = User::where('dial_code', $request->dial_code)->where('phone', $request->phone)->where('id', '!=', $CallCenterUserDetail->user_id)->exists();
                }else{
                    $check_phone = User::where('dial_code', $request->dial_code)->where('phone', $request->phone)->exists();
                }


                if($check_phone){
                    return response()->error("Phone number already registred with us", ['phone' => 'Phone number already registred with us']);
                }

            }

            // If id is provided then get the user obj else create the user object
            if ($id) {

                $message = "Service Center Updated Successfully";
                $CallCenterUserDetail =   CallCenterUserDetail::find($id);
                $user = User::with("callCenterDetails")->where('id',$CallCenterUserDetail->user_id)->first();
                $user->active    =   1;
                if (!$user)
                    return response()->error("User not found", ['id' => 'User not found']);
            } else {
                $user = new User();
                $user->role = CALL_CENTER_ROLE; //Call Center_ROLE; @todo undefined constant fix it,
               $user->user_type_id = CALL_CENTER_ROLE; // Call Center_USER_TYPE_ID; @todo undefined constant fix it
               $user->active    =   1;
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

            $user->role='4';

            $user->save();

            // ---------------------------------------

            //dd($user->id);
            // ------------ Call Center Data ----------------

            // check if Call Center details already exists in the $user object if yes then use it else new object
            $callcenterDetails = $request->id ? $user->callcenterDetails : new CallCenterUserDetail();


            $callcenterDetails->user_id = $user->id;

            if ($request->gender)
                $callcenterDetails->gender = $request->gender;

            if ($request->country_id)
                $callcenterDetails->country_id = $request->country_id;

            if ($request->emirate_id)
                $callcenterDetails->emirate_id = $request->emirate_id;

            if ($request->area_id)
                $callcenterDetails->area_id = $request->area_id;

            if ($request->address)
                $callcenterDetails->address = $request->address;
            if ($request->location){
                $callcenterDetails->location    = $request->location;
                $callcenterDetails->latitude    = $request->latitude;
                $callcenterDetails->longitude    = $request->longitude;
            }

            if ($request->website)
               $callcenterDetails->website = $request->website;
                //dd($callcenterDetails);

            $callcenterDetails->save();

            DB::commit();

            return response()->success($message, $o_data);
        } catch (Exception $e) {
            DB::rollback();

            return response()->error("Faild to create Service Center " . $e->getMessage());
        }
    }

    public function load_data(REQUEST $request)
    {
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;

        $query = CallCenterUserDetail::query()
            ->join('users', 'users.id', '=', 'callcenter_user_details.user_id')
            ->join('country', 'country.id', '=', 'callcenter_user_details.country_id')
            ->join('emirates', 'emirates.id', '=', 'callcenter_user_details.emirate_id')
            ->join('areas', 'areas.id', '=', 'callcenter_user_details.area_id');

        if ($request->has('search') && ($request->search['filters'] ?? null)) {
            $filters = $request->search['filters'];
            $carbonDate =($filters['to_date'])? Carbon::createFromFormat('d-m-Y', $filters['to_date']):'';

            // Convert the date strings to Carbon instances
        $fromDate =($filters['from_date'])? Carbon::createFromFormat('d-m-Y', $filters['from_date']):'';
        $toDate = $carbonDate;

        // Check if from_date and to_date are the same
        if (!empty($fromDate) && !empty($toDate) &&$fromDate->isSameDay($toDate)) {
            // Add one day to to_date
            $toDate->addDay();

            // Update the filters array
            $filters['to_date'] = $toDate->format('d-m-Y');
        }
            if (!empty($filters['from_date'])) {
                $fromDate = \Carbon\Carbon::createFromFormat('d-m-Y', $filters['from_date'])->startOfDay()->format('Y-m-d');
                $query->where('callcenter_user_details.created_at', '>=', $fromDate);
            }

            if (!empty($filters['to_date'])) {
                if(!$carbonDate->isToday()){
                $toDate = \Carbon\Carbon::createFromFormat('d-m-Y', $filters['to_date'])->endOfDay()->format('Y-m-d');
                $query->where('callcenter_user_details.created_at', '<=', $toDate);
            }
        }

            if (!empty($filters['emirate_id'])) {
                $query->where('callcenter_user_details.emirate_id', $filters['emirate_id']);
            }

            if (!empty($filters['area_id'])) {
                $query->where('callcenter_user_details.area_id', $filters['area_id']);
            }
        }


        $users = $query->select('callcenter_user_details.*', 'users.email','users.name', 'users.dial_code', 'users.phone', 'country.name as country_name', 'emirates.name_en as emirate_name', 'areas.name_en as area_name')
            ->orderBy('callcenter_user_details.id', 'desc');


        return DataTables::eloquent($users)
            ->addColumn('action', function ($user) {
                $action = '<div class="dropdown mt-4 mt-sm-0">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu">';

                // if (get_user_permission('hospitals', 'r')) {
                //     $action .= '<a class="dropdown-item complete-link" href="' . route('admin.hospitals.show', ['id' => $user->id]) . '">View </a>';
                // }
                if (get_user_permission('call_centers', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.callcenter.edit', ['id' => $user->id]) . '">Edit Call Center</a>';
                }
                if (get_user_permission('call_centers', 'd')) {
                    $action .= '<a class="dropdown-item" data-role="unlink"
                            data-message="Do you want to remove the call center? This may be linked with other sections"
                            href="' . route('admin.callcenter.delete', ['id' => encrypt($user->id)]) . '">
                            <i class="flaticon-delete-1"></i> Delete Call Center
                          </a>';
                }
                if (get_user_permission('call_centers', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.hospitals.index', ['call_center_id' => $user->id]).'">Hospital </a>';
                }
                if (get_user_permission('call_centers', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.clinics.index', ['call_center_id' => $user->id]).'">Clinic </a>';
                }
                if (get_user_permission('call_centers', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.doctors.index', ['call_center_id' => $user->id]) . '">Doctors</a>';
                }
                if (get_user_permission('call_centers', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.appointments.index', ['call_center_id' => $user->id]) . '">Total Appointments </a>';
                }
                if (get_user_permission('call_centers', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.callcenter.agent', ['id' => $user->id]) . '">Agents </a>';
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
                                    data-url="'.url('admin/callcenter/change_status').'"
                                    '.($item->user->active == 1 ? 'checked' : '').'>
                    </div>';
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }
    // public function appointments($id) {
    //     if (!get_user_permission('patients', 'c')) {
    //         return redirect()->route('admin.restricted_page');
    //     }
    //     $row = null;
    //     $page_heading = $id ? "Edit Appointment" : "Book Appointment";
    //     $hospital = null;
    //     $doctors = [];
    //     $departments = [];
    //     $members = [];
    //     if ($id) {
    //         $row = DoctorPatientAppointment::with(['user', 'doctor_reschedule_appointments'])->where('id', $id)->first();
    //         $row->booking_date = date('d-m-Y', strtotime($row->booking_date));
    //     }

    //     $hospital_id = $row->hospital_id ?? $hospital_id;

    //     if($hospital_id){
    //         $hospital = Hospital::find($hospital_id);
    //         $departments = $hospital->departments;
    //         $doctors = Doctor::with('user')->where('hospital_id', $hospital_id)->get();
    //     }

    //     if ($row->department_id ?? null) {
    //         $doctors = Doctor::with('user')->whereHas('departments', function ($query) use ($row) {
    //             $query->where('department_id', $row->department_id);
    //         })->get();
    //     }

    //     $patients = User::where('role', USER_ROLE)->where('deleted', 0)->get();

    //     if($row->user_id ?? null){
    //         $members = Members::where('user_id', $row->user_id)->get();
    //     }
    //     // dd($members->toArray());
    //     $time_slot = TIME_SLOTS;
    //     $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
    //     // dd($row->toArray());
    //     return view('admin.hospitals.make_appointment', compact(
    //         'page_heading',
    //         'id',
    //         'patients',
    //         'hospital_id',
    //         'hospital',
    //         'departments',
    //         'doctors',
    //         'time_slot',
    //         'members',
    //         'row'
    //     ));
    // }
    public function appointments($id){
        if (!get_user_permission('doctors', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Appointments";
        $doctor_id = '5';
        $hospital = [];
        $doctors = [];
        $departments = [];
        $patient = User::with('user_role')
        ->where(['users.deleted' => '0', 'role' => '7'])
        ->orderBy('users.id', 'desc')
        ->get();
        $hospitals = Hospital::find($id)->get();
        $hospital = Hospital::find($id);
       // dd($hospital->departments);

            $departments = $hospital->departments;

           // dd($departments);
            $doctors = Doctor::with('user')->where('hospital_id', $id)->get();

        $time_slot = [
            "08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30",
            "13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
            "18:00","18:30","19:00","19:30","20:00"
        ];
        return view('admin.callcenter.appointment',compact('page_heading','hospitals','patient','departments','doctors','time_slot','doctor_id'));

    }
    public function patientAppointmentSave(REQUEST $request){
    //   dd($request->all());
        DB::beginTransaction();
        try {


                $FourDigitRandomNumber = rand(1231,7879);
                $doctor = new DoctorPatientAppointment();
                $doctor->doctor_id     =  (int)$request->doctor_id;
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
                DoctorAppointmentsStatus::create([
                    'appointment_id' => $doctor->id,
                    'status' => 'Created',
                    'changed_by' => Auth::id(),
                    'changed_at' => Carbon::now()
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
    public function hospital(){
        if (!get_user_permission('hospitals', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Hospitals";

        return view('admin.callcenter.hospitals.index',compact('page_heading'));
    }
    public function doctor(){
        if (!get_user_permission('doctors', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Doctors";
        $hospital = null;

        return view('admin.callcenter.doctors.index',compact('page_heading', 'hospital'));
    }
    public function agent($call_center_id='')
    {


        if (!get_user_permission('agents', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Agents";

        // Get the agents list
        $agents = User::with(['agentDetails', 'agentDetails.emirate', 'agentDetails.area', 'agentDetails.country'])
            ->where('user_type_id', 3) // @todo use the AGENT_USER_TYPE_ID
            ->whereHas('agentDetails', function($query) use($call_center_id){
                $query->where('callcenter_id', $call_center_id);
            })
            ->orderBy('id', 'desc')
//            ->get();
            ->paginate(10);

//        dd($agents->first()->agentDetails->emirate->name_en);

        return view('admin.callcenter.agents.index', compact('page_heading', 'agents','call_center_id'));
    }
    public function appointmentLoadData(REQUEST $request){
        $users = DoctorPatientAppointment::query()
        //->where('doctor_patient_appointments.doctor_id','=',$request->doctor_id)
        ->join('users', 'users.id', '=', 'doctor_patient_appointments.user_id')
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
                    $action.='<a class="dropdown-item complete-link" onclick="passDataToViewModel(\'' . $user->booking_id . '\', \'' . $user->id . '\')"  href="'.route('admin.doctors.viewAppointment',['id'=>$user->id]).'">View Appointment</a>';
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
        ->addColumn('sl_no', function($user) {
            static $index = 0;
            return ++$index;
        })
        ->addColumn('name', function($item) {
            return $item->first_name.''.$item->last_name;
        })


        ->toJson();
    }
    public function hospital_load_data(){
        $users = Hospital::query()
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

    public function hospital_create($id=''){
        if (!get_user_permission('hospitals', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        // dd($id);
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
        $page = 'admin.hospitals.create';
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
            'name_ar',
            'userPhoneNumber',
            'appointment_phone',
            'selected_department',
            'trade_licenece'
        ));
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destory($id)
    {
        $status = "0";
        $o_data = [];

        $id = decrypt($id);
        $row = CallCenterUserDetail::find($id);
        $user = User::where('id', $row->user_id)->first();
        if ($row) {
            $agents = AgentUserDetail::where('callcenter_id', $row->id)->with('user')->get();
            foreach ($agents as $agent) {
                $agent->user->user_device_token = "";
                $agent->user->email = $agent->user->email . "__deleted_account_" . $agent->user->id;
                $agent->user->phone = $agent->user->phone . "__deleted_account_" . $agent->user->id;
                $agent->user->deleted = 1;
                $agent->user->access_token = "";
                $agent->user->save();
            }
            $row->delete();
            // User::where('id', $row->user_id)->update(['deleted' => 1]);
            $user->user_device_token = "";
            $user->email = $user->email . "__deleted_account_" . $user->id;
            $user->phone = $user->phone . "__deleted_account_" . $user->id;
            $user->deleted = 1;
            $user->access_token = "";
            $user->update();

            $status = "1";
            $message = "Call Center removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
}
