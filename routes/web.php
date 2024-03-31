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

Auth::routes(['reset' => false]); // デフォルトのパスワードリマインダー機能を無効化

Route::get('/home', 'HomeController@index')->name('home');

// 未認証ユーザーはwelcome画面に遷移（仮）
Route::get('/', function () {
    return view('welcome');
})->name('login');

// 利用者側
Route::get('/user/register', 'Auth\User\RegisterController@show')->name('user.register.show'); // ユーザー登録画面
Route::post('/user/register', 'Auth\User\RegisterController@create')->name('user.register'); // ユーザー登録処理
Route::get('/user/login', 'Auth\User\LoginController@show')->name('user.login.show'); // ログイン画面
Route::post('/user/login', 'Auth\User\LoginController@login')->name('user.login'); // ログイン処理
Route::post('logout', 'Auth\User\LoginController@logout')->name('logout'); // ログアウト処理（仮）
Route::get('/user/password/reset', 'Auth\User\ForgotPasswordController@show')->name('user.password.request'); // パスワード変更（メールアドレス入力）画面
Route::post('/user/password/email', 'Auth\User\ForgotPasswordController@sendResetLinkEmail')->name('user.password.email'); // パスワード変更メール送信処理
Route::get('/user/password/reset/{token}', 'Auth\User\ResetPasswordController@show')->name('user.password.reset'); // パスワード変更（古いパスワード・新しいパスワード入力）画面
Route::post('/user/password/reset', 'Auth\User\ResetPasswordController@reset')->name('user.password.update'); // パスワード変更処理

// コンビニ側
Route::get('/convenience/register', 'Auth\Convenience\RegisterController@show')->name('convenience.register.show'); // ユーザー登録画面
Route::post('/convenience/register', 'Auth\Convenience\RegisterController@create')->name('convenience.register'); // ユーザー登録処理
Route::get('/convenience/login', 'Auth\Convenience\LoginController@show')->name('convenience.login.show'); // ログイン画面
Route::post('/convenience/login', 'Auth\Convenience\LoginController@login')->name('convenience.login'); // ログイン処理
Route::post('logout', 'Auth\Convenience\LoginController@logout')->name('logout'); // ログアウト処理（仮）
Route::get('/convenience/password/reset', 'Auth\Convenience\ForgotPasswordController@show')->name('convenience.password.request'); // パスワード変更画面（メールアドレス入力画面）
Route::post('/convenience/password/email', 'Auth\Convenience\ForgotPasswordController@sendResetLinkEmail')->name('convenience.password.email'); // パスワード変更メール送信処理
Route::get('/convenience/password/reset/{token}', 'Auth\Convenience\ResetPasswordController@show')->name('convenience.password.reset'); // パスワード変更（古いパスワード・新しいパスワード入力）画面
Route::post('/convenience/password/reset', 'Auth\Convenience\ResetPasswordController@reset')->name('convenience.password.update'); // パスワード変更処理
