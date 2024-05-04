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

// 商品
Route::get('/products', 'Products\Convenience\ProductController@getAllProducts'); // すべての商品情報の取得
Route::get('/products/{productId}', 'Products\Convenience\ProductController@getProduct'); // 商品情報の取得
Route::get('categories', 'Products\Convenience\ProductController@getCategories'); // 商品カテゴリー情報の取得
Route::get('like', 'Products\Convenience\ProductController@getLike'); // 商品のお気に入り情報の取得

Route::get('/user', fn() => Auth::user())->name('user');

// 利用者側
Route::post('/user/register', 'Auth\User\RegisterController@create'); // ユーザー登録処理
Route::post('/user/login', 'Auth\User\LoginController@login'); // ログイン処理
Route::post('/user/logout', 'Auth\User\LoginController@logout'); // ログアウト処理
Route::post('/user/password/email', 'Auth\User\ForgotPasswordController@sendResetLinkEmail'); // パスワード変更メール送信処理
Route::post('/user/password/reset', 'Auth\User\ResetPasswordController@reset'); // パスワード変更処理

Route::middleware(['auth', 'authcheckrole'])->group(function () {
    Route::get('/user/mypage/profile', 'Accounts\User\MyPageController@getProfile'); // プロフィール情報の取得処理
    Route::put('/user/mypage/profile', 'Accounts\User\MyPageController@editProfile'); // プロフィール編集・更新処理
    Route::delete('/user/mypage/withdraw', 'Accounts\User\MyPageController@withdraw'); // 退会処理
    Route::get('/user/products/purchase/{productId}', 'Products\User\ProductController@getPurchase'); // 商品の購入状態の取得
    Route::post('/user/products/purchase/{productId}', 'Products\User\ProductController@purchaseProduct'); // 商品購入処理
    Route::delete('/user/products/purchase/cancel/{productId}', 'Products\User\ProductController@cancelProduct'); // 商品購入キャンセル処理
    Route::post('/user/like/{productId}', 'Products\User\ProductController@likeProduct'); // 商品お気に入り登録処理
    Route::post('/user/unlike/{productid}', 'Products\User\ProductController@unlikeProduct'); // 商品お気に入り解除処理
});

// コンビニ側
Route::post('/convenience/register', 'Auth\Convenience\RegisterController@create'); // ユーザー登録処理
Route::post('/convenience/login', 'Auth\Convenience\LoginController@login'); // ログイン処理
Route::post('/convenience/logout', 'Auth\Convenience\LoginController@logout'); // ログアウト処理
Route::post('/convenience/password/email', 'Auth\Convenience\ForgotPasswordController@sendResetLinkEmail'); // パスワード変更メール送信処理
Route::post('/convenience/password/reset', 'Auth\Convenience\ResetPasswordController@reset'); // パスワード変更処理

Route::middleware(['auth', 'authcheckrole'])->group(function () {
    Route::get('/convenience/mypage/profile', 'Accounts\Convenience\MyPageController@getProfile'); // プロフィール情報の取得処理
    Route::put('/convenience/mypage/profile', 'Accounts\Convenience\MyPageController@editProfile'); // プロフィール編集・更新処理
    Route::delete('/convenience/mypage/withdraw', 'Accounts\Convenience\MyPageController@withdraw'); // 退会処理
    Route::put('/convenience/products/sale', 'Products\Convenience\ProductController@createProduct'); // 商品出品処理（商品の投稿）
    Route::put('/convenience/products/edit/{productId}', 'Products\Convenience\ProductController@editProduct'); // 商品編集・更新処理
    Route::delete('/convenience/products/{productId}', 'Products\Convenience\ProductController@deleteProduct'); // 商品削除
    Route::get('/convenience/products', 'Products\Convenience\ProductController@getConvenienceProduct'); // 出品した商品情報の取得
    Route::get('/convenience/products/purchased', 'Products\Convenience\ProductController@getPurchaseProduct'); // 購入された商品情報の取得
});