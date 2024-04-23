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

// 利用者側
Route::post('/user/register', 'Auth\User\RegisterController@create')->name('user.register'); // ユーザー登録処理
Route::post('/user/login', 'Auth\User\LoginController@login')->name('user.login'); // ログイン
Route::post('logout', 'Auth\User\LoginController@logout')->name('logout'); // ログアウト処理（仮）【TODO】 Vue実装
Route::post('/user/password/email', 'Auth\User\ForgotPasswordController@sendResetLinkEmail')->name('user.password.email'); // パスワード変更メール送信処理
Route::post('/user/password/reset', 'Auth\User\ResetPasswordController@reset')->name('user.password.update'); // パスワード変更処理

// コンビニ側
Route::post('/convenience/register', 'Auth\Convenience\RegisterController@create')->name('convenience.register'); // ユーザー登録処理
Route::post('/convenience/login', 'Auth\Convenience\LoginController@login')->name('convenience.login'); // ログイン
Route::post('logout', 'Auth\Convenience\LoginController@logout')->name('logout'); // ログアウト処理（仮）【TODO】 Vue実装
Route::post('/convenience/password/email', 'Auth\Convenience\ForgotPasswordController@sendResetLinkEmail')->name('convenience.password.email'); // パスワード変更メール送信処理
Route::post('/convenience/password/reset', 'Auth\Convenience\ResetPasswordController@reset')->name('convenience.password.update'); // パスワード変更処理