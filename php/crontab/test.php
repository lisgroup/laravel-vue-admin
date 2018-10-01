<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/2
 * Time: 0:23
 */

include_once __DIR__ . '/function.php';

$url = 'https://www.guke1.com/php/index.php/index/index/cron';
$result = httpGet($url);
echo $result;

