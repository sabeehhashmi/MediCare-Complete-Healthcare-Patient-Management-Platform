<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorIntrests;
use Illuminate\Http\Request;
use App\Models\WebsiteService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WebsiteServicesController extends Controller
{
    //
    public function index()
    {
        if (!get_user_permission('special_intrests', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Website Service";
        $list = WebsiteService::orderBy('id','desc')->get();
        $mode='list';
        return view('admin.website_services.list', compact('mode', 'page_heading', 'list'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('special_intrests', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = 'Website Service';
        $mode = "Create";
        $title  = '';
        $desc  = '';
        $status = '';
        $icon = '';
        $icon_type = 'fontawesome';


        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $role = WebsiteService::find($id);
            $title = $role->title;
            $status = $role->status;
            $desc = $role->desc;
            $icon = $role->icon;
            $icon_type = $role->icon_type;
        }
        return view('admin.website_services.create', compact('mode', 'page_heading', 'id', 'status', 'title', 'desc', 'icon', 'icon_type'));
    }

    public function submit(Request $request)
    {

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('admin.website_services.list');
        $rules = [
            'title' => [
                'required',
                'min:2',
                Rule::unique('website_services', 'title')->ignore($request->id)
            ],
            'desc' => [
                'required',
                'string'
            ],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $title = $request->title;
            $desc = $request->desc;
            $status = $request->status;
            $id         = $request->id;
            $icon_type = $request->icon_type;
            $check      = WebsiteService::whereRaw('Lower(title) = ?', [strtolower($title)])->where('id', '!=', $id)->get();

            if ($check->count() > 0) {
                $status = "0";
                $message = "Website Service Already Addded";
                $errors['title'] = 'Website Service Already Added';
                if($request->json_action){
                    return response()->json([
                        'success' => true,
                        'message' => "Website Service Already Added successfully",
                        'id' => '',
                        'name' => ''
                    ]);
                }
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $role   = WebsiteService::find($id);
                        $role->title    = $title;
                        $role->status  = $request->status;
                        $role->desc  = $desc;
                        $role->icon_type = $icon_type;
                    
                        // Handle fontawesome icon
                        if ($icon_type == 'fontawesome') {
                            $role->icon = $request->icon_fontawesome;
                        }
                        
                        // Handle image upload
                        if ($icon_type == 'image' && $request->hasFile('icon_image')) {
                            $file = $request->file('icon_image');
                            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                            $file->storeAs(config('global.website_services_dir', 'website_services'), $file_name, config('global.upload_bucket'));
                            $role->icon = $file_name;
                        }
                        $role->save();

                        DB::commit();
                        $status = "1";
                        $message = "Website Service updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create special intrest " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $role   = new WebsiteService();
                        $role->title    = $title;
                        $role->status  = $request->status;
                        $role->desc  = $desc;

                         $role->icon_type = $icon_type;
                    
                        // Handle fontawesome icon
                        if ($icon_type == 'fontawesome') {
                            $role->icon = $request->icon_fontawesome;
                        }
                        
                        // Handle image upload
                        if ($icon_type == 'image' && $request->hasFile('icon_image')) {
                            $file = $request->file('icon_image');
                            $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                            $file->storeAs(config('global.website_services_dir', 'website_services'), $file_name, config('global.upload_bucket'));
                            $role->icon = $file_name;
                        }
                        $role->save();

                        DB::commit();
                        $status = "1";
                        $message = "Website Service Added Successfully";
                        if($request->json_action){
                            return response()->json([
                                'success' => true,
                                'message' => "Website Service added successfully",
                                'id' => $role->id,
                                'name' => $request->title
                            ]);
                        }
                    } catch (\Exception $e) {
                        DB::rollback();
                        $message = "Faild to website service " . $e->getMessage();
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

        $item = WebsiteService::where(['id' => $id])->get();

        if ($item->count() > 0) {

            WebsiteService::where('id', '=', $id)->update(['status' => $request->status]);
            $status = "1";
            $message = "Status changed successfully";
        } else {
            $message = "Faild to change status";
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function delete(Request $request, $id)
    {

        $id = decrypt($id);

        $category_data = WebsiteService::where(['id' => $id])->first();

        $category_data->delete();
        $message = "Website service deleted successfully";
        $status = "1";

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }
}
