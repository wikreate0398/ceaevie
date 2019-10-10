<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeQuestion extends Mailable
{
    use Queueable, SerializesModels;

    private $name;

    private $phone;

    private $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $phone, $message)
    {
        $this->name    = $name;
        $this->phone   = $phone;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(setting('mailbox'))
                    ->markdown('mails.question')
                    ->with([
                        'name'    => $this->name,
                        'phone'   => $this->phone,
                        'message' => $this->message
                    ]);
    }
}
