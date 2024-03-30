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

Auth::routes();

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

// コンビニ側
Route::get('/convenience/register', 'Auth\Convenience\RegisterController@show')->name('convenience.register.show'); // ユーザー登録画面
Route::post('/convenience/register', 'Auth\Convenience\RegisterController@create')->name('convenience.register'); // ユーザー登録処理
Route::get('/convenience/login', 'Auth\Convenience\LoginController@show')->name('convenience.login.show'); // ログイン画面
Route::post('/convenience/login', 'Auth\Convenience\LoginController@login')->name('convenience.login'); // ログイン処理
Route::post('logout', 'Auth\Convenience\LoginController@logout')->name('logout'); // ログアウト処理（仮）
