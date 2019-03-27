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
     * 自动任务
     */
    public function handleAutoTask()
    {
        // 1. 自动删除任务
        $this->handleAutoDelete();
        // 2. 大于自动删除时间，state = 1 的置为 5 失败
        $this->autoFailed();
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
     * 自动失败的任务
     * 开启任务后 state = 1 的一直未更新状态的为失败任务
     */
    public function autoFailed()
    {
        // 查询数据库已完成的任务，判断过期条件
        $excels = ApiExcel::where('state', 1)->get(['id', 'auto_delete', 'created_at', 'updated_at']);

        foreach ($excels as $excel) {
            // 开启任务后 10 分钟未查询出结果=》失败
            if ($excel['auto_delete'] > 0 && strtotime($excel['updated_at']) + 600 < time()) {
                ApiExcel::where('id', $excel['id'])->update(['state' => 5]);

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
