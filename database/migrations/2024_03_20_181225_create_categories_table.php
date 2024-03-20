<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // カテゴリーテーブルのマイグレーション
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            // カテゴリー名カラムを作成し、NOT NULL制約を追加
            $table->string('name')->nullable(false);
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
        // カテゴリーテーブルのロールバック
        Schema::dropIfExists('categories');
    }
}
