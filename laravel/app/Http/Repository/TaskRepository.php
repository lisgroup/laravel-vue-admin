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
            'line_href' => ['#listall li>a', 'href']
        ];
        $result = $ql->rules($rules)->encoding('UTF-8','GB2312')->removeHead()->queryData();
        var_dump($result);
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