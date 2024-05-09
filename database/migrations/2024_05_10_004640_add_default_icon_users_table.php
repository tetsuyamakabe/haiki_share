<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultIconUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ユーザーテーブルのマイグレーション
        Schema::table('users', function (Blueprint $table) {
            // アイコンカラムのデフォルト値にデフォルト画像のパスを指定
            $table->string('icon')->default('default.png')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ユーザーテーブルのロールバック
        Schema::table('users', function (Blueprint $table) {
            // アイコンカラムをロールバック
            $table->string('icon')->nullable()->change();
        });
    }
}
