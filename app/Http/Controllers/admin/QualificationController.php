<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorQualifications;
use Illuminate\Http\Request;
use App\Models\Qualifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class QualificationController extends Controller
{
    //
    public function index()
    {
        if (!get_user_permission('qualifications', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Qualifications";
        $list = Qualifications::orderBy('id','desc')->get();
        $mode='list';
        return view('admin.qualifications.list', compact('mode', 'page_heading', 'list'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('qualifications', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = 'Qualifications';
        $mode = "Create";
        $title_en  = '';
        $title_ar  = '';
        $status = '';


        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $role = Qualifications::find($id);
            $title_en = $role->title;
            $status = $role->status;
            $title_ar = $role->title_ar;
        }
        return view('admin.qualifications.create', compact('mode', 'page_heading', 'id', 'status', 'title_en', 'title_ar'));
    }

    public function submit(REQUEST $request)
    {

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('admin.qualifications.list');
        $rules = [
            'title_en' => [
                'required', // or any other rules you need
                'string',
                'max:255',
                'min:3',
                Rule::unique('qualifications', 'title')
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
            $check      = Qualifications::whereRaw('Lower(title) = ?', [strtolower($title_en)])->where('id', '!=', $id)->get();

            if ($check->count() > 0) {
                $status = "0";
                $message = "Qualification Already Addded";
                $errors['title_en'] = 'Qualification Already Added';
                if($request->json_action){
                    return response()->json([
                        'success' => true,
                        'message' => "Qualification Already Exist",
                        'id' => 0,
                        'name' => ''
                    ]);
                }
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $role   = Qualifications::find($id);
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();

                        DB::commit();
                        $status = "1";
                        $message = "Qualification updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create qualification " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $role   = new Qualifications();
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->created_by = Auth::user()->id??0;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();
                        $role_id            = $role->id;

                        DB::commit();
                        $status = "1";
                        $message = "Qualification Added Successfully";
                        if($request->json_action){
                            return response()->json([
                                'success' => true,
                                'message' => "Qualification added successfully",
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

        $item = Qualifications::where(['id' => $id])->get();

        if ($item->count() > 0) {

            Qualifications::where('id', '=', $id)->update(['status' => $request->status]);
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

        $category_data = Qualifications::where(['id' => $id])->first();

        if ($category_data) {
            $qualifications = DoctorQualifications::where('qualification_id', $id)->count();
            if ($qualifications) {
                $message = "Qualification cannot be delete, because this qualification may have association with doctors.";
            } else {
                $category_data->delete();
                $message = "Qualification deleted successfully";
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
