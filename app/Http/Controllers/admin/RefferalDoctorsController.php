<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorIntrests;
use App\Models\DepartmentModel;
use Illuminate\Http\Request;
use App\Models\RefferalDoctor;
use App\Models\Referral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RefferalDoctorsController extends Controller
{
    //
    public function index(REQUEST $request)
    {
        if (!get_user_permission('special_intrests', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $referral = Referral::find($request->referral_id);
        $page_heading = "Refferal Doctor - ".$referral->title;
        $list = RefferalDoctor::where('referral_id',$request->referral_id)->orderBy('id','desc')->get();
        
        $mode='list';
        return view('admin.refferal_doctors.list', compact('mode', 'page_heading', 'list','referral'));
    }

    public function create(REQUEST $request,$id = '')
    {
        if (!get_user_permission('special_intrests', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $departments = DepartmentModel::orderBy('id','desc')->get();
        $referral = Referral::find($request->referral_id);
        $page_heading = 'Refferal Doctor - '.$referral->title;
        $mode = "Create";
        $title_en  = '';
        $title_ar  = '';
        $title_ban  = '';
        $department  = '';
        $description  = '';
        $status = '';
        $referral_id = $request->referral_id;

       
        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $role = RefferalDoctor::find($id);
            $title_en = $role->title;
            $status = $role->status;
            $title_ar = $role->title_ar;
            $title_ban = $role->title_ban;
            $department = $role->department_id;
            $description = $role->description;
            $referral_id = $role->referral_id;
        }

        
        
        return view('admin.refferal_doctors.create', compact('mode', 'page_heading', 'id', 'status', 'title_en', 'title_ar',
        'title_ban','departments','description','department','referral_id','referral'));
    }

    public function submit(REQUEST $request)
    {

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('admin.refferal_doctors.list').'?referral_id='.$request->referral_id;
        $rules = [
            'title_en' => [
                'required',
                'min:2',
                Rule::unique('medicines', 'title')
                ->ignore($request->id)
                ->where(function ($query) {
                    // Adjust the query to handle deleted_at correctly
                    $query->whereNull('deleted_at'); // Assuming '1' is not a valid timestamp
                }),
            ],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            
            $title_en  = $request->title_en;
            $department_id  = $request->department_id;
            $status = $request->status;
            $referral_id = $request->referral_id;
            $id         = $request->id;
            
            $check      = RefferalDoctor::whereRaw('Lower(title) = ?', [strtolower($title_en)])->where('id', 0)->get();

            if ($check->count() > 0) {
                $status = "0";
                $message = "Refferal Doctor Already Addded";
                $errors['title_en'] = 'Refferal Doctor Already Added';
                if($request->json_action){
                    return response()->json([
                        'success' => true,
                        'message' => "Refferal Doctor Already Added successfully",
                        'id' => '',
                        'name' => ''
                    ]);
                }
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $role   = RefferalDoctor::find($id);
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->department_id  = $department_id;
                        $role->referral_id  = $referral_id;
                        $role->last_updated_by = Auth::user()->id??0;

                        if ($request->hasfile('image')) {
                            $file = $request->file('image');
                            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                            $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $role->image = $file_name;
                            }
                        $role->save();

                        DB::commit();
                        $status = "1";
                        $message = "Refferal Doctor updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create special intrest " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $role   = new RefferalDoctor();
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->department_id  = $department_id;
                        $role->referral_id  = $referral_id;
                        $role->created_by = Auth::user()->id??0;
                        $role->last_updated_by = Auth::user()->id??0;
                    
                        $role->save();
                        $role_id            = $role->id;

                        DB::commit();
                        $status = "1";
                        $message = "Refferal Doctor Added Successfully";
                        if($request->json_action){
                            return response()->json([
                                'success' => true,
                                'message' => "Refferal Doctor added successfully",
                                'id' => $role_id,
                                'name' => $request->title_en
                            ]);
                        }
                    } catch (EXCEPTION $e) {
                        DB::rollback();
                        $message = "Faild to create special intrest " . $e->getMessage();
                    }
                }
            }
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function change_status(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data  = [];
        $errors = [];

        $id = $request->id;

        $item = RefferalDoctor::where(['id' => $id])->get();

        if ($item->count() > 0) {

            RefferalDoctor::where('id', '=', $id)->update(['status' => $request->status]);
            $status = "1";
            $message = "Status changed successfully";
        } else {
            $message = "Faild to change status";
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function delete(REQUEST $request, $id)
    {
        $status = "0";
        $message = "";

        $id = decrypt($id);

        $category_data = RefferalDoctor::where(['id' => $id])->first();

        if ($category_data) {
            
                $category_data->delete();
                $message = "Refferal Doctor deleted successfully";
                $status = "1";
        } else {
            $message = "Invalid type data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }
}
