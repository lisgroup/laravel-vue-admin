<?php
/**
 * Desc: TaskRepository 仓库类
 * User: lisgroup
 * Date: 18-11-03
 * Time: 22:06
 */

namespace App\Http\Repository;


use App\Models\CronTask;
use App\Models\Line;
use Illuminate\Support\Facades\Log;
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
        if (PHP_SAPI != 'cli') {
            return ['code' => 1, 'msg' => 'error'];
        }

        // 正常需抓取页面列表，下面示例只是简单操作
        $urls = [
            'http://bus.suzhou.bendibao.com/linelist/1.htm',
            'http://bus.suzhou.bendibao.com/linelist/2.htm',
        ];
        foreach ($urls as $url) {
            // $url = 'http://bus.suzhou.bendibao.com/linelist/1.htm';
            // $ql = $this->ql->get($url);
            // 手动转码
            // $html = iconv('GBK', 'UTF-8', $html);
            // print_r($html);
            // $url = 'http://bus.suzhou.bendibao.com/linelist/2.htm';
            $ql = $this->ql->get($url);
            // 1. 元数据采集规则
            $rules = [
                'line' => ['#listall li>a', 'text'],
                'href' => ['#listall li>a', 'href']
            ];
            $result = $ql->rules($rules)->encoding('UTF-8', 'GB2312')->removeHead()->queryData();
            // var_dump($result[0]);exit();

            // 2. 采集详细车次线路信息
            $url = 'http://bus.suzhou.bendibao.com';
            foreach ($result as $value) {
                $listInfo = $this->ql->get($url.$value['href']);
                $try = 1;
                // 如果获取数据失败，再尝试2次
                while (empty($listInfo)) {
                    if ($try >= 3) {
                        break;
                    }
                    $listInfo = $this->ql->get($url.$value['href']);

                    $try++;
                }

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
                if (!empty($rs)) {
                    $line = $rs[0];
                    $line['station'] = str_replace([" ", "　", "\n", "\r", "\t"], ['', '', '', '', ''], $line['station']);
                    $line['station_back'] = str_replace([" ", "　", "\n", "\r", "\t"], ['', '', '', '', ''], $line['station_back']);
                    // 抓取的数据入库操作
                    // $model = new Line($line);
                    // $model->save();
                    $day = date('Y-m-d H:i:s');
                    // 2. 插入前判断是否已经存在该线路
                    $getLine = Line::where('name', $line['name'])->first();
                    if ($getLine) {
                        // 2.1 update 操作
                        $line += ['updated_at' => $day];
                        if (Line::where('id', $getLine['id'])->update($line)) {
                            Log::info('Lines 更新执行成功 success: 线路名称 '.$line['name']);
                        } else {
                            Log::error('Lines 更新执行失败 error: 线路名称 '.$line['name'], $line);
                        }
                    } else {
                        // 2.2 insert 操作
                        $line += ['created_at' => $day, 'updated_at' => $day];
                        if (Line::insert($line)) {
                            Log::info('Lines 入库执行成功 success: 线路名称 '.$line['name']);
                        } else {
                            Log::error('Lines 入库执行失败 error: 线路名称 '.$line['name'], $line);
                        }
                    }
                } else {
                    Log::error('Lines 线路获取失败 error: 线路 URL '.$url.$value['href']);
                }
                // sleep 200ms
                usleep(200000);
            }
        }
        return ['code' => 0, 'msg' => 'success'];
    }


    /**
     * 任务： 从原库 line 数据，抓取实时公交写入 bus_lines
     * 作用： 为以后直接查询实时公交提供方便
     *
     * @return array
     */
    public static function line()
    {
        if (PHP_SAPI != 'cli') {
            return ['code' => 1, 'msg' => 'error'];
        }

        // 1. 读取数据
        Line::chunk(100, function($lines) {
            foreach ($lines as $key => $line) {
                // 1001路（景城邻里中心首末站 - 景城邻里中心首末站）
                $name = str_replace(['路'], [''], mb_substr($line->name, 0, mb_strpos($line['name'], '（')));
                echo $name.PHP_EOL;
                BusRepository::getInstent()->getList($name);
                usleep(100000);
            }
        });
        return ['code' => 0, 'msg' => 'success'];
    }

    /**
     * 写入任务表 cron_task 的操作
     *
     * @param $input
     *
     * @return array
     */
    public function saveSearchCronTask($input)
    {
        $result = ['code' => 1104, 'reason' => '参数错误', 'data' => ''];
        if (empty($input)) {
            return $result;
        }
        $data = [];
        foreach ($input as $item) {
            $item['start_at'] = '05:00:00';
            $item['end_at'] = '23:00:00';
            $model = new CronTask($item);
            if ($model->save()) {
                $data['ids'][] = $model->id;
            } else {
                $result['code'] = 5000;
                $result['reason'] = 'insert error';
                return $result;
            }
        }
        return ['code' => 200, 'data' => $data];
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
