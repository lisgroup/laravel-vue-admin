<?php
/**
 * Desc: 具体实现方法在仓库 NewBusRepository.php 文件中
 * User: lisgroup
 * Date: 18-10-03
 * Time: 11:52
 */

namespace App\Http\Controllers\Bus;


use App\Http\Repository\NewBusRepository;
use Illuminate\Http\Request;
use Jxlwqq\ChineseTypesetting\ChineseTypesetting;

class NewApiController extends CommonController
{
    /**
     * 首页展示页面
     *
     * @return mixed
     */
    public function index()
    {
        $repo = NewBusRepository::getInstent();

        // $list = $repo->getLineID('10');
        // dump($list);
        $lineID = '10000239';
        $data = $repo->getLineStatus($lineID);

        // BusRepository::getInstent()->cronTaskTable();
        if (PHP_SAPI == 'cli') {
            // $result = BusRepository::getInstent()->busTask();
            return ['code' => 0, 'msg' => 'success', 'result' => ['data' => 'task '.date('Y-m-d H:i:s')]];
        } else {
            return $this->out(200, $data);
        }
    }

    /**
     * 搜索列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $line = $request['linename'];
        $line = preg_replace('/快\b(\d)/', '快线$1号', $line);
        $list = NewBusRepository::getInstent()->getLine($line);

        // return $this->exportData($list);
        return $this->out(200, $list);
    }

    /**
     * 线路查询详情，列表展示
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function newBusLine(Request $request)
    {
        $data = [];
        $post = $request->all();
        if ($post && !empty($post['lineID'])) {
            $data = NewBusRepository::getInstent()->getLineStatus($post['lineID']);
        }

        return $this->out(200, $data);
    }

    public function output(Request $request)
    {
        $chineseTypesetting = new ChineseTypesetting();

        // 使用指定方法来纠正排版（推荐此用法）
        $text = '<p class="class-name" style="color: #FFFFFF;"> Hello世界。\n option</p>';
        $out = $chineseTypesetting->correct($text, ['insertSpace', 'removeClass', 'removeIndent']);
        // output: <p style="color: #FFFFFF;">Hello 世界。</p>

        // 使用全部方法来纠正排版（不推荐此用法）
        $text = '<p class="class-name" style="color: #FFFFFF;"> Hello世界。</p>';
        $out1 = $chineseTypesetting->correct($text);
        // output: <p>Hello 世界。</p>
        var_dump($out);
        var_dump($out1);
    }

}
