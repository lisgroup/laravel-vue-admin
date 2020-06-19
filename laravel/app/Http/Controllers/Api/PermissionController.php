<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\Store;
use App\Http\Requests\Permission\Update;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;


class PermissionController extends Controller
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
     * 权限列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Permission::paginate($this->perPage);
        return $this->out(200, $list);
    }

    /**
     * 显示创建权限表单
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get(); // 获取所有角色

        return $this->out(200, ['roles' => $roles, 'method' => 'create']);
    }

    /**
     * 保存新创建的权限
     *
     * @param Store $store
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $name = $request['name'];
        $permission = new Permission();
        $permission->name = $name;
        !is_null($request['route']) && $permission->route = $request['route'];

        if ($permission->save()) {
            if (!empty($request['checkedRoles'])) { // 如果选择了角色
                $roles = $request['checkedRoles'];
                foreach ($roles as $role) {
                    $r = Role::where('id', '=', $role)->firstOrFail(); // 将输入角色和数据库记录进行匹配

                    $permission = Permission::where('name', '=', $name)->first(); // 将输入权限与数据库记录进行匹配
                    $r->givePermissionTo($permission);
                }
            }

            return $this->out(200, ['data' => ['id' => $permission->id]]);
        } else {
            return $this->out(4000);
        }

    }

    /**
     * 显示给定权限
     *
     * @param  Permission $permission
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return $this->out(200, $permission);
    }

    /**
     * 显示编辑权限表单
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        return $this->out(200, $permission);
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
        if (env('APP_ENV') == 'demo' && $id < 50) {
            return $this->out(4000, [], 'demo account Do Not Operate');
        }
        $input = $request->all();
        is_null($input['route']) && $input['route'] = '';
        // $model = new Category();$model->save($input, ['id' => $id]);
        // 老版本更新操作如下，新版本先查询再更新
        // Category::where('id', $id)->update($input)
        $model = Permission::findOrFail($id);
        if ($model->update($input)) {
            return $this->out(200, ['data' => ['id' => $id]]);
        } else {
            return $this->out(4000);
        }
    }

    /**
     * 删除给定权限
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        // 让特定权限无法删除
        if ($permission->name == "Administer roles & permissions") {
            return $this->out(200, [], 'Cannot delete this Permission!');
        }

        if ($permission->delete()) {
            $data = ['msg' => '删除成功', 'errno' => 0];
        } else {
            $data = ['msg' => '删除失败', 'errno' => 2];
        }
        return $this->out(200, $data);

    }
}
