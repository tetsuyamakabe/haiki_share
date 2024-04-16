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
        // 商品情報を取得
        $products = Product::with('pictures')->get();
        // 商品詳細画面のリンクの配列
        $productDetailLinks = [];

        // ログインユーザーのroleから商品詳細画面のリンクを作る
        if (auth()->check()) { // ログインしているか？
            $role = auth()->user()->role; // roleのチェック

            // 各商品の商品詳細画面のリンクを作る
            foreach ($products as $product) {
                if ($role === 'user') {
                    $productDetailLinks[$product->id] = route('user.products.detail', ['productId' => $product->id]);
                } elseif ($role === 'convenience') {
                    $productDetailLinks[$product->id] = route('convenience.products.detail', ['productId' => $product->id]);
                }
            }

        } else {
            // ログインしていない場合は、各商品の商品詳細画面のリンクからhome画面に遷移する
            foreach ($products as $product) {
                $productDetailLinks[$product->id] = route('home');
            }
        }

        return view('products.productAllIndex', ['products' => $products, 'productDetailLinks' => $productDetailLinks]);
    }
}