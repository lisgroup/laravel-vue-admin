<?php

namespace App\Http\Controllers\Api;

use App\Events\ApiExcelEvent;
use App\Http\Repository\MultithreadingRepository;
use App\Http\Requests\ApiExcel\Store;
use App\Http\Requests\ApiExcel\Update;
use App\Models\ApiExcel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ApiExcelController extends Controller
{
    /**
     * @var int 默认分页条数
     */
    public $perPage = 10;

    private $request = null;

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
        // $this->middleware('auth:api', ['except' => ['login', 'show']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回

        $perPage = intval($request->input('perPage'));
        $this->perPage = $perPage ?? 11;

        $this->request || $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = ApiExcel::orderBy('id', 'desc')->paginate($this->perPage);
        return $this->out(200, $list);
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

            $file = $this->request->file('file');
            // 文件是否上传成功
            if ($file->isValid()) {

                // 获取文件相关信息
                // $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
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
    public function startTask()
    {
        $multi = MultithreadingRepository::getInstent();
        $multi->setParam(public_path('/storage/20190130_220729_5c51afa15e70f.xlsx'));
        $result = $multi->multiRequest('http://118.25.87.12/token/php/index.php/hello/123', '111');

        ksort($result);

        /************************* 2. 写入 Excel 文件 ******************************/
        // 首先创建一个新的对象  PHPExcel object
        $objPHPExcel = new Spreadsheet();

        /** 以下是一些设置 ，什么作者、标题信息 */
        $objPHPExcel->getProperties()->setCreator('lisgroup')
            ->setLastModifiedBy('lisgroup')
            ->setTitle('EXCEL 导出')
            ->setSubject('EXCEL 导出')
            ->setDescription('导出数据')
            ->setKeywords("excel php")
            ->setCategory("result file");
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/

        // Excel 的第 A 列，uid 是你查出数组的键值，下面以此类推
        try {
            $setActive = $objPHPExcel->setActiveSheetIndex(0);
            // 1. 第一行应该是 param 参数
            $keys = array_keys($result[0]['param']);
            $i = 'A';
            foreach ($keys as $num => $key) {
                $setActive->setCellValue($i.'1', "\t".$key);
                $i++;
            }
            $setActive->setCellValue($i.'1', 'res');

            // 2. 第二行开始循环数据
            foreach ($result as $key => $value) {
                $array = json_decode($value['result'], true);
                if ($array['error_code'] == 0) {
                    $message = $array['result']['res'] == 1 ? '一致' : '不一致';
                } else {
                    $message = $array['reason'];
                }
                // 2.1 第二行位置
                $number = $key + 2;

                $i = 'A';
                foreach ($keys as $num => $key) {
                    $setActive->setCellValue($i.$number, "\t".$value['param'][$key]);
                    $i++;
                }
                $setActive->setCellValue($i.$number, $message);
            }

            //得到当前活动的表,注意下文教程中会经常用到$objActSheet
            $objActSheet = $objPHPExcel->getActiveSheet();
            // 位置bbb  *为下文代码位置提供锚
            // 给当前活动的表设置名称
            $objActSheet->setTitle('Simple');
            // 代码还没有结束，可以复制下面的代码来决定我们将要做什么

            // 1,直接生成一个文件
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
            $path = storage_path('app/public');
            // is_dir($path) || mkdir($path, 777, true);
            $objWriter->save($path.'/out-208-'.date('mdHis').'.xlsx');


            $data = $this->request->all();
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
                return $this->out(4007);
            }
            $task->state = 1;
            // 3. 更新表字段状态
            $task->save();

            // 4. 写入事件中处理
            $task = $task->toArray();
            event(new ApiExcelEvent($task));

            return $this->out(200, [], '任务加入成功，请稍后下载处理结果');
        } catch (\PhpOffice\PhpSpreadsheet\Exception|\PhpOffice\PhpSpreadsheet\Writer\Exception $exception) {
            return $this->out(5000);
        }
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
     * @param  int $id
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
     * @param  Update $request
     * @param  int $id
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        if (ApiExcel::findOrFail($id)->delete()) {
            $data = ['msg' => '删除成功', 'errno' => 0];
        } else {
            $data = ['msg' => '删除失败', 'errno' => 2];
        }
        return $this->out(200, $data);
    }
}
