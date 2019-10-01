<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class parts_sendEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $shopname;
    public $sendquery;

    public function __construct($query,$shopname)
    {
        $this->shopname = $shopname;
        $this->send_query = $query;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $part_lists = $this->send_query;
        $shopname   = $this->shopname;

        $subject = 'Parts Delivery To '.$shopname.' Branch.';
        return $this->from('stocktake@funtech.ie')
                    ->subject($subject)
                    ->view('email.Mail_partsToBranches',compact('part_lists','shopname'));
    }
}
