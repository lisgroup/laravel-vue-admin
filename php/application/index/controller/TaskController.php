<?php

namespace app\index\controller;

use QL\QueryList;
use think\Controller;


class TaskController extends Controller
{
    /**
     * 首页展示页面
     * @return mixed
     */
    public function index()
    {
        // 1. 定义任务的 起始 - 终止 目录
        $start = '20180704';
        $end = '20181001';
        // 2， 开始循环每个目录，查找其中的文件
        /*for ($month = '07'; $month < 11; $month++) {
            for ($day = '01'; $day <= 32; $day++) {
                $dirMudu = ROOT_PATH . 'crontab/2018' . $month . $day . '/line_k1_to_mudu/';
                $dirXing = ROOT_PATH . 'crontab/2018' . $month . $day . '/line_k1_to_xingtang_/';

                // 3. 最终遍历目录下的文件
                for ($i = 1; $i <= 300; $i++) {
                    // 3.1 mudu 目录下的文件操作
                    $file = $dirMudu.$i.'.html';
                    if (file_exists($file)) {
                        echo $file.'<br>';
                    }

                    // 3.1 mudu 目录下的文件操作
                    $file2 = $dirXing.$i.'.html';
                    if (file_exists($file2)) {
                        echo $file2.'<br>';
                    }
                }
            }
        }*/
        $file = 'E:\www\vueBus\php\crontab/20181001/line_k1_to_mudu/1.html';
        $html = file_get_contents($file);

        $rules = [
            'to' => ['#MainContent_LineInfo', 'text'],  //方向
            //采集 tr 下的 td 标签的 text 文本
            'stationName' => ['#MainContent_DATA tr td:nth-child(1)', 'text'], // 站台
            'stationCode' => ['#MainContent_DATA tr td:nth-child(2)', 'text'], // 编号
            'carCode' => ['#MainContent_DATA tr td:nth-child(3)', 'text'],  // 车牌
            'ArrivalTime' => ['#MainContent_DATA tr td:nth-child(4)', 'text'], // 进站时间
        ];

        // 过程:设置HTML=>设置采集规则=>执行采集=>获取采集结果数据
        $arrayData = QueryList::html($html)->rules($rules)->query()->getData()->all();
        $to = $arrayData[0]['to'];
        unset($arrayData[0]['to']);

        $data = ['to' => $to, 'line' => $arrayData];

        $content = json_encode($data['line'], JSON_UNESCAPED_UNICODE);

        // 入库操作1 ----- 木渎  '快线1号(星塘公交中心首末站)';
        $date = date('Y-m-d H:i:s');
        $rs1 = db('cron')->insert(['line_info' => '快线1号(木渎公交换乘枢纽站)', 'content' => $content, 'create_time' => $date, 'update_time' => $date]);
        /**********************   line1  end ************************/

        return ['code' => 0, 'msg' => 'success', 'result' => $rs1];
    }
}
