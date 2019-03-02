<?php

// Git WebHooks 自动部署脚本
// 项目存放的路径
$path = "../..";
$requestBody = file_get_contents("php://input");
if (empty($requestBody)) {
    die('send fail');
}
$content = json_decode($requestBody, true);
// 若是主分支且提交数大于 0 请修改为 refs/heads/master
if ($content['ref'] == 'refs/heads/develop' && $content['total_commits_count'] > 0) {
    $res = shell_exec("cd {$path} && git pull 2>&1");// 以 www 用户运行
    $res_log = '-------------------------'.PHP_EOL;
    $res_log .= $content['user_name'].' 在'.date('Y-m-d H:i:s').'向'.$content['repository']['name'].'项目的'.$content['ref'].'分支push了'.$content['total_commits_count'].'个commit：'.PHP_EOL;
    $res_log .= $res.PHP_EOL;
    var_dump($res_log);
    file_put_contents("git-webhook.txt", $res_log, FILE_APPEND);//追加写入
}
echo '很棒:'.date('y-m-d H:i:s');
