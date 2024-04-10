<?php

namespace App\Http\Controllers\Products\Convenience;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductPicture;
use App\Http\Controllers\Controller;
use App\Http\Requests\Convenience\ProductSaleRequest;

class ProductController extends Controller
{
    // 商品一覧画面の表示
    public function showProductIndex()
    {
        return view('products.convenience.productIndex');
    }

    // 商品出品一覧画面の表示
    public function showProductSale(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $categories = Category::all();
        return view('products.convenience.productSale', compact('user', 'categories'));
    }


    // 商品出品処理（商品の投稿）
    public function createProductSale(ProductSaleRequest $request, $userId)
    {
        \Log::info('$userIdは、', [$userId]);
        \Log::info('リクエストは、:', $request->all());

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category;
        $product->expiration_date = Carbon::parse($request->expiration_date);
        $product->convenience_store_id = $userId;

        $product->save();

        // 商品画像の処理
        if ($request->hasFile('product_picture')) {
            $productPicture = $request->file('product_picture');
            $extension = $productPicture->getClientOriginalExtension();
            $fileName = sha1($productPicture->getClientOriginalName()) . '.' . $extension;
            $productPicturePath = $productPicture->storeAs('public/product_pictures', $fileName);

            // 商品画像をproduct_picturesテーブルに保存
            $productPictureModel = new ProductPicture();
            $productPictureModel->product_id = $product->id;
            $productPictureModel->file = $fileName;
            $productPictureModel->save();
        }

        return redirect()->route('convenience.mypage.show', ['user' => $userId])->with('flash_message', '商品出品が完了しました');
    }


}