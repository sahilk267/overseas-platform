<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $name;

    public function __construct($token, $name)
    {
        $this->token = $token;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('Reset Your UMAEP Password')
            ->markdown('emails.forgot-password');
    }
}
