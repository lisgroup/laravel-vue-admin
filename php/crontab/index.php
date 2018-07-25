<?php
/**
 * PHP 定时任务 -- 每天5点开始抓取实时公交（为统计公交运行时间准备）
 * User: lisgroup
 * Time: 2017/10/29  22:06
 */

include_once __DIR__ . '/function.php';

// 1.线路 快线1号(木渎公交换乘枢纽站)
$url = 'http://www.szjt.gov.cn/BusQuery/APTSLine.aspx?cid=175ecd8d-c39d-4116-83ff-109b946d7cb4&LineGuid=af9b209b-f99d-4184-af7d-e6ac105d8e7f&LineInfo=%E5%BF%AB%E7%BA%BF1%E5%8F%B7(%E6%9C%A8%E6%B8%8E%E5%85%AC%E4%BA%A4%E6%8D%A2%E4%B9%98%E6%9E%A2%E7%BA%BD%E7%AB%99)';

$i = 1;
do {
    // 2. 实时公交返回的网页数据
    $startTime = microtime(true);
    $line = httpGet($url);
    $spendTime = microtime(true) - $startTime;

    if ($line) {
        // 3. 将数据按照时间顺序排序存储数据
        $fileName = __DIR__ . date('/Ymd/') . 'line_k1_to_mudu/' . $i . '.html';
        $dir = dirname($fileName);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $mark = '<!-- ' . date('Y-m-d H:i:s') . ' Spend:[' . $spendTime . '] -->' . PHP_EOL;
        $result = file_put_contents($fileName, $mark . $line);
    }

    $i++;

    if (date('Hi') > 2200) {
        $i = 0;
    }

    sleep(200);
} while ($i > 0);
