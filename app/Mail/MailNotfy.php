<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNotfy extends Mailable
{
    use Queueable, SerializesModels;

    public $fullname;
    public $login_time;
    public $clientIP;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fullname, $login_time, $clientIP)
    {
        $this->fullname = $fullname;
        $this->login_time = $login_time;
        $this->clientIP = $clientIP;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("Menara@p.com", "Menara Prefa")
            ->view('emails.index');
    }

}
