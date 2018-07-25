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

    //采集 bus 网址是表单数据
    public function getList()
    {
        //17年5月9日新增 搜索历史的功能
        $cookie_line = Cookie::get('cookie_line');
        $this->assign('cookie_line', $cookie_line);

        $line = input('get.linename', '', 'htmlspecialchars');
        $line = preg_replace('/快\b(\d)/', '快线$1号', $line);
        $list = BusRepository::getInstent()->getList($line);

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
