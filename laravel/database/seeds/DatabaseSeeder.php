<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CronTasksTableSeeder::class);
        $this->call(LinesTableSeeder::class);
        $this->call(BusLinesTableSeeder::class);
        $this->call(ApiParamTableSeeder::class);
    }
}
