<?php

namespace App\Listeners;

use App\Events\ApiExcelEvent;
use App\Http\Repository\MultithreadingRepository;
use App\Models\ApiExcel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ApiExcelListener implements ShouldQueue
{
    /**
     * 失败重试次数
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ApiExcelEvent $event
     * @throws \Exception
     * @return void
     */
    public function handle(ApiExcelEvent $event)
    {
        // 获取事件中保存的数据
        $data = $event->getData();
        // 根据状态处理数据
        switch ($data['state']) {
            case 1:
                // 根据 upload 上传的数据批量测试数据
                $path = public_path($data['upload_url']);
                if ($data['state'] == 1 && file_exists($path)) {
                    // 获取 appkey 和 url
                    $apiExcel = ApiExcel::findOrFail($data['id']);
                    $param = $apiExcel->apiParam()->get();
                    if ($param) {
                        $param = $param[0];
                        $multi = MultithreadingRepository::getInstent();
                        $multi->setParam($path, ['concurrent' => $param['concurrent']]);
                        $result = $multi->multiRequest($param['url'], $data['appkey']);

                        // TODO: 正式上线后注释下一行
                        Log::info('result', $result);
                        if (!$result) {
                            throw new \Exception(date('Y-m-d H:i:s').' 任务失败： 第三方请求错误～！'.$param['url']);
                        }

                        ksort($result);

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
                                    $setActive->setCellValue($i.'1', $array['result'][$item] ?? '');
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

                                if ($param['result'] && $arr = explode(',', $param['result'])) {
                                    foreach ($arr as $item) {
                                        $setActive->setCellValue($i.$number, $array['result'][$item] ?? '');
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
                            $fileName = '/out-208-'.date('mdHis').'.xlsx';
                            $objWriter->save($path.$fileName);

                            // 更新任务状态
                            $apiExcel->state = 2;
                            $apiExcel->finish_url = '/storage'.$fileName;
                            $apiExcel->save();
                        } catch (\PhpOffice\PhpSpreadsheet\Exception|\PhpOffice\PhpSpreadsheet\Writer\Exception $exception) {
                            // 记录任务失败的错误日志
                            Log::error('Api_Excel 任务执行失败: ', ['error' => $exception]);
                        }
                    }
                }
                break;
            default:
                break;
        }

    }

    /**
     * 处理任务失败
     *
     * @param  \App\Events\ApiExcelEvent $event
     * @param  \Exception $exception
     * @return void
     */
    public function failed(ApiExcelEvent $event, $exception)
    {
        //
    }
}
