<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreLineRequest;
use App\Models\Line;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Vinkla\Hashids\Facades\Hashids;

class LinesController extends Controller
{
    /**
     * @var int 默认分页条数
     */
    public $perPage = 10;

    /**
     * Create a new AuthController instance.
     * 要求附带email和password（数据来源users表）
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // 这里额外注意了：官方文档样例中只除外了『login』
        // 这样的结果是，token 只能在有效期以内进行刷新，过期无法刷新
        // 如果把 refresh 也放进去，token 即使过期但仍在刷新期以内也可刷新
        // 不过刷新一次作废
        $this->middleware(['auth:api', 'role']);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回

        $perPage = intval($request->input('perPage'));
        $this->perPage = $perPage ?? 11;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Line::paginate($this->perPage);
        return $this->out(200, $list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->out(200, ['method' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     * 新增入库操作
     *
     * @param  StoreLineRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLineRequest $request)
    {
        // 会出现 Unknown column 'guid' in 'field list' 不存在的字段入库报错问题
        // $rs = Line::insert($request->all());
        $input = $request->all();
        $input['is_show'] = $input['is_show'] ? 1 : 0;
        $input['username'] = $input['username'] ?? '';
        $model = new Line($input);
        if ($model->save()) {
            return $this->out(200, ['data' => ['id' => $model->id]]);
        } else {
            return $this->out(4000);
        }

    }

    /**
     * Display the specified resource.
     * 展示某个详情数据
     *
     * @param Line $line
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Line $line)
    {
        return $this->out(200, $line);
    }

    /**
     * Show the form for editing the specified resource.
     * 编辑展示数据
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Line::findOrFail($id);
        return $this->out(200, $data);
    }

    /**
     * Update the specified resource in storage.
     * 更新数据
     *
     * @param  StoreLineRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreLineRequest $request, $id)
    {
        $input = $request->only('name', 'price', 'car_type', 'depart_time', 'open_time', 'total_time', 'via_road', 'company', 'station', 'station_back', 'reason', 'username', 'is_show', 'last_update');
        // var_dump($input);exit();
        $input['is_show'] = $input['is_show'] ? 1 : 0;
        $input['username'] = $input['username'] ?? '';
        // $model = new Line();$model->save($input, ['id' => $id]);
        // 老版本更新操作如下，新版本先查询再更新
        // Line::where('id', $id)->update($input)
        $line = Line::findOrFail($id);
        if ($line->update($input)) {
            return $this->out(200, ['data' => ['id' => $id]]);
        } else {
            return $this->out(4000);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        // $rs = Line::where('id', $id)->delete()
        if (Line::findOrFail($id)->delete()) {
            $data = ['msg' => '删除成功', 'errno' => 0];
        } else {
            $data = ['msg' => '删除失败', 'errno' => 2];
        }
        return $this->out(200, $data);
    }

    /**
     * 查询 bus_line 线路--全文索引
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function busLineSearch(\Illuminate\Http\Request $request)
    {
        $params = $request->all(['wd']);
        if (empty($params['wd'])) {
            return $this->out(200, [], 'param error');
        }
        $list = $this->commonSearch('\\App\\Models\\BusLine', $params['wd']);
        return $this->out(200, $list);
    }

    /**
     * bus_line 列表数据
     *
     * @return \Illuminate\Http\Response
     */
    public function busLineList()
    {
        $list = \App\Models\BusLine::paginate($this->perPage);
        return $this->out(200, $list);
    }

    /**
     * 查询 lines 线路--全文索引
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(\Illuminate\Http\Request $request)
    {
        $params = $request->all(['wd']);
        if (empty($params['wd'])) {
            return $this->out(200, [], 'param error');
        }
        $list = $this->commonSearch('\\App\\Models\\Line', $params['wd']);
        return $this->out(200, $list);
    }

    /**
     * @param $model
     * @param $word
     * @param $like
     *
     * @return array|bool
     */
    private function commonSearch($model, $word, $like = 'name')
    {
        try {
            $list = $model::search($word)->paginate($this->perPage)->toArray();
        } catch (\Elasticsearch\Common\Exceptions\NoNodesAvailableException|\Exception $exception) {
            $list = $model::where($like, 'LIKE', "%$word%")->paginate($this->perPage)->toArray();
        }
        return $list;
    }


    /**
     * 清理线路缓存文件
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function clearCache(\Illuminate\Http\Request $request)
    {
        $lines = $request->input('lines');

        $flag = 0;
        $path = storage_path().'/framework/bus/';
        if (is_dir($path)) {
            $p = scandir($path);
            if ($lines == 'all') {
                $flag = 1;
                foreach ($p as $item) {
                    if (!in_array($item, ['.', '..', '.gitignore']) && !is_dir($path.$item)) {
                        unlink($path.$item);
                    }
                }
            } elseif (in_array($lines, $p)) {
                $flag = 1;
                unlink($path.$lines);
            }

        }
        return $this->out($flag == 1 ? 200 : 4000);
    }
}
