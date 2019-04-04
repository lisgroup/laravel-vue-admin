<?php
/**
 * Desc: ExcelRepository 仓库类
 * User: lisgroup
 * Date: 2019-04-04
 * Time: 10:15
 */

namespace App\Http\Repository;


use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelRepository
{
    /**
     * @var array 原始数据
     */
    public $dataSet;
    /**
     * @var array 请求后的数据
     */
    public $data = [];

    public $api_excel_id = 0;
    public $fileName;
    public $config;

    /**
     * @var self 单例
     */
    private static $instance;

    /**
     * 获取单例
     *
     * @return ExcelRepository
     */
    public static function getInstent()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function setApiExcelId($id)
    {
        $this->api_excel_id = $id;
    }

    /**
     * 设置参数
     *
     * @param $fileName
     * @param array $config
     */
    public function setParam($fileName, $config = [])
    {
        $this->fileName = $fileName;
        $this->config = $config;
        // $this->config || $this->config = $config ? $config : config('apiParam');
    }

    /**
     * 加载 Excel 文件返回内容数组
     *
     * @param $fileName
     *
     * @return bool
     */
    public function loadExcel($fileName)
    {
        try {
            // new PhpOffice\PhpSpreadsheet\IOFactory 读取 Excel 文件
            $excel = IOFactory::load($fileName);
            // 1. 取出全部数组
            $data = $excel->getActiveSheet()->toArray('', true, true, true);
            // 2. 数组第一元素为参数名称
            $dataSet['param'] = array_shift($data);

            // 3. 循环数组每个单元格的数据
            $dataSet['data'] = $data;

            return $dataSet;
        } catch (Exception|\PhpOffice\PhpSpreadsheet\Exception $exception) {
            return false;
        }
    }


    /**
     * a. 使用 PhpOffice/PhpSpreadsheet/IOFactory 获取 Excel 内容
     *
     * @return bool
     */
    public function newLoadExcel()
    {
        try {
            // new PhpOffice\PhpSpreadsheet\IOFactory 读取 Excel 文件
            $excel = IOFactory::load($this->fileName);
            // 1. 取出全部数组
            $data = $excel->getActiveSheet()->toArray('', true, true, true);
            // 2. 数组第一元素为参数名称
            $this->dataSet['param'] = array_shift($data);

            // 3. 循环数组每个单元格的数据
            $this->dataSet['data'] = $data;

            return true;
        } catch (Exception|\PhpOffice\PhpSpreadsheet\Exception $exception) {
            return false;
        }

    }


    /**
     * 存储 Excel 数据
     *
     * @param $param
     * @param $result
     *
     * @return bool|string
     */
    public function saveExcel($param, $result)
    {
        /************************* 2. 写入 Excel 文件 ******************************/
        // 首先创建一个新的对象  PHPExcel object
        $objPHPExcel = new Spreadsheet();

        /** 以下是一些设置 ，什么作者、标题信息 */
        $objPHPExcel->getProperties()->setCreator('lisgroup')
            ->setLastModifiedBy('lisgroup')
            ->setTitle('EXCEL 导出')
            ->setSubject('EXCEL 导出')
            ->setDescription('导出数据')
            ->setKeywords("excel php")
            ->setCategory("result file");
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/

        // Excel 的第 A 列，uid 是你查出数组的键值，下面以此类推
        try {
            // ErrorException: Undefined offset: 0 in ApiExcelListener.php:76
            $setActive = $objPHPExcel->setActiveSheetIndex(0);
            // 1. 第一行应该是 param 参数
            $keys = array_keys($result[0]['param']);
            $i = 'A';
            foreach ($keys as $num => $key) {
                $setActive->setCellValue($i.'1', "\t".$key);
                $i++;
            }

            // 1.2 处理配置的字段
            if ($param['result'] && $arr = explode(',', $param['result'])) {
                foreach ($arr as $item) {
                    $val = $item ?? '';
                    if (strpos($item, '.') !== false) {
                        $kems = explode('.', $item);
                        $val = end($kems);
                    }
                    $setActive->setCellValue($i.'1', $val ?? '');
                    $i++;
                }
            }
            // 1.3 is_need 字段
            if ($param['is_need'] == 1) {
                $setActive->setCellValue($i.'1', 'res');
            }

            // 2. 第二行开始循环数据
            foreach ($result as $key => $value) {
                // 2.1 第二行位置
                $number = $key + 2;

                $i = 'A';
                foreach ($keys as $num => $key) {
                    $setActive->setCellValue($i.$number, "\t".$value['param'][$key]);
                    $i++;
                }

                // 2.2 处理配置的字段
                $array = json_decode($value['result'], true);

                // 示例1： $param['result'] = 'res'; -- 为 api_param 表一行数据
                // 示例2： $value = ['param' => ['realname' => '**', 'idcard' => '***'], 'result' => '{"reason":"成功","result":{"realname":"**","idcard":"***","res":2},"error_code":0}'];
                if ($param['result'] && $arr = explode(',', $param['result'])) {
                    foreach ($arr as $k => $item) {
                        // 2019-03-15 输出可能的参数异常等错误信息，需判断最后一列输出
                        if ((isset($array['error_code']) && $array['error_code'] != 0) && ($k == count($arr) - 1)) {
                            $val = $array['reason'];
                        } else {
                            // 2019-02-27 日新增： 354 接口配置 data.0.status 字段
                            // 输出需要 $array['result']['data'][0]['status']
                            $val = $array['result'][$item] ?? '';
                            if (strpos($item, '.') !== false) {
                                $kems = explode('.', $item);
                                $val = $array['result'];
                                foreach ($kems as $kem) {
                                    $val = $val[$kem] ?? '';
                                }

                            }
                        }

                        $setActive->setCellValue($i.$number, $val);
                        $i++;
                    }
                }

                // 1.3 is_need 字段
                if ($param['is_need'] == 1) {
                    if (isset($array['error_code']) && $array['error_code'] == 0) {
                        if (isset($array['result']['res'])) {
                            $message = $array['result']['res'] == 1 ? '一致' : '不一致';
                        } else {
                            $message = '';
                        }
                    } else {
                        $message = $array['reason'] ?? '';
                    }
                    $setActive->setCellValue($i.$number, $message);
                }
            }

            //得到当前活动的表,注意下文教程中会经常用到$objActSheet
            $objActSheet = $objPHPExcel->getActiveSheet();
            // 位置bbb  *为下文代码位置提供锚
            // 给当前活动的表设置名称
            $objActSheet->setTitle('Simple');
            // 代码还没有结束，可以复制下面的代码来决定我们将要做什么

            // 1,直接生成一个文件
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
            $path = storage_path('app/public');
            // is_dir($path) || mkdir($path, 777, true);
            $did = explode('/', $param['website']);
            $did = is_numeric(end($did)) ? end($did) : '999';
            $fileName = '/out-'.$did.'-'.date('YmdHis').uniqid().'.xlsx';
            $objWriter->save($path.$fileName);

            return '/storage'.$fileName;
        } catch (\PhpOffice\PhpSpreadsheet\Exception|\PhpOffice\PhpSpreadsheet\Writer\Exception $exception) {
            // 记录任务失败的错误日志
            Log::error('Api_Excel 任务执行失败: ', ['error' => $exception]);
            return false;
        }
    }

    /**
     * BusRepository constructor.
     */
    private function __construct()
    {
        //$this->ql || $this->ql = QueryList::getInstance();
    }

    /**
     * 不允许 clone
     */
    private function __clone()
    {

    }
}
