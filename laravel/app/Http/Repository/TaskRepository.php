<?php
/**
 * Desc: TaskRepository 仓库类
 * User: lisgroup
 * Date: 18-11-03
 * Time: 22:06
 */

namespace App\Http\Repository;


use QL\QueryList;

class TaskRepository
{
    /**
     * @var mixed|QueryList
     */
    protected $ql;
    /**
     * @var string $url 定义采集的 url
     */
    protected $url = '';

    private static $instance;

    // private $cronModel;

    public static function getInstent($conf = [])
    {
        if (!isset(self::$instance) || !(self::$instance instanceof TaskRepository)) {
            self::$instance = new static($conf);
        }
        return self::$instance;
    }

    private function __construct($config)
    {
        if (!empty($config)) {
            foreach ($config as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->ql || $this->ql = QueryList::getInstance();
    }

    private function __clone()
    {

    }
}