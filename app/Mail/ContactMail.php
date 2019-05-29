<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;
    public $email, $name, $phone, $m;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $name, $phone, $message)
    {
        $this->email = $email;
        $this->name = $name;
        $this->phone = $phone;
        $this->m = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->email)->view('email.contact_email', compact('email', 'name', 'phone', 'm'));
    }
}
