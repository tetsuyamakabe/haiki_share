<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Address;
use App\Models\Convenience;
use Faker\Generator as Faker;

$factory->define(Convenience::class, function (Faker $faker) {
    // ユーザーIDを生成
    $user_id = factory(User::class)->create()->id;
    // 住所IDを生成
    $address_id = factory(Address::class)->create()->id;
    return [
        'user_id' => $user_id,
        'branch_name' => $faker->company,
        'address_id' => $address_id,
    ];
});