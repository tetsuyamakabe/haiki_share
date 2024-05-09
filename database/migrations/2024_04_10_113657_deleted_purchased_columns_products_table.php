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
            // 購入状態カラムを削除
            $table->dropColumn('is_purchased');
            // 購入者IDカラムの外部キー制約を解除
            $table->dropForeign(['purchased_id']);
            // 購入者IDカラムを削除
            $table->dropColumn('purchased_id');
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
            // 購入状態カラムのロールバック
            $table->boolean('is_purchased')->default(false);
            // 購入者IDカラムのロールバック
            $table->unsignedBigInteger('purchased_id');
            // 購入者IDの外部キー制約をロールバック
            $table->foreign('purchased_id')->references('id')->on('users');
        });
    }
}
