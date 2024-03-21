<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Convenience extends Model
{
    protected $table = 'convenience_stores';

    protected $fillable = [
        'user_id', 'branch_name', 'address_id', 'is_deleted'
    ];

    // ユーザーテーブルとの関連付け
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 住所テーブルとの関連付け
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}


// mysql> desc convenience_stores;
// +-------------+---------------------+------+-----+---------+----------------+
// | Field       | Type                | Null | Key | Default | Extra          |
// +-------------+---------------------+------+-----+---------+----------------+
// | id          | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
// | user_id     | bigint(20) unsigned | NO   | MUL | NULL    |                |
// | branch_name | varchar(255)        | NO   |     | NULL    |                |
// | address_id  | bigint(20) unsigned | NO   | MUL | NULL    |                |
// | is_deleted  | tinyint(1)          | NO   |     | 0       |                |
// | created_at  | timestamp           | YES  |     | NULL    |                |
// | updated_at  | timestamp           | YES  |     | NULL    |                |
// +-------------+---------------------+------+-----+---------+----------------+
// 7 rows in set (0.01 sec)