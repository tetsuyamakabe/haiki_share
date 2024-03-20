<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 商品画像テーブルのマイグレーション
        Schema::create('product_pictures', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 商品IDカラムを作成し、NOT NULL制約を追加
            $table->unsignedBigInteger('product_id')->nullable(false);
            // 画像ファイルカラムを作成し、NOT NULL制約を追加
            $table->string('file')->nullable(false);
            $table->timestamps();

            // 商品IDカラムと商品テーブルのidカラムを外部キー制約の設定
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 商品画像テーブルのロールバック
        Schema::dropIfExists('product_pictures');
    }
}
