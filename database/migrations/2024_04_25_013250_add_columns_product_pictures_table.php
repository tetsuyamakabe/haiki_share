<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsProductPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 商品画像テーブルのマイグレーション
        Schema::table('product_pictures', function (Blueprint $table) {
            // SoftDeletesトレイトの論理削除カラムを追加
            $table->softDeletes()->after('file');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 商品画像テーブルのロールバック
        Schema::table('product_pictures', function (Blueprint $table) {
            // 論理削除カラムをロールバック
            $table->dropSoftDeletes();
        });
    }
}
