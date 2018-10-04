<?php
/**
 * Desc: 父类一些声明和输出操作
 * User: lisgroup
 * Date: 18-10-03
 * Time: 11:52
 */

namespace app\index\controller;

use think\Controller;

class CommonController extends Controller
{
    public function _initialize()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
    }

    public function index()
    {
        return $this->fetch();
    }

    /**
     * 返回默认格式
     * @param $data
     * @param int $errorCode
     * @param string $reason
     * @return array
     */
    protected function exportData($data, $errorCode = 0, $reason = 'success')
    {
        return ['error_code' => $errorCode, 'reason' => $reason, 'result' => $data];
    }
}
