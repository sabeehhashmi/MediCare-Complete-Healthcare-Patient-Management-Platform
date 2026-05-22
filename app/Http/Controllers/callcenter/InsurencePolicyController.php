<?php

namespace App\Http\Controllers\callcenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InsurencePolicy;
use App\Models\SubInsurencePolicy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InsurencePolicyController extends Controller
{
    //
    public function index()
    {
       

        $page_heading = "Insurance Policy";
        $list = InsurencePolicy::orderBy('id','desc')->get();
        $mode='list';
        return view('admin.insurence_policy.list', compact('mode', 'page_heading', 'list'));
    }

    public function create($id = '')
    {
       
        $page_heading = 'Insurance Policy';
        $mode = "Create";
        $title_en  = '';
        $title_ar  = '';
        $status = '';


        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $role = InsurencePolicy::find($id);
            $title_en = $role->title;
            $status = $role->status;
            $title_ar = $role->title_ar;
        }
        return view('admin.insurence_policy.create', compact('mode', 'page_heading', 'id', 'status', 'title_en', 'title_ar'));
    }

    public function submit(REQUEST $request)
    {

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('admin.insurence_policy.list');
        $rules = [
                'title_en' => [
                    'required',
                    'min:3',
                    Rule::unique('insurence_policies', 'title')
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
            $title_ar = $request->title_ar;
            $title_en  = $request->title_en;
            $status = $request->status;
            $id         = $request->id;
            $check      = InsurencePolicy::whereRaw('Lower(title) = ?', [strtolower($title_en)])->where('id', '!=', $id)->get();

            if ($check->count() > 0) {
                $status = "0";
                $message = "Insurance Policy Already Addded";
                $errors['title_en'] = 'Insurance Policy Already Added';
                if($request->json_action){
                    return response()->json([
                        'success' => true,
                        'message' => "Insurance Policy Already Exist",
                        'id' => '',
                        'name' => ''
                    ]);
                }
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $role   = InsurencePolicy::find($id);
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();

                        DB::commit();
                        $status = "1";
                        $message = "Insurance Policy updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create Insurance Policy " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $role   = new InsurencePolicy();
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->created_by = Auth::user()->id??0;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();
                        $role_id            = $role->id;

                        DB::commit();
                        $status = "1";
                        $message = "Insurance Policy Added Successfully";
                        if($request->json_action){
                            return response()->json([
                                'success' => true,
                                'message' => "Insurance Policy added successfully",
                                'id' => $role_id,
                                'name' => $request->title_en
                            ]);
                        }
                    } catch (EXCEPTION $e) {
                        DB::rollback();
                        $message = "Faild to create Insurance Policy " . $e->getMessage();
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

        $item = InsurencePolicy::where(['id' => $id])->get();

        if ($item->count() > 0) {

            InsurencePolicy::where('id', '=', $id)->update(['status' => $request->status]);
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

        $category_data = InsurencePolicy::where(['id' => $id])->first();

        if ($category_data) {
            $sub_insurances = SubInsurencePolicy::where('insurence_id', $id)->count();
            if ($sub_insurances) {
                $message = "Insurance Policy cannot be delete, because this policy may have association with sub insurances.";
            } else {
                $category_data->delete();
                $message = "Insurance deleted successfully";
                $status = "1";
            }
        } else {
            $message = "Invalid Role data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }

    public function getSubInsurence($parent_id) {
        $data = SubInsurencePolicy::get();
        return response()->json($data);
    }
}
