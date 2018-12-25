<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BusInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bus:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the vueBus';

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
        $this->executeShellCommands('php artisan migrate --seed');
        $this->executeShellCommands('php artisan jwt:secret');
        $this->executeShellCommands('php artisan storage:link');
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
