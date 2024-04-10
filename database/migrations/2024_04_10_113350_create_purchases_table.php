<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 購入者テーブルのマイグレーション
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 商品IDカラムを追加し、、NOT NULL制約を追加
            $table->unsignedBigInteger('product_id');
            // 購入状態カラムを追加し、NOT NULL制約を追加
            $table->boolean('is_purchased')->nullable(false)->default(false);
            // 購入者IDカラムを追加し、NOT NULL制約を追加
            $table->unsignedBigInteger('purchased_id')->nullable(false);
            $table->timestamps();

            // 商品IDカラムと商品テーブルのidカラムを外部キー制約の設定
            $table->foreign('product_id')->references('id')->on('products');
            // 購入者IDカラムとユーザーテーブルのidカラムを外部キー制約の設定
            $table->foreign('purchased_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 購入者テーブルのロールバック
        Schema::dropIfExists('purchases');
    }
}
