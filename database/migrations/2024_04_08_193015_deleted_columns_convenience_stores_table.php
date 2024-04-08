<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeletedColumnsConvenienceStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // コンビニテーブルのマイグレーション
        Schema::table('convenience_stores', function (Blueprint $table) {
            // SoftDeletesトレイト使用のため、論理削除カラムを削除
            $table->dropColumn('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // コンビニテーブルのロールバック
        Schema::table('convenience_stores', function (Blueprint $table) {
            // 論理削除カラムをロールバック
            $table->boolean('is_deleted')->default(false);
        });
    }
}
