<?php

namespace App\Http\Controllers\doctor;

use App\CityModel;
use App\Categories;
use App\StateModel;
use App\Models\DoctorHolidays;
use App\Models\DoctorLanguageSpoken;
use App\Models\DoctorQualifications;
use App\Models\DoctorSpecialities;
use App\Models\DoctorAvailability;
use App\Models\DoctorIntrests;
use App\Models\User;
use App\Partnership;
use App\PackageModel;
use App\Models\Cities;
use App\Models\States;
use App\UserDocsModel;
use App\Models\Languages;
use App\Models\LicenceType;
use App\Models\BankModel;
use App\Models\AccountNotification;
use App\AccountTypesModel;
use App\IndustryTypesModel;
use App\Models\AccountType;
use App\Models\VendorModel;
use App\Models\ActivityType;
use App\Models\CountryModel;
use App\Models\CountryOfOrigin;
use Illuminate\Http\Request;
use App\Models\BankCodetypes;
use App\Models\BankdataModel;
use App\Models\DepartmentModel;
use App\Models\IndustryTypes;
use App\Models\SubInsurencePolicy;
use App\Models\Emirate;
use App\Models\UserLocations;
use App\TransactionFeesModel;
use App\Models\VendorDetailsModel;
use App\Models\SpecialIntrests;
use Illuminate\Support\Facades\DB;
use App\Models\PublicBusinessInfos;
use App\Http\Controllers\Controller;
use App\Models\TempVendorUpdateData;
use App\Models\StoreTableSlotes;
use App\Models\DoctorPatientAppointment;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\Hospital;
use App\Models\Qualifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Libraries\Agora\RtcTokenBuilder;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_heading = "Users";
        return view("admin.users.buyer_list", compact("page_heading"));
    }
    public function buyers()
    {
        $page_heading = "Users";
        $filter = ['res_groups.name' => 'buyer'];
        $params = [];
        $search_key = $_GET['search_key'] ?? '';
        $params['search_key'] = $search_key;
        $list = User::get_users_list($filter, $params)->orderBy('res_users.id', 'desc')->paginate(10);
        $packages = PackageModel::where('deleted', 0)->get()->toArray();
        return view("admin.users.user_list", compact("page_heading", "list", 'search_key', 'packages'));
    }
    public function sellers()
    {
        $page_heading = "Vendors";
        $filter = ['res_groups.name' => 'seller', 'res_users.deleted' => 0];
        $params = [];
        $search_key = $_GET['search_key'] ?? '';
        $params['search_key'] = $search_key;
        $list = Users::get_users_list($filter, $params)->orderBy('res_users.id', 'desc')->paginate(10);
        $packages = PackageModel::where('deleted', 0)->get()->toArray();
        return view("admin.users.buyer_list", compact("page_heading", "list", 'search_key', 'packages'));
    }
    public function trashed()
    {
        $page_heading = "Trashed Members";
        $filter = ['res_groups.name' => 'seller', 'res_users.deleted' => 1];
        $params = [];
        $search_key = $_GET['search_key'] ?? '';
        $params['search_key'] = $search_key;
        $list = Users::get_users_list($filter, $params)->paginate(10);
        $packages = PackageModel::where('deleted', 0)->get()->toArray();
        return view("admin.users.trashed_user_list", compact("page_heading", "list", 'search_key', 'packages'));
    }
    public function delete_document($id = '')
    {
        UserDocsModel::where('id', $id)->delete();
        $status = "1";
        $message = "Document removed successfully";
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    public function delete_user($id = '')
    {
        Users::delete_user($id);
        $status = "1";
        $message = "User removed successfully";
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    public function active_user($id = '')
    {
        Users::active_user($id);
        $status = "1";
        $message = "User activated successfully";
        echo json_encode(['status' => $status, 'message' => $message]);
    }

    public function verify_user($id)
    {
        $status = "0";
        $message = "";
        $user = Users::find($id);
        if ($user) {
            if ($user->user_verified == 1) {
                $user_verified = 0;
            } else {
                // if(!$user->user_package){
                //$message = "Membership not assigned!! Please assign membership package first";
                // echo json_encode(['status' => $status, 'message' => $message,'user_id'=>$id,'st'=>'membrshp_not_asgnd']);die();
                //}
                $user_verified = 1;
            }

            $ret = Users::update_user(['user_verified' => $user_verified, 'updated_on' => gmdate('Y-m-d H:i:s'), 'updated_uid' => session('user_id')], $id);
            if ($ret) {
                if ($user_verified == 1) {
                    $link = url('portal/login');
                    $mailbody =  view("web.emai_templates.verify_mail", compact('user', 'link'));
                    $res = send_email($user->email, 'Your Membership To The My events Marketplace Has Been Approved', $mailbody);
                    if ($res) {
                        Users::update_user([
                            'is_verify_email_sent' => 1
                        ], $id);
                    }
                }
                $status = "1";
                $message = $user_verified == 1 ? "Verified successfully" : 'User Rejected';
            } else {
                $message = "Faild to update";
            }
        } else {
            $message = "User not found";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    public function assign_package(Request $request)
    {

        $status = "0";
        $message = "";
        $errors = '';
        $validator = Validator::make(
            $request->all(),
            [
                'membership_package' => 'required',
                'alt_api_key' => 'required',
                'alt_secret_key' => 'required',
                'alt_merchant_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $id = $request->user_id;
            $user = Users::find($id);
            if ($user) {
                if ($user->user_verified == 1) {
                    $user_verified = 0;
                } else {
                    $user_package = $request->membership_package;
                    $user_verified = 1;
                }
                $ret = Users::update_user([
                    'user_verified' => $user_verified,
                    'user_package' => $user_package,
                    'updated_on' => gmdate('Y-m-d H:i:s'),
                    'updated_uid' => session('user_id'),
                    'alt_api_key'   =>  $request->alt_api_key,
                    'alt_secret_key'    =>  $request->alt_secret_key,
                    'alt_merchant_id'   =>  $request->alt_merchant_id
                ], $id);
                if ($ret) {
                    if ($user_verified == 1 && !$user->is_verify_email_sent) {
                        $link = url('login');
                        $mailbody =  view("web.emai_templates.verify_mail", compact('user', 'link'));
                        $res = send_email($user->email, 'Your Membership To The Oodle Marketplace Has Been Approved', $mailbody);
                        if ($res) {
                            Users::update_user([
                                'is_verify_email_sent' => 1
                            ], $id);
                        }
                    }
                    $status = "1";
                    $message = $user_verified == 1 ? "Verified successfully" : 'User Rejected';
                } else {
                    $message = "Faild to update";
                }
            } else {
                $message = "User not found";
            }
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_users($id)
    {

        $rltn = [
            'docs' => function ($qr) {
                $qr->orderBy('id', 'asc');
            },
            'user_categories' => function ($qr1) {
                $qr1->orderBy('id', 'asc');
            },
        ];

        $data['user'] = Users::select('res_users.*', 'phone', 'place', 'passport_number', 'passport_file', 'trade_licence_number', 'trade_licence_file', 'res_user_additional_info.description', 'po_box', 'website_url', 'industry_type', 'account_type', 'transaction_fee', 'photo_id')->leftjoin('res_user_additional_info', 'res_user_additional_info.user_id', '=', 'res_users.id')->join('res_users_groups', 'res_users_groups.user_id', '=', 'res_users.id')
            ->join('res_groups', 'res_groups.id', '=', 'res_users_groups.group_id')
            ->where('res_groups.name', 'buyer')->where('res_users.id', $id)
            ->with($rltn)->get()->toArray();
        if (!$data['user']) {
            abort(404);
        } else {
            $data['user'] = $data['user'][0];
            // $data['user']['states'] = StateModel::select('id', 'name')->where(['deleted' => 0, 'active' => 1, 'country_id' => $data['user']['country_id']])->get();

            // $data['user']['cities'] = CityModel::select('id', 'name')->where(['deleted' => 0, 'active' => 1, 'state_id' => $data['user']['state_id']])->get();
        }
        $data['user']['user_categories'] = array_column($data['user']['user_categories'], 'category_id');
        $data['country_list'] = CountryModel::where(['deleted' => 0])->orderBy('name', 'asc')->get();
        $data['parent_categories'] = Categories::where(['deleted' => 0, 'active' => 1, 'parent_id' => 0])->get();
        $industry_types = IndustryTypesModel::where(['deleted' => 0, 'active' => 1])->orderBy('name', 'asc')->get();
        $account_types = AccountTypesModel::where(['deleted' => 0, 'active' => 1])->orderBy('name', 'asc')->get();
        $transaction_fees = TransactionFeesModel::where(['deleted' => 0, 'active' => 1])->orderBy('name', 'asc')->get();
        $page_heading = "Edit Profile";
        $packages = PackageModel::where('deleted', 0)->get()->toArray();
        return view("admin.users.users_details", compact('page_heading', "data", 'packages', 'industry_types', 'account_types', 'transaction_fees'));
    }
    public function edit($id)
    {

        $rltn = [
            'docs' => function ($qr) {
                $qr->orderBy('id', 'asc');
            },
            'user_categories' => function ($qr1) {
                $qr1->orderBy('id', 'asc');
            },
        ];

        $data['user'] = Users::select('res_users.*', 'phone', 'place', 'passport_number', 'res_users.description as description', 'res_users.photo_id', 'res_users.passport_file', 'res_users.trade_licence_number', 'res_users.trade_licence_file', 'po_box', 'website_url', 'industry_type', 'account_type', 'transaction_fee')->leftjoin('res_user_additional_info', 'res_user_additional_info.user_id', '=', 'res_users.id')->join('res_users_groups', 'res_users_groups.user_id', '=', 'res_users.id')
            ->join('res_groups', 'res_groups.id', '=', 'res_users_groups.group_id')
            ->where('res_groups.name', 'seller')->where('res_users.id', $id)
            ->with($rltn)->get()->toArray();
        if (!$data['user']) {
            abort(404);
        } else {
            $data['user'] = $data['user'][0];
            // $data['user']['states'] = StateModel::select('id', 'name')->where(['deleted' => 0, 'active' => 1, 'country_id' => $data['user']['country_id']])->get();

            // $data['user']['cities'] = CityModel::select('id', 'name')->where(['deleted' => 0, 'active' => 1, 'state_id' => $data['user']['state_id']])->get();
        }
        $data['user']['user_categories'] = array_column($data['user']['user_categories'], 'category_id');
        $data['country_list'] = CountryModel::where(['deleted' => 0])->orderBy('name', 'asc')->get();
        $data['parent_categories'] = Categories::where(['deleted' => 0, 'active' => 1, 'parent_id' => 0])->get();
        $industry_types = IndustryTypesModel::where(['deleted' => 0, 'active' => 1])->orderBy('name', 'asc')->get();
        $account_types = AccountTypesModel::where(['deleted' => 0, 'active' => 1])->orderBy('name', 'asc')->get();
        $transaction_fees = TransactionFeesModel::where(['deleted' => 0, 'active' => 1])->orderBy('name', 'asc')->get();
        $page_heading = "Edit Profile";
        $packages = PackageModel::where('deleted', 0)->get()->toArray();
        return view("admin.users.detail", compact('page_heading', "data", 'packages', 'industry_types', 'account_types', 'transaction_fees'));
    }
    public function update_seller(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';

        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:res_users,email,' . $request->id . ',id',
                'country_id' => 'required',
                //'state' => 'required',
                'city' => 'required',
                //'phone' => 'required|numeric',
                'dial_code' => 'required',
                'mobile' => 'required|numeric',
                'place' => 'required',
                //'passport_number' => 'required',
                //'trade_licenece_number'=> 'required',
                // 'password'       => 'required|confirmed',
                'passport_file' => 'mimes:jpeg,png,jpg,pdf',
                'trade_licence' => 'mimes:jpeg,png,jpg,pdf',
                'user_image' => 'image',
                'description' => 'required',
                //'po_box'=>'required',
                // 'website_url'=>'url',
                'business_name' => 'required',
                //'industry_type'=>'required',
                //'dob'=>'required',
                //'account_type'=>'required',
                //'transaction_fee'=>'required',
                'photo_id' => 'mimes:jpeg,png,jpg,pdf',
                'commission' => 'required|numeric|min:0|max:99',
                //'public_wallet_address'=>'required',
                //'alt_api_key'=>'required',
                //'alt_secret_key'=>'required',
                //'alt_merchant_id'=>'required',
            ],
            [
                'passport_file.required' => 'P.O.A required',
                'country_id.required' => 'Country required',
                'state.required' => 'State required',
                'city.required' => 'City required',
                'mobile.integer' => 'Enter valid mobile',
                'phone.integer' => 'Enter valid contact number',
                'passport_file.image' => 'should be in image format (.jpg,.jpeg,.png)',
                'trade_licence.image' => 'should be in image format (.jpg,.jpeg,.png)',
                'user_image.image' => 'should be in image format (.jpg,.jpeg,.png)',
            ]
        );
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $id = $request->id;
            $category_ids = $request->category_id;

            $user_table_ins = [
                'username' => $request->email,
                'email' => strtolower($request->email),
                'dial_code' => $request->dial_code,
                'mobile' => $request->mobile,
                'mobile_verified' => 1,
                'updated_on' => gmdate('Y-m-d H:i:s'),
                'updated_uid' => 0,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'display_name' => $request->first_name . " " . $request->last_name,
                'country_id' => $request->country_id,
                'state' => $request->state,
                'city' => $request->city,
                'ip_address' => $request->ip(),
                'user_verified' => $request->user_verified,
                'user_package' => $request->user_package ?? 0,
                'business_name'           =>  $request->business_name,
                'business_name_arabic'    =>  $request->business_name_arabic,
                'dob'           =>  date('Y-m-d', strtotime($request->dob)),
                'public_wallet_address' => $request->public_wallet_address,
                'alt_api_key'   =>  $request->alt_api_key,
                'alt_secret_key'    =>  $request->alt_secret_key,
                'alt_merchant_id'   =>  $request->alt_merchant_id,
                'commission'        =>  $request->commission,
                'address_1'             => $request->place,
                'trade_licence_number'  => $request->trade_licenece_number,
                'description'  =>  $request->description,

            ];
            if ($request->password) {
                $user_table_ins['password'] = md5($request->password);
            }
            $additional_info = [
                'phone' => $request->phone,
                'place' => $request->place,
                'passport_number' => $request->passport_number,
                'trade_licence_number' => $request->trade_licenece_number,
                'description' => $request->description,
                'po_box' => $request->po_box,
                'website_url' => $request->website_url,
                'industry_type' => $request->industry_type,
                'account_type' => $request->account_type,
                'transaction_fee' => $request->transaction_fee,
            ];

            if ($file = $request->file("passport_file")) {
                $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                $file_name = time() . $file->getClientOriginalName();
                $file->move($dir, $file_name);
                //$file->storeAs(config('global.user_image_upload_dir'),$file_name,'s3');
                $user_table_ins['passport_file'] = $file_name;
            }
            if ($file = $request->file("trade_licence")) {
                $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                $file_name = time() . $file->getClientOriginalName();
                $file->move($dir, $file_name);
                //$file->storeAs(config('global.user_image_upload_dir'),$file_name,'s3');
                $user_table_ins['trade_licence_file'] = $file_name;
                $additional_info['trade_licence_file'] = $file_name;
            }
            if ($file = $request->file("user_image")) {
                if (isset($request->cropped_user_image) && $request->cropped_user_image) {
                    $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                    $image_parts = explode(";base64,", $request->cropped_user_image);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $imageName = uniqid() . time() . '.' . $image_type;
                    //$path = \Storage::disk('s3')->put(config('global.user_image_upload_dir').$imageName, $image_base64);
                    //$path = \Storage::disk('s3')->url($path);
                    file_put_contents($dir . '/' . $imageName, $image_base64);
                    $user_table_ins['user_image'] = $imageName;
                } else {
                    $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                    $file_name = time() . $file->getClientOriginalName();
                    // $file->move($dir, $file_name);
                    $file->storeAs(config('global.user_image_upload_dir'), $file_name, 's3');
                    $user_table_ins['user_image'] = $file_name;
                }
            }
            if ($file = $request->file("photo_id")) {
                $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                $file_name = time() . $file->getClientOriginalName();
                $file->move($dir, $file_name);
                //$file->storeAs(config('global.user_image_upload_dir'),$file_name,'s3');
                $user_table_ins['photo_id'] = $file_name;
            }

            $button_counter = $request->button_counter;
            $other_doc_ins = [];

            for ($i = 1; $i <= $button_counter; $i++) {
                if ($file = $request->file("other_doc_image_" . $i)) {
                    $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');

                    $file_name = time() . $file->getClientOriginalName();
                    // $file->move($dir, $file_name);
                    $file->storeAs(config('global.user_image_upload_dir'), $file_name, 's3');
                    $other_doc_ins[] = ['title' => $request->{"other_doc_title_$i"}, 'doc_path' => $file_name];
                }
            }
            $ret = Users::edit_user($user_table_ins, $additional_info, $category_ids, $other_doc_ins, $id);
            if ($ret) {
                $status = "1";
                $message = "Successfully updated";
            } else {
                $status = "0";
                $message = "Something went wrong";
            }
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }
    public function update_user(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';

        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:res_users,email,' . $request->id . ',id',
                'country_id' => 'required',
                //'state' => 'required',
                'city' => 'required',
                //'phone' => 'required|numeric',
                'dial_code' => 'required',
                'mobile' => 'required|numeric',
                'place' => 'required',
                //'passport_number' => 'required',
                'trade_licenece_number' => '',
                // 'password'       => 'required|confirmed',
                'passport_file' => 'mimes:jpeg,png,jpg,pdf',
                'trade_licence' => 'mimes:jpeg,png,jpg,pdf',
                'user_image' => 'image',
                'description' => '',
                //'dob'=>'required',
                //'account_type'=>'required',
                //'transaction_fee'=>'required',
                'photo_id' => 'mimes:jpeg,png,jpg,pdf',
                //'public_wallet_address'=>'required',
                //'alt_api_key'=>'required',
                //'alt_secret_key'=>'required',
                //'alt_merchant_id'=>'required',
            ],
            [
                'passport_file.required' => 'P.O.A required',
                'country_id.required' => 'Country required',
                'state.required' => 'State required',
                'city.required' => 'City required',
                'mobile.integer' => 'Enter valid mobile',
                'phone.integer' => 'Enter valid contact number',
                'passport_file.image' => 'should be in image format (.jpg,.jpeg,.png)',
                'trade_licence.image' => 'should be in image format (.jpg,.jpeg,.png)',
                'user_image.image' => 'should be in image format (.jpg,.jpeg,.png)',
            ]
        );
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $id = $request->id;
            $category_ids = $request->category_id;

            $user_table_ins = [
                'username' => $request->email,
                'email' => strtolower($request->email),
                'dial_code' => $request->dial_code,
                'mobile' => $request->mobile,
                'mobile_verified' => 1,
                'updated_on' => gmdate('Y-m-d H:i:s'),
                'updated_uid' => 0,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'display_name' => $request->first_name . " " . $request->last_name,
                'country_id' => $request->country_id,
                'state' => $request->state,
                'city' => $request->city,
                'ip_address' => $request->ip(),
                'user_verified' => $request->user_verified,
                'user_package' => $request->user_package ?? 0,
                'business_name'           =>  $request->business_name,
                'business_name_arabic'    =>  $request->business_name_arabic,
                'dob'           =>  date('Y-m-d', strtotime($request->dob)),
                'public_wallet_address' => $request->public_wallet_address,
                'alt_api_key'   =>  $request->alt_api_key,
                'alt_secret_key'    =>  $request->alt_secret_key,
                'alt_merchant_id'   =>  $request->alt_merchant_id
            ];
            if ($request->password) {
                $user_table_ins['password'] = md5($request->password);
            }
            $additional_info = [
                'phone' => $request->phone,
                'place' => $request->place,
                'passport_number' => $request->passport_number,
                'trade_licence_number' => $request->trade_licenece_number,
                'description' => $request->description,
                'po_box' => $request->po_box,
                'website_url' => $request->website_url,
                'industry_type' => $request->industry_type,
                'account_type' => $request->account_type,
                'transaction_fee' => $request->transaction_fee,
            ];

            if ($file = $request->file("passport_file")) {
                $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                $file_name = time() . $file->getClientOriginalName();
                // $file->move($dir, $file_name);
                $file->storeAs(config('global.user_image_upload_dir'), $file_name, 's3');
                $additional_info['passport_file'] = $file_name;
            }
            if ($file = $request->file("trade_licence")) {
                $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                $file_name = time() . $file->getClientOriginalName();
                $file->move($dir, $file_name);
                //$file->storeAs(config('global.user_image_upload_dir'),$file_name,'s3');

                $additional_info['trade_licence_file'] = $file_name;
            }
            if ($file = $request->file("user_image")) {
                if (isset($request->cropped_user_image) && $request->cropped_user_image) {
                    $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                    $image_parts = explode(";base64,", $request->cropped_user_image);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $imageName = uniqid() . time() . '.' . $image_type;
                    //$path = \Storage::disk('s3')->put(config('global.user_image_upload_dir').$imageName, $image_base64);
                    //$path = \Storage::disk('s3')->url($path);
                    file_put_contents($dir . '/' . $imageName, $image_base64);
                    $user_table_ins['user_image'] = $imageName;
                } else {
                    $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                    $file_name = time() . $file->getClientOriginalName();
                    // $file->move($dir, $file_name);
                    $file->storeAs(config('global.user_image_upload_dir'), $file_name, 's3');
                    $user_table_ins['user_image'] = $file_name;
                }
            }
            if ($file = $request->file("photo_id")) {
                $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                $file_name = time() . $file->getClientOriginalName();
                // $file->move($dir,$file_name);
                $file->storeAs(config('global.user_image_upload_dir'), $file_name, 's3');
                $additional_info['photo_id'] = $file_name;
            }

            $button_counter = $request->button_counter;
            $other_doc_ins = [];

            for ($i = 1; $i <= $button_counter; $i++) {
                if ($file = $request->file("other_doc_image_" . $i)) {
                    $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');

                    $file_name = time() . $file->getClientOriginalName();
                    // $file->move($dir, $file_name);
                    $file->storeAs(config('global.user_image_upload_dir'), $file_name, 's3');
                    $other_doc_ins[] = ['title' => $request->{"other_doc_title_$i"}, 'doc_path' => $file_name];
                }
            }
            $ret = Users::edit_user($user_table_ins, $additional_info, $category_ids, $other_doc_ins, $id);
            if ($ret) {
                $status = "1";
                $message = "Successfully updated";
            } else {
                $status = "0";
                $message = "Something went wrong";
            }
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function holiday(){
       
        $page_heading="Holiday";
        $auth_id = auth()->id();
        $doctor = Doctor::where('user_id',$auth_id)->get()->first();
        $doctor_id =  $doctor->id;

        return view('doctor.holiday',compact('page_heading','doctor_id'));




    }

    public function instantAppointment(){

       
        $page_heading="Instant Appointment";
        $auth_id = auth()->id();
        $doctor = Doctor::where('user_id',$auth_id)->get()->first();
        $doctor_id =  $doctor->id;
        // $time_slot = [
        //     "08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30",
        //     "13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
        //     "18:00","18:30","19:00","19:30","20:00"
        // ];

        return view('doctor.instantAppointment',compact('page_heading',
        'doctor_id'));



    }

    public function temporaryUnavailable(){
       
        $page_heading="Temporary Unavailable";
        $auth_id = auth()->id();
        $doctor = Doctor::where('user_id',$auth_id)->get()->first();
        $doctor_id =  $doctor->id;
        $time_slot = [
            "08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30",
            "13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
            "18:00","18:30","19:00","19:30","20:00"
        ];

        return view('doctor.temporaryUnavailable',compact('page_heading','time_slot',
        'doctor_id'));


    }
    public function holiday_save(REQUEST $request){
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
            $auth_id = auth()->id();
            $doctor = Doctor::where('user_id',$auth_id)->get()->first();
            $doctor_id =  $doctor->id;
            $page_heading="Doctors";
            $message = "Doctor holiday Save Successfully";
            return view('doctor.holiday',compact('page_heading','doctor_id'));
         
        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }

    }
    public function availability(){
       
        $page_heading="Availability";
        $auth_id = auth()->id();
        $doctor = Doctor::where('user_id',$auth_id)->get()->first();
        $doctor_id =  $doctor->id;
        
        //$doctor_id = auth()->id();
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

        $doctor = DoctorAvailability::find($doctor_id);
        
        if($doctor){
          
        $sunday_availability = $doctor->sunday_availability;
        $sunday_time_slot = json_decode($doctor->sunday_time_slot);
        $monday_availability = $doctor->monday_availability;
        $monday_time_slot = json_decode($doctor->monday_time_slot);
        $tuesday_availability = $doctor->tuesday_availability;
        $tuesday_time_slot = json_decode($doctor->tuesday_time_slot);   
        $wednesday_availability = $doctor->wednesday_availability;
        $wednesday_time_slot = json_decode($doctor->wednesday_time_slot);
        $thursday_availability = $doctor->thursday_availability;
        $thursday_time_slot = json_decode($doctor->thursday_time_slot);
        $friday_availability = $doctor->friday_availability;
        $friday_time_slot = json_decode($doctor->friday_time_slot);
        $saturday_availability = $doctor->saturday_availability;
        $saturday_time_slot = json_decode($doctor->saturday_time_slot);
        }
        $time_slot = [
            "08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30",
            "13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
            "18:00","18:30","19:00","19:30","20:00"
        ];

        return view('doctor.availability',compact('page_heading','time_slot',
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
        'doctor_id'));

    }
    public function availability_save(REQUEST $request){
        DB::beginTransaction();
        try {

           $doctor = DoctorAvailability::find($request->doctor_id);
           
            if($doctor === null){
                $doctor   = new DoctorAvailability();
            } 
            $doctor->doctor_id   =  $request->doctor_id;
            $doctor->sunday_availability    =   ($request->sunday_availability)?$request->sunday_availability:"0";
            $doctor->sunday_time_slot     =   json_encode($request->sunday_time_slot);
            $doctor->monday_availability     =  ($request->monday_availability)?$request->monday_availability:"0";
            $doctor->monday_time_slot     =    json_encode($request->monday_time_slot); 
            $doctor->tuesday_availability =  ($request->tuesday_availability)?$request->tuesday_availability:"0";
            $doctor->tuesday_time_slot     =  json_encode($request->tuesday_time_slot);
            $doctor->wednesday_availability  =   ($request->wednesday_availability)?$request->wednesday_availability:"0";
            $doctor->wednesday_time_slot      =    json_encode($request->wednesday_time_slot);
            $doctor->thursday_availability    =    ($request->thursday_availability)?$request->thursday_availability:"0";
            $doctor->thursday_time_slot =      json_encode($request->thursday_time_slot);
            $doctor->friday_availability =  ($request->friday_availability)?$request->friday_availability:"0";
            $doctor->friday_time_slot      =  json_encode($request->friday_time_slot);
            $doctor->saturday_availability    =  ($request->saturday_availability)?$request->saturday_availability:"0";
            $doctor->saturday_time_slot = json_encode($request->saturday_time_slot);
            $doctor->created_at = gmdate('Y-m-d H:i:s');
            $doctor->updated_at = gmdate('Y-m-d H:i:s');
            $doctor->save(); 
            DB::commit();
            $status = "1";
            $page_heading="Doctors";
            $message = "Doctor Availability Save Successfully";
            return response()->json([
                'status' => $status,
                'message' => $message,
            ]);
         
        } catch (Exception $e) {
            DB::rollback();
            $message = "Failed to create special intrest " . $e->getMessage();
        }

    }
    public function change_password(Request $request)
    {
        if ($request->isMethod('post')) {
            $status = "0";
            $message = "";
            $o_data['redirect'] = '';
            $errors = [];
            $validator = Validator::make($request->all(), [
                'cur_pswd' => 'required',
                'new_pswd' => 'required', 
                'confirm' => 'required|same:new_pswd', // Ensure new password and confirm match
            ], [
                'cur_pswd.required' => 'Current password is required',
                'new_pswd.required' => 'New password is required',
                'confirm.required' => 'Confirm password is required',
                'confirm.same' => 'New password and Confirm password must match',
            ]);
            if ($validator->fails()) {
                $status = "0";
                $message = "Validation error occured";
                $errors = $validator->messages();
            } else {
                $cur_pswd = $request->cur_pswd;
                $new_pswd = $request->new_pswd;
                $user_id = session("user_id");
                if (!Auth::attempt(['id' => $user_id, 'password' => $cur_pswd])) {
                    $validator->errors()->add('cur_pswd', 'Current password is not matched.');
                    $status = "0";
                    $message = "Validation error occured";
                    $errors = $validator->messages();
                } else{
                    $up['password'] = bcrypt($new_pswd);
                    $up['updated_on'] = gmdate('Y-m-d H:i:s');
                    if (User::update_password($user_id, $new_pswd)) {
                        $status = "1";
                        $message = "Password successfully changed";
                        $errors = '';
                    } else {
                        $status = "0";
                        $message = "Unable to change password. Please try again later";
                        $errors = '';
                    }
                }
            }
            echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors,'oData' => $o_data]);
            die();
        } else {
            $page_heading = "Change Password";
            $module_heading = "Doctor Profile";
            $loginuserid = Auth::id();
            $doctor = Doctor::where('user_id',$loginuserid)->get()->first();
            $hospital = $doctor->hospital ?? null;
            $hospitalId  = $hospital->id ?? null;
            $doctorId  = $hospital->id ?? null;
            $name       = $hospital->name_en ?? '';
            return view("doctor.users.change_password", compact('page_heading','module_heading','hospitalId','name','hospital', 'doctor', 'doctorId'));
        }
    }

    public function send_mail($id)
    {
        $id = base64_decode($id);
        $user = Users::where('id', '=', $id)->get()->first();
        if (!empty($user)) {
            $page_heading = "Send Mail to " . $user->display_name;
            return view("admin.users.send_mail", compact("page_heading", "user"));
        } else {
            abort(404);
        }
    }
    public function submit_mail(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $user = Users::where('id', '=', $request->id)->first();
        if (!empty($user)) {
            $message = $request->message;
            $mailbody =  view("web.emai_templates.custom_mail", compact('message', 'user'));
            $ret = send_email($user->email, 'OODLE', $mailbody);
            if ($ret) {
                $status = "1";
                $message = "Mail sent successfully";
            } else {
                $status = "0";
                $message = "Faild to sent mail";
            }
        }
        echo json_encode(['status' => $status, 'messsage' => $message]);
    }

    public function edit_profile()
    {
        $id = auth()->id();
        // dd($id);
        $country_list = CountryOfOrigin::where(['status'=>1])->select(['id','name'])->orderBy('name','asc')->get();
        // ->whereIn('prefix', ALLOWED_COUNTRIES_PREF)
        // ->get();
        $emirates_list=[];
        $area_list = [];
        $country_id = '';
        $emirate_id = '';
        $area_id    = '';
        $first_name = '';
        
        $hospital_name = Hospital::orderBy('name_en','asc')->get();

        $last_name  = '';
        $qualification = Qualifications::where(['status'=>1])->orderBy('title','asc')->get();
        $specialty = Specialty::where(['active'=>1])->orderBy('name_en','asc')->get();
        $special_interest = SpecialIntrests::where(['status'=>1])->orderBy('title','asc')->get();
        $experiences = '';
        $license_no = '';
        $license_type = LicenceType::where(['status'=>1])->orderBy('title','asc')->get();
        $qualification_id = '';
        $license_type_id = '';
        $language_spoken_id = '';
        $speciality_id = '';
        $special_intrest_id = '';
        $language_spoken = Languages::where(['status'=>1])->orderBy('title','asc')->get();
        $gender = '';
        $phone = '';
        $email = '';
        $profile_bio = '';
        $direct_phone = '';
        $dial_code = '';
        $appointment_dial_code = '';
        $appointment_phone = '';
        $doctor_id = '';
        $department_list = [];
        $department_ids = [];
        $is_clinic = 0;
        $user_signature_url = '';
        

        if($id){
            $page_heading="Edit Profile";
            $module_heading="Doctor Profile";
            $doctor = Doctor::where('user_id',$id)->get()->first();
            
            $user = User::where('id',$doctor->user_id)->get()->first();
            
            $dial_code =  $user->dial_code;
            $phone     =   str_replace(" ","",ltrim($user->phone,"0"));
            $email = $user->email;
            if($doctor){  
                $doctor_id = $doctor->id; 
                $hospital_id = $doctor->hospital_id;    
                $first_name = $user->first_name;
                $last_name = $user->last_name;
                $country_id = $doctor->country_id;
                $user_signature_url = $doctor->user_signature;
                $language_spoken_id =  $doctor->doctorLanguageSpoken->pluck('language_spoken_id')->toArray();
                $profile_bio = $doctor->profile_desciription;
                $qualification_id = $doctor->doctorQualifications->pluck('qualification_id')->toArray();
                $speciality_id = $doctor->doctorSpecialities->pluck('speciality_id')->toArray();
                $special_intrest_id = $doctor->doctorIntrests->pluck('special_intrest_id')->toArray();
                $experiences=$doctor->year_of_experiance;
                $license_no = $doctor->license_no;
                $license_type_id =json_decode($doctor->license_type_id);
                $gender =$doctor->gender;
                $appointment_dial_code  = $doctor->appointment_dial_code;
                $appointment_phone      = str_replace(" ","",$doctor->appointment_phone);
                $department_list = $doctor->hospital->departments ?? [];
                $department_ids = $doctor->departments->pluck('id')->toArray();
                if(isset($doctor->hospital)){
                    $is_clinic =($doctor->hospital->type == 20)?1:0;
                }
            }
        }else{
            $country_id     = 229; //$country_list->first()->id??0;
            $emirates_list  = Emirate::where(['active'=>1,'country_id'=>$country_id])->orderBy('name_en','asc')->get();
            $emirate_id     = $emirates_list->first()->id??0;
            $area_list      = Area::where(['active'=>1,'emirate_id'=>$emirate_id])->orderBy('name_en','asc')->get();
        }


         
        return view("doctor.edit_profile", compact('page_heading','user_signature_url','is_clinic','doctor_id', 'doctor','appointment_dial_code','appointment_phone','email','id','dial_code','phone','hospital_id', 'country_list', 'emirates_list','area_list','country_id', 'emirate_id','area_id','hospital_name','first_name','last_name', 'qualification_id','license_type_id', 'language_spoken_id','speciality_id','special_intrest_id', 'qualification', 'specialty','special_interest','experiences','license_no', 'license_type', 'language_spoken','gender','phone', 'email','profile_bio','direct_phone', 'department_list', 'department_ids' ));
    }

    public function save(REQUEST $request)
    {
        // dd($request->all());
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $sanitizedDirectPhone = preg_replace('/\D/', '', $request->direct_phone ?? null);
        $request->merge(['phone' => $sanitizedPhone, 'direct_phone' => $sanitizedDirectPhone]);
        $status     = "0";
        $id = auth()->id();
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('doctor.get_profile');
        $rules = [
            'first_name' => 'required',
            'last_name' => 'nullable',
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],    
            'dial_code'=>'nullable|numeric',
            //'phone'=>'required|numeric|digits_between:8,12',
            //'direct_dial_code'=>'nullable|numeric',
            //'direct_phone'=>'nullable|numeric|digits_between:8,12',
            'hospital_id'=>'required|numeric|exists:hospitals,id',
            'departments' => 'array|min:1',
            'departments.*' => 'exists:departments,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    
        $validator = Validator::make($request->all(), $rules);


        

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
            // dd($errors);
        } else {
            // dd($id);
            $check_email      = User::whereRaw('Lower(email) = ?', [strtolower($request->email)])->where('id', '!=', $id)->get();
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $name =  $request->first_name.''.$request->last_name;
                        $language_spoken_id = $request->language_spoken_id ?? [];
                        $qualification_id = $request->qualification ?? [];
                        $speciality_id = $request->specialty ?? [];
                        $special_intrest_id = $request->special_interest ?? [];
                        $doctor   = Doctor::where('user_id', $id)->first();
                        $user   = $doctor->user;
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
                        if($request->password){
                            $user->password  = Hash::make($request->password);
                        }
                        $user->role      =   DOCTOR_ROLE;
                        $user->active    =   1;
                        $user->created_by = Auth::user()->id;
                        $user->last_updated_by = Auth::user()->id;
                        $user->updated_at = gmdate('Y-m-d H:i:s');
                        $user->save();

                        $doctor->user_id   = $id;
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
                       if ($request->hasfile('signature')) {
                            $file = $request->file('signature');
                            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $doctor->signature = $file_name;
                        }
                       
                        $doctor->save();
                       
                        // if ($request->has('departments')) {
                            $doctor->departments()->sync($request->departments ?? []);
                        // }
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
                        activity_log('profile_updated', "$user->name Updated");
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
                        if($request->password){
                            $user->password  = Hash::make($request->password);
                        }
                        $user->role      =   DOCTOR_ROLE;
                        $user->active    =   1;
                        $user->created_by = Auth::user()->id;
                        $user->last_updated_by = Auth::user()->id;
                        $user->created_at = gmdate('Y-m-d H:i:s');
                        $user->updated_at = gmdate('Y-m-d H:i:s');
                        $user->save();

                        $doctor =  new Doctor();
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
                        
                        // if ($request->has('departments')) {
                            $doctor->departments()->sync($request->departments ?? []);
                        // }

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


    // public function get_profile()
    // {
        
    //     $id = auth()->id();
    //     $page_heading = "My Profile";
    //     $doctor = Doctor::where('user_id',$id)->get()->first();
        
    //     $user = User::where('id',$id)->get()->first();
    //     $email = $user->email;
    //     $clinic_dial_code =  $user->dial_code;
    //     $clinicphone     =   str_replace(" ","",ltrim($user->phone,"0"));
    //     if($doctor){       
    //         $first_name = $user->first_name;
    //         $last_name = $user->last_name;
    //         $country_id = $doctor->country_id;
    //         $emirates_list  = Emirate::where(['active'=>1,'country_id'=>$country_id])->get()->first();
    //         $language_spoken_id =  $doctor->doctorLanguageSpoken->pluck('language_spoken_id')->toArray();
    //         $languages = Languages::where('status', 1)->whereIn('id', $language_spoken_id)->get(['id', 'title', 'title_ar']);
            
    //         $profile_bio = $doctor->profile_desciription;
    //         $qualification_id = $doctor->doctorQualifications->pluck('qualification_id')->toArray();
    //         $qualifications = Qualifications::where('status', 1)->whereIn('id', $qualification_id)->get(['id', 'title', 'title_ar']);
            
    //         $speciality_id = $doctor->doctorSpecialities->pluck('speciality_id')->toArray();
    //         $specialties = Specialty::where('active', 1)->whereIn('id', $speciality_id)->get(['id', 'name_en', 'name_ar']);
    //         $hospital_id = $doctor->hospital_id;
    //         $hospital_name = Hospital::where('id',$hospital_id)->get()->first();          
                        
    //         $special_intrest_id = $doctor->doctorIntrests->pluck('special_intrest_id')->toArray();
            
    //         $special_intrests = SpecialIntrests::where('status', 1)->whereIn('id', $special_intrest_id)->get(['id', 'title', 'title_ar']);
    //         $experiences=$doctor->year_of_experiance;
    //         $license_no = $doctor->license_no;
    //         $license_type_id =json_decode($doctor->license_type_id);
    //         $license_types = LicenceType::where('status', 1)->whereIn('id', $license_type_id)->get(['id', 'title', 'title_ar']);

    //         $gender =$doctor->gender;
    //         $appointment_dial_code  = $doctor->appointment_dial_code;
    //         $appointment_phone      = str_replace(" ","",$doctor->appointment_phone);
    //         $profile_bio = $doctor->profile_desciription;
            
     
         
    //     }

        
    //     return view("doctor.doctor_profile", compact('page_heading','first_name','last_name','specialties','hospital_name','gender','qualifications','special_intrests','experiences','license_no','appointment_dial_code','appointment_phone','email','clinic_dial_code','clinicphone','profile_bio','languages','license_types','emirates_list' ));
    // }

    // public function update_profile(Request $request)
    // {
    //     $status = "0";
    //     $message = "";
    //     $o_data = [];
    //     $errors = [];
    //     $redirectUrl = '';
    //     $id = auth()->id();

    //     $exisiting_req = TempVendorUpdateData::where('user_id', $id)->where('status', TempVendorUpdateData::PENDING)->first();

    //     if (!is_null($exisiting_req)) {
    //         $status = "0";
    //         $message = "You have already requested for update. Please wait for admin approval";
    //         echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    //         return;
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'phone' => 'required',
    //         'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
    //     ]);

    //     if ($validator->fails()) {
    //         $status = "0";
    //         $message = "Validation error occured";
    //         $errors = $validator->messages();
    //     } else {
    //         $input = $request->all();
    //         $lemail = strtolower($request->email);
    //         $check_exist = VendorModel::whereRaw("LOWER(email) = '$lemail'")->where('id', '!=', $id)->first();
    //         if (is_null($check_exist)) {
    //             $check_exist_phone = VendorModel::where('phone', $request->phone)->where('id', '!=', $id)->first();
    //             DB::beginTransaction();
    //             try {
    //                 if (is_null($check_exist_phone)) {
    //                     $v = VendorModel::find($id);

    //                     if ($v->profile_once_updated == 1) {
    //                         $array = $this->saveTempData($request, $id);
    //                         $status = $array['status'];
    //                         $message = $array['message'];
    //                     } else {
    //                         $array = $this->saveOriginalData($request, $id);
    //                         $status = $array['status'];
    //                         $message = $array['message'];
    //                     }
    //                 } else {
    //                     $status = "0";
    //                     $message = "Phone number should be unique";
    //                     $errors['phone'] = "Already exist";
    //                 }
    //             } catch (\Throwable $th) {
    //                 $status = "0";
    //                 $message = $th->getMessage();
    //                 $errors[] = $th->getLine() . " " . $th->getFile() . " " . $th->getMessage();
    //             }
    //         } else {
    //             $status = "0";
    //             $message = "Email should be unique";
    //             $errors['email'] = $request->email . " already added";
    //         }
    //     }

    //     if ($status == "1") {
    //         DB::commit();
    //     } else {
    //         DB::rollBack();
    //     }

    //     echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    // }

    public function get_profile()
    {
        $loginuserid = Auth::id();
        $doctor = Doctor::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $doctor->hospital_id;
        $hospital = $doctor->hospital ?? null;
        $insurances = [];
        if($hospital->insurences ?? [] && count($hospital->insurences)){
            $sub_insurances_ids = $hospital->insurences->pluck('sub_insurance_id');
            $subinsurancesData = SubInsurencePolicy::whereIn('id', $sub_insurances_ids)->get();
            foreach($subinsurancesData as $sub_insurance){
                $insurances[$sub_insurance->insurence_id]['insurance'] = $sub_insurance->insurence_with_trashed ;
                $insurances[$sub_insurance->insurence_id]['sub_insurances'][] = $sub_insurance;
            }
        }
        // dd($insurances[1]['insurance']);
        $totalPatients = DoctorPatientAppointment::where('doctor_id', $doctor->id)->distinct('user_id')->count('user_id');
        $totalAppointment = DoctorPatientAppointment::where('doctor_id', $doctor->id)->count();
        $page_heading="Doctor Profile";
        $module_heading="Doctor Profile";

        return view("doctor.doctor_profile", compact('page_heading', 'module_heading','totalPatients', 'totalAppointment', 'hospital', 'doctor', 'insurances'));
    }

    public function update_profile(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';
        $id = auth()->id();

        $exisiting_req = TempVendorUpdateData::where('user_id', $id)->where('status', TempVendorUpdateData::PENDING)->first();

        if (!is_null($exisiting_req)) {
            $status = "0";
            $message = "You have already requested for update. Please wait for admin approval";
            echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
            return;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();
            $lemail = strtolower($request->email);
            $check_exist = VendorModel::whereRaw("LOWER(email) = '$lemail'")->where('id', '!=', $id)->first();
            if (is_null($check_exist)) {
                $check_exist_phone = VendorModel::where('phone', $request->phone)->where('id', '!=', $id)->first();
                DB::beginTransaction();
                try {
                    if (is_null($check_exist_phone)) {
                        $v = VendorModel::find($id);

                        if ($v->profile_once_updated == 1) {
                            $array = $this->saveTempData($request, $id);
                            $status = $array['status'];
                            $message = $array['message'];
                        } else {
                            $array = $this->saveOriginalData($request, $id);
                            $status = $array['status'];
                            $message = $array['message'];
                        }
                    } else {
                        $status = "0";
                        $message = "Phone number should be unique";
                        $errors['phone'] = "Already exist";
                    }
                } catch (\Throwable $th) {
                    $status = "0";
                    $message = $th->getMessage();
                    $errors[] = $th->getLine() . " " . $th->getFile() . " " . $th->getMessage();
                }
            } else {
                $status = "0";
                $message = "Email should be unique";
                $errors['email'] = $request->email . " already added";
            }
        }

        if ($status == "1") {
            DB::commit();
        } else {
            DB::rollBack();
        }

        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    public function update_profile_image(Request $request) {
        // dd($request->all());
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image
            'user_id' => 'required||exists:users,id',
        ]);
        $user = User::find($request->user_id);
        $file = $request->file('image');
        $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
        $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
        $user->user_image = $file_name;
    
        // Update the user's profile image path
        $user->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Profile image updated successfully.',
        ]);
    }
    
    public function delete_images(Request $request) {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image
            'user_id' => 'required||exists:users,id',
        ]);
        $user = User::find($request->user_id);
        $file = $request->file('image');
        $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
        $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
        $user->user_image = $file_name;
    
        // Update the user's profile image path
        $user->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Profile image updated successfully.',
        ]);
    }

    public function saveTempData($request, $id)
    {
        $TempVendorUpdateData = [];

        $ins = [
            'name' => $request->name,
            // 'email' => $request->email, (dont update email)
            'dial_code' => $request->dial_code,
            'phone' => $request->phone,
            // 'role' => '3', //vendor
            // 'first_name' => $request->first_name,
            // 'last_name' => $request->last_name,
            'user_name' => $request->name,
            // 'user_type_id' => AccountType::RESERVATIONS,
            //'activity_type_id' => $request->activity_type_id, 
            'commercial_reg_no' => $request->commercial_reg_no,
            'address' => $request->address,
            'about_me' => $request->about_me
        ];

        $lat = "";
        $long = "";
        $location_name = $request->txt_location;
        if ($request->location) {
            $location = explode(",", $request->location);
            $lat = $location[0];
            $long = $location[1];
        }

        if ($request->file("user_image")) {
            $response = image_upload($request, 'users', 'user_image');
            if ($response['status']) {
                $ins['user_image'] = $response['link'];
            }
        }
        if ($request->file("banner_image")) {
            $response = image_upload($request, 'users', 'banner_image');
            if ($response['status']) {
                $ins['banner_image'] = $response['link'];
            }
        }

        if ($request->file("commercial_license")) {
            $response = image_upload($request, 'company', 'commercial_license');
            if ($response['status']) {
                $ins['commercial_license'] = $response['link'];
            }
        }

        if ($request->file("associated_license")) {
            $response = image_upload($request, 'company', 'associated_license');
            if ($response['status']) {
                $ins['associated_license'] = $response['link'];
            }
        }

        // $ins['updated_at'] = gmdate('Y-m-d H:i:s');
        $temp =  VendorModel::select(array_keys($ins))->find($id);
        $tempArray = $temp->toArray();

        if (array_key_exists('user_image', $tempArray)) {
            unset($tempArray['user_image']);
            $tempArray['user_image'] = $temp->getAttributes()['user_image'];
        }

        if (array_key_exists('banner_image', $tempArray)) {
            unset($tempArray['banner_image']);
            $tempArray['banner_image'] = $temp->getAttributes()['banner_image'];
        }

        $res = remove_common_elements($ins, $tempArray);

        $TempVendorUpdateData['VendorModel'] = $res['array1'];
        $TempVendorUpdateData['OldVendorModel'] = $res['array2'];


        $bankdata = BankdataModel::where('user_id', $id)->first();

        if ($bankdata) {
            $bank_data = [
                'bank_name' => $request->bank_name,
                'account_no' => $request->account_no,
                'iban_code' => $request->iban_code,
            ];
        } else {
            $bankdata = new BankdataModel();
            // $bankdata->bank_name = $request->bank_name;
            // $bankdata->account_no = $request->account_no;
            // $bankdata->iban_code = $request->iban_code;
            // $bankdata->user_id = $id;

            $bank_data = [
                'bank_name' => $request->bank_name,
                'account_no' => $request->account_no,
                'iban_code' => $request->iban_code,
                'user_id' => $id,
            ];
        }

        $BankDataModel = BankdataModel::select(array_keys($bank_data))->where('user_id', $id)->first();
        if (!is_null($BankDataModel)) {
            $res = remove_common_elements($bank_data, $BankDataModel->toArray());
            $TempVendorUpdateData['BankdataModel'] = $res['array1'];
            $TempVendorUpdateData['OldBankdataModel'] = $res['array2'];
        }else{
            $TempVendorUpdateData['BankdataModel'] = $bank_data;
            $TempVendorUpdateData['OldBankdataModel'] = [];
        }

        $loc_data = [
            'lattitude' => $lat,
            'longitude' => $long,
            'location_name' => $location_name,
            'user_id' => $id,
            // 'updated_at' => gmdate('Y-m-d H:i:s'),
        ];

        $UserLocations = UserLocations::select(array_keys($loc_data))->where('user_id', $id)->first();
        if (!is_null($UserLocations)) {
            $res = remove_common_elements($loc_data, $UserLocations->toArray());
            $TempVendorUpdateData['UserLocations'] = $res['array1'];
            $TempVendorUpdateData['OldUserLocations'] = $res['array2'];
        }else{
            $TempVendorUpdateData['UserLocations'] = $loc_data;
            $TempVendorUpdateData['OldUserLocations'] = [];
        }

        if (count($TempVendorUpdateData['VendorModel']) == 0 && count($TempVendorUpdateData['BankdataModel']) == 0 && count($TempVendorUpdateData['UserLocations']) == 0) {
            $status = "0";
            $message = "No changes found";
            return ['status' => $status, 'message' => $message];
        }

        $status = "1";
        $message = "Request submitted succesfully";

        $temp = TempVendorUpdateData::saveData($TempVendorUpdateData, $id);
        AccountNotification::create([
            'user_id' => $id,
            'title' => 'New Change Request',
            'message' => auth()->user()->name . ' just sent change request.',
            'type' => 1
        ]);

        return ['status' => $status, 'message' => $message];
    }

    public function saveOriginalData($request, $id)
    {
        $ins = [
            'name' => $request->name,
            // 'email' => $request->email, (dont update email)
            'dial_code' => $request->dial_code,
            'phone' => $request->phone,
            // 'role' => '3', //vendor
            // 'first_name' => $request->first_name,
            // 'last_name' => $request->last_name,
            'user_name' => $request->name,
            // 'user_type_id' => AccountType::RESERVATIONS,
            'activity_type_id' => $request->activity_type_id, 
            'commercial_reg_no' => $request->commercial_reg_no,
            'address' => $request->address,
            'about_me' => $request->about_me,
            'country_id'=>$request->country_id,
            'state_id'=>$request->state_id,
            'city_id'=>$request->city_id,
            'verified'=>0
        ];

        $lat = "";
        $long = "";
        $location_name = $request->txt_location;
        if ($request->location) {
            $location = explode(",", $request->location);
            $lat = $location[0];
            $long = $location[1];
        }

        if ($request->file("user_image")) {
            $response = image_upload($request, 'users', 'store_logo');
            if ($response['status']) {
                $ins['user_image'] = $response['link'];
            }
        }
        if ($request->file("banner_image")) {
            $response = image_upload($request, 'users', 'banner_image');
            if ($response['status']) {
                $ins['banner_image'] = $response['link'];
            }
        }

        if ($request->file("commercial_license")) {
            $response = image_upload($request, 'company', 'commercial_license');
            if ($response['status']) {
                $ins['commercial_license'] = $response['link'];
            }
        }

        if ($request->file("associated_license")) {
            $response = image_upload($request, 'company', 'associated_license');
            if ($response['status']) {
                $ins['associated_license'] = $response['link'];
            }
        }

        $ins['updated_at'] = gmdate('Y-m-d H:i:s');
        $user = VendorModel::find($id);
        $user->update($ins);

        $vendordata = VendorDetailsModel::where('user_id', $id)->first();
        if (empty($vendordata->id)) {
            $vendordatils = new VendorDetailsModel();
            $vendordatils->user_id = $id;
        } else {
            $vendordatils = VendorDetailsModel::find($vendordata->id);
        }
        $bankdata = BankdataModel::where('user_id', $id)->first();
        if ($bankdata) {
            $bankdata->bank_name = $request->bank_name;
            $bankdata->account_no = $request->account_no;
            $bankdata->iban_code = $request->iban_code;
            $bankdata->save();
        } else {
            $bankdata = new BankdataModel();
            $bankdata->bank_name = $request->bank_name;
            $bankdata->account_no = $request->account_no;
            $bankdata->iban_code = $request->iban_code;
            $bankdata->user_id = $id;
            $bankdata->save();
        }

        $loc = \App\Models\UserLocations::where('user_id', $id)->first();
        if (!$loc) {
            $loc = new \App\Models\UserLocations();
            $loc->user_id = $id;
        }
        $loc->lattitude = $lat;
        $loc->longitude = $long;
        $loc->location_name = $location_name;
        $loc->updated_at = gmdate('Y-m-d H:i:s');
        $loc->save();

        $status = "1";
        $message = "Updated succesfully";

        return ['status' => $status, 'message' => $message];
    }

    public function wait_for_verification()
    {
        //if (empty(Auth::user()->commercial_license) || empty(Auth::user()->associated_license) || empty(Auth::user()->commercial_reg_no)) {
        if (Auth::user()->profile_once_updated == 0) {
            return redirect()->route('vendor.edit_profile')->with('unverified_error', 'Please complete your profile to continue');
        } else {
            if (Auth::user()->verified == '1') {
                return redirect()->route('vendor.dashboard');
            } else {
                $page_heading = "Pending verification";
                return view('vendor.wait_for_verification', compact('page_heading'));
            }
        }
    }

    public function getStateList(Request $request){
        $states = States::select('id', 'name')->where('country_id',$request->country_id)->orderby('name', 'asc')->get();
        $html = view('admin.users.state_option',compact('states'))->render();
        return response()->json(['html' => $html],200);
    }

    public function table_settings(){
        
        $page_heading = "Table Settings";
        $settings = StoreTableSlotes::where(['store_id'=>Auth::user()->id])->get();
        return view("vendor.users.table_settings", compact("page_heading","settings"));
    }
    public function update_table_settings(REQUEST $request){
        $table_booking_available = $request->table_booking_available??0;
        $monday = $request->mon;
        $tuesday = $request->tue;
        $wednesday = $request->wed;
        $thursday = $request->thu;
        $friday = $request->fri;
        $saturday = $request->sat;
        $sunday = $request->sun;

        $ins = [];
        StoreTableSlotes::where(['store_id'=>Auth::user()->id])->delete();
        
           StoreTableSlotes::create([
                'store_id'          =>  Auth::user()->id,
                'is_available'      => $sunday['available']??0,
                'day'               => 'sun',
                'time_from'         => $sunday['opening_from']??'00:00',
                'time_to'           => $sunday['opening_to']??'23:00',
                'created_at'        => gmdate('Y-m-d H:i:s'),
                'updated_at'        => gmdate('Y-m-d H:i:s')
            ]);
        
            StoreTableSlotes::create([
                'store_id'          =>  Auth::user()->id,
                'is_available'      => $monday['available']??0,
                'day'               => 'mon',
                'time_from'         => $monday['opening_from']??'00:00',
                'time_to'           => $monday['opening_to']??'23:00',
                'created_at'        => gmdate('Y-m-d H:i:s'),
                'updated_at'        => gmdate('Y-m-d H:i:s')
            ]);
        
            StoreTableSlotes::create([
                'store_id'          =>  Auth::user()->id,
                'is_available'      => $tuesday['available']??0,
                'day'               => 'tue',
                'time_from'         => $tuesday['opening_from']??'00:00',
                'time_to'           => $tuesday['opening_to']??'23:00',
                'created_at'        => gmdate('Y-m-d H:i:s'),
                'updated_at'        => gmdate('Y-m-d H:i:s')
            ]);
        
            StoreTableSlotes::create([
                'store_id'          =>  Auth::user()->id,
                'is_available'      => $wednesday['available']??0,
                'day'               => 'wed',
                'time_from'         => $wednesday['opening_from']??'00:00',
                'time_to'           => $wednesday['opening_to']??'23:00',
                'created_at'        => gmdate('Y-m-d H:i:s'),
                'updated_at'        => gmdate('Y-m-d H:i:s')
            ]);
        
            StoreTableSlotes::create([
                'store_id'          =>  Auth::user()->id,
                'is_available'      => $thursday['available']??0,
                'day'               => 'thu',
                'time_from'         => $thursday['opening_from']??'00:00',
                'time_to'           => $thursday['opening_to']??'23:00',
                'created_at'        => gmdate('Y-m-d H:i:s'),
                'updated_at'        => gmdate('Y-m-d H:i:s')
            ]);
        
            StoreTableSlotes::create([
                'store_id'          =>  Auth::user()->id,
                'is_available'      => $friday['available']??0,
                'day'               => 'fri',
                'time_from'         => $friday['opening_from']??'00:00',
                'time_to'           => $friday['opening_to']??'23:00',
                'created_at'        => gmdate('Y-m-d H:i:s'),
                'updated_at'        => gmdate('Y-m-d H:i:s')
            ]);
        
        
            StoreTableSlotes::create([
                'store_id'          =>  Auth::user()->id,
                'is_available'      => $saturday['available']??0,
                'day'               => 'sat',
                'time_from'         => $saturday['opening_from']??'00:00',
                'time_to'           => $saturday['opening_to']??'23:00',
                'created_at'        => gmdate('Y-m-d H:i:s'),
                'updated_at'        => gmdate('Y-m-d H:i:s')
            ]);
        
       
        
        //StoreTableSlotes::create($ins);
        $user = User::find(Auth::user()->id);
        $user->is_table_booking_available = $request->table_booking_available??0;
        $user->slot_gap = $request->slot_gap;
        $user->slot_difference = $request->slot_difference;
        $user->no_of_seats = $request->no_of_seats;
        $user->save();
        $status = "1";
        $message = "slots updated successfully";
        return ['status' => $status, 'message' => $message];

    }

    public function getHospitalDepartments($hospitalId) {
        $departments = DepartmentModel::whereHas('hospitals', function ($query) use ($hospitalId) {
                $query->where('hospitals.id', $hospitalId);
            })
            ->orderBy('departments.title', 'asc')  // Sort departments by title in ascending order
            ->select('departments.*')  // Select all columns from departments table
            ->get()
            ->toArray();
    
        return response()->json($departments);
    }

    public function deleteAccount(Request $request)
{
    $user = auth()->user();
    $user->email=$user->email.'_deleted';
    $user->phone=$user->phone.'_deleted';
    $user->deleted='1';
    $user->save();
    // logout first
    auth()->logout();

    return response()->json(['status' => true]);
}
}
