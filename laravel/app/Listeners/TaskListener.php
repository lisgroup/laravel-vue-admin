<?php

/**
 * 创建监听器类
 * User: lisgroup
 * Date: 19-4-13
 * Time: 下午10:28
 */

namespace App\Listeners;

use App\Tasks\TaskEvent;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Hhxsv5\LaravelS\Swoole\Task\Listener;

class TaskListener extends Listener
{
    // 声明没有参数的构造函数
    public function __construct()
    {
    }

    public function handle(Event $event)
    {
        \Log::info(__CLASS__.':handle start', [$event->getData()]);
        sleep(2);// 模拟一些慢速的事件处理
        // 监听器中也可以投递Task，但不支持Task的finish()回调。
        // 注意：
        // 1.参数2需传true
        // 2.config/laravels.php中修改配置 task_ipc_mode 为1或2，参考 https://wiki.swoole.com/wiki/page/296.html
        $ret = Task::deliver(new TaskEvent('task data'), true);
        var_dump($ret);
        // throw new \Exception('an exception');// handle时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
    }
}