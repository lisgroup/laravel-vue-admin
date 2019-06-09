<?php
/**
 * Desc: 具体实现方法在仓库 NewBusRepository.php 文件中
 * User: lisgroup
 * Date: 18-10-03
 * Time: 11:52
 */

namespace App\Http\Controllers\Bus;


use App\Events\TestEvent;
use App\Http\Repository\NewBusRepository;
use App\Rules\Uppercase;
use App\Tasks\TestTask;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jxlwqq\ChineseTypesetting\ChineseTypesetting;

class NewApiController extends CommonController
{
    /**
     * 首页展示页面
     *
     * @return mixed
     */
    public function index()
    {
        $repo = NewBusRepository::getInstent();

        // $list = $repo->getLineID('10');
        // dump($list);
        $lineID = '10000239';
        $data = $repo->getLineStatus($lineID);

        // BusRepository::getInstent()->cronTaskTable();
        if (PHP_SAPI == 'cli') {
            // $result = BusRepository::getInstent()->busTask();
            return ['code' => 0, 'msg' => 'success', 'result' => ['data' => 'task '.date('Y-m-d H:i:s')]];
        } else {
            return $this->out(200, $data);
        }
    }

    /**
     * 搜索列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $line = $request['linename'];
        $line = preg_replace('/快\b(\d)/', '快线$1号', $line);
        $list = NewBusRepository::getInstent()->getLine($line);

        // return $this->exportData($list);
        return $this->out(200, $list);
    }

    /**
     * 线路查询详情，列表展示
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function newBusLine(Request $request)
    {
        $data = [];
        $post = $request->all();
        if ($post && !empty($post['lineID'])) {
            $data = NewBusRepository::getInstent()->getLineStatus($post['lineID']);
        }

        return $this->out(200, $data);
    }

    public function output(Request $request)
    {
        $input = $request->input('input');
        $output = '';
        if ($input) {
            $chineseTypesetting = new ChineseTypesetting();
            $arrays = explode("\n", $input);

            $newArr = [];
            foreach ($arrays as $key => $item) {
                // 使用指定方法来纠正排版（推荐此用法）
                $newArr[] = $chineseTypesetting->correct($item, ['insertSpace', 'removeClass', 'removeIndent']);
            }
            $output = implode("\n", $newArr);
        }

        return $this->out(200, ['output' => $output]);

        // 使用全部方法来纠正排版（不推荐此用法）
        // $text = '<p class="class-name" style="color: #FFFFFF;"> Hello世界。</p>';
        // $out1 = $chineseTypesetting->correct($text);
        // output: <p>Hello 世界。</p>
    }

    public function Task()
    {
        // 实例化TestEvent并通过fire触发，此操作是异步的，触发后立即返回，由Task进程继续处理监听器中的handle逻辑
        // use Hhxsv5\LaravelS\Swoole\Task\Event;
        $success = Event::fire(new TestEvent('event data'));
        var_dump($success);//判断是否触发成功

        // 实例化TestTask并通过deliver投递，此操作是异步的，投递后立即返回，由Task进程继续处理TestTask中的handle逻辑
        $task = new TestTask('task data222');
        // $task->delay(3);// 延迟3秒投放任务
        $ret = Task::deliver($task);
        var_dump($ret);//判断是否投递成功
    }

    /**
     * 验证器示例
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function jwt(Request $request)
    {
        $input = $request->all();
        $rules = [
            'id' => 'required|numeric',
            'name' => 'required',
            'title' => [
                'required',
                'max:255',
                function($attribute, $value, $fail) {
                    if ($value === 'foo') {
                        $fail($attribute.' is invalid.');
                    }
                },
            ],
            'upper' => ['required', 'string', new Uppercase()]
        ];

        $messages = [
            'required' => ':attribute 不能为空',
            'numeric' => ':attribute 必须是数字',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return $this->out(4000, [], $validator->errors()->first());
        }
        return $this->out(200);
    }

}
