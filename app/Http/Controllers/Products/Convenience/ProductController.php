<?php

namespace App\Http\Controllers\Products\Convenience;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
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
        $product = Product::with(['pictures', 'category'])->findOrFail($productId);
        return response()->json(['product' => $product]);
    }

    // 商品カテゴリー情報の取得
    public function getCategories()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories]);
    }

    // 出品した商品情報の取得
    public function getConvenienceProduct(Request $request, $convenienceId)
    {
        \Log::info('$convenienceIdは、', [$convenienceId]);
        try {
            $product = Product::with('pictures')->where('convenience_store_id', $convenienceId)->paginate(10);
            return response()->json(['product' => $product]);
        } catch (\Exception $e) {
            return response()->json(['error' => '商品が見つかりません'], 404);
        }
    }

    // 商品出品処理（商品の投稿）
    public function createProduct(ProductSaleRequest $request, $userId)
    {
        \Log::info('createProductSaleメソッドが実行されます。');
        \Log::info('$userIdは、', [$userId]);
        \Log::info('リクエストは、:', $request->all());

        try {
            // 出品する商品情報の登録
            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->category_id = $request->category;
            $product->expiration_date = Carbon::parse($request->expiration_date);
            $product->convenience_store_id = $userId;
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
            $product->convenience_store_id = $product->convenience_store_id;

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