<?php
/**
 * Desc: ApiRepository 仓库类
 * User: lisgroup
 * Date: 19-03-06
 * Time: 20:50
 */

namespace App\Http\Repository;


use App\Models\ApiExcel;

class ApiRepository
{

    /**
     * @var self 单例
     */
    private static $instance;


    /**
     * @param array $conf
     *
     * @return ApiRepository
     */
    public static function getInstent($conf = [])
    {
        if (!isset(self::$instance)) {
            self::$instance = new static($conf);
        }
        return self::$instance;
    }

    /**
     * 自动删除的任务
     */
    public function handleAutoDelete()
    {
        // 查询数据库已完成的任务，判断过期条件
        $excels = ApiExcel::where('state', 2)->get(['id', 'auto_delete', 'updated_at']);

        foreach ($excels as $excel) {
            if ($excel['auto_delete'] > 0 && strtotime($excel['updated_at']) + $excel['auto_delete'] * 86400 < time()) {
                // 获取过期时间戳
                ApiExcel::destroy($excel['id']);

            }

        }
    }

    /**
     * BusRepository constructor.
     *
     *
     * @param $config
     */
    private function __construct($config)
    {
        //$this->ql || $this->ql = QueryList::getInstance();
    }

    /**
     * 不允许 clone
     */
    private function __clone()
    {

    }

    /**
     * @return float
     */
    public function randomTime()
    {
        return (float)sprintf('%.0f', microtime(true) * 1000);
    }
}
