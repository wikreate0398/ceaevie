<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailTemplates;

class SendLetter extends Notification
{
    use Queueable;

    private $theme;

    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($theme, $message)
    {
        $this->theme   = $theme; 
        $this->message = $message;
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
                    ->subject($this->theme)
                    ->from(\Constant::get('EMAIL')) 
                    ->line(new \Illuminate\Support\HtmlString(nl2br($this->message))) ;
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
