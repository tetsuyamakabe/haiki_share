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

// フロントエンドのVueRouterのリクエスト処理
Route::get('/{any}', 'AppController@index')->where('any', '.*');

// 利用者側
Route::get('/user/password/reset/{token}', 'Auth\User\ResetPasswordController@show')->name('user.password.reset'); // パスワード変更メール

// コンビニ側
Route::get('/convenience/password/reset/{token}', 'Auth\Convenience\ResetPasswordController@show')->name('convenience.password.reset'); // パスワード変更メール