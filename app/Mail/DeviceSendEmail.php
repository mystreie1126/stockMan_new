<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeviceSendEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        $lists    = $this->sendList;
        $shopname =  $this->shopname;

        return $this->view('email.Mail_deviceToBranches',compact('lists','shopname'));
    }
}
