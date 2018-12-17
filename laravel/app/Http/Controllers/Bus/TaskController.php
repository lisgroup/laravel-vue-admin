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
     *
     *
     * @return mixed
     */
    public function index()
    {
        return TaskRepository::getInstent()->lineList();
    }

    /**
     * 测试发送 json 格式数据参数接收
     *
     *
     * @param Request $request
     *
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
        $bus = \Illuminate\Support\Facades\DB::table('bus_lines')->get();

        $file = __DIR__.'/bus_lines.php';
        $array = $bus->toArray();
        $str = var_export($array,true);
        $str = str_replace(['stdClass::__set_state(', '))'], ['', ')'], $str);

        // 缓存
        $text='<?php $rows='.$str.';';
        if(false !== fopen($file,'w+')){
            file_put_contents($file,$text);
        }else{
            echo '创建失败';
        }


        // 入口方法
        $param = 'line';
        $repository = TaskRepository::getInstent();
        switch ($param) {
            // case '':
            case 'index':
                $result = $repository->lineList();
                break;
            default:
                if (is_callable([$repository, $param])) {
                    $result = $repository->$param();
                } else {
                    return 'error: required param';
                }

        }
        return $result;

        // $result = TaskRepository::getInstent()->lineTask();
        // return $result;
    }

}
