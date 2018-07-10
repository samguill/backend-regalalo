<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShippingOrder extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    public $user_type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $user_type){
        $this->order = $order;
        $this->user_type = $user_type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        if($this->user_type == "client"){
            $subject = $this->order->client["first_name"] . ", tu pedido " . $this->order["order_code"] . " ha sido confirmado";
        }else{
            $subject = "Tu pedido " . $this->order["order_code"] . " ha sido confirmado";
        }
        return $this->view('mail.shipping-order')
            ->subject($subject);
    }
}
