<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use App\Models\Convenience;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'price', 'expiration_date', 'category_id', 'convenience_store_id'
    ];

    protected $dates = ['expiration_date'];

    // カテゴリーモデルとの関連付け（一対一の関係）
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // コンビニモデルとの関連付け（多対一の関係）
    public function convenience()
    {
        return $this->belongsTo(Convenience::class);
    }

    // 商品画像との関連付け（一対多の関係）
    public function pictures()
    {
        return $this->hasMany(ProductPicture::class);
    }
}

// mysql> desc products;
// +----------------------+---------------------+------+-----+---------+----------------+
// | Field                | Type                | Null | Key | Default | Extra          |
// +----------------------+---------------------+------+-----+---------+----------------+
// | id                   | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
// | name                 | varchar(255)        | NO   |     | NULL    |                |
// | price                | int(11)             | NO   |     | NULL    |                |
// | expiration_date      | date                | NO   |     | NULL    |                |
// | category_id          | bigint(20) unsigned | NO   | MUL | NULL    |                |
// | convenience_store_id | bigint(20) unsigned | NO   | MUL | NULL    |                |
// | deleted_at           | timestamp           | YES  |     | NULL    |                |
// | created_at           | timestamp           | YES  |     | NULL    |                |
// | updated_at           | timestamp           | YES  |     | NULL    |                |
// +----------------------+---------------------+------+-----+---------+----------------+
// 9 rows in set (0.00 sec)