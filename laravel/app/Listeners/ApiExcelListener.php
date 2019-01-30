<?php

namespace App\Listeners;

use App\Events\ApiExcelEvent;
use App\Http\Repository\MultithreadingRepository;
use App\Models\ApiParam;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApiExcelListener implements ShouldQueue
{
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
     * @return void
     */
    public function handle(ApiExcelEvent $event)
    {
        // 获取事件中保存的数据
        $data = $event->getData();
        // 根据状态处理数据
        switch ($data['state']) {
            case 1:
                // 根据 upload 上传的数据批量测试数据
                $path = public_path($data['upload_url']);
                if ($data['state'] != 0 || !file_exists($path)) {

                    $multi = MultithreadingRepository::getInstent();
                    $multi->setParam($path);
                    // 获取 appkey 和 url
                    $param = ApiParam::find($data['api_excel_id']);
                    if ($param) {
                        $result = $multi->multiRequest($param['url'], $param['appkey']);
                        // TODO 记录 result 到 excel 中
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
