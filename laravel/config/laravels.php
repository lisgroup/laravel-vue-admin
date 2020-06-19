<?php
/**
 * @see https://github.com/hhxsv5/laravel-s/blob/master/Settings-CN.md  Chinese
 * @see https://github.com/hhxsv5/laravel-s/blob/master/Settings.md  English
 */
return [
    'listen_ip'                => env('LARAVELS_LISTEN_IP', '0.0.0.0'),
    'listen_port'              => env('LARAVELS_LISTEN_PORT', 5200),
    'socket_type'              => defined('SWOOLE_SOCK_TCP') ? SWOOLE_SOCK_TCP : 1,
    'enable_coroutine_runtime' => false,
    'server'                   => env('LARAVELS_SERVER', 'LaravelS'),
    'handle_static'            => env('LARAVELS_HANDLE_STATIC', false),
    'laravel_base_path'        => env('LARAVEL_BASE_PATH', base_path()),
    'inotify_reload'           => [
        'enable'        => env('LARAVELS_INOTIFY_RELOAD', false),
        'watch_path'    => base_path(),
        'file_types'    => ['.php'],
        'excluded_dirs' => [],
        'log'           => true,
    ],
    'event_handlers'           => [],
    'websocket'                => [
        'enable'  => true, // 看清楚，这里是true
        'handler' => \App\Services\WebSocketService::class,
    ],
    'sockets'                  => [],
    'processes'                => [
        //[
        //    'class'    => \App\Processes\TestProcess::class,
        //    'redirect' => false, // Whether redirect stdin/stdout, true or false
        //    'pipe'     => 0 // The type of pipeline, 0: no pipeline 1: SOCK_STREAM 2: SOCK_DGRAM
        //    'enable'   => true // Whether to enable, default true
        //],
    ],
    'timer'                    => [
        'enable'        => env('LARAVELS_TIMER', true),
        'jobs'          => [
            // 启用LaravelScheduleJob来执行`php artisan schedule:run`，每分钟一次，替代 Linux Crontab
            \Hhxsv5\LaravelS\Illuminate\LaravelScheduleJob::class,
            // 两种配置参数的方式：
            // [\App\Jobs\Timer\TestCronJob::class, [1000, true]], // 注册时传入参数
            // \App\Jobs\Timer\TestCronJob::class, // 重载对应的方法来返回参数
            // \App\Jobs\Timer\FiveMinutesCronJob::class,
            // \App\Jobs\Timer\HourlyCronJob::class,
        ],
        'max_wait_time' => 5,
    ],
    'swoole_tables'            => [],
    'register_providers'       => [],
    'cleaners'                 => [
        // See LaravelS's built-in cleaners: https://github.com/hhxsv5/laravel-s/blob/master/Settings.md#cleaners
        Hhxsv5\LaravelS\Illuminate\Cleaners\SessionCleaner::class, // 如果你的项目中使用到了Session或Authentication，请解除这行注释
        Hhxsv5\LaravelS\Illuminate\Cleaners\AuthCleaner::class, // 如果你的项目中使用到了Authentication或Passport，请解除这行注释
        Hhxsv5\LaravelS\Illuminate\Cleaners\JWTCleaner::class, // 如果你的项目中使用到了包"tymon/jwt-auth"，请解除这行注释
        // Hhxsv5\LaravelS\Illuminate\Cleaners\RequestCleaner::class,
    ],
    'destroy_controllers'      => [
        'enable'        => false,
        'excluded_list' => [
            //\App\Http\Controllers\TestController::class,
        ],
    ],
    'swoole'                   => [
        'daemonize'          => env('LARAVELS_DAEMONIZE', false),
        // dispatch_mode 只能设置为 2、4、5，https://wiki.swoole.com/wiki/page/277.html
        'dispatch_mode'      => 2,
        'reactor_num'        => function_exists('swoole_cpu_num') ? swoole_cpu_num() * 2 : 4,
        'worker_num'         => function_exists('swoole_cpu_num') ? swoole_cpu_num() * 2 : 8,
        'task_worker_num'    => function_exists('swoole_cpu_num') ? swoole_cpu_num() * 2 : 8,
        'task_ipc_mode'      => 1,
        'task_max_request'   => env('LARAVELS_TASK_MAX_REQUEST', 8000),
        'task_tmpdir'        => @is_writable('/dev/shm/') ? '/dev/shm' : '/tmp',
        'max_request'        => env('LARAVELS_MAX_REQUEST', 8000),
        'open_tcp_nodelay'   => true,
        'pid_file'           => storage_path('laravels.pid'),
        'log_file'           => storage_path(sprintf('logs/swoole-%s.log', date('Y-m'))),
        'log_level'          => 4,
        'document_root'      => base_path('public'),
        'buffer_output_size' => 2 * 1024 * 1024,
        'socket_buffer_size' => 128 * 1024 * 1024,
        'package_max_length' => 4 * 1024 * 1024,
        'reload_async'       => true,
        'max_wait_time'      => 60,
        'enable_reuse_port'  => true,
        'enable_coroutine'   => false,
        'http_compression'   => false,

        // Slow log
        'request_slowlog_timeout' => 2,
        'request_slowlog_file'    => storage_path(sprintf('logs/slow-%s.log', date('Y-m'))),
        'trace_event_worker'      => true,

        /**
         * More settings of Swoole
         * @see https://wiki.swoole.com/#/server/setting  Chinese
         * @see https://www.swoole.co.uk/docs/modules/swoole-server/configuration  English
         */
    ],
];
