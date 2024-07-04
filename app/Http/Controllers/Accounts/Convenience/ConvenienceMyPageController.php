<?php

namespace App\Http\Controllers\Accounts\Convenience;

use App\Models\User;
use App\Models\Product;
use App\Models\Convenience;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Convenience\ProfileRequest;

class ConvenienceMyPageController extends Controller
{
    // プロフィール情報の取得処理
    public function getProfile(Request $request)
    {
        try {
            // 未認証の場合
            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // 認証済みユーザー情報・コンビニ情報・住所情報の取得
            $user = Auth::user();
            $convenience = $user->convenience()->first();
            $address = $convenience->address;
            return response()->json(['user' => $user, 'convenience' => $convenience, 'address' => $address], 200);
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
            $user->name = $request->input('convenience_name'); // コンビニ名
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
            // コンビニ情報を取得
            $convenience = $user->convenience()->first();
            // コンビニ情報を更新
            if ($convenience) {
                $convenience->branch_name = $request->input('branch_name'); // 支店名
                // 住所情報の更新
                $address = $convenience->address;
                if ($address) {
                    $address->prefecture = $request->input('prefecture'); // 都道府県
                    $address->city = $request->input('city'); // 市区町村
                    $address->town = $request->input('town'); // 地名・番地
                    $address->building = $request->input('building'); // 建物名・部屋番号
                    $address->save();
                }
                $convenience->save();
            }
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
            if ($user->role === 'convenience') {
                $convenience = $user->convenience()->first();
                if ($convenience) {
                    $convenience->delete(); // コンビニ情報を論理削除
                    $convenience->address()->delete(); // 住所情報を論理削除
                }
            }
            $user->delete(); // ユーザー情報を論理削除
            return response()->json(['message' => 'ユーザーが退会しました。', 'user' => $user], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '退会できませんでした。'], 500);
        }
    }

    // マイページに表示する出品・購入商品情報の取得
    public function getMyProducts(Request $request)
    {
        try {
            // 認証済みユーザー情報の取得
            $user = Auth::user();
            // 未認証の場合
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // 関連づけられたコンビニ情報を取得
            $convenience = $user->convenience;
            // 出品した商品を最大5件表示（論理削除された商品も含む）
            $saleProducts = Product::with(['pictures', 'category'])->withCount('likes')
                ->where('convenience_store_id', $convenience->id)
                ->whereNull('deleted_at') // 論理削除された商品を除外する
                ->orderBy('created_at', 'desc') // 最新の投稿（降順）
                ->limit(5)->get();
            // 購入された商品を最大5件表示（論理削除された商品も含む）
            $purchasedProducts = Product::with(['pictures', 'category'])->withCount('likes')
                ->where('convenience_store_id', $convenience->id)
                ->whereHas('purchases', function ($query) {
                    $query->where('is_purchased', true);
                })
                ->whereNull('deleted_at') // 論理削除された商品を除外する
                ->orderBy('created_at', 'desc') // 最新の購入履歴（降順）
                ->limit(5)->get();

            return response()->json(['sale_products' => $saleProducts, 'purchased_products' => $purchasedProducts], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'マイページに表示する商品情報を取得できませんでした。'], 500);
        }
    }
}