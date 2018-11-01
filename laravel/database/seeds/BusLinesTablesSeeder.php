<?php

use Illuminate\Database\Seeder;

class BusLinesTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dateTime = date('Y-m-d H:i:s');
        DB::table('bus_lines')->insert([
            [
                'name' => '快线1号',
                'LineGuid' => '921f91ad-757e-49d6-86ae-8e5f205117be',
                'LineInfo' => '快线1号(星塘公交中心)',
                'FromTo' => '星塘公交中心',
                'expiration' => '1556606320',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'name' => '快线1号',
                'LineGuid' => 'af9b209b-f99d-4184-af7d-e6ac105d8e7f',
                'LineInfo' => '快线1号(木渎公交换乘枢纽站)',
                'FromTo' => '木渎公交换乘枢纽站',
                'expiration' => '1556606320',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'name' => '138',
                'LineGuid' => '46b6787b-476b-4b03-8cc7-c370010573c7',
                'LineInfo' => '138(国泰三村南门)',
                'FromTo' => '国泰三村南门',
                'expiration' => '1556606320',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'name' => '138',
                'LineGuid' => '4a7d59ae-7408-4497-bf1a-45849f1b71b9',
                'LineInfo' => '138(湖畔花园首末站)',
                'FromTo' => '湖畔花园首末站',
                'expiration' => '1556606320',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'name' => '238',
                'LineGuid' => '0b414f07-059d-4f6f-899e-e2263abcdd9f',
                'LineInfo' => '238(星华街游客中心首末站)',
                'FromTo' => '星华街游客中心首末站',
                'expiration' => '1556606320',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'name' => '238',
                'LineGuid' => '0874023a-c2eb-4b3d-994a-0a17facfe857',
                'LineInfo' => '238(东兴路首末站)',
                'FromTo' => '东兴路首末站',
                'expiration' => '1556606320',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'name' => '38',
                'LineGuid' => 'c3d74f0a-a1f8-4b03-9469-faee2b5c8f3d',
                'LineInfo' => '38(苏州站北广场)',
                'FromTo' => '苏州站北广场',
                'expiration' => '1556606320',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'name' => '38',
                'LineGuid' => 'b8b1a2ae-dfdf-4454-85f7-980298a6639b',
                'LineInfo' => '38(孙庄路)',
                'FromTo' => '孙庄路',
                'expiration' => '1556606320',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ]
        ]);
    }
}
