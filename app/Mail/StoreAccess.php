<?php

namespace App\Mail\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreAccess extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $pin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $pin)
    {
        $this->user = $user;
        $this->pin = $pin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "¡Bienvenido a Regalalo!";
        return $this->view('mail.store-access')
            ->subject($subject);
    }
}
