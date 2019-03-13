<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Permission;
use Illuminate\Support\Facades\URL;

class PermissionAuth
{
    /**
     * 把这个中间件放入路由组，把需要的验证的路由
     * 放入这个中间组里
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * 获取当前路由地址，匹配权限
         */
        $route = URL::current();
        $arr = explode('/', $route);
        $action = end($arr);
        if (in_array($action, ['create', 'update', 'delete', 'show'])) {
            $action = $arr[count($arr) - 2];
        }
        $method = $request->getMethod();
        switch (strtoupper($method)) {
            case 'POST':
                $pre = 'create';
                break;
            case 'PUT':
            case 'PATCH':
                $pre = 'update';
                break;
            case 'DELETE':
                $pre = 'delete';
                break;
            default:
                $pre = 'show';
        }

        // 判断权限表中这条路由是否需要验证
        if ($permission = Permission::where('name', $pre.'-'.$action)->first()) {
            // 当前用户不拥有这个权限的名字
            if (!auth()->user()->hasPermission($permission->name)) {
                return response()->json(['code' => 4001, 'reason' => '未授权', 'data' => null])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
                // return response()->view('errors.403', ['status' => "权限不足，需要：{$permission->name}权限"]);
            }
        }

        return $next($request);
    }
}
