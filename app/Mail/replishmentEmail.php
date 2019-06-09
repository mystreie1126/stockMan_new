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

    public function __construct($query,$shopname)
    {
         $this->sendList = $query;
         $this->shopname = $shopname;
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
        $date = date('Y-m-d h:i:s');

        return $this->view('email.Mail_replishment',compact('lists','shopname','date'));
    }
}
