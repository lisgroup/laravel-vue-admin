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
use App\Models\Cron;

Route::get('/', function () {
    return view('welcome');
});

Route::get('search', function () {
    // 为查看方便都转成数组
    dump(Cron::all()->toArray());
});
