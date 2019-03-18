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
                'id' => 1,
                'name' => 'show-api_param',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'create-api_param',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'update-api_param',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'delete-api_param',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'show-api_excel',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'create-api_excel',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'update-api_excel',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'delete-api_excel',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'show-category',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'create-category',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'update-category',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'delete-category',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'show-article',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'create-article',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'update-article',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'delete-article',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'show-nav',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'create-nav',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'update-nav',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'delete-nav',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'show-tag',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'create-tag',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'update-tag',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'delete-tag',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'show-crontask',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'create-crontask',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'update-crontask',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'delete-crontask',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'show-lines',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'create-lines',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'update-lines',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'delete-lines',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'show-config',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'create-config',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'update-config',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'delete-config',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'show-user',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'create-user',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'update-user',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'delete-user',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'show-permissions',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'create-permissions',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'update-permissions',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'delete-permissions',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'show-roles',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'create-roles',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'update-roles',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'delete-roles',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'ALL',
                'route' => '',
                'view' => '',
                'hidden' => 1,
                'created_at' => '2019-03-13 10:28:22',
                'updated_at' => '2019-03-13 10:28:22',
            ),
        ));
        
        
    }
}