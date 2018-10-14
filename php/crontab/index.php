<?php
/**
 * 定时任务：HTTP 请求
 * User: Administrator
 * Date: 2018/10/2
 * Time: 0:23
 */

ini_set('date.timezone', 'Asia/Shanghai');
set_time_limit(0);

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



/**
 * http请求获取网页源数据
 * @param $url
 * @param bool $ssl 是否https协议
 * @return bool|mixed|string
 */
function httpGet($url, $ssl = false)
{
    $curl = curl_init(); //1.初始化curl
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ssl);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $ssl);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    if (empty($res)) {
        $res = file_get_contents($url);
    }
    return $res;
}