<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 住所テーブルのマイグレーション
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 都道府県カラムを作成し、NOT NULL制約を追加
            $table->string('prefecture')->nullable(false);
            // 市区町村カラムを作成し、NOT NULL制約を追加
            $table->string('city')->nullable(false);
            // 番地カラムを作成し、NOT NULL制約を追加
            $table->string('address')->nullable(false);
            $table->timestamps();
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
        Schema::dropIfExists('addresses');
    }
}
