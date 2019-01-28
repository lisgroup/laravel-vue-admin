<?php
/**
 * 配置文件
 */

return [
    '999' => [
        'url' => 'http://118.25.87.12/token/php/index.php/hello/123',
        'param' => 'idcard,name,mobile',
    ],
    '208' => [
        'url' => 'http://v.juhe.cn/telecom/query',
        'param' => 'idcard,realname,mobile',
    ],
    '261' => [ // 手机号码月消费档次
        'url' => 'http://v.juhe.cn/mobile_consume/query',
        'param' => 'mobile',
    ],
    '207' => [
        'url' => 'http://v.juhe.cn/verifybankcard3/query.php',
        'param' => 'idcard,realname,bankcard',
    ],
    '213' => [
        'url' => 'http://v.juhe.cn/verifybankcard4/query.php',
        'param' => 'idcard,realname,bankcard,mobile',
    ],
    '248' => [ // 在网时长
        'url' => 'http://v.juhe.cn/mobileOnline/query',
        'param' => 'mobile',
    ],
    '249' => [ // 在网状态
        'url' => 'http://v.juhe.cn/mobile_status/query',
        'param' => 'mobile',
    ],
];
