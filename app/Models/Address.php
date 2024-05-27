<?php

namespace App\Models;

use App\Models\Convenience;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes; // 論理削除トレイト

    protected $fillable = ['prefecture', 'city', 'town', 'building'];

    // コンビニモデルとの関連付け（一対一の関係）
    public function convenience()
    {
        return $this->hasOne(Convenience::class);
    }

}

// addressesテーブルのテーブル構成
// mysql> desc addresses;
// +------------+---------------------+------+-----+---------+----------------+
// | Field      | Type                | Null | Key | Default | Extra          |
// +------------+---------------------+------+-----+---------+----------------+
// | id         | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
// | prefecture | varchar(255)        | NO   |     | NULL    |                |
// | city       | varchar(255)        | NO   |     | NULL    |                |
// | town       | varchar(255)        | NO   |     | NULL    |                |
// | building   | varchar(255)        | YES  |     | NULL    |                |
// | deleted_at | timestamp           | YES  |     | NULL    |                |
// | created_at | timestamp           | YES  |     | NULL    |                |
// | updated_at | timestamp           | YES  |     | NULL    |                |
// +------------+---------------------+------+-----+---------+----------------+
// 8 rows in set (0.01 sec)