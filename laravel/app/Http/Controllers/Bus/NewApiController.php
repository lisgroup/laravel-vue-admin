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

}
