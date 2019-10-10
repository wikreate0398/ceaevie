<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailTemplates;

class NewItemInCart extends Notification
{
    use Queueable;

    private $auction;  

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($auction)
    {
        $this->auction = $auction; 
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
        $emailTemplate = EmailTemplates::where('var', 'item_in_cart')->first(); 
        $message = str_replace(['{USERNAME}'], [$notifiable->name], $emailTemplate["message_{$notifiable->lang}"]);
 
        return (new MailMessage) 
            ->subject($emailTemplate["theme_{$notifiable->lang}"])
            ->from(\Constant::get('EMAIL')) 
            ->line(new \Illuminate\Support\HtmlString($message));
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
