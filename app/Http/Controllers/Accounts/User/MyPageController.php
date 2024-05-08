<?php

namespace App\Http\Controllers\Accounts\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\ProfileRequest;

class MyPageController extends Controller
{
    // マイページに表示する購入・お気に入り商品情報の取得
    public function getMyProducts(Request $request)
    {
        try {
            $user = Auth::user();
            // マイページに購入した商品を最大5件表示
            $purchasedProducts = $user->purchases()->with('product.pictures')->limit(5)->get();
            // \Log::info('$purchasedProducts', [$purchasedProducts]);
            // マイページにお気に入り登録商品を最大5件表示
            $likedProducts = $user->likes()->with('product.pictures')->limit(5)->get();
            // \Log::info('$likedProducts', [$likedProducts]);

            return response()->json(['purchased_products' => $purchasedProducts, 'liked_products' => $likedProducts]);
        } catch (\Exception $e) {
            return response()->json(['error' => '商品が見つかりません'], 404);
        }
    }

    // プロフィール情報の取得処理
   public function getProfile(Request $request)
    {
        $user = Auth::user();
        \Log::info('$userは、', [$user]);
        return response()->json(['user' => $user]);
    }

    // プロフィール編集・更新処理
    public function editProfile(ProfileRequest $request)
    {
        \Log::info('リクエストは、:', $request->all());

        try {
            // ユーザー情報を取得
            $user = Auth::user();
            \Log::info('$userは、', [$user]);

            // ユーザー情報を更新
            $user->name = $request->input('name');
            $user->email = $request->input('email');

            // パスワードの入力がある場合のみ更新する
            $password = $request->input('password');
            if (!empty($password)) {
                $user->password = Hash::make($password);
            }

            $user->introduction = $request->input('introduction');

            // ファイルがアップロードされているか確認
            if ($request->hasFile('icon')) {
                $iconImage = $request->file('icon');
                $extension = $iconImage->getClientOriginalExtension(); // ファイルの拡張子を取得
                $fileName = sha1($iconImage->getClientOriginalName()) . '.' . $extension; // SHA-1ハッシュでファイル名を決定
                $iconImagePath = $iconImage->storeAs('public/icons', $fileName); // ファイルを保存
                $user->icon = $fileName; // ファイルパスを保存
            }
            $user->save();
            return response()->json(['message' => 'プロフィール編集に成功しました', 'user' => $user]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'プロフィール編集に失敗しました'], 500);
        }
    }

    // 退会処理の実行
    public function withdraw(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'ユーザーが見つかりません'], 404);
        }
        Auth::logout(); // 自動ログアウト
        $user->delete(); // 論理削除を実行
        return response()->json(['message' => 'ユーザーが退会しました', 'user' => $user]);
    }
}