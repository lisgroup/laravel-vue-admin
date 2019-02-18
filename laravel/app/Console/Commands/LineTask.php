<?php

namespace App\Console\Commands;

use App\Http\Repository\TaskRepository;
use Illuminate\Console\Command;

class LineTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'line:task {param?} {--param2=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To perform the specified line task, use the following parameters: php artisan line:task {$taskName}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 入口方法
        $param = $this->argument('param'); // 不指定参数名的情况下用 argument
        // $param2 = $this->option('param2'); // 用--开头指定参数名
        $repository = TaskRepository::getInstent();
        switch ($param) {
            // case '':
            case 'index':
                $result = $repository->lineList();
                break;
            default:
                if (is_callable([$repository, $param])) {
                    $result = $repository->$param();
                } else {
                    return false;
                }

        }
        if ($result['msg']) {
            echo $result['msg'].PHP_EOL;
        }
        return true;
    }
}
