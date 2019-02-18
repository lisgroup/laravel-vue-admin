<?php

use Illuminate\Database\Seeder;

class CronTasksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cron_tasks')->delete();
        
        \DB::table('cron_tasks')->insert(array (
            0 => 
            array (
                'id' => 1,
                'cid' => '175ecd8d-c39d-4116-83ff-109b946d7cb4',
                'LineGuid' => '921f91ad-757e-49d6-86ae-8e5f205117be',
            'LineInfo' => '快线1号(星塘公交中心)',
                'is_task' => 1,
                'start_at' => '05:00:00',
                'end_at' => '23:10:00',
                'created_at' => '2018-10-05 16:30:01',
                'updated_at' => '2018-10-05 16:30:01',
            ),
            1 => 
            array (
                'id' => 2,
                'cid' => '175ecd8d-c39d-4116-83ff-109b946d7cb4',
                'LineGuid' => 'af9b209b-f99d-4184-af7d-e6ac105d8e7f',
            'LineInfo' => '快线1号(木渎公交换乘枢纽站)',
                'is_task' => 1,
                'start_at' => '05:00:00',
                'end_at' => '23:10:00',
                'created_at' => '2018-10-05 16:30:01',
                'updated_at' => '2018-10-05 16:30:01',
            ),
            2 => 
            array (
                'id' => 3,
                'cid' => '175ecd8d-c39d-4116-83ff-109b946d7cb4',
                'LineGuid' => 'c3d74f0a-a1f8-4b03-9469-faee2b5c8f3d',
            'LineInfo' => '38(苏州站北广场)',
                'is_task' => 1,
                'start_at' => '06:00:00',
                'end_at' => '23:00:00',
                'created_at' => '2018-10-05 16:30:01',
                'updated_at' => '2018-10-05 16:30:01',
            ),
            3 => 
            array (
                'id' => 4,
                'cid' => '175ecd8d-c39d-4116-83ff-109b946d7cb4',
                'LineGuid' => 'b8b1a2ae-dfdf-4454-85f7-980298a6639b',
            'LineInfo' => '38(孙庄路)',
                'is_task' => 1,
                'start_at' => '05:00:00',
                'end_at' => '23:00:00',
                'created_at' => '2018-10-05 16:30:01',
                'updated_at' => '2018-10-05 16:30:01',
            ),
        ));
        
        
    }
}
