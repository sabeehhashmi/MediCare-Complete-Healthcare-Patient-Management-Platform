<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CountryModel;
use App\Models\Specialty;
use App\Models\InsurencePolicy;
use App\Models\SubInsurencePolicy;
use App\Models\MedicalCondition;
use App\Models\Languages;
use App\Models\CountryOfOrigin;
use App\Models\Emirate;
use App\Models\Area;
use App\Models\ContactUsSetting;
use App\Models\Hospital;
use App\Models\SpecialIntrests;
use App\Models\Doctor;
use App\Models\DoctorHolidays;
use App\Models\DoctorAvailability;
use App\Models\DoctorPatientAppointment;
use App\Models\DoctorTemporaryUnavailable;
use App\Models\HpManagement;
use App\Models\HpPartnerLogo;
use App\Models\SettingsModel;
use App\Models\User;
use App\Models\Members;
use App\Models\DoctorAppointmentsStatus;
use Carbon\Carbon;
use Validator,DB;
use DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\DeleteAccountEmail;
use App\Models\Article;
use App\Models\FaqForDoctorModel;
use App\Models\FaqForHospitaModel;
use App\Models\FaqModel;
use App\Models\HpSlide;
use Illuminate\Support\Facades\Hash;

class FindDoctorsController extends Controller
{
    //

    public function set_session_location(REQUEST $request){
        if ($request->has('current_latitude') && $request->has('current_longitude')) {
            $request->session()->put('current_latitude', $request->current_latitude);
            $request->session()->put('current_longitude', $request->current_longitude);
        }
        echo "success";
    }
    public function index(REQUEST $request){
        $page_heading = 'Find Doctors'; 
        $requestParams = $request->all();

        if ($request->has('current_latitude') && $request->has('current_longitude')) {
            $request->session()->put('current_latitude', $request->current_latitude);
            $request->session()->put('current_longitude', $request->current_longitude);
        }

        $current_lattiude = $request->current_latitude ?? null;
        $current_longitude = $request->current_longitude ?? null;
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 1000;
        $offset = ($page - 1) * $limit;
        $emirate_ids = $request->emirates_id ? explode(",", $request->emirates_id) : [];
        $area_ids = $request->area_id ? explode(",", $request->area_id) : [];
        $main_insurence_id = $request->insurance_id ?? '';
        $sub_insurance_id = $request->sub_insurance_id ?? '';
        $speciality_id = $request->specialty_id ?? null;
        $gender = $request->gender_id ?? '';
        $doctor_language = $request->language_id ? explode(",", $request->language_id) : [];
        $medical_condition = $request->medical_condition_id ?? '';
        $country_id = $request->cuntry_of_origin_id ?? '';
        $direct_call_enabled = $request->dirent_call_for_appointment ?? '';
        $instend_need = $request->ready_to_consult_instantly ?? '';
        $need_date = $request->need_date ? date('Y-m-d', strtotime($request->need_date)) : null;
        $selected_ids =  $request->selected_ids ? explode(",", $request->selected_ids) : [];

        $specialties = Specialty::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
        $insurencePolicies = InsurencePolicy::where('status', 1)->orderBy('title')->get()->pluck('title', 'id');
        $subInsurencePolicies = [];
        if ($request->insurance_id ?? null) {
            $subInsurencePolicies = SubInsurencePolicy::where('status', 1)->where('insurence_id', $request->insurance_id)->orderBy('title')->get()->pluck('title', 'id');
        }

        $medicalConditions = SpecialIntrests::where('status', 1)->orderBy('title')->get()->pluck('title', 'id');
        $languages = Languages::orderBy('title')->get()->pluck('title', 'id');
        $countries = CountryOfOrigin::orderBy('name')->get()->pluck('name', 'id');
        $genders = [1 => 'Male', 2 => 'Female', 3 => 'Others'];
        $emirates = Emirate::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');
        $areas = Area::where('active', 1)->orderBy('name_en')->get()->pluck('name_en', 'id');

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
                    ->when($instend_need, function ($query1) use ($need_date) {
                        $settings = SettingsModel::first();
                        $query1->whereHas('instantAppointments', function ($q) use ($need_date, $settings) {
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
    
            $hospitals = $query->select(['hospitals.id', 'name_en'])->limit($limit)->offset($offset)->get();
            $settings = SettingsModel::first();
            $max_radius = $settings->doctor_search_radius;
        return view('web.find_a_doctor', compact('page_heading',
            'specialties', 
            'insurencePolicies', 
            'subInsurencePolicies', 
            'medicalConditions', 
            'languages', 
            'countries', 
            'genders', 
            'emirates', 
            'areas', 
            'requestParams',
            'hospitals',
            'max_radius'
        ));
    }

    public function get_doctors(REQUEST $request){
        $status  = "1";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $validator = Validator::make($request->all(), [
            
           // 'access_token'=>'required',
        //    'current_lattiude'=>'required',
        //    'current_longitude'=>'required'

        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
        // $user_id = $this->validateAccesToken($request->access_token);
            if ($request->has('current_lattiude') && $request->has('current_longitude')) {
                $request->session()->put('current_latitude', $request->current_lattiude);
                $request->session()->put('current_longitude', $request->current_longitude);
            }
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
            $current_lattiude = $request->current_lattiude??'25.2048';
            $current_longitude = $request->current_longitude??'55.2708';
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

            $total_pages = ($total_count/$limit);
            
            if($list->count() > 0){
                $status = "1";
                $message = "data fetcehed successfully";
                $o_data['list'] = convert_all_elements_to_string($list->toArray());
            }else{
                $status = "0";
                $o_data['list'] =[];
                $message = "no data to list";
            }
            $o_data['total_count'] = (string) $total_count;
            $o_data['over_all_doctor_count']= (string)$over_all_doctor_count;
            $o_data['total_pages'] = (string)ceil($total_pages);
            $o_data['page'] = (string)$page;
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
            $limit =  1000;
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
            $need_date = $request->need_date ? date('Y-m-d', strtotime($request->need_date)) : date('Y-m-d');
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
}
