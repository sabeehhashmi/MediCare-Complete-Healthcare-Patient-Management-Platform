<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorSpecialities;
use App\Models\DoctorTemporaryUnavailable;
use App\Models\DoctorPatientAppointment;
use App\Models\DoctorRescheduleAppointment;
use App\Models\HospitalDoctorFeedback;
use App\Models\MydrworldServiceFeedback;
use App\Models\DoctorHolidays;
use App\Models\DoctorInstantAppointment;
use App\Models\DoctorIntrests;
use App\Models\DoctorAvailability;
use App\Models\DoctorLanguageSpoken;
use App\Models\DoctorQualifications;
use App\Models\SpecialIntrests;
use App\Models\InsurencePolicy;
use App\Models\Specialty;
use App\Models\MedicalCondition;
use App\Models\CountryModel;
use App\Models\Languages;
use App\Models\Emirate;
use App\Models\Area;
use App\Models\Hospital;
use App\Models\SettingsModel;
use App\Models\CountryOfOrigin;
use Illuminate\Support\Facades\Auth;
use Validator,DB;
use App\Models\User;
use App\Models\TempUsers;
use App\Models\Doctor;
use App\Models\Members;
use Illuminate\Support\Facades\Hash;

class DoctorsController extends Controller
{
    //
    private function validateAccesToken($access_token)
    {

        $user = User::where(['access_token' => $access_token])->get();

        if ($user->count() == 0) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => 'Session Expired Please login to continue',
                'oData' => [],
                'errors' => (object) [],
            ]);
            exit;

        } else {
            $user = $user->first();
            if ($user != null) { //$user->active == 1
                return $user->id;
            } else {
                http_response_code(401);
                echo json_encode([
                    'status' => "0",
                    'message' => 'Session Expired Please login to continue',
                    'oData' => [],
                    'errors' => (object) [],
                ]);
                exit;
                return response()->json([
                    'status' => "0",
                    'message' => 'Session Expired Please login to continue',
                    'oData' => [],
                    'errors' => (object) [],
                ], 401);
                exit;
            }
        }
    }
    public function get_filter_data(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
           // 'access_token'=>'required',

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $insurence_policy_list = InsurencePolicy::where(['status'=>1])->orderBy('title','asc')->get();
            $specialities  = Specialty::where(['active'=>1])->orderBy('name_en','asc')->get();
            $medical_conditions = MedicalCondition::where(['status'=>1])->orderBy('title','asc')->get();
            $settings = SettingsModel::first();
            $banners = [
            ["id"=> "1", "url"=> 'https://d27k3316b49gzy.cloudfront.net/banners/Group1525574.png'],
            ["id"=> "2", "url"=>'https://d27k3316b49gzy.cloudfront.net/banners/Group1525575.png'],
            ["id"=> "3", "url"=> 'https://d27k3316b49gzy.cloudfront.net/banners/Group1525576.png'],
            ["id"=> "4", "url"=> 'https://d27k3316b49gzy.cloudfront.net/banners/Group1525577.png'],
            ["id"=> "5", "url"=> 'https://d27k3316b49gzy.cloudfront.net/banners/Group1525578.png'],
            ["id"=> "6", "url"=> 'https://d27k3316b49gzy.cloudfront.net/banners/Group1525579.png']];
            $o_data=[
                'insurence_policy_list'=>convert_all_elements_to_string($insurence_policy_list->toArray()),
                'specialities'=>convert_all_elements_to_string($specialities->toArray()),
                'medical_conditions'=>convert_all_elements_to_string($medical_conditions->toArray()),
                "serach_radius"=>(string)$settings->doctor_search_radius,
                "banners"=>$banners,

            ];
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function get_country_of_origin_list(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
           // 'access_token'=>'required',

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;

            $country_list = CountryOfOrigin::where(['status'=>1])->orderBy('name','asc');
            if($request->language==2){
                $country_list = $country_list->whereRaw("name_ar ilike '%".$request->search_text."%'");
            }else{
                $country_list = $country_list->whereRaw("name ilike '%".$request->search_text."%'");
            }
            $country_list = $country_list->take($limit)->skip($offset)->get();
            if($country_list->count() > 0){
                $status = "1";
                $o_data['list'] = convert_all_elements_to_string($country_list->toArray());
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function get_country_list(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
           // 'access_token'=>'required',

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;

            $country_list = CountryModel::where('prefix', 'AE')->where(['active'=>1])->orderBy('name','asc');
            if($request->language==2){
                $country_list = $country_list->whereRaw("name_ar ilike '%".$request->search_text."%'");
            }else{
                $country_list = $country_list->whereRaw("name ilike '%".$request->search_text."%'");
            }
            $country_list = $country_list->take($limit)->skip($offset)->get();
            if($country_list->count() > 0){
                $status = "1";
                $o_data['list'] = convert_all_elements_to_string($country_list->toArray());
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_speciality_list(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
           // 'access_token'=>'required',

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;

            $country_list = Specialty::where(['active'=>1])->orderBy('name_en','asc');
            if($request->language==2){
                $country_list = $country_list->whereRaw("name_ar ilike '%".$request->search_text."%'");
            }else{
                $country_list = $country_list->whereRaw("name_en ilike '%".$request->search_text."%'");
            }
            $country_list = $country_list->take($limit)->skip($offset)->get();
            if($country_list->count() > 0){
                $status = "1";
                $o_data['list'] = convert_all_elements_to_string($country_list->toArray());
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_medical_condition_list(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
           // 'access_token'=>'required',

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;

            $country_list = SpecialIntrests::where(['status'=>1])->orderBy('title','asc');
            if($request->language==2){
                $country_list = $country_list->whereRaw("title_ar ilike '%".$request->search_text."%'");
            }else{
                $country_list = $country_list->whereRaw("title ilike '%".$request->search_text."%'");
            }
            $country_list = $country_list->take($limit)->skip($offset)->get();
            if($country_list->count() > 0){
                $status = "1";
                $o_data['list'] = convert_all_elements_to_string($country_list->toArray());
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_language_list(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
           // 'access_token'=>'required',

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;

            $country_list = Languages::where(['status'=>1])->orderBy('title','asc');
            if($request->language==2){
                $country_list = $country_list->whereRaw("title_ar ilike '%".$request->search_text."%'");
            }else{
                $country_list = $country_list->whereRaw("title ilike '%".$request->search_text."%'");
            }
            $country_list = $country_list->take($limit)->skip($offset)->get();
            if($country_list->count() > 0){
                $status = "1";
                $o_data['list'] = convert_all_elements_to_string($country_list->toArray());
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_emirates_list(REQUEST $request){
        $status = "1";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
            //'country_id'=>'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $list = Emirate::where(['active'=>1]);
            if($request->country_id){
                $list=$list->where(['country_id'=>$request->country_id]);
            }
            if($request->language==2){
                $list = $list->whereRaw("name_ar ilike '%".$request->search_text."%'");
            }else{
                $list = $list->whereRaw("name_en ilike '%".$request->search_text."%'");
            }
            $list=$list->orderBy('name_en','asc')->take($limit)->skip($offset)->get();
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function get_area_list(REQUEST $request){
        $status = "1";
        $message = "";
        $o_data =[];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'emirate_id'=>'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $emirate_ids = explode(",",$request->emirate_id);
            $list = Area::where(['active'=>1])->whereIn('emirate_id',$emirate_ids);
            if($request->language==2){
                $list = $list->whereRaw("name_ar ilike '%".$request->search_text."%'");
            }else{
                $list = $list->whereRaw("name_en ilike '%".$request->search_text."%'");
            }
            $list=$list->orderBy('name_en','asc')->take($limit)->skip($offset)->get();
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list']  = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to show";
            }
            
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function get_hospital_list(Request $request) {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
    
        $validator = Validator::make($request->all(), [
            // 'access_token' => 'required',
        ]);
    
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
        } else {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 10;
            $offset = ($page - 1) * $limit;
            $emirate_ids = $request->emirate_id ? explode(",", $request->emirate_id) : [];
            $area_ids = $request->area_id ? explode(",", $request->area_id) : [];
            $main_insurence_id = $request->main_insurence_id ?? '';
            $sub_insurance_id = $request->sub_insurance_id ?? '';
            $speciality_id = $request->speciality_id ?? null;
            $gender = $request->gender ?? '';
            $doctor_language = $request->doctor_language ? explode(",", $request->doctor_language) : [];
            $medical_condition = $request->medical_condition ?? '';
            $country_id = $request->country_id ?? '';
            $direct_call_enabled = $request->direct_call_enabled ?? '';
            $instend_need = $request->instend_need ?? '';
            $need_date = $request->need_date ? date('Y-m-d', strtotime($request->need_date)) : null;
            $selected_ids =  $request->selected_ids ? explode(",", $request->selected_ids) : [];
    
            $query = Hospital::query()->has('doctors')->orderBy('name_en', 'asc');
    
            if ($request->search_text) {
                $search_text = $request->search_text;
                if ($request->language == 2) {
                    $query->where('name_ar', 'ILIKE', "%$search_text%");
                } else {
                    $query->where('name_en', 'ILIKE', "%$search_text%");
                }
            }
    
            if (!empty($emirate_ids)) {
                $query->whereIn('emirate_id', array_filter($emirate_ids));
            }
    
            if (!empty($area_ids)) {
                $query->whereIn('area_id', array_filter($area_ids));
            }
    
            if ($main_insurence_id) {
                $query->whereHas('insurences', function ($q) use ($main_insurence_id) {
                    $q->where('insurance_id', $main_insurence_id);
                });
            }
    
            if ($sub_insurance_id) {
                $query->whereHas('insurences', function ($q) use ($sub_insurance_id) {
                    $q->where('sub_insurance_id', $sub_insurance_id);
                });
            }
    
            if ($speciality_id || $gender || $doctor_language || $medical_condition || $country_id || $direct_call_enabled || ($instend_need && $need_date)) {
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

            if($selected_ids){
                $query->whereIn('id',$selected_ids);
            }
    
            $country_list = $query->select(['id', 'name_en'])->limit($limit)->offset($offset)->get();
    
            if ($country_list->count() > 0) {
                $status = "1";
                $o_data['list'] = convert_all_elements_to_string($country_list->toArray());
            }
        }
    
        return response()->json(['status' => $status, 'message' => $message, 'oData' => (object)$o_data, 'errors' => (object)$errors]);
    }
    public function get_hospital_list_old(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
           // 'access_token'=>'required',

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $page = $request->page??1;
            $limit = $request->limit??10;
            $offset = ($page - 1) * $limit;
            $emirate_ids = $request->emirate_id;
            $area_ids = $request->area_id;
            $main_insurence_id = $request->main_insurence_id??'';
            $sub_insurance_id=$request->sub_insurance_id??'';
            $speciality_id = $request->speciality_id;
            $gender=$request->gender??'';
            $doctor_language=$request->doctor_language??'';
            $medical_condition = $request->medical_condition??'';
            $country_id = $request->country_id??'';
            $direct_call_enabled = $request->direct_call_enabled??'';
            $instend_need = $request->instend_need??'';
            $need_date = $request->need_date;
            if($need_date){
                $need_date = date('Y-m-d',strtotime($need_date));
            }

            if($emirate_ids){
                $emirate_ids = explode(",",$emirate_ids);
            }
            if($area_ids){
                $area_ids = explode(",",$area_ids);
            }

            $country_list = Hospital::orderBy('name_en','asc');
            if($request->search_text){
                if($request->language==2){
                    $country_list = $country_list->whereRaw("name_ar ilike '%".$request->search_text."%'");
                }else{
                    $country_list = $country_list->whereRaw("name_en ilike '%".$request->search_text."%'");
                }
            }
            if($emirate_ids){
                $emirate_ids = array_filter($emirate_ids);
                $country_list = $country_list->whereIn('emirate_id',$emirate_ids);
            }
            if($area_ids){
                $area_ids = array_filter($area_ids);
                $country_list = $country_list->whereIn('area_id',$area_ids);
            }
            if($main_insurence_id){
                $country_list=$country_list->whereHas('insurences',function($q) use($main_insurence_id){
                    $q->where(['insurance_id'=>$main_insurence_id]);
                });
            }
            if($sub_insurance_id){
                $country_list=$country_list->whereHas('insurences',function($q) use($sub_insurance_id){
                    $q->where(['sub_insurance_id'=>$sub_insurance_id]);
                });
            }
            $country_list = $country_list->whereIn('hospitals.id', function ($q) use (
                $speciality_id,
                $gender,
                $doctor_language,
                $medical_condition,
                $country_id,
                $direct_call_enabled,
                $instend_need,
                $need_date
            ) {
                $q->select('hospital_id')
                    ->from('doctors')
                    ->join('users', 'doctors.user_id', '=', 'users.id')
                    ->leftJoin('doctor_specialities', 'doctors.id', '=', 'doctor_specialities.doctor_id')
                    ->leftJoin('doctor_language_spokens', 'doctors.id', '=', 'doctor_language_spokens.doctor_id')
                    ->leftJoin('doctor_intrests', 'doctors.id', '=', 'doctor_intrests.doctor_id')
                    ->leftJoin('doctor_instant_appointments', 'doctors.id', '=', 'doctor_instant_appointments.doctor_id')
                    ->when($speciality_id, function ($query) use ($speciality_id) {
                        $query->where('doctor_specialities.speciality_id', $speciality_id);
                    })
                    ->when($gender, function ($query) use ($gender) {
                        $query->where('users.gender', $gender);
                    })
                    ->when($doctor_language, function ($query) use ($doctor_language) {
                        $languages = explode(",", $doctor_language);
                        $query->whereIn('doctor_language_spokens.language_spoken_id', $languages);
                    })
                    ->when($medical_condition, function ($query) use ($medical_condition) {
                        $query->where('doctor_intrests.special_intrest_id', $medical_condition);
                    })
                    ->when($country_id, function ($query) use ($country_id) {
                        $query->where('doctors.country_id', $country_id);
                    })
                    ->when($direct_call_enabled, function ($query) {
                        $query->whereNotNull('doctors.appointment_phone');
                    })
                    ->when($instend_need, function ($query) use ($need_date) {
                        $settings = SettingsModel::first();
                        $query->whereDate('doctor_instant_appointments.instant_appointment_date', $need_date)
                            ->addSelect(['*', DB::raw("'" . $settings->instant_appoitment_number . "' as instant_appoitment_number")]);
                    });
            });
            $country_list = $country_list->select(['id','name_en'])->orderBy('name_en','asc')->take($limit)->skip($offset)->get();
            if($country_list->count() > 0){
                $status = "1";
                $o_data['list'] = convert_all_elements_to_string($country_list->toArray());
            }

        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function get_doctors(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
           // 'access_token'=>'required',
           'current_lattiude'=>'required',
           'current_longitude'=>'required'

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
        // $user_id = $this->validateAccesToken($request->access_token);
            $page = $request->page??1;
            $limit=$request->limit??10;
            $offset = ($page -  1) * $limit;
            $doctor_name = $request->doctor_name??'';
            $speciality_id = $request->speciality_id;
            $gender=$request->gender??'';
            $doctor_language=$request->doctor_language??'';
            $medical_condition = $request->medical_condition??'';
            $country_id = $request->country_id??'';
            $hospital_id =$request->hospital_id??'';
            $emirate_id=$request->emirate_id??'';
            $area_id = $request->area_id??'';
            $main_insurence_id = $request->main_insurence_id??'';
            $sub_insurance_id=$request->sub_insurance_id??'';
            $current_lattiude = $request->current_lattiude??'';
            $current_longitude = $request->current_longitude??'';
            $filter_distance = $request->filter_distance;
            $direct_call_enabled = $request->direct_call_enabled??'';
            $instend_need = $request->instend_need??'';
            $need_date = $request->need_date;
            if($need_date){
                $need_date = date('Y-m-d',strtotime($need_date));
            }else{
                //$need_date = date('Y-m-d');
            }

            $list = Doctor::with(['country','user','hospital',
            'hospital.location'=>function($q) use($current_lattiude,$current_longitude){
                $q->select('*');
                $distance =
                    "6371 * acos (
                    cos ( radians( CAST (latitude AS double precision) ) )
                    * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
                    * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                    + sin ( radians( CAST (latitude AS double precision) ) )
                    * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
                )";
                $q->selectRaw("$distance as distance");
            },
            'doctorIntrests'=>function($q){
               $q->whereHas('specialInterest');
            }
            ,'doctorIntrests.specialInterest'
            
            ,'doctorSpecialities.speciality','doctorQualifications.qualification'])
            ->leftJoin('hospital_locations', 'doctors.hospital_id', '=', 'hospital_locations.hospital_id')
            ->whereHas('user', function ($q) {
                $q->where('active', 1)->where('deleted', 0);
            })
            ->whereHas('hospital.user', function ($q) {
                $q->where('active', 1)->where('deleted', 0);
            });
            

            if($filter_distance){
                $list=$list->whereHas('hospital.location',function($q) use($filter_distance,$current_lattiude,$current_longitude){
                    $distance =
                        "6371 * acos (
                        cos ( radians( CAST (latitude AS double precision) ) )
                        * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
                        * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                        + sin ( radians( CAST (latitude AS double precision) ) )
                        * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
                    )";
                    $q->whereRaw("($distance) <= ".$filter_distance);
                });
            }
            
            if($direct_call_enabled){
                $list=$list->where('appointment_phone','!=','');
            }
            if($doctor_name != ''){
                $list=$list->whereHas('user',function($query) use($doctor_name){
                    $query->whereRaw("name ilike '%".strtolower($doctor_name)."%'");
                });
            }
            if($speciality_id !=''){
                $list=$list->whereHas('doctorSpecialities',function($query) use($speciality_id){
                    $query->where(['speciality_id'=>$speciality_id]);
                });
            }
            if($doctor_language !=''){
                $list=$list->whereHas('doctorLanguageSpoken',function($query) use($doctor_language){
                    $doctor_language = explode(",",$doctor_language);
                    $query->whereIn('language_spoken_id',$doctor_language);
                });
            }
            if($medical_condition !=''){
                $list=$list->whereHas('doctorIntrests',function($query) use($medical_condition){
                    $query->where(['special_intrest_id'=>$medical_condition]);
                });
            }
            if($gender){
                $list=$list->whereHas('user',function($q)use($gender){
                    $q->where(['gender'=>$gender]);
                });
            }
            if($country_id){
                $list=$list->where(['country_id'=>$country_id]);
            }
            if($hospital_id){
                $list=$list->where(['doctors.hospital_id'=>$hospital_id]);
            }
            if($emirate_id){
                $list=$list->whereHas('hospital',function($q) use($emirate_id){
                    $emirate_ids = explode(",",$emirate_id);
                    $q->whereIn('emirate_id',$emirate_ids);
                });
            }
            if($area_id){
                $list=$list->whereHas('hospital',function($q) use($area_id){
                    $areas = explode(",",$area_id);
                    $q->whereIn('area_id',$areas);
                    // if(is_numeric($area_id)){
                    //     $q->where(['area_id'=>$area_id]);
                    // }else{
                    //     $q->whereIn();
                    // }
                    
                });
            }
            if($main_insurence_id){
                $list=$list->whereHas('hospital.insurences',function($q) use($main_insurence_id){
                    $q->where(['insurance_id'=>$main_insurence_id]);
                });
            }
            if($sub_insurance_id){
                $list=$list->whereHas('hospital.insurences',function($q) use($sub_insurance_id){
                    $q->where(['sub_insurance_id'=>$sub_insurance_id]);
                });
            }
            if($need_date){
                $list=$list->whereNotIn('doctors.id',DoctorHolidays::whereDate('holiday_date','=',$need_date)->select('doctor_id'));
            }
             $settings = SettingsModel::first();
            if($instend_need){
               if($need_date ==''){
                $need_date = date('Y-m-d');
               }
                $list=$list->whereHas('doctorInstantAppointment',function($q) use($need_date){
                    $q->whereDate('instant_appointment_date','=',$need_date);
                })->addSelect(['*',DB::raw("'".$settings->instant_appoitment_number."' as instant_appoitment_number")]);
            }else{
                if($need_date){
                    $dayName = strtolower(date('l', strtotime($need_date)));
                    $list = $list->whereIn('doctors.id',DoctorAvailability::where($dayName.'_availability', 1)->select('doctor_id'))->addSelect(['*',DB::raw("'' as instant_appoitment_number")]);
                }
            }
            
            
            if($instend_need){
            $list = $list->select('doctors.*')
            ->selectRaw("6371 * acos (
                cos ( radians( CAST (hospital_locations.latitude AS double precision) ) )
                * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
                * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (hospital_locations.longitude AS double precision) ) )
                + sin ( radians( CAST (hospital_locations.latitude AS double precision) ) )
                * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
            ) as distance")->selectRaw("'".$settings->instant_appoitment_number."' as instant_appoitment_number");
            }else{
                $list = $list->select('doctors.*')
                    ->selectRaw("6371 * acos (
                        cos ( radians( CAST (hospital_locations.latitude AS double precision) ) )
                        * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
                        * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (hospital_locations.longitude AS double precision) ) )
                        + sin ( radians( CAST (hospital_locations.latitude AS double precision) ) )
                        * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
                    ) as distance")->selectRaw("'' as instant_appoitment_number");
            }
            $list = $list->orderBy('distance','asc');
            $total_count = $list->get()->count();
            $list = $list->take($limit)->skip($offset)->get();
            $over_all_doctor_count = Doctor::whereHas('user', function ($q) {
                $q->where('active', 1)->where('deleted', 0);
            })
            ->whereHas('hospital.user', function ($q) {
                $q->where('active', 1)->where('deleted', 0);
            })->get()->count();
            
            if($list->count() > 0){
                $status = "1";
                $message = "data fetcehed successfully";
                $o_data['list'] = convert_all_elements_to_string($list->toArray());
            }else{
                $o_data['list'] =[];
                $message = "no data to list";
            }
            $o_data['total_count'] = (string) $total_count;
            $o_data['over_all_doctor_count']= (string)$over_all_doctor_count;
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_doctors_v2(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
    
        $validator = Validator::make($request->all(), [
            'current_lattiude' => 'required',
            'current_longitude' => 'required'
        ]);
    
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
        } else {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 10;
            $offset = ($page - 1) * $limit;
            $current_lattiude = $request->current_lattiude;
            $current_longitude = $request->current_longitude;
            $filter_distance = $request->filter_distance;
            $need_date = $request->need_date ? date('Y-m-d', strtotime($request->need_date)) : date('Y-m-d');
    
            // Initial query setup with necessary joins and filters
            $list = Doctor::with([
                'user',
                'hospital',
                'hospital.location' => function($q) use($current_lattiude, $current_longitude) {
                    $q->select('*');
                    $distance = "6371 * acos(
                        cos(radians(CAST(latitude AS double precision))) *
                        cos(radians(CAST({$current_lattiude} AS double precision))) *
                        cos(radians(CAST({$current_longitude} AS double precision)) - radians(CAST(longitude AS double precision))) +
                        sin(radians(CAST(latitude AS double precision))) *
                        sin(radians(CAST({$current_lattiude} AS double precision)))
                    )";
                    $q->selectRaw("$distance as distance");
                },
                'doctorIntrests.specialInterest',
                'doctorSpecialities.speciality',
                'doctorQualifications.qualification'
            ])
            ->leftJoin('hospital_locations', 'doctors.hospital_id', '=', 'hospital_locations.hospital_id');
    
            // Apply filters
            $filters = [
                'doctor_name' => $request->doctor_name ?? '',
                'speciality_id' => $request->speciality_id,
                'gender' => $request->gender ?? '',
                'doctor_language' => $request->doctor_language ?? '',
                'medical_condition' => $request->medical_condition ?? '',
                'country_id' => $request->country_id ?? '',
                'hospital_id' => $request->hospital_id ?? '',
                'emirate_id' => $request->emirate_id ?? '',
                'area_id' => $request->area_id ?? '',
                'main_insurence_id' => $request->main_insurence_id ?? '',
                'sub_insurance_id' => $request->sub_insurance_id ?? '',
                'direct_call_enabled' => $request->direct_call_enabled ?? '',
                'instend_need' => $request->instend_need ?? '',
            ];
    
            // Filter by distance if provided
            if ($filter_distance) {
                $list = $list->whereHas('hospital.location', function($q) use($filter_distance, $current_lattiude, $current_longitude) {
                    $distance = "6371 * acos(
                        cos(radians(CAST(latitude AS double precision))) *
                        cos(radians(CAST({$current_lattiude} AS double precision))) *
                        cos(radians(CAST({$current_longitude} AS double precision)) - radians(CAST(longitude AS double precision))) +
                        sin(radians(CAST(latitude AS double precision))) *
                        sin(radians(CAST({$current_lattiude} AS double precision)))
                    )";
                    $q->whereRaw("($distance) <= ".$filter_distance);
                });
            }
    
            // Apply other filters
            foreach ($filters as $key => $value) {
                if ($value) {
                    // Special handling for specific filters
                    switch ($key) {
                        case 'doctor_name':
                            $list = $list->whereHas('user', function($query) use($value) {
                                $query->whereRaw("name ILIKE ?", ['%' . strtolower($value) . '%']);
                            });
                            break;
                        case 'speciality_id':
                            $list = $list->whereHas('doctorSpecialities', function($query) use($value) {
                                $query->where('speciality_id', $value);
                            });
                            break;
                        case 'doctor_language':
                            $languages = explode(",", $value);
                            $list = $list->whereHas('doctorLanguageSpoken', function($query) use($languages) {
                                $query->whereIn('language_spoken_id', $languages);
                            });
                            break;
                        case 'medical_condition':
                            $list = $list->whereHas('doctorIntrests', function($query) use($value) {
                                $query->where('special_intrest_id', $value);
                            });
                            break;
                        case 'gender':
                            $list = $list->whereHas('user', function($query) use($value) {
                                $query->where('gender', $value);
                            });
                            break;
                        case 'country_id':
                            $list = $list->where('country_id', $value);
                            break;
                        case 'hospital_id':
                            $list = $list->where('doctors.hospital_id', $value);
                            break;
                        case 'emirate_id':
                            $emirate_ids = explode(",", $value);
                            $list = $list->whereHas('hospital', function($query) use($emirate_ids) {
                                $query->whereIn('emirate_id', $emirate_ids);
                            });
                            break;
                        case 'area_id':
                            $areas = explode(",", $value);
                            $list = $list->whereHas('hospital', function($query) use($areas) {
                                $query->whereIn('area_id', $areas);
                            });
                            break;
                        case 'main_insurence_id':
                            $list = $list->whereHas('hospital.insurences', function($query) use($value) {
                                $query->where('insurance_id', $value);
                            });
                            break;
                        case 'sub_insurance_id':
                            $list = $list->whereHas('hospital.insurences', function($query) use($value) {
                                $query->where('sub_insurance_id', $value);
                            });
                            break;
                        case 'direct_call_enabled':
                            $list = $list->where('appointment_phone', '!=', '');
                            break;
                        case 'instend_need':
                            $settings = SettingsModel::first();
                            $list = $list->whereHas('doctorInstantAppointment', function($query) use($need_date) {
                                $query->whereDate('instant_appointment_date', '=', $need_date);
                            })->addSelect(['*', DB::raw("'" . $settings->instant_appoitment_number . "' as instant_appoitment_number")]);
                            break;
                    }
                }
            }
    
            // Handle doctor availability and holidays
            $dayName = strtolower(date('l', strtotime($need_date)));
            $list = $list->whereIn('doctors.id', DoctorAvailability::where($dayName . '_availability', 1)->select('doctor_id'))
                         ->whereNotIn('doctors.id', DoctorHolidays::whereDate('holiday_date', '=', $need_date)->select('doctor_id'))
                         ->select('doctors.*')
                         ->selectRaw("6371 * acos(
                            cos(radians(CAST(hospital_locations.latitude AS double precision))) *
                            cos(radians(CAST({$current_lattiude} AS double precision))) *
                            cos(radians(CAST({$current_longitude} AS double precision)) - radians(CAST(hospital_locations.longitude AS double precision))) +
                            sin(radians(CAST(hospital_locations.latitude AS double precision))) *
                            sin(radians(CAST({$current_lattiude} AS double precision)))
                         ) as distance")
                         ->orderBy('distance', 'asc');
    
            // Get total count and paginated results
            $total_count = $list->count();
            $list = $list->take($limit)->skip($offset)->get();
            $over_all_doctor_count = Doctor::count();
    
            if ($list->count() > 0) {
                $status = "1";
                $message = "Data fetched successfully";
                $o_data['list'] = convert_all_elements_to_string($list->toArray());
            } else {
                $o_data['list'] = [];
                $message = "No data to list";
            }
    
            $o_data['total_count'] = (string)$total_count;
            $o_data['over_all_doctor_count'] = (string)$over_all_doctor_count;
        }
    
        return response()->json(['status' => $status, 'message' => $message, 'oData' => (object)$o_data, 'errors' => (object)$errors]);
    }
      public function get_doctor_lists(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
           // 'access_token'=>'required',

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{ 
        // $user_id = $this->validateAccesToken($request->access_token);
            $page = $request->page??1;
            $limit=$request->limit??10;
            $offset = ($page -  1) * $limit;
            $list = Doctor::with(['country','user','hospital.location','hospital.images','doctorIntrests.specialInterest','doctorLanguageSpoken.languageSpoken','doctorSpecialities.speciality','doctorQualifications.qualification'])
            ->leftJoin('hospitals', 'hospitals.id', '=', 'doctors.hospital_id')
           ->leftJoin('country', 'country.id', '=', 'doctors.country_id')
            ->select('doctors.*','country.name as country_name')
           ->orderBy('doctors.id','desc')->take($limit)->skip($offset)->get();
            
            if($list->count() > 0){
                $status = "1";
                $message = "data fetcehed successfully";
                $o_data['list'] = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to list";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function get_doctor_profiles(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
         //   'access_token'=>'required',
            'doctor_user_id'=>'required',
            'booking_date' => 'required'

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
           // $user_id = $this->validateAccesToken($request->access_token);
            $page = $request->page??1;
            $limit=$request->limit??10;
            $offset = ($page -  1) * $limit;
            $current_lattiude = $request->current_lattiude??'25.2048';
            $current_longitude = $request->current_longitude??'55.2708';
            $booking_date = ($request->booking_date)?date('Y-m-d',strtotime($request->booking_date)):date('Y-m-d');
            $list = Doctor::with(['country','user','hospital.location'=>function($q) use($current_lattiude,$current_longitude){
                $q->select('*');
                $distance =
                    "6371 * acos (
                    cos ( radians( CAST (latitude AS double precision) ) )
                    * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
                    * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                    + sin ( radians( CAST (latitude AS double precision) ) )
                    * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
                )";
                //$roundedDistance = "ROUND(CAST($distance AS numeric), 2)";
                $q->selectRaw("$distance as distance");
            },
            'hospital.user'=>function($q){
                return $q->select(['id','user_image']);
            },
            'hospital.images','doctorQualifications','doctorLanguageSpoken','doctorSpecialities','doctorIntrests'=>function($q){
               $q->whereHas('specialInterest');
            },'doctorIntrests.specialInterest','doctorLanguageSpoken.languageSpoken','doctorSpecialities.speciality','doctorQualifications.qualification'])
            ->where(['id'=>$request->doctor_user_id])->orderBy('id','desc')->take($limit)->skip($offset)->get();
           
            $unavailable_timeSlot = [];
            $doctorAvailable = [];
            $timeSlot =  [];
            $doctor_time_slot = array();
            if($list){
                
                $today = date('Y-m-d');
               
                if($booking_date >= $today){
                        $checkHoliday = DoctorHolidays::where('doctor_id',$request->doctor_user_id)
                        ->where('holiday_date',$booking_date )
                        ->get();
                    

                        if($checkHoliday->count() == 0){
                            
                                $dayName = strtolower(date('l', strtotime($booking_date)));
                                $doctorAvailable = DoctorAvailability::where('doctor_id', $request->doctor_user_id)
                                ->where($dayName.'_availability', 1)
                                ->select($dayName.'_availability',$dayName.'_time_slot')
                                ->orderBy('id','desc')->first();
                                $doctorUnAvailable = DoctorTemporaryUnavailable::where('doctor_id', $request->doctor_user_id)
                                ->where('unavailable_date', $booking_date)
                                ->select('unavailable_timeslot')
                                ->orderBy('id','desc')->first();
                
                                if(!empty($doctorUnAvailable)){
                                    $unavailable_timeSlot = json_decode($doctorUnAvailable->unavailable_timeslot);
                                } 
                    
                                if(!empty($doctorAvailable)){
                                    $available_timeSlot = json_decode($doctorAvailable->{$dayName.'_time_slot'});
                                    $timeSlot =array_diff($available_timeSlot??[],$unavailable_timeSlot??[]);   
                                    if($timeSlot){

                                        $list[0]->setAttribute('doctor_availability_status','1');
                                    }else{
                                        $list[0]->setAttribute('doctor_availability_status','0');
                                    }
                            
                                }else{
                                    $list[0]->setAttribute('doctor_availability_status','0');
                               }
                        }else{
                            $list[0]->setAttribute('doctor_availability_status','0');
                        }
                }else{

                    $list[0]->setAttribute('doctor_availability_status','0');
                } 

                if($request->from_instant){
                    $settings = SettingsModel::first();
                    $list[0]->setAttribute('instant_appoitment_number',$settings->instant_appoitment_number);
                }else{
                    $list[0]->setAttribute('instant_appoitment_number','');
                }
            
            }
            if($list->count() > 0){
                $status = "1";
                $message = "data fetcehed successfully";
                $o_data['list'] = convert_all_elements_to_string($list->toArray());
            }else{
                $message = "no data to list";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
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
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
        // $user_id = $this->validateAccesToken($request->access_token);
        $page = $request->page??1;
        $limit=$request->limit??10;
        $offset = ($page -  1) * $limit;
        $booking_date = date('Y-m-d',strtotime($request->booking_date));
        
        $list =  [];
        $unavailable_timeSlot = [];
        $doctorAvailable = [];
        $timeSlot =  [];
        $doctor_time_slot = array();
        $checkHoliday = DoctorHolidays::where('doctor_id',$request->doctor_user_id)
        ->where('holiday_date',$booking_date )
        ->get();
        $unavailable_timeSlot = [];
        if($checkHoliday->count() == 0){
            
                $dayName = strtolower(date('l', strtotime($booking_date)));
                $doctorAvailable = DoctorAvailability::where('doctor_id', $request->doctor_user_id)
                ->where($dayName.'_availability', 1)
                ->select($dayName.'_availability',$dayName.'_time_slot')
                ->orderBy('id','desc')->first();
                $doctorUnAvailable = DoctorTemporaryUnavailable::where('doctor_id', $request->doctor_user_id)
                ->whereDate('unavailable_date', $booking_date)
                ->select('unavailable_timeslot')
                ->orderBy('id','desc')->first();

                if(!empty($doctorUnAvailable)){
                    $unavailable_timeSlot = json_decode($doctorUnAvailable->unavailable_timeslot);
                    if(!$unavailable_timeSlot){
                        $unavailable_timeSlot=[];
                    }
                } 
    
                if(!empty($doctorAvailable)){
                $available_timeSlot = json_decode($doctorAvailable->{$dayName.'_time_slot'});
                //$timeSlot =array_diff($available_timeSlot,$unavailable_timeSlot);   
                $timeSlot=$available_timeSlot;
            }  
                            
                if($timeSlot){
                    $takenAppointment= [];  
                    $takenAppointment =DoctorPatientAppointment::where('doctor_id',$request->doctor_user_id)->whereNotIn('booking_status',[BOOKING_STATUS_CANCELLED])
                    ->where('booking_date',$booking_date )
                    ->pluck('booking_time_slot')->toArray();
                
                    date_default_timezone_set('Asia/Dubai');
                    $dubai_time_now = date('H:i');
                    $today  = date('Y-m-d');
                    foreach ($timeSlot as $key => $value) {
                        if($today == $booking_date){
                            
                            if(strtotime($timeSlot[$key]) < strtotime($dubai_time_now)){
                                $doctor_time_slot[] =[
                                    "slot_text" => $timeSlot[$key],
                                    "is_available" => "0"
                                ];
                            }else{
                                if( in_array($timeSlot[$key], $takenAppointment)){
                            
                                    $doctor_time_slot[] =[
                                                "slot_text" => $timeSlot[$key],
                                                "is_available" => "0"
                                            ];
        
                                }else{
                                    if( in_array($timeSlot[$key], $unavailable_timeSlot)){
                                        $doctor_time_slot[] =[
                                            "slot_text" => $timeSlot[$key],
                                            "is_available" => "0"
                                        ];
                                    }else{
                                    $doctor_time_slot[] =[
                                                "slot_text" => $timeSlot[$key],
                                                "is_available" => "1"
                                            ];
                                        }
                                }
                            }
                        }else{
                            if( in_array($timeSlot[$key], $takenAppointment)){
                            
                                $doctor_time_slot[] =[
                                            "slot_text" => $timeSlot[$key],
                                            "is_available" => "0"
                                        ];
    
                            }else{
                                if( in_array($timeSlot[$key], $unavailable_timeSlot)){
                                    $doctor_time_slot[] =[
                                        "slot_text" => $timeSlot[$key],
                                        "is_available" => "0"
                                    ];
                                }else{
                                    $doctor_time_slot[] =[
                                                "slot_text" => $timeSlot[$key],
                                                "is_available" => "1"
                                            ];
                                    }
                            }
                        }

                        
                    }
                    }else{
                        $messageResponse = "Add record on Doctor Availibility";
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

    public function book_appointment(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            'doctor_id'=> 'required',
            'access_token'=>'required',
            'booking_time_slot' => 'required',
            'booking_date' => 'required',
            'member_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
            $check_slot = DoctorPatientAppointment::where(['booking_time_slot'=>$request->booking_time_slot,'booking_date'=>date('Y-m-d',strtotime($request->booking_date)),'doctor_id'=>$request->doctor_id])->whereNotIn('booking_status',[BOOKING_STATUS_CANCELLED])->get();
            if($check_slot->count() > 0){
                $message = "Timeslot is not available plz try another slot";
            }else{
                $doctorData = Doctor::where(['id'=>$request->doctor_id])->get()->first();
                if($doctorData){
                    $FourDigitRandomNumber = time();
                    $doctor = new DoctorPatientAppointment();
                    $doctor->doctor_id   =  $request->doctor_id;
                    $doctor->booking_id    = config('global.booking_prefix').$FourDigitRandomNumber;
                    $doctor->member_id    = $request->member_id;
                    $doctor->user_id  =  $user_id;
                    $doctor->booking_date = date('Y-m-d',strtotime($request->booking_date));
                    $doctor->booking_time_slot    =  $request->booking_time_slot;
                    $doctor->booking_status   = BOOKING_STATUS_PENDING;
                    $doctor->hospital_id = (int)$doctorData->hospital_id;
                    if($doctorData->department_id){
                        $doctor->department_id = (int)$doctorData->department_id;
                    }
                    
                    $doctor->created_at = gmdate('Y-m-d H:i:s');
                    $doctor->updated_at = gmdate('Y-m-d H:i:s');
                    $doctor->save();

                    exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");

                    $status = "1";
                    $message = "You have successfullly saved Book Appointment";
                    $o_data = convert_all_elements_to_string($doctor->toArray());
                }else{
                    $message = "selected doctor not found";
                }
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_booking_lists(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'access_token'=>'required',
            'filter' => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
         $user_id = $this->validateAccesToken($request->access_token);
            $page = $request->page??1;
            $limit=$request->limit??10;
            $offset = ($page -  1) * $limit;
            $member_id = $request->member_id??'';
            $current_lattiude = $request->current_lattiude??'25.2048';
            $current_longitude = $request->current_longitude??'55.2708';
            $list = DoctorPatientAppointment::with(['member','user','hospital',
            'hospital.location'=>function($q) use($current_lattiude,$current_longitude){
                $q->select('*');
                $distance =
                    "6371 * acos (
                    cos ( radians( CAST (latitude AS double precision) ) )
                    * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
                    * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                    + sin ( radians( CAST (latitude AS double precision) ) )
                    * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
                )";
                $q->selectRaw("$distance as distance");
            },'doctor','doctor.doctorQualifications.qualification','doctor.user','doctor.doctorSpecialities.speciality'])
            ->withCount(['feedback as feedback_noted'])
            ->where('user_id',$user_id) 
            ->orderBy('id','desc')->take($limit)->skip($offset);
            if ($request->filter !== 'All') {
                $list->where('booking_status', $request->filter);
            }
            if($member_id != ''){
                $list->where('member_id','=',$member_id);
            }
            $list = $list->get();
            if($list->count() > 0){
                
                $status = "1";
                $message = "data fetcehed successfully";
                $o_data['list'] = convert_all_elements_to_string($list->toArray());
                foreach($o_data['list'] as $key=>$value){
                    
                    if(!$value['member']){
                        $o_data['list'][$key]['member'] = (object)[];
                    }
                }
            }else{
                $message = "no data to list";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function booking_details_lists(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            'appointment_id'=>'required',
            'access_token'=>'required',

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
         $user_id = $this->validateAccesToken($request->access_token);
            $page = $request->page??1;
            $limit=$request->limit??10;
            $offset = ($page -  1) * $limit;
            $current_lattiude = $request->current_lattiude??'25.2048';
            $current_longitude = $request->current_longitude??'55.2708';
          
           $list = DoctorPatientAppointment::withCount(['feedback as feedback_noted'])->with([ 'user','member','doctor','doctor.country','doctor.user',
           'hospital.location'=>function($q) use($current_lattiude,$current_longitude){
            $q->select('*');
            $distance =
                "6371 * acos (
                cos ( radians( CAST (latitude AS double precision) ) )
                * cos( radians( CAST ({$current_lattiude} AS double precision) ) )
                * cos( radians( CAST ({$current_longitude} AS double precision) ) - radians( CAST (longitude AS double precision) ) )
                + sin ( radians( CAST (latitude AS double precision) ) )
                * sin( radians ( CAST ({$current_lattiude} AS double precision) ) )
            )";
            $q->selectRaw("$distance as distance");
            },'doctor.doctorIntrests.specialInterest','doctor.doctorLanguageSpoken.languageSpoken','doctor.doctorSpecialities.speciality','doctor.doctorQualifications.qualification','feedback','followups'])
           ->where('doctor_patient_appointments.id','=',$request->appointment_id)
           ->orderBy('doctor_patient_appointments.id','desc')->get();
        if($list->count() > 0){
           if($list[0]['member_id'] === '0'){
            $list[0]->setAttribute('label','self');
            //$list[0]->setAttribute('user',[]);
          }else{
            $list->load('user');
            foreach ($list as $item) {
         
             // Accessing the getUserImgUrlAttribute method if user_image exists
             $userImage = $item->user->user_image ? $item->user->getUserImgUrlAttribute() : 'admin-assets/assets/img/placeholder.jpg';
             // $userImage now holds the image URL if it exists, otherwise, it's null
             $item->user->setAttribute('user_image',$userImage);
             $item->setAttribute('label','');
             if(empty($item->feedback)){
                $item->setAttribute("feedback_noted","0");
             }else{
                $item->setAttribute("feedback_noted","1");
             }
             }
          }
     
        }

           if($list->count() > 0){
                $status = "1";
                $message = "data fetcehed successfully";
                $d=$list->first()->toArray();
                
                $o_data = convert_all_elements_to_string($d);
                if($o_data['member_id'] == 0){
                    $o_data['member'] = (object)[];
                }
                if(empty($o_data['feedback'])){
                    $o_data['feedback'] = (object)[];
                }
            }else{
                $message = "no data to list";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function cancel_appointment(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required',
            'access_token' =>'required'
         
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
            $doctor = DoctorPatientAppointment::find($request->appointment_id);        
             if($doctor){
                $doctor->booking_status   = BOOKING_STATUS_CANCELLED;
                $doctor->reason_cancel = $request->reason_cancel;
                $doctor->updated_at = gmdate('Y-m-d H:i:s');
                $doctor->save();
                exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
                $status = "1";
                $message = "You have successfullly Cancel Appointment";
                $o_data = convert_all_elements_to_string($doctor->toArray());
             }else{
                $message = "no data to list";
             }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }


    public function reschedule_appointment(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            'appointment_id'=> 'required',
            'access_token'=> 'required',
            'booking_date'=>'required',
            'booking_time_slot' => 'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
            $doctor = DoctorPatientAppointment::find($request->appointment_id);
            if($doctor){
                $check_slot = DoctorPatientAppointment::where(['booking_time_slot'=>$request->booking_time_slot,'booking_date'=>date('Y-m-d',strtotime($request->booking_date)),'doctor_id'=>$doctor->doctor_id])->whereNotIn('booking_status',[BOOKING_STATUS_CANCELLED])->get();
                if($check_slot->count() > 0){
                    $message = "Timeslot is not available plz try another slot";
                }else{
                    $RescheduleAppointment = new DoctorRescheduleAppointment();
                    
                    $RescheduleAppointment->doctor_id = $doctor->doctor_id;
                    $RescheduleAppointment->patient_appointment_id = $request->appointment_id;
                    $RescheduleAppointment->reschedule_patient_booking_date = $doctor->booking_date;
                    $RescheduleAppointment->reschedule_patient_time_slot    =  $doctor->booking_time_slot;
                    $RescheduleAppointment->reason = $request->reason_reschedule??'';
                    $RescheduleAppointment->save();

                    $doctor->previous_booking_date = $doctor->booking_date;
                    $doctor->previous_booking_time_slot    =  $doctor->booking_time_slot;    
                    $doctor->reason_reschedule  = $request->reason_reschedule??'';
                    $doctor->booking_date = date('Y-m-d',strtotime($request->booking_date));
                    $doctor->booking_time_slot    =  $request->booking_time_slot;
                    $doctor->booking_status   = BOOKING_STATUS_RESCHEDULED;
                    $doctor->updated_at = gmdate('Y-m-d H:i:s');
                    $doctor->save();

                    exec("php " . base_path() . "/artisan app:send-notitications-patient " . $doctor->id . " > /dev/null 2>&1 & ");
                    $status = "1";
                    $message = "You have successfullly saved  reschedule Book Appointment";
                    //$o_data = convert_all_elements_to_string($RescheduleAppointment->toArray());
                }
            }else{
                $message = "no data to list";
                
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function booking_count_list(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $status_list = [];
        $validator = Validator::make($request->all(), [
            
            
        'access_token'=>'required',

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
          $user_id = $this->validateAccesToken($request->access_token);
          $member_id = $request->member_id??'';
          if($member_id==''){
           $pending_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_PENDING])->get()->count();
           $completed_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_COMPLETED])->get()->count();
           $cancelled_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_CANCELLED])->get()->count();
           $confirmed_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_CONFIRMED])->get()->count();
           $rescheduled_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_RESCHEDULED])->get()->count();
          }else{
            $pending_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_PENDING,'member_id'=>$member_id])->get()->count();
            $completed_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_COMPLETED,'member_id'=>$member_id])->get()->count();
            $cancelled_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_CANCELLED,'member_id'=>$member_id])->get()->count();
            $confirmed_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_CONFIRMED,'member_id'=>$member_id])->get()->count();
            $rescheduled_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_RESCHEDULED,'member_id'=>$member_id])->get()->count();
          }
          $o_data= [
            BOOKING_STATUS_PENDING =>(string)$pending_count,
            BOOKING_STATUS_COMPLETED =>(string)$completed_count,
            BOOKING_STATUS_CANCELLED =>(string)$cancelled_count,
            BOOKING_STATUS_CONFIRMED =>(string)$confirmed_count,
            BOOKING_STATUS_RESCHEDULED =>(string)$rescheduled_count
          ];
          $status = "1";
          $message = "success";
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_my_members_booking_counts(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
            'access_token'=>'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
            $page = $request->page??1;
            $limit=$request->limit??10;
            $own_count =DoctorPatientAppointment::where('user_id',$user_id)->where('member_id','=',0)->get()->count();
            if($page==1){
                if($own_count > 0){
                    $limit = $limit - 1;
                }
            }
            $offset = ($page -  1) * $limit;
            $user = User::find($user_id);
            
            $list = Members::where(['user_id'=>$user_id])
            ->select(['id','full_name'])
            ->whereRaw("(select count(*) from doctor_patient_appointments where CAST(member_id AS INTEGER)=members.id) > 0")
            ->orderBy('id','desc')->take($limit)->skip($offset)->get();
            if($page==1){
                if($own_count > 0){
                    $me = (object)['id'=>0,'full_name'=>$user->name];
                    $list->prepend($me);
                }
            }
            if($list->count() > 0){

                foreach($list as $key=>$value){
                    $list[$key]->pending_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_PENDING,'member_id'=>$value->id])->get()->count();
                    $list[$key]->completed_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_COMPLETED,'member_id'=>$value->id])->get()->count();
                    $list[$key]->cancelled_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_CANCELLED,'member_id'=>$value->id])->get()->count();
                    $list[$key]->confirmed_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_CONFIRMED,'member_id'=>$value->id])->get()->count();
                    $list[$key]->rescheduled_count =DoctorPatientAppointment::where('user_id',$user_id)->where(['booking_status'=>BOOKING_STATUS_RESCHEDULED,'member_id'=>$value->id])->get()->count();
                   
                }
                $status = "1";
                $message = "data fetcehed successfully";
                
                $o_data['list'] = convert_all_elements_to_string($list->toArray());
                
            }else{
                $message = "no data to list";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
 

    public function hospital_doctor_feedback(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            'doctor_id'=> 'required',
            'hospital_id'=>'required',
            'appointment_id'=>'required',
            'access_token' => 'required',
            'rating' => 'required|numeric|min:0|max:5'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
           
            $feedback = new HospitalDoctorFeedback();
            $feedback->doctor_id   =  $request->doctor_id;
            $feedback->hospital_id    = $request->hospital_id;
            $feedback->appointment_id    = $request->appointment_id;
            $feedback->rating    = $request->rating;
            $feedback->user_id  =  $user_id;
            $feedback->feeback_message = $request->feeback_message;
            $feedback->created_at = gmdate('Y-m-d H:i:s');
            $feedback->updated_at = gmdate('Y-m-d H:i:s');
            $feedback->save();
            $status = "1";
            $message = "You have successfullly saved Hospital Doctor Feedback";
            //$o_data = convert_all_elements_to_string($feedback->toArray());
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    public function mydrworld_service_feedback(REQUEST $request){
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'rating' => 'required|numeric|min:0|max:5'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
           
            $feedback = new MydrworldServiceFeedback();
            $feedback->rating    = $request->rating;
            $feedback->user_id  =  $user_id;
            $feedback->feeback_message = $request->feeback_message;
            $feedback->created_at = gmdate('Y-m-d H:i:s');
            $feedback->updated_at = gmdate('Y-m-d H:i:s');
            $feedback->save();
            $status = "1";
            $message = "You have successfullly saved Mednero Service Feedback";
            $o_data = convert_all_elements_to_string($feedback->toArray());
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
    
    public function get_hospital_profile(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
           // 'access_token'=>'required',
           'hospital_id'=>'required'

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            
            $hospital_id = $request->hospital_id;
            
            $data = Hospital::with(['user','departments','images','emirate','area','country'])->where(['id'=>$hospital_id])->get();
            if($data->count() > 0){
                $status = "1";
                $message = "data fetched successfully";
                $data=$data->first();
                if(empty($data->area)){
                    $data->area = (object)[];
                }
                $o_data = convert_all_elements_to_string($data->toArray());
            }else{
                $message = "invalid id passed";
            }

        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
}
