<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\User\PasswordResetNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
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
        if ($this->role === 'user') {
            $this->notify(new \App\Notifications\User\PasswordResetNotification($token));
        } elseif ($this->role === 'convenience') {
            $this->notify(new \App\Notifications\Convenience\PasswordResetNotification($token));
        }
    }
}

// mysql> desc users;
// +-------------------+---------------------+------+-----+---------+----------------+
// | Field             | Type                | Null | Key | Default | Extra          |
// +-------------------+---------------------+------+-----+---------+----------------+
// | id                | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
// | name              | varchar(255)        | NO   |     | NULL    |                |
// | email             | varchar(255)        | NO   | UNI | NULL    |                |
// | email_verified_at | timestamp           | YES  |     | NULL    |                |
// | password          | varchar(255)        | NO   |     | NULL    |                |
// | icon              | varchar(255)        | YES  |     | NULL    |                |
// | introduction      | text                | YES  |     | NULL    |                |
// | role              | varchar(255)        | NO   |     | NULL    |                |
// | is_deleted        | tinyint(1)          | NO   |     | 0       |                |
// | remember_token    | varchar(100)        | YES  |     | NULL    |                |
// | created_at        | timestamp           | YES  |     | NULL    |                |
// | updated_at        | timestamp           | YES  |     | NULL    |                |
// +-------------------+---------------------+------+-----+---------+----------------+
// 12 rows in set (0.04 sec)