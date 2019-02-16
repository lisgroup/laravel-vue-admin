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
        /*$html = new Http();
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

        $data = $arrayData->all();*/
        // $name = $arrayData[0]['line'];
        // $busLines = BusLine::where('name', 'like', $name)->get();

        // $data = [
        //     ["pName" => "快线1号", "lineID" => "10000239"],
        //     ["pName" => "木渎公交换乘枢纽站—星塘公交中心首末站", "lineID" => "10000317"],
        //     ["pName" => "快线1号"],
        //     ["pName" => "星塘公交中心首末站—木渎公交换乘枢纽站"]
        // ];

        $data = $this->returnData();
        // 遍历数据 下标奇偶数加工处理
        $line = [];
        foreach ($data as $key => $datum) {
            if (isset($datum['lineID'])) {
                $line['data'][$key]['lineID'] = $datum['lineID'];
            }
            // 偶数列，直接除 2 得到下标，奇数先减 1 再除以 2
            if ($key % 2 == 0) {
                $name = str_replace(['（', '）', '路'], ['(', ')', ''], $datum['pName']);
                $line['data'][$key / 2]['station'] = $datum['pName'];
                $line['data'][$key / 2]['expiration'] = time() + 30 * 24 * 3600;
                // $line['data'][$key / 2]['en_name'] = $name;
                $line['line'][$key / 2] = $name;
            } else {
                $line['data'][($key - 1) / 2]['station'] = $datum['pName'];
                $line['data'][($key - 1) / 2]['expiration'] = time() + 30 * 24 * 3600;
            }
        }

        if (count($line['data']) == 0) {
            return [];
        }

        // 准备入库记录操作, 需要放入队列处理
        /**
         * 循环更新和入库的思路：
         * 1. 遍历 bus_lines , 并 $needInsert = $line['data'];
         * 2. $line['data'] 中是否存在 $storage【'name'】 数据
         * 2.1 存在的情况下， 更新数据 , 并删除 $needInsert 中的值
         * 3. 遍历 $needInsert 插入数据
         */
        // 1. 步骤 1
        $needInsert = $line['data'];
        $list = BusLine::get();
        foreach ($list as $key => $storage) {

            // 2. 步骤 2
            $name = str_replace(['（', '）', '路'], ['(', ')', ''], $storage['name']);
            if (in_array($name, $line['line'])) {
                // 2.1 每个线路正常有两条数据，需处理两次
                $key = array_search($storage['name'], $line['line']);

                $res = $this->handleData($storage, $line['data'][$key]);
                unset($line['line'][$key]);
                if ($res) {
                    unset($needInsert[$key]);
                } else {
                    $key2 = array_search($storage['name'], $line['line']); // $key 补充回来
                    $line['line'][$key] = $storage['name'];
                    $res = $this->handleData($storage, $line['data'][$key2]);
                    if ($res) {
                        unset($line['line'][$key2]);
                        unset($needInsert[$key2]);
                    }
                }
            }
        }

        // 3. $needInsert 记录需要插入的数据 : 入库操作新逻辑
        $rs = BusLine::insert($needInsert);
        if (!$rs) {
            Log::error('Error--$needInsert 记录需要插入的数据失败: ', $needInsert);
        }

        return $line;
    }

    private function returnData()
    {
        return $data = array(
            0 =>
                array(
                    'pName' => '10路（东线）',
                    'lineID' => '10000113',
                ),
            1 =>
                array(
                    'pName' => '东南环立交换乘枢纽站—东南环立交换乘枢纽站',
                    'lineID' => '10000392',
                ),
            2 =>
                array(
                    'pName' => '10路（西线）',
                    'lineID' => '10002244',
                ),
            3 =>
                array(
                    'pName' => '东南环立交换乘枢纽站—东南环立交换乘枢纽站',
                    'lineID' => '10002245',
                ),
            4 =>
                array(
                    'pName' => '夜10路',
                    'lineID' => '10004234',
                ),
            5 =>
                array(
                    'pName' => '龙翔客运站—龙翔客运站',
                    'lineID' => '10004235',
                ),
            6 =>
                array(
                    'pName' => '夜10路',
                    'lineID' => '10000120',
                ),
            7 =>
                array(
                    'pName' => '龙翔客运站—龙翔客运站',
                    'lineID' => '10000199',
                ),
            8 =>
                array(
                    'pName' => '快线10号',
                    'lineID' => '10001387',
                ),
            9 =>
                array(
                    'pName' => '东山首末站—苏州站南广场公交枢纽',
                    'lineID' => '10001445',
                ),
            10 =>
                array(
                    'pName' => '快线10号',
                    'lineID' => '10000581',
                ),
            11 =>
                array(
                    'pName' => '苏州站南广场公交枢纽—东山首末站',
                    'lineID' => '10000547',
                ),
            12 =>
                array(
                    'pName' => '101路',
                    'lineID' => '10003719',
                ),
            13 =>
                array(
                    'pName' => '城北西路首末站—吴中汽车站南',
                    'lineID' => '10001442',
                ),
            14 =>
                array(
                    'pName' => '101路',
                    'lineID' => '10001378',
                ),
            15 =>
                array(
                    'pName' => '吴中汽车站南—城北西路首末站',
                    'lineID' => '10000169',
                ),
            16 =>
                array(
                    'pName' => '吴105路',
                    'lineID' => '10000345',
                ),
            17 =>
                array(
                    'pName' => '吴江汽车站站—花港路227省道口站',
                    'lineID' => '10003089',
                ),
            18 =>
                array(
                    'pName' => '吴105路',
                    'lineID' => '10001472',
                ),
            19 =>
                array(
                    'pName' => '花港路227省道口站—吴江汽车站站',
                    'lineID' => '10000362',
                ),
            20 =>
                array(
                    'pName' => '106路',
                    'lineID' => '10000037',
                ),
            21 =>
                array(
                    'pName' => '欧尚超市西—唯亭便利中心东',
                    'lineID' => '10001432',
                ),
            22 =>
                array(
                    'pName' => '106路',
                    'lineID' => '10001401',
                ),
            23 =>
                array(
                    'pName' => '唯亭便利中心东—欧尚超市西',
                    'lineID' => '10000522',
                ),
            24 =>
                array(
                    'pName' => '106路（区间）',
                    'lineID' => '10000570',
                ),
            25 =>
                array(
                    'pName' => '亭苑社区—亭苑社区',
                    'lineID' => '10003338',
                ),
            26 =>
                array(
                    'pName' => '吴106路',
                    'lineID' => '10003337',
                ),
            27 =>
                array(
                    'pName' => '吴江出口加工区北门站—新湖明珠城站',
                    'lineID' => '10002253',
                ),
            28 =>
                array(
                    'pName' => '吴106路',
                    'lineID' => '10002262',
                ),
            29 =>
                array(
                    'pName' => '新湖明珠城站—吴江出口加工区北门站',
                    'lineID' => '10000248',
                ),
            30 =>
                array(
                    'pName' => '108路',
                    'lineID' => '10001310',
                ),
            31 =>
                array(
                    'pName' => '解放西路换乘枢纽站—金陵西路厦亭家园',
                    'lineID' => '10003723',
                ),
            32 =>
                array(
                    'pName' => '108路',
                    'lineID' => '10003722',
                ),
            33 =>
                array(
                    'pName' => '金陵西路厦亭家园—解放西路换乘枢纽站',
                    'lineID' => '10003409',
                ),
            34 =>
                array(
                    'pName' => '108路（夜）',
                    'lineID' => '10003410',
                ),
            35 =>
                array(
                    'pName' => '金陵西路厦亭家园—园区人力资源中心',
                    'lineID' => '10001494',
                ),
            36 =>
                array(
                    'pName' => '108路早班车',
                    'lineID' => '10001537',
                ),
            37 =>
                array(
                    'pName' => '苏大东校区—金陵西路厦亭家园',
                    'lineID' => '10001399',
                ),
            38 =>
                array(
                    'pName' => '109路',
                    'lineID' => '10002285',
                ),
            39 =>
                array(
                    'pName' => '重元寺首末站—欧尚超市',
                    'lineID' => '10002288',
                ),
            40 =>
                array(
                    'pName' => '109路',
                    'lineID' => '10002368',
                ),
            41 =>
                array(
                    'pName' => '欧尚超市首末站—重元寺首末站',
                    'lineID' => '10002332',
                ),
            42 =>
                array(
                    'pName' => '吴109路',
                    'lineID' => '10003763',
                ),
            43 =>
                array(
                    'pName' => '流虹小区站—西湖小区南站',
                    'lineID' => '10003766',
                ),
            44 =>
                array(
                    'pName' => '吴109路',
                    'lineID' => '10003742',
                ),
            45 =>
                array(
                    'pName' => '西湖小区南站—流虹小区站',
                    'lineID' => '10003743',
                ),
            46 =>
                array(
                    'pName' => '110路（南线）',
                    'lineID' => '10003744',
                ),
            47 =>
                array(
                    'pName' => '星塘中心站—星塘中心站',
                    'lineID' => '10003745',
                ),
            48 =>
                array(
                    'pName' => '110路（北线）',
                    'lineID' => '10003746',
                ),
            49 =>
                array(
                    'pName' => '星塘中心站—星塘中心站',
                    'lineID' => '10004207',
                ),
            50 =>
                array(
                    'pName' => '136路（原100路）',
                    'lineID' => '10004206',
                ),
            51 =>
                array(
                    'pName' => '凤凰城首末站南—苏州中心',
                    'lineID' => '10004208',
                ),
            52 =>
                array(
                    'pName' => '136路（原100路）',
                    'lineID' => '10003747',
                ),
            53 =>
                array(
                    'pName' => '苏州中心—凤凰城首末站南',
                    'lineID' => '10003748',
                ),
            54 =>
                array(
                    'pName' => '吴210路',
                    'lineID' => '10003749',
                ),
            55 =>
                array(
                    'pName' => '厍港村站—梅堰农贸市场站',
                    'lineID' => '10003750',
                ),
            56 =>
                array(
                    'pName' => '吴210路',
                    'lineID' => '10003751',
                ),
            57 =>
                array(
                    'pName' => '梅堰农贸市场站—厍港村站',
                    'lineID' => '10003752',
                ),
            58 =>
                array(
                    'pName' => '310路（东线）',
                    'lineID' => '10003753',
                ),
            59 =>
                array(
                    'pName' => '东菱科技（软件学院）首末站—东菱科技西',
                    'lineID' => '10003754',
                ),
            60 =>
                array(
                    'pName' => '310路（西线）',
                    'lineID' => '10003755',
                ),
            61 =>
                array(
                    'pName' => '东菱科技西—东菱科技（软件学院）首末站',
                    'lineID' => '10003756',
                ),
            62 =>
                array(
                    'pName' => '310路',
                    'lineID' => '10004210',
                ),
            63 =>
                array(
                    'pName' => '东菱科技（软件学院）首末站站—东菱科技（软件学院）首末站站',
                    'lineID' => '10003757',
                ),
            64 =>
                array(
                    'pName' => '310路（夜）',
                    'lineID' => '10003759',
                ),
            65 =>
                array(
                    'pName' => '东菱科技（软件学院）首末站站—东菱科技（软件学院）首末站站',
                    'lineID' => '10003530',
                ),
            66 =>
                array(
                    'pName' => '汾310路',
                    'lineID' => '10003761',
                ),
            67 =>
                array(
                    'pName' => '江泽村路口站—金家坝中学站',
                    'lineID' => '10003762',
                ),
            68 =>
                array(
                    'pName' => '汾310路',
                    'lineID' => '10002423',
                ),
            69 =>
                array(
                    'pName' => '金家坝中学站—江泽村路口站',
                    'lineID' => '10002424',
                ),
            70 =>
                array(
                    'pName' => '盛310路',
                    'lineID' => '10001498',
                ),
            71 =>
                array(
                    'pName' => '平西村站—秋泽村站',
                    'lineID' => '10001525',
                ),
            72 =>
                array(
                    'pName' => '盛310路',
                    'lineID' => '10002390',
                ),
            73 =>
                array(
                    'pName' => '秋泽村站—莺湖桥站',
                    'lineID' => '10002388',
                ),
            74 =>
                array(
                    'pName' => '松310路',
                    'lineID' => '10002411',
                ),
            75 =>
                array(
                    'pName' => '叶家港站—双湾(陆家湾)站',
                    'lineID' => '10002370',
                ),
            76 =>
                array(
                    'pName' => '310路区间',
                    'lineID' => '10001437',
                ),
            77 =>
                array(
                    'pName' => '东菱科技（软件学院）首末站—东菱科技西',
                    'lineID' => '10001499',
                ),
            78 =>
                array(
                    'pName' => '310路区间',
                    'lineID' => '10002381',
                ),
            79 =>
                array(
                    'pName' => '东菱科技西—东菱科技（软件学院）首末站',
                    'lineID' => '10002452',
                ),
            80 =>
                array(
                    'pName' => '710路',
                    'lineID' => '10002416',
                ),
            81 =>
                array(
                    'pName' => '格林华城站—喜庆苑站',
                    'lineID' => '10002387',
                ),
            82 =>
                array(
                    'pName' => '710路',
                    'lineID' => '10001511',
                ),
            83 =>
                array(
                    'pName' => '喜庆苑站—格林华城站',
                    'lineID' => '10001527',
                ),
            84 =>
                array(
                    'pName' => '1001路（东线）',
                    'lineID' => '10003145',
                ),
            85 =>
                array(
                    'pName' => '景城邻里中心首末站—景城邻里中心首末站',
                    'lineID' => '10002342',
                ),
            86 =>
                array(
                    'pName' => '1001路（西线）',
                    'lineID' => '10002344',
                ),
            87 =>
                array(
                    'pName' => '景城邻里中心首末站—景城邻里中心首末站',
                    'lineID' => '10002338',
                ),
            88 =>
                array(
                    'pName' => '1002路',
                    'lineID' => '10002426',
                ),
            89 =>
                array(
                    'pName' => '钟南街首末站南—钟南街首末站南',
                    'lineID' => '10003370',
                ),
            90 =>
                array(
                    'pName' => '1003路',
                    'lineID' => '10003162',
                ),
            91 =>
                array(
                    'pName' => '杏秀桥首末站—杏秀桥首末站',
                    'lineID' => '10002280',
                ),
            92 =>
                array(
                    'pName' => '1005路',
                    'lineID' => '10002365',
                ),
            93 =>
                array(
                    'pName' => '星港街东首末站—华林幼儿园站',
                    'lineID' => '10003394',
                ),
            94 =>
                array(
                    'pName' => '1005路',
                    'lineID' => '10003080',
                ),
            95 =>
                array(
                    'pName' => '华林幼儿园站—星港街东首末站',
                ),
            96 =>
                array(
                    'pName' => '1006路',
                ),
            97 =>
                array(
                    'pName' => '国际科技园停车场—国际科技园停车场',
                ),
            98 =>
                array(
                    'pName' => '1007路',
                ),
            99 =>
                array(
                    'pName' => '新光天地—艺术中心',
                ),
            100 =>
                array(
                    'pName' => '1007路',
                ),
            101 =>
                array(
                    'pName' => '艺术中心—商旅大厦',
                ),
            102 =>
                array(
                    'pName' => '1008路',
                ),
            103 =>
                array(
                    'pName' => '钟南花苑西（西侧）—钟南花苑西（东侧）',
                ),
            104 =>
                array(
                    'pName' => '1009路',
                ),
            105 =>
                array(
                    'pName' => '津梁街首末站—津梁街首末站',
                ),
            106 =>
                array(
                    'pName' => '1021路',
                ),
            107 =>
                array(
                    'pName' => '独墅湖邻里中心公交首末站—独墅湖邻里中心公交首末站',
                ),
            108 =>
                array(
                    'pName' => '1022路',
                ),
            109 =>
                array(
                    'pName' => '星湖公馆—月亮湾首末站',
                ),
            110 =>
                array(
                    'pName' => '1026路（原190路）',
                ),
            111 =>
                array(
                    'pName' => '独墅湖邻里中心公交首末站—独墅湖邻里中心西',
                ),
            112 =>
                array(
                    'pName' => '1051路',
                ),
            113 =>
                array(
                    'pName' => '金陵西路停车场—金陵西路厦亭家园',
                ),
            114 =>
                array(
                    'pName' => '1052路',
                ),
            115 =>
                array(
                    'pName' => '星华街游客中心首末站—星华街游客中心首末站',
                ),
            116 =>
                array(
                    'pName' => '1053路',
                ),
            117 =>
                array(
                    'pName' => '星湖医院—星湖医院',
                ),
            118 =>
                array(
                    'pName' => '1055路（南线）',
                ),
            119 =>
                array(
                    'pName' => '星湖医院站—星湖医院站',
                ),
            120 =>
                array(
                    'pName' => '1055路（北线）',
                ),
            121 =>
                array(
                    'pName' => '星湖医院—星湖医院',
                ),
            122 =>
                array(
                    'pName' => '1071路（原181路）',
                ),
            123 =>
                array(
                    'pName' => '星塘公交中心—星塘公交中心',
                ),
            124 =>
                array(
                    'pName' => '1072路（原209路）',
                ),
            125 =>
                array(
                    'pName' => '钟南街首末站南站—滨江苑站',
                ),
            126 =>
                array(
                    'pName' => '1072路（原209路）',
                ),
            127 =>
                array(
                    'pName' => '滨江苑站—钟南街首末站南',
                ),
            128 =>
                array(
                    'pName' => '1073路',
                ),
            129 =>
                array(
                    'pName' => '钟南街首末站南—钟南街首末站南',
                ),
            130 =>
                array(
                    'pName' => '1075路（原高峰12号）',
                ),
            131 =>
                array(
                    'pName' => '园区综合保税区东区首末站—吴淞商业广场',
                ),
            132 =>
                array(
                    'pName' => '1076路（原高峰11号）',
                ),
            133 =>
                array(
                    'pName' => '东坊工业园—东坊工业园',
                ),
            134 =>
                array(
                    'pName' => '1077路（原高峰10号）',
                ),
            135 =>
                array(
                    'pName' => '滨江苑—滨江苑',
                ),
            136 =>
                array(
                    'pName' => '7101路',
                ),
            137 =>
                array(
                    'pName' => '叶建村站—九里湖(后浜)站',
                ),
            138 =>
                array(
                    'pName' => '7101路',
                ),
            139 =>
                array(
                    'pName' => '九里湖(后浜)站—叶建村站',
                ),
            140 =>
                array(
                    'pName' => '7102路',
                ),
            141 =>
                array(
                    'pName' => '同里汽车站—松汾路口站',
                ),
            142 =>
                array(
                    'pName' => '7102路',
                ),
            143 =>
                array(
                    'pName' => '松汾路口站—同里汽车站',
                ),
            144 =>
                array(
                    'pName' => '7103路',
                ),
            145 =>
                array(
                    'pName' => '肖甸湖—同里汽车站',
                ),
            146 =>
                array(
                    'pName' => '7103路',
                ),
            147 =>
                array(
                    'pName' => '同里汽车站—肖甸湖',
                ),
            148 =>
                array(
                    'pName' => '7105路',
                ),
            149 =>
                array(
                    'pName' => '同里汽车站—旺塔',
                ),
            150 =>
                array(
                    'pName' => '7105路',
                ),
            151 =>
                array(
                    'pName' => '旺塔—同里汽车站',
                ),
            152 =>
                array(
                    'pName' => '7106路',
                ),
            153 =>
                array(
                    'pName' => '练聚村站—石铁村(横港)站',
                ),
            154 =>
                array(
                    'pName' => '7106路',
                ),
            155 =>
                array(
                    'pName' => '石铁村(横港)站—练聚村站',
                ),
            156 =>
                array(
                    'pName' => '7106路',
                ),
            157 =>
                array(
                    'pName' => '石铁村(横港)站—练聚村站',
                ),
            158 =>
                array(
                    'pName' => '7106路',
                ),
            159 =>
                array(
                    'pName' => '练聚村站—石铁村(横港)站',
                ),
            160 =>
                array(
                    'pName' => '7107路',
                ),
            161 =>
                array(
                    'pName' => '同里汽车站—肖甸湖北',
                ),
            162 =>
                array(
                    'pName' => '7107路',
                ),
            163 =>
                array(
                    'pName' => '肖甸湖北—同里汽车站',
                ),
            164 =>
                array(
                    'pName' => '7108路',
                ),
            165 =>
                array(
                    'pName' => '新营村站—农创村(变电所)站',
                ),
            166 =>
                array(
                    'pName' => '7108路',
                ),
            167 =>
                array(
                    'pName' => '农创村(变电所)站—新营村站',
                ),
            168 =>
                array(
                    'pName' => '7108路',
                ),
            169 =>
                array(
                    'pName' => '新营村站—农创村(变电所)站',
                ),
            170 =>
                array(
                    'pName' => '7110路',
                ),
            171 =>
                array(
                    'pName' => '叶家港站—大家港村道杨家扇临时首末站',
                ),
            172 =>
                array(
                    'pName' => '7110路',
                ),
            173 =>
                array(
                    'pName' => '大家港村道杨家扇临时首末站—叶家港站',
                ),
            174 =>
                array(
                    'pName' => '7210路',
                ),
            175 =>
                array(
                    'pName' => '平望汽车站—秋泽村站',
                ),
            176 =>
                array(
                    'pName' => '7210路',
                ),
            177 =>
                array(
                    'pName' => '秋泽村站—平望汽车站',
                ),
            178 =>
                array(
                    'pName' => '7310路',
                ),
            179 =>
                array(
                    'pName' => '金家坝中学站—江泽村路口站',
                ),
            180 =>
                array(
                    'pName' => '7510路',
                ),
            181 =>
                array(
                    'pName' => '高家埭站—实验小学（新城小区）站',
                ),
            182 =>
                array(
                    'pName' => '7610路',
                ),
            183 =>
                array(
                    'pName' => '西路口站—船菜港站',
                ),
            184 =>
                array(
                    'pName' => '7610路',
                ),
            185 =>
                array(
                    'pName' => '船菜港站—西路口站',
                ),
            186 =>
                array(
                    'pName' => '7610路',
                ),
            187 =>
                array(
                    'pName' => '七都农贸市场站—船菜港站',
                ),
            188 =>
                array(
                    'pName' => '9010路',
                ),
            189 =>
                array(
                    'pName' => '南环桥（华东装饰城）—华东装饰城',
                ),
        );
    }

    /**
     * 处理数据
     *
     * @param $storage
     * @param $value
     * @return bool
     */
    private function handleData($storage, $value)
    {
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
                    return false;
                }
                return true;
            }
        }
        return false;
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
