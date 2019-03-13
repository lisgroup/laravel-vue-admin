<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Super Administrator',
                'created_at' => '2019-03-13 11:29:55',
                'updated_at' => '2019-03-13 11:29:55',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Editor',
                'created_at' => '2019-03-13 15:34:47',
                'updated_at' => '2019-03-13 15:34:47',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Kefu',
                'created_at' => '2019-03-13 15:50:01',
                'updated_at' => '2019-03-13 15:50:01',
            ),
        ));
        
        
    }
}