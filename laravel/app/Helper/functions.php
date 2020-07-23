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
            return Cache::remember('user_r_p_'.$user_id, 3600 * 24 * 30, function() use ($user_id) {
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

if (!function_exists('cacheTotalExcel')) {
    function cacheTotalExcel($api_excel_id, $file_path, $flash = false)
    {
        if ($flash) {
            Cache::forget('api_excel_total_'.$api_excel_id);
            return cacheTotalExcel($api_excel_id, false);
        } else {
            return Cache::remember('api_excel_total_'.$api_excel_id, 60, function() use ($api_excel_id, $file_path) {
                $excel = \App\Http\Repository\MultithreadingRepository::getInstent();
                $data = $excel->getExcelData($file_path);
                return isset($data['data']) ? count($data['data']) : 0;
            });
        }
    }
}

/**
 * 判断是否是中文名称
 *
 * @param $realname
 * @return int
 */
if (!function_exists('isName')) {
    function isName($name)
    {
        $name = str_replace('.', '', $name);
        $name = str_replace('·', '', $name);
        return preg_match('/^[\x{2E80}-\x{FE4F}]{2,16}$/u', $name);
    }
}

/**
 * 下划线转驼峰
 * @param $str
 * @return string|string[]|null
 */
function convertUnderline($str)
{
    $str = preg_replace_callback('/([-_]+([a-z]{1}))/i', function($matches) {
        return strtoupper($matches[2]);
    }, $str);
    return $str;
}

/**
 * 驼峰转下划线
 * @param $str
 * @return string|string[]|null
 */
function humpToLine($str)
{
    $str = preg_replace_callback('/([A-Z]{1})/', function($matches) {
        return '_'.strtolower($matches[0]);
    }, $str);
    return $str;
}
