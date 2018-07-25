<?php
use think\Cookie;
// 应用公共文件
/**
 * 是否需要延迟访问
 */
function sleep_visit() {
    $last_visit = Cookie::get('lastVisit'); //上次访问时间
    $now = time(); //现在的时间戳
    if ($now -  $last_visit < 1) sleep(1);
    Cookie::set('lastVisit', $now);
}
/**
 * 判断是否为Windows服务器
 * @return bool
 */
function is_win() {
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
        return true;
    else
        return false;
}

/**
 * http请求获取网页源数据
 * @param $url
 * @param bool $ssl             是否https协议
 * @return bool|mixed|string
 */
function httpGet($url, $ssl = false) {
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

    if(empty($res)) {
        $res = file_get_contents($url);
    }
    return $res;
}

/**
 * post提交数据的方案一
 * @param $url              //地址
 * @param string $data      $data = "app=request&version=beta";
 * @param string $cookie
 * @return bool|mix|string
 */
function f_post($url,$data='',$cookie=''){
    // 模拟提交数据函数
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $data,
        ),
    );
    return file_get_contents($url, false, stream_context_create($options));
}

/**
 * post提交数据的方案一
 * @param $url
 * @param string $data
 * @param string $cookie
 * @return mixed
 */
function c_post($url, $data='', $cookie='') {

    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    curl_setopt($curl, CURLOPT_REFERER,'');// 设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno'.curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}