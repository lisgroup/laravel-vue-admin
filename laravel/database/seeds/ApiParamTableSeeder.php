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
                'result' => 'isp,code,desc',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-01-29 12:10:08',
                'updated_at' => '2019-03-26 10:30:24',
            ),
            1 =>
            array (
                'id' => 2,
                'website' => 'https://www.juhe.cn/docs/api/id/208',
                'name' => '208 手机三要素',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/telecom/query',
                'param' => 'idcard,realname,mobile',
                'result' => 'res,resmsg',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-01-29 12:10:08',
                'updated_at' => '2019-02-27 06:24:11',
            ),
            2 =>
            array (
                'id' => 3,
                'website' => 'https://www.juhe.cn/docs/api/id/261',
                'name' => '261 月消费档次',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/mobile_consume/query',
                'param' => 'mobile',
                'result' => 'isp,code,desc',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-01-29 12:10:08',
                'updated_at' => '2019-01-29 12:15:10',
            ),
            3 =>
            array (
                'id' => 4,
                'website' => 'https://www.juhe.cn/docs/api/id/207',
                'name' => '207 银行卡三要素',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/verifybankcard3/query.php',
                'param' => 'idcard,realname,bankcard',
                'result' => 'res,message',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-01-29 12:10:08',
                'updated_at' => '2019-01-29 12:15:10',
            ),
            4 =>
            array (
                'id' => 5,
                'website' => 'https://www.juhe.cn/docs/api/id/213',
                'name' => '213 银行卡四要素',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/verifybankcard4/query.php',
                'param' => 'idcard,realname,bankcard,mobile',
                'result' => 'res,message',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-01-29 12:10:08',
                'updated_at' => '2019-01-29 12:15:10',
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
                'created_at' => '2019-01-29 12:10:08',
                'updated_at' => '2019-01-29 12:15:10',
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
                'created_at' => '2019-01-29 12:10:08',
                'updated_at' => '2019-01-29 12:15:10',
            ),
            7 =>
            array (
                'id' => 8,
                'website' => 'https://www.juhe.cn/docs/api/id/354',
                'name' => '354 手机状态查询',
                'method' => 'get',
                'url' => 'http://apis.juhe.cn/invalid_number/query',
                'param' => 'mobiles',
                'result' => 'data.0.status',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-02-26 22:06:23',
                'updated_at' => '2019-02-26 22:06:23',
            ),
            8 =>
            array (
                'id' => 9,
                'website' => 'https://www.juhe.cn/docs/api/id/103',
                'name' => '103 身份证实名',
                'method' => 'get',
                'url' => 'http://op.juhe.cn/idcard/query',
                'param' => 'realname,idcard',
                'result' => 'res',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-03-15 03:27:18',
                'updated_at' => '2019-03-15 03:27:18',
            ),
            9 =>
            array (
                'id' => 11,
                'website' => 'https://www.juhe.cn/docs/api/id/251',
                'name' => '251手机二元素',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/telecom2/query',
                'param' => 'mobile,realname',
                'result' => 'res,error_code,resmsg',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-03-18 09:01:42',
                'updated_at' => '2019-03-18 09:01:42',
            ),
            10 =>
            array (
                'id' => 12,
                'website' => 'https://www.juhe.cn/docs/api/id/336',
                'name' => '336 非机动车销售发票',
                'method' => 'get',
                'url' => 'http://apis.juhe.cn/fp/query',
                'param' => 'fpdm,fphm,kprq,jym,je',
                'result' => 'xfmc,xfsh,xfdz,xfzh,gfmc,gfsh,gfdz,gfzh,jym,jqbm,je,zfbz,bz,spxx.0.spmc,spxx.0.ggxh,spxx.0.spdw,spxx.0.spsl,spxx.0.spdj,spxx.0.spje,spxx.0.spslv,spxx.0.spse,spxx.0.flbm,spxx.0.cph,spxx.0.type',
                'is_need' => 0,
                'state' => 1,
                'created_at' => '2019-04-08 08:51:03',
                'updated_at' => '2019-04-09 06:09:38',
            ),
            11 =>
            array (
                'id' => 13,
                'website' => 'https://www.juhe.cn/docs/api/id/336',
                'name' => '336 机动车销售发票',
                'method' => 'get',
                'url' => 'http://apis.juhe.cn/fp/query',
                'param' => 'fpdm,fphm,kprq,jym,je',
                'result' => 'orderid,xfmc,xfsh,xfdz,xfzh,gfmc,gfsh,gfdz,gfzh,jym,jqbm,spxx,je,zfbz,bz,cllx,cpxh,cd,hgzh,fdjh,cjh,sl,jshj,se,swjgmc,swjgdm,wsz,dw,xcrs,gfid,fplx',
                'is_need' => 0,
                'state' => 0,
                'created_at' => '2019-04-09 06:15:49',
                'updated_at' => '2019-04-09 06:15:57',
            ),
            12 =>
            array (
                'id' => 14,
                'website' => 'https://www.juhe.cn/docs/api/id/188',
                'name' => '188银行卡二元素',
                'method' => 'get',
                'url' => 'http://v.juhe.cn/verifybankcard/query',
                'param' => 'realname,bankcard',
                'result' => 'res,error_code,reason',
                'is_need' => 1,
                'state' => 1,
                'created_at' => '2019-04-19 07:52:02',
                'updated_at' => '2019-04-19 07:52:02',
            ),
        ));


    }
}
