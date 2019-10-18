<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailTemplates;

class NewTips extends Notification
{
    use Queueable;
 
    private $lang;

    private $amount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($amount, $lang = 'ru')
    { 
        $this->lang   = $lang;
        $this->amount = $amount;
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
        $emailTemplate = EmailTemplates::where('var', 'new_tip')->first();
        $message = str_replace(['{USERNAME}', '{AMOUNT}'], [$notifiable->name, $this->amount], $emailTemplate["message_{$this->lang}"]);
        return (new MailMessage) 
                    ->subject($emailTemplate["theme_{$this->lang}"])
                    ->from(setting('mailbox')) 
                    ->line(new \Illuminate\Support\HtmlString($message)) ;
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
