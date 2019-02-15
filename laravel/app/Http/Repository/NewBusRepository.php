<?php
/**
 * Desc: BusRepository 仓库类
 * User: lisgroup
 * Date: 18-10-03
 * Time: 15:50
 */

namespace App\Http\Repository;

use App\Models\BusLine;
use Curl\Http;
use Illuminate\Support\Facades\Log;
use QL\QueryList;

class NewBusRepository
{
    /**
     * @var mixed|QueryList 实例
     */
    protected $ql;
    /**
     * @var string $url 定义采集的url
     */
    protected $url = 'http://bus.2500.tv';

    /**
     * @var self 单例
     */
    private static $instance;

    // private $cronModel;

    /**
     * @param array $conf
     *
     * @return NewBusRepository
     */
    public static function getInstent($conf = [])
    {
        if (!isset(self::$instance)) {
            self::$instance = new static($conf);
        }
        return self::$instance;
    }

    /**
     * 1. 采集所有线路的 lineID
     * 地址： http://bus.2500.tv/line.php?line=
     *
     * @param string $line
     *
     * @return array
     */
    public function getLineID($line)
    {
        $html = new Http();
        $result = $html->get($this->url.'/line.php', ['line' => $line]);
        // $result['content'] = $this->html();

        $queryList = QueryList::html($result['content']);

        // 1.2 自定义采集规则
        $rules = [
            // 采集元素里面的纯文本内容
            // 'lineid' => ['.stationList>a', 'lineid'],
            // 'line' => ['.tMsg', 'text'],
            // 'station' => ['.istationList>p:gt(1)', 'text'],
            'pName' => ['.istationList>p', 'text'],
            'lineID' => ['.star1', 'lineid'],
        ];
        //1.3. 开始采集 -- 作为发送数据的基础
        // $ql = QueryList::getInstance();
        $arrayData = $queryList->rules($rules)->query()->getData();
        // $arrayData = QueryList::Query($html, $rules)->data;
        if (empty($arrayData)) {
            return [];
        }

        $data = $arrayData->all();
        // $name = $arrayData[0]['line'];
        // $busLines = BusLine::where('name', 'like', $name)->get();

        // $data = [
        //     ["pName" => "快线1号", "lineID" => "10000239"],
        //     ["pName" => "木渎公交换乘枢纽站—星塘公交中心首末站", "lineID" => "10000317"],
        //     ["pName" => "快线1号"],
        //     ["pName" => "星塘公交中心首末站—木渎公交换乘枢纽站"]
        // ];

        // 遍历数据 下标奇偶数加工处理
        $line = [];
        foreach ($data as $key => $datum) {
            if (isset($datum['lineID'])) {
                $line[$key]['lineID'] = $datum['lineID'];
            }
            // 偶数列，直接除 2 得到下标，奇数先减 1 再除以 2
            if ($key % 2 == 0) {
                $name = str_replace(['（', '）', '路'], ['(', ')', ''], $datum['pName']);
                $line[$key / 2]['name'] = $datum['pName'];
                $line[$key / 2]['en_name'] = $name;
            } else {
                $line[($key - 1) / 2]['station'] = $datum['pName'];
            }
        }

        if (count($line) == 0) {
            return [];
        }

        // 准备入库记录操作, 需要放入队列处理
        $list = BusLine::get();
        foreach ($list as $key => $storage) {

            $i = 0;
            foreach ($line as $value) {
                if ($storage['name'] == $value['en_name']) {
                    $arr = explode('—', $value['station']);
                    // bus_lines 表的 FromTo 字段是否存在 $value['station'] 元素
                    $end = end($arr);
                    if (strpos($storage['FromTo'], $end) !== false) {
                        $databaseEnd = explode('—', $storage['FromTo']);
                        if (end($databaseEnd) == $end) {
                            // — 符号最后元素相同的，满足条件更新数据库
                            $storage->station = $value['station'];
                            $storage->lineID = $value['lineID'];
                            $rs = $storage->save();

                            if (!$rs) {
                                Log::error('error--ID: '.$storage['id'], $value);
                            }
                        }
                    }
                } else {
                    $i++;
                }
            }

            // TODO: 入库逻辑问题待处理
            if ($key == 0 && count($line) == $i) {
                // insert 操作
                $value['name'] = $value['en_name'];
                unset($value['en_name']);
                $value['expiration'] = time() + 30 * 24 * 3600;
                $rs = BusLine::insert($value);
                if (!$rs) {
                    Log::error('error--ID: '.$storage['id'], $value);
                }

            }
        }

        return $line;
    }


    /**
     * 替换字符串
     */
    public function replace()
    {
        $list = BusLine::get();
        foreach ($list as $l) {
            $replace = str_replace(['（', '）', '－', '-', '—', '=&gt;'], ['(', ')', '—', '—', '—', '—'], $l['FromTo']);
            if (in_array($replace, ['东线', '西线', '南线', '北线'])) {
                $replace = $l['LineInfo'];
            }

            $l->FromTo = $replace;
            $rs = $l->save();

            // $rs = BusLine::where('id', $l['id'])->update(['FromTo' => $replace]);
            if (!$rs) {
                Log::error('error--ID: '.$l['id']);
            }
        }
    }

    /**
     * BusRepository constructor.
     *
     *
     * @param $config
     */
    private function __construct($config)
    {
        //$this->ql || $this->ql = QueryList::getInstance();
    }

    /**
     * 不允许 clone
     */
    private function __clone()
    {

    }

    /**
     * @return float
     */
    public function randomTime()
    {
        return (float)sprintf('%.0f', microtime(true) * 1000);
    }
}
