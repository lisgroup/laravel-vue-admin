<?php
/**
 * PHP 定时任务 -- 每天5点开始抓取实时公交（为统计公交运行时间准备）
 * User: lisgroup
 * Time: 2017/10/29  22:06
 */

include_once __DIR__ .'/function.php';

// 1.线路 快线1号(木渎公交换乘枢纽站)
$url = 'http://www.szjt.gov.cn/BusQuery/APTSLine.aspx?cid=175ecd8d-c39d-4116-83ff-109b946d7cb4&LineGuid=921f91ad-757e-49d6-86ae-8e5f205117be&LineInfo=快线1号(星塘公交中心首末站)';

$i = 1;
do {
    // 2. 实时公交返回的网页数据
    $startTime = microtime(true);
    $line = httpGet($url);
    $spendTime = microtime(true) - $startTime;

    if ($line) {
        // 3. 将数据按照时间顺序排序存储数据
        $fileName = __DIR__ . date('/Ymd/') . 'line_k1_to_xingtang_/' . $i . '.html';
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
