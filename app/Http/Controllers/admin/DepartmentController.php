<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DepartmentDoctor;
use Illuminate\Http\Request;
use App\Models\DepartmentModel;
use App\Models\Hospital;
use App\Models\Referral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    //
    public function index(REQUEST $request)
    {
        if (!get_user_permission('departments', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $hospital = null;
        $clinic = null;
        $doctor = null;
        $query = DepartmentModel::with(['hospitals','doctors']);

        if($request->hospital_id){
            $hospital = Hospital::find($request->hospital_id);
            $query->whereHas('hospitals', function($query) use ($request) {
                $query->where('hospital_id', $request->hospital_id);
            });
        }

        if($request->clinic_id){
            $clinic = Hospital::find($request->clinic_id);
            $query->whereHas('hospitals', function($query) use ($request) {
                $query->where('hospital_id', $request->clinic_id);
            });
        }

        if($request->doctor_id){
            $doctor = Doctor::find($request->doctor_id);
            $query->whereHas('doctors', function($query) use ($request) {
                $query->where('doctor_id', $request->doctor_id);
            });
        }

        $page_heading = "Departments";

        $list = $query->orderBy('id','desc')->get();
        // dd($list);
        $mode='list';
        return view('admin.departments.list', compact('mode', 'page_heading', 'list', 'hospital', 'clinic', 'doctor'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('departments', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = 'Departments';
        $mode = "Create";
        $title_en  = '';
        $title_ar  = '';
        $status = '';
        $referral_id = '';
        $referrals = Referral::orderBy('id', 'desc')->get();

        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $role = DepartmentModel::find($id);
            $title_en = $role->title;
            $status = $role->status;
            $title_ar = $role->title_ar;
            $referral_id = $role->referral_id;
        }
        return view('admin.departments.create', compact('mode', 'page_heading', 'id', 'status', 'title_en', 
        'title_ar','referrals','referral_id'));
    }

    public function submit(REQUEST $request)
    {

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('admin.departments.list');
        $rules = [
            'title_en' => 'required|min:3'
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
            $referral_id = $request->referral_id;
            $id         = $request->id;
            $check      = DepartmentModel::whereRaw('Lower(title) = ?', [strtolower($title_en)])->where('id', '!=', $id)->get();

            if ($check->count() > 0) {
                $status = "0";
                $message = "Department Already Addded";
                $errors['title_en'] = 'Department Already Added';
                if($request->json_action){
                    return response()->json([
                        'success' => true,
                        'message' => "Department Already Added",
                        'id' => '',
                        'name' => ''
                    ]);
                }
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $role   = DepartmentModel::find($id);
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->referral_id  = $referral_id;
                        // $role->last_updated_by = Auth::user()->id??0;
                        $role->save();

                        DB::commit();
                        $status = "1";
                        $message = "Department updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create Department " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $role   = new DepartmentModel();
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->referral_id  = $referral_id;
                        // $role->created_by = Auth::user()->id??0;
                        // $role->last_updated_by = Auth::user()->id??0;
                        $role->save();
                        $role_id            = $role->id;

                        DB::commit();
                        $status = "1";
                        $message = "Department Added Successfully";
                        if($request->json_action){
                            return response()->json([
                                'success' => true,
                                'message' => "Department added successfully",
                                'id' => $role_id,
                                'name' => $request->title_en
                            ]);
                        }
                    } catch (EXCEPTION $e) {
                        DB::rollback();
                        $message = "Faild to create variation " . $e->getMessage();
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

        $item = DepartmentModel::where(['id' => $id])->first();

        if ($item) {
            DepartmentModel::where('id', $id)->update(['status' => $request->status]);
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

        $category_data = DepartmentModel::where(['id' => $id])->first();

        if ($category_data) {
            $departments = DepartmentDoctor::where('department_id', $id)->count();
            if ($departments) {
                $message = "Department cannot be delete, because this department may have association with doctors.";
            } else {
                $category_data->delete();
                $message = "Department deleted successfully";
                $status = "1";
            }
        } else {
            $message = "Invalid Role data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
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

}
