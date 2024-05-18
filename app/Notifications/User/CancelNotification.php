<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CancelNotification extends Notification
{
    use Queueable;
    protected $product;
    protected $convenience;
    protected $formattedExpirationDate;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($product, $convenience, $formattedExpirationDate)
    {
        $this->product = $product;
        $this->convenience = $convenience;
        $this->formattedExpirationDate = $formattedExpirationDate;
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
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('【' . config('app.name') . '】商品キャンセル完了メール');

        if ($notifiable->role === 'user') {
            $mail->view('products.user.cancel', ['product' => $this->product, 'convenience' => $this->convenience, 'formattedExpirationDate' => $this->formattedExpirationDate]);
        } elseif ($notifiable->role === 'convenience') {
            $mail->view('products.convenience.cancel', ['product' => $this->product, 'convenience' => $this->convenience, 'formattedExpirationDate' => $this->formattedExpirationDate]);
        }

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