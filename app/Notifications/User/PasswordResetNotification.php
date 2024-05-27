<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetNotification extends Notification
{
    use Queueable; // キュートレイト
    public $token; // トークン

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token; // トークンを保持
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // パスワードリセットメールのカスタム
    public function toMail($notifiable): MailMessage
    {
        // パスワードリセットURLを生成
        $url = route('user.password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()]);
        // パスワードリセットリンクの有効期限を取得
        $count = config('auth.passwords.users.expire');
        // MailMessageのインスタンスを作成、件名を設定、メールテンプレートのビューにパスワードリセットURLとリンクの有効期限を渡す
        return (new MailMessage)
            ->subject('【' . config('app.name') . '】パスワード再設定')
            ->view('auth.user.passwordreset', ['reset_url' => $url, 'count' => $count]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}