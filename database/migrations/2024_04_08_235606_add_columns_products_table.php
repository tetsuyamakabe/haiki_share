<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsProductsTable extends Migration
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
            // SoftDeletesトレイトの論理削除カラムを追加
            $table->softDeletes()->after('convenience_store_id');
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
            // 論理削除カラムをロールバック
            $table->dropSoftDeletes();
        });
    }
}
