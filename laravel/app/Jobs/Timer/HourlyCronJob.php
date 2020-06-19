<?php
/**
 * 创建定时任务类--毫秒级定时任务
 * 基于 Swoole 的毫秒定时器，封装的定时任务，取代 Linux 的 Crontab
 *
 * User: lisgroup
 * Date: 20-06-08
 * Time: 15:20
 */

namespace App\Jobs\Timer;

use App\Http\Repository\ApiRepository;
use App\Tasks\TestTask;
use Swoole\Coroutine;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use Hhxsv5\LaravelS\Swoole\Timer\CronJob;

class HourlyCronJob extends CronJob
{
    protected $i = 0;
    // !!! 定时任务的`interval`和`isImmediate`有两种配置方式（二选一）：一是重载对应的方法，二是注册定时任务时传入参数。
    // --- 重载对应的方法来返回配置：开始
    public function interval()
    {
        return 1000 * 3600;// 每 3600 秒运行一次
    }

    public function isImmediate()
    {
        return false;// 是否立即执行第一次，false则等待间隔时间后执行第一次
    }

    // --- 重载对应的方法来返回配置：结束
    public function run()
    {
        \Log::info(__METHOD__, ['start', $this->i, microtime(true)]);
        // do something
        ApiRepository::getInstent()->handleAutoDelete();
    }
}
