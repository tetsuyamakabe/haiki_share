<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPicture extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id', 'file'];

    // 商品との関連付け（多対一の関係）
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

// mysql> desc product_pictures;
// +------------+---------------------+------+-----+---------+----------------+
// | Field      | Type                | Null | Key | Default | Extra          |
// +------------+---------------------+------+-----+---------+----------------+
// | id         | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
// | product_id | bigint(20) unsigned | NO   | MUL | NULL    |                |
// | file       | varchar(255)        | NO   |     | NULL    |                |
// | deleted_at | timestamp           | YES  |     | NULL    |                |
// | created_at | timestamp           | YES  |     | NULL    |                |
// | updated_at | timestamp           | YES  |     | NULL    |                |
// +------------+---------------------+------+-----+---------+----------------+
// 6 rows in set (0.00 sec)