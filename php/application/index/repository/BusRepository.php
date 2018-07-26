<?php
/**
 * Desc: Bus 获取
 * User: lisgroup
 * Date: 17-12-29
 * Time: 下午1:52
 */

namespace app\index\repository;


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
        is_dir(ROOT_PATH.'/runtime/bus') || mkdir(ROOT_PATH.'/runtime/bus', 0777, true);

        // 2.0 判断是否已经有此条线路搜索
        if ($refresh || !file_exists(ROOT_PATH.'/runtime/bus/serialize_'.$line.'.txt')) {

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
            $fileName = ROOT_PATH.'/runtime/bus/serialize_'.$line.'.txt';
            file_put_contents($fileName, $str);
            //抛出异常if (!$rs)
        } else {
            // 2.1 文件存在直接读取
            $serialize = file_get_contents(ROOT_PATH.'/runtime/bus/serialize_'.$line.'.txt');//线路列表
            $arrayData = unserialize($serialize);
        }

        return $arrayData;
    }

    /**
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
     * 获取实时公交数据 table 列表
     * @param $get
     * @return bool
     */
    public function getLine($get)
    {
        if (empty($get['url']) || empty($get['LineInfo']))
            return false;
        $url = 'http://www.szjt.gov.cn/BusQuery/'.$get['url'].'&LineGuid='.$get['LineGuid'].'&LineInfo='.$get['LineInfo'];
        //实时公交返回的网页数据
        $queryList = QueryList::get($url);

        $rules = [
            'to' => ['#MainContent_LineInfo', 'text'],  //方向
            'content' => ['#MainContent_DATA', 'html']       //具体线路table表格
        ];

        //$arrayData = QueryList::Query($line, $rules)->data;
        $arrayData = $queryList->rules($rules)->query()->getData();
        if (!isset($arrayData[0])) {
            return false;
        }

        $data = $arrayData[0];

        //替换样式，自适应显示
        $find = ['<?xml version="1.0" encoding="utf-16"?>', '<table ', "href="];
        $replace = ['', '<table class="layui-table" lay-even="" lay-skin="row" ', "onclick='changeName(this)' href='javascript:;' data-href="];

        $html = str_replace($find, $replace, $data['content']);
        $html = '<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;color:green;"><legend>'.$data['to'].' 方向&nbsp;<button class="layui-btn layui-btn-normal" onclick="location.reload();">刷新</button></legend></fieldset>'.$html;

        return $html;
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
