<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\Store;
use App\Http\Requests\Role\Update;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;


class RoleController extends Controller
{

    /**
     * @var int 默认分页条数
     */
    public $perPage = 10;

    public function __construct(Request $request)
    {
        $this->middleware(['auth:api', 'role']); // role 中间件让具备指定权限的用户才能访问该资源

        $perPage = intval($request->input('perPage'));
        $this->perPage = $perPage ?? 11;
    }

    /**
     * 角色列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Role::paginate($this->perPage);
        return $this->out(200, $list);
    }

    /**
     * 显示创建角色
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get(); // 获取所有权限

        return $this->out(200, ['permissions' => $permissions, 'method' => 'create']);
    }

    /**
     * 保存新创建的角色
     *
     * @param Store $store
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $name = $request['name'];
        $role = new Role();
        $role->name = $name;

        if ($role->save()) {

            $permissions = $request['checkedPermissions'];
            // 遍历选择的权限
            foreach ($permissions as $permission) {
                $p = Permission::where('id', '=', $permission)->firstOrFail();
                // 获取新创建的角色并分配权限
                $role = Role::where('name', '=', $name)->first();
                $role->givePermissionTo($p);
            }

            return $this->out(200, ['data' => ['id' => $role->id]]);
        } else {
            return $this->out(4000);
        }

    }

    /**
     * 显示给定角色
     *
     * @param  Role $role
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $permissions = Permission::all();
        return $this->out(200, ['permissions' => $permissions, 'checkedPermissions' => array_column($role->permissions->toArray(), 'id'), 'role' => $role]);
    }

    /**
     * 显示编辑角色
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return $this->out(200, ['permissions' => $permissions, 'role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     * 更新数据
     *
     * @param  Update $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        if (env('APP_ENV') == 'demo' && $id == 1) {
            return $this->out(4000, [], 'demo account Do Not Operate');
        }
        $role = Role::findOrFail($id); // 通过给定id获取角色
        // 验证 name 和 permission 字段
        // $this->validate($request, [
        //     'name' => 'required|max:10|unique:roles,name,'.$id,
        //     'permissions' => 'required',
        // ]);

        $input = $request->only(['name']);
        $permissions = $request['checkedPermissions'];
        if ($role->fill($input)->save()) {
            // 清除用户缓存角色权限记录
            $users = $role->users;
            foreach ($users as $user) {
                \Cache::forget('user_r_p_'.$user->id);
            }

            // $p_all = Permission::all();//获取所有权限
            // foreach ($p_all as $p) {
            //     $role->revokePermissionTo($p); // 将会移除与角色关联的所有权限
            // }
            // Fix: 一并移除了权限的错误
            /**
             * 多对多关联
             * 附加 / 移除 attach 和 detach
             * @link https://learnku.com/docs/laravel/5.5/eloquent-relationships/1333#a93d4c
             */
            $role->permissions()->detach(); // 仅移除 permission_role 表与角色关联的权限

            $role->permissions()->attach($permissions);

            // foreach ($permissions as $permission) {
            //     $p = Permission::where('id', '=', $permission)->first(); //从数据库中获取相应权限
            //     $p && $role->givePermissionTo($p);  // 分配权限到角色
            // }

            return $this->out(200, ['data' => ['id' => $id]]);
        } else {
            return $this->out(4000);
        }
    }

    /**
     * 删除给定角色
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->delete()) {
            $data = ['msg' => '删除成功', 'errno' => 0];
        } else {
            $data = ['msg' => '删除失败', 'errno' => 2];
        }
        return $this->out(200, $data);

    }
}
