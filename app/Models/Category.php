<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    // 商品モデルとの関連付け（一対多の関係）
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

// categoriesテーブルのテーブル構成
// mysql> desc categories;
// +------------+---------------------+------+-----+---------+----------------+
// | Field      | Type                | Null | Key | Default | Extra          |
// +------------+---------------------+------+-----+---------+----------------+
// | id         | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
// | name       | varchar(255)        | NO   |     | NULL    |                |
// | created_at | timestamp           | YES  |     | NULL    |                |
// | updated_at | timestamp           | YES  |     | NULL    |                |
// +------------+---------------------+------+-----+---------+----------------+
// 4 rows in set (0.01 sec)
