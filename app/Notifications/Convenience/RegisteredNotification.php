<?php

namespace App\Notifications\Convenience;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RegisteredNotification extends Notification
{
    use Queueable; // キュートレイト
    protected $user; // ユーザー情報

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    // ユーザー情報をクラスのプロパティに設定
    public function __construct($user)
    {
        $this->user = $user; // ユーザー情報
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
    // ユーザー登録完了メールのカスタム
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('【' . config('app.name') . '】ユーザー登録完了メール')
            ->view('auth.convenience.registered', ['user' => $this->user]);
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
