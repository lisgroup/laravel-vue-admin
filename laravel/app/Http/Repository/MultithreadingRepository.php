<?php
/**
 * Desc: MultithreadingRepository 仓库类
 * User: lisgroup
 * Date: 2019-01-28
 * Time: 18:15
 */

namespace App\Http\Repository;


use GuzzleHttp\Pool;
use GuzzleHttp\Client;

class MultithreadingRepository
{
    /**
     * @var array 原始数据
     */
    public $dataSet;
    /**
     * @var array 请求后的数据
     */
    public $data = [];

    public $fileName;
    public $config;

    /**
     * @var self 单例
     */
    private static $instance;

    /**
     * @param array $conf
     *
     * @return MultithreadingRepository
     */
    public static function getInstent($conf = [])
    {
        if (!isset(self::$instance)) {
            self::$instance = new static($conf);
        }
        return self::$instance;
    }

    public function setParam($fileName, $config)
    {
        $this->fileName = $fileName;
        $this->config = $config;
    }

    /**
     * 1. 读取 Excel 文件
     * 文件暂只支持 xlsx 文件
     * @return bool
     */
    public function loadExcel()
    {
        /************************* 1. 读取 Excel 文件 ******************************/
        try {
            // 设置以 Excel2007 格式
            $reader = \PHPExcel_IOFactory::createReader('Excel2007');
            // 载入 Excel 文件
            $excel = $reader->load($this->fileName);

            // 读取一个工作表
            $sheet = $excel->getSheet();
            // 取得总行数
            $highestRow = $sheet->getHighestRow();
            // 取得总列数
            $highestColumn = $sheet->getHighestColumn();
            // 1. 根据第一列数据查询需要哪些参数
            $dataSet = [];

            // 循环读取每个单元格的数据
            for ($row = 2; $row <= $highestRow; $row++) {
                //    $dataSet[$row - 2]['name'] = $sheet->getCell('B'.$row)->getValue();
                for ($i = 'A'; $i <= $highestColumn; $i++) {
                    $value = $sheet->getCell($i.'1')->getValue();
                    $dataSet['data'][$row - 2][$value] = $sheet->getCell($i.$row)->getValue();
                }
            }
            for ($i = 'A'; $i <= $highestColumn; $i++) {
                $dataSet['param'][] = $sheet->getCell($i.'1')->getValue();
            }

            $this->dataSet = $dataSet;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 2. 发送并发请求
     * @param $url
     * @param $appkey
     * @return array
     */
    public function request($url, $appkey)
    {
        $client = new Client();
        // 简单本地并发的 GET 请求测试
        $requests = function($url, $appkey, $dataSet) use ($client) {
            foreach ($dataSet as $key => $value) {
                $params = array_merge($value, ['key' => $appkey]);
                $uri = $url.'?'.http_build_query($params);
                yield function() use ($client, $uri) {
                    return $client->getAsync($uri);
                };
            }
        };

        $pool = new Pool($client, $requests($url, $appkey, $this->dataSet['data']), [
            'concurrency' => 5, // 并发设置
            'fulfilled' => function($response, $index) {
                // this is delivered each successful response
                $result = $response->getBody()->getContents();
                // var_dump($result);
                // var_dump($index);
                $this->data[$index] = $result;
            },
            'rejected' => function($reason, $index) {
                // this is delivered each failed request
                return 'Index: '.$index.' Reason:'.$reason;
            },
        ]);

        // Initiate the transfers and create a promise
        $promise = $pool->promise();
        // Force the pool of requests to complete.
        $promise->wait();

        // 处理 data 数据然后返回
        $returnArray = [];
        foreach ($this->data as $k => $v) {
            $returnArray[$k]['param'] = $this->dataSet['data'][$k];
            $returnArray[$k]['result'] = $v;
        }
        return $returnArray;
    }

    /**
     *  执行 1, 2, 3. 并发请求处理操作
     * @param string $appKey
     * @return array
     */
    public function multiRequest($appKey)
    {
        try {
            /************************* 1. 读取 Excel 文件 ******************************/
            if (!$this->loadExcel()) {
                return [];
            }
            /************************* 2. 发送并发请求   ******************************/
            $config = $this->config;
            return $this->request($config['url'], $appKey);
        } catch (\Exception $e) {
            return [];
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
}
