<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\User\UserLoginController;
use App\Http\Controllers\Auth\User\UserRegisterController;
use App\Http\Controllers\Accounts\User\UserMyPageController;
use App\Http\Controllers\Products\User\UserProductController;
use App\Http\Controllers\Auth\User\UserResetPasswordController;
use App\Http\Controllers\Auth\User\UserForgotPasswordController;
use App\Http\Controllers\Auth\Convenience\ConvenienceLoginController;
use App\Http\Controllers\Auth\Convenience\ConvenienceRegisterController;
use App\Http\Controllers\Accounts\Convenience\ConvenienceMyPageController;
use App\Http\Controllers\Products\Convenience\ConvenienceProductController;
use App\Http\Controllers\Auth\Convenience\ConvenienceResetPasswordController;
use App\Http\Controllers\Auth\Convenience\ConvenienceForgotPasswordController;

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

Route::middleware(['cors'])->group(function () {

    // 商品
    Route::post('products', [ConvenienceProductController::class, 'getAllProducts']); // すべての商品情報の取得
    Route::get('/products/{productId}', [ConvenienceProductController::class, 'getProduct']); // 商品情報の取得
    Route::get('/products/detail/{productId}', [ConvenienceProductController::class, 'getProductDetail']); // （未ログインユーザーの）商品情報の取得
    Route::get('categories', [ConvenienceProductController::class, 'getCategories']); // 商品カテゴリー情報の取得
    Route::get('prefecture', [UserProductController::class, 'getPrefecture']); // 出品しているコンビニがある都道府県の取得
    Route::post('contact', [UserMyPageController::class, 'contact']); // お問い合わせ処理

    Route::get('/user', [UserLoginController::class, 'getUser']); // ユーザー情報の取得

    // 利用者側
    Route::post('/user/register', [UserRegisterController::class, 'create']); // ユーザー登録処理
    Route::post('/user/login', [UserLoginController::class, 'login']); // ログイン処理
    Route::post('/user/logout', [UserLoginController::class, 'logout']); // ログアウト処理
    Route::post('/user/password/email', [UserForgotPasswordController::class, 'sendResetLinkEmail']); // パスワード変更メール送信処理
    Route::post('/user/password/reset', [UserResetPasswordController::class, 'reset']); // パスワード変更処理

    Route::middleware(['auth', 'authcheckrole'])->group(function () {
        Route::get('/user/mypage/profile', [UserMyPageController::class, 'getProfile']); // プロフィール情報の取得処理
        Route::put('/user/mypage/profile', [UserMyPageController::class, 'editProfile']); // プロフィール編集・更新処理
        Route::post('/user/mypage/email', [UserMyPageController::class, 'sendEmailVerification']); // メールアドレス認証メール送信処理
        Route::delete('/user/mypage/withdraw', [UserMyPageController::class, 'withdraw']); // 退会処理
        Route::get('/user/mypage/products', [UserMyPageController::class, 'getMyProducts']); // マイページに表示する購入・お気に入り商品情報の取得
        Route::post('/user/products/purchase/{productId}', [UserProductController::class, 'purchaseProduct']); // 商品購入処理
        Route::delete('/user/products/purchase/cancel/{productId}', [UserProductController::class, 'cancelProduct']); // 商品購入キャンセル処理
        Route::post('/user/like/{productId}', [UserProductController::class, 'likeProduct']); // 商品お気に入り登録処理
        Route::post('/user/unlike/{productid}', [UserProductController::class, 'unlikeProduct']); // 商品お気に入り解除処理
        Route::get('/user/products/purchased', [UserProductController::class, 'getPurchasedProducts']); // 購入した商品情報の取得
        Route::get('/user/products/liked', [UserProductController::class, 'getLikedProducts']); // お気に入り登録した商品情報の取得
    });

    // コンビニ側
    Route::post('/convenience/register', [ConvenienceRegisterController::class, 'create']); // ユーザー登録処理
    Route::post('/convenience/login', [ConvenienceLoginController::class, 'login']); // ログイン処理
    Route::post('/convenience/logout', [ConvenienceLoginController::class, 'logout']); // ログアウト処理
    Route::post('/convenience/password/email', [ConvenienceForgotPasswordController::class, 'sendResetLinkEmail']); // パスワード変更メール送信処理
    Route::post('/convenience/password/reset', [ConvenienceResetPasswordController::class, 'reset']); // パスワード変更処理

    Route::middleware(['auth', 'authcheckrole'])->group(function () {
        Route::get('/convenience/mypage/profile', [ConvenienceMyPageController::class, 'getProfile']); // プロフィール情報の取得処理
        Route::put('/convenience/mypage/profile', [ConvenienceMyPageController::class, 'editProfile']); // プロフィール編集・更新処理
        Route::delete('/convenience/mypage/withdraw', [ConvenienceMyPageController::class, 'withdraw']); // 退会処理
        Route::get('/convenience/mypage/products', [ConvenienceMyPageController::class, 'getMyProducts']); // マイページに表示する出品・購入商品情報の取得
        Route::put('/convenience/products/sale', [ConvenienceProductController::class, 'createProduct']); // 商品出品処理（商品の投稿）
        Route::put('/convenience/products/edit/{productId}', [ConvenienceProductController::class, 'editProduct']); // 商品編集・更新処理
        Route::delete('/convenience/products/{productId}', [ConvenienceProductController::class, 'deleteProduct']); // 商品削除
        Route::get('/convenience/products', [ConvenienceProductController::class, 'getConvenienceProducts']); // 出品した商品情報の取得
        Route::get('/convenience/products/purchased', [ConvenienceProductController::class, 'getPurchasedProducts']); // 購入された商品情報の取得
    });

});