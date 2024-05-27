<?php

namespace App\Http\Controllers\Accounts\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\ContactRequest;
use App\Http\Requests\User\ProfileRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\User\ContactNotification;

class MyPageController extends Controller
{
    // プロフィール情報の取得処理
    public function getProfile(Request $request)
    {
        // 未認証の場合
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        // 認証済みユーザー情報の取得
        $user = Auth::user();
        return response()->json(['user' => $user], 200);
    }

    // プロフィール編集・更新処理
    public function editProfile(ProfileRequest $request)
    {
        try {
            // 認証済みユーザー情報の取得
            $user = Auth::user();
            // 未認証の場合
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // ユーザー情報を更新
            $user->name = $request->input('name'); // 名前
            $user->email = $request->input('email'); // メールアドレス
            // パスワードの入力がある場合のみ更新する
            $password = $request->input('password'); // パスワード
            if (!empty($password)) {
                $user->password = Hash::make($password);
            }
            $user->introduction = $request->input('introduction'); // 自己紹介文
            // ファイルがアップロードされているか確認
            if ($request->hasFile('icon')) {
                $iconImage = $request->file('icon'); // 顔写真
                $extension = $iconImage->getClientOriginalExtension(); // ファイルの拡張子を取得
                $fileName = sha1($iconImage->getClientOriginalName()) . '.' . $extension; // SHA-1ハッシュでファイル名を決定
                $iconImagePath = $iconImage->storeAs('public/icons', $fileName); // ファイルを保存
                $user->icon = $fileName; // ファイルパスを保存
            }
            $user->save();
            return response()->json(['message' => 'プロフィール編集に成功しました', 'user' => $user], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'プロフィール編集に失敗しました'], 500);
        }
    }

    // 退会処理の実行
    public function withdraw(Request $request)
    {
        // 認証済みユーザー情報の取得
        $user = Auth::user();
        // 未認証の場合
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        Auth::logout(); // 自動ログアウト
        $user->delete(); // 論理削除を実行
        return response()->json(['message' => 'ユーザーが退会しました', 'user' => $user], 200);
    }

    // マイページに表示する購入・お気に入り商品情報の取得
    public function getMyProducts(Request $request)
    {
        try {
            // 認証済みユーザー情報の取得
            $user = Auth::user();
            // 未認証の場合
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // マイページに購入した商品を最大5件表示
            $purchasedProducts = $user->purchases()->with('product.pictures')
                ->orderBy('created_at', 'desc') // 最新の購入履歴（降順）
                ->limit(5)->get();
            // \Log::info('$purchasedProducts', [$purchasedProducts]);

            // マイページにお気に入り登録商品を最大5件表示
            $likedProducts = $user->likes()->with('product.pictures')
                ->orderBy('created_at', 'desc') // 最新の登録履歴（降順）
                ->limit(5)->get();
            // \Log::info('$likedProducts', [$likedProducts]);
            return response()->json(['purchased_products' => $purchasedProducts, 'liked_products' => $likedProducts]);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '商品取得に失敗しました'], 500);
        }
    }

    // お問い合わせ処理
    public function contact(ContactRequest $request)
    {
        try {
            $name = $request->input('name');
            $email = $request->input('email');
            $contact = $request->input('contact');
            // メール送信
            Notification::route('mail', $request->email)->notify(new ContactNotification($name, $email, $contact));
            return response()->json(['message' => 'お問い合わせが送信されました'], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'お問い合わせが送信されませんでした'], 500);
        }
    }
}