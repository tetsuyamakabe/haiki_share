<?php

namespace App\Http\Controllers\Products\User;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    // 商品一覧画面の表示
    public function showProductIndex()
    {
        return view('products.user.productIndex');
    }
}