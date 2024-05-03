<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes    Web路由
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', 'StaticPagesController@home');
// Route::get('/help', 'StaticPagesController@help');
// Route::get('/help', 'StaticPagesController@help')->name('help');
// Route::get('/about', 'StaticPagesController@about');

// 获取主页
Route::get('/', 'StaticPagesController@home')->name('home');
// 获取帮助页面
Route::get('/help', 'StaticPagesController@help')->name('help');
// 获取关于页面
Route::get('/about', 'StaticPagesController@about')->name('about');
// 获取注册页面
Route::get('/signup', 'UsersController@create')->name('signup');
// 用户资源路由
Route::resource('users', 'UsersController');
// 获取登录页面
Route::get('login', 'SessionsController@create')->name('login');
// 创建新会话（登录应用）
Route::post('login', 'SessionsController@store')->name('login');
// 销毁会话（退出应用）
Route::delete('logout', 'SessionsController@destroy')->name('logout');
// 确认激活令牌
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');
