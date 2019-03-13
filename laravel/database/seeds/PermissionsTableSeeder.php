<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name' => 'update-article',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'delete-article',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'show-article',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'create-api_excel',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'update-api_excel',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            5 => 
            array (
                'id' => 7,
                'name' => 'delete-api_excel',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            6 => 
            array (
                'id' => 8,
                'name' => 'show-api_excel',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            7 => 
            array (
                'id' => 13,
                'name' => 'create-category',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            8 => 
            array (
                'id' => 14,
                'name' => 'update-category',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            9 => 
            array (
                'id' => 15,
                'name' => 'delete-category',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            10 => 
            array (
                'id' => 16,
                'name' => 'show-category',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            11 => 
            array (
                'id' => 17,
                'name' => 'create-nav',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            12 => 
            array (
                'id' => 18,
                'name' => 'update-nav',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            13 => 
            array (
                'id' => 19,
                'name' => 'delete-nav',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            14 => 
            array (
                'id' => 20,
                'name' => 'show-nav',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            15 => 
            array (
                'id' => 21,
                'name' => 'create-tag',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            16 => 
            array (
                'id' => 22,
                'name' => 'update-tag',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            17 => 
            array (
                'id' => 23,
                'name' => 'delete-tag',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            18 => 
            array (
                'id' => 24,
                'name' => 'show-tag',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            19 => 
            array (
                'id' => 25,
                'name' => 'create-crontask',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            20 => 
            array (
                'id' => 26,
                'name' => 'update-crontask',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            21 => 
            array (
                'id' => 27,
                'name' => 'delete--crontask',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            22 => 
            array (
                'id' => 28,
                'name' => 'show-crontask',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            23 => 
            array (
                'id' => 29,
                'name' => 'create-lines',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            24 => 
            array (
                'id' => 30,
                'name' => 'update-lines',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            25 => 
            array (
                'id' => 31,
                'name' => 'delete-lines',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            26 => 
            array (
                'id' => 32,
                'name' => 'show-lines',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            27 => 
            array (
                'id' => 33,
                'name' => 'create-config',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            28 => 
            array (
                'id' => 34,
                'name' => 'update-config',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            29 => 
            array (
                'id' => 35,
                'name' => 'delete-config',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            30 => 
            array (
                'id' => 36,
                'name' => 'show-config',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            31 => 
            array (
                'id' => 37,
                'name' => 'create-user',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            32 => 
            array (
                'id' => 38,
                'name' => 'update-user',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            33 => 
            array (
                'id' => 39,
                'name' => 'delete-user',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            34 => 
            array (
                'id' => 40,
                'name' => 'show-user',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            35 => 
            array (
                'id' => 41,
                'name' => 'create-permissions',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            36 => 
            array (
                'id' => 42,
                'name' => 'update-permissions',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            37 => 
            array (
                'id' => 43,
                'name' => 'delete-permissions',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            38 => 
            array (
                'id' => 44,
                'name' => 'show-permissions',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            39 => 
            array (
                'id' => 45,
                'name' => 'create-roles',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            40 => 
            array (
                'id' => 46,
                'name' => 'update-roles',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            41 => 
            array (
                'id' => 47,
                'name' => 'delete-roles',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            42 => 
            array (
                'id' => 48,
                'name' => 'show-roles',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            43 => 
            array (
                'id' => 49,
                'name' => 'ALL',
                'route' => '',
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
        ));
        
        
    }
}