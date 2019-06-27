<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class replishmentEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $sendList;
    public $shopname;

    public function __construct($query,$shopname,$total_retail,$total_wholesale)
    {
         $this->sendList = $query;
         $this->shopname = $shopname;
         $this->total_retail = $total_retail;
         $this->total_wholesale = $total_wholesale;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lists = $this->sendList;
        $shopname =  $this->shopname;
        $date = date('Y-m-d');
        $total_retail = $this->total_retail;
        $total_wholesale = $this->total_wholesale;

        return $this->view('email.Mail_replishment',compact('lists','shopname','date','total_retail','total_wholesale'));
    }
}
