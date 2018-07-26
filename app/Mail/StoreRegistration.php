<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreRegistration extends Mailable
{
    use Queueable, SerializesModels;
    public $store;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($store)
    {
        //Instanciando en el constructor
        $this->store = $store;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Registro de nueva tienda";
        return $this->view('mail.admin-store-registration')
            ->subject($subject);
    }
}
