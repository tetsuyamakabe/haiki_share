<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ContactNotification extends Notification
{
    use Queueable; // キュートレイト
    protected $name; // 名前
    protected $email; // メールアドレス
    protected $contact; // お問い合わせ内容

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    // 名前、メールアドレス、お問い合わせ内容の値をクラスのプロパティに設定
    public function __construct($name, $email, $contact)
    {
        $this->name = $name; // 名前
        $this->email = $email; // メールアドレス
        $this->contact = $contact; // お問い合わせ内容
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
    // お問い合わせ受付完了メールのカスタム
    public function toMail($notifiable): MailMessage
    {
        // MailMessageインスタンスを作成、件名を設定、メールテンプレートのビューに名前とメールアドレスとお問い合わせ内容を渡す
        return (new MailMessage)
            ->subject('【' . config('app.name') . '】お問い合わせを受け付けました')
            ->view('contact', ['name' => $this->name, 'email' => $this->email, 'contact' => $this->contact]);
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
