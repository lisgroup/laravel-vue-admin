<?php

namespace App\Jobs;

use App\Http\Repository\BusRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SaveBusLine implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 任务最大尝试次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * 任务运行的超时时间。
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * 存储的数据
     *
     * @var array|object
     */
    private $datum;

    /**
     * Create a new job instance.
     * @param array|object $datum
     *
     * @return void
     */
    public function __construct($datum)
    {
        $this->datum = $datum;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rs = BusRepository::getInstent()->updateBusLine($this->datum);
        Log::info('------------- 入库记录操作结果： '.$rs);
    }
}
