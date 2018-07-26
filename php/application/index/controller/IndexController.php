<?php

namespace app\index\controller;

use app\index\repository\BusRepository;
use think\Cookie;

class IndexController extends CommonController
{
    /**
     * 首页展示页面
     * @return mixed
     */
    public function index()
    {
        //17年5月9日新增 搜索历史的功能
        $cookie_line = Cookie::get('cookie_line');
        if (!is_array($cookie_line)) {
            $cookie_line = [];
            Cookie::set('cookie_line', []);
        }
        $this->assign('cookie_line', $cookie_line);
        return $this->fetch();
    }

    public function bus()
    {
        $str = <<<EOF
{
	"resultcode": "200",
	"reason": "success",
	"result": [{
			"bus": "115",
			"FromTo": "独墅湖高教区首末站=>沪宁城铁园区站广场",
			"code": "edc1ecd6-2bf8-4b08-8727-385bb8943b9d",
			"carCode": "苏E-S6051",
			"time": "15:56:03",
			"distance": "5"
		},
		{
			"bus": "128",
			"FromTo": "车坊首末站=>阳澄人家西",
			"code": "7e5f894b-db9a-43da-b5eb-8c6a170db5b8",
			"carCode": "苏E-S6051",
			"time": "15:56:03",
			"distance": "5"
		},
		{
			"bus": "146",
			"FromTo": "独墅湖高教区首末站=>虎丘首末站",
			"code": "ab9fdefc-a31d-461f-bbdf-030ee15d41e7",
			"carCode": "苏E-2U082",
			"time": "15:54:09",
			"distance": "3"
		},
		{
			"bus": "156",
			"FromTo": "车坊首末站=>官渎里立交换乘枢纽站",
			"code": "89bd6613-be78-4981-a90a-b00b26eea9af",
			"carCode": "无",
			"time": "",
			"distance": "无车"
		},
		{
			"bus": "177",
			"FromTo": "启月街首末站=>玉皇宫首末站",
			"code": "e0855aea-89f2-4881-a582-4950272fbdb0",
			"carCode": "无",
			"time": "",
			"distance": "无车"
		}
	]
}
EOF;
        $data = json_decode($str, true);

        return $data;
    }

    public function lines()
    {
        $str = '{"resultcode":"200","reason":"success","result":[{"stationName":"独墅湖高教区首末站","stationCode":"FGT","carCode":"","ArrivalTime":""},{"stationName":"职业技术学院北","stationCode":"PUF","carCode":"","ArrivalTime":""},{"stationName":"园区服务外包学院","stationCode":"RVV","carCode":"","ArrivalTime":""},{"stationName":"苏州评弹学校","stationCode":"RVT","carCode":"","ArrivalTime":""},{"stationName":"裕新路松涛街西","stationCode":"RVR","carCode":"","ArrivalTime":""},{"stationName":"腾飞创新园南","stationCode":"RUV","carCode":"苏E-2N173","ArrivalTime":"16:05:39"},{"stationName":"腾飞创新园","stationCode":"RET","carCode":"","ArrivalTime":""},{"stationName":"生物纳米园","stationCode":"EFP","carCode":"","ArrivalTime":""},{"stationName":"创意产业园西","stationCode":"MDD","carCode":"","ArrivalTime":""},{"stationName":"创意产业园东","stationCode":"MDG","carCode":"","ArrivalTime":""},{"stationName":"崇文路雪堂街西","stationCode":"MDE","carCode":"","ArrivalTime":""},{"stationName":"东大研究院","stationCode":"GSP","carCode":"","ArrivalTime":""},{"stationName":"翰林邻里中心","stationCode":"FCS","carCode":"","ArrivalTime":""},{"stationName":"西交大","stationCode":"DHB","carCode":"","ArrivalTime":""},{"stationName":"南大研究生院","stationCode":"HGA","carCode":"","ArrivalTime":""},{"stationName":"人大国际学院东","stationCode":"FZZ","carCode":"","ArrivalTime":""},{"stationName":"莲花新村五区","stationCode":"MMZ","carCode":"苏E-2N017","ArrivalTime":"16:08:42"},{"stationName":"莲花新村三区","stationCode":"GDX","carCode":"","ArrivalTime":""},{"stationName":"莲花新村四区","stationCode":"ETG","carCode":"","ArrivalTime":""},{"stationName":"敦煌路","stationCode":"DCH","carCode":"","ArrivalTime":""},{"stationName":"联丰广场","stationCode":"GFR","carCode":"苏E-2N288","ArrivalTime":"16:09:52"},{"stationName":"荷韵新村","stationCode":"ECR","carCode":"","ArrivalTime":""},{"stationName":"大地乐章东","stationCode":"HHS","carCode":"","ArrivalTime":""},{"stationName":"橄榄湾","stationCode":"CGK","carCode":"","ArrivalTime":""},{"stationName":"金姬墩","stationCode":"FJA","carCode":"","ArrivalTime":""},{"stationName":"西洲路","stationCode":"EEY","carCode":"","ArrivalTime":""},{"stationName":"旺墩路","stationCode":"FXC","carCode":"","ArrivalTime":""},{"stationName":"园区行政中心西","stationCode":"EBM","carCode":"","ArrivalTime":""},{"stationName":"星湖街沈浒路","stationCode":"ETJ","carCode":"","ArrivalTime":""},{"stationName":"星湖立交南","stationCode":"BRD","carCode":"","ArrivalTime":""},{"stationName":"上娄路","stationCode":"BZT","carCode":"苏E-2N072","ArrivalTime":"16:05:12"},{"stationName":"珠泾路葑亭大道南","stationCode":"PPT","carCode":"","ArrivalTime":""},{"stationName":"青青家园","stationCode":"RBD","carCode":"苏E-2N305","ArrivalTime":"16:03:41"},{"stationName":"沪宁城铁园区站广场","stationCode":"PPV","carCode":"苏E-2N107","ArrivalTime":"15:52:33"}]}';

        return json_decode($str, true);
    }

    //采集 bus 网址是表单数据
    public function getList()
    {
        $line = input('get.linename', '', 'htmlspecialchars');
        $line = preg_replace('/快\b(\d)/', '快线$1号', $line);
        $list = BusRepository::getInstent()->getList($line);

        echo '<pre>';
        print_r($list);exit();

        $this->assign('list', $list);

        return $this->fetch('index/getList');
        /**
         * 历史遗留。。。
         * if (request()->isAjax()) {
         * //用户输入，搜索的线路
         * $inputData = input('post.', '', 'htmlspecialchars');
         * // 17 年 10 月 25 日新增 CSRF 验证
         * $result = $this->validate(
         * $inputData,
         * [
         * 'linename' => 'require|max:100|token:__hash__',
         * ]
         * );
         *
         * $line = $inputData['linename'];
         * $line = preg_replace('/快\b(\d)/', '快线$1', $line);
         *
         * //17年5月9日新增 搜索历史的功能 写入cookie的操作
         * $cookie_line = !is_array(Cookie::get('cookie_line')) ? [] : Cookie::get('cookie_line');
         * //17年5月11修复最新搜索排序在最后的问题。//array_push($cookie_line, $line); //合并数据 $cookie_line = array_unique($cookie_line); //去除重复
         * //1.先搜索数组中是否存在元素,搜索到后删除，然后合并 ,修复$i = 0 的bug
         * if (($i = array_search($line, $cookie_line)) !== false) unset($cookie_line[$i]);
         * array_unshift($cookie_line, $line);
         *
         * if (count($cookie_line) > 3) array_pop($cookie_line); //删除最后的元素
         * Cookie::set('cookie_line', $cookie_line);
         * //定义采集的url
         * $url = 'http://www.szjt.gov.cn/BusQuery/APTSLine.aspx?cid=175ecd8d-c39d-4116-83ff-109b946d7cb4';
         * //缓存data post表单提交参数数据
         * if (!$data = cache('inputData')) {
         * //1.采集目标 $html = file_get_contents('bus.html');
         * //缓存公交线标题提交的参数信息（默认缓存一天）
         * if (file_exists(RUNTIME_PATH.'temp'.DS.'bus.html') && filemtime(RUNTIME_PATH.'temp'.DS.'bus.html') + 86400 > time()) {
         * $html = file_get_contents(RUNTIME_PATH.'temp'.DS.'bus.html');
         * } else {
         * //重新生成缓存文件
         * $html = httpGet($url);
         * $rs = file_put_contents(RUNTIME_PATH.'temp'.DS.'bus.html', httpGet($url));
         * }
         * //2.自定义采集规则
         * $rules = [
         * //采集id为__VIEWSTATE这个元素里面的纯文本内容
         * '__VIEWSTATE' => array('#__VIEWSTATE', 'value'),
         * '__VIEWSTATEGENERATOR' => array('#__VIEWSTATEGENERATOR', 'value'),
         * '__EVENTVALIDATION' => array('#__EVENTVALIDATION', 'value'),
         * //'ctl00$MainContent$LineName' => array('#MainContent_LineName','value'),         //线路番号
         * //'ctl00$MainContent$SearchLine' => array('#MainContent_SearchLine','value'),     //搜索
         * ];
         * //3.开始采集 -- 作为发送数据的基础
         * $arrayData = QueryList::Query($html, $rules)->data;
         * if (!isset($arrayData[0]))
         * return $this->error('网络异常，请稍后重试');
         * $data = $arrayData[0];
         *
         * cache('inputData', $data, 86400);
         * }
         *
         * //merge 用户输入的线路数据
         * $input = [
         * 'ctl00$MainContent$LineName' => $line,        //线路番号
         * 'ctl00$MainContent$SearchLine' => '搜索',     //搜索
         * ];
         * $data = array_merge($data, $input);
         *
         * //判断是否已经有此条线路搜索
         * if (file_exists(RUNTIME_PATH.'temp'.DS.'post_replace_'.$line.'.html')) {
         * //1.文件存在直接读取
         * $html = file_get_contents(RUNTIME_PATH.'temp'.DS.'post_replace_'.$line.'.html');//线路列表
         * } else {
         * //2.文件不存在，模拟表单提交
         * //PHP curl 模拟表单提交数据
         * $c_post = c_post($url, $data); //线路列表
         * //根据线路信息，替换其中的a标签
         * $rules = [
         * 'content' => array('#MainContent_DATA', 'html')
         * ];
         * $arrayData = QueryList::Query($c_post, $rules)->data;
         * if (!isset($arrayData[0]))
         * return $this->error('网络异常，请稍后重试');
         * $data = $arrayData[0];
         * //替换样式
         * $find = ['<?xml version="1.0" encoding="utf-16"?>', '<table ', "href="];
         * $replace = ['', '<table class="layui-table" lay-even="" lay-skin="row" ', "onclick='changeDo(this)' href='javascript:;' data-href="];
         * $html = str_replace($find, $replace, $data['content']);
         * //缓存 此条线路替换a标签的数据
         * $fileName = RUNTIME_PATH.'temp'.DS.'post_replace_'.$line.'.html';
         * $rs = file_put_contents($fileName, $html);
         * //抛出异常if (!$rs)
         * }
         *
         * //json 格式输出
         * return $this->success($html, '');
         * //exit(json_encode($data));
         * }*/
    }

    /**
     * ajax获取实时公交数据table列表 --- 根据data-href 地址，请求szjt.gov.cn查询实时公交数据，不能缓存
     *
     */
    public function busLine()
    {
        if (request()->isAjax()) {
            $href = input('post.href', '', 'htmlspecialchars');

            $html = BusRepository::getInstent()->getLine($href);

            if ($html) {
                $this->assign('html', $html);
                $this->success($html);
            } else {
                $this->error('网络异常，请稍后重试');
            }
            $this->success($html);

            /*$url = 'http://www.szjt.gov.cn/BusQuery/'.$href;
            //实时公交返回的网页数据
            $line = httpGet($url);
            $rules = [
                'to' => ['#MainContent_LineInfo', 'text'],  //方向
                'content' => ['#MainContent_DATA', 'html']       //具体线路table表格
            ];
            $arrayData = QueryList::Query($line, $rules)->data;
            if (!isset($arrayData[0]))
                return $this->error('网络异常，请稍后重试');
            $data = $arrayData[0];

            //替换样式，自适应显示
            $find = ['<?xml version="1.0" encoding="utf-16"?>', '<table ', "href="];
            $replace = ['', '<table class="layui-table" lay-even="" lay-skin="row" ', "onclick='changeName(this)' href='javascript:;' data-href="];
            $html = str_replace($find, $replace, $data['content']);
            $html = '<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;color:green;"><legend>'.$data['to'].' 方向</legend></fieldset>'.$html;
            return $this->success($html);*/
        }
    }

    /**
     * 页面显示获取实时公交数据table列表，数据来自 szjt.gov.cn，需要限制访问频率，1秒一次请求
     * @return mixed
     */
    public function line()
    {
        //页面访问频率限制操作，使用cookie保存每次请求的时间点
        sleep_visit();//是否需要延迟访问

        //‌array ('url' => 'APTSLine.aspx?LineGuid=547E0449-A28B-1770-B4CA-31A11D6A3C72','LineInfo' => '55(众泾社区停车场)',)
        $get = input('get.', '', 'htmlspecialchars');
        $html = BusRepository::getInstent()->getLine($get);

        if ($html) {
            $this->assign('html', $html);
            return $this->fetch();
        }
        $this->redirect('/');
        return false;
    }
}
