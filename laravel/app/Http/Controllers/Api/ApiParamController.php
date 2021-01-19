<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ApiParam\Store;
use App\Http\Requests\ApiParam\Update;
use App\Models\ApiParam;
use App\Http\Controllers\Controller;

class ApiParamController extends Controller
{
    /**
     * @var int 默认分页条数
     */
    public $perPage = 10;

    /**
     * Create a new AuthController instance.
     * 要求附带email和password（数据来源users表）
     *
     * @return void
     */
    public function __construct()
    {
        // 这里额外注意了：官方文档样例中只除外了『login』
        // 这样的结果是，token 只能在有效期以内进行刷新，过期无法刷新
        // 如果把 refresh 也放进去，token 即使过期但仍在刷新期以内也可刷新
        // 不过刷新一次作废
        $this->middleware(['auth:api', 'role']);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = $this->getPerPage();
        // 1. 增加customer用户，并增加排序操作
        $list = ApiParam::orderBy('sort_index', 'desc')->orderBy('id', 'desc')->where('state', 1)->paginate($perPage);
        return $this->out(200, $list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return $this->out(200, ['method' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     * 新增入库操作
     *
     * @param Store $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Store $request)
    {
        // 会出现 Unknown column 'guid' in 'field list' 不存在的字段入库报错问题
        // $rs = ApiParam::insert($request->all());
        $input = $request->all();
        $input['website'] = $input['website'] ?? '';
        $model = new ApiParam($input);
        if ($model->save()) {
            return $this->out(200, ['data' => ['id' => $model->id]]);
        } else {
            return $this->out(4000);
        }

    }

    /**
     * Display the specified resource.
     * 展示某个详情数据
     *
     * @param ApiParam $apiParam
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ApiParam $apiParam)
    {
        return $this->out(200, $apiParam);
    }

    /**
     * Show the form for editing the specified resource.
     * 编辑展示数据
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $data = ApiParam::findOrFail($id);
        return $this->out(200, $data);
    }

    /**
     * Update the specified resource in storage.
     * 更新数据
     *
     * @param  Update $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Update $request, $id)
    {
        $input = $request->all();
        // $model = new ApiParam();$model->save($input, ['id' => $id]);
        // 老版本更新操作如下，新版本先查询再更新
        // ApiParam::where('id', $id)->update($input)
        $apiParam = ApiParam::findOrFail($id);
        if ($apiParam->update($input)) {
            return $this->out(200, ['data' => ['id' => $id]]);
        } else {
            return $this->out(4000);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        if (ApiParam::findOrFail($id)->delete()) {
            $data = ['msg' => '删除成功', 'errno' => 0];
        } else {
            $data = ['msg' => '删除失败', 'errno' => 2];
        }
        return $this->out(200, $data);
    }
}
