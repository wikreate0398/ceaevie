<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ChangedOrderStatus extends Notification
{
    use Queueable;

    private $order; 

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order; 

        if(!$this->order->status["message_{$this->order->lang}"])
        {
            return false;
        } 
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
        $statusMessage = $this->replaceVars($this->order->status["message_{$this->order->lang}"], 
                                      ['{NAME}', '{ORDER_ID}'], 
                                      [$notifiable->name, $this->order->rand]);
  
        return (new MailMessage)
                    ->subject('☆ ' . config('app.name') . ': New Message')
                    ->from(\Constant::get('EMAIL')) 
                    ->line(new \Illuminate\Support\HtmlString(nl2br($statusMessage)));
    }

    private function replaceVars($subject, $search, $replace){
        return str_replace($search, $replace, $subject);
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
