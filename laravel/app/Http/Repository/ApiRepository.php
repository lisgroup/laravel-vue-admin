<?php
/**
 * Desc: ApiRepository 仓库类
 * User: lisgroup
 * Date: 19-03-06
 * Time: 20:50
 */

namespace App\Http\Repository;


use App\Models\ApiExcel;
use App\Models\ApiExcelLogs;
use Illuminate\Support\Facades\DB;

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
        $excels = ApiExcel::whereIn('state', [2, 5])->get(['id', 'auto_delete', 'updated_at']);

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
        // $excels = ApiExcel::where('state', 1)->get(['id', 'auto_delete', 'created_at', 'updated_at']);

        // New: 2020-05-31 失败任务的条件
        // 1. 完成率 96% -- 5 秒不再增加
        // 2. 完成率 50% -- 1 分钟不再增加
        // 3. 完成率 10% -- 5 分钟不再增加
        // 4. 完成率 1%  -- 10 分钟不再增加
        // 5. 完成率 0%  -- 30 分钟不再增加
        // $excels = DB::connection()->select('SELECT ae.id,ae.api_param_id,ae.state,ae.total_excel,ael.api_excel_id,ael.sort_index,ael.created_at FROM `boss_api_excel` ae LEFT JOIN boss_api_excel_logs ael ON ae.id=ael.api_excel_id AND ael.id=(SELECT id FROM boss_api_excel_logs WHERE boss_api_excel_logs.api_excel_id=ae.id ORDER BY sort_index DESC LIMIT 1) WHERE ae.state=1 AND ae.`deleted_at` IS NULL ');
        $excels = DB::connection()->select('SELECT ae.id,ae.api_param_id,ae.state,ae.total_excel,ael.api_excel_id,ael.sort_index,ae.updated_at,ael.created_at FROM `boss_api_excel` ae LEFT JOIN boss_api_excel_logs ael ON ae.id=ael.api_excel_id AND ael.id=(SELECT id FROM boss_api_excel_logs WHERE boss_api_excel_logs.api_excel_id=ae.id ORDER BY id DESC LIMIT 1) WHERE ae.state=1 AND ae.`deleted_at` IS NULL ');

        foreach ($excels as $excel) {
            // 开启任务后 10 分钟未查询出结果=》失败
            // if ($excel['auto_delete'] > 0 && strtotime($excel['updated_at']) + 600 < time()) {
            //     ApiExcel::where('id', $excel['id'])->update(['state' => 5]);
            // }
            // if (!$excel->sort_index || !$excel->created_at) {
            //     continue;
            // }

            // 1. logs 10s 一直无新增数据；2. 查询最新更新时间大于 20 秒未新增数据--失败
            if ((!$excel->sort_index && $excel['updated_at'] + 10 < time()) || strtotime($excel->created_at) + 20 < time()) {
                ApiExcel::where('id', $excel->id)->update(['state' => 5]);
            }
            // 记录完成率
            // $finish = (($excel->sort_index + 1) / $excel->total_excel) * 100;
            // $finish = sprintf("%.2f", $finish);
            // if (($finish > '96' && strtotime($excel->created_at) + 5 < time()) || ($finish > '50' && strtotime($excel->created_at) + 60 < time()) || ($finish > '10' && strtotime($excel->created_at) + 300 < time()) || ($finish > '1' && strtotime($excel->created_at) + 600 < time()) || ($finish >= 0 && strtotime($excel->created_at) + 1800 < time())) {
            //    ApiExcel::where('id', $excel->id)->update(['state' => 5]);
            // }
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


    /**
     * 获取下载进度条
     *
     * @param $lists
     *
     * @return mixed
     */
    public function workProgress($lists)
    {
        foreach ($lists as $key => $list) {
            $rate = 0;
            switch ($list['state']) {
                case '0': // 未开启任务
                    $rate = 0;
                    break;
                case '1': // 正在处理的任务
                case '5': // 失败任务
                    $rate = $this->progressRate($list['id'], $list['total_excel']);
                    break;
            }

            $lists[$key]['rate'] = $rate;
        }
        return $lists;
    }


    /**
     * 计算任务完成百分比
     *
     * @param int $excel_id api_excel 主键 id
     * @param int $total_excel 总数
     *
     * @return int|bool
     */
    private function progressRate($excel_id, $total_excel)
    {
        if ($total_excel > 0) {
            // 2. 查询 api_excel_logs 表更新的数据量
            $total = ApiExcelLogs::where('api_excel_id', $excel_id)->count();
            // 3. 返回完成率
            $rate = floor($total / $total_excel * 100);
            return $rate > 100 ? 100 : $rate;
        }

        return 0;
    }

}
