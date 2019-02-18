<?php
/**
 * Desc: 具体实现方法在仓库 NewBusRepository.php 文件中
 * User: lisgroup
 * Date: 18-10-03
 * Time: 11:52
 */

namespace App\Http\Controllers\Bus;


use App\Http\Repository\NewBusRepository;

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
        dump($repo->lineStatus($lineID));

        // BusRepository::getInstent()->cronTaskTable();
        if (PHP_SAPI == 'cli') {
            // $result = BusRepository::getInstent()->busTask();
            return ['code' => 0, 'msg' => 'success', 'result' => ['data' => 'task '.date('Y-m-d H:i:s')]];
        } else {
            return ['code' => 0, 'msg' => 'success'];
        }
    }

}
