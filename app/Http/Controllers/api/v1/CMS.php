<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Models\CountryModel;
use App\Models\States;
use App\Models\Article;
use App\Models\Area;
use App\Models\HelpModel;
use App\Models\ContactUsModel;
use App\Models\ContactUsSetting;
use App\Models\NationalityModel;
use Illuminate\Http\Request;
use Validator;

class CMS extends Controller
{
    //
    public function default_location(Request $request)
    {
        $default = Cities::select('cities.id as city_id', 'states.id as emirate_id', 'cities.name as city_name', 'states.name as emirate_name')
            ->where('cities.id', 4)
            ->join('states', 'states.id', '=', 'cities.state_id')
            ->get()->first();
        return response()->json([
            'status' => (string) 1,
            'message' => 'Data fetched successfully',
            'errors' => [],
            'oData' => convert_all_elements_to_string($default),
        ], 200);
    }
    public function countrylist(Request $request)
    {
        $countries = CountryModel::where('active', 1)->get();
        if (!empty($countries)) {
            foreach ($countries as $key => $value) {
                
                $value->dial_code = (string) $value->dial_code;
             
            }
        }
        return response()->json([
            'status' => (string) 1,
            'message' => 'Data fetched successfully',
            'errors' => [],
            'oData' => convert_all_elements_to_string($countries),
        ], 200);
    }

    public function states(Request $request)
    {
        $lang = $request->header('lang');
        $name = "name";
        if($lang =='ar')
        {
            $name = "name_ar";
        }
        $where['states.deleted'] = 0;
        $where['states.active'] = 1;
        if ($request->country_id) {
            $where['states.country_id'] = $request->country_id;
        }
        $states = States::select('id', $name)->where($where)->orderby($name, 'asc')->get();
        return response()->json([
            'status' => (string) 1,
            'message' => 'Data fetched successfully',
            'errors' => [],
            'oData' => convert_all_elements_to_string($states),
        ], 200);
    }
    public function cities(Request $request)
    {
        $where['deleted'] = 0;
        $where['active'] = 1;
        // if ($request->state_id) { 
        //     $where['cities.state_id'] = $request->state_id;
        // }
        // if ($request->country_id) { 
        //     $where['cities.country_id'] = $request->country_id;
        // }
        //$cities = Cities::select('id', 'name','state_id')->where($where)->orderby('name', 'asc')->get();
        
        
        $cities = States::select('id', 'name','id as state_id')->where($where)->orderby('name', 'asc')->get();
       
        return response()->json([
            'status' => (string) 1,
            'message' => 'Data fetched successfully',
            'errors' => [],
            'oData' =>convert_all_elements_to_string( $cities),
        ], 200);
    }

    public function nationality(Request $request)
    {
        $lang = $request->header('lang');
        $name = "name";
        if($lang =='ar')
        {
            $name = "name_ar";
        }
        $countries = NationalityModel::select('*',$name)->where('active', 1);

        if(!empty($request->search))
        {
            
            $countries = $countries->where('name', 'ilike', '%' . $request->search . '%');
        }
        
        $countries = $countries->orderBy('name','asc')->get();
        if (!empty($countries)) {
            foreach ($countries as $key => $value) {
                $value->dial_code = (string) $value->dial_code;

                //$value->country_flag = get_uploaded_image_url($value->country_flag,config('global.flag_image_upload_dir'),'admin-assets/assets/img/placeholder.jpg');

            }
        }
        return response()->json([
            'status' => (string) 1,
            'message' => 'Data fetched successfully',
            'errors' => [],
            'oData' => convert_all_elements_to_string($countries),
        ], 200);
    }
    public function areas(Request $request)
    {
        $where['status'] = 1;
        if ($request->country_id) {
            $where['country_id'] = $request->country_id;
        }
        if ($request->city_id) {
            $where['city_id'] = $request->city_id;
        }
        $areas = Area::select('id', 'name')->where($where)->orderby('name', 'asc')->get();
        return response()->json([
            'status' => (string) 1,
            'message' => 'Data fetched successfully',
            'errors' => [],
            'oData' => convert_all_elements_to_string($areas),
        ], 200);
    }
    public function get_page(Request $request)
    {
        $status = (string) 0;
        $message = "";
        $o_data = [];

        $lang = $request->header('lang');
        $title = "title_en";
        $desc_en = "desc_en";
        if($lang == "ar")
        {
            $title = "title_ar as title_en";
            $desc_en = "desc_ar as desc_en";
        }

        $page_data = Article::select('*',$title,$desc_en)->where(['id' => $request->page_id]);
        if($request->type)
        {
            $page_data =$page_data->where('type',$request->type);
        }
        $page_data =$page_data->get();
        if ($page_data->count() > 0) {
            $status = (string) 1;
            $message = "data fetched successfully";
            $o_data = $page_data->first();
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => [],
            'oData' => convert_all_elements_to_string($o_data->toArray()),
        ], 200);
    }
    public function get_faq(Request $request)
    {
        $status = (string) 0;
        $message = "";
        $o_data = [];

        $lang = $request->header('lang');
        $title = "title";
        $desc_en = "description";
        if($lang == "ar")
        {
            $title = "title_ar as title";
            $desc_en = "description_ar as description";
        }


        $page_data = \App\Models\FaqModel::select('*',$title,$desc_en)->orderBy('id', 'asc')->get();
        if ($page_data->count() > 0) {
            $status = (string) 1;
            $message = "data fetched successfully";
            $o_data = $page_data;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => [],
            'oData' => convert_all_elements_to_string($o_data),
        ], 200);
    }
    public function gethelp(Request $request)
    {
        $status = (string) 0;
        $message = "";
        $o_data = [];


        $page_data = HelpModel::orderBy('id', 'asc')->get();
        if ($page_data->count() > 0) {
            $status = (string) 1;
            $message = "data fetched successfully";
            $o_data = $page_data;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => [],
            'oData' => convert_all_elements_to_string($o_data),
        ], 200);
    }
    function submit_contact_us(Request $request)
    {


        $status = (string) 0;
        $message = "";
        $errors = '';
        $validator = Validator::make(
            $request->all(),
            [
                //'name' => 'required',
                //'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
                //'dial_code' => 'required',
                //'phone' => 'required',
                'message' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = (string) 0;
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $name = $request->name;
            $email = $request->email;
            $phone = $request->dial_code . " " . $request->phone;
            $msg = $request->message;

            $contact['name'] = $name??'';
            $contact['email'] = $email??'';
            $contact['dial_code'] = $request->dial_code??'';
            $contact['mobile'] = $request->phone??'';
            $contact['message'] = $msg;
            $contact['updated_at'] = gmdate('Y-m-d H:i:s');
            $contact['created_at'] = gmdate('Y-m-d H:i:s');
            ContactUsModel::insert($contact);

            $mailbody =  view("mail.contact_us", compact('name', 'email', 'phone', 'msg'));
            $to = ContactUsSetting::first();

            $status = (string) 1;
            $message = "Successfully submited";
            $errors = '';

            // if (send_email($to->email, 'New Contact Form Received', $mailbody)) {
            //     $status = (string) 1;
            //     $message = "Successfully submited";
            //     $errors = '';
            // } else {
            //     $status = (string) 0;
            //     $message = "Something went wrong";
            //     $errors = '';
            // }
        }
        return response()->json([
            'status' => (string) 1,
            'message' => $message,
            'errors' => $errors
        ], 200);
    }

    function contact_settings(Request $request)
    {

        $o_data = [];
        $status = (string) 0;
        $message = "";
        $errors = '';
        $validator = Validator::make(
            $request->all(),
            [
            ]
        );
        if ($validator->fails()) {
            $status = (string) 0;
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
           
            $o_data = ContactUsSetting::first()->toArray();
            $o_data = convert_all_elements_to_string($o_data);
          
        }
        return response()->json([
            'status' => (string) 1,
            'message' => $message,
            'errors' => $errors,
            'oData' => $o_data,
        ], 200);
    }
}