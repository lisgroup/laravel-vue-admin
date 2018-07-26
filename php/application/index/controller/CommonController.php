<?php

namespace app\index\controller;

use think\Controller;

class CommonController extends Controller
{
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
