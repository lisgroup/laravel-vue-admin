<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 处理输出
     * @param int $code
     * @param array $data
     * @param string $reason
     * @return \Illuminate\Http\Response
     */
    public function out($code = 200, $data = [], $reason = 'success')
    {
        if ($reason === 'success') {
            $reason = config('errorCode.'.$code.'.reason') ?? 'success';
        }

        return response()->json(compact('code', 'reason', 'data'))->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
