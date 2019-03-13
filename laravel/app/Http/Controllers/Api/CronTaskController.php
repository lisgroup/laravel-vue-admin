<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\TaskRepository;
use App\Http\Requests\StoreCronTask;
use App\Models\CronTask;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CronTaskController extends Controller
{
    /**
     * @var int 默认分页条数
     */
    public $perPage = 11;

    /**
     * Create a new AuthController instance.
     * 要求附带email和password（数据来源users表）
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // 这里额外注意了：官方文档样例中只除外了『login』
        // 这样的结果是，token 只能在有效期以内进行刷新，过期无法刷新
        // 如果把 refresh 也放进去，token 即使过期但仍在刷新期以内也可刷新
        // 不过刷新一次作废
        $this->middleware(['auth:api', 'role']);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
        $perPage = intval($request->input('perPage'));
        $this->perPage = $perPage ?? 11;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = CronTask::paginate($this->perPage);
        return $this->out(200, $list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->out(200, ['method' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     * 新增入库操作
     *
     * @param  StoreCronTask $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCronTask $request)
    {
        // 会出现 Unknown column 'guid' in 'field list' 不存在的字段入库报错问题
        // $rs = CronTask::insert($request->all());
        $input = $request->all();
        $input['is_task'] = $input['is_task'] ? 1 : 0;
        $model = new CronTask($input);
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = CronTask::findOrFail($id);
        return $this->out(200, $data);
    }

    /**
     * Show the form for editing the specified resource.
     * 编辑展示数据
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = CronTask::findOrFail($id);
        return $this->out(200, $data);
    }

    /**
     * Update the specified resource in storage.
     * 更新数据
     *
     * @param  StoreCronTask $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCronTask $request, $id)
    {
        $input = $request->only('cid', 'LineGuid', 'LineInfo', 'is_task', 'start_at', 'end_at');
        // var_dump($input);exit();
        $input['is_task'] = $input['is_task'] ? 1 : 0;
        // $model = new CronTask();$model->save($input, ['id' => $id]);
        // 老版本更新操作如下，新版本先查询再更新
        // CronTask::where('id', $id)->update($input)
        $task = CronTask::findOrFail($id);
        if ($task->update($input)) {
            return $this->out(200, ['data' => ['id' => $id]]);
        } else {
            return $this->out(4000);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        // $rs = CronTask::where('id', $id)->delete()
        if (CronTask::findOrFail($id)->delete()) {
            $data = ['msg' => '删除成功', 'errno' => 0];
        } else {
            $data = ['msg' => '删除失败', 'errno' => 2];
        }
        return $this->out(200, $data);
    }

    /**
     * 展示所有任务 不分页
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        return $this->out(200, CronTask::all());
    }

    /**
     * 全文索引查询 bus_lines 表是数据，写入任务表 cron_task 的操作
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postCrontask(Request $request)
    {
        $input = $request->all();
        $result = TaskRepository::getInstent()->saveSearchCronTask($input);

        return $this->out($result['code'], $result['data']);
    }

}
