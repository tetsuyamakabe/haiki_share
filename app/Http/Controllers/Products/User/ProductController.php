<?php

namespace App\Http\Controllers\Products\User;

use Carbon\Carbon;
use App\Models\Like;
use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Convenience;
use Illuminate\Http\Request;
use App\Models\ProductPicture;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\User\CancelNotification;
use App\Notifications\User\PurchaseNotification;

class ProductController extends Controller
{
    // 商品購入処理
    public function purchaseProduct($productId)
    {
        try {
            // 未認証の場合
            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // 購入者と購入商品情報を取得
            $purchaserId = Auth::id();
            $product = Product::find($productId);
            if ($product) {
                // 購入情報を保存
                $purchase = new Purchase();
                $purchase->product_id = $product->id;
                $purchase->purchased_id = $purchaserId;
                $purchase->is_purchased = true;
                $purchase->save();
                // 利用者（ログイン）ユーザー情報の取得
                $user = Auth::user();
                // コンビニユーザー情報の取得
                $convenience = Convenience::with('address')->findOrFail($product->convenience_store_id);
                // コンビニに紐づく住所情報の取得
                $address = $convenience->address;
                // 賞味期限日付のフォーマット（年月日）修正
                $expirationDate = Carbon::parse($product->expiration_date);
                $formattedExpirationDate = $expirationDate->format('Y年m月d日');
                // 利用者（ログイン）ユーザーに商品購入完了通知メールを送信
                Notification::send($user, new PurchaseNotification($product, $convenience, $formattedExpirationDate));
                // コンビニユーザーに商品購入完了通知メールを送信
                Notification::send($convenience->user, new PurchaseNotification($product, $convenience, $formattedExpirationDate));
                return response()->json(['message' => '商品を購入しました。'], 200);
            } else {
                return response()->json(['error' => '商品が見つかりません。'], 404);
            }
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '商品を購入失敗しました'], 500);
        }
    }

    // 商品購入キャンセル処理
    public function cancelProduct($productId)
    {
        try {
            // 未認証の場合
            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // 購入者と購入商品情報を取得
            $purchaserId = Auth::id();
            // \Log::info('$purchaserIdは、', [$purchaserId]);
            $product = Product::find($productId);
            // 商品が存在しない場合
            if (!$product) {
                return response()->json(['error' => '商品が見つかりません。'], 404);
            }
            // 購入情報を取得
            $purchase = Purchase::where('product_id', $product->id)->where('purchased_id', $purchaserId)->first();
            if ($purchase) {
                $purchase->delete(); // 物理削除
                // 利用者（ログイン）ユーザー情報の取得
                $user = Auth::user();
                // コンビニユーザー情報の取得
                $convenience = Convenience::with('address')->findOrFail($product->convenience_store_id);
                // コンビニに紐づく住所情報の取得
                $address = $convenience->address;
                // 賞味期限日付のフォーマット（年月日）修正
                $expirationDate = Carbon::parse($product->expiration_date);
                $formattedExpirationDate = $expirationDate->format('Y年m月d日');
                // 利用者（ログイン）ユーザーに商品キャンセル完了通知メールを送信
                Notification::send($user, new CancelNotification($product, $convenience, $formattedExpirationDate));
                // コンビニユーザーに商品キャンセル完了通知メールを送信
                Notification::send($convenience->user, new CancelNotification($product, $convenience, $formattedExpirationDate));
                return response()->json(['message' => '商品の購入をキャンセルしました。'], 200);
            }
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '商品を購入失敗しました'], 500);
        }
    }

    // 商品お気に入り登録処理
    public function likeProduct(Request $request, $productId)
    {
        // 未認証の場合
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        // 認証済みユーザーの取得
        $userId = auth()->id();
        // 認証済みユーザーがコンビニユーザーかどうかを判定
        $user = User::findOrFail($userId);
        if ($user->role === 'convenience') {
            return response()->json(['message' => 'コンビニユーザーはお気に入り登録できません'], 400);
        }
        // お気に入り登録された商品の中から商品IDとユーザーIDに一致する商品を取得
        $likedProduct = Like::where('product_id', $productId)->where('user_id', $userId)->first();
        // すでにお気に入り登録されている場合
        if ($likedProduct) {
            return response()->json(['message' => 'すでにお気に入り登録されています'], 400);
        }
        // お気に入り登録
        $like = Like::create(['product_id' => $productId, 'user_id' => $userId]);
        return response()->json(['message' => '商品をお気に入り登録しました'], 200);
    }

    // 商品お気に入り解除処理
    public function unlikeProduct(Request $request, $productId)
    {
        // 未認証の場合
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        // 認証済みユーザーの取得
        $userId = auth()->id();
        // 認証済みユーザーがコンビニユーザーかどうかを判定
        $user = User::findOrFail($userId);
        if ($user->role === 'convenience') {
            return response()->json(['message' => 'コンビニユーザーはお気に入り登録できません'], 400);
        }
        // お気に入り登録された商品の中から商品IDとユーザーIDに一致する商品を取得
        $likedProduct = Like::where('product_id', $productId)->where('user_id', $userId)->first();
        // お気に入り登録されていない場合
        if (!$likedProduct) {
            return response()->json(['message' => 'お気に入り登録が見つかりません'], 400);
        }
        // お気に入り解除
        $likedProduct->delete();
        return response()->json(['message' => '商品のお気に入り登録を解除しました'], 200);
    }

    // 購入した商品情報の取得
    public function getPurchasedProducts()
    {
        // 未認証の場合
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        // 認証済みユーザーの取得
        $user = Auth::user();
        // 購入した商品（と商品画像）を購入日の降順で15件ずつ取得（削除済みの商品は含めない）
        $products = $user->purchases()->with(['product' => function ($query) {
            $query->whereNull('deleted_at')->withCount('likes')->with('pictures', 'category'); 
        }])->orderBy('created_at', 'desc')->paginate(15)->toArray();
        $totalProducts = count($products['data']);
        $filteredProducts = [];
        foreach ($products['data'] as $like) {
            if ($like['product'] !== null) {
                $like['product']['liked'] = true; // お気に入りに追加されている商品なのでtrue
                $filteredProducts[] = $like;
            }
        }
        $products['data'] = $filteredProducts;
        $products['total'] = count($filteredProducts); // フィルタリング後の商品数を更新する

        return response()->json(['products' => $products], 200);
    }

    // お気に入り登録した商品情報の取得
    public function getLikedProducts()
    {
        // 未認証の場合
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        // 認証済みユーザーの取得
        $user = Auth::user();
        // お気に入り登録した商品（と商品画像）をお気に入り登録した日の降順で15件ずつ取得（削除済みの商品は含めない）
        $products = $user->likes()->with(['product' => function ($query) {
            $query->whereNull('products.deleted_at')->withCount('likes')->with('pictures', 'category'); 
        }])->orderBy('created_at', 'desc')->paginate(15)->toArray();
        // \Log::info('$productsは、', [$products]);
        // productsの中にlikedプロパティを含める
        $totalProducts = count($products['data']);
        $filteredProducts = [];
        foreach ($products['data'] as $like) {
            if ($like['product'] !== null) {
                $like['product']['liked'] = true; // お気に入りに追加されている商品なのでtrue
                $filteredProducts[] = $like;
            }
        }
        $products['data'] = $filteredProducts;
        $products['total'] = count($filteredProducts); // フィルタリング後の商品数を更新する
        // \Log::info('$productsは、', [$products]);
        return response()->json(['products' => $products], 200);
    }

    // 出品しているコンビニがある都道府県の取得
    public function getPrefecture()
    {
        // 住所テーブルからprefectureカラムの重複していない値だけを取り出す
        $prefectures = Address::pluck('prefecture')->unique()->values();
        return response()->json(['prefectures' => $prefectures], 200);
    }
}