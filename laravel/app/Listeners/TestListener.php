<?php

/**
 * 创建监听器类
 * User: lisgroup
 * Date: 19-4-13
 * Time: 下午10:28
 */

namespace App\Listeners;

use App\Events\TestEvent;
use App\Tasks\TestTask;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use Hhxsv5\LaravelS\Swoole\Task\Listener;

class TestListener extends Listener
{
    /**
     * @var TestEvent
     */
    protected $event;

    public function handle()
    {
        \Log::info(__CLASS__.':handle start', [$this->event->getData()]);
        sleep(2);// 模拟一些慢速的事件处理
        // 监听器中也可以投递Task，但不支持Task的finish()回调。
        // 注意：config/laravels.php中修改配置task_ipc_mode为1或2，参考 https://wiki.swoole.com/#/server/setting?id=task_ipc_mode
        $ret = Task::deliver(new TestTask('task data'));
        var_dump($ret);
        // throw new \Exception('an exception');// handle时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
    }
}
