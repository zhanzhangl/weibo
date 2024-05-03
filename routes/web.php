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
// 提交创建新会话（登录应用）
Route::post('login', 'SessionsController@store')->name('login');
// 提交销毁会话（退出应用）
Route::delete('logout', 'SessionsController@destroy')->name('logout');
// 获取激活令牌
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');
// 获取密码重置链接页面 （填写 Email 的表单）
Route::get('password/reset',  'PasswordController@showLinkRequestForm')->name('password.request');
// 提交密码重置链接页面 （处理提交的表单，成功的话就发送邮件，附带 Token 的链接）
Route::post('password/email',  'PasswordController@sendResetLinkEmail')->name('password.email');
// 获取密码重置令牌页面 （显示更新密码的表单，包含 token）
Route::get('password/reset/{token}',  'PasswordController@showResetForm')->name('password.reset');
// 提交密码重置令牌页面 （对提交过来的 token 和 email 数据进行配对，正确的话更新密码。）
Route::post('password/reset',  'PasswordController@reset')->name('password.update');
// 微博动态控制器路由
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);
// 获取用户关注列表
Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');
// 获取用户粉丝列表
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');
// 获取用户粉丝列表
Route::post('/users/followers/{user}', 'FollowersController@store')->name('followers.store');
// 取消用户粉丝列表
Route::delete('/users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy');
