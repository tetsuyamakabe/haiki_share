<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Like;
use App\Models\User;
use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Like::class, function (Faker $faker) {
    // 商品IDの取得
    $product_id = factory(Product::class)->create()->id;
    // ユーザーIDを生成
    $user_id = factory(User::class)->create()->id;
    return [
        'product_id' => $product_id,
        'user_id' => $user_id,
    ];
});