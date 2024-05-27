<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PurchaseNotification extends Notification 
{
    use Queueable; // キュートレイト
    protected $product; // 商品情報
    protected $convenience; // コンビニ情報
    protected $formattedExpirationDate; // （フォーマット済みの）賞味期限日付

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    // 商品情報、コンビニ情報、（フォーマット済みの）賞味期限日付の値をクラスのプロパティに設定
    public function __construct($product, $convenience, $formattedExpirationDate)
    {
        $this->product = $product; // 商品情報
        $this->convenience = $convenience; // コンビニ情報
        $this->formattedExpirationDate = $formattedExpirationDate; // （フォーマット済みの）賞味期限日付
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
    // 商品購入完了メールのカスタム
    public function toMail($notifiable)
    {
        // MailMessageのインスタンスを作成、件名を設定
        $mail = (new MailMessage)->subject('【' . config('app.name') . '】商品購入完了メール');
        // 利用者ユーザーとコンビニユーザーでそれぞれのメールテンプレートのビューに商品情報とコンビニ情報と（フォーマット済みの）賞味期限日付を渡す
        if ($notifiable->role === 'user') {
            $mail->view('products.user.purchase', ['product' => $this->product, 'convenience' => $this->convenience, 'formattedExpirationDate' => $this->formattedExpirationDate]);
        } elseif ($notifiable->role === 'convenience') {
            $mail->view('products.convenience.purchase', ['product' => $this->product, 'convenience' => $this->convenience, 'formattedExpirationDate' => $this->formattedExpirationDate]);
        }
        // $mailを返す
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