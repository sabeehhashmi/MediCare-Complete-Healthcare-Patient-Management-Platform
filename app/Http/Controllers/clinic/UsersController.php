<?php

namespace App\Http\Controllers\clinic;

use App\CityModel;
use App\Categories;
use App\StateModel;
use App\Models\HospitalImage;
use App\Models\DoctorHolidays;
use App\Models\DoctorLanguageSpoken;
use App\Models\DoctorQualifications;
use App\Models\DoctorSpecialities;
use App\Models\DoctorAvailability;
use App\Models\HospitalDoctorFeedback;
use App\Models\DoctorIntrests;
use App\Models\User;
use App\Partnership;
use App\PackageModel;
use App\Models\Cities;
use App\Models\States;
use App\UserDocsModel;
use App\Models\Languages;
use App\Models\Area;
use App\Models\LicenceType;
use App\Models\BankModel;
use App\Models\AccountNotification;
use App\AccountTypesModel;
use App\IndustryTypesModel;
use App\Models\AccountType;
use App\Models\VendorModel;
use App\Models\ActivityType;
use App\Models\CountryModel;
use Illuminate\Http\Request;
use App\Models\BankCodetypes;
use App\Models\BankdataModel;
use App\Models\IndustryTypes;
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
use App\Models\Doctor;
use App\Models\DoctorPatientAppointment;
use App\Models\Specialty;
use App\Models\Hospital;
use App\Models\HospitalLocation;
use App\Models\Qualifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


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
            $o_data['redirect'] = route('clinic.change_password');
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
            $module_heading = "Clinic Profile";
            $loginuserid = Auth::id();
            $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
            $hospitalId  = $hospital->id;
            $name       = $hospital->name_en;
            return view("clinic.change_password", compact('page_heading','module_heading','hospitalId','name','hospital'));
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
        
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $hospital->id;
        $page_heading="Edit Clinic Profile";
        $module_heading="Clinic Profile";

       //  $country_list =  CountryModel::where(['active'=>1])->whereIn('prefix', ALLOWED_COUNTRIES_PREF)->get();
       $country_list =  CountryModel::where(['active'=>1])->get();
        $emirates_list=[];
        $area_list = [];
        $country_id = '';
        $emirate_id = '';
        $area_id    = '';
        $name       = '';
        $name_ar    = '';
        $trade_licenece = '';


        if($hospitalId){
            $hospital = Hospital::where('id',$hospitalId)->get()->first();
            
            if($hospital){
                $name       = $hospital->name_en;
                $area_id    = $hospital->area_id;
                $emirate_id = $hospital->emirate_id;
                $country_id = $hospital->country_id;
                $name_ar    = $hospital->name_ar;
                $trade_licenece = $hospital->trade_licence_url;
                $area_list      = Area::where(['active'=>1,'emirate_id'=>$emirate_id])->get();
                $emirates_list  = Emirate::where(['active'=>1,'country_id'=>$country_id])->get();
                $country = CountryModel::where(['active'=>1, 'id'=>$country_id])->first();
            }
            
           
        }else{
            $country_id     = 229; //$country_list->first()->id??0;
            // $emirates_list  = Emirate::where(['active'=>1,'country_id'=>$country_id])->get();
            // $emirate_id     = $emirates_list->first()->id??0;
            // $area_list      = Area::where(['active'=>1,'emirate_id'=>$emirate_id])->get();
        }
        //  dd($hospital->location);
        return view("clinic.edit_profile",  compact('page_heading', 'module_heading', 'hospital','country','hospitalId','country_list', 'emirates_list', 'area_list', 'country_id', 'emirate_id', 'area_id', 'name','name_ar','trade_licenece' ));
    }

    public function save(REQUEST $request)
    {
        $sanitizedPhone = preg_replace('/\D/', '', $request->phone);
        $sanitizedDirectPhone = preg_replace('/\D/', '', $request->direct_phone ?? null);
        $request->merge(['phone' => $sanitizedPhone, 'direct_phone' => $sanitizedDirectPhone]);

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('clinic.get_profile');
        $rules = [
            'name_en' => 'required',
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'image.*' => 'mimes:jpeg,png,pdf|max:2048',
            'website' => 'nullable|url',
            'trade_licenece' => 'mimes:jpg,jpeg,png,pdf|max:2048',
           // 'trade_licence' => 'mimes:jpeg,png,pdf|max:2048',
            'dial_code'=>'nullable|numeric',
            'phone'=>'nullable|numeric|digits_between:8,12',
            'direct_dial_code'=>'nullable|numeric',
            'direct_phone'=>'nullable|numeric|digits_between:8,12',
        ];
        // dd($request->all());

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            
            $id         = $request->id;
            
                if ($id) {
                    $hospital = Hospital::where('id', $id)->first();
                    $hospital->country_id = $request->country;
                    $hospital->emirate_id = $request->emirate_id;
                    $hospital->area_id    = $request->area_id;
                    $hospital->address    = $request->address;
                    $hospital->website    = $request->website;
                    $hospital->profile_description = $request->profile_bio;
                    $hospital->profile_description_ar = $request->profile_bio_ar;
                    $hospital->appointment_dial_code  = $request->direct_dial_code ? $request->direct_dial_code : DEFAULT_DIAL_CODE;
                    $hospital->appointment_phone      = str_replace(" ","",$request->direct_phone);
                    $hospital->name_en    = $request->name_en;
                    $hospital->name_ar    = $request->name_ar;
                    $hospital->save();
                    if ($request->has('department')) {
                        $hospital->departments()->sync($request->department);
                    }
                    
                    if($request->has('location')){
                        if ($hospital->location) {
                            HospitalLocation::where('hospital_id', $id)->delete();
                        }
                        $insurancePolicy = new HospitalLocation();
                        $insurancePolicy->hospital_id = $id;
                        $insurancePolicy->latitude = $request->latitude;
                        $insurancePolicy->longitude = $request->longitude;
                        $insurancePolicy->location = $request->location;
                        $insurancePolicy->save();
                    }

                    $user = User::find($hospital->user_id);
                    // dd($user);
                    // $user->email    = strtolower($request->email);
                    $user->name     = $request->name_en;
                    $user->dial_code = $request->dial_code ? $request->dial_code : DEFAULT_DIAL_CODE;
                    $user->phone     = str_replace(" ","",ltrim($request->phone,"0"));
                    if($request->password){
                        $user->password  = Hash::make($request->password);
                    }
                    $user->last_updated_by = Auth::user()->id;
                    $user->updated_at = gmdate('Y-m-d H:i:s');
                    $user->save();

                    if ($file = $request->file("trade_licenece")) {
                        $file_name = time().uniqid().".".$file->getClientOriginalExtension();
                        $file->storeAs(config('global.trade_licenece_image_upload_dir'), $file_name, config('global.upload_bucket'));
                        $hospital->trade_licenece = $file_name;
                    }

                    if ($request->has('remove_images') && $request->remove_images) {
                        // Delete existing images
                        HospitalImage::whereIn('id', explode(",", $request->remove_images))->delete();
                    }
                    if ($request->hasfile('images')) {
                        
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

                    DB::commit();
                    activity_log('profile_updated', "$user->name Updated");
                    $status = "1";
                    $message = "Hospital updated successfully";
                }
           // }
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function get_profile()
    {
        $loginuserid = Auth::id();
        $hospital = Hospital::where('user_id',$loginuserid)->get()->first();
        $hospitalId  = $hospital->id;
        $doctorIds = Doctor::where('hospital_id', $hospitalId)->pluck('id');
        $totaldoctors = Doctor::where('hospital_id', $hospitalId)->count();
        $totalpatients = DoctorPatientAppointment::whereIn('doctor_id', $doctorIds)->distinct('user_id')->count('user_id');
        $page_heading="Clinic Profile";
        $module_heading="Clinic Profile";

        $country_list =  CountryModel::where(['active'=>1])->get();
        $emirates_list=[];
        $area_list = [];
        $country_id = '';
        $emirate_id = '';
        $area_id    = '';
        $name       = '';
        $name_ar    = '';
        $trade_licenece = '';

        if($hospitalId){
            $hospital = Hospital::where('id',$hospitalId)->get()->first();
            if($hospital){
                $name       = $hospital->name_en;
                $area_id    = $hospital->area_id;
                $emirate_id = $hospital->emirate_id;
                $country_id = $hospital->country_id;
                $name_ar    = $hospital->name_ar;
                $trade_licenece = $hospital->trade_licence_url;
                $area_list      = Area::where(['active'=>1,'emirate_id'=>$emirate_id])->get();
                $emirates_list  = Emirate::where(['active'=>1,'country_id'=>$country_id])->get();
                $country = CountryModel::where(['active'=>1, 'id'=>$country_id])->first();
            }
            
           
        }else{
            $country_id     = 229; //$country_list->first()->id??0;
            $emirates_list  = Emirate::where(['active'=>1,'country_id'=>$country_id])->get();
            $emirate_id     = $emirates_list->first()->id??0;
            $area_list      = Area::where(['active'=>1,'emirate_id'=>$emirate_id])->get();
        }
        // dd($hospital->user);
        return view("clinic.hospital_profile", compact('page_heading', 'module_heading', 'totaldoctors','totalpatients','hospital','country','hospitalId','country_list', 'emirates_list', 'area_list', 'country_id', 'emirate_id', 'area_id', 'name','name_ar','trade_licenece' ));
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

public function HospitalDoctorsReviews(){
       

        // $reviews = HospitalDoctorFeedback::orderBy('id', 'desc')
        //     ->paginate(10);

        $reviews = HospitalDoctorFeedback::with(['doctor','user', 'hospital' ])
        ->orderBy('rating', 'asc')
        ->paginate(10);
        

        return view('clinic.reviews', compact('reviews'));

    }
}
