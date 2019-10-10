<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailTemplates;

class ResetPassword extends Notification
{
    use Queueable;

    private $new_pass;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($new_pass)
    {
        $this->new_pass = $new_pass;
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
        $emailTemplate = EmailTemplates::where('var', 'reset_password')->first(); 
        $message = str_replace(['{USERNAME}', '{NEW_PASSWORD}'], 
                               [$notifiable->name, "<strong>{$this->new_pass}</strong>"], 
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
