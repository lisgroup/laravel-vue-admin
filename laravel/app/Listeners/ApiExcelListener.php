<?php

namespace App\Listeners;

use App\Events\ApiExcelEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApiExcelListener implements ShouldQueue
{
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
                // TODO: 根据 upload 上传的数据批量测试数据
                // public_path()
                break;
            default:
                break;
        }
    }
}
