<?php
/**
 * 公共函数库
 * User: lisgroup
 * Date: 2019-1-19 15:33
 */

/**
 * @param $varName
 * @return string
 */
if (!function_exists('show')) {
    function show($varName)
    {
        switch ($result = get_cfg_var($varName)) {
            case 0:
                return 'no';
                break;
            case 1:
                return 'yes';
                break;
            default:
                return $result;
                break;
        }
    }
}
