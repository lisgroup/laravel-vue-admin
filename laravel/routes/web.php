<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return [
        'code' => 0,
        'reason' => 'success',
        'data' => null
    ];
});

Route::get('search', function () {
    // 为查看方便都转成数组
    dump('搜索关键词：汽车南站');
    $list = \App\Models\Line::search('汽车南站')->get()->toArray();
    dump($list);
});

Route::any('/api/_token', function() {
    return [
        'error_code' => 0,
        'reason' => '成功',
        'result' => csrf_token()
    ];
});

/******** 公共接口处理类 *********/
Route::group(['namespace' => 'Bus', 'prefix' => 'api'], function () {
    // 1. 首页测试
    Route::get('index', 'IndexController@index');
    Route::get('getList', 'IndexController@getList');
    Route::any('busLine', 'IndexController@busLine');
    Route::any('search', 'IndexController@search');
    Route::any('new', 'NewApiController@index');
    Route::any('line', 'NewApiController@getList');
    Route::any('new_line', 'NewApiController@newBusLine');
    Route::any('output', 'NewApiController@output');
    Route::any('test', 'NewApiController@jwt');

    // 工具类接口
    Route::any('tool', 'ToolController@tool');

    // 1. 获取七牛上传操作的 token
    Route::get('getToken', 'AutoController@getToken');
    // 2. 七牛 303 状态码 回调上传完成文件信息
    Route::get('qiniuCallback', 'AutoController@qiniuCallback');
    // 3. 百度 OCR
    Route::get('baiduOCR', 'AutoController@baiduOCR');
});

/******** 测试任务的接口地址 *********/
Route::group(['namespace' => 'Bus', 'prefix' => 'task'], function () {
    Route::get('index', 'TaskController@index');
    Route::any('api', 'TaskController@api');
    Route::any('line', 'TaskController@line');
});

// Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');

Route::any('login', function() {
   return ['code' => 200, 'data' => null, 'reason' => 'success'];
})->name('login');

// Route::get('/login', 'UserController@login')->name('login');

Route::get('/test', function () {
    $user = \App\User::find(3);
    $rs = Auth::login($user);
    dump($rs);
    return view('test');
});

Route::middleware('web')->get('/upload/bigfile', 'Bus\AutoController@loadView')->name('bigfile_view');
// bindings:不限制API访问次数限制，不需要 csrf_token 验证
Route::middleware('bindings')->post('/upload/bigfile', 'Bus\AutoController@upload')->name('bigfile_upload');
