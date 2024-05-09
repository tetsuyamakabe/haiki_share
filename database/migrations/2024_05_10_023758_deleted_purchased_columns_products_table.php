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
            // 購入者IDカラムのロールバック
            $table->unsignedBigInteger('purchased_id');
        });
    }
}
