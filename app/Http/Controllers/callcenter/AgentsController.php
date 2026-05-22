<?php

namespace App\Http\Controllers\callcenter;

use App\Models\Hospital;
use App\Models\Doctor;
use App\Http\Controllers\Controller;
use App\Models\AgentModal;
use App\Models\CallCenterUserDetail;
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
    public function index()
    {


        $page_heading="Agents";
        $module_heading="Agents";

        // Get the agents list
        // $agents = User::with(['agentDetails', 'agentDetails.emirate', 'agentDetails.area', 'agentDetails.country'])
        //    ->where('user_type_id', 3) // @todo use the AGENT_USER_TYPE_ID
        //     ->orderBy('id', 'desc')
        //     ->paginate(10);


          // Get the agents list
        $agents = User::with(['agentDetails', 'agentDetails.emirate', 'agentDetails.area', 'agentDetails.country'])
           ->where('user_type_id', AGENT_ROLE) // @todo use the AGENT_USER_TYPE_ID
            ->orderBy('id', 'desc')
            ->paginate(10);

        // dd($agents);




        return view('callcenter.agents.index', compact('page_heading','module_heading','agents'));
    }

    public function create($id = '')
    {


        $page_heading = $id ? "Edit Agent" : "Add Agent";

        $module_heading="Agents";
       //  $country_list =  CountryModel::where(['active'=>1])->whereIn('prefix', ALLOWED_COUNTRIES_PREF)->get();
       $country_list =  CountryModel::where(['active'=>1])->get();
        $emirates_list = [];
        $area_list = [];
        $city_list = [];
        $country_id = '';
        $emirate_id = '';
        $area_id    = '';
        // $callcenter_id    = '';
        $name       = '';
        $gender    = '';
        $photo = '';
        $phone = '';
        $dial_code = '';
        $email = '';
        $address = '';

         $callcenters = CallCenterUserDetail::with('user')->get();

        if ($id) {

            $agentDetails = AgentUserDetail::where('id',$id)->first();

            $user = User::with('agentDetails')->where('id', $agentDetails->user_id)->get()->first();

            if ($user) {
                $email = $user->email;
                $name       = $user->name;

                $area_id    = $user->agentDetails->area_id;
                $emirate_id = $user->agentDetails->emirate_id;
                $gender = $user->agentDetails->gender;
                $country_id = $user->agentDetails->country_id;
                // $callcenter_id = $user->agentDetails->callcenter_id;
                // $photo = $user->user_img_url;
                $phone = $user->phone;
                if($user->user_image){
                $photo = $user->user_img_url;
                }else{
                 $photo = "";
                }
                $dial_code = $user->dial_code;
                $address = $user->agentDetails->address;
            }
        }

        // get emirates list
//        $emirates_list  = Emirate::where('active', 1)
//            ->when($country_id, fn ($query) => $query->where('country_id', $country_id))
//            ->orderBy("name_en", 'desc')
//            ->get();

        // $emirate_id  = $emirates_list->first()->id ?? 0;
//        $area_list   = Area::where('active', 1)
//            ->when($emirate_id, fn ($query) => $query->where('emirate_id', $emirate_id))
//            ->get();

        return view('callcenter.agents.create', compact(
            'page_heading',
            'id',
            'country_list',
            'emirates_list',
            'callcenters',
            'area_list',
            'country_id',
            'emirate_id',
            'area_id',
            // 'callcenter_id',
            'email',
            'name',
            'photo',
            'gender',
            'city_list',
            'phone',
            'dial_code',
            'address',
            'module_heading'

        ));
    }
    public function save(REQUEST $request)
    {
        //sanitize pone number value
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $request->merge(['phone' => $sanitizedPhone]);

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $message    = "Agent Added Successfully";
        $o_data['redirect'] = route('callcenter.agents');
        $rules = [
            'name' => 'required:string',
            'email' => 'required:email',
            'gender' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'dial_code' => 'required|numeric',
            'phone' => 'required|numeric|digits_between:7,12',
            'password' => !$request->id ? 'required|min:8' : '',
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
            $id  = $request->id;
            // $check_email = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->where('id', '!=', $id)->get();




            // If email already exists
            // if ($check_email->count() > 0)
            //     return response()->error("Email id already registred with us", ['email' => 'Email id already registred with us']);

            // if dial code and phone number provided then check the phone number

            $agent=AgentUserDetail::where('id',$id)->first();
            $check_email = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->where('id', '!=', ($agent->user_id ?? null))->first();

            // If email already exists
            if ($check_email){
                return response()->error("Email id already registred with us", ['email' => 'Email id already registred with us']);
            }


            if ($request->dial_code && $request->phone) {
                $check_phone = User::where('dial_code', $request->dial_code)->where('phone', $request->phone)->where('id', '!=', ($agent->user_id ?? null))->exists();
                if($check_phone){
                    return response()->error("Phone number already registred with us", ['phone' => 'Phone number already registred with us']);
                }
            }


            // If id is provided then get the user obj else create the user object
            if ($id) {

                $message = "Agent Updated Successfully";

               $agent = AgentUserDetail::find($id);
                $user = $agent->user;

                if (!$user)
                    // return response()->error("User not found", ['id' => 'User not found']);
                    $user->role = 3; //AGENT_ROLE; @todo undefined constant fix it,
                    $user->user_type_id = 3; // AGENT_USER_TYPE_ID; @todo undefined constant fix it

            }
            else {

                $user = new User();
                $user->role = 3; //AGENT_ROLE; @todo undefined constant fix it,
                $user->user_type_id = 3; // AGENT_USER_TYPE_ID; @todo undefined constant fix it
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

            $user->save();

            // ---------------------------------------


            // ------------ Agent Data ----------------

            // check if agent details already exists in the $user object if yes then use it else new object
            $agentDetails = $request->id ? $user->agentDetails : new AgentUserDetail();


            $agentDetails->user_id = $user->id;

            if ($request->gender)
                $agentDetails->gender = $request->gender;

                $user_id = Auth::user()->id;
                $callcenter =  CallCenterUserDetail::where('user_id',$user_id)->first();
                $agentDetails->callcenter_id = $callcenter->id;



            if ($request->country_id)
                $agentDetails->country_id = $request->country_id;

            if ($request->emirate_id)
                $agentDetails->emirate_id = $request->emirate_id;

            if ($request->area_id)
                $agentDetails->area_id = $request->area_id;

            if ($request->address)
                $agentDetails->address = $request->address;

                //dd($agentDetails);
                if($id){
                     activity_log('hospiat_created', "Agen $user->name Updated", [
                            'user_id' => $user->name
                        ]);
                }
                else{
                    activity_log('hospiat_created', "Agen $user->name Created", [
                            'user_id' => $user->name
                        ]);
                }
               

                         

            $agentDetails->save();


            // ----------------------------------------

            DB::commit();
            $status = "1";
            $message = "Agents added successfully";

//            return response()->success($message, $o_data);
           echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
} catch (Exception $e) {
            DB::rollback();

            return response()->error("Faild to create agent " . $e->getMessage());
        }
    }

    public function load_data(REQUEST $request)
    {

        // dd($request->all());

        $user_id = Auth::user()->id;
        $callcenter =  CallCenterUserDetail::where('user_id',$user_id)->first();
        $users = User::with(['agentDetails'])
            ->where('callcenter_id',$callcenter->id)
            ->join('agent_user_details', 'users.id', '=', 'agent_user_details.user_id')
            ->Join('country', 'country.id', '=', 'agent_user_details.country_id')
            ->Join('emirates', 'emirates.id', '=', 'agent_user_details.emirate_id')
            ->Join('areas', 'areas.id', '=', 'agent_user_details.area_id')
            // ->select('users.*','agent_user_details.status as status','emirates.name_en as emirate_name','areas.name_en as area_name' , 'country.name as country_name', 'emirates.name_en as emirate_name','agent_user_details.id')
            ->select('users.*','users.active as status','emirates.name_en as emirate_name','areas.name_en as area_name' , 'country.name as country_name', 'emirates.name_en as emirate_name','agent_user_details.id')
            ->orderBy('agent_user_details.id', 'desc');


        return DataTables::eloquent($users)
            ->addColumn('action', function ($user) {
                $action = '<div class="dropdown mt-4 mt-sm-0">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu">';


                    $action .= '<a class="dropdown-item complete-link" href="' . route('callcenter.agents.edit', ['id' => $user->id]) . '">Edit Agent </a>';
                    $action.='<a class="dropdown-item" data-role="unlink"
                    data-message="Do you want to remove the agent?  This may be linked with other sections"
                    href="'.route('callcenter.agents.delete', ['id' => encrypt($user->id)]).'">
                    <i class="flaticon-delete-1"></i> Delete Agent
                  </a>';
                    $action .= '<a class="dropdown-item complete-link" href="' . route('callcenter.totalappointments') . '">View Appointments </a>';

                    // $action .= '<a class="dropdown-item complete-link" href="' . route('callcenter.totalappointments', ['id' => $user->id]) . '">View Appointments </a>';

                $action .= '</div>
            </div>';

                return $action;
            })
            ->addColumn('sl_no', function ($user) {
                static $index = 0;
                return ++$index;
            })
            ->addColumn('phone_number', function ($item) {
                return '+' . $item->dial_code . $item->phone;
            })
            // ->addColumn('name', function ($item) {
            //     return '<div class="flex-shrink-0 me-3">
            //                 <img class="rounded-circle avatar-sm" src="' . asset($item->user_img_url) . '" /> Agent 1
            //             </div>' . $item->name;
            // })


            ->toJson();
    }
  public function appointments($id){
    $page_heading="Appointments";
    $agent_id = '3';
    $patient = User::with('user_role')
    ->where(['users.deleted' => '0', 'role' => '7'])
    ->orderBy('users.id', 'desc')
    ->get();
   $id = '1';
       $hospitals = Hospital::find($id)->get();

       $hospital = Hospital::with('departments')->find($id);

       // Check if hospital with given $id exists
       if (!$hospital) {
           abort(404, 'Hospital not found');
       }

       // Retrieve departments for the hospital
       $departments = $hospital->departments;
//dd($departments);
            $doctors = Doctor::with('user')->where('hospital_id', $id )->get();
           // dd($doctors[0]->user);
    $time_slot = [
        "08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30",
        "13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
        "18:00","18:30","19:00","19:30","20:00"
    ];
    return view('callcenter.agents.appointment',compact('page_heading','hospitals','patient','departments','doctors','time_slot','agent_id'));



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
                $doctor->booking_status   = "pending";
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
      // dd($request->agent_id);
        $users = DoctorPatientAppointment::with([ 'user','doctor.user','agent.user'])
        ->where('doctor_patient_appointments.agent_id','=',$request->agent_id)
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
                 if(strtolower($user->booking_status) !== 'rescheduled'){
                $action.='<a class="dropdown-item reschdule-link" href="#!" data-bs-toggle="modal" onclick="passDataToRescheduleModel(\'' . $user->booking_id . '\', \'' . $user->doctor->user_id . '\',\'' . $user->booking_time_slot . '\',\'' . $user->booking_date . '\')"
                data-bs-target="#reschedule-modal">Reschedule Appointment</a>';
                 }
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
    public function viewAppointment($id){

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
        return view('callcenter.agents.patientAppointment.viewAppointment',compact('page_heading','agent','doctor','booking_time_slot','users','time_slot','doctor_id'));
    }
    public function agentStatus(REQUEST $request){
        $request->validate([
            'status' => 'required|in:1,0'
        ]);
       $id = $request->input('agentId');


        // $agent = AgentUserDetail::find($id);
        $agent = AgentUserDetail::find($id);

        // dd($agent->user->active);
        $user = user::find($agent->user_id);

        // dd($user);

        $user->active = $request->input('status');
        // $agent->status = $request->input('status');
        $user->save();

        // Return response
        return response()->json(['message' => 'Agent status updated successfully.']);
    }
    public function destory($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];

        $id = decrypt($id);
        $row = AgentUserDetail::find($id);
        if ($row) {
            $row->delete();
            User::where('id', $row->user_id)->update(['deleted' => 1]);
            $status = "1";
            $message = "Agent removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }
        // return redirect()->route('callcenter.agents');
       echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
    }

