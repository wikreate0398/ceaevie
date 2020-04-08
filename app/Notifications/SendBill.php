<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailTemplates;

class SendBill extends Notification
{
    use Queueable;

    private $order_number;

    private $price;

    private $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order_number, $price, $link)
    {
        $this->order_number = $order_number;
        $this->price        = $price;
        $this->link         = $link;
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
        return (new MailMessage) 
                    ->subject('Вам выставлен счет для оплаты заказа')
                    ->from(setting('mailbox'))
                    ->markdown('mails.bill', [
                        'waiter'       => $notifiable->name,
                        'order_number' => $this->order_number,
                        'price'        => $this->price,
                        'link'         => $this->link
                    ]);
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
