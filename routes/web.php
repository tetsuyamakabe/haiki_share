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
Route::get('/register?type={$type}', 'RegisterController@show')->name('register');
Route::get('/login', 'Auth\LoginController@show')->name('login.show');
Route::post('/login?type={$type}', 'Auth\LoginController@login')->name('login')->middleware('authcheckrole');
