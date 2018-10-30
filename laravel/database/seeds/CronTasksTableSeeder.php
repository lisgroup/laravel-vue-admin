<?php

use Illuminate\Database\Seeder;

class CronTasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('cron_tasks')->insert([
            [
                'cid' => '175ecd8d-c39d-4116-83ff-109b946d7cb4',
                'LineGuid' => '921f91ad-757e-49d6-86ae-8e5f205117be',
                'LineInfo' => '快线1号(星塘公交中心)',
                'start_at' => '05:00:00',
                'end_at' => '23:10:00',
            ],
            [
                'cid' => '175ecd8d-c39d-4116-83ff-109b946d7cb4',
                'LineGuid' => 'af9b209b-f99d-4184-af7d-e6ac105d8e7f',
                'LineInfo' => '快线1号(木渎公交换乘枢纽站)',
                'start_at' => '05:00:00',
                'end_at' => '23:10:00',
            ]
        ]);
    }
}
