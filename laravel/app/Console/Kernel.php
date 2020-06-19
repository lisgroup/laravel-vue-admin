<?php

namespace App\Console;

use App\Http\Repository\ApiRepository;
use App\Http\Repository\BusRepository;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use QL\QueryList;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // 命令列表
        'App\Console\Commands\LineTask',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function()
        {
            // // 定时任务
            // Log::info('定时任务：'.date('Y-m-d H:i:s'));
            // // 测试定时 HTTP 请求
            // $url = "http://118.25.87.12/token/php/index.php/hello/123";
            // $query = QueryList::get($url);
            // $path = storage_path('framework/cache/');
            // is_dir($path) || mkdir($path, 777, true);
            // file_put_contents($path.'/cache.txt', $query->getHtml().PHP_EOL, FILE_APPEND);
            // // 每隔五分钟入库操作
            // BusRepository::getInstent()->cronTaskTable();
            ApiRepository::getInstent()->autoFailed();
            \Log::info('every five minutes task', []);
        })->everyFiveMinutes()->between('5:00', '23:00')->runInBackground();

        // 每隔一小时检测需要自动处理的任务
        $schedule->call(function() {
            ApiRepository::getInstent()->handleAutoDelete();
        })->hourly()->between('4:00', '23:59')->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
