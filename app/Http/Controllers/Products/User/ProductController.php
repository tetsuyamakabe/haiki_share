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
use App\Notifications\User\PurchaseNotification;

class ProductController extends Controller
{
    // 商品詳細画面の表示
    public function showProductDetail($productId)
    {
        // 【TODO】 ミドルウェアでログイン状態・roleのチェックをしているので不要。例外処理を実装するか。
        if (auth()->check()) { // ログインしているか？
            $role = auth()->user()->role; // roleのチェック
            if ($role === 'user') {
                \Log::debug('利用者ユーザーの商品詳細画面に遷移します。');
                // 商品IDから商品情報を取得
                $product = Product::findOrFail($productId);
                $categories = Category::all();
                $productPictures = ProductPicture::where('product_id', $productId)->get();
                foreach ($productPictures as $picture) {
                    $picture->url = asset('storage/product_pictures/' . $picture->file);
                }
                \Log::info('$productは、', [$product]);
                // 購入状態ログインしているユーザーが商品を購入しているか？
                $purchased = Purchase::where('product_id', $productId)
                                // ->where('purchased_id', auth()->id())
                                ->exists(); // クエリが空でない場合にtrueを返すメソッド
                \Log::info('$purchasedは、', [$purchased]);
                // dd($purchased);
                return view('products.user.productDetail', ['product' => $product, 'productPictures' => $productPictures, 'categories' => $categories, 'purchased' => $purchased]);
            } else {
                \Log::debug('コンビニユーザーです。');
                return redirect()->route('home'); // コンビニユーザーはhome画面に遷移
            }
        } else {
            \Log::debug('home画面に遷移します。');
            return redirect()->route('home'); // ログインしていないユーザーはhome画面に遷移
        }
    }


    // 購入処理（共通コントローラーに移動するか？メール通知メソッドを分けるか？）
    public function purchase($productId)
    {
        // 購入者と購入商品情報を取得
        $purchaserId = Auth::id();
        $product = Product::findOrFail($productId);

        // 購入情報を保存
        $purchase = new Purchase();
        $purchase->product_id = $product->id;
        $purchase->purchased_id = $purchaserId;
        $purchase->save();

        // 利用者ユーザーに通知通知メールを送信
        $user = Auth::user();
        Notification::send($user, new PurchaseNotification($product));

        // コンビニユーザーに通知メールを送信
        $convenience = Convenience::findOrFail($product->convenience_store_id);
        Notification::send($convenience->user, new PurchaseNotification($product));

        return response()->json(['message' => '商品を購入しました。']);
    }

    // 購入キャンセル処理
    public function purchaseCancel($productId)
    {
        // 購入キャンセル処理後、通知メールが届くようにする。
    }
}