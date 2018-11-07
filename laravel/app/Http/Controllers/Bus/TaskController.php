<?php
/**
 * Desc: 具体实现方法在仓库 TaskRepository.php 文件中
 * User: lisgroup
 * Date: 18-11-04
 * Time: 10:52
 */

namespace App\Http\Controllers\Bus;


use App\Http\Repository\TaskRepository;
use Illuminate\Http\Request;

class TaskController extends CommonController
{
    /**
     * 采集 bus 网址是表单数据
     * @return mixed
     */
    public function index()
    {
        return TaskRepository::getInstent()->lineList();
    }

    /**
     * 测试发送 json 格式数据参数接收
     * @param Request $request
     * @return mixed
     */
    public function api(Request $request)
    {
        $post = $request->getContent();
        $data = json_decode($post, true);
        return $data;
    }

    public function line()
    {
        TaskRepository::getInstent()->lineTask();
    }

}
