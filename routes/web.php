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

// 利用者側
Route::get('/user/register', 'Auth\User\RegisterController@show')->name('user.register.show'); // ユーザー登録画面
Route::post('/user/register', 'Auth\User\RegisterController@create')->name('user.register'); // ユーザー登録処理

// コンビニ側
Route::get('/convenience/register', 'Auth\Convenience\RegisterController@show')->name('convenience.register.show'); // ユーザー登録画面
Route::post('/convenience/register', 'Auth\Convenience\RegisterController@create')->name('convenience.register'); // ユーザー登録処理

Route::get('/login?type={$type}', 'Auth\LoginController@show')->name('login.show');
Route::post('/login?type={$type}', 'Auth\LoginController@login')->name('login');