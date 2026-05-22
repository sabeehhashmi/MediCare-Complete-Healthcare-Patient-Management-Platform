<?php

namespace App\Services;

use App\Models\RolePermissions;
use Illuminate\Http\Request;

class LoginService
{
    public function check(Request $request)
    {
        $request->session()->put('user_id', auth()->user()->id);
        if ($request->timezone) {
            $request->session()->put('user_timezone', $request->timezone);
        }

        try {
            $permission = RolePermissions::where(['user_role_id_fk' => auth()->user()->role_id])->get();

            if ($permission && $permission->count() > 0) {
                $permission = $permission->toArray();
                $user_permissions = array_column($permission, 'permissions', 'module_key');
                $request->session()->put('user_permissions', $user_permissions);
            } else {
                $request->session()->put('user_permissions', []);
            }
        } catch (\Throwable $th) {
            info('Error in getting permissions:: ');
            info($th->getMessage());
        }
    }
}
