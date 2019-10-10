<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailTemplates;

class ReturnBidCost extends Notification
{
    use Queueable;

    private $auction;  

    private $order;

    private $amount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order, $auction, $amount)
    {
        $this->auction = $auction; 
        $this->order   = $order; 
        $this->amount  = $amount; 
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
        $emailTemplate = EmailTemplates::where('var', 'return_bids')->first(); 
        $message = str_replace(['{USERNAME}', '{AUCTION_CODE}', '{AMOUNT}'], 
                               [$notifiable->name, $this->auction->code, $this->amount], 
                               $emailTemplate["message_{$notifiable->lang}"]);

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
