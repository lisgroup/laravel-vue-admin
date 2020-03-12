<?php
/**
 * 工具类
 * User: lisgroup
 * Date: 18-10-03
 * Time: 11:52
 */

namespace App\Http\Controllers\Bus;


use Illuminate\Http\Request;

class ToolController extends CommonController
{
    /**
     * 字符串转十六进制函数
     *
     * @param Request $request
     *
     * @return string
     */
    public function stringToHex(Request $request)
    {
        $str = $request->input('input');
        $hex = "";
        for ($i = 0; $i < strlen($str); $i++) {
            $hex .= dechex(ord($str[$i]));
        }
        $hex = strtoupper($hex);

        return $this->out(200, ['output' => $hex]);
    }

    /**
     * 十六进制转字符串函数
     *
     * @param $request
     *
     * @return string
     */
    public function hexToString(Request $request)
    {
        $hex = strtoupper($request->input('input'));
        $hex = str_replace('\\X', '', $hex);
        $str = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $str .= chr(hexdec($hex[$i].$hex[$i + 1]));
        }
        return $this->out(200, ['output' => $str]);
    }
}
