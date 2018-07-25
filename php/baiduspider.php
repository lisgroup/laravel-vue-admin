<?php

//百度站长工具的token值
$token = 'NsygfTSkxgY1FQ**';

//百度蜘蛛数据的推送地址
//1.推送
$api = 'http://data.zz.baidu.com/urls?site=www.guke1.com&token='.$token;
//2.更新
//$api = 'http://data.zz.baidu.com/update?site=www.guke1.com&token='.$token;
//3.删除数据
//$api = 'http://data.zz.baidu.com/del?site=www.guke1.com&token='.$token;

$url = '';
//1.文章提交
for ($i=1;$i < 11; $i++) {
    //$url .= 'http://www.guke1.com/article/'.$i.".html\n";
    //2.栏目提交
    $url .= 'http://www.guke1.com/list/'.$i . ".html\n";
}
$url = rtrim($url, "\n");

$ch = curl_init();
$options = [
    CURLOPT_URL             =>      $api,
    CURLOPT_POST            =>      true,
    CURLOPT_RETURNTRANSFER  =>      true, //return transfer
    CURLOPT_POSTFIELDS      =>      $url,
    CURLOPT_HTTPHEADER      =>      array('Content-Type: text/plain'),
];
//设置参数
curl_setopt_array($ch, $options);
//执行函数
$rs = curl_exec($ch);
echo $rs;

