<?php
/**
 * Desc: TaskRepository 仓库类
 * User: lisgroup
 * Date: 18-11-03
 * Time: 22:06
 */

namespace App\Http\Repository;


use QL\QueryList;

class TaskRepository
{
    /**
     * @var mixed|QueryList 采集类
     */
    protected $ql;
    /**
     * @var string $url 定义采集的 url
     */
    protected $url = '';

    private static $instance;

    // private $cronModel;

    public static function getInstent($conf = [])
    {
        if (!isset(self::$instance) || !(self::$instance instanceof TaskRepository)) {
            self::$instance = new static($conf);
        }
        return self::$instance;
    }


    /**
     * 抓取任务地址: http://bus.suzhou.bendibao.com/linelist/2.htm
     * 目标是获取页面中所有的线路信息
     */
    public function lineList()
    {
        $file = __DIR__.'/line.html';
        $html = file_get_contents($file);
        // 手动转码
        // $html = iconv('GBK', 'UTF-8', $html);
        // print_r($html);
        $ql = $this->ql->html($html);
        // 1. 元数据采集规则
        $rules = [
            'line' => ['#listall li>a', 'text'],
            'href' => ['#listall li>a', 'href']
        ];
        $result = $ql->rules($rules)->encoding('UTF-8','GB2312')->removeHead()->queryData();
        var_dump($result[0]);

        // 2. 采集详细车次线路信息
        $url = 'http://bus.suzhou.bendibao.com';
        $listInfo = $this->ql->get($url.$result[0]['href']);
        $rules = [
            'name' => ['#rpt_Line_List_ctl00_lk_Line', 'text'],
            'open_time' => ['#rpt_Line_List_ctl00_lb_YYSJ>span', 'text'], // 营运时间
            'depart_time' => ['#rpt_Line_List_ctl00_lb_FCJG>span', 'text'], // 发车间隔
            'price' => ['#rpt_Line_List_ctl00_lb_Price>span', 'text'],
            'company' => ['#rpt_Line_List_ctl00_lb_ComName>span', 'text'], // 公交公司
            'station' => ['#rpt_Line_List_ctl00_lb_StationAll1', 'text'], // 去程
            'station_back' => ['#rpt_Line_List_ctl00_lb_StationAll2', 'text'], // 返程
            'last_update' => ['#rpt_Line_List_ctl00_lb_UpdateTime', 'text'], // 最后更新日期
        ];
        $rs = $listInfo->rules($rules)->encoding('UTF-8', 'GB2312')->removeHead()->queryData();
        var_dump($rs);
    }


    /**
     * TaskRepository constructor.
     * @param $config
     */
    private function __construct($config)
    {
        if (!empty($config)) {
            foreach ($config as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->ql || $this->ql = QueryList::getInstance();
    }

    private function __clone()
    {

    }
}