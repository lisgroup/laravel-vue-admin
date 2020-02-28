<?php
/**
 * Desc: 具体实现方法在仓库 BusRepository.php 文件中
 * User: lisgroup
 * Date: 18-10-03
 * Time: 11:52
 */

namespace App\Http\Controllers\Bus;

use App\Http\Repository\BusRepository;
use App\Http\Repository\NewBusRepository;
use App\Models\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class IndexController extends CommonController
{
    /**
     * 首页展示页面
     *
     * @return mixed
     */
    public function index()
    {
        // BusRepository::getInstent()->cronTaskTable();
        if (PHP_SAPI == 'cli') {
            // $result = BusRepository::getInstent()->busTask();
            return ['code' => 0, 'msg' => 'success', 'result' => ['data' => 'task '.date('Y-m-d H:i:s')]];
        } else {
            return ['code' => 0, 'msg' => 'success'];
        }
    }

    /**
     * 采集 bus 网址是表单数据
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $line = $request['linename'];
        $line = preg_replace('/快\b(\d)/', '快线$1号', $line);
        // 1. 前端直接传递 type 参数时先判断
        if (isset($request['type'])) {
            switch ($request['type']) {
                case '1':
                case 'new':
                    $list = $this->getNewList($line);
                    break;
                default:
                    $list = $this->getOldList($line);
                    // $list = [];
                    if (empty($list)) {
                        $list = $this->getNewList($line);
                    }
            }
        } else {
            // 2. 根据后台配置查询不同的结果
            $default_open = Cache::get('default_open');
            switch ($default_open) {
                case '2': // 查询新版
                    $list = $this->getNewList($line);
                    break;
                case '3': // 先查老版再查新版
                    $list = $this->getOldList($line);
                    // $list = [];
                    if (empty($list)) {
                        $list = $this->getNewList($line);
                    }
                    break;
                case '4': // 先查新版再查老版
                    $list = $this->getNewList($line);
                    // $list = [];
                    if (empty($list)) {
                        $list = $this->getOldList($line);
                    }
                    break;
                default: // 查询老版
                    $list = $this->getOldList($line);
            }
        }

        // return $this->exportData($list);
        return $this->out(200, $list);
    }

    /**
     * 获取老线路列表
     *
     * @param $line
     *
     * @return array
     */
    private function getNewList($line)
    {
        return NewBusRepository::getInstent()->getLine($line);
    }

    /**
     * 获取老线路列表
     *
     * @param $line
     *
     * @return array
     */
    private function getOldList($line)
    {
        return BusRepository::getInstent()->getListV2($line);
    }

    /**
     * 获取实时公交数据table列表 --- 根据data-href 地址，请求 szjt.gov.cn 查询实时公交数据，不能缓存
     * 获取实时公交数据table列表，数据来自 szjt.gov.cn，需要限制访问频率，1秒一次请求
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function busLine(Request $request)
    {
        $data = [];
        // 根据 POST 数据，获取路线信息
        // $request->input('href', '');
        // $post = input('post.', '', 'htmlspecialchars');
        $post = $request->all();

        // 新版公交查询
        if (!empty($post['lineID'])) {
            $data = NewBusRepository::getInstent()->getLineStatus($post['lineID']);
        } elseif (!empty($post) && !empty($post['href']) && !empty($post['LineGuid']) && !empty($post['LineInfo'])) {
            // ['href' => 'APTSLine.aspx?cid=175ecd8d-c39d-4116-83ff-109b946d7cb4', 'LineGuid' => '9d090af5-c5c6-4db8-b34e-2e8af4f63216', 'LineInfo' => '1(公交一路新村)']
            if (isset($post['cid'])) {
                $aspUrl = $post['href'] ?? 'APTSLine.aspx';
            } else {
                $parseUrl = parse_url($post['href']);
                $query = $parseUrl['query'] ?? '';
                parse_str($query, $params);
                $post['cid'] = $params['cid'] ?? '';
                $aspUrl = $parseUrl['path'] ?? 'APTSLine.aspx';
            }
            unset($post['href']);

            $data = BusRepository::getInstent()->getLineData2($aspUrl, $post);
        }

        return $this->out(200, $data);
    }

    /**
     * 查询公交线路--全文索引
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $params = $request->all(['wd']);
        if (empty($params['wd'])) {
            return $this->out(200, [], 'param error');
        }
        $list = Line::search($params['wd'])->get()->toArray();
        return $this->out(200, $list);
    }

    public function getMe($req)
    {
        echo 'test';
        return $req;
    }
}
