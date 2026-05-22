<?php

namespace App\Http\Controllers\Admin;

use Hash;
use Validator;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserPrivileges;
use App\Models\AdminDesignation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!get_user_permission('admin_users', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Admin Users";
        $datamain = User::with('user_role')
            ->where(['users.deleted' => '0', 'role' => '1'])
            ->where('users.id', '!=', '1')
            ->orderBy('users.id', 'desc')
            ->paginate();

        return view('admin.admin_users.list', compact('page_heading', 'datamain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!get_user_permission('admin_users', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Admin Users";
        $mode = "create";
        $id = "";
        $prefix = "";
        $name = "";
        $dial_code = "";
        $image = "";
        $active = "1";
        $roles  = Role::where(['status' => 1])->orderBy('role', 'asc')->get();


        return view("admin.admin_users.create", compact('page_heading', 'id', 'name', 'dial_code', 'active', 'prefix', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        if (!empty($request->password)) {
            $validator = Validator::make($request->all(), [
                'confirm_password' => 'required',
            ]);
        }
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();
            $check_exist = User::where('email', $request->email)->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {


                $ins = [
                    'name'       => $request->first_name." ".$request->last_name,
                    'email'      => $request->email,
                    'role'       => '1', //customer
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'role_id' => $request->designation,
                    'active'      => $request->active,
                    'verified' => '1',
                    'created_at'=>gmdate('Y-m-d H:i:s'),
                    'updated_at'=>gmdate('Y-m-d H:i:s')
                ];



                if ($request->password) {
                    $ins['password'] = bcrypt($request->password);
                }

                if ($request->file("image")) {
                    $response = image_upload($request, 'company', 'image');
                    if ($response['status']) {
                        $ins['user_image'] = $response['link'];
                    }
                }


                if ($request->id != "") {
                    $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                    $user = User::find($request->id);
                    $user->update($ins);


                    $status = "1";
                    $message = "Admin Users updated succesfully";
                } else {
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    $userid = User::create($ins)->id;

                    $status = "1";
                    $message = "Admin Users added successfully";
                }
            } else {
                $status = "0";
                $message = "Email should be unique";
                $errors['email'] = $request->email . " already added";
            }
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function save_privilege(Request $request)
    {
        $errors = [];
        $user_id = $request->id;
        $designation_id = User::select('designation_id')->where('id', $user_id)->first()->designation_id;

        $access_groups = json_encode($request->access_groups);

        UserPrivileges::updateOrCreate(
            [
                'user_id'   => $user_id,
                "designation_id" => $designation_id
            ],
            [
                "user_id" => $user_id,
                "designation_id" => $designation_id,
                "privileges" => $access_groups,
                "status" => 1
            ]
        );
        $status = 1;
        $message = "Privileges updated successfully";
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }
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
    public function edit($id)
    {
        if (!get_user_permission('admin_users', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Edit Admin Users";
        $datamain = User::find($id);
        $roles  = Role::where(['status' => 1])->orderBy('role', 'asc')->get();

        if ($datamain) {
            return view("admin.admin_users.create", compact('page_heading', 'datamain', 'id', 'roles'));
        } else {
            abort(404);
        }
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
    public function update_permission($id)
    {
        if (!get_user_permission('admin_users', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Admin User Permission";
        $mode = "create";
        $prefix = "";
        $name = "";
        $dial_code = "";
        $image = "";
        $active = "1";
        $designation  = AdminDesignation::where(['is_deletd' => 0])->orderBy('name', 'asc')->get();


        return view("admin.admin_users.permission", compact('page_heading', 'id', 'name', 'dial_code', 'active', 'prefix', 'designation'));
    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";

        if (User::where('id', $request->id)->update(['active' => $request->status, 'email_verified_at' => now()])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    public function verify(Request $request)
    {
        $status = "0";
        $message = "";
        if (User::where('id', $request->id)->update(['verified' => $request->status])) {
            $status = "1";
            $msg = "Successfully verified";
            if (!$request->status) {
                $msg = "Successfully updated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $datatb = User::find($id);
        if ($datatb) {
            $datatb->deleted = 1;
            $datatb->active = 0;
            $datatb->save();
            $status = "1";
            $message = "Admin Users removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }

    public function access_restricted(){
        $page_heading = "Access Restricted";
        return view('admin.access_restricted',compact('page_heading'));
    }

}
