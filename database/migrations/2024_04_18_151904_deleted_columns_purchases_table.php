<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeletedColumnsPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 購入者テーブルのマイグレーション
        Schema::table('purchases', function (Blueprint $table) {
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
        // 購入者テーブルのロールバック
        Schema::table('purchases', function (Blueprint $table) {
            // 購入状態カラムのロールバック
            $table->boolean('is_purchased')->default(false);
        });
    }
}
