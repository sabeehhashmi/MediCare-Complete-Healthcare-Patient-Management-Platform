<?php

namespace App\Http\Controllers\admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\RolePermissions;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;

class UserRoleController extends Controller
{
    public function index()
    {
        if (!get_user_permission('user_roles', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "User Roles";
        $mode = "List";

        $query = Role::where('id', '!=', 1)->where('is_admin_role', '=', 1);

        $roles = $query->paginate(10);

        return view('admin.roles.list', compact('mode', 'page_heading', 'roles'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('user_roles', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = 'Role';
        $mode = "Create";
        $role_name  = '';
        $is_admin_role  = '';
        $status = '';
        $permissions = [];

        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $role = Role::find($id);
            $role_name = $role->role;
            $status = $role->status;
            $is_admin_role = $role->is_admin_role;
            $permission = RolePermissions::where('user_role_id_fk', '=', $id)->get()->toArray();
            $permissions = array_column($permission, 'permissions', 'module_key');
        }
        $site_modules = config('crud.site_modules');
        $operations   = config('crud.operations');
        return view('admin.roles.create', compact('mode', 'page_heading', 'id', 'status', 'role_name', 'is_admin_role', 'permissions', 'operations', 'site_modules'));
    }

    public function submit(REQUEST $request)
    {

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('admin.user_roles.list');
        $rules = [
            'role' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $permission = $request->permission;
            $role_name  = $request->role;
            // $status = $request->status;
            $is_admin_role = (int) $request->is_admin_role;
            $id         = $request->id;
            $check      = Role::whereRaw('Lower(role) = ?', [strtolower($role_name)])->where('id', '!=', $id)->get();

            if ($check->count() > 0) {
                $message = "Role Already Addded";
                $errors['role'] = 'Role Already Added';
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $role   = Role::find($id);
                        $role->role    = $role_name;
                        $role->status  = $request->status;
                        $role->is_admin_role  = $is_admin_role;
                        $role->save();
                        $role_id            = $role->id;

                        RolePermissions::where(['user_role_id_fk' => $role_id])->delete();
                        $module_permissions = [];
                        $site_modules = config('crud.site_modules');
                        foreach ($site_modules as $moduleKey => $moduleName) {
                            if (isset($permission[$moduleKey])) {
                                $module_permissions[] = [
                                    'module_key'        => $moduleKey,
                                    'user_role_id_fk'   => $role_id,
                                    'permissions'       => json_encode($permission[$moduleKey] ?? [])
                                ];
                            }
                        }
                        if (!empty($module_permissions)) {
                            RolePermissions::insert($module_permissions);
                        }
                        DB::commit();
                        $status = "1";
                        $message = "Role Permissions updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create variation " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $role   = new Role();
                        $role->role    = $role_name;
                        $role->status  = $request->status;
                        $role->is_admin_role  = $is_admin_role;
                        $role->save();
                        $role_id            = $role->id;

                        $module_permissions = [];
                        $site_modules = config('crud.site_modules');
                        foreach ($site_modules as $moduleKey => $moduleName) {
                            if (isset($permission[$moduleKey])) {
                                $module_permissions[] = [
                                    'module_key'        => $moduleKey,
                                    'user_role_id_fk'   => $role_id,
                                    'permissions'       => json_encode($permission[$moduleKey] ?? [])
                                ];
                            }
                        }
                        if (!empty($module_permissions)) {
                            RolePermissions::insert($module_permissions);
                        }
                        DB::commit();
                        $status = "1";
                        $message = "Role Permissions Added Successfully";
                    } catch (EXCEPTION $e) {
                        DB::rollback();
                        $message = "Faild to create variation " . $e->getMessage();
                    }
                }
            }
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function change_status(REQUEST $request, $id)
    {
        $status = "0";
        $message = "";
        $o_data  = [];
        $errors = [];

        $id = decrypt($id);

        $item = Role::where(['id' => $id])->get();

        if ($item->count() > 0) {

            Role::where('id', '=', $id)->update(['status' => $request->status]);
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

        $category_data = Role::where(['id' => $id])->first();

        if ($category_data) {
            Role::where(['id' => $id])->delete();
            $message = "Role deleted successfully";
            $status = "1";
        } else {
            $message = "Invalid Role data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }
}
