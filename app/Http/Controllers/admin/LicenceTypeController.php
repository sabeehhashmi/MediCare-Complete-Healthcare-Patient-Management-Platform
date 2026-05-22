<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LicenceType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;

class LicenceTypeController extends Controller
{
    //
    public function index()
    {
        if (!get_user_permission('licencetype', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "LicenceTypes";
        $list = LicenceType::orderBy('id','desc')->paginate(10);
        $mode='list';
        return view('admin.licencetype.list', compact('mode', 'page_heading', 'list'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('licencetype', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = 'LicenceTypes';
        $mode = "Create";
        $title_en  = '';
        $title_ar  = '';
        $status = '';
        

        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $role = LicenceType::find($id);
            $title_en = $role->title;
            $status = $role->status;
            $title_ar = $role->title_ar;
        }
        return view('admin.licencetype.create', compact('mode', 'page_heading', 'id', 'status', 'title_en', 'title_ar'));
    }

    public function submit(REQUEST $request)
    {

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('admin.licencetype.list');
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
            $check      = LicenceType::whereRaw('Lower(title) = ?', [strtolower($title_en)])->where('id', '!=', $id)->get();

            if ($check->count() > 0) {
                $status = "0";
                $message = "Licencetype Already Addded";
                $errors['title_en'] = 'Licencetype Already Added';
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $role   = LicenceType::find($id);
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();
                        
                        DB::commit();
                        $status = "1";
                        $message = "LicenceType updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create licencetype " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $role   = new LicenceType();
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->created_by = Auth::user()->id??0;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();
                        $role_id            = $role->id;

                        DB::commit();
                        $status = "1";
                        $message = "LicenceType Added Successfully";
                    } catch (EXCEPTION $e) {
                        DB::rollback();
                        $message = "Faild to create licencetype " . $e->getMessage();
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

        $item = LicenceType::where(['id' => $id])->get();

        if ($item->count() > 0) {

            LicenceType::where('id', '=', $id)->update(['status' => $request->status]);
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

        $category_data = LicenceType::where(['id' => $id])->first();

        if ($category_data) {
            LicenceType::where(['id' => $id])->delete();
            $message = "Licencetype deleted successfully";
            $status = "1";
        } else {
            $message = "Invalid type data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }
}
