<?php
/**
 * WebSocket 服务器
 * User: lisgroup
 * Date: 19-4-2
 * Time: 下午 4:28
 */

namespace App\Services;

use App\Models\ApiExcel;
use App\Models\ApiExcelLogs;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * @see https://wiki.swoole.com/wiki/page/400.html
 */
class WebSocketService implements WebSocketHandlerInterface
{
    // 声明没有参数的构造函数
    public function __construct()
    {
    }

    public function onOpen($server, $request)
    {
        // 在触发 onOpen 事件之前 Laravel 的生命周期已经完结，所以 Laravel 的 Request 是可读的，Session 是可读写的
        // \Log::info('New WebSocket connection', [$request->fd, request()->all(), session()->getId(), session('xxx'), session(['yyy' => time()])]);
        // 1. 根据 api_excel 的 id 查询总数，
        $req = $request->get;
        if (isset($req['id']) && $api_excel = ApiExcel::find($req['id'])) {
            if ($api_excel['state'] == 1 && $api_excel['total_excel'] > 0) {
                // 2. 查询 api_excel_logs 表更新的数据量
                while (true) {
                    $total = ApiExcelLogs::where('api_excel_id', $req['id'])->count();
                    $str = floor($total / $api_excel['total_excel'] * 100).'%';
                    // 3. 输出完成率
                    $server->push($request->fd, $str);
                    sleep(1);
                    if ($total >= $api_excel['total_excel']) {
                        break;
                    }
                }
            } else {
                $server->push($request->fd, '100%');
            }
        } else {
            $server->push($request->fd, '100%');
        }
        // throw new \Exception('an exception');// 此时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
    }

    public function onMessage($server, $frame)
    {
        // \Log::info('Received message', [$frame->fd, $frame->data, $frame->opcode, $frame->finish]);
        $server->push($frame->fd, date('Y-m-d H:i:s'));
        // throw new \Exception('an exception');// 此时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
    }

    public function onClose($server, $fd, $reactorId)
    {
        // throw new \Exception('an exception');// 此时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
    }
}
