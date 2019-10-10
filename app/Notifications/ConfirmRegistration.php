<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailTemplates;

class ConfirmRegistration extends Notification
{
    use Queueable;

    private $confirmation_link;

    private $lang;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($confirm_hash, $lang)
    {
        $this->confirmation_link = url(route('registration_confirm', ['lang' => \App::getLocale(), 'confirmation_hash' => $confirm_hash]));

        $this->lang = $lang;
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
        $emailTemplate = EmailTemplates::where('var', 'confirm_registration')->first();
        $message = str_replace(['{CONFIRMATION_LINK}', '{USERNAME}'], [$this->confirmation_link, $notifiable->name], $emailTemplate["message_{$this->lang}"]);
        return (new MailMessage) 
                    ->subject($emailTemplate["theme_{$this->lang}"])
                    ->from(\Constant::get('EMAIL')) 
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
