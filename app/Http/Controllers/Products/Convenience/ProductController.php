<?php

namespace App\Http\Controllers\Products\Convenience;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductPicture;
use App\Http\Controllers\Controller;
use App\Http\Requests\Convenience\ProductEditRequest;
use App\Http\Requests\Convenience\ProductSaleRequest;

class ProductController extends Controller
{
    // 商品一覧画面の表示
    public function showProductIndex()
    {
        // 商品一覧を取得し、関連付けられた画像もロードする
        $products = Product::with('pictures')->get();
        return view('products.convenience.productIndex', ['products' => $products]);
    }

    // 商品出品一覧画面の表示
    public function showProductSale(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $categories = Category::all();
        return view('products.convenience.productSale', ['user' => $userId, 'categories' => $categories]);
    }

    // 商品出品処理（商品の投稿）
    public function createProductSale(ProductSaleRequest $request, $userId)
    {
        \Log::info('createProductSaleメソッドが実行されます。');
        \Log::info('$userIdは、', [$userId]);
        \Log::info('リクエストは、:', $request->all());

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

        return redirect()->route('convenience.mypage.show', ['user' => $userId])->with('flash_message', '商品出品が完了しました');
    }

    // 商品編集画面の表示
    public function showProductEdit(Request $request, $productId)
    {
        \Log::info('showProductEditメソッドが実行されます。');
        \Log::info('$productIdは、', [$productId]);
        // dd($productId);
        $product = Product::findOrFail($productId);
        // dd($product);
        $categories = Category::all();
        return view('products.convenience.productEdit', ['product' => $product, 'categories' => $categories]);
    }

    // 商品編集・更新処理
    public function editProduct(ProductEditRequest $request, $productId)
    {
        \Log::info('editProductメソッドが実行されます。');
        \Log::info('$productIdは、', [$productId]);
        \Log::info('リクエストは、:', $request->all());

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

            $productPicture->product_id = $product->id;
            $productPicture->file = $fileName;
            $productPicture->save();
        }
        return redirect()->route('convenience.productIndex.show')->with('flash_message', '商品の編集が完了しました');
    }
}