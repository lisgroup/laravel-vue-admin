<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 服务器信息
Route::any('admin/system', 'Api\IndexController@index');
Route::any('admin/report', 'Api\IndexController@report');

Route::group(['prefix' => 'user'], function($router) {
    Route::any('startCaptcha', 'AuthController@startCaptcha'); // 获取-极验验证码
    Route::any('verifyCaptcha', 'AuthController@verifyCaptcha'); // 校验-极验验证码
    Route::any('login', 'AuthController@login')->name('login'); // ->middleware('admin.login');
    Route::any('logout', 'AuthController@logout');
    Route::any('refresh', 'AuthController@refresh');
    Route::any('info', 'AuthController@info');
});

Route::group(['namespace' => 'Api'], function () {
    // 栏目管理
    Route::resource('category', 'CategoryController');
    // 导航管理
    Route::resource('nav', 'NavController');
    // 标签
    Route::resource('tag', 'TagController');
    // 文章管理
    Route::resource('article', 'ArticleController');
    // api_param
    Route::resource('api_param', 'ApiParamController');
    Route::resource('api_excel', 'ApiExcelController');
    Route::post('upload', 'ApiExcelController@upload');
    Route::any('start_task', 'ApiExcelController@startTask');
    Route::any('download_log', 'ApiExcelController@downloadLog');

    // 权限相关路由
    Route::get('permissions/store', 'PermissionController@store');
    Route::get('roles/store', 'RoleController@store');
    Route::resource('permissions', 'PermissionController');
    Route::resource('roles', 'RoleController');

    // 配置相关
    Route::resource('config', 'ConfigController');
});


Route::resource('crontask', 'Api\CronTaskController');
Route::resource('lines', 'Api\LinesController');
Route::resource('user', 'Api\UserController');

/************************* 车次任务相关操作 start ************************/
Route::any('bus_line_search', 'Api\LinesController@busLineSearch');
Route::post('postCrontask', 'Api\CronTaskController@postCrontask');
Route::any('bus_line_list', 'Api\LinesController@busLineList');
/************************* 车次任务相关操作 end  ************************/
// Route::any('table/list', 'Api\CronTaskController@list');

Route::any('line_search', 'Api\LinesController@search');
Route::any('user_password', 'Api\UserController@password');
Route::any('clearCache', 'Api\LinesController@clearCache');


// 登录权限获取路由
Route::any('routes', 'Api\IndexController@route');
