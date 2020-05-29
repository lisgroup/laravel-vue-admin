<?php
/**
 * WebSocket 服务器
 * User: lisgroup
 * Date: 19-4-2
 * Time: 下午 4:28
 */

namespace App\Services;

use App\Http\Repository\ApiRepository;
use App\Http\Repository\MultithreadingRepository;
use App\Models\ApiExcel;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;
use Illuminate\Support\Facades\Redis;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * @see https://wiki.swoole.com/wiki/page/400.html
 */
class WebSocketService implements WebSocketHandlerInterface
{
    public $perPage = 10;

    private $redis;

    // 声明没有参数的构造函数
    public function __construct()
    {
    }

    public function onOpen($server, $request)
    {
        $userInfo = auth('api')->user();
        if (empty($userInfo)) {
            $data = $this->outJson(1200, [], '用户未登录或登录超时');
            return $server->push($request->fd, $data);
        }
        // 在触发 onOpen 事件之前 Laravel 的生命周期已经完结，所以 Laravel 的 Request 是可读的，Session 是可读写的
        // \Log::info('New WebSocket connection', [$request->fd, request()->all(), session()->getId(), session('xxx'), session(['yyy' => time()])]);
        // 1. 根据 api_excel 的 id 查询总数，
        $req = $request->get;
        $this->perPage = isset($req['perPage']) ? intval($req['perPage']) : 10;

        $action = $req['action'] ?? '';
        switch ($action) {
            case 'api_excel': // api_excel 列表完成率
                while (true) {
                    $user_id = $userInfo['id'];
                    $server->push($request->fd, $this->apiExcel($user_id));
                    sleep(5);
                    $state = ApiExcel::where('state', 1)->first();
                    if (!$state) {
                        $server->push($request->fd, $this->apiExcel($user_id));
                        break;
                    }
                    // 每个用户 fd 限制请求次数
                    $redisKey = 'websocket_fd_'.$request->fd;
                    if (empty($this->redis)) {
                        $this->redis = Redis::connection();
                    }
                    // 如果获取不到 redis 实例，使用总计数次数
                    if ($this->redis) {
                        $count = $this->redis->incr($redisKey);
                        if ($count == 1) {
                            // 设置过期时间
                            $this->redis->expire($redisKey, 6000);
                        }
                        if ($count > 20000) { // 防止刷单的安全拦截
                            break; // 超出就跳出循环
                        }
                    } else {
                        $count_fd = 'count_'.$request->fd;
                        $this->incrKey($count_fd);
                        // 单fd超过 1000 次跳出循环
                        if ($this->$count_fd > 1000) {
                            unset($this->$count_fd);
                            break;
                        }
                    }
                }
        }
        return '';

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

    /**
     * 处理输出
     *
     * @param int $code
     * @param array $data
     * @param string $reason
     *
     * @return array
     */
    public function outJson($code = 200, $data = [], $reason = 'success')
    {
        if ($reason === 'success') {
            $reason = config('errorCode.'.$code.'.reason') ?? 'success';
        }

        return json_encode(['code' => $code, 'reason' => $reason, 'data' => $data], JSON_UNESCAPED_UNICODE);
    }


    /**
     * 根据 api_excel_id 获取完成率
     *
     * @param $id
     *
     * @return array
     */
    protected function getRateById($id)
    {
        $rate = '100';
        if ($id && $id == floor($id)) {
            // 3. 输出完成率
            $rate = MultithreadingRepository::getInstent()->completionRate($id);
        }
        return $this->outJson(200, ['rate' => $rate]);
    }

    /**
     * 获取api_excel 列表-完成率
     *
     * @param int $user_id 用户 id
     *
     * @return array
     */
    private function apiExcel($user_id)
    {
        // 查询对应用户的上传数据
        $where = [];
        if ($user_id != 1) {
            $where = ['uid' => $user_id];
        }
        $list = ApiExcel::with('apiParam')->where($where)->orderBy('id', 'desc')->paginate($this->perPage);
        // 获取完成进度情况
        $list = ApiRepository::getInstent()->workProgress($list);

        $appUrl = env('APP_URL') ?? '';
        $collect = collect(['appUrl' => $appUrl]);
        $items = $collect->merge($list);

        return $this->outJson(200, $items);
    }

    private function incrKey($key)
    {
        if (!isset($this->$key)) {
            $this->$key = 1;
        }
        $this->$key++;
    }
}
