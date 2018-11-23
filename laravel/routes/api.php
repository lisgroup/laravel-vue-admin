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

Route::group(['prefix' => 'user'], function($router) {
    Route::any('login', 'AuthController@login'); // ->middleware('admin.login');
    Route::any('logout', 'AuthController@logout');
    Route::any('refresh', 'AuthController@refresh');
    Route::any('info', 'AuthController@info');
});
