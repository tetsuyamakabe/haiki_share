<?php

namespace App\Models;

use App\Models\Convenience;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['product_id','user_id'];

    // 商品モデルとの関連付け（多対一の関係）
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ユーザーモデルとの関連付け（多対一の関係）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

// likesテーブルのテーブル構成
// mysql> desc likes;
// +------------+---------------------+------+-----+---------+----------------+
// | Field      | Type                | Null | Key | Default | Extra          |
// +------------+---------------------+------+-----+---------+----------------+
// | id         | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
// | product_id | bigint(20) unsigned | NO   | MUL | NULL    |                |
// | user_id    | bigint(20) unsigned | NO   | MUL | NULL    |                |
// | created_at | timestamp           | YES  |     | NULL    |                |
// | updated_at | timestamp           | YES  |     | NULL    |                |
// +------------+---------------------+------+-----+---------+----------------+
// 5 rows in set (0.02 sec)