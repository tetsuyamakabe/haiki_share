<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['product_id', 'is_purchased', 'purchased_id'];

    // 商品との関連付け（多対一の関係）
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 購入者との関連付け（多対一の関係）
    public function purchaser()
    {
        return $this->belongsTo(User::class, 'purchased_id');
    }
}

// mysql> desc purchases;
// +--------------+---------------------+------+-----+---------+----------------+
// | Field        | Type                | Null | Key | Default | Extra          |
// +--------------+---------------------+------+-----+---------+----------------+
// | id           | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
// | product_id   | bigint(20) unsigned | NO   | MUL | NULL    |                |
// | is_purchased | tinyint(1)          | NO   |     | 0       |                |
// | purchased_id | bigint(20) unsigned | NO   | MUL | NULL    |                |
// | created_at   | timestamp           | YES  |     | NULL    |                |
// | updated_at   | timestamp           | YES  |     | NULL    |                |
// +--------------+---------------------+------+-----+---------+----------------+
// 6 rows in set (0.01 sec)