<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ApiExcelEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array api_excel 表中数据
     */
    private $data;

    /**
     * 实例化事件时传递参数
     *
     * ApiExcelEvent constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * 获取 $data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
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
