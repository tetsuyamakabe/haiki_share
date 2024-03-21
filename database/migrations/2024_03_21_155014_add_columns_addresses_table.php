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
            // 建物名・部屋番号カラムを追加し、nullを許可
            $table->string('building')->nullable()->after('town');
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
            //建物名・部屋番号カラムをロールバック
            $table->dropColumn('building');
        });
    }
}
