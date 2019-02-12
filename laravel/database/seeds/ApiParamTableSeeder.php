<?php

use Illuminate\Database\Seeder;

class ApiParamTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('api_param')->delete();
        
        \DB::table('api_param')->insert(array (
            0 => 
            array (
                'id' => 1,
                'website' => 'https://www.guke1.com',
                'name' => '999 测试接口',
                'method' => 'get',
                'url' => 'http://118.25.87.12/token/php/index.php/hello/123',
                'param' => 'idcard,name,mobile',
                'result' => '',
                'is_need' => 1,
                'state' => 1,
                'created_at' => '2019-01-29 20:10:08',
                'updated_at' => '2019-01-29 20:15:10',
            ),
            1 => 
            array (
                'id' => 2,
                'website' => 'https://www.juhe.cn/docs/api/id/208',
                'name' => '208 手机三要素',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/telecom/query',
                'param' => 'idcard,realname,mobile',
                'result' => '',
                'is_need' => 1,
                'state' => 1,
                'created_at' => '2019-01-29 20:10:08',
                'updated_at' => '2019-01-29 20:15:10',
            ),
            2 => 
            array (
                'id' => 3,
                'website' => 'https://www.juhe.cn/docs/api/id/261',
                'name' => '261 月消费档次',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/mobile_consume/query',
                'param' => 'mobile',
                'result' => '',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-01-29 20:10:08',
                'updated_at' => '2019-01-29 20:15:10',
            ),
            3 => 
            array (
                'id' => 4,
                'website' => 'https://www.juhe.cn/docs/api/id/207',
                'name' => '207 银行卡三要素',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/verifybankcard3/query.php',
                'param' => 'idcard,realname,bankcard',
                'result' => '',
                'is_need' => 1,
                'state' => 1,
                'created_at' => '2019-01-29 20:10:08',
                'updated_at' => '2019-01-29 20:15:10',
            ),
            4 => 
            array (
                'id' => 5,
                'website' => 'https://www.juhe.cn/docs/api/id/213',
                'name' => '213 银行卡四要素',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/verifybankcard4/query.php',
                'param' => 'idcard,realname,bankcard,mobile',
                'result' => '',
                'is_need' => 1,
                'state' => 1,
                'created_at' => '2019-01-29 20:10:08',
                'updated_at' => '2019-01-29 20:15:10',
            ),
            5 => 
            array (
                'id' => 6,
                'website' => 'https://www.juhe.cn/docs/api/id/248',
                'name' => '248 在网时长',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/mobileOnline/query',
                'param' => 'mobile',
                'result' => 'code,desc',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-01-29 20:10:08',
                'updated_at' => '2019-01-29 20:15:10',
            ),
            6 => 
            array (
                'id' => 7,
                'website' => 'https://www.juhe.cn/docs/api/id/249',
                'name' => '249 在网状态',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/mobile_status/query',
                'param' => 'mobile',
                'result' => 'state,description',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-01-29 20:10:08',
                'updated_at' => '2019-01-29 20:15:10',
            ),
        ));
        
        
    }
}
