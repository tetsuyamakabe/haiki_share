<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // お気に入りテーブルのマイグレーション
        Schema::create('likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 商品IDカラムを作成し、NOT NULL制約を追加
            $table->unsignedBigInteger('product_id')->nullable(false);
            // ユーザーIDカラムを作成し、NOT NULL制約を追加
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->timestamps();

            // 商品IDカラムと商品テーブルのidカラムを外部キー制約の設定
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            // ユーザーIDカラムとユーザーテーブルのidカラムを外部キー制約の設定
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // お気に入りテーブルのロールバック
        Schema::dropIfExists('likes');
    }
}
