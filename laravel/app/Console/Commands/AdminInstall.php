<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AdminInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:install {param?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the laravel-vue-admin. If you want support elasticSearch, You Need Run: php artisan admin:install search';

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
     * @return void
     */
    public function handle()
    {
        $this->executeShellCommands('php artisan key:generate');
        $this->executeShellCommands('php artisan migrate');
        $this->executeShellCommands('php artisan db:seed');
        $this->executeShellCommands('php artisan storage:link');
        $this->executeShellCommands('php artisan jwt:secret --force');

        $param = $this->argument('param'); // 不指定参数名的情况下用 argument
        if ($param == 'search') {
            $this->executeShellCommands('php artisan elasticsearch:import "App\Models\Line"');
            $this->executeShellCommands('php artisan elasticsearch:import "App\Models\BusLine"');
        }
    }

    /**
     * Exec shell with pretty print.
     *
     * @param  string $command
     *
     * @return void
     */
    public function executeShellCommands($command)
    {
        $this->info('-------------');
        $this->info($command);
        $output = shell_exec($command);
        $this->info($output);
    }
}
