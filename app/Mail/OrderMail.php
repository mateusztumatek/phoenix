<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $content, $email, $product;
    public function __construct($content, $email, $product)
    {
        $this->content = $content;
        $this->email = $email;
        $this->product = $product;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $content = $this->content;
        $product = $this->product;
        $email = $this->email;
        $this->replyTo($this->email, 'odbiorca');
        return $this->from($this->email)->view('email.order_email', compact('content', 'product', 'email'));
    }
}
