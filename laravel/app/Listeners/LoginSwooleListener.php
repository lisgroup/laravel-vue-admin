<?php

namespace App\Listeners;


use App\Events\LoginSwooleEvent;
use Hhxsv5\LaravelS\Swoole\Task\Listener;
use Illuminate\Support\Facades\DB;
use Zhuzhichao\IpLocationZh\Ip;

class LoginSwooleListener extends Listener
{
    /**
     * @var LoginSwooleEvent
     */
    protected $event;

    /**
     * 失败重试次数
     *
     * @var int
     */
    public $tries = 1;

    /**
     * handle 方法中处理事件
     */
    public function handle()
    {
        $event = $this->event;
        // 获取事件中保存的信息
        $user = $event->getUser();
        $agent = $event->getAgent();
        $ip = $event->getIp();
        $timestamp = $event->getTimestamp();

        // 登录信息
        $login_info = [
            'ip' => $ip,
            'login_time' => $timestamp,
            'user_id' => $user->id
        ];

        // zhuzhichao/ip-location-zh 包含的方法获取 ip 地理位置
        $addresses = Ip::find($ip);
        $login_info['address'] = implode(' ', $addresses);

        // jenssegers/agent 的方法来提取agent信息
        $login_info['device'] = $agent->device(); // 设备名称
        $browser = $agent->browser();
        $login_info['browser'] = $browser.' '.$agent->version($browser); // 浏览器
        $platform = $agent->platform();
        $login_info['platform'] = $platform.' '.$agent->version($platform); // 操作系统
        $login_info['language'] = implode(',', $agent->languages()); // 语言
        // 设备类型
        if ($agent->isTablet()) {
            // 平板
            $login_info['device_type'] = 'tablet';
        } elseif ($agent->isMobile()) {
            // 便捷设备
            $login_info['device_type'] = 'mobile';
        } elseif ($agent->isRobot()) {
            // 爬虫机器人
            $login_info['device_type'] = 'robot';
            $login_info['device'] = $agent->robot(); // 机器人名称
        } else {
            // 桌面设备
            $login_info['device_type'] = 'desktop';
        }
        $login_info['created_at'] = date('Y-m-d H:i:s', $timestamp);
        $login_info['updated_at'] = date('Y-m-d H:i:s');

        // 插入到数据库
        DB::table('login_log')->insert($login_info);
    }
}
