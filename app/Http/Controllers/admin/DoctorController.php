<?php

namespace App\Http\Controllers\Admin;
use App\Exports\DoctorsExport;
use App\Models\DoctorAppointmentsStatus;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\DoctorSpecialities;
use App\Models\DoctorTemporaryUnavailable;
use App\Models\DoctorPatientAppointment;
use App\Models\DoctorRescheduleAppointment;
use App\Models\DoctorHolidays;
use App\Models\DoctorInstantAppointment;
use App\Models\DoctorIntrests;
use App\Models\DoctorAvailability;
use App\Models\DoctorLanguageSpoken;
use App\Models\DoctorQualifications;
use App\Models\SpecialIntrests;
use App\Models\Languages;
use App\Models\LicenceType;
use App\Models\Qualifications;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator,DB;
use App\Models\Area;
use App\Models\CountryModel;
use App\Models\Emirate;
use App\Models\User;
use App\Models\Referral;
use App\Models\HospitalImage;
use App\Models\CountryOfOrigin;
use Illuminate\Support\Facades\Hash;
use App\Models\DepartmentModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DoctorExport;
use App\Imports\DoctorImport;
use DataTables;
use App\Mail\ActivateAccountEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use App\Models\DoctorDocument;

class DoctorController extends Controller
{
    public function index(REQUEST $request){
        if (!get_user_permission('doctors', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Doctors";
        $hospital = null;
        $clinic = null;

        if($request->hospital_id){
            $hospital = Hospital::find($request->hospital_id);
            $page_heading.= '- '.$hospital->name_en.' hospital';
        }

        if($request->clinic_id){
            $clinic = Hospital::find($request->clinic_id);
            $page_heading.= '- '.$clinic->name_en.' clinic';
        }
        $hospitals = Hospital::join('users', 'users.id', '=', 'hospitals.user_id')
        ->where('users.active', 1)
        ->orderBy('hospitals.name_en', 'asc')
        ->select('hospitals.*')  // This ensures you're selecting only hospital columns
        ->get();

        $departments = DepartmentModel::where(['status'=>1])->orderBy('title', 'asc')->get();
        $specialities = Specialty::where(['active'=>1])->orderBy('name_en','asc')->get();
        $special_interestes = SpecialIntrests::where(['status'=>1])->orderBy('title','asc')->get();
        //$countries = CountryModel::where('active',1)->orderBy('name','asc')->get();
        $countries = CountryOfOrigin::where(['status'=>1])->orderBy('name','asc')->get();

        return view('admin.doctors.index',compact('page_heading', 'hospital', 'clinic','hospitals','departments','specialities','special_interestes','countries'));
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        // dd($filters);
        $exporter = new DoctorsExport();
        $fileName = $exporter->export($filters);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }


    public function create(REQUEST $request, $id=''){
        if (!get_user_permission('doctors', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Create Doctor";
        $page_heading="Doctors";
        $hospital = null;
        $clinic = null;

        if($request->hospital_id){
            $hospital = Hospital::find($request->hospital_id);
            $page_heading.= '- '.$hospital->name_en.' hospital';
        }

        if($request->clinic_id){
            $clinic = Hospital::find($request->clinic_id);
            $page_heading.= '- '.$clinic->name_en.' clinic';
        }

        $country_list = CountryOfOrigin::where(['status'=>1])->orderBy('name','asc')->get();
        $emirates_list=[];
        $department_list=[];
        $selected_departments = [];
        $area_list = [];
        $consultation_fee = '';
        $country_id = '';
        $emirate_id = '';
        $area_id    = '';
        $first_name = '';

        $hospitals = Hospital::orderBy('hospitals.name_en', 'asc')->get();
        $referrals = Referral::orderBy('id', 'desc')->get();
            
        $last_name  = '';
        $qualification = Qualifications::where(['status'=>1])->orderBy('title','asc')->get();
        $specialty = Specialty::where(['active'=>1])->orderBy('name_en','asc')->get();
        $special_interest = SpecialIntrests::where(['status'=>1])->orderBy('title','asc')->get();
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
        $language_spoken = Languages::where(['status'=>1])->orderBy('title','asc')->get();
        $gender = '';
        $phone = '';
        $email = '';
        $profile_bio = '';
        $direct_phone = '';
        $direct_dial_code='';
        $dial_code='';
        $doctor = null;
        $user= null;
        if($id){
            $page_heading="Edit Doctor";
            $doctor = Doctor::find($id);
            $user = User::where('id',$doctor->user_id)->get()->first();
            if($doctor){
                if($doctor->hospital_id){
                    $oldHospital = Hospital::where('id', $doctor->hospital_id)->first();
                    $department_list = $oldHospital->departments;
                    $oldHospitalCollection = collect([$oldHospital]);
                    $hospitals = $hospitals->merge($oldHospitalCollection);
                }
                $selected_departments_data = $doctor->departments->toArray();
                if(count($selected_departments_data)){
                    $selected_departments = array_keys(mapArrayByIndex($selected_departments_data, 'id'));
                }
                $consultation_fee = $user->consultation_fee;
                $first_name = $user->first_name;
                $last_name = $user->last_name;
                $email = $user->email;
                $dial_code = $user->dial_code;
                $phone = $user->phone;
                $country_id = $doctor->country_id;
                $language_spoken_id =  $doctor->doctorLanguageSpoken->pluck('language_spoken_id')->toArray();
                $profile_bio = $doctor->profile_desciription;
                // $hospital_id = $doctor->hospital_id;
                $qualification_id = $doctor->doctorQualifications->pluck('qualification_id')->toArray();
                $speciality_id = $doctor->doctorSpecialities->pluck('speciality_id')->toArray();
                $special_intrest_id = $doctor->doctorIntrests->pluck('special_intrest_id')->toArray();
                $experiences=$doctor->year_of_experiance;
                $license_no = $doctor->license_no;
                $license_type_id =json_decode($doctor->license_type_id);
                $gender =$user->gender;
                $direct_dial_code  = $doctor->appointment_dial_code;
                $direct_phone    = $doctor->appointment_phone;

            }
        }else{
            $country_id     = 229; //$country_list->first()->id??0;
            $emirates_list  = Emirate::where(['active'=>1,'country_id'=>$country_id])->orderBy('name_en','asc')->get();
            $emirate_id     = $emirates_list->first()->id??0;
            $area_list      = Area::where(['active'=>1,'emirate_id'=>$emirate_id])->orderBy('name_en','asc')->get();
            $department_list = $hospital->departments ?? [];
        }

        // dd($doctor->toArray());
        // dd($country_id);
        return view('admin.doctors.create',compact(
            'page_heading',
            'id',
            'country_list',
            'emirates_list',
            'department_list',
            'selected_departments',
            'area_list',
            'country_id',
            'emirate_id',
            'area_id',
            'hospital',
            'clinic',
            'hospitals',
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
            'user',
            'referrals',
            'consultation_fee'
        ));
    }
    public function appointments($id=""){
        if (!get_user_permission('doctors', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Appointments";
        $doctor_id = $id;
        $patient = User::with('user_role')
        ->where(['users.deleted' => '0', 'role' => '7'])
        ->orderBy('users.id', 'desc')
        ->get();

        $time_slot = TIME_SLOTS;
        return view('admin.doctors.appointment',compact('page_heading','patient','time_slot','doctor_id'));

    }
    public function availability($id){
        if (!get_user_permission('doctors', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Availability";
        $module_heading="Doctors";
        $doctor_id = $id;
        $sunday_availability = 0;
        $sunday_time_slot = [];
        $monday_availability = 0;
        $monday_time_slot = [];
        $tuesday_availability = 0;
        $tuesday_time_slot = [];
        $wednesday_availability = 0;
        $wednesday_time_slot = [];
        $thursday_availability = 0;
        $thursday_time_slot = [];
        $friday_availability = 0;
        $friday_time_slot = [];
        $saturday_availability = 0;
        $saturday_time_slot = [];
        $days = [
            'Sun' => 'Sunday',
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday'
        ];

        $doctor = Doctor::find($id);
        $page_heading.= '- '.$doctor->user->name;
        $doctorAble = DoctorAvailability::where('doctor_id', $id)->first();
        if($doctorAble){

            $sunday_availability = $doctorAble->sunday_availability;
            if($doctorAble->sunday_time_slot && $doctorAble->sunday_time_slot != 'null' ){
                $sunday_time_slot = json_decode($doctorAble->sunday_time_slot);
            }
            $monday_availability = $doctorAble->monday_availability;
            if($doctorAble->monday_time_slot && $doctorAble->monday_time_slot != 'null' ){
                $monday_time_slot = json_decode($doctorAble->monday_time_slot);
            }
            $tuesday_availability = $doctorAble->tuesday_availability;
            if($doctorAble->tuesday_time_slot && $doctorAble->tuesday_time_slot != 'null' ){
                $tuesday_time_slot = json_decode($doctorAble->tuesday_time_slot);
            }
            $wednesday_availability = $doctorAble->wednesday_availability;
            if($doctorAble->wednesday_time_slot && $doctorAble->wednesday_time_slot != 'null' ){
                $wednesday_time_slot = json_decode($doctorAble->wednesday_time_slot);
            }
            $thursday_availability = $doctorAble->thursday_availability;
            if($doctorAble->thursday_time_slot && $doctorAble->thursday_time_slot != 'null' ){
                $thursday_time_slot = json_decode($doctorAble->thursday_time_slot);
            }
            $friday_availability = $doctorAble->friday_availability;
            if($doctorAble->friday_time_slot && $doctorAble->friday_time_slot != 'null' ){
                $friday_time_slot = json_decode($doctorAble->friday_time_slot);
            }
            $saturday_availability = $doctorAble->saturday_availability;
            if($doctorAble->saturday_time_slot && $doctorAble->saturday_time_slot != 'null' ){
                $saturday_time_slot = json_decode($doctorAble->saturday_time_slot);
            }
        }
        $time_slot = TIME_SLOTS;
// dd($friday_time_slot);
        return view('admin.doctors.availability',compact('page_heading','module_heading','time_slot',
        'sunday_availability',
        'sunday_time_slot',
        'monday_availability',
        'monday_time_slot',
        'tuesday_availability',
        'tuesday_time_slot',
        'wednesday_availability',
        'wednesday_time_slot',
        'thursday_availability',
        'thursday_time_slot',
        'friday_availability',
        'friday_time_slot',
        'saturday_availability',
        'saturday_time_slot',
        'doctor_id',
        'doctor',
        'days'
        ));

    }

    public function temporaryUnavailable($id){
        if (!get_user_permission('doctors', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Temporary Unavailable";
        $doctor_id = $id;
        $doctor = Doctor::find($id);
        // dd($doctor);
        $time_slot = TIME_SLOTS;
        $page_heading.= '- DR '.$doctor->user->name;
        return view('admin.doctors.temporaryUnavailable',compact('page_heading','time_slot',
        'doctor_id', 'doctor'));


    }

    public function save(Request $request)
    {
        $hospital = Hospital::find($request->prnt_hospital_id ?? $request->hospital_id);

        if (!$hospital) {
            return response()->json(['status' => 0, 'errors' => ['Hospital not found'], 'message' => 'Invalid hospital ID'], 404);
        }

        $parentRoute = route('admin.doctors.index', ['hospital_id' => $request->prnt_hospital_id]);
        if (!empty($hospital->type) && $hospital->type == TYPE_CLINIC) {
            $parentRoute = route('admin.doctors.index', ['clinic_id' => $request->prnt_hospital_id]);
        }

        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $sanitizedDirectPhone = preg_replace('/\D/', '', $request->direct_phone ?? null);
        $request->merge(['phone' => $sanitizedPhone, 'direct_phone' => $sanitizedDirectPhone]);

        $rules = [
            'first_name' => 'required',
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'dial_code' => 'nullable|numeric',
            'phone' => 'nullable|numeric|digits_between:8,12',
            'direct_dial_code' => 'nullable|numeric',
            'direct_phone' => 'nullable|numeric|digits_between:8,12',
            'hospital_id' => 'required_without:prnt_hospital_id|numeric|exists:hospitals,id',
            'prnt_hospital_id' => 'required_without:hospital_id|numeric|exists:hospitals,id',
            'password' => !$request->id ? 'required|min:8' : '',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'language_spoken_id' => 'required|array',
            'language_spoken_id.*' => 'required|numeric',
            'qualification' => 'required|array',
            'qualification.*' => 'required|numeric',
            'specialty' => 'required|array',
            'specialty.*' => 'required|numeric',
            'special_interest' => 'required|array',
            'special_interest.*' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->messages(),
                'message' => 'Validation error occurred'
            ]);
        }

        $id = $request->id;

        // $check_email = Doctor::whereNot('id', $id)->whereHas('user', function($q) use($request){
        //     $q->whereRaw('Lower(email) = ?', [strtolower($request->email)]);
        // })->first();

        $doctorUserId = null;

        if (!empty($id)) {
            $doctorUserId = Doctor::where('id', $id)->value('user_id');
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

        DB::beginTransaction();
        try {
            $name = $request->first_name . ' ' . $request->last_name;
            $language_spoken_id = array_unique($request->language_spoken_id);
            $qualification_id = array_unique($request->qualification);
            $specialty_id = array_unique($request->specialty);
            $special_interest_id = array_unique($request->special_interest);

            if ($id) {
                $doctor = Doctor::find($id);
                if (!$doctor) {
                    throw new \Exception('Doctor not found');
                }
                $user = User::find($doctor->user_id);
            } else {
                $user = new User();
                $user->active = 1;
                $user->email_verified_at = now();
            }

            $user->email = strtolower($request->email);
            $user->name = $name;
            $user->first_name = $request->first_name;
            $user->consultation_fee = $request->consultation_fee;
            $user->last_name = $request->last_name;
            $user->video_conssultant = $request->video_conssultant;

            if ($request->hasfile('image')) {
                $file = $request->file('image');
               // $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
               $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $cleanName = preg_replace('/\s+/', '_', $originalName);
                $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '', $cleanName);

                // ✅ FIX: fallback if empty
                if (empty($cleanName)) {
                    $cleanName = 'file';
                }

                $extension = $file->getClientOriginalExtension();

                $file_name = $cleanName . '_' . time() . uniqid() . "." . $extension;
                $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $user->user_image = $file_name;
            }
            $user->gender = $request->gender;
            $user->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
            $user->phone = str_replace(" ", "", ltrim($request->phone, "0"));
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->role = DOCTOR_ROLE;
            $user->created_by = Auth::user()->id;
            $user->last_updated_by = Auth::user()->id;
            $user->updated_at = now();
            $user->save();

            if ($id) {
                $doctor = Doctor::find($id);
            } else {
                $doctor = new Doctor();
            }

            $doctor->user_id = $user->id;
            $doctor->country_id = $request->country;
            $doctor->referral_id = $request->referral_id;
            $doctor->hospital_id = $request->hospital_id ?? $request->prnt_hospital_id;
            $doctor->profile_desciription = $request->profile_bio;
            $doctor->year_of_experiance = $request->experiences;
            $doctor->license_no = $request->license_no_dha;
            $doctor->license_no_moh = $request->license_no_moh;
            $doctor->license_no_doh = $request->license_no_doh;
            $doctor->license_no_dhcc = $request->license_no_dhcc;
            $doctor->gender = $request->gender;
            $doctor->appointment_dial_code = $request->direct_dial_code ? $request->direct_dial_code : DEFAULT_DIAL_CODE;
            $doctor->appointment_phone = str_replace(" ", "", $request->direct_phone);
            if ($request->hasfile('signature')) {
                            $file = $request->file('signature');
                            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $doctor->signature = $file_name;
                        }
            $doctor->save();

            if ($request->has('departments')) {
                $doctor->departments()->sync($request->departments);
            }
             if ($request->has('document_titles')) {

                        $existingIds = $request->document_ids ?? [];

DoctorDocument::where('doctor_id', $doctor->id)
    ->whereNotIn('id', array_filter($existingIds))
    ->delete();

    foreach ($request->document_titles as $index => $title) {

        if (
            empty($title)
            && empty($request->file('documents')[$index])
        ) {
            continue;
        }

        $docId = $request->document_ids[$index] ?? null;

        if ($docId) {

            $doctorDocument = DoctorDocument::find($docId);

        } else {

            $doctorDocument = new DoctorDocument();

            $doctorDocument->doctor_id = $doctor->id;
        }

        $doctorDocument->title = $title;

        if (
            $request->hasFile('documents')
            && isset($request->file('documents')[$index])
        ) {

            $file = $request->file('documents')[$index];

            $mini = new \Illuminate\Http\Request();

            $mini->files->set('document', $file);

            $res = image_upload(
                $mini,
                config('global.user_image_upload_dir'),
                'document'
            );

            if ($res['status']) {

                $doctorDocument->document = $res['link'];
            }
        }

        $doctorDocument->save();
    }
}

            DoctorLanguageSpoken::where('doctor_id', $doctor->id)->delete();
            foreach ($language_spoken_id as $language_spoken) {
                $doctorLanguageSpoken = new DoctorLanguageSpoken();
                $doctorLanguageSpoken->doctor_id = $doctor->id;
                $doctorLanguageSpoken->language_spoken_id = (int)$language_spoken;
                $doctor->doctorLanguageSpoken()->save($doctorLanguageSpoken);
            }

            DoctorQualifications::where('doctor_id', $doctor->id)->delete();
            foreach ($qualification_id as $qualification) {
                $doctorQualification = new DoctorQualifications();
                $doctorQualification->doctor_id = $doctor->id;
                $doctorQualification->qualification_id = (int)$qualification;
                $doctor->doctorQualifications()->save($doctorQualification);
            }

            DoctorSpecialities::where('doctor_id', $doctor->id)->delete();
            foreach ($specialty_id as $speciality) {
                $doctorSpeciality = new DoctorSpecialities();
                $doctorSpeciality->doctor_id = $doctor->id;
                $doctorSpeciality->speciality_id = (int)$speciality;
                $doctor->doctorSpecialities()->save($doctorSpeciality);
            }

            DoctorIntrests::where('doctor_id', $doctor->id)->delete();
            foreach ($special_interest_id as $language_interest) {
                $doctorInterest = new DoctorIntrests();
                $doctorInterest->doctor_id = $doctor->id;
                $doctorInterest->special_intrest_id = (int)$language_interest;
                $doctor->doctorIntrests()->save($doctorInterest);
            }

            DB::commit();

            return response()->json([
                'status' => 1,
                'errors' => [],
                'message' => $id ? 'Doctor updated successfully' : 'Doctor added successfully',
                'oData' => ['redirect' => $request->prnt_hospital_id ? $parentRoute : route('admin.doctors.index')]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 0, 'errors' => ['exception' => $e->getMessage()], 'message' => 'Failed to save doctor data'], 500);
        }
    }

    public function appointmentLoadData(REQUEST $request){
        $users = DoctorPatientAppointment::query();
        if(!empty($request->doctor_i))
        {
            $users =$users->where('doctor_patient_appointments.doctor_id','=',$request->doctor_id);
        }

        $users =$users->leftJoin('users', 'users.id', '=', 'doctor_patient_appointments.user_id')
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

    public function temporary_load(REQUEST $request){
        $users = DoctorTemporaryUnavailable::query();

        $users->leftJoin('doctors', 'doctors.id', '=', 'doctor_temporary_unavailables.doctor_id')
        ->leftJoin('users', 'users.id', '=', 'doctors.user_id')
        ->where('doctor_temporary_unavailables.doctor_id', $request->doctor_id ?? null)
        ->select('doctor_temporary_unavailables.*', 'doctor_temporary_unavailables.id as unavailable_id','doctors.*', 'users.email','users.name','users.last_name', 'users.dial_code','users.phone')
        ->orderBy('doctor_temporary_unavailables.id','desc');

        return DataTables::eloquent($users)
        ->addColumn('action', function($user) {
            // dd($user);
             $action = '<div class="dropdown mt-4 mt-sm-0">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu">';

                // if (get_user_permission('doctors', 'r')) {
                //     $action.='<a class="dropdown-item complete-link" href="'.route('admin.doctors.show',['id'=>$user->id]).'">View </a>';
                // }
                if (get_user_permission('doctors', 'u')) {
                    $action.='<a class="dropdown-item edit-unavailable" href="#!" data-unavailable-date="'.$user->unavailable_date.'" data-unavailable-id="'.$user->unavailable_id.'" data-bs-toggle="modal" data-bs-target="#appointment-modal"> Edit </a>';
                }
                if (get_user_permission('doctors', 'u')) {
                    $action.='<a class="dropdown-item" data-role="unlink"
                        data-message="Do you want to remove the Unavailability?  This may be linked with other sections"
                        href="'.route('admin.doctors.temporaryUnavailableDelete', ['id' => encrypt($user->unavailable_id)]).'">
                        <i class="flaticon-delete-1"></i> Delete
                      </a>';
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
    public function load_data(Request $request)
    {
        $page = $request->start ? ($request->start / $request->length) + 1 : 1;
        $itemsPerPage = $request->length ? (int)$request->length : 10;
        $startIndex = ($page - 1) * $itemsPerPage;

        $query = Doctor::query();

    $query->select('doctors.*', 'users.name as user_name', 'country.name as country_name', 'hospitals.name as hospital_name')
      ->leftJoin('users', 'users.id', '=', 'doctors.user_id')
      ->leftJoin('country', 'country.id', '=', 'doctors.country_id')
      ->leftJoin('hospitals', 'hospitals.id', '=', 'doctors.hospital_id')
      ->leftJoin('department_doctors', 'department_doctors.doctor_id', '=', 'doctors.id')
      ->leftJoin('doctor_specialities', 'doctor_specialities.doctor_id', '=', 'doctors.id')
      ->leftJoin('country_of_origins', 'country_of_origins.id', '=', 'doctors.country_id')
      ->leftJoin('doctor_intrests', 'doctor_intrests.doctor_id', '=', 'doctors.id')
      ->groupBy('doctors.id', 'users.id', 'country.id', 'hospitals.id');

            //   if ($request->hospital_id) {
            //       $query->where('doctors.hospital_id', $request->hospital_id);
            //       $params['hospital_id'] = $request->hospital_id;
            //   }
            // dd($request->all());
            if ($request->has('hospital_id') && $request->hospital_id) {
                $query->where('doctors.hospital_id', $request->hospital_id);
            }

            if ($request->has('clinic_id') && $request->clinic_id) {
                $query->where('doctors.hospital_id', $request->clinic_id);
            }

            if ($request->has('search') && ($request->search['filters'] ?? null)) {
                $filters = $request->search['filters'];
                $carbonDate =( $filters['booking_to'] )? Carbon::createFromFormat('d-m-Y', $filters['booking_to']):'';
                $fromDate =( $filters ['booking_from'] )?  Carbon::createFromFormat('d-m-Y',$filters ['booking_from']):'';
                 $toDate = $carbonDate;

        // Check if from_date and to_date are the same
        if (!empty($carbonDate) && !empty($fromDate) && $fromDate->isSameDay($toDate)) {
            // Add one day to to_date
            $toDate->addDay();

            // Update the filters array
            $filters['booking_to'] = $toDate->format('d-m-Y');
        }
                if ($request->search['filters']['booking_from'] ?? null) {
                    $date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->search['filters']['booking_from'])->startOfDay()->format('Y-m-d');
                    $query->where('doctors.created_at', '>=', $date);
                }

                if ($filters['booking_to'] ?? null) {
                    if(!$carbonDate->isToday()){
                    $date = \Carbon\Carbon::createFromFormat('d-m-Y', $filters['booking_to'])->format('Y-m-d');
                    $query->where('doctors.created_at', '<=', $date);
                    }
                }

                if ($request->search['filters']['hospital_id'] ?? null) {
                    $query->where('doctors.hospital_id', $request->search['filters']['hospital_id']);
                }
                if ($request->search['filters']['department_id'] ?? null) {
                    $query->where('department_doctors.department_id', $request->search['filters']['department_id']);
                }
                if ($request->search['filters']['speciality_id'] ?? null) {
                    $query->where('doctor_specialities.speciality_id', $request->search['filters']['speciality_id']);
                }
                if ($request->search['filters']['special_interest_id'] ?? null) {
                    $query->where('doctor_intrests.special_intrest_id', $request->search['filters']['special_interest_id']);
                }
                if ($request->search['filters']['country_id'] ?? null) {
                  // dd($request->search['filters']['country_id']);
                    $query->where('doctors.country_id', $request->search['filters']['country_id']);
                }
                if ($request->search['filters']['clinic_status'] != "") {
                    $query->where('users.active', $request->search['filters']['clinic_status']);
                }
            }

            $users = $query->select(['doctors.*', 'users.email', 'users.first_name', 'users.last_name',
            'users.dial_code', 'users.phone', 'country.name as country_name'])
            ->orderBy('doctors.id', 'desc');

        return DataTables::eloquent($users)
            ->addColumn('action', function ($user) use ($request) {
                $action = '<div class="dropdown mt-4 mt-sm-0">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-dots-horizontal-rounded"></i>
                    </button>
                    <div class="dropdown-menu">';

                // Uncomment and modify the permission checks as needed
                // if (get_user_permission('doctors', 'r')) {
                //     $action .= '<a class="dropdown-item complete-link" href="'.route('admin.doctors.show', ['id' => $user->id]).'">View</a>';
                // }

                if (get_user_permission('doctors', 'u')) {
                    $params = ['id' => $user->id];
                    if ($request->hospital_id) {
                        $params['hospital_id'] = $request->hospital_id;
                    }

                    if ($request->clinic_id) {
                        $params['clinic_id'] = $request->clinic_id;
                    }

                    $hospital_id=($request->hospital_id)?'?hospital_id='.$request->hospital_id:'';
                    $clinic_id=($request->clinic_id)?'?clinic_id='.$request->clinic_id:'';
                    $action .= '<a class="dropdown-item complete-link" href="'.route('admin.doctors.edit', $params).'">Edit Doctor</a>';
                    $action.='<a class="dropdown-item" data-role="unlink"
                        data-message="Do you want to remove the doctor?  This may be linked with other sections"
                        href="'.route('admin.doctors.delete', ['id' => encrypt($user->id)]).'">
                        <i class="flaticon-delete-1"></i> Delete Doctor
                      </a>';
                }

                if (get_user_permission('doctors', 'r')) {
                    $action .= '<a class="dropdown-item" href="'.route('admin.appointments.index', ['doctor_id' => $user->id]).'">View Appointments</a>';
                    $action .= '<a class="dropdown-item complete-link" href="'.route('admin.doctors.availability', ['id' => $user->id]).$hospital_id.$clinic_id.'">Schedule Appointment Slots</a>';
                    $action .= '<a class="dropdown-item complete-link" href="'.route('admin.doctors.temporaryUnavailable', ['id' => $user->id]).'">Mark Temporary Unavailability</a>';
                    $action .= '<a class="dropdown-item complete-link" href="'.route('admin.doctors.holiday', ['id' => $user->id]).'">Mark Holiday Date</a>';
                    $action .= '<a class="dropdown-item complete-link" href="'.route('admin.doctors.instantAppointment', ['id' => $user->id]).'">Mark Instant Appointment Date</a>';
//                    $action .= '<a class="dropdown-item complete-link" href="#">Reports</a>';
                }

                $action .= '</div>
                </div>';

                return $action;
            })
            ->addColumn('sl_no', function($user) use (&$startIndex) {
                return ++$startIndex;
            })
            ->addColumn('phone_number', function ($item) {
                return ($item->phone)?'+' . $item->dial_code . $item->phone:'';
            })
            ->addColumn('qualifications', function ($item) {
                return $item->qualifications ? $item->qualifications->pluck('title')->implode(', ') : null;
            })
            ->addColumn('hospital_name', function ($item) {
                return $item->hospital->name_en ?? null;
            })
            ->addColumn('dr_name', function ($item) {
                return $item->user->name ? ('<span class="d-flex">'.$item->user->name.' '.($item->user->email_verified_at ? '<img class="verified-account" src="'.asset('admin-assets/assets/images/verified-icon.png').'" alt="verification Icon">' : ''). '</span>') : null;
            })
            ->addColumn('departments', function ($item) {
                return $item->departments ? $item->departments->pluck('title')->implode(', ') : null;
            })
            ->addColumn('specialities', function ($item) {
                return $item->specialities ? $item->specialities->pluck('name_en')->implode(', ') : null;
            })
            ->addColumn('interests', function ($item) {
                return $item->interests ? $item->interests->pluck('title')->implode(', ') : null;
            })
            ->addColumn('status', function($item) {
                return '<div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                        <input type="checkbox" class="form-check-input change_status" data-id="'.$item->user_id.'"
                                    data-url="'.url('admin/doctors/change_status').'"
                                    '.($item->user->active == 1 ? 'checked' : '').'>
                    </div>';
            })
            ->rawColumns(['status', 'action', 'dr_name'])
            ->toJson();
    }

    public function patientAppointmentCancel(REQUEST $request){
        DB::beginTransaction();
        try {

            $doctor = DoctorPatientAppointment::find($request->appointment_id);

                $doctor->booking_status   = "Cancelled";
                $doctor->updated_at = gmdate('Y-m-d H:i:s');
                $doctor->reason_cancel  = $request->reason_cancel;
                $doctor->save();

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

    public function patientAppointmentConfirmed(REQUEST $request){
        DB::beginTransaction();
        try {

            $doctor = DoctorPatientAppointment::find($request->appointment_id);

                $doctor->booking_status   = 'Confirmed'?? null;
                $doctor->updated_at = gmdate('Y-m-d H:i:s');


                $doctor->save();

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
    public function patientAppointmentCompleted(REQUEST $request){
        DB::beginTransaction();
        try {
                $doctor = DoctorPatientAppointment::find($request->appointment_id);
                $doctor->booking_status   = "Completed";
                $doctor->updated_at = gmdate('Y-m-d H:i:s');
                $doctor->save();

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

    public function patientAppointmentRescheduled(REQUEST $request){
        DB::beginTransaction();
        try {
                $doctor = DoctorPatientAppointment::find($request->appointment_id);
                $doctor->previous_booking_date = $doctor->booking_date;
                $doctor->previous_booking_time_slot    =  $doctor->booking_time_slot;

                $doctor->reason_reschedule  = $request->reason_reschedule;
                $doctor->booking_date = $request->booking_date;
                $doctor->booking_time_slot    =  $request->booking_time_slot;
                $doctor->booking_status   = "Rescheduled";
                $doctor->created_at = gmdate('Y-m-d H:i:s');
                $doctor->updated_at = gmdate('Y-m-d H:i:s');
                $doctor->save();

                $RescheduleAppointment = new DoctorRescheduleAppointment();

                $RescheduleAppointment->doctor_id = $doctor->doctor_id;
                $RescheduleAppointment->patient_appointment_id = $request->appointment_id;
                $RescheduleAppointment->reschedule_patient_booking_date = $request->booking_date;
                $RescheduleAppointment->reschedule_patient_time_slot    =  $request->booking_time_slot;
                $RescheduleAppointment->save();
                DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor Appointment Save Successfully";

         return response()->json( [ 'success' => 'Doctor Appointment Save Successfully!' ] );
        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }


    }
    public function patienttAppointmentSave(REQUEST $request){
        DB::beginTransaction();
        try {


                $FourDigitRandomNumber = rand(1231,7879);
                $doctor = new DoctorPatientAppointment();
                $doctor->doctor_id   =  $request->doctor_id;
                $doctor->booking_id    = '#MYDW'.$FourDigitRandomNumber;
                $doctor->user_id  =  $request->patient_id;
                $doctor->booking_date = $request->booking_date;
                $doctor->booking_time_slot    =  $request->booking_time_slot;
                $doctor->booking_status   = "pending";
                $doctor->created_at = gmdate('Y-m-d H:i:s');
                $doctor->updated_at = gmdate('Y-m-d H:i:s');
                $doctor->save();
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
    public function instantAppointmentSaveold(REQUEST $request){
        DB::beginTransaction();
        try {

           $instantAppointment = $request->instant_appointment_date;
            foreach($instantAppointment as $instantAppointment){

                $doctor = new DoctorInstantAppointment();
                $doctor->doctor_id   =  $request->doctor_id;
                $doctor->instant_appointment_date    =(string)$instantAppointment;
                $doctor->created_at = gmdate('Y-m-d H:i:s');
                $doctor->updated_at = gmdate('Y-m-d H:i:s');
                $doctor->save();
            }
            DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor InstantAppointment Save Successfully";
            return view('admin.doctors.index',compact('page_heading'));

        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }


    }

    public function instantAppointment($id){
        $page_heading="Instant Appointment";
        $module_heading="Doctors";
        $doctor_id = $id;
        $doctor = Doctor::find($doctor_id);
        // dd($doctor->doctorInstantAppointment);
        return view('admin.doctors.instantAppointment',compact('page_heading','module_heading',
        'doctor_id', 'doctor'));
    }

    public function instantAppointmentSave(Request $request) {
        // Define validation rules
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'instant_appointment_date' => 'required|array',
            'instant_appointment_date.*' => 'required|date_format:d-m-Y'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => "0",
                'errors' => $validator->messages(),
                'message' => "Validation error occurred"
            ]);
        }

        $instantAppointmentDates = $request->instant_appointment_date;

        foreach($instantAppointmentDates as &$date) {
            $date = \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
        }

        $existDates = DoctorInstantAppointment::where('doctor_id', $request->doctor_id)->whereIn('instant_appointment_date', $instantAppointmentDates)->get();

        if($existDates && count($existDates)){
            $existDates = array_column($existDates->toArray(), 'instant_appointment_date');
            return response()->json([
                'status' => "3",
                'errors' => 'Duplicate Date',
                'message' => "Sorry, selected date is already added before",
                "dates" => $existDates
            ]);
        }

        DB::beginTransaction();
        try {
            foreach($instantAppointmentDates as $select_date) {
                $doctor = new DoctorInstantAppointment();
                $doctor->doctor_id = $request->doctor_id;
                $doctor->instant_appointment_date = $select_date;
                $doctor->created_at = now();
                $doctor->updated_at = now();
                $doctor->save();
            }

            DB::commit();

            return response()->json([
                'status' => "1",
                'errors' => [],
                'message' => "Doctor InstantAppointment saved successfully!",
                'oData' => [
                    'redirect' => route('admin.doctors.index')
                ]
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => "0",
                'errors' => [],
                'message' => "Failed to save doctor instant appointment. Error: " . $e->getMessage()
            ]);
        }
    }

    public function instantAppointmentDelete($id) {
        $status = "0";
        $message = "";
        $o_data = [];

        $id = decrypt($id);
        $row = DoctorInstantAppointment::find($id);
        if ($row) {
            $row->delete();
            $status = "1";
            $message = "Doctor instant appointment removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }

    public function holiday($id){
        if (!get_user_permission('doctors', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading="Holiday";
        $doctor_id = $id;
        $doctor = Doctor::find($id);
        $holidays = DoctorHolidays::where('doctor_id', $doctor_id)->get();
        return view('admin.doctors.holiday',compact('page_heading','doctor_id', 'doctor', 'holidays'));
    }
    public function holiday_saveold(REQUEST $request){
        DB::beginTransaction();
        try {
            $holidayNames = $request->holiday_name;
            $dates = $request->date;
            // Combine arrays
            $combinedArray = [];
            for ($i = 0; $i < count($holidayNames); $i++) {
                $combinedArray[] = [
                    "holiday_name" => $holidayNames[$i],
                    "date" => $dates[$i]
                ];
            }
            foreach($combinedArray as $combinedArray){

                $doctor = new DoctorHolidays();
                $doctor->doctor_id   =  $request->doctor_id;
                $doctor->holiday_name    = $combinedArray['holiday_name'];
                $doctor->holiday_date    =   $combinedArray['date'];
                $doctor->created_at = gmdate('Y-m-d H:i:s');
                $doctor->updated_at = gmdate('Y-m-d H:i:s');
                $doctor->save();
            }
            DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor holiday Save Successfully";
            return view('admin.doctors.index',compact('page_heading'));

        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }

    }

    public function holiday_save(Request $request) {
        // Custom validation rule for unique dates
        Validator::extend('unique_dates', function($attribute, $value, $parameters, $validator) {
            return count($value) === count(array_unique($value));
        }, 'The :attribute field has duplicate dates.');

        // Define base validation rules
        $rules = [
            'doctor_id' => 'required|exists:doctors,id',
        ];

        // Conditionally add rules for holiday_date fields if they exist
        if (!empty($request->holiday_date)) {
            $rules['holiday_name'] = 'required|array';
            $rules['holiday_name.*'] = 'required|string';
            $rules['holiday_date'] = 'required|array';
            $rules['holiday_date.*'] = 'required|date_format:d-m-Y';
        }

        // Perform validation
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => "0",
                'errors' => $validator->messages(),
                'message' => "Validation error occurred"
            ]);
        }

        DB::beginTransaction();
        try {
            // Delete existing holidays for the doctor
            DoctorHolidays::where('doctor_id', $request->doctor_id)->delete();

            // Save the new holidays if dates are provided
            if (!empty($request->holiday_date)) {
                $holidayNames = $request->holiday_name;
                $dates = $request->holiday_date;
                $combinedArray = [];

                for ($i = 0; $i < count($holidayNames); $i++) {
                    $combinedArray[] = [
                        "holiday_name" => $holidayNames[$i],
                        "date" => \Carbon\Carbon::createFromFormat('d-m-Y', $dates[$i])->format('Y-m-d')
                    ];
                }

                foreach ($combinedArray as $data) {
                    $doctorHoliday = new DoctorHolidays();
                    $doctorHoliday->doctor_id = $request->doctor_id;
                    $doctorHoliday->holiday_name = $data['holiday_name'];
                    $doctorHoliday->holiday_date = $data['date'];
                    $doctorHoliday->created_at = now();
                    $doctorHoliday->updated_at = now();
                    $doctorHoliday->save();
                }
            }

            DB::commit();
            return response()->json([
                'status' => "1",
                'errors' => [],
                'message' => "Doctor holiday saved successfully!",
                'oData' => [
                    'redirect' => route('admin.doctors.index')
                ]
            ]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => "0",
                'errors' => [],
                'message' => "Failed to save doctor holiday. Error: " . $e->getMessage()
            ]);
        }
    }


    public function holiday_delete($id) {
        $status = "0";
        $message = "";
        $o_data['redirect'] = route('admin.doctors.index');

        $id = decrypt($id);
        $row = DoctorHolidays::where('doctor_id', $id)->get();
        if (count($row)) {
            DoctorHolidays::where('doctor_id', $id)->delete();
            $status = "1";
            $message = "Doctor Holidays removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
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
        // dd($unavailable_date);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();

        }else{

            $messageResponse = 'No record found';
            $list =  [];
            $data = DoctorTemporaryUnavailable::where('doctor_id', $request->doctor_id)->where('unavailable_date', $unavailable_date)->first();
            // dd($data);
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

    public function temporaryUnavailableSave(REQUEST $request){
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'unavailable_date' => 'required|date_format:d-m-Y',
            'unavailable_timeslot' => 'array',
            'unavailable_timeslot.*' => 'required|string' // Add any specific validation for the timeslot array elements
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();

            return response()->json([
                'status' => $status,
                'message' => $message,
                'errors' => $errors,
                'oData' => []
            ], 200);
        }

        DB::beginTransaction();
        try {
            // dd($request->all());
            $unavailable_date = \DateTime::createFromFormat('d-m-Y', $request->unavailable_date)->format('Y-m-d');
            $doctor = DoctorTemporaryUnavailable::where('unavailable_date', $unavailable_date)->where('doctor_id', $request->doctor_id)->first();
            if($request->id ?? null){
                $doctor = DoctorTemporaryUnavailable::find($request->id);
            }

            if((!$request->unavailable_timeslot || !count($request->unavailable_timeslot)) && $doctor){
                $doctor->delete();
                $status = "1";
                $page_heading="Doctors";
                $message = "Doctor temporary Unavailable Removed Successfully";
                DB::commit();
                return response()->json([
                    'status' => "1",
                    'errors' => [],
                    'message' => $message,
                    'oData' => [
                        'redirect' => route('admin.doctors.index')
                    ]
                ]);
            }

            if(!$doctor){
                $doctor = new DoctorTemporaryUnavailable();
            }
            // dd($doctor->toArray());
            $doctor->doctor_id   =  $request->doctor_id;
            $doctor->unavailable_date    = $unavailable_date;
            $doctor->unavailable_timeslot    =  json_encode($request->unavailable_timeslot);
            $doctor->created_at = gmdate('Y-m-d H:i:s');
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->save();

            DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor temporary Unavailable Save Successfully";
            return response()->json([
                'status' => "1",
                'errors' => [],
                'message' => $message,
                'oData' => [
                    'redirect' => route('admin.doctors.index')
                ]
            ]);

        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to save temporary Unavailable " . $e->getMessage();
        }

    }
    public function availability_save(REQUEST $request){
        DB::beginTransaction();
        try {
        //   dd($request->all());
            $doctor = DoctorAvailability::find($request->doctor_id);

            if($doctor === null){
                $doctor = new DoctorAvailability();
            }

            $doctor->doctor_id = $request->doctor_id;
            $doctor->sunday_availability = $request->sunday_availability ?? "0";
            $doctor->sunday_time_slot = ($doctor->sunday_availability && isset($request->booking_time_slot['sun'])) ? json_encode($request->booking_time_slot['sun']) : null;
            $doctor->monday_availability = $request->monday_availability ?? "0";
            $doctor->monday_time_slot = ($doctor->monday_availability && isset($request->booking_time_slot['mon'])) ? json_encode($request->booking_time_slot['mon']) : null;
            $doctor->tuesday_availability = $request->tuesday_availability ?? "0";
            $doctor->tuesday_time_slot = ($doctor->tuesday_availability && isset($request->booking_time_slot['tue'])) ? json_encode($request->booking_time_slot['tue']) : null;
            $doctor->wednesday_availability = $request->wednesday_availability ?? "0";
            $doctor->wednesday_time_slot = ($doctor->wednesday_availability && isset($request->booking_time_slot['wed'])) ? json_encode($request->booking_time_slot['wed']) : null;
            $doctor->thursday_availability = $request->thursday_availability ?? "0";
            $doctor->thursday_time_slot = ($doctor->thursday_availability && isset($request->booking_time_slot['thu'])) ? json_encode($request->booking_time_slot['thu']) : null;
            $doctor->friday_availability = $request->friday_availability ?? "0";
            $doctor->friday_time_slot = ($doctor->friday_availability && isset($request->booking_time_slot['fri'])) ? json_encode($request->booking_time_slot['fri']) : null;
            $doctor->saturday_availability = $request->saturday_availability ?? "0";
            $doctor->saturday_time_slot = ($doctor->saturday_availability && isset($request->booking_time_slot['sat'])) ? json_encode($request->booking_time_slot['sat']) : null;
            $doctor->created_at = gmdate('Y-m-d H:i:s');
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            // dd($doctor);
            $doctor->save();

            DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor Availability Save Successfully";
            return redirect()->back()->with('success',  $message);

        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }

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
        $row = Doctor::find($id);
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
            $message = "Doctor removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

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
            Mail::to($user->email)->send(new ActivateAccountEmail($user, 'doctorlogin'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function temporaryUnavailableDelete($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];

        $id = decrypt($id);
        $row = DoctorTemporaryUnavailable::find($id);
        if ($row) {
            $row->delete();
            $status = "1";
            $message = "Record removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

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
$booking_time_slot = (array)$users[0]['booking_time_slot'];

$time_slot = [
    "08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30",
    "13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
    "18:00","18:30","19:00","19:30","20:00"
];
    return view('admin.doctors.patientAppointment.viewAppointment',compact('page_heading','booking_time_slot','users','time_slot','doctor_id'));
}

public function getDepartmentDoctors ($department_id, $hospital_id = null) {
    $query = Doctor::join('users', 'doctors.user_id', '=', 'users.id')
        ->with('user')
        ->join('department_doctors', 'doctors.id', '=', 'department_doctors.doctor_id')
        ->where('department_doctors.department_id', $department_id)
        ->where('users.active', 1);

if (isset($hospital_id) && !is_null($hospital_id) && $hospital_id!='undefined') {
    $query->where('doctors.hospital_id', $hospital_id);  // Use the correct table name for the hospital_id column
}

$data = $query->orderBy('users.name', 'asc')
        ->select('doctors.*', 'users.name as user_name') // Include the user name in the selection
        ->get()
        ->toArray();

    return response()->json($data);
}

public function getHospitalDoctors($hospital_id) {
    $data = Doctor::join('users', 'doctors.user_id', '=', 'users.id')->with('user')
    ->where('doctors.hospital_id', $hospital_id)
    ->where('users.active', 1)
    ->orderBy('users.name', 'asc')
    ->select('doctors.*', 'users.name as user_name')
    ->get();

    return response()->json($data);
}
public function import_export(){
    $page_heading = "Bulk Upload";
    $hospital_list = Hospital::join('users', 'users.id', '=', 'hospitals.user_id')
            ->where(['users.deleted' => 0])
            ->select(['hospitals.id', 'hospitals.name_en'])
            ->orderBy('name_en', 'asc')
            ->get();
    return view('admin.bulkupload.index',compact('page_heading','hospital_list'));
}
public function export_excel(){
    $hospital_id = $_GET['hospital_id']??0;
    $hospital = Hospital::find($hospital_id);
    $file_name = str_replace(" ","_",$hospital->name_en);
    $file_name = strtolower($file_name).'.xlsx';
    return Excel::download(new DoctorExport(1,$hospital_id), $file_name);
}

public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $import = new DoctorImport();
            Excel::import($import, $request->file('file'));

            $validRecords = $import->getValidRecords();
            //printr($validRecords); exit;
            $err_msg='';
            $any_succes=0;
            foreach($validRecords as $record){
                $check_email = User::whereRaw('Lower(email) = ?', [strtolower($record['email'])])->get();
                if($check_email->count() > 0){
                    $user_data = $check_email->first();
                    if($user_data->role == DOCTOR_ROLE){
                        DB::beginTransaction();
                        try {
                            $gender = 3;
                            if($record['gender'] == 'Female'){
                                $gender = 2;
                            }else if($record['gender'] == 'male'){
                                $gender = 1;
                            }
                            $user = User::find($user_data->id);
                            //$user->email = strtolower($record['email']);
                            $user->name = $record['first_name']." ".$record['last_name'];
                            $user->first_name = $record['first_name'];
                            $user->last_name = $record['last_name'];
                            $user->gender = $gender;
                            $user->dial_code = $record['clinic_dialcode']??'';
                            $user->phone = str_replace(" ", "", ltrim($record['clinic_number'], "0"));
                            if($record['password'] != ''){
                                $user->password = Hash::make($record['password']);
                            }
                            
                            $user->email_verified_at = now();
                            $user->last_updated_by = Auth::user()->id;
                            $user->updated_at = now();
                            $user->save();

                            $check_doctor = Doctor::where(['user_id'=>$user->id])->get();
                            if($check_doctor->count() > 0){
                                $doctor = Doctor::find($check_doctor->first()->id);
                            }else{
                                $doctor = new Doctor();
                                $doctor->user_id = $user->id;
                            }
                                $doctor->country_id = $record['country_of_origin'];
                                $doctor->hospital_id = $record['hospital_id'];
                                $doctor->profile_desciription = $record['profle'];
                                $doctor->year_of_experiance = $record['year_of_experience'];
                                // $doctor->license_no = $record['dha_license_no'];
                                // $doctor->license_no_moh = $record['moh_license_no'];
                                // $doctor->license_no_doh = $record['doh_license_no'];
                                // $doctor->license_no_dhcc = $record['dhcc_license_no'];
                                $doctor->gender = $gender;
                                $doctor->appointment_dial_code = $record['direct_dial_code'];
                                $doctor->appointment_phone = str_replace(" ", "", $record['direct_contact_number_for_appoitment']);
                                $doctor->temp_photo_file_name = $record['photo_file_name']??'';
                                $doctor->save();

                                if($record['department']){
                                    $doctor->departments()->sync([$record['department']]);
                                }

                                DoctorLanguageSpoken::where(['doctor_id'=>$doctor->id])->delete();
                                if(!empty($record['language_spoken'])){
                                    foreach ($record['language_spoken'] as $language_spoken) {
                                        $doctorLanguageSpoken = new DoctorLanguageSpoken();
                                        $doctorLanguageSpoken->doctor_id = $doctor->id;
                                        $doctorLanguageSpoken->language_spoken_id = (int)$language_spoken;
                                        $doctor->doctorLanguageSpoken()->save($doctorLanguageSpoken);
                                    }
                                }

                                DoctorQualifications::where(['doctor_id'=>$doctor->id])->delete();
                                if(!empty($record['qualification'])){
                                    foreach ($record['qualification'] as $qualification) {
                                        $doctorQualification = new DoctorQualifications();
                                        $doctorQualification->doctor_id = $doctor->id;
                                        $doctorQualification->qualification_id = (int)$qualification;
                                        $doctor->doctorQualifications()->save($doctorQualification);
                                    }
                                }

                                DoctorSpecialities::where(['doctor_id'=>$doctor->id])->delete();
                                if(!empty($record['speciality'])){
                                    foreach ($record['speciality'] as $speciality) {
                                        $doctorSpeciality = new DoctorSpecialities();
                                        $doctorSpeciality->doctor_id = $doctor->id;
                                        $doctorSpeciality->speciality_id = (int)$speciality;
                                        $doctor->doctorSpecialities()->save($doctorSpeciality);
                                    }
                                }

                                DoctorIntrests::where(['doctor_id'=>$doctor->id])->delete();
                                if(!empty($record['special_intrests'])){
                                    foreach ($record['special_intrests'] as $language_interest) {
                                        $doctorInterest = new DoctorIntrests();
                                        $doctorInterest->doctor_id = $doctor->id;
                                        $doctorInterest->special_intrest_id = (int)$language_interest;
                                        $doctor->doctorIntrests()->save($doctorInterest);
                                    }
                                }

                                

                            DB::commit();
                            $any_succes = 1;
                        } catch (Exception $e) {
                            DB::rollback();
                            $err_msg.=$record['first_name'].' faild to create due to '.$e->getMessage().'<br>';
                        }
                    }else{
                        $err_msg.=$record['email'].' already exist in our db'.'<br>';
                    }
                }else{
                    DB::beginTransaction();
                    try {
                        $gender = 1;
                        if($record['gender'] == 'Female'){
                            $gender = 2;
                        }else if($record['gender'] == 'Others'){
                            $gender = 3;
                        }
                        $user = new User();
                        $user->email = strtolower($record['email']);
                        $user->name = $record['first_name']." ".$record['last_name'];
                        $user->first_name = $record['first_name'];
                        $user->last_name = $record['last_name'];
                        $user->gender = $gender;
                        $user->dial_code = $record['clinic_dialcode']??'';
                        $user->phone = str_replace(" ", "", ltrim($record['clinic_number'], "0"));
                        $user->password = Hash::make($record['password']);
                        $user->role = DOCTOR_ROLE;
                        $user->active = 0;
                        $user->email_verified_at = now();
                        $user->created_by = Auth::user()->id;
                        $user->last_updated_by = Auth::user()->id;
                        $user->updated_at = now();
                        $user->save();

                        $doctor = new Doctor();
                        $doctor->user_id = $user->id;
                        $doctor->country_id = $record['country_of_origin'];
                        $doctor->hospital_id = $record['hospital_id'];
                        $doctor->profile_desciription = $record['profle'];
                        $doctor->year_of_experiance = $record['year_of_experience'];
                        // $doctor->license_no = $record['dha_license_no'];
                        // $doctor->license_no_moh = $record['moh_license_no'];
                        // $doctor->license_no_doh = $record['doh_license_no'];
                        // $doctor->license_no_dhcc = $record['dhcc_license_no'];
                        $doctor->gender = $gender;
                        $doctor->appointment_dial_code = $record['direct_dial_code'];
                        $doctor->appointment_phone = str_replace(" ", "", $record['direct_contact_number_for_appoitment']);
                        $doctor->temp_photo_file_name = $record['photo_file_name']??'';
                        $doctor->save();

                        if($record['department']){
                            $doctor->departments()->sync([$record['department']]);
                        }

                        if(!empty($record['language_spoken'])){
                            foreach ($record['language_spoken'] as $language_spoken) {
                                $doctorLanguageSpoken = new DoctorLanguageSpoken();
                                $doctorLanguageSpoken->doctor_id = $doctor->id;
                                $doctorLanguageSpoken->language_spoken_id = (int)$language_spoken;
                                $doctor->doctorLanguageSpoken()->save($doctorLanguageSpoken);
                            }
                        }

                        if(!empty($record['qualification'])){
                            foreach ($record['qualification'] as $qualification) {
                                $doctorQualification = new DoctorQualifications();
                                $doctorQualification->doctor_id = $doctor->id;
                                $doctorQualification->qualification_id = (int)$qualification;
                                $doctor->doctorQualifications()->save($doctorQualification);
                            }
                        }

                        if(!empty($record['speciality'])){
                            foreach ($record['speciality'] as $speciality) {
                                $doctorSpeciality = new DoctorSpecialities();
                                $doctorSpeciality->doctor_id = $doctor->id;
                                $doctorSpeciality->speciality_id = (int)$speciality;
                                $doctor->doctorSpecialities()->save($doctorSpeciality);
                            }
                        }

                        if(!empty($record['special_intrests'])){
                            foreach ($record['special_intrests'] as $language_interest) {
                                $doctorInterest = new DoctorIntrests();
                                $doctorInterest->doctor_id = $doctor->id;
                                $doctorInterest->special_intrest_id = (int)$language_interest;
                                $doctor->doctorIntrests()->save($doctorInterest);
                            }
                        }

                        DB::commit();
                        $any_succes = 1;
                    } catch (Exception $e) {
                        DB::rollback();
                        $err_msg.=$record['first_name'].' faild to create due to '.$e->getMessage().'<br>';
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
        $extractToPath = storage_path('app/uploads/extracted_doctor');

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
            exec("php " . base_path() . "/artisan app:extract-doctor-images > /dev/null 2>&1 & ");

            // Optionally, delete the uploaded ZIP file after extraction
            Storage::delete($zipFilePath);

            return back()->with('success', 'ZIP file extracted successfully!');
        } else {
            return back()->with('error', 'Failed to open the ZIP file.');
        }
    }

}

?>
