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
        });

        // 外部キー制約を削除
        Schema::table('products', function (Blueprint $table) {
            // 外部キー制約を削除
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
            // 購入者IDカラムを作成
            $table->unsignedBigInteger('purchased_id');
            // 外部キー制約を追加
            $table->foreign('purchased_id')->references('id')->on('users');
            // 購入状態カラムを追加
            $table->boolean('is_purchased')->default(false);
        });
    }
}