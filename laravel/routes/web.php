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
    return view('welcome');
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
   return ['code' => 200, 'data' => 'no user', 'reason' => 'error'];
})->name('login');

// Route::get('/login', 'UserController@login')->name('login');
