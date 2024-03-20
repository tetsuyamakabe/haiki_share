<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
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
            // アイコンカラムを追加し、nullを許可
            $table->string('icon')->nullable()->after('password');
            // 自己紹介文カラムを追加し、nullを許可
            $table->text('introduction')->nullable()->after('icon');
            // ロールカラムを追加し、NOT NULL制約を追加
            $table->string('role')->nullable(false)->after('introduction');
            // 論理削除カラムを追加し、NOT NULL制約を追加
            $table->boolean('is_deleted')->nullable(false)->default(false)->after('role');
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
            $table->dropColumn('icon');
            // 自己紹介文カラムをロールバック
            $table->dropColumn('introduction');
            // ロールカラムをロールバック
            $table->dropColumn('role');
            // 論理削除カラムをロールバック
            $table->dropColumn('is_deleted');
        });
    }
}
