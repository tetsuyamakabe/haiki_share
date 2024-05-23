<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeletedPurchasedColumnsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 商品テーブルのマイグレーション
        Schema::table('products', function (Blueprint $table) {
            // 外部キー制約の削除
            $table->dropForeign('products_purchased_id_foreign');
            // 購入者IDカラムを削除
            $table->dropColumn('purchased_id');
            // 購入状態カラムを削除
            $table->dropColumn('is_purchased');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 商品テーブルのロールバック
        Schema::table('products', function (Blueprint $table) {
            // 外部キー制約のロールバック
            $table->foreign('purchased_id')->references('id')->on('purchased')->onDelete('cascade');
            // 購入者IDカラムをロールバック
            $table->unsignedBigInteger('purchased_id');
            // 購入状態カラムをロールバック
            $table->boolean('is_purchased')->default(false);
        });
    }
}