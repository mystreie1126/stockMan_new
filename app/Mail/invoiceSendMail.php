<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use PDF;

class invoiceSendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $lists;
    public $date;
    public $shipping_address;
    public $billing_address;
    public $email;
    public $name;
    public $order_ref;
    public $total_tax;
    public $total_price;
    public $invoice_id;
    public $pdf;

    public function __construct($lists,$date,$shipping_address,$billing_address,$email,$name,$order_ref,$total_tax,$total_price,$invoice_id)
    {
        $this->lists             = $lists;
        $this->date              = $date;
        $this->shipping_address  = $shipping_address;
        $this->billing_address   = $billing_address;
        $this->email             = $email;
        $this->name              = $name;
        $this->order_ref         = $order_ref;
        $this->total_tax         = $total_tax;
        $this->total_price       = $total_price;
        $this->invoice_id        = $invoice_id;
        $this->pdf = PDF::loadView('email.Mail_invoice_email',compact('lists','date','shipping_address','billing_address','email','name','order_ref','total_tax','total_price','invoice_id')
    );
        //$this->pdf = PDF::loadView('email.Mail_order_to_rockpos',compact('order_details','total_wholesale','total_retail','order_ref','shopname'));

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lists              = $this->lists;
        $date               = $this->date;
        $shipping_address   = $this->shipping_address;
        $billing_address    = $this->billing_address;
        $email              = $this->email;
        $name               = $this->name;
        $order_ref          = $this->order_ref;
        $total_tax          = $this->total_tax;
        $total_price        = $this->total_price;
        $invoice_id         = $this->invoice_id;

        $subject = 'Your VAT Receipt from Funtech';

        return $this->subject($subject)
                     //->view('email.Mail_invoice_email',compact('lists','date','shipping_address','billing_address','email','name','order_ref','total_tax','total_price','invoice_id'))
                    ->view('email.Mail_invoice')
                    ->attachData($this->pdf->output(),'invoice.pdf',[
                        'mime' => 'application/pdf'
                    ]);

    }
}
