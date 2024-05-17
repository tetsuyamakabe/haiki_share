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
    public function getAllProducts(Request $request)
    {
        $userId = auth()->id();
        $query = Product::with(['pictures', 'category'])->withCount('likes'); // withCountでいいね数の取得

        // 出品しているコンビニがある都道府県
        $prefecture = $request->input('prefecture');
        if ($prefecture !== null) {
            // Convenienceモデル経由でAddressモデルのprefectureの値を取得
            $query->whereHas('convenience.address', function ($addressQuery) use ($prefecture) {
                // Addressモデルに指定された値とリクエストの値を持つレコードの検索
                $addressQuery->where('prefecture', $prefecture);
            });
        }

        // 最小価格
        $minPrice = $request->input('minprice');
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        // 最大価格
        $maxPrice = $request->input('maxprice');
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        // 賞味期限切れかどうか
        $expired = $request->input('expiration_date');
        if ($expired !== null) {
            $today = Carbon::today()->toDateString(); // 現在の日付を取得
            if ($expired === 'true') {
                $query->where('expiration_date', '<', $today); // 賞味期限切れの商品を検索
            } else {
                $query->where('expiration_date', '>=', $today); // 賞味期限内の商品を検索
            }
        }

        // コレクションを取得
        $products = $query->paginate(4);

        // 各商品に対して処理を行う
        foreach ($products as $product) {
            // 商品情報にお気に入り情報を含める
            $like = Like::where('user_id', $userId)->where('product_id', $product->id)->first();
            $product->liked = $like ? true : false;

            // 購入済み商品は「購入済み」ラベルをつける
            $is_purchased = Purchase::where('product_id', $product->id)->first();
            $product->is_purchased = $is_purchased ? true : false;
        }

        // \Log::info('$productは、', [$products]);

        return response()->json(['products' => $products]);
    }

    // 出品した商品情報の取得
    public function getConvenienceProducts(Request $request)
    {
        try {
            $user = Auth::user();
            $convenience = $user->convenience;
            if (!$convenience) {
                return response()->json(['error' => 'コンビニが見つかりません'], 404);
            }
            
            $products = Product::with('pictures')->where('convenience_store_id', $convenience->id)->paginate(10);
            
            // 購入情報を取得し、各商品に is_purchased プロパティを設定する
            foreach ($products as $product) {
                $purchase = Purchase::where('product_id', $product->id)->first();
                $purchasedId = $purchase ? $purchase->purchaser->id : null;
                $product->is_purchased = $purchase ? true : false;
                $product->purchased_id = $purchasedId;
            }

            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            return response()->json(['error' => '商品が見つかりません'], 404);
        }
    }

    // 購入された商品情報の取得
    public function getPurchasedProducts(Request $request)
    {
        $user = Auth::user();
        $convenience = $user->convenience;
        if (!$convenience) {
            return response()->json(['error' => 'コンビニが見つかりません'], 404);
        }
        $products = Product::with('pictures')
            ->where('convenience_store_id', $convenience->id)
            ->whereHas('purchases', function ($query) {
                $query->where('is_purchased', true);
            })->paginate(10);
        // \Log::info('$productsは、', [$products]);
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
                $fileContent = file_get_contents($picture->getRealPath());
                $fileName = sha1($fileContent) . '.' . $extension;
                // $fileName = sha1($picture->getClientOriginalName()) . '.' . $extension;
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