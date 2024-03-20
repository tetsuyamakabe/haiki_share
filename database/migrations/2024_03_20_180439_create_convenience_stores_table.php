<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConvenienceStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // コンビニテーブルのマイグレーション
        Schema::create('convenience_stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            // ユーザーIDカラムを作成し、NOT NULL制約を追加
            $table->unsignedBigInteger('user_id')->nullable(false);
            // 支店名カラムを作成し、NOT NULL制約を追加
            $table->string('branch_name')->nullable(false);
            // 住所IDカラムを作成し、NOT NULL制約を追加
            $table->unsignedBigInteger('address_id')->nullable(false);
            // 論理削除カラムを追加し、NOT NULL制約を追加
            $table->boolean('is_deleted')->nullable(false)->default(false);
            $table->timestamps();

            // ユーザーIDカラムとユーザーテーブルのidカラムを外部キー制約の設定
            $table->foreign('user_id')->references('id')->on('users');
            // 住所IDカラムと住所テーブルのidを外部キー制約の設定
            $table->foreign('address_id')->references('id')->on('addresses');
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
        Schema::dropIfExists('convenience_stores');
    }
}
