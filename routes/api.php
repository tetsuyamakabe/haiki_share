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

// コンビニ側
Route::post('/convenience/register', 'Auth\User\RegisterController@create')->name('convenience.register'); // ユーザー登録処理