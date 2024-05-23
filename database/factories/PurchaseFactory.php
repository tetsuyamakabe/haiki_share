<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use Faker\Generator as Faker;

$factory->define(Purchase::class, function (Faker $faker) {
    // 商品IDの取得
    $product_id = factory(Product::class)->create()->id;
    // 購入者IDを生成
    $purchased_id = factory(User::class)->create()->id;
    return [
        'product_id' => $product_id,
        'purchased_id' => $purchased_id,
        'is_purchased' => $faker->boolean,
    ];
});