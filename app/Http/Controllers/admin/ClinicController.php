<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ClinicExport;
use App\Models\DoctorAppointmentsStatus;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator,DB;
use App\Models\Area;
use App\Models\CountryModel;
use App\Models\DepartmentModel;
use App\Models\Emirate;
use App\Models\User;
use App\Models\HospitalImage;
use App\Models\DepartmentHospital;
use App\Models\DoctorPatientAppointment;
use App\Models\HospitalInsurancePolicy;
use App\Models\Members;
use App\Models\InsurencePolicy;
use App\Models\HospitalLocation;
use Illuminate\Support\Facades\Hash;
use DataTables;
use App\Mail\ActivateAccountEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Pest\Mutate\Mutators\ControlStructures\ElseIfNegated;

class ClinicController extends Controller
{
    public function index(){

        if (!get_user_permission('clinics', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Clinics";

        $emirates = Emirate::where('active',1)->orderBy('name_en','asc')->get();

        return view('admin.clinics.index',compact('page_heading','emirates'));
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        // dd($filters);
        $exporter = new ClinicExport();
        $fileName = $exporter->export($filters);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }

    public function create($id=''){
        if (!get_user_permission('clinics', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
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
        $page = 'admin.clinics.create';
        $is_contract_signed = 0;
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
                $is_contract_signed = $hospital->is_contract_signed;
        }
        // dd($hospital->user->toArray());
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
            'trade_licenece',
            'is_contract_signed'
        ));
    }

    public function save(Request $request)
{
    $status     = "0";
    $message    = "";
    $o_data     = [];
    $errors     = [];
    $o_data['redirect'] = route('admin.clinics.index');

    // Sanitize phone number value
    $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
    $sanitizedDirectPhone = preg_replace('/\D/', '', $request->direct_phone ?? null);
    $request->merge(['phone' => $sanitizedPhone, 'direct_phone' => $sanitizedDirectPhone]);

    $rules = [
        // Define your validation rules
        'name_en' => 'required',
        'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        'image.*' => 'mimes:jpeg,png,pdf|max:2048',
        'trade_licenece' => 'mimes:jpg,jpeg,png,pdf|max:2048',
        'website' => 'nullable|url',
        'dial_code'=>'nullable|numeric',
        'password' => !$request->id ? 'required|min:8' : '',
        'phone'=>'nullable|numeric|digits_between:8,12',
        'direct_dial_code'=>'nullable|numeric',
        'direct_phone'=>'nullable|numeric|digits_between:8,12',
    ];
    $id = $request->id;
    $validator = Validator::make($request->all(), $rules);

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

     

     
    if ($validator->fails()) {

        $status = "0";
        $message = "Validation error occurred";
        $errors = $validator->messages();
    } elseif ($check_email) {
        $status = "0";
        $message = "Email id already registered with us";
        $errors['email'] = 'Email id already registered with us';
    }elseif ($check_phone) {
        $status = "0";
        $message = "Phone registered with us";
        $errors['phone'] = 'Phone registered with us';
    }else {

        
        DB::beginTransaction();
        try {
            if ($id) {
                // Update logic
                $hospital = Hospital::where('type', TYPE_CLINIC)->where('id', $id)->first();
                $hospital->type = TYPE_CLINIC;
                $hospital->country_id = $request->country;
                $hospital->emirate_id = $request->emirate_id;
                $hospital->area_id = $request->area_id;
                $hospital->address = $request->address;
                $hospital->txt_location = $request->txt_location;
                $hospital->latitude = $request->latitude;
                $hospital->longitude = $request->longitude;
                $hospital->website = $request->website;
                $hospital->profile_description = $request->profile_bio;
                $hospital->profile_description_ar = $request->profile_bio_ar;
                $hospital->appointment_dial_code = $request->direct_dial_code ?: DEFAULT_DIAL_CODE;
                $hospital->appointment_phone = str_replace(" ", "", $request->direct_phone);
                $hospital->name_en = $request->name_en;
                $hospital->name_ar = $request->name_ar;
                $hospital->is_contract_signed = $request->is_contract_signed;
                $hospital->save();

                // Remove the selected images
                if ($request->remove_images) {
                    $removeImagesIds = explode(',', $request->remove_images);
                    foreach ($removeImagesIds as $imageId) {
                        $image = HospitalImage::find($imageId);
                        if ($image) {
                            // Optionally delete the file from storage
                            // Storage::disk(config('global.upload_bucket'))->delete(config('global.hospital_image_upload_dir') . '/' . $image->image_name);

                            // Delete the record from the database
                            $image->delete();
                        }
                    }
                }

                // Process the image uploads
                if ($request->hasfile('images')) {
                    foreach ($request->file('images') as $file) {
                        $fileName = time().uniqid().".".$file->getClientOriginalExtension();
                        $file->storeAs(config('global.hospital_image_upload_dir'), $fileName, config('global.upload_bucket'));
                        $image = new HospitalImage();
                        $image->hospital_id = $hospital->id;
                        $image->image_name = $fileName;
                        $image->created_at = gmdate('Y-m-d H:i:s');
                        $image->updated_at = gmdate('Y-m-d H:i:s');
                        $image->save();
                    }
                }

                // Update the User information
                $user = User::find($hospital->user_id);
                $user->email = strtolower($request->email);
                $user->name = $request->name_en;
                $user->dial_code = $request->dial_code ?: DEFAULT_DIAL_CODE;
                $user->phone = str_replace(" ", "", ltrim($request->phone, "0"));
                $user->last_updated_by = Auth::user()->id;
                $user->updated_at = now();
                $user->deleted = 0;
                $user->role = CLINIC_ROLE;

                if ($request->password) {
                    $user->password = Hash::make($request->password);
                }

                if ($request->hasfile('image')) {
                    $file = $request->file('image');
                    $fileName = time() . uniqid() . "." . $file->getClientOriginalExtension();
                    $file->storeAs(config('global.user_image_upload_dir'), $fileName, config('global.upload_bucket'));
                    $user->user_image = $fileName;
                }
                $user->save();

                // Process the trade licence file upload
                if ($request->hasFile("trade_licenece")) {
                    $file = $request->file("trade_licenece");
                    $fileName = time().uniqid().".".$file->getClientOriginalExtension();
                    $file->storeAs(config('global.trade_licenece_image_upload_dir'), $fileName, config('global.upload_bucket'));
                    $hospital->trade_licenece = $fileName;
                }

                // Update hospital location
                HospitalLocation::where('hospital_id', $hospital->id)->delete();
                $locations = new HospitalLocation;
                $locations->hospital_id = $hospital->id;
                $locations->location = $request->location;
                $locations->latitude = $request->latitude;
                $locations->longitude = $request->longitude;
                $locations->created_at = gmdate('Y-m-d H:i:s');
                $locations->updated_at = gmdate('Y-m-d H:i:s');
                $locations->save();

                $hospital->save();
                DB::commit();
                $status = "1";
                $message = "Clinic updated successfully";
            }
            else {
                // Update the User information
                $user = new User();
                $user->email_verified_at = now();
                $user->email = strtolower($request->email);
                $user->name = $request->name_en;
                $user->dial_code = $request->dial_code ?: DEFAULT_DIAL_CODE;
                $user->phone = str_replace(" ", "", ltrim($request->phone, "0"));
                $user->last_updated_by = Auth::user()->id;
                $user->updated_at = now();
                $user->deleted = 0;
                $user->active = 1;
                $user->role = CLINIC_ROLE;

                if ($request->password) {
                    $user->password = Hash::make($request->password);
                }

                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $fileName = time() . uniqid() . "." . $file->getClientOriginalExtension();
                    $file->storeAs(config('global.user_image_upload_dir'), $fileName, config('global.upload_bucket'));
                    $user->user_image = $fileName;
                }

                $user->save();

                $hospital = new Hospital();
                $hospital->type = TYPE_CLINIC;
                $hospital->country_id = $request->country;
                $hospital->emirate_id = $request->emirate_id;
                $hospital->area_id = $request->area_id;
                $hospital->address = $request->address;
                $hospital->txt_location = $request->txt_location;
                $hospital->latitude = $request->latitude;
                $hospital->longitude = $request->longitude;
                $hospital->website = $request->website;
                $hospital->profile_description = $request->profile_bio;
                $hospital->profile_description_ar = $request->profile_bio_ar;
                $hospital->appointment_dial_code = $request->direct_dial_code ?: DEFAULT_DIAL_CODE;
                $hospital->appointment_phone = str_replace(" ", "", $request->direct_phone);
                $hospital->name_en = $request->name_en;
                $hospital->name_ar = $request->name_ar;
                $hospital->is_contract_signed = $request->is_contract_signed;
                $hospital->user_id = $user->id;

                $hospital->save();

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $fileName = time() . uniqid() . "." . $file->getClientOriginalExtension();
                        $file->storeAs(config('global.hospital_image_upload_dir'), $fileName, config('global.upload_bucket'));
                        $image = new HospitalImage();
                        $image->hospital_id = $hospital->id;
                        $image->image_name = $fileName;
                        $image->created_at = now();
                        $image->updated_at = now();
                        $image->save();
                    }
                }

                // Process the trade license file upload
                if ($file = $request->file("trade_licenece")) {
                    $fileName = time() . uniqid() . "." . $file->getClientOriginalExtension();
                    $file->storeAs(config('global.trade_licenece_image_upload_dir'), $fileName, config('global.upload_bucket'));
                    $hospital->trade_licenece = $fileName;
                }

                $locations = new HospitalLocation();
                $locations->hospital_id = $hospital->id;
                $locations->location = $request->location;
                $locations->latitude = $request->latitude;
                $locations->longitude = $request->longitude;
                $locations->created_at = now();
                $locations->updated_at = now();
                $locations->save();

                DB::commit();
                $status = "1";
                $message = "Clinic updated successfully";
            }

            // else {


            //     // Create logic (not shown for brevity, similar to update logic)
            // }
        } catch (Exception $e) {
            DB::rollback();
            $message = $id ? "Failed to update clinic " . $e->getMessage() : "Failed to create clinic " . $e->getMessage();
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
        $query = Hospital::query()
        ->where('type', TYPE_CLINIC)
        ->leftJoin('users', 'users.id', '=', 'hospitals.user_id')
        ->leftJoin('country', 'country.id', '=', 'hospitals.country_id')
        ->leftJoin('emirates', 'emirates.id', '=', 'hospitals.emirate_id');

        $params = [];

        if ($request->emirate_id) {
            $query->where('hospitals.emirate_id', $request->emirate_id);
            $params['emirate_id'] = $request->emirate_id;
        }

        if ($request->has('search') && ($request->search['filters'] ?? null)) {
            $filters = $request->search['filters'];
            $carbonDate =( $filters['booking_to'] )? Carbon::createFromFormat('d-m-Y', $filters['booking_to']):'';
            $fromDate =( $filters ['booking_from'] )?  Carbon::createFromFormat('d-m-Y',$filters ['booking_from']):'';
             $toDate = $carbonDate;

             if (!empty($carbonDate) && !empty($fromDate) && $fromDate->isSameDay($toDate)) {
                // Add one day to to_date
                $toDate->addDay();

                // Update the filters array
                $filters['booking_to'] = $toDate->format('d-m-Y');
            }
            if ($request->search['filters']['booking_from'] ?? null) {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->search['filters']['booking_from'])->startOfDay()->format('Y-m-d');
                $query->where('hospitals.created_at', '>=', $date);
            }

            if ($filters['booking_to'] ?? null) {
                if(!$carbonDate->isToday()){
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $filters['booking_to'])->endOfDay()->format('Y-m-d');
                $query->where('hospitals.created_at', '<=', $date);
            }
        }

            if ($request->search['filters']['emirate_id'] ?? null) {
                $query->where('hospitals.emirate_id', $request->search['filters']['emirate_id']);
            }
            if ($request->search['filters']['clinic_status'] != "") {
                $query->where('users.active', $request->search['filters']['clinic_status']);
            }
        }

        $users  = $query->select('hospitals.*', 'users.email', 'users.dial_code','users.phone','country.name as country_name','emirates.name_en as emirate_name')
        ->orderBy('hospitals.id','desc');


    return DataTables::eloquent($users)
    ->editColumn('aprroval_status', function($item) {

    $statuses = [
        'waiting' => 'Waiting',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    $html = '<select class="form-select approval-status" 
                    data-id="'.$item->user_id.'" 
                    data-url="' . url('admin/hospitals/approve_status') . '">';

    foreach ($statuses as $key => $label) {
        $selected = ($item->aprroval_status == $key) ? 'selected' : '';
        $html .= '<option value="'.$key.'" '.$selected.'>'.$label.'</option>';
    }

    $html .= '</select>';

    return $html;
})
        ->addColumn('action', function($user) {
             $action = '<div class="dropdown mt-4 mt-sm-0">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu">';

                if (get_user_permission('clinics', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.clinics.edit', ['id' => $user->id]) . '">Edit Clinic</a>';
                }
                if (get_user_permission('clinics', 'd')) {
                    $action .= '<a class="dropdown-item" data-role="unlink"
                            data-message="Do you want to remove the clinic? This may be linked with other sections"
                            href="' . route('admin.clinics.delete', ['id' => encrypt($user->id)]) . '">
                            <i class="flaticon-delete-1"></i> Delete Clinic
                          </a>';
                }
                if (get_user_permission('clinics', 'r')) {
                    $action.='<a class="dropdown-item complete-link" href="'.route('admin.doctors.index',['clinic_id' =>$user->id]).'">Doctors </a>';
                }
                if (get_user_permission('clinics', 'r')) {
                    $action.='<a class="dropdown-item complete-link" href="'.route('admin.appointments.index',['clinic_id' =>$user->id]).'">Total Appointments </a>';
                }
                if (get_user_permission('insurence_policy', 'r')) {
                    $action.='<a class="dropdown-item complete-link" href="'.route('admin.clinics.insurances',['id'=>$user->id]).'">Our Insurance </a>';
                }


           $action .='</div>
            </div>';

            return $action;
        })
        ->addColumn('sl_no', function($user) {
            static $index = 0;
            return ++$index;
        })
        ->addColumn('name_en', function($user) {
            return '<span class="d-flex">'.$user->name_en.' '.($user->user->email_verified_at ? '<img class="verified-account" src="'.asset('admin-assets/assets/images/verified-icon.png').'" alt="verification Icon">' : ''). '</span>';
        })
        ->addColumn('phone_number', function($item) {
            return ($item->phone)?'+'.$item->dial_code.$item->phone:'';
        })
        ->addColumn('status', function($item) {
            return '<div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                    <input type="checkbox" class="form-check-input change_status" data-id="'.$item->user_id.'"
                                data-url="'.url('admin/clinics/change_status').'"
                                '.($item->user->active == 1 ? 'checked' : '').'>
                </div>';
        })
        ->rawColumns(['status', 'action', 'name_en','aprroval_status'])


        ->toJson();
    }

    public function doctors($id){
        $hospital_id = $id;
        $hospital = Hospital::find($hospital_id);
        // dd($hospital);
        if (!get_user_permission('doctors', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Doctors";
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';

        return view('admin.doctors.index',compact('page_heading', 'hospital_id', 'hospital'));

    }



    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        $user = User::find($request->id);
        if($user){
            if (User::where('id', $request->id)->update(['active' => $request->status, 'email_verified_at' => now()])) {
                $status = "1";
                $msg = "Successfully activated";
                if (!$request->status) {
                    $msg = "Successfully deactivated";
                }
                $message = $msg;
            } else {
                $message = "Something went wrong";
            }
        }else{
            $message = "Record Not Exist!";
        }

        echo json_encode(['status' => $status, 'message' => $message]);

        if($request->status && $user){
            Mail::to($user->email)->send(new ActivateAccountEmail($user, 'clinic/login'));
        }
    }

    public function appointments($id){
        if (!get_user_permission('clinics', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Appointments";
        $hospital_id = $id;
        $hospital = Hospital::find($hospital_id);
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
        return view('admin.clinics.appointment',compact('page_heading','hospital','hospital_id'));
    }

    public function appointmentLoadData(REQUEST $request){
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;

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

                if (get_user_permission('patients', 'r')) {
                    $action .= '<a class="dropdown-item complete-link" href="'.route('admin.clinics.edit_appointment', ['hospital_id' => $user->hospital_id ?? null, 'id' => $user->id]).'">Edit</a>';
                    $action .= '<button class="dropdown-item complete-link delete-appointment" data-id="'.encrypt($user->id).'">Delete</button>';
                }

           $action .='</div>
            </div>';

            return $action;
        })
        ->addColumn('sl_no', function($user) use (&$startIndex) {
                return ++$startIndex;
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
        if (!get_user_permission('patients', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
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
        return view('admin.clinics.make_appointment', compact(
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
            $o_data['redirect'] = route('admin.clinics.appointments', $request->hospital);
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    public function insurances($id){
        $hospital_id = $id;
        $hospital = Hospital::find($hospital_id);
        if (!get_user_permission('insurence_policy', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Clinics Insurances";
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
        $list = HospitalInsurancePolicy::with(['insurance', 'subInsurance', 'hospital'])->where('hospital_id', $hospital_id)->orderBy('id','desc')->paginate(10);
        return view('admin.clinics.insurances',compact('page_heading', 'hospital_id', 'hospital', 'list'));

    }

    public function createInsurance($hospital_id, $id = '')
    {
        if (!get_user_permission('insurence_policy', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $insurances = InsurencePolicy::where(['status'=>1])->get();
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
        return view('admin.clinics.createInsurance', compact('page_heading', 'id', 'hospital_id', 'row', 'insurances', 'sub_insurance_list'));
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
        ];

        $subInsuranceExists = \DB::table('sub_insurence_policies')
        ->where('insurence_id', $request->insurance_id)
        ->exists();

        if($subInsuranceExists){
        $rules['sub_insurance_id'] = 'required';
        }


        $validator = Validator::make($request->all(), $rules);

        $o_data['redirect'] = route('admin.clinics.insurances', $request->hospital_id ?? null);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $id = $request->id;
           $check='';
           if($request->sub_insurance_id){
            $check = HospitalInsurancePolicy::where('hospital_id', $request->hospital_id)
                ->whereIn('sub_insurance_id', $request->sub_insurance_id)
                ->where('id', '!=', $id)
                ->first();
           }
            if ($check) {
                $status = "0";
                $message = "This insurance policy is already associated with the hospital.";
                $errors['sub_insurance_id[]'] = (($check->subInsurance->title ?? null) ? $check->subInsurance->title : 'this').' insurance policy is already associated with this hospital.';
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        if ($request->has('sub_insurance_id') && is_array($request->sub_insurance_id) && !empty($request->sub_insurance_id)) {
                            foreach ($request->sub_insurance_id as $sub_insurance_id) {
                                $insurancePolicy = new HospitalInsurancePolicy();
                                $insurancePolicy->hospital_id = $request->hospital_id;
                                $insurancePolicy->insurance_id = $request->insurance_id;
                                $insurancePolicy->sub_insurance_id = $sub_insurance_id;
                                $insurancePolicy->save();
                            }
                        }
                        else{
                            $insurancePolicy = new HospitalInsurancePolicy();
                                $insurancePolicy->hospital_id = $request->hospital_id;
                                $insurancePolicy->insurance_id = $request->insurance_id;
                                $insurancePolicy->save();
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
                        if ($request->has('sub_insurance_id') && is_array($request->sub_insurance_id) && !empty($request->sub_insurance_id)) {
                            foreach ($request->sub_insurance_id as $sub_insurance_id) {
                                $insurancePolicy = new HospitalInsurancePolicy();
                                $insurancePolicy->hospital_id = $request->hospital_id;
                                $insurancePolicy->insurance_id = $request->insurance_id;
                                $insurancePolicy->sub_insurance_id = $sub_insurance_id;
                                $insurancePolicy->save();
                            }
                        }
                        else{

                            $insurancePolicy = new HospitalInsurancePolicy();
                                $insurancePolicy->hospital_id = $request->hospital_id;
                                $insurancePolicy->insurance_id = $request->insurance_id;
                                $insurancePolicy->save();
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
        if (!get_user_permission('hospital', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Clinics Locations";
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
        $list = HospitalLocation::with('hospital')->where('hospital_id', $hospital_id)->orderBy('id','desc')->paginate(10);
        return view('admin.clinics.locations',compact('page_heading', 'hospital_id', 'hospital', 'list'));

    }

    public function createLocation($hospital_id, $id = '')
    {
        if (!get_user_permission('hospital', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $row = null;
        $hospital = Hospital::find($hospital_id);
        $page_heading = $id ? 'Edit Clinics Location' : 'Create Clinics Location';
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';

        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $row = HospitalLocation::find($id);
        }

        return view('admin.clinics.createLocation', compact('page_heading', 'id', 'hospital_id', 'row'));
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

        $o_data['redirect'] = route('admin.clinics.locations', $request->hospital_id ?? null);

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
        $user = User::where('id', $row->user_id)->first();
        if ($row) {
            $row->delete();
            // User::where('id', $row->user_id)->update(['deleted' => 1]);
            $user->user_device_token = "";
            $user->email = $user->email . "__deleted_account_" . $user->id;
            $user->phone = $user->phone . "__deleted_account_" . $user->id;
            $user->deleted = 1;
            $user->access_token = "";
            $user->update();
            $status = "1";
            $message = "Clinic removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
}
?>
