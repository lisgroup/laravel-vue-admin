<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\User;
use Jenssegers\Agent\Agent;

class LoginEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User 用户模型
     */
    protected $user;

    /**
     * @var Agent Agent对象
     */
    protected $agent;

    /**
     * @var string IP地址
     */
    protected $ip;

    /**
     * @var int 登录时间戳
     */
    protected $timestamp;

    /**
     * 实例化事件时传递这些信息
     *
     * LoginEvent constructor.
     * @param $user
     * @param $agent
     * @param $ip
     * @param $timestamp
     */
    public function __construct($user, $agent, $ip, $timestamp)
    {
        $this->user = $user;
        $this->agent = $agent;
        $this->ip = $ip;
        $this->timestamp = $timestamp;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getAgent()
    {
        return $this->agent;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-default');
    }
}
