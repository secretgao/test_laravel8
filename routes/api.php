<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\AuthTest;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::any('/', function () {
    return '访问错误';
});
//登陆
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
//注册
Route::post('/register', [\App\Http\Controllers\UserController::class, 'register']);

//获取用户信息
Route::get('/user_info', [\App\Http\Controllers\UserController::class, 'getUserById']);
//更新用户邮箱
Route::put('/user_update', [\App\Http\Controllers\UserController::class, 'UpdateUserEmailById']);
//删除用户
Route::delete('/user_del', [\App\Http\Controllers\UserController::class, 'deleteUserById']);
