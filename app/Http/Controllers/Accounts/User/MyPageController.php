<?php

namespace App\Http\Controllers\Accounts\User;

use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\User\ContactRequest;
use App\Http\Requests\User\ProfileRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\User\ContactNotification;

class MyPageController extends Controller
{
    // プロフィール情報の取得処理
    public function getProfile(Request $request)
    {
        try {
            // 未認証の場合
            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // 認証済みユーザー情報の取得
            $user = Auth::user();
            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'プロフィール情報を取得できませんでした。'], 500);
        }
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
            if ($request->hasFile('avatar')) {
                $avatarImage = $request->file('avatar'); // 顔写真
                $dir = 'avatar'; // アップロード先S3フォルダ名
                $s3Upload = Storage::disk('s3')->putFile('/'.$dir, $avatarImage); // S3にファイルを保存
                $s3Url = Storage::disk('s3')->url($s3Upload); // アップロードファイルURLを取得
                $s3UploadFileName = explode("/", $s3Url)[5]; // $s3UrlからS3でのファイル保存名取得
                $s3Path = $dir.'/'.$s3UploadFileName; // アップロード先パスを取得
                $user->avatar = 'https://haikishare.com/' . $s3Path; // ファイルURLを保存
            }
            $user->save();
            return response()->json(['message' => 'プロフィール編集に成功しました。', 'user' => $user], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'プロフィール編集に失敗しました。'], 500);
        }
    }

    // 退会処理の実行
    public function withdraw(Request $request)
    {
        try {
            // 認証済みユーザー情報の取得
            $user = Auth::user();
            // 未認証の場合
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            Auth::logout(); // 自動ログアウト
            $user->delete(); // 論理削除を実行
            return response()->json(['message' => 'ユーザーが退会しました。', 'user' => $user], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '退会できませんでした。'], 500);
        }
    }

    // マイページに表示する購入・お気に入り商品情報の取得
    public function getMyProducts(Request $request)
    {
        try {
            // 認証済みユーザー情報の取得
            $user = Auth::user();
            // 認証済みユーザーIDの取得
            $userId = auth()->id();
            // 未認証の場合
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // マイページに購入した商品を最大5件表示（削除済みの商品は含めない）
            $purchasedProducts = $user->purchases()
                ->with(['product' => function ($query) {
                    $query->with(['pictures', 'category', 'likes']);
                    $query->withCount('likes');
                    $query->whereNull('deleted_at');
                }])
                ->orderBy('created_at', 'desc')// 最新の登録履歴（降順）
                ->limit(5)
                ->get();
            foreach ($purchasedProducts as $product) {
                // 商品情報にお気に入り情報を含める
                $like = Like::where('user_id', $userId)->where('product_id', $product->product->id)->first(); // Likeモデルから特定のユーザーがお気に入り登録した商品を取得
                $product->product->liked = $like ? true : false; // お気に入り登録（true）の場合はlikedプロパティをtrueにする
            }
            // マイページにお気に入り登録商品を最大5件表示（削除済みの商品は含めない）
            $likedProducts = $user->likes()
                ->with(['product' => function ($query) {
                    $query->with(['pictures', 'category', 'likes']);
                    $query->withCount('likes');
                    $query->whereNull('deleted_at');
                }])
                ->orderBy('created_at', 'desc')// 最新の登録履歴（降順）
                ->limit(5)
                ->get();
            foreach ($likedProducts as $product) {
                // 商品情報にお気に入り情報を含める
                $like = Like::where('user_id', $userId)->where('product_id', $product->product->id)->first(); // Likeモデルから特定のユーザーがお気に入り登録した商品を取得
                $product->product->liked = $like ? true : false; // お気に入り登録（true）の場合はlikedプロパティをtrueにする
            }
            return response()->json(['purchased_products' => $purchasedProducts, 'liked_products' => $likedProducts]);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'マイページに表示する商品情報を取得できませんでした。'], 500);
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
            return response()->json(['message' => 'お問い合わせ受付完了メールを送信しました。'], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'お問い合わせ受付完了メールを送信できませんでした。'], 500);
        }
    }
}