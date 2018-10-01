<?php
/**
 * Desc: Bus 获取
 * User: lisgroup
 * Date: 17-12-29
 * Time: 下午1:52
 */

namespace app\index\repository;


use app\index\model\Cron;
use QL\QueryList;
use think\Cache;
use think\Cookie;

class BusRepository
{
    /**
     * @var mixed|QueryList
     */
    protected $ql;
    /**
     * @var string $url 定义采集的url
     */
    protected $url = 'http://www.szjt.gov.cn/BusQuery/APTSLine.aspx?cid=175ecd8d-c39d-4116-83ff-109b946d7cb4';

    private static $instance;

    public static function getInstent($conf = [])
    {
        if (!isset(self::$instance)) {
            self::$instance = new static($conf);
        }
        return self::$instance;
    }

    /**
     * 1. 设置查询线路的 cookie
     * @param $line
     */
    public function setLineCookie($line)
    {
        //17年5月9日新增 搜索历史的功能 写入cookie的操作
        $cookie_line = Cookie::get('cookie_line');
        if (!is_array($cookie_line)) {
            $cookie_line = [];
            Cookie::set('cookie_line', []);
        }
        //17年5月11修复最新搜索排序在最后的问题。//array_push($cookie_line, $line); //合并数据 $cookie_line = array_unique($cookie_line); //去除重复
        //1.先搜索数组中是否存在元素,搜索到后删除，然后合并 ,修复$i = 0 的bug
        if (($i = array_search($line, $cookie_line)) !== false) unset($cookie_line[$i]);
        array_unshift($cookie_line, $line);

        if (count($cookie_line) > 3) array_pop($cookie_line); //删除最后的元素

        // setcookie('cookie_line', serialize($cookie_line), time() + 3600 * 24 * 30);
        //$response->setCookie('cookie_line', serialize($cookie_line), time() + 3600 * 24 * 30);
        Cookie::set('cookie_line', $cookie_line);
        // Cookie::set('cookie_line', $cookie_line);
    }

    /**
     * 2. 获取 Token 的方法
     * @param bool $refresh
     * @return array|bool
     */
    public function getToken($refresh = false)
    {
        /**************     18-01-10 修改获取表单 token 的方法         **************/
        if ($refresh || !$data = Cache::get('token')) {
            // 1.1 获取提交表单的 token  //缓存data post表单提交参数数据
            //缓存公交线标题提交的参数信息（默认缓存一天）
            /*if (file_exists(ROOT_PATH.'/runtime/bus.html') && filemtime(ROOT_PATH.'/runtime/bus.html') + 86400 > time()) {
                $html = file_get_contents(ROOT_PATH.'/runtime/bus.html');
            } else {
                //重新生成缓存文件
                $html = httpGet($url);
                file_put_contents(ROOT_PATH.'/runtime/bus.html', $html);
            }*/
            /**
             * 18年5月15日更新版本
             * $html = httpGet($this->url);
             * $arrayData = $this->ql->html($html)->rules($rules)->query()->getData();
             */
            QueryList::get($this->url);
            $queryList = QueryList::get($this->url);

            // 1.2 自定义采集规则
            $rules = [
                //采集id为__VIEWSTATE这个元素里面的纯文本内容
                '__VIEWSTATE' => array('#__VIEWSTATE', 'value'),
                '__VIEWSTATEGENERATOR' => array('#__VIEWSTATEGENERATOR', 'value'),
                '__EVENTVALIDATION' => array('#__EVENTVALIDATION', 'value'),
                //'ctl00$MainContent$LineName' => array('#MainContent_LineName','value'),         //线路番号
                //'ctl00$MainContent$SearchLine' => array('#MainContent_SearchLine','value'),     //搜索
            ];
            //1.3. 开始采集 -- 作为发送数据的基础
            // $ql = QueryList::getInstance();
            $arrayData = $queryList->rules($rules)->query()->getData();
            // $arrayData = QueryList::Query($html, $rules)->data;
            if (!isset($arrayData[0])) {
                // $this->response()->write('网络异常，请稍后重试');
                return false;
            }
            $data = $arrayData[0];

            // cache('inputData', $data, 86400);

            Cache::set('token', $data, 86400);
        }

        return $data;
    }

    /**
     * 3. 发送 POST 请求获取查询的线路列表
     * @param $line
     * @param $data
     * @param bool $refresh
     * @return array
     */
    private function getPostBusList($line, $data, $refresh = false)
    {
        /**************************    2. 模拟表单请求获取查询线路列表     ****************************/
        is_dir(ROOT_PATH . '/runtime/bus') || mkdir(ROOT_PATH . '/runtime/bus', 0777, true);

        // 2.0 判断是否已经有此条线路搜索
        if ($refresh || !file_exists(ROOT_PATH . '/runtime/bus/serialize_' . $line . '.txt')) {

            //2.2 文件不存在，模拟表单提交 //POST curl 模拟表单提交数据

            /**
             * 18年5月15日更新版本
             * $c_post = http($this->url, $data, true); //线路列表
             * $arrayData = $this->ql->html($c_post)->rules($rules)->query()->getData();
             */
            $queryList = QueryList::post($this->url, $data);

            // 根据线路信息，替换其中的 a 标签
            /*$rules = [
                'content' => array('#MainContent_DATA', 'html')
            ];*/
            $rules = [
                //采集a标签的href属性
                'link' => ['#MainContent_DATA tr td a', 'href'],
                //采集a标签的text文本
                'bus' => ['#MainContent_DATA tr td a', 'text'],
                //采集 tr 下第二个 td 标签的 text文本
                'FromTo' => ['#MainContent_DATA tr td:nth-child(2)', 'text']
            ];
            // $arrayData = QueryList::Query($c_post, $rules)->data;
            $arrayData = $queryList->rules($rules)->query()->getData();
            $str = serialize($arrayData->all());
            //缓存 此条线路替换a标签的数据
            $fileName = ROOT_PATH . '/runtime/bus/serialize_' . $line . '.txt';
            file_put_contents($fileName, $str);
            //抛出异常if (!$rs)
        } else {
            // 2.1 文件存在直接读取
            $serialize = file_get_contents(ROOT_PATH . '/runtime/bus/serialize_' . $line . '.txt');//线路列表
            $arrayData = unserialize($serialize);
        }

        return $arrayData;
    }

    /**
     * 查询公交如：快线1号 展示列表
     * @param string $line
     * @param bool $refresh 是否强制更新结果
     * @return array
     */
    public function getList($line, $refresh = false)
    {
        // 1. 设置线路查询的 cookie
        $this->setLineCookie($line);

        // 2. 获取 Token
        $data = $this->getToken();

        $listData = [];
        if ($data) {
            // 1.4 构造请求参数
            $input = [
                'ctl00$MainContent$LineName' => $line,        //线路番号
                'ctl00$MainContent$SearchLine' => '搜索',     //搜索
            ];
            //merge 用户输入的线路数据
            $data = array_merge($data, $input);

            // 3. 获取线路列表
            $listData = $this->getPostBusList($line, $data, $refresh);
        }


        return $listData;
    }


    /**
     * 获取实时公交站台数据 table 列表
     * @param $path
     * @param $get
     * @return array|bool
     */
    public function getLine($path, $get)
    {
        if (empty($path) || empty($get['cid']) || empty($get['LineGuid']) || empty($get['LineInfo']))
            return false;
        $paramString = http_build_query($get);
        $url = 'http://www.szjt.gov.cn/BusQuery/' . $path . '?' . $paramString;
        //实时公交返回的网页数据
        $queryList = QueryList::get($url);

        /*$rules = [
            'to' => ['#MainContent_LineInfo', 'text'],  //方向
            'content' => ['#MainContent_DATA', 'html']       //具体线路table表格
        ];*/

        $rules = [
            'to' => ['#MainContent_LineInfo', 'text'],  //方向
            //采集 tr 下的 td 标签的 text 文本
            'stationName' => ['#MainContent_DATA tr td:nth-child(1)', 'text'], // 站台
            'stationCode' => ['#MainContent_DATA tr td:nth-child(2)', 'text'], // 编号
            'carCode' => ['#MainContent_DATA tr td:nth-child(3)', 'text'],  // 车牌
            'ArrivalTime' => ['#MainContent_DATA tr td:nth-child(4)', 'text'], // 进站时间
        ];

        //$arrayData = QueryList::Query($line, $rules)->data;
        $arrayData = $queryList->rules($rules)->query()->getData()->all();
        $to = $arrayData[0]['to'];
        unset($arrayData[0]['to']);

        return ['to' => $to, 'line' => $arrayData];
    }


    /**
     * @return int|string
     */
    public function getCronSave()
    {
        $post = [
            'cid' => '175ecd8d-c39d-4116-83ff-109b946d7cb4',
            'LineGuid' => 'af9b209b-f99d-4184-af7d-e6ac105d8e7f',
            'LineInfo' => '快线1号(木渎公交换乘枢纽站)',
        ];
//        $data = $this->getLine('APTSLine.aspx', $post)['line'];
//        $content = json_encode($data, JSON_UNESCAPED_UNICODE);
        $content = '[{"stationName":"星塘公交中心","stationCode":"ADU","carCode":"","ArrivalTime":""},{"stationName":"园区青少年中心","stationCode":"DHJ","carCode":"","ArrivalTime":""},{"stationName":"园区行政中心","stationCode":"APM","carCode":"","ArrivalTime":""},{"stationName":"园区人力资源中心","stationCode":"FJT","carCode":"","ArrivalTime":""},{"stationName":"西洲路","stationCode":"AXZ","carCode":"","ArrivalTime":""},{"stationName":"金姬墩","stationCode":"CMP","carCode":"","ArrivalTime":""},{"stationName":"橄榄湾","stationCode":"HHE","carCode":"","ArrivalTime":""},{"stationName":"石港路东","stationCode":"CHK","carCode":"","ArrivalTime":""},{"stationName":"荣域花园","stationCode":"BAY","carCode":"","ArrivalTime":""},{"stationName":"独墅湖大道通园路","stationCode":"JSA","carCode":"","ArrivalTime":""},{"stationName":"汽车南站东","stationCode":"BKR","carCode":"","ArrivalTime":""},{"stationName":"赛格电子市场","stationCode":"GTN","carCode":"","ArrivalTime":""},{"stationName":"香缇华苑北","stationCode":"FHV","carCode":"","ArrivalTime":""},{"stationName":"新区实小竹园路校区","stationCode":"DRU","carCode":"","ArrivalTime":""},{"stationName":"名馨花园","stationCode":"RZC","carCode":"","ArrivalTime":""},{"stationName":"明基医院","stationCode":"FMY","carCode":"","ArrivalTime":""},{"stationName":"新区一中","stationCode":"BVT","carCode":"","ArrivalTime":""},{"stationName":"新升新苑南","stationCode":"DPA","carCode":"","ArrivalTime":""},{"stationName":"新区水厂","stationCode":"BBU","carCode":"","ArrivalTime":""},{"stationName":"竹园路金枫路西","stationCode":"HYM","carCode":"","ArrivalTime":""},{"stationName":"新华路","stationCode":"BHM","carCode":"","ArrivalTime":""},{"stationName":"南浜","stationCode":"GGC","carCode":"苏E-3L507","ArrivalTime":"22:59:12"}]';

        // 入库操作
        $date = date('Y-m-d H:i:s');
        $rs = Cron::insert(['content' => $content, 'create_time' => $date, 'update_time' => $date]);
        return $rs;
    }


    private function __construct($config)
    {
        //$this->ql || $this->ql = QueryList::getInstance();
    }

    private function __clone()
    {

    }

    public function randomTime()
    {
        return (float)sprintf('%.0f', microtime(true) * 1000);
    }
}
