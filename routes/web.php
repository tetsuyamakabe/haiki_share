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

// VueRouterのための{any}
Route::get('/{any}', function() { 
    return view('app');
})->where('any', '.*');

Route::get('/user/password/reset/{token}', 'Auth\User\ResetPasswordController@show')->name('user.password.reset'); // パスワード変更（古いパスワード・新しいパスワード入力）画面
Route::get('/convenience/password/reset/{token}', 'Auth\Convenience\ResetPasswordController@show')->name('convenience.password.reset'); // パスワード変更（古いパスワード・新しいパスワード入力）画面