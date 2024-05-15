<?php

namespace App\Http\Controllers\Products\User;

use Carbon\Carbon;
use App\Models\Like;
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
        // 購入者と購入商品情報を取得
        $purchaserId = Auth::id();
        \Log::info('$purchaserIdは、', [$purchaserId]);
        $product = Product::findOrFail($productId);

        // 購入情報を保存
        $purchase = new Purchase();
        $purchase->product_id = $product->id;
        $purchase->purchased_id = $purchaserId;
        $purchase->is_purchased = true;
        $purchase->save();

        // 利用者（ログイン）ユーザーに商品購入完了通知メールを送信
        $user = Auth::user();
        Notification::send($user, new PurchaseNotification($product));

        // コンビニユーザーに商品購入完了通知メールを送信
        $convenience = Convenience::findOrFail($product->convenience_store_id);
        Notification::send($convenience->user, new PurchaseNotification($product));

        return response()->json(['message' => '商品を購入しました。']);
    }

    // 商品購入キャンセル処理
    public function cancelProduct($productId)
    {
        // 購入者と購入商品情報を取得
        $purchaserId = Auth::id();
        \Log::info('$purchaserIdは、', [$purchaserId]);
        $product = Product::findOrFail($productId);
    
        // 購入情報を取得
        $purchase = Purchase::where('product_id', $product->id)->where('purchased_id', $purchaserId)->first();
    
        if ($purchase) {
            $purchase->delete();

            // 利用者（ログイン）ユーザーに商品キャンセル完了通知メールを送信
            $user = Auth::user();
            Notification::send($user, new CancelNotification($product));

            // コンビニユーザーに商品キャンセル完了通知メールを送信
            $convenience = Convenience::findOrFail($product->convenience_store_id);
            Notification::send($convenience->user, new CancelNotification($product));

            return response()->json(['message' => '商品の購入をキャンセルしました。']);
        } else {
            return response()->json(['error' => '商品が見つかりません。'], 404);
        }
    }

    // 商品お気に入り登録処理
    public function likeProduct(Request $request, $productId)
    {
        $userId = auth()->id();
        $likedProduct = Like::where('product_id', $productId)->where('user_id', $userId)->first();
        if ($likedProduct) {
            return response()->json(['message' => 'すでにお気に入り登録されています。'], 400);
        }
        $like = Like::create(['product_id' => $productId, 'user_id' => $userId]);

        return response()->json(['message' => '商品をお気に入り登録しました。']);
    }

    // 商品お気に入り解除処理
    public function unlikeProduct(Request $request, $productId)
    {
        $userId = auth()->id();
        $likedProduct = Like::where('product_id', $productId)->where('user_id', $userId)->first();
        if (!$likedProduct) {
            return response()->json(['message' => 'お気に入り登録が見つかりません。'], 400);
        }
        $likedProduct->delete();

        return response()->json(['message' => '商品のお気に入り登録を解除しました。']);
    }

    // 購入した商品情報の取得
    public function getPurchasedProducts()
    {
        $user = Auth::user();
        $products = $user->purchases()->with('product.pictures')->get();
        return response()->json(['products' => $products]);
    }

    // お気に入り登録した商品情報の取得
    public function getLikedProducts()
    {
        $user = Auth::user();
        $products = $user->likes()
            ->with(['product' => function ($query) {
                $query->withCount('likes');
            }, 'product.pictures'])
            ->orderBy('created_at', 'desc')
            ->paginate(4);
        // productsの中にlikedプロパティを含める
        foreach ($products as $like) {
            $like->product->liked = true; // お気に入りに追加されている商品なのでtrue
        }
        // \Log::info('$productsは、', [$products]);
        return response()->json(['products' => $products]);
    }

    // 出品しているコンビニがある都道府県の取得
    public function getPrefecture()
    {
        // 住所テーブルからprefectureカラムの重複していない値だけを取り出す
        $prefectures = Address::pluck('prefecture')->unique()->values();
        return response()->json(['prefectures' => $prefectures]);
    }
}