<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnAddressesTable extends Migration
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
            // 番地カラムを地名・番地カラムにカラム名変更
            $table->renameColumn('address', 'town');
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
            // 地名・番地カラムを番地カラムにカラム名を変更
            $table->renameColumn('town', 'address');
        });
    }
}
