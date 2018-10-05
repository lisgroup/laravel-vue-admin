<?php
/**
 * 定时任务：HTTP 请求
 * User: Administrator
 * Date: 2018/10/2
 * Time: 0:23
 */

include_once __DIR__ . '/function.php';

$url = 'https://www.guke1.com/index/index/cron';

$i = 1;
do {
    $result = httpGet($url);
    echo $result;

    $i++;

    if (date('Hi') > 2200) {
        $i = 0;
    }
    sleep(200);
} while ($i > 0);

