<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailTemplates;

class OficiantCreated extends Notification
{
    use Queueable;
 
    private $lang; 

    private $location;

    private $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($location, $link, $lang = 'ru')
    { 
        $this->lang     = $lang; 
        $this->location = $location;
        $this->link     = $link;
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
        $emailTemplate = EmailTemplates::where('var', 'oficiant_created')->first();
        $message = str_replace(['{USERNAME}', '{LOCATION_NAME}', '{LINK}'], 
                               [$notifiable->name, $this->location->institution_name, $this->link], 
                               $emailTemplate["message_{$this->lang}"]);
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
