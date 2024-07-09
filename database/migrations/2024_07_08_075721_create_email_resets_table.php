<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // メールアドレスリセットテーブルのマイグレーション
        Schema::create('email_resets', function (Blueprint $table) {
            $table->bigIncrements('id');
            // メールアドレスを更新したユーザーIDカラムを追加
            $table->integer('user_id');
            // 新しいメールアドレスカラムを追加
            $table->string('new_email');
            // トークンカラムを追加
            $table->string('token');
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
        // メールアドレスリセットテーブルのロールバック
        Schema::dropIfExists('email_resets');
    }
}
