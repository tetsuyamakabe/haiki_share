<?php

namespace App\Notifications\Convenience;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetNotification extends Notification
{
    use Queueable;
    public $token;
    protected $title = 'パスワード変更メール';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
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
    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('【' . config('app.name') . '】パスワード再設定');
            $url = route('convenience.password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()]);
            $count = config('auth.passwords.users.expire');
            $mail->view('auth.convenience.passwordreset', ['reset_url' => $url, 'count' => $count]);
        return $mail;
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