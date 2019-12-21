<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public $mailVars;

    public function __construct($mailVars)
    {
        $this->mailVars = $mailVars;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $token = $this->mailVars->token;
        $username = $this->mailVars->username;
        return $this->from($this->mailVars->sender)->view('mails.forgot-password', compact('username', 'token'));
    }
}
