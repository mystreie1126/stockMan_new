<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class warehouseStandardCheck extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $lists;
    public $subject;

    public function __construct($query,$subject)
    {
        $this->lists = $query;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lists = $this->lists;
        $subject = $this->subject;
        return $this->from('warehouse@funtech.ie')
                ->subject($subject)
                ->view('email.Mail_warehouseStandard_check',compact('lists'));
    }
}
