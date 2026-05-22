<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    //
    public function index()
    {
        if (!get_user_permission('services', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Services";
        $list = Services::orderBy('id','desc')->paginate(10);
        $mode='list';
        return view('admin.services.list', compact('mode', 'page_heading', 'list'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('services', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = 'Services';
        $mode = "Create";
        $title_en  = '';
        $title_ar  = '';
        $status = '';
        

        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $role = Services::find($id);
            $title_en = $role->title;
            $status = $role->status;
            $title_ar = $role->title_ar;
        }
        return view('admin.services.create', compact('mode', 'page_heading', 'id', 'status', 'title_en', 'title_ar'));
    }

    public function submit(REQUEST $request)
    {

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('admin.services.list');
        $rules = [
            'title_en' => 'required'
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
            $check      = Services::whereRaw('Lower(title) = ?', [strtolower($title_en)])->where('id', '!=', $id)->get();

            if ($check->count() > 0) {
                $status = "0";
                $message = "Services Already Addded";
                $errors['title_en'] = 'Services Already Added';
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $role   = Services::find($id);
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();
                        
                        DB::commit();
                        $status = "1";
                        $message = "Service updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create service " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $role   = new Services();
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->created_by = Auth::user()->id??0;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();
                        $role_id            = $role->id;

                        DB::commit();
                        $status = "1";
                        $message = "Service Added Successfully";
                    } catch (EXCEPTION $e) {
                        DB::rollback();
                        $message = "Faild to create service " . $e->getMessage();
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

        $item = Services::where(['id' => $id])->get();

        if ($item->count() > 0) {

            Services::where('id', '=', $id)->update(['status' => $request->status]);
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

        $category_data = Services::where(['id' => $id])->first();

        if ($category_data) {
            Services::where(['id' => $id])->delete();
            $message = "Service deleted successfully";
            $status = "1";
        } else {
            $message = "Invalid type data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }
}
