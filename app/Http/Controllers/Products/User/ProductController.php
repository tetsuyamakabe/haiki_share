<?php

namespace App\Http\Controllers\Products\User;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPicture;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    // 商品詳細画面の表示
    public function showProductDetail($productId)
    {
        // 商品IDから商品情報を取得
        $product = Product::findOrFail($productId);
        $categories = Category::all();
        $productPictures = ProductPicture::where('product_id', $productId)->get();
        foreach ($productPictures as $picture) {
            $picture->url = asset('storage/product_pictures/' . $picture->file);
        }

        return view('products.user.productDetail', ['product' => $product, 'productPictures' => $productPictures, 'categories' => $categories]);
    }
}