<?php

/**
 * 创建监听器类
 * User: lisgroup
 * Date: 20-06-05
 * Time: 下午01:51
 */

namespace App\Listeners;


use App\Http\Repository\MultithreadingRepository;
use App\Models\ApiExcel;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Hhxsv5\LaravelS\Swoole\Task\Listener;

class ApiExcelSwooleListener extends Listener
{
    // 声明没有参数的构造函数
    public function __construct()
    {
    }

    public function handle(Event $event)
    {
        \Log::info(__CLASS__.':handle start', [$event->getData()]);
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
                        // $multi = MultithreadingRepository::getInstent();
                        $multi = new MultithreadingRepository();
                        $multi->setApiExcelId($data['id']);
                        $multi->setParam($path, ['concurrent' => $apiExcel->concurrent]);
                        $is_open = $apiExcel->concurrent > 1;
                        $result = $multi->newMultiRequest($param->url, $apiExcel->appkey, $is_open);

                        ksort($result);

                        // 正式上线后注释下一行
                        // \Log::info('ID-'.$data['id'].'-result: ', $result);
                        if (!$result) {
                            // 更新任务失败
                            $apiExcel->state = 5;
                            $apiExcel->save();
                            throw new \Exception(date('Y-m-d H:i:s').' 任务失败： 第三方请求错误～！'.$param['url']);
                        }

                        /************************* 2. 写入 Excel 文件 ******************************/
                        $fileName = $multi->saveExcel($param, $result);

                        if (!$fileName) {
                            $apiExcel->state = 5;
                            $apiExcel->save();
                            throw new \Exception(date('Y-m-d H:i:s').' 任务失败： Writer\Exception～！');
                        }

                        // 更新任务状态
                        $apiExcel->state = 2;
                        $apiExcel->finish_url = $fileName;
                        $apiExcel->save();
                    }
                } else {
                    \Log::info(__CLASS__.':handle out', [$event->getData()]);
                }
                break;
            default:
                break;
        }
    }
}
