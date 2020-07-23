<?php
/**
 * 工具类
 * User: lisgroup
 * Date: 18-10-03
 * Time: 11:52
 */

namespace App\Http\Controllers\Bus;


use App\Http\Repository\ToolRepository;
use Illuminate\Http\Request;

class ToolController extends CommonController
{
    public function tool(Request $request)
    {
        $func = $request->input('operation');
        $input = $request->input('input');
        // 下划线转驼峰
        $convert = convertUnderline($func);

        $toolRepository = ToolRepository::getInstent();
        if ($convert && $input) {
            if (is_callable([$toolRepository, $convert])) {
                $out = $toolRepository->$convert($input);
                if (!$out) {
                    return $this->out(4009);
                }

                return $this->out(200, ['output' => $out]);
            }
        }

        return $this->out(1006);
    }

}
