<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\HospitalDoctorFeedback;
use Illuminate\Support\Facades\Hash;
use App\Exports\HospitalsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HospitalExport;
use App\Imports\HospitalImport;
use App\Models\SettingsModel;
use App\Models\DoctorAvailability;
use App\Models\DoctorHolidays;
use DataTables;
use App\Mail\ActivateAccountEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class HospitalController extends Controller
{

    public function index(){

        if (!get_user_permission('hospitals', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Hospitals";
        $emirates = Emirate::where('active',1)->orderBy('name_en','asc')->get();

        return view('admin.hospitals.index',compact('page_heading','emirates'));
    }

    public function create($id=''){
        if (!get_user_permission('hospitals', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        // dd($id);
        $page_heading="Create Hospital";

      //  $country_list =  CountryModel::where(['active'=>1])->whereIn('prefix', ALLOWED_COUNTRIES_PREF)->orderBy('name','asc')->get();
        $country_list =  CountryModel::where(['active'=>1])->orderBy('name','asc')->get();
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
        $is_contract_signed = "0";
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
                $is_contract_signed = $hospital->is_contract_signed;
            //     $name_ar    = $hospital->name_ar;
            //     $trade_licenece = $hospital->trade_licence_url;
            // }
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

    public function save(REQUEST $request)
    {
        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        //
        //sanitize pone number value
        function sanitizePhoneNumber($phone, $dialCode) {
            $sanitizedPhone = preg_replace('/\D/', '', $phone);
            if (strpos($sanitizedPhone, $dialCode) === 0) {
                $sanitizedPhone = substr($sanitizedPhone, strlen($dialCode));
            }
            return $sanitizedPhone;
        }

        $sanitizedPhone = sanitizePhoneNumber($request->phone, $request->dial_code);
        $sanitizedDirectPhone = sanitizePhoneNumber($request->direct_phone, $request->direct_dial_code);
        $request->merge(['phone' => $sanitizedPhone, 'direct_phone' => $sanitizedDirectPhone]);
        //
        // dd($request->all());
        $o_data['redirect'] = route('admin.hospitals.index');
        $rules = [
            'name_en' => 'required',
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'image.*' => 'mimes:jpeg,png,pdf|max:2048',
            'trade_licenece' => 'mimes:jpg,jpeg,png,pdf|max:2048',
            'website' => 'nullable|url',
            'password' => !$request->id ? 'required|min:8' : '',
            // 'department' => 'required|array|min:1',
            // 'department.*' => 'exists:departments,id',
            'dial_code'=>'nullable',
            'phone'=>'nullable|numeric|digits_between:8,12',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $id = $request->id;
            // $check_email = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->whereHas('hospital', function($q) use($id){
            //     $q->where('id', '!=', $id);
            // })->first();
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

            // $location = $request->location;
            $latitude = $request->latitude;
            $longitude  = $request->longitude;
            // dd($request->all());
            if ($check_email) {
                $status = "0";
                $message = "Email id already registered with us";
                $errors['email'] = 'Email id already registered with us';
            }elseif ($check_phone) {
                $status = "0";
                $message = "Phone registered with us";
                $errors['phone'] = 'Phone registered with us';
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
                        $hospital->txt_location    = $request->location;
                        $hospital->latitude    = $latitude;
                        $hospital->longitude    = $longitude;
                        $hospital->website    = $request->website;
                        $hospital->profile_description = $request->profile_bio;
                        $hospital->profile_description_ar = $request->profile_bio_ar;
                        $hospital->appointment_dial_code  = $request->direct_dial_code ? $request->direct_dial_code : DEFAULT_DIAL_CODE;
                        $hospital->appointment_phone      = str_replace(" ","",$request->direct_phone);
                        $hospital->name_en    = $request->name_en;
                        $hospital->name_ar    = $request->name_ar;
                        $hospital->is_contract_signed = $request->is_contract_signed;
                        $hospital->save();
                        if ($request->has('department')) {
                            // $hospital->departments()->sync($request->department);
                        }
                        $user = User::find($hospital->user_id);
                        // dd($user);
                        $user->email    = strtolower($request->email);
                        $user->name     = $request->name_en;
                        $user->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                        $user->phone     = str_replace(" ","",ltrim($request->phone,"0"));
                        if($request->password){
                            $user->password  = Hash::make($request->password);
                        }
                        $user->last_updated_by = Auth::user()->id;
                        $user->updated_at = gmdate('Y-m-d H:i:s');
                        $user->deleted = 0;

                        if ($request->hasfile('image')) {
                            $file = $request->file('image');
                            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $user->user_image = $file_name;
                        }
                        $user->save();


                        if ($request->hasFile("trade_licenece")) {
                            $file = $request->file("trade_licenece");
                            $file_name = time().uniqid().".".$file->getClientOriginalExtension();
                            $file->storeAs(config('global.trade_licenece_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $hospital->trade_licenece = $file_name;
                        }

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

                        if ($request->hasfile('images')) {
                            // Delete existing images
                            // HospitalImage::where('hospital_id', $hospital->id)->delete();

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
                        $message = "Hospital updated successfully";
                    } else {
                        // dd($request->all());
                        // Create logic
                        $user = new User();
                        $user->email    = strtolower($request->email);
                        $user->email_verified_at = now();
                        $user->name     = $request->name_en;
                        $user->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                        $user->phone     = str_replace(" ","",ltrim($request->phone,"0"));
                        if($request->password){
                            $user->password  = Hash::make($request->password);
                        }
                        $user->role      = HOSPITAL_ROLE;
                        $user->active = (Auth::user()->role == ADMIN_ROLE ? 1 : 0);
                        $user->created_by = Auth::user()->id;
                        $user->last_updated_by = Auth::user()->id;
                        $user->created_at = gmdate('Y-m-d H:i:s');
                        $user->updated_at = gmdate('Y-m-d H:i:s');
                        $user->role_id     = 0;
                        $user->deleted = 0;
                        if ($request->hasfile('image')) {
                            $file = $request->file('image');
                            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $user->user_image = $file_name;
                        }
                        $user->save();

                        $hospital = new Hospital();
                        $hospital->user_id   = $user->id;
                        $hospital->country_id = $request->country;
                        $hospital->emirate_id = $request->emirate_id;
                        $hospital->area_id    = $request->area_id;
                        $hospital->address    = $request->address;
                        $hospital->txt_location    = $request->location;
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
                        $locations->location = $request->location;
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

    public function show($id)
    {
        $hospital = Hospital::findOrFail($id); // Assuming Hospital is your model

        return view('hospitals.show', compact('hospital'));
    }


    public function load_data(Request $request) {
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;

        $query = Hospital::query()
            ->where('type', TYPE_HOSPITAL)
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
                if ($request->search['filters']['hospital_status'] != "") {
                    $query->where('users.active', $request->search['filters']['hospital_status']);
                }
            }
            $users = $query->select(['hospitals.*','users.aprroval_status as aprroval_status', 'users.email as email', 'users.dial_code', 'users.phone', 'country.name as country_name', 'emirates.name_en as emirate_name'])
            ->orderBy('hospitals.id', 'desc');
        return DataTables::eloquent($users)
        ->filter(function ($query) use ($request) {

            if ($request->has('search') && !empty($request->search['value'])) {
    
                $searchValue = $request->search['value'];
    
                $query->where(function ($q) use ($searchValue) {
                    $q->where('hospitals.name_en', 'ILIKE', "%{$searchValue}%")
                      ->orWhere('hospitals.name_en', 'ILIKE', "%{$searchValue}%");
                });
            }
        })
       ->addColumn('action', function ($user) use ($params) {
                $action = '<div class="dropdown mt-4 mt-sm-0">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-dots-horizontal-rounded"></i>
                    </button>
                    <div class="dropdown-menu">';

                if (get_user_permission('hospitals', 'u')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.hospitals.edit', ['id' => $user->id]) . '">Edit Hospital</a>';
                }
                if (get_user_permission('hospitals', 'd')) {
                    $action .= '<a class="dropdown-item" data-role="unlink"
                            data-message="Do you want to remove the hospital? This may be linked with other sections"
                            href="' . route('admin.hospitals.delete', ['id' => encrypt($user->id)]) . '">
                            <i class="flaticon-delete-1"></i> Delete Hospital
                          </a>';
                }
                if (get_user_permission('departments', 'r')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.hospitals.departments', ['id' => $user->id]) . '">Departments </a>';
                }
                if (get_user_permission('hospitals', 'r')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.doctors.index', ['hospital_id' => $user->id]) . '">Doctors </a>';
                }
                if (get_user_permission('hospitals', 'r')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.appointments.index', ['hospital_id' => $user->id]) . '">Total Appointments </a>';
                }
                if (get_user_permission('insurence_policy', 'r')) {
                    $action .= '<a class="dropdown-item complete-link" href="' . route('admin.hospitals.insurances', ['id' => $user->id]) . '">Insurances</a>';
                }

                $action .= '</div>
                </div>';

                return $action;
            })

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

            ->addColumn('sl_no', function($user) use (&$startIndex) {
                return ++$startIndex;
            })
            ->addColumn('name_en', function($item) {
                return '<span class="d-flex">' . $item->name_en . ' ' . ($item->user->email_verified_at ? '<img class="verified-account" src="' . asset('admin-assets/assets/images/verified-icon.png') . '" alt="verification Icon">' : '') . '</span>';
            })
            ->addColumn('phone_number', function($item) {
                return ($item->phone)? '+' . $item->dial_code . $item->phone:'';
            })
            ->addColumn('status', function($item) {
                return '<div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                        <input type="checkbox" class="form-check-input change_status" data-id="' . $item->user_id . '"
                                    data-url="' . url('admin/hospitals/change_status') . '"
                                    ' . ($item->user->active == 1 ? 'checked' : '') . '>
                    </div>';
            })
            ->rawColumns(['status', 'action', 'name_en','aprroval_status'])
            ->toJson();
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        // dd($filters);
        $exporter = new HospitalsExport();
        $fileName = $exporter->export($filters);

        return response()->download($fileName)->deleteFileAfterSend(true);
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
        }else{
            $message = "Record Not Exist!";
        }

        echo json_encode(['status' => $status, 'message' => $message]);

        if($request->status && $user){
            Mail::to($user->email)->send(new ActivateAccountEmail($user, 'hospital/login'));
        }
    }
    
    public function change_review_status(Request $request)
    {
        $status = "0";
        $message = "";
        $user = HospitalDoctorFeedback::find($request->id);
        if($user){
            if (HospitalDoctorFeedback::where('id', $request->id)->update(['status' => $request->status])) {
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

       
    }

    public function approve_status(Request $request)
    {
        
        $status = "0";
        $message = "";
        $user = User::find($request->id);
        if($user){
            if (User::where('id', $request->id)->update([
                'aprroval_status' => $request->status,
                'reject_reason' => $request->reason
                ])) {
                $status = "1";
                $msg = "Account Successfully Approved";
                if ($request->status=='rejected') {
                    $msg = "Account Successfully Rejected";
                }
                $message = $msg;
                 $user = User::find($request->id);
                $this->sendApproveEmail($user);
            } else {
                $message = "Something went wrong";
            }
        }else{
            $message = "Record Not Exist!";
        }

        echo json_encode(['status' => $status, 'message' => $message]);

        if($request->status && $user){
            Mail::to($user->email)->send(new ActivateAccountEmail($user, 'hospital/login'));
        }
    }

    private function sendApproveEmail($user)
    {
        if($user){
            if($user->email != ''){
                $mailbody = view('mail.approve-registration', compact('user'));
                send_email($user->email, "Account Verification " . env('APP_NAME'), $mailbody);
            }
        }
    }

    public function departments($id){
        $hospital_id = $id;
        $hospital = Hospital::find($hospital_id);
        if (!get_user_permission('departments', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Hospital Departments";
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
        $department_list = DepartmentHospital::with(['department', 'hospital'])->where('hospital_id', $hospital_id)
            ->whereHas('department')
            ->orderBy('id','desc')
            ->paginate(10);
        return view('admin.hospitals.departments',compact('page_heading', 'hospital_id', 'hospital', 'department_list'));
    }

    public function createDepartment($hospital_id, $id = '')
    {
        if (!get_user_permission('departments', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $departments = DepartmentModel::where(['status'=>1])->get();
        $row = null;
        $hospital = Hospital::find($hospital_id);
        $page_heading = $id ? 'Edit Hospital Department' : 'Create Hospital Department';
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';

        if ($id) {
            $mode = "Edit";
            // $id = decrypt($id);
            $row = DepartmentHospital::find($id);
        }
        return view('admin.hospitals.createDepartment', compact('page_heading', 'id', 'hospital_id', 'row', 'departments'));
    }

    public function saveDepartment(REQUEST $request)
    {
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        //
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $request->merge(['phone' => $sanitizedPhone]);
        //
        $rules = [
            'hospital_id' => 'required|exists:hospitals,id',
            'department' => 'required|exists:departments,id',
            ];

        $validator = Validator::make($request->all(), $rules);

        $o_data['redirect'] = route('admin.hospitals.departments', $request->hospital_id ?? null);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            $id = $request->id;
            $check = DepartmentHospital::where('hospital_id', $request->hospital_id)
                ->where('department_id', $request->department)
                ->where('id', '!=', $id)
                ->first();

            if ($check) {
                $status = "0";
                $message = "This department is already associated with the hospital.";
                $errors['department'] = 'This department is already associated with the hospital.';
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $departmentHospital = DepartmentHospital::find($id);
                        $departmentHospital->hospital_id = $request->hospital_id;
                        $departmentHospital->department_id = $request->department;
                        $departmentHospital->manager_name = $request->manager_name;
                        $departmentHospital->phone = $request->phone;
                        $departmentHospital->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                        $departmentHospital->email = $request->email;
                        $departmentHospital->save();

                        DB::commit();
                        $status = "1";
                        $message = "Department updated successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to update Department: " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $departmentHospital = new DepartmentHospital();
                        $departmentHospital->hospital_id = $request->hospital_id;
                        $departmentHospital->department_id = $request->department;
                        $departmentHospital->manager_name = $request->manager_name;
                        $departmentHospital->phone = $request->phone;
                        $departmentHospital->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                        $departmentHospital->email = $request->email;
                        $departmentHospital->save();

                        DB::commit();
                        $status = "1";
                        $message = "Department added successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to add Department: " . $e->getMessage();
                    }
                }
            }
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
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

    public function deleteDepartment(REQUEST $request, $id)
    {
        $status = "0";
        $message = "";

        $id = decrypt($id);

        $row = DepartmentHospital::where(['id' => $id])->first();

        if ($row) {
            DepartmentHospital::where(['id' => $id])->delete();
            $message = "Hospital Department deleted successfully";
            $status = "1";
        } else {
            $message = "Invalid Role data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }

    public function appointments($id){
        if (!get_user_permission('hospitals', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Appointments";
        $hospital_id = $id;
        $hospital = Hospital::find($hospital_id);
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
        return view('admin.hospitals.appointment',compact('page_heading','hospital','hospital_id'));
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

                if (get_user_permission('patients', 'r')) {
                    $action .= '<a class="dropdown-item complete-link" href="'.route('admin.hospitals.edit_appointment', ['hospital_id' => $user->hospital_id ?? null, 'id' => $user->id]).'">Edit</a>';
                    $action .= '<button class="dropdown-item complete-link delete-appointment" data-id="'.encrypt($user->id).'">Delete</button>';
                }

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
        return view('admin.hospitals.make_appointment', compact(
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
            $o_data['redirect'] = route('admin.hospitals.appointments', $request->hospital);
        }

        return response()->json(['status' => $status, 'message' => $message, 'oData' => $o_data, 'errors' => (object)$errors]);
    }

    public function insurances($id){
        $hospital_id = $id;
        $hospital = Hospital::find($hospital_id);
        if (!get_user_permission('insurence_policy', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Hospital Insurances";
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
        $list = HospitalInsurancePolicy::with(['insurance', 'subInsurance', 'hospital'])->where('hospital_id', $hospital_id)->orderBy('id','desc')->paginate(10);

        return view('admin.hospitals.insurances',compact('page_heading', 'hospital_id', 'hospital', 'list'));

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
        $page_heading = $id ? 'Edit Hospital Insurance' : 'Create Hospital Insurance';
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';

        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $row = HospitalInsurancePolicy::find($id);
        }
        if($row->insurance_id ?? null){
            $sub_insurance_list = SubInsurencePolicy::where('insurence_id', $row->insurance_id)->get();
        }
        return view('admin.hospitals.createInsurance', compact('page_heading', 'id', 'hospital_id', 'row', 'insurances', 'sub_insurance_list'));
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

        $o_data['redirect'] = route('admin.hospitals.insurances', $request->hospital_id ?? null);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
            // dd($errors);
        } else {
            $id = $request->id;
            $check='';
           if(!empty($request->sub_insurance_id)){
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
                        if ($request->has('sub_insurance_id') && is_array($request->sub_insurance_id)) {
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
                        if ($request->has('sub_insurance_id') && is_array($request->sub_insurance_id)) {
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
            $message = "Hospital Insurance deleted successfully";
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
        $page_heading="Hospital Locations";
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';
        $list = HospitalLocation::with('hospital')->where('hospital_id', $hospital_id)->orderBy('id','desc')->paginate(10);
        return view('admin.hospitals.locations',compact('page_heading', 'hospital_id', 'hospital', 'list'));

    }

    public function createLocation($hospital_id, $id = '')
    {
        if (!get_user_permission('hospital', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $row = null;
        $hospital = Hospital::find($hospital_id);
        $page_heading = $id ? 'Edit Hospital Location' : 'Create Hospital Location';
        $page_heading.= $hospital ? ('- '.$hospital->name_en) : '';

        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $row = HospitalLocation::find($id);
        }

        return view('admin.hospitals.createLocation', compact('page_heading', 'id', 'hospital_id', 'row'));
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

        $o_data['redirect'] = route('admin.hospitals.locations', $request->hospital_id ?? null);

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
            $message = "Hospital Location deleted successfully";
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
            $message = "Hospital removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }

    public function getFilteredHospitals(Request $request)
    {

        $speciality_id = $request->dr_specialty?? null;
        $gender = $request->dr_gender ?? '';
        $doctor_language = $request->dr_language ? explode(",", $request->dr_language) : [];
        $medical_condition = $request->medical_condition_id ?? '';
        $country_id = $request->dr_countryOrigin ?? '';
        $direct_call_enabled = $request->dirent_call_for_appointment ?? '';
        $instend_need = $request->ready_to_consult_instantly ?? '';
        $need_date = $request->need_date ? date('Y-m-d', strtotime($request->need_date)) : date('Y-m-d');
        // dd($request->all());
        $query = Hospital::query()->has('doctors')->orderBy('name_en', 'asc')->whereHas('user', function ($q) {
            $q->where('active', 1);
        });

        if($request->insurance_id){
            $query->whereHas('insurences', function($q) use($request){
                $q->where('insurance_id', $request->insurance_id);
            });
        }

        if($request->sub_insurance_id){
            $query->whereHas('insurences', function($q) use($request){
                $q->where('sub_insurance_id', $request->sub_insurance_id);
            });
        }

        if($request->emirate_id){
            $query->where('emirate_id', $request->emirate_id);
        }

        if($request->country_id){
            //$query->where('country_id', $request->country_id);
        }

        if($request->area_id){
            $query->where('area_id', $request->area_id);
        }

        // if($request->dr_specialty){
        //     $query->whereHas('doctors.doctorSpecialities', function($q) use($request) {
        //         $q->where('speciality_id', $request->dr_specialty);
        //     });
        // }

        // if($request->dr_interest){
        //     $query->whereHas('doctors.doctorIntrests', function($q) use($request) {
        //         $q->where('special_intrest_id', $request->dr_interest);
        //     });
        // }

        // if($request->dr_language){
        //     $query->whereHas('doctors.doctorLanguageSpoken', function($q) use($request) {
        //         $q->where('language_spoken_id', $request->dr_language);
        //     });
        // }

        // if($request->dr_countryOrigin){
        //     $query->whereHas('doctors', function($q) use($request) {
        //         $q->where('country_of_orgin', $request->dr_countryOrigin);
        //     });
        // }

        // if($request->dr_gender){
        //     $query->whereHas('doctors', function($q) use($request) {
        //         $q->where('gender', $request->dr_gender);
        //     });
        // }
        if ($speciality_id || $gender || $doctor_language || $medical_condition || $country_id || $direct_call_enabled || ($instend_need && $need_date) || $need_date) {

                $query->whereHas('doctors', function ($q) use ($speciality_id, $gender, $doctor_language, $medical_condition, $country_id, $direct_call_enabled, $instend_need, $need_date) {
                    $q->when($speciality_id, function ($query) use ($speciality_id) {
                        $query->whereHas('specialities', function ($q) use ($speciality_id) {
                            $q->where('speciality_id', $speciality_id);
                        });
                    })
                    ->when($gender, function ($query) use ($gender) {
                        $query->whereHas('user', function ($q) use ($gender) {
                            $q->where('gender', $gender);
                        });
                    })
                    ->when($doctor_language, function ($query) use ($doctor_language) {
                        $query->whereHas('languages', function ($q) use ($doctor_language) {
                            $q->whereIn('language_spoken_id', $doctor_language);
                        });
                    })
                    ->when($medical_condition, function ($query) use ($medical_condition) {
                        $query->whereHas('interests', function ($q) use ($medical_condition) {
                            $q->where('special_intrest_id', $medical_condition);
                        });
                    })
                    ->when($country_id, function ($query) use ($country_id) {
                        $query->where('country_id', $country_id);
                    })
                    ->when($direct_call_enabled, function ($query) {
                        $query->whereNotNull('appointment_phone');
                    })
                    ->when($instend_need, function ($query) use ($need_date) {
                        $settings = SettingsModel::first();
                        $query->whereHas('instantAppointments', function ($q) use ($need_date, $settings) {
                            $q->whereDate('instant_appointment_date', $need_date)
                              ->addSelect(['*', DB::raw("'" . $settings->instant_appoitment_number . "' as instant_appoitment_number")]);
                        });
                    })
                    ->when($need_date,function($query) use($need_date){
                        $dayName = strtolower(date('l', strtotime($need_date)));
                        $query->whereIn('doctors.id', DoctorAvailability::where($dayName . '_availability', 1)->select('doctor_id'))
                        ->whereNotIn('doctors.id', DoctorHolidays::whereDate('holiday_date', '=', $need_date)->select('doctor_id'));
                    });
                });
            }

        $hospitals = $query->orderBy('name_en')->get();
        // dd($hospitals);
        return response()->json($hospitals);

    }


    public function HospitalDoctorsReviews(){
        if (!get_user_permission('settings', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        // $reviews = HospitalDoctorFeedback::orderBy('id', 'desc')
        //     ->paginate(10);

        $reviews = HospitalDoctorFeedback::with(['doctor','user', 'hospital' ])
        ->orderBy('id', 'desc')
        ->paginate(10);

        return view('admin.hospitals.reviews', compact('reviews'));

    }

    public function HospitalDoctorsEdit($id)
    {
        // dd($id);


        if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Edit Reviews";
        $Review = HospitalDoctorFeedback::find($id);

        if ($Review) {
            return view("admin.hospitals.edit_review", compact('page_heading', 'Review', 'id'));
        } else {
            abort(404);
        }
    }

    public function review_update(Request $request, string $id)
    {

        // dd($id);


          if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $request->validate([
            'rating' => 'required',
        ]);


            $Review = HospitalDoctorFeedback::find($id);

            $Review->rating = $request->rating;
            $Review->feeback_message = $request->review;

            $Review->update();



        if($request->json_action){
            return response()->json([
                'success' => true,
                'message' => "Review Updated successfully",
                'id' => $Banner->id,
                'title' => $request->title
            ]);
        }
        return redirect()->route('admin.hospitals.reviews')->with('success',  'Review Updated Successfully.');
    }

    public function export_excel(){
        return Excel::download(new HospitalExport(1), 'hospital_clinic_blank_excel.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $import = new HospitalImport();
            Excel::import($import, $request->file('file'));

            $validRecords = $import->getValidRecords();
            $err_msg='';
            $any_succes=0;
            foreach($validRecords as $record){
                $check_email = User::whereRaw('Lower(email) = ?', [strtolower($record['email'])])->get();
                if($check_email->count() > 0){
                    $user_data = $check_email->first();
                    if($user_data->role == HOSPITAL_ROLE || $user_data->role == CLINIC_ROLE){
                        DB::beginTransaction();
                        try {
                            $user = User::find($user_data->id);
                            //$user->email    = strtolower($record['email']);
                            $user->name     = $record['hospital_name'];
                            $user->dial_code = $record['phone_dialcode'];
                            $user->phone     = str_replace(" ","",ltrim($record['phone_number'],"0"));
                            if($record['password'] != ''){
                                $user->password  = Hash::make($record['password']);
                            }
                            $user->last_updated_by = Auth::user()->id;
                            $user->updated_at = gmdate('Y-m-d H:i:s');
                            $user->email_verified_at = now();
                            $user->save();

                            //check for hsopital table entry
                            $check_hospital = Hospital::where(['user_id'=>$user->id])->get();
                            if($check_hospital->count() > 0){
                                $hospital = Hospital::find($check_hospital->first()->id);
                            }else{
                                $hospital = new Hospital();
                                $hospital->user_id   = $user->id;
                                if($record['hospital_or_clinic'] == 'Hospital'){
                                    $hospital->type      = TYPE_HOSPITAL;
                                }else{
                                    $hospital->type      = TYPE_CLINIC;
                                }
                            }
                                $hospital->country_id = $record['country'];
                                $hospital->emirate_id = $record['emirate_id'];
                                $hospital->area_id    = $record['area_id'];
                                $hospital->address    = $record['address_of_organisation'];
                                $hospital->txt_location    = $record['location'];
                                $hospital->latitude    = $record['latitude'];
                                $hospital->longitude    = $record['longitude'];
                                $hospital->website    = $record['website'];
                                $hospital->profile_description = $record['hospital_profile_en'];
                                $hospital->profile_description_ar = $record['hospital_profile_ar'];
                                $hospital->appointment_dial_code  = $record['direct_call_dialcode'];
                                $hospital->appointment_phone      = str_replace(" ","",$record['direct_call_phone_number']);
                                $hospital->name_en    = $record['hospital_name'];
                                $hospital->name_ar    = $record['hospital_name_ar'];
                                $hospital->from_excel = 1;
                                $hospital->temp_logo  = $record['logo_file_name']??'';
                                $hospital->temp_trade_licence  = $record['tradelicence_file_name']??'';
                                $hospital->temp_images  = $record['hospital_images_multiple_images_name_coma_seperated']??'';

                                $hospital->save();

                                HospitalLocation::where(['hospital_id'=>$hospital->id])->delete();
                                $locations = new HospitalLocation;
                                $locations->hospital_id = $hospital->id;
                                $locations->location = $record['location'];
                                $locations->latitude = $record['latitude'];
                                $locations->longitude = $record['longitude'];
                                $locations->created_at = gmdate('Y-m-d H:i:s');
                                $locations->updated_at = gmdate('Y-m-d H:i:s');
                                $locations->save();

                                if( ( $record['hospital_or_clinic'] == 'Hospital') && !empty($record['department'])){
                                    DepartmentHospital::where(['hospital_id'=>$hospital->id])->delete();
                                    foreach($record['department'] as $dpt){
                                        $check = DepartmentHospital::where(['hospital_id'=>$hospital->id,'department_id'=>$dpt['department_id']])->get();
                                        if($check->count() > 0){
                                            $err_msg.=$record['hospital_name'].' already added '.$dpt['department_id'].'<br>';
                                        }else{
                                            if($dpt['department_id']){
                                                $departmentHospital = new DepartmentHospital();
                                                $departmentHospital->hospital_id = $hospital->id;
                                                $departmentHospital->department_id = $dpt['department_id'];
                                                $departmentHospital->manager_name = $dpt['manager'];
                                                $departmentHospital->phone = $dpt['phone'];
                                                $departmentHospital->dial_code = $dpt['dial_code'];
                                                $departmentHospital->email = $dpt['email'];
                                                $departmentHospital->save();
                                            }
                                        }
                                    }
                                }

                            DB::commit();
                            $any_succes = 1;
                        } catch (Exception $e) {
                            DB::rollback();
                            $err_msg.=$record['hospital_name'].' faild to update due to '.$e->getMessage().'<br>';
                        }


                    }else{
                        $err_msg.=$record['email'].' already exist in our db for dotor or patient'.'<br>';
                    }
                }else{
                    DB::beginTransaction();
                    try {
                        $user = new User();
                        $user->email    = strtolower($record['email']);
                        $user->name     = $record['hospital_name'];
                        $user->dial_code = $record['phone_dialcode'];
                        $user->phone     = str_replace(" ","",ltrim($record['phone_number'],"0"));
                        $user->password  = Hash::make($record['password']);
                        if($record['hospital_or_clinic'] == 'Hospital'){
                            $user->role      = HOSPITAL_ROLE;
                        }else{
                            $user->role      = CLINIC_ROLE;
                        }

                        $user->active    = 0;
                        $user->created_by = Auth::user()->id;
                        $user->last_updated_by = Auth::user()->id;
                        $user->created_at = gmdate('Y-m-d H:i:s');
                        $user->updated_at = gmdate('Y-m-d H:i:s');
                        $user->email_verified_at = now();
                        $user->role_id     = 0;
                        $user->deleted = 0;
                        $user->save();

                        $hospital = new Hospital();
                        $hospital->user_id   = $user->id;
                        if($record['hospital_or_clinic'] == 'Hospital'){
                            $hospital->type      = TYPE_HOSPITAL;
                        }else{
                            $hospital->type      = TYPE_CLINIC;
                        }
                        $hospital->country_id = $record['country'];
                        $hospital->emirate_id = $record['emirate_id'];
                        $hospital->area_id    = $record['area_id'];
                        $hospital->address    = $record['address_of_organisation'];
                        $hospital->txt_location    = $record['location'];
                        $hospital->latitude    = $record['latitude'];
                        $hospital->longitude    = $record['longitude'];
                        $hospital->website    = $record['website'];
                        $hospital->profile_description = $record['hospital_profile_en'];
                        $hospital->profile_description_ar = $record['hospital_profile_ar'];
                        $hospital->appointment_dial_code  = $record['direct_call_dialcode'];
                        $hospital->appointment_phone      = str_replace(" ","",$record['direct_call_phone_number']);
                        $hospital->name_en    = $record['hospital_name'];
                        $hospital->name_ar    = $record['hospital_name_ar'];
                        $hospital->from_excel = 1;
                        $hospital->temp_logo  = $record['logo_file_name']??'';
                        $hospital->temp_trade_licence  = $record['tradelicence_file_name']??'';
                        $hospital->temp_images  = $record['hospital_images_multiple_images_name_coma_seperated']??'';

                        $hospital->save();

                        $locations = new HospitalLocation;
                        $locations->hospital_id = $hospital->id;
                        $locations->location = $record['location'];
                        $locations->latitude = $record['latitude'];
                        $locations->longitude = $record['longitude'];
                        $locations->created_at = gmdate('Y-m-d H:i:s');
                        $locations->updated_at = gmdate('Y-m-d H:i:s');
                        $locations->save();

                        if( ( $record['hospital_or_clinic'] == 'Hospital') && !empty($record['department'])){
                            foreach($record['department'] as $dpt){
                                $check = DepartmentHospital::where(['hospital_id'=>$hospital->id,'department_id'=>$dpt['department_id']])->get();
                                if($check->count() > 0){
                                    $err_msg.=$record['hospital_name'].' already added '.$dpt['department_id'].'<br>';
                                }else{
                                    if($dpt['department_id']){
                                        $departmentHospital = new DepartmentHospital();
                                        $departmentHospital->hospital_id = $hospital->id;
                                        $departmentHospital->department_id = $dpt['department_id'];
                                        $departmentHospital->manager_name = $dpt['manager'];
                                        $departmentHospital->phone = $dpt['phone'];
                                        $departmentHospital->dial_code = $dpt['dial_code'];
                                        $departmentHospital->email = $dpt['email'];
                                        $departmentHospital->save();
                                    }
                                }
                            }
                        }
                        DB::commit();
                        $any_succes = 1;
                    } catch (Exception $e) {
                        DB::rollback();
                        $err_msg.=$record['hospital_name'].' faild to create due to '.$e->getMessage().'<br>';
                    }

                }
            }
            if($any_succes == 1){

                return redirect()->back()->with('success', 'Data imported successfully.'.$err_msg);
            }else{
                return redirect()->back()->with('error', $err_msg);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            // Handle validation failures
            return redirect()->back()->with('error', 'There were validation errors.');
        }
    }

    public function uploadAndExtractZip(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|mimes:zip',
        ]);

        // Get the uploaded ZIP file
        $file = $request->file('zip_file');

        // Define the storage paths
        $zipFilePath = $file->storeAs('uploads/temp', $file->getClientOriginalName());
        $extractToPath = storage_path('app/uploads/extracted');

        // Create the directory if it doesn't exist
        if (!file_exists($extractToPath)) {
            mkdir($extractToPath, 0777, true);
        }

        // Initialize ZipArchive
        $zip = new ZipArchive;

        // Open the ZIP file
        if ($zip->open(storage_path('app/' . $zipFilePath)) === TRUE) {
            // Extract the contents to the specified directory
            $zip->extractTo($extractToPath);
            $zip->close();
            exec("php " . base_path() . "/artisan app:extract-hospital-images > /dev/null 2>&1 & ");

            // Optionally, delete the uploaded ZIP file after extraction
            Storage::delete($zipFilePath);

            return back()->with('success', 'ZIP file extracted successfully!');
        } else {
            return back()->with('error', 'Failed to open the ZIP file.');
        }
    }


}
?>
