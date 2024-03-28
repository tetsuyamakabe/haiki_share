<?php

namespace App\Models;

use App\Models\Convenience;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'prefecture', 'city', 'town', 'building'
    ];

    // コンビニテーブルとの関連付け
    public function convenienceStores()
    {
        return $this->hasMany(Convenience::class);
    }
}

// mysql> desc addresses;
// +------------+---------------------+------+-----+---------+----------------+
// | Field      | Type                | Null | Key | Default | Extra          |
// +------------+---------------------+------+-----+---------+----------------+
// | id         | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
// | prefecture | varchar(255)        | NO   |     | NULL    |                |
// | city       | varchar(255)        | NO   |     | NULL    |                |
// | town       | varchar(255)        | NO   |     | NULL    |                |
// | building   | varchar(255)        | YES  |     | NULL    |                |
// | created_at | timestamp           | YES  |     | NULL    |                |
// | updated_at | timestamp           | YES  |     | NULL    |                |
// +------------+---------------------+------+-----+---------+----------------+
// 7 rows in set (0.01 sec)