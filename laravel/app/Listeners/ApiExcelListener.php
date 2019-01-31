<?php

namespace App\Listeners;

use App\Events\ApiExcelEvent;
use App\Http\Repository\MultithreadingRepository;
use App\Models\ApiParam;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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

                    $multi = MultithreadingRepository::getInstent();
                    $multi->setParam($path);
                    // 获取 appkey 和 url
                    $param = ApiParam::find($data['api_excel_id']);
                    if ($param) {
                        $result = $multi->multiRequest($param['url'], $param['appkey']);
                        // TODO 记录 result 到 excel 中
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

                                                                                                                                                        // Excel的第A列，uid是你查出数组的键值，下面以此类推
                                                                                                                                                                $setActive = $objPHPExcel->setActiveSheetIndex(0);
                                                                                                                                                                        // 第一行应该是 param 参数
                                                                                                                                                                                foreach ($result as $key => $value) {
                                                                                                                                                                                                if ($value['error_code'] == 0) {
                                                                                                                                                                                                                    $message = $value['result']['res'] == 1 ? '一致' : '不一致';
                                                                                                                                                                                                } else {
                                                                                                                                                                                                                    $message = $value['reason'];
                                                                                                                                                                                                }

                                                                                                                                                                                                            $num = $key + 2;
                                                                                                                                                                                                                        $setActive->setCellValue('A'.$num, "\t".$value['name'])
                                                                                                                                                                                                                                        ->setCellValue('B'.$num, "\t".$value['idcard'])
                                                                                                                                                                                                                                                        ->setCellValue('C'.$num, "\t".$value['bankcard'])
                                                                                                                                                                                                                                                                        ->setCellValue('D'.$num, $message);

                                                                                                                                                                                                                                                                                    //sleep(0.15);
                                                                                                                                                                                }

                                                                                                                                                                                        //得到当前活动的表,注意下文教程中会经常用到$objActSheet
                                                                                                                                                                                                $objActSheet = $objPHPExcel->getActiveSheet();
                                                                                                                                                                                                        // 位置bbb  *为下文代码位置提供锚
                                                                                                                                                                                                                // 给当前活动的表设置名称
                                                                                                                                                                                                                        $objActSheet->setTitle('Simple');
                                                                                                                                                                                                                                // 代码还没有结束，可以复制下面的代码来决定我们将要做什么

                                                                                                                                                                                                                                        // 1,直接生成一个文件
                                                                                                                                                                                                                                                $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
                                                                                                                                                                                                                                                        $objWriter->save('out-207-'.date('mdHis').'.xlsx');

                                                                                                                                                                                                                                                        {}
                                                                                                                                                                                                }
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
