<?php
/**
 * 创建事件类--异步事件
 * 此特性依赖Swoole的AsyncTask，必须先设置config/laravels.php的swoole.task_worker_num。
 * 异步事件的处理能力受Task进程数影响，需合理设置task_worker_num。
 * User: lisgroup
 * Date: 19-4-13
 * Time: 下午10:23
 */

namespace App\Events;

use App\Listeners\TestListener;
use Hhxsv5\LaravelS\Swoole\Task\Event;

class TestEvent extends Event
{
    protected $listeners = [
        // Listener list
        TestListener::class,
    ];

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
