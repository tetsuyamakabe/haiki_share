<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 商品テーブルのマイグレーション
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 商品名カラムを追加し、NOT NULL制約を追加
            $table->string('name')->nullable(false);
            // 商品価格カラムを追加し、NOT NULL制約を追加
            $table->integer('price')->nullable(false);
            // 賞味期限カラムを追加し、NOT NULL制約を追加
            $table->date('expiration_date')->nullable(false);
            // 購入状態カラムを追加し、NOT NULL制約を追加
            $table->boolean('is_purchased')->nullable(false)->default(false);
            // 購入者IDカラムを追加し、NOT NULL制約を追加
            $table->unsignedBigInteger('purchased_id')->nullable(false);
            // カテゴリーIDカラムを追加し、NOT NULL制約を追加
            $table->unsignedBigInteger('category_id')->nullable(false);
            // コンビニIDカラムを追加し、NOT NULL制約を追加
            $table->unsignedBigInteger('convenience_store_id')->nullable(false);
            // 論理削除カラムを追加し、NOT NULL制約を追加
            $table->boolean('is_deleted')->nullable(false)->default(false);
            $table->timestamps();

            // 購入者IDカラムとユーザーテーブルのidカラムを外部キー制約の設定
            $table->foreign('purchased_id')->references('id')->on('users');
            // カテゴリーIDカラムとカテゴリーテーブルのidカラムを外部キー制約の設定
            $table->foreign('category_id')->references('id')->on('categories');
            // コンビニIDカラムとコンビニテーブルのidカラムを外部キー制約の設定
            $table->foreign('convenience_store_id')->references('id')->on('convenience_stores');
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
        Schema::dropIfExists('products');
    }
}
