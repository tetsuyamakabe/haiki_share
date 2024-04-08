<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 住所テーブルのマイグレーション
        Schema::table('addresses', function (Blueprint $table) {
            // SoftDeletesトレイトの論理削除カラムを追加
            $table->softDeletes()->after('building');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 住所テーブルのロールバック
        Schema::table('addresses', function (Blueprint $table) {
            // 論理削除カラムをロールバック
            $table->dropSoftDeletes();
        });
    }
}
