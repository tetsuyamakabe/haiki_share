<?php

use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductPicture;
use Illuminate\Database\Seeder;

class ProductPicturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Product::first();

        // テスト用の商品画像情報の作成
        ProductPicture::create([
            'product_id' => $product->id,
            'file' => 'product_picture.jpg',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
// mysql> desc product_pictures;
// +------------+---------------------+------+-----+---------+----------------+
// | Field      | Type                | Null | Key | Default | Extra          |
// +------------+---------------------+------+-----+---------+----------------+
// | id         | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
// | product_id | bigint(20) unsigned | NO   | MUL | NULL    |                |
// | file       | varchar(255)        | NO   |     | NULL    |                |
// | deleted_at | timestamp           | YES  |     | NULL    |                |
// | created_at | timestamp           | YES  |     | NULL    |                |
// | updated_at | timestamp           | YES  |     | NULL    |                |
// +------------+---------------------+------+-----+---------+----------------+
// 6 rows in set (0.00 sec)