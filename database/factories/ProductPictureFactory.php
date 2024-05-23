<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;
use App\Models\ProductPicture;

$factory->define(ProductPicture::class, function (Faker $faker) {
    // カテゴリIDを生成
    $product_id = factory(Product::class)->create()->id;
    return [
        'product_id' => $product_id,
        'file' => $faker->imageUrl()
    ];
});