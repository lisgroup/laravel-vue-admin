<?php
/**
 * Desc: TaskRepository 仓库类
 * User: lisgroup
 * Date: 18-11-03
 * Time: 22:06
 */

namespace App\Http\Repository;


use App\Models\CronTask;
use App\Models\Line;
use Illuminate\Support\Facades\Log;
use QL\QueryList;

class ToolRepository
{
    private static $instance;


    public static function getInstent($conf = [])
    {
        if (!isset(self::$instance) || !(self::$instance instanceof TaskRepository)) {
            self::$instance = new static($conf);
        }
        return self::$instance;
    }

    /**
     * 字符串转十六进制函数
     *
     * @param $str
     *
     * @return string
     */
    public function stringToHex($str)
    {
        $hex = "";
        for ($i = 0; $i < strlen($str); $i++) {
            $hex .= dechex(ord($str[$i]));
        }

        return strtoupper($hex);
    }

    /**
     * 十六进制转字符串函数
     *
     * @param $input
     *
     * @return string
     */
    public function hexToString($input)
    {
        $hex = strtoupper($input);
        $hex = str_replace('\\X', '', $hex);
        if (!preg_match("/^[A-Fa-f0-9]+$/", $hex)) {
            return $this->out(1006);
        }
        $str = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $str .= chr(hexdec($hex[$i].$hex[$i + 1]));
        }
        if (preg_match('~[\x{4e00}-\x{9fa5}]+~u', $str, $tmp)) {
            return (string)$str;
        }
        return '';
    }

    /**
     * 加密的密钥为:客户个人中心的openid经过md5后结果为小写取前16位
     * @param $input
     * @return false|string
     */
    public function openidSecret($input)
    {
        return substr(md5($input), 0, 16);
    }

    public function base64Encode($input)
    {
        return base64_encode($input);
    }

    public function base64Decode($input)
    {
        return base64_decode($input);
    }

    public function urlEncode($input)
    {
        return urlencode($input);
    }

    public function urlDecode($input)
    {
        return urldecode($input);
    }

    /**
     * TaskRepository constructor.
     * @param $config
     */
    private function __construct($config)
    {
        if (!empty($config)) {
            foreach ($config as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    private function __clone()
    {

    }
}
