<?php

namespace App\Http\Controllers\Api;

use App\Events\ApiExcelEvent;
use App\Events\ApiExcelSwooleEvent;
use App\Http\Repository\ApiRepository;
use App\Http\Repository\ExcelRepository;
use App\Http\Requests\ApiExcel\Store;
use App\Http\Requests\ApiExcel\Update;
use App\Models\ApiExcel;
use App\Http\Controllers\Controller;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiExcelController extends Controller
{
    /**
     * @var int 默认分页条数
     */
    public $perPage = 10;

    // private $request = null;

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
        $this->middleware(['auth:api', 'role'], ['except' => ['login']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = auth('api')->user()['id'];

        // 查询对应用户的上传数据
        $where = [];
        if ($user_id != 1) {
            $where = ['uid' => $user_id];
        }
        $perPage = intval($request->input('perPage'));
        $perPage = $perPage ?? 20;
        $list = ApiExcel::with('apiParam')->where($where)->orderBy('id', 'desc')->paginate($perPage);
        // 获取完成进度情况
        $list = ApiRepository::getInstent()->workProgress($list);

        $appUrl = env('APP_URL') ?? '';
        $collect = collect(['appUrl' => $appUrl]);
        $items = $collect->merge($list);

        return $this->out(200, $items);
    }

    /**
     * 文件上传处理
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        // 上传文件
        if ($request->isMethod('post')) {

            $file = $request->file('file');
            // 文件是否上传成功
            if ($file->isValid()) {

                // 获取文件相关信息
                // $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                if (!in_array($ext, ['xlsx', 'xls'])) {
                    return $this->out(4008);
                }
                $realPath = $file->getRealPath();   // 临时文件的绝对路径
                // $type = $file->getClientMimeType();     // application/wps-office.xlsx

                // 上传文件
                $filename = date('Ymd_His').'_'.uniqid().'.'.$ext;
                // 使用 public 配置，上传到 storage/app/public/ 目录
                $bool = Storage::disk('public')->put($filename, file_get_contents($realPath));
                if ($bool) {
                    return $this->out(200, ['url' => '/storage/'.$filename, 'ext' => $ext]);
                }
                return $this->out(4006);
            }
        }
        return $this->out(4000);
    }


    /**
     * 将任务放入队列处理
     *
     * @return \Illuminate\Http\Response
     */
    public function startTask(Request $request)
    {
        $data = $request->all();
        // $data = ['id' => 2, 'api_excel_id' => 1, 'appkey' => '123','upload_url' => '/storage/20190130_114747_5c511e632efe8.xlsx', 'state' => 0];
        // 1. 检测参数是否正常
        if (empty($data['id']) || !isset($data['state']) || empty($data['upload_url'])) {
            return $this->out(1006);
        }

        $path = public_path($data['upload_url']);
        if ($data['state'] != 0 || !file_exists($path)) {
            return $this->out(4007);
        }

        // 2. 查询数据库中任务真实状态
        $task = ApiExcel::find($data['id']);
        if (!$task || $task['state'] != 0) {
            return $this->out(4009);
        }

        // 3. 更新表字段状态
        $task->state = 1;

        // 4. 写入事件中处理
        $data = $task->toArray();

        // 如果是 cli 模式使用 laravels Task 异步事件
        if (PHP_SAPI == 'cli' && extension_loaded('swoole')) {
            // 触发事件--实例化并通过fire触发，此操作是异步的，触发后立即返回，由Task进程继续处理监听器中的handle逻辑
            // \Log::info(__CLASS__.': start task', $data);
            $event = new ApiExcelSwooleEvent($data);
            // $event = new TestEvent('event data');
            // $event->delay(10); // 延迟10秒触发
            // $event->setTries(2); // 出现异常时，累计尝试3次
            $success = Event::fire($event);
            // var_dump($success);// 判断是否触发成功
        } else {
            $success = event(new ApiExcelEvent($data));
        }
        $code = 200;
        if (!$success) {
            $code = 5000;
            $task->state = 0;
        }
        $task->save();

        return $this->out($code, [], '任务加入成功，请稍后下载处理结果');
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
     * @param Store $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        // 会出现 Unknown column 'guid' in 'field list' 不存在的字段入库报错问题
        // $rs = ApiExcel::insert($request->all());
        $input = $request->all();
        $input['uid'] = $input['uid'] ?? '';
        $model = new ApiExcel($input);
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
     * @param ApiExcel $apiExcel
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ApiExcel $apiExcel)
    {
        return $this->out(200, $apiExcel);
    }

    /**
     * Show the form for editing the specified resource.
     * 编辑展示数据
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ApiExcel::findOrFail($id);
        return $this->out(200, $data);
    }

    /**
     * Update the specified resource in storage.
     * 更新数据
     *
     * @param Update $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $input = $request->all();
        // $model = new ApiExcel();$model->save($input, ['id' => $id]);
        // 老版本更新操作如下，新版本先查询再更新
        // ApiExcel::where('id', $id)->update($input)
        $apiExcel = ApiExcel::findOrFail($id);
        if ($apiExcel->update($input)) {
            return $this->out(200, ['data' => ['id' => $id]]);
        } else {
            return $this->out(4000);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (ApiExcel::destroy($id)) {
            $data = ['msg' => '删除成功', 'errno' => 0];
        } else {
            $data = ['msg' => '删除失败', 'errno' => 2];
        }
        return $this->out(200, $data);
    }

    /**
     * 恢复操作
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        ApiExcel::onlyTrashed()->find($id)->restore();

        return $this->out(200);
    }

    /**
     * 彻底删除操作
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        ApiExcel::onlyTrashed()->find($id)->forceDelete();

        return $this->out(200);
    }

    /**
     * 下载已完成数据
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadLog(Request $request)
    {
        $api_excel_id = $request->input('id');
        // 判断用户有没有下载权限
        $user_id = auth('api')->user()['id'];
        // $user_id = 1;
        $failed_done_file = ExcelRepository::getInstent()->exportExcelLogs($api_excel_id, $user_id);
        if ($failed_done_file === false) {
            // 权限不足
            return $this->out(4001);
        } elseif ($failed_done_file === '') {
            // 无可下载内容
            return $this->out(4009, [], '下载失败，无可下载内容');
        }
        return $this->out(200, ['failed_done_file' => $failed_done_file]);
    }

}
