<?php

namespace App\Listeners;

use App\Events\ApiExcelEvent;
use App\Http\Repository\MultithreadingRepository;
use App\Models\ApiExcel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ApiExcelListener implements ShouldQueue
{
    /**
     * 任务运行的超时时间。
     *
     * @var int
     */
    public $timeout = 3600;

    /**
     * 失败重试次数
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ApiExcelEvent $event
     * @throws \Exception
     * @return void
     */
    public function handle(ApiExcelEvent $event)
    {
        // 获取事件中保存的数据
        // $data = ['id' => 6, 'state' => 1, 'upload_url' => '/storage/20190318_103542_5c8f03fe14d89.xlsx'];
        $data = $event->getData();
        // 根据状态处理数据
        switch ($data['state']) {
            case 1:
                // 根据 upload 上传的数据批量测试数据
                $path = public_path($data['upload_url']);
                if ($data['state'] == 1 && file_exists($path)) {
                    // 获取 appkey 和 url
                    $apiExcel = ApiExcel::find($data['id']);
                    $param = $apiExcel->apiParam()->first();
                    if ($param) {
                        $multi = MultithreadingRepository::getInstent();
                        $multi->setApiExcelId($data['id']);
                        $multi->setParam($path, ['concurrent' => $apiExcel->concurrent]);
                        $is_open = $apiExcel->concurrent > 1;
                        $result = $multi->newMultiRequest($param->url, $apiExcel->appkey, $is_open);

                        ksort($result);

                        // 正式上线后注释下一行
                        Log::info('ID-'.$data['id'].'-result: ', $result);
                        if (!$result) {
                            // 更新任务失败
                            $apiExcel->state = 5;
                            $apiExcel->save();
                            throw new \Exception(date('Y-m-d H:i:s').' 任务失败： 第三方请求错误～！'.$param['url']);
                        }

                        /************************* 2. 写入 Excel 文件 ******************************/
                        $fileName = $multi->saveExcel($param, $result);

                        if (!$fileName) {
                            throw new \Exception(date('Y-m-d H:i:s').' 任务失败： Writer\Exception～！');
                        }

                        // 更新任务状态
                        $apiExcel->state = 2;
                        $apiExcel->finish_url = $fileName;
                        $apiExcel->save();
                    }
                }
                break;
            default:
                break;
        }

    }

    /**
     * 处理任务失败
     *
     * @param  \App\Events\ApiExcelEvent $event
     * @param  \Exception $exception
     * @return void
     */
    public function failed(ApiExcelEvent $event, $exception)
    {
        //
    }
}
