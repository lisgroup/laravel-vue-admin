<?php

namespace App\Listeners;

use App\Events\SaveApiExcelLogEvent;
use App\Models\ApiExcelLogs;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveApiExcelLogListener implements ShouldQueue
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
     * @param  SaveApiExcelLogEvent $event
     * @throws \Exception
     * @return void
     */
    public function handle(SaveApiExcelLogEvent $event)
    {
        // 获取事件中保存的数据
        // $logs = ['api_excel_id' => 1, 'sort_index' => 0, 'param' => 'name|mobile', 'result' => '{}', 'created_at' => date('Y-m-d H:i:s'),];
        $logs = $event->getData();
        // 保存数据
        ApiExcelLogs::insert($logs);

    }

    /**
     * 处理任务失败
     *
     * @param  \App\Events\SaveApiExcelLogEvent $event
     * @param  \Exception $exception
     * @return void
     */
    public function failed(SaveApiExcelLogEvent $event, $exception)
    {
        //
    }
}
