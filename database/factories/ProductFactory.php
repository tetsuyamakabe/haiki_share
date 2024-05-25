<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Convenience;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    // カテゴリIDを生成
    $category_id = factory(Category::class)->create()->id;
    // コンビニIDを生成
    $convenience_store_id = factory(Convenience::class)->create()->id;
    return [
        'name' => $faker->word,
        'price' => $faker->numberBetween(0, 500),
        'expiration_date' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+2 week')->format('Y-m-d'),
        'category_id' => $category_id,
        'convenience_store_id' => $convenience_store_id,
    ];
});