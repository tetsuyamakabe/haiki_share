<?php

namespace App\Http\Controllers\Products\Common;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPicture;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    // 商品一覧画面の表示（すべてのコンビニの商品一覧を表示）
    public function showAllProductIndex()
    {
        $products = Product::with('pictures')->get();
        return view('products.productAllIndex', ['products' => $products]);
    }
}