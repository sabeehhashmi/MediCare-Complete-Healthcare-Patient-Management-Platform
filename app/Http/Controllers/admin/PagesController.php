<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Article;
use App\Models\ContactQuery;
use Illuminate\Http\Request;
use App\Models\ContactUsSetting;
use App\Models\SettingsModel;
use App\Models\ProfileBio;
use Illuminate\Support\Facades\Validator;
use DB;

class PagesController extends Controller
{
    public function pharmacy()
    {
        $page_heading = "Pharmacy";
        return view('admin.pharmacy.index',compact('page_heading'));
    }
    public function drug_dosage()
    {
        $page_heading = "Drug Dosage";
        return view('admin.drug_dosage.index',compact('page_heading'));
    }
    public function drug_brand()
    {
        $page_heading = "Drug Brand";
        return view('admin.drug_brand.index',compact('page_heading'));
    }
    public function drug_direction()
    {
        $page_heading = "Drug Direction";
        return view('admin.drug_direction.index',compact('page_heading'));
    }
    public function drug_frequency()
    {
        $page_heading = "Drug Frequency";
        return view('admin.drug_frequency.index',compact('page_heading'));
    }
    public function drug_duration()
    {
        $page_heading = "Drug Duration";
        return view('admin.drug_duration.index',compact('page_heading'));
    }
    
    public function contact_quries()
    {
        $page_heading = "Contact Us Queries";
        $queries  = ContactQuery::orderBy('id', 'Desc')->get();
        return view('admin.contact.index',compact('page_heading','queries'));
    }


    public function contact_details()
    {
        if (!get_user_permission('contact_detail_settings','u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Contact Us Details";
        $page  = ContactUsSetting::first();
        if($page == null){
            $page  = new ContactUsSetting();
        }
        return view('admin.contact.contact_settings',compact('page_heading','page'));
    }
    public function settings()
    {
        if (!get_user_permission('settings','u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Settings";
        $page  = SettingsModel::first();
        $bio = ProfileBio::get();
        
        return view('admin.contact.settings',compact('page_heading','page','bio'));
    }
    public function setting_store(Request $request)
    {
        $table = SettingsModel::first();
        
        $table->instant_appoitment_number  =  $request->instant_appoitment_number;
        $table->doctor_search_radius   =  $request->doctor_search_radius;
        $table->support_email = $request->support_email;
        $table->support_phone = $request->support_phone;
        $table->shipping_price = $request->shipping_price;
        $table->comission = $request->comission;
        $table->loyallty_points_enable = $request->loyallty_points_enable;
        $table->loyallty_points_amount = $request->loyallty_points_amount;
        $table->loyallty_points_on_amount = $request->loyallty_points_on_amount;
        $table->loyallty_points_for_percentage = $request->loyallty_points_for_percentage;
        $table->loyallty_points_percentage = $request->loyallty_points_percentage;

        if ($request->hasfile('consent')) {
                $file = $request->file('consent');
                $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $table->consent = $file_name;
            }
      
        if ($table->save()){
            $message = 'Setting has been updated.';
            return redirect()->back()->with('success',  $message);
        }
        
        return redirect()->back()->with('error', 'Unable to update setting');
    }
    public function contact_us_setting_store(Request $request)
    {
        $contact = ContactUsSetting::first();
        
        if($contact == null){
            $contact  = new ContactUsSetting();
            $message = 'Contact us setting has been Created.';
        }
        $contact->title_en  =  $request->title_en;
        $contact->title_ar  =  $request->title_ar;
        $contact->email  =  $request->email;
        $contact->mobile  =  $request->mobile;
        $contact->desc_en  =  $request->desc_en;
        $contact->desc_ar  =  $request->desc_ar;
        $contact->location  =  $request->location;
        $contact->latitude  =  $request->latitude;
        $contact->longitude  =  $request->longitude;
      $contact->linkedin  =  $request->linkedin;
        $contact->twitter  =  $request->twitter;
        $contact->youtube  =  $request->youtube;
        $contact->facebook  =  $request->facebook;
        $contact->instagram  =  $request->instagram;
        $contact->working_hours  =  $request->working_hours;
        $contact->uae_phone  =  $request->uae_phone;
        $contact->uae_email  =  $request->uae_email;
        $contact->uk_phone  =  $request->uk_phone;
        $contact->uk_email  =  $request->uk_email;
        $contact->uk_location  =  $request->uk_location;

        if ($contact->save()){
            $message = 'Contact us setting has been updated.';
            return redirect()->back()->with('success',  $message);
        }
        
        return redirect()->back()->with('error', 'Unable to update Contact us setting');
    }
    
    public function prompt_face_verification(){
        exec("php ".base_path()."/artisan prompt_verification:driver  > /dev/null 2>&1 & ");  
        $message = 'Request sent successfully';
        return redirect()->back()->with('success',  $message);
    }

    public function index(Request $request){
        if (!get_user_permission('settings','r')) {
            return redirect()->route('admin.restricted_page');
        }
        $type = $request->type;
        $page_heading = "CMS Pages - ".cms_type($request->type);
        $cms_pages = Article::orderBy('id','desc')->where('type',$request->type)->get();
        return view('admin.cms_pages.index', compact('cms_pages','page_heading','type'));
    }

    public function create(Request $request){
        if (!get_user_permission('settings','c')) {
            return redirect()->route('admin.restricted_page');
        }
        $type = $request->type;
        $page_heading = "Add New Page";
        $cms_page = new Article();
        $cms_page->status = 1;
        return view('admin.cms_pages.form', compact('page_heading','cms_page','type'));
    }
    public function edit(Request $request, $id){
        if (!get_user_permission('settings','u')) {
            return redirect()->route('admin.restricted_page');
        }
        $type = $request->type;
        $page_heading = "Update Page";
        $cms_page = Article::where("id", $id)->first();
        return view('admin.cms_pages.form', compact('page_heading','cms_page','type'));
    }

    public function save(Request $request)
    {
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $redirectUrl = '';
        $id      = $request->id;
        $rules   = [
            'title_en'      => 'required',
            'desc_en'       =>'required',
        ];
        $validator = Validator::make($request->all(),$rules,
        [
            'title_en.required' => 'Title required',
            'desc_en.required' => 'Description Engish required',
           
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        }else{
            $input = $request->all();
            if ($request->id != null) {
                $cms_page = Article::find($request->id);
            } else {
                $cms_page = new Article();
            }
            $cms_page->status     = $request->status == 1 ? 1 : 0;
            $cms_page->title_en     = $request->title_en;
            $cms_page->title_ar     = $request->title_ar;
            $cms_page->desc_en = $request->desc_en;
            $cms_page->desc_ar = $request->desc_ar;
            $cms_page->type = $request->type ?? 2;
            $cms_page->save();
            $status="1";
             $message='Record has been saved successfully';
       }
        echo json_encode(['status'=>$status,'message'=>$message,'errors'=>$errors]);
        
    }

    
    public function delete($id){
        $record = Article::find($id);
        $status="0";
        $message="Page removal failed";
        if($record){
            $record->delete();
           $status="1";
           $message="Page removed successfully";
        } 
        
        echo json_encode(['status' => $status, 'message' => $message]);
    }


}
