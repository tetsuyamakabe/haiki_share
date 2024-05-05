<?php

namespace App\Http\Controllers\Products\Convenience;

use Carbon\Carbon;
use App\Models\Like;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\ProductPicture;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Convenience\ProductEditRequest;
use App\Http\Requests\Convenience\ProductSaleRequest;

class ProductController extends Controller
{
    // 商品情報の取得処理
    public function getProduct(Request $request, $productId)
    {
        $userId = auth()->id();
        // \Log::info('$userIdは、', [$userId]);
        // 商品情報を取得
        $product = Product::with(['pictures', 'category'])->withCount('likes')->findOrFail($productId);
        // \Log::info('$productIdは、', [$productId]);
        // 購入情報を取得
        $purchase = Purchase::where('product_id', $productId)->first();
        // \Log::info('$purchaseは、', [$purchase]);
        // 購入者のIDを取得
        $purchasedId = $purchase ? $purchase->purchaser->id : null;

        // is_purchasedプロパティを設定
        $product->is_purchased = $purchase ? true : false;
        $product->purchased_id = $purchasedId;
        $like = Like::where('user_id', $userId)->where('product_id', $product->id)->first();
        $product->liked = $like ? true : false;
        // \Log::info('$productは、', [$product]);
        return response()->json(['product' => $product]);
    }

    // 商品カテゴリー情報の取得
    public function getCategories()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories]);
    }

    // すべての商品情報の取得
    public function getAllProducts()
    {
        $userId = auth()->id();
        $products = Product::with('pictures')->withCount('likes')->paginate(4); // withCountでいいね数の取得

        foreach ($products as $product) {
            // 商品情報にお気に入り情報を含める
            $like = Like::where('user_id', $userId)->where('product_id', $product->id)->first();
            $product->liked = $like ? true : false;

            // 購入済み商品は「購入済み」ラベルをつける
            $is_purchased = Purchase::where('product_id', $product->id)->first();
            $product->is_purchased = $is_purchased ? true : false;
        }
        // \Log::info('$productsは、', [$products]);
        return response()->json(['products' => $products]);
    }

    // 出品した商品情報の取得
    public function getConvenienceProduct(Request $request)
    {
        try {
            $user = Auth::user();
            $convenience = $user->convenience;
            if (!$convenience) {
                return response()->json(['error' => 'コンビニが見つかりません'], 404);
            }
            $product = Product::with('pictures')->where('convenience_store_id', $convenience->id)->paginate(10);
            return response()->json(['products' => $product]);
        } catch (\Exception $e) {
            return response()->json(['error' => '商品が見つかりません'], 404);
        }
    }

    // 購入された商品情報の取得
    public function getPurchaseProduct(Request $request)
    {
        // ログインユーザーのIDを取得
        $userId = auth()->id();

        // 購入された商品情報を取得
        $products = Product::whereHas('purchases', function ($query) use ($userId) {
            // 購入者IDがログインユーザーのIDで、購入フラグがtrueのクエリ
            $query->where('purchased_id', $userId)->where('is_purchased', true);
        })->paginate(4);

        return response()->json(['products' => $products]);
    }

    // 商品出品処理（商品の投稿）
    public function createProduct(ProductSaleRequest $request)
    {
        \Log::info('createProductSaleメソッドが実行されます。');
        \Log::info('リクエストは、:', $request->all());

        try {
            // 出品する商品情報の登録
            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->category_id = $request->category;
            $product->expiration_date = Carbon::parse($request->expiration_date);
            $user = Auth::user();
            $convenienceId = $user->convenience->id;
            $product->convenience_store_id = $convenienceId;
            $product->save();

            // 商品画像の処理
            if ($request->hasFile('product_picture')) {
                $picture = $request->file('product_picture');
                $extension = $picture->getClientOriginalExtension();
                $fileName = sha1($picture->getClientOriginalName()) . '.' . $extension;
                $picturePath = $picture->storeAs('public/product_pictures', $fileName);

                // 商品画像をproduct_picturesテーブルに保存
                $productPicture = new ProductPicture();
                $productPicture->product_id = $product->id;
                $productPicture->file = $fileName;
                $productPicture->save();
            }
            return response()->json(['message' => '商品の出品に成功しました', 'product' => $product]);
        } catch (\Exception $e) {
            return response()->json(['message' => '商品の出品に失敗しました'], 500);
        }
    }

    // 商品編集・更新処理
    public function editProduct(ProductEditRequest $request, $productId)
    {
        \Log::info('editProductメソッドが実行されます。');
        \Log::info('$productIdは、', [$productId]);
        \Log::info('リクエストは、:', $request->all());
        try {
            // 出品する商品の更新
            $product = Product::findOrFail($productId);
            $product->name = $request->input('name');
            $product->price = $request->input('price');
            $product->category_id = $request->input('category');
            $product->expiration_date = Carbon::parse($request->input('expiration_date'));
            $user = Auth::user();
            $convenienceId = $user->convenience->id;
            $product->convenience_store_id = $convenienceId;
            $product->save();

            // 商品画像の処理
            if ($request->hasFile('product_picture')) {
                $picture = $request->file('product_picture');
                $extension = $picture->getClientOriginalExtension();
                $fileName = sha1($picture->getClientOriginalName()) . '.' . $extension;
                $picturePath = $picture->storeAs('public/product_pictures', $fileName);

                $ProductPicture = ProductPicture::where('product_id', $product->id)->first();
                $ProductPicture->file = $fileName;
                $ProductPicture->save();
            }
            return response()->json(['message' => '商品の編集に成功しました', 'product' => $product]);
        } catch (\Exception $e) {
            return response()->json(['message' => '商品の編集に失敗しました'], 500);
        }
    }

    // 商品削除処理
    public function deleteProduct(Request $request, $productId)
    {
        \Log::info('deleteメソッドが実行されます。');
        
        // 商品を論理削除する
        $product = Product::findOrFail($productId);
        if (!$product) {
            return response()->json(['message' => '商品が見つかりません'], 404);
        }
        $product->delete();

        // 商品画像も論理削除する
        $productPictures = ProductPicture::where('product_id', $productId)->get();
        foreach ($productPictures as $productPicture) {
            $productPicture->delete();
        }

        return response()->json(['message' => '商品削除が完了しました']);
    }
}