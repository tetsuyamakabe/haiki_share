<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Convenience;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\User\PasswordResetNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes; // 通知・論理削除トレイト

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'introduction', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // PasswordResetNotificationクラスを利用するためにオーバーライド
    public function sendPasswordResetNotification($token)
    {
        if ($this->role === 'user') { // 利用者ユーザーの場合
            $this->notify(new \App\Notifications\User\PasswordResetNotification($token));
        } elseif ($this->role === 'convenience') { // コンビニユーザーの場合
            $this->notify(new \App\Notifications\Convenience\PasswordResetNotification($token));
        }
    }

    // コンビニモデルとの関連付け（一対一の関係）
    public function convenience()
    {
        return $this->hasOne(Convenience::class);
    }

    // 商品モデルとの関連付け（一対多の関係）
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // お気に入りモデルとの関連付け（一対多の関係）
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 購入した商品との関連付け（一対多の関係）
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'purchased_id');
    }
}

// usersテーブルのテーブル構成
// mysql> desc users;
// +-------------------+---------------------+------+-----+-------------------------------------------+----------------+
// | Field             | Type                | Null | Key | Default                                   | Extra          |
// +-------------------+---------------------+------+-----+-------------------------------------------+----------------+
// | id                | bigint(20) unsigned | NO   | PRI | NULL                                      | auto_increment |
// | name              | varchar(255)        | NO   |     | NULL                                      |                |
// | email             | varchar(255)        | NO   | UNI | NULL                                      |                |
// | email_verified_at | timestamp           | YES  |     | NULL                                      |                |
// | password          | varchar(255)        | NO   |     | NULL                                      |                |
// | avatar            | varchar(255)        | YES  |     | https://haikishare.com/avatar/default.png |                |
// | introduction      | text                | YES  |     | NULL                                      |                |
// | role              | varchar(255)        | NO   |     | NULL                                      |                |
// | deleted_at        | timestamp           | YES  |     | NULL                                      |                |
// | remember_token    | varchar(100)        | YES  |     | NULL                                      |                |
// | created_at        | timestamp           | YES  |     | NULL                                      |                |
// | updated_at        | timestamp           | YES  |     | NULL                                      |                |
// +-------------------+---------------------+------+-----+-------------------------------------------+----------------+
// 12 rows in set (0.00 sec)