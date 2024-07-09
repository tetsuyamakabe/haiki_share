<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\User\EmailVerificationNotification;

class EmailReset extends Model
{
    use Notifiable; // 通知トレイト

    protected $fillable = ['user_id', 'new_email', 'token'];

    // メールアドレス認証メールを送信
    public function sendEmailVerificationNotification($token)
    {
        $this->notify(new EmailVerificationNotification($token));
    }

    // 新しいメールアドレス宛にメールを送信
    public function routeNotificationForMail($notification)
    {
        return $this->new_email;
    }
}