<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HospitalInsurancePolicy;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\InsurencePolicy;
use App\Models\SubInsurencePolicy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubInsurencePolicyController extends Controller
{
    //
    public function index()
    {
        if (!get_user_permission('sub_insurence_policy', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Sub Insurance Policy";
        $list = SubInsurencePolicy::with('insurence_with_trashed')->orderBy('id','desc')->get();
        $mode='list';
        return view('admin.sub_insurence_policy.list', compact('mode', 'page_heading', 'list'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('sub_insurence_policy', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = 'Sub Insurance Policy';
        $mode = "Create";
        $title_en  = '';
        $title_ar  = '';
        $status = '';
        $insurence_id = '';


        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $role = SubInsurencePolicy::find($id);
            $title_en = $role->title;
            $status = $role->status;
            $title_ar = $role->title_ar;
            $insurence_id = $role->insurence_id;
        }
        $insurence_list = InsurencePolicy::orderBy('title','asc')->get();
        return view('admin.sub_insurence_policy.create', compact('mode', 'page_heading', 'id', 'status', 'title_en', 'title_ar','insurence_id','insurence_list'));
    }

    public function submit(REQUEST $request)
    {

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('admin.sub_insurence_policy.list');
        $rules = [
            'title_en' => [
                'required',
                'min:3',
                Rule::unique('sub_insurence_policies', 'title')
                ->ignore($request->id)
                ->where(function ($query) {
                    // Adjust the query to handle deleted_at correctly
                    $query->whereNull('deleted_at'); // Assuming '1' is not a valid timestamp
                }),

            ],
            'insurence_id'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $title_ar = $request->title_ar;
            $title_en  = $request->title_en;
            $status = $request->status;
            $id         = $request->id;
            $insurence_id = $request->insurence_id;
            $check      = SubInsurencePolicy::whereRaw('Lower(title) = ?', [strtolower($title_en)])->where(['insurence_id'=>$insurence_id])->where('id', '!=', $id)->get();

            if ($check->count() > 0) {
                $status = "0";
                $message = "Sub Insurance Already Addded";
                $errors['title_en'] = 'Sub Insurance Already Added';
                if($request->json_action){
                    return response()->json([
                        'success' => true,
                        'message' => "Sub Insurance Policy Already Exist",
                        'id' => '',
                        'name' => ''
                    ]);
                }
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $role   = SubInsurencePolicy::find($id);
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->insurence_id = $insurence_id;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();

                        DB::commit();
                        $status = "1";
                        $message = "Sub Inusurence updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create Sub Insurance " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $role   = new SubInsurencePolicy();
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->insurence_id = $insurence_id;
                        $role->created_by = Auth::user()->id??0;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();
                        $role_id            = $role->id;

                        DB::commit();
                        $status = "1";
                        $message = "Sub Inusurence Added Successfully";
                        if($request->json_action){
                            return response()->json([
                                'success' => true,
                                'message' => "Sub Insurance Policy added successfully",
                                'id' => $role_id,
                                'name' => $request->title_en
                            ]);
                        }
                    } catch (EXCEPTION $e) {
                        DB::rollback();
                        $message = "Faild to create Sub Insurance " . $e->getMessage();
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

        $item = SubInsurencePolicy::where(['id' => $id])->get();

        if ($item->count() > 0) {

            SubInsurencePolicy::where('id', '=', $id)->update(['status' => $request->status]);
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

        $category_data = SubInsurencePolicy::where(['id' => $id])->first();

        if ($category_data) {
            $users = User::where('sub_insurence_id', $id)->count();
            $hospital_policies = HospitalInsurancePolicy::where('sub_insurance_id', $id)->count();
            if ($users || $hospital_policies) {
                $message = "Sub insurance policy cannot be delete, because this policy may have association with user or hospital.";
            } else {
                $category_data->delete();
                $message = "Sub insurance deleted successfully";
                $status = "1";
            }
        } else {
            $message = "Invalid Role data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }
}
