<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColomnsPurchasesTable extends Migration
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
            // 購入状態カラムを追加し、NOT NULL制約を追加
            $table->boolean('is_purchased')->nullable(false)->default(false)->after('purchased_id');
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
            // 購入状態カラムをロールバック
            $table->dropColumn('is_purchased');
        });
    }
}
