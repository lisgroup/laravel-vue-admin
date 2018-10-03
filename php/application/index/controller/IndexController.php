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
        if (IS_CLI) {
            $rs1 = BusRepository::getInstent()->busTask();

            return ['code' => 0, 'msg' => 'success', 'result' => $rs1];

        } else {
            return ['code' => 0, 'msg' => 'success'];
        }
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

        return $this->exportData($list);
    }

    /**
     * ajax获取实时公交数据table列表 --- 根据data-href 地址，请求szjt.gov.cn查询实时公交数据，不能缓存
     *
     */
    public function busLine()
    {
        $data = [];
        // $href = 'APTSLine.aspx?cid=175ecd8d-c39d-4116-83ff-109b946d7cb4&LineGuid=9d090af5-c5c6-4db8-b34e-2e8af4f63216&LineInfo=1(公交一路新村)';
        // $parseUrl = parse_url($href);
        // var_dump($parseUrl);
        // parse_str($parseUrl['query'], $params);

        // var_dump($_POST);
        $post = input('post.', '', 'htmlspecialchars');
        // 'href' => string 'APTSLine.aspx?cid=175ecd8d-c39d-4116-83ff-109b946d7cb4' (length=54)  'LineGuid' => string '9d090af5-c5c6-4db8-b34e-2e8af4f63216' (length=36)  'LineInfo' => string '1(公交一路新村)' (length=21)
        if (!empty($post) && !empty($post['href']) && !empty($post['LineGuid']) && !empty($post['LineInfo'])) {
            $parseUrl = parse_url($post['href']);
            parse_str($parseUrl['query'], $params);
            unset($post['href']);
            $post['cid'] = $params['cid'];
            $data = BusRepository::getInstent()->getLine($parseUrl['path'], $post);
        }

        return $this->exportData($data);
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
        $html = BusRepository::getInstent()->getLine($get, $get);

        if ($html) {
            $this->assign('html', $html);
            return $this->fetch();
        }
        $this->redirect('/');
        return false;
    }


    /**
     * 定时任务执行实时公交入库操作
     * @return array
     */
    public function cron()
    {
        $data = BusRepository::getInstent()->getCronSave();
        return $this->exportData($data);
    }
}
