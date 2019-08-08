<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use PDF;

class orderToPosEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $shopname;
    public $orderItems;
    public $total_retail;
    public $total_wholesale;
    public $total_cost;
    public $order_ref;
    public $pdf;
    public function __construct($order_details,$total_wholesale,$total_retail,$order_ref,$shopname)
    {
        $this->orderItems      = $order_details;
        $this->total_wholesale = $total_wholesale;
        $this->total_retail    = $total_retail;
        $this->order_ref       = $order_ref;
        $this->shopname        = $shopname;
        $this->pdf = PDF::loadView('email.Mail_order_to_rockpos',compact('order_details','total_wholesale','total_retail','order_ref','shopname'));
;


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order_details   = $this->orderItems;
        $total_wholesale = $this->total_wholesale;
        $total_retail    = $this->total_retail;
        $order_ref       = $this->order_ref;
        $shopname        = $this->shopname;

        $subject = 'Order: '.$order_ref.' to '.$shopname.' Branch';
        return $this->subject($subject)
                    ->view('email.Mail_order_to_rockpos',compact('order_details','total_wholesale','total_retail','order_ref','shopname'))
                    ->attachData($this->pdf->output(),$order_ref.'_to_'.$shopname,[
                        'mime' => 'application/pdf'
                    ]);
    }
}
