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
Route::get('/register?type={$type}', 'Auth\RegisterController@show')->name('register');
Route::get('/login', 'Auth\LoginController@show')->name('login.show');
Route::middleware('authcheckrole')->group(function () {
    Route::post('/login?type={$type}', 'Auth\LoginController@login')->name('login');
});


// makabetetsuya@makabetetsuyanoMacBook-Pro haiki_share % php artisan route:list
// +--------+----------+------------------------+------------------+------------------------------------------------------------------------+-------------------------+
// | Domain | Method   | URI                    | Name             | Action                                                                 | Middleware              |
// +--------+----------+------------------------+------------------+------------------------------------------------------------------------+-------------------------+
// |        | GET|HEAD | /                      |                  | Closure                                                                | web                     |
// |        | GET|HEAD | api/user               |                  | Closure                                                                | api,auth:api            |
// |        | GET|HEAD | home                   | home             | App\Http\Controllers\HomeController@index                              | web,auth                |
// |        | GET|HEAD | login                  | login.show       | App\Http\Controllers\Auth\LoginController@show                         | web,guest               |
// |        | POST     | login                  |                  | App\Http\Controllers\Auth\LoginController@login                        | web,guest               |
// |        | POST     | login?type={$type}     | login            | App\Http\Controllers\Auth\LoginController@login                        | web,authcheckrole,guest |
// |        | POST     | logout                 | logout           | App\Http\Controllers\Auth\LoginController@logout                       | web                     |
// |        | POST     | password/email         | password.email   | App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail  | web,guest               |
// |        | GET|HEAD | password/reset         | password.request | App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm | web,guest               |
// |        | POST     | password/reset         | password.update  | App\Http\Controllers\Auth\ResetPasswordController@reset                | web,guest               |
// |        | GET|HEAD | password/reset/{token} | password.reset   | App\Http\Controllers\Auth\ResetPasswordController@showResetForm        | web,guest               |
// |        | GET|HEAD | register               | register         | App\Http\Controllers\Auth\RegisterController@showRegistrationForm      | web,guest               |
// |        | POST     | register               |                  | App\Http\Controllers\Auth\RegisterController@register                  | web,guest               |
// |        | GET|HEAD | register?type={$type}  | register         | App\Http\Controllers\Auth\RegisterController@show                      | web,guest               |
// +--------+----------+------------------------+------------------+------------------------------------------------------------------------+-------------------------+