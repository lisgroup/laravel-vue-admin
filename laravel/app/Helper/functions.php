<?php
/**
 * 公共函数库
 * User: lisgroup
 * Date: 2019-1-19 15:33
 */

/**
 * @param $varName
 * @return string
 */
if (!function_exists('show')) {
    function show($varName)
    {
        switch ($result = get_cfg_var($varName)) {
            case 0:
                return 'no';
                break;
            case 1:
                return 'yes';
                break;
            default:
                return $result;
                break;
        }
    }
}

if (!function_exists('cacheUserRolesAndPermissions')) {
    function cacheUserRolesAndPermissions($user_id, $flash = false)
    {
        if ($flash) {
            Cache::forget('user_r_p_'.$user_id);
            return cacheUserRolesAndPermissions($user_id, false);
        } else {
            return Cache::remember('user_r_p_'.$user_id, 60, function() use ($user_id) {
                $res = collect(DB::table('role_user')
                    ->where('role_user.user_id', $user_id)
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->join('permission_role', 'permission_role.role_id', '=', 'role_user.role_id')
                    ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                    ->select(['permissions.name as p_name', 'roles.name as r_name'])
                    ->get());
                $roles = $res->pluck('r_name')->unique();
                $permissions = $res->pluck('p_name')->unique();
                $vals = [
                    'roles' => $roles->values()->all(),
                    'permissions' => $permissions->values()->all()
                ];
                return $vals;
            });
        }
    }
}
