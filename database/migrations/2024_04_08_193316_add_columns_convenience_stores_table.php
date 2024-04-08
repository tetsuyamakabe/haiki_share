<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsConvenienceStoresTable extends Migration
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
            // SoftDeletesトレイトの論理削除カラムを追加
            $table->softDeletes()->after('address_id');
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
            $table->dropSoftDeletes();
        });
    }
}
