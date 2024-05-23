<?php

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use App\Models\Convenience;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::first();
        $convenience_stores = Convenience::first();

        // テスト用の商品情報の作成
        Product::create([
            'name' => '塩むすび',
            'price' => '110',
            'expiration_date' => '2024-05-25',
            'category_id' => $category->id,
            'convenience_store_id' => $convenience_stores->id,
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}