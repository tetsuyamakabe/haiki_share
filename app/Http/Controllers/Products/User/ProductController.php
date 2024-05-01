<?php

namespace App\Http\Controllers\Products\User;

use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Convenience;
use App\Models\ProductPicture;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\User\CancelNotification;
use App\Notifications\User\PurchaseNotification;

class ProductController extends Controller
{
    // 商品の購入状態の取得
    public function getPurchase($productId)
    {
        // 利用者と商品情報を取得
        $userId = Auth::id();
        $product = Product::findOrFail($productId);

        // 購入情報を取得
        $purchase = Purchase::where('product_id', $productId)->first();

        if (!$purchase) {
            // 購入情報が存在しない場合、商品はまだ誰も購入していない
            $purchaseStatus = '購入する';
        } else {
            if ($purchase->is_purchased == true && $purchase->purchased_id == $userId) {
                // ログインユーザーが購入した商品の場合
                $purchaseStatus = 'キャンセルする';
            } else {
                // 他ユーザーが購入した商品の場合
                $purchaseStatus = '購入済み';
            }
        }

        return response()->json(['status' => $purchaseStatus]);
    }

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
}