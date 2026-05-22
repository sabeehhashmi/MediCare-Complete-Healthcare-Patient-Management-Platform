<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorIntrests;
use App\Models\DoctorSpecialities;
use Illuminate\Http\Request;
use App\Models\Direction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DirectionsController extends Controller
{
    //
    public function index()
    {
        if (!get_user_permission('special_intrests', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Directions";
        $list = Direction::orderBy('id','desc')->get();
        $mode='list';
        return view('admin.directions.list', compact('mode', 'page_heading', 'list'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('special_intrests', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = 'Directions';
        $mode = "Create";
        $title_en  = '';
        $title_ar  = '';
        $title_ban  = '';
        $title_ban  = '';
        $status = '';


        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $role = Direction::find($id);
            $title_en = $role->title;
            $status = $role->status;
            $title_ar = $role->title_ar;
            $title_ban = $role->title_ban;
        }
        return view('admin.directions.create', compact('mode', 'page_heading', 'id', 'status', 'title_en', 'title_ar','title_ban'));
    }

    public function submit(REQUEST $request)
    {

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('admin.directions.list');
        $rules = [
            'title_en' => [
                'required',
                'min:3',
                Rule::unique('directions', 'title')
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
            $title_ban = $request->title_ban;
            $title_en  = $request->title_en;
            $status = $request->status;
            $id         = $request->id;
            $check      = Direction::whereRaw('Lower(title) = ?', [strtolower($title_en)])->where('id', '!=', $id)->get();

            if ($check->count() > 0) {
                $status = "0";
                $message = "Directions Already Addded";
                $errors['title_en'] = 'Directions Already Added';
                if($request->json_action){
                    return response()->json([
                        'success' => true,
                        'message' => "Directions Already Added successfully",
                        'id' => '',
                        'name' => ''
                    ]);
                }
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $role   = Direction::find($id);
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->title_ban  = $title_ban;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();

                        DB::commit();
                        $status = "1";
                        $message = "Directions updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create special intrest " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $role   = new Direction();
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->title_ban  = $title_ban;
                        $role->created_by = Auth::user()->id??0;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();
                        $role_id            = $role->id;

                        DB::commit();
                        $status = "1";
                        $message = "Directions Added Successfully";
                        if($request->json_action){
                            return response()->json([
                                'success' => true,
                                'message' => "Directions added successfully",
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

        $item = Direction::where(['id' => $id])->get();

        if ($item->count() > 0) {

            Direction::where('id', '=', $id)->update(['status' => $request->status]);
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

        $category_data = Direction::where(['id' => $id])->first();

        if ($category_data) {
            $interests = DoctorIntrests::where('special_intrest_id', $id)->count();
            if ($interests) {
                $message = "Special Interest cannot be delete, because this specail interest may have association with doctors.";
            } else {
                $category_data->delete();
                $message = "Special Interest deleted successfully";
                $status = "1";
            }
        } else {
            $message = "Invalid type data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }
}
