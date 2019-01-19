<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * IndexController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        // 这里额外注意了：官方文档样例中只除外了『login』
        // 这样的结果是，token 只能在有效期以内进行刷新，过期无法刷新
        // 如果把 refresh 也放进去，token 即使过期但仍在刷新期以内也可刷新
        // 不过刷新一次作废
        // $this->middleware('auth:api', ['except' => ['login', 'show']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }

    /**
     * 服务器信息
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['serve'] = [
            ['name' => '服务器操作系统', 'value' => PHP_OS],
            ['name' => '服务器解译引擎', 'value' => $_SERVER['SERVER_SOFTWARE'] ?? ''],
            ['name' => '服务器主机名', 'value' => $_SERVER['SERVER_NAME'] ?? ''],
            ['name' => 'MySQL 版本', 'value' => DB::select('SHOW VARIABLES LIKE "version"')[0]->Value],
        ];
        // PHP相关参数
        $data['php'] = [
            ['name' => 'PHP 版本', 'value' => PHP_VERSION,],
            ['name' => '上传文件最大限制', 'value' => show("upload_max_filesize"),],
            ['name' => 'POST 提交最大限制', 'value' => show("post_max_size"),],
            ['name' => '脚本占用最大内存', 'value' => show("memory_limit"),],
        ];

        return $this->out(200, $data);
    }

    /**
     * 获取内存使用情况
     *
     * @return \Illuminate\Http\Response
     */
    public function memory()
    {
        $memory = (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage() / 1024 / 1024, 2).'MB';

        return $this->out(200, ['usage' => $memory]);
    }
}
