<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameIconToAvatarInUsersTable extends Migration
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
            // 顔写真カラム名の修正（iconカラムからavatarカラムに変更）
            $table->renameColumn('icon', 'avatar');
        });

        // 顔写真カラムのデフォルト値を変更
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->default('https://haikishare.com/avatar/default.png')->change();
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
            // 顔写真カラム名のロールバック（avatarカラムからiconカラムに変更）
            $table->renameColumn('avatar', 'icon');
        });

        // 顔写真カラムのデフォルト値のロールバック
        Schema::table('users', function (Blueprint $table) {
            $table->string('icon')->default('https://haikishare.com/icon/default.png')->change();
        });
    }
}