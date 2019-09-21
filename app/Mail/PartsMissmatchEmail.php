<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class PartsMissmatchEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($query,$shop_id)
    {
        $this->missmatch_parts = $query;
        $this->shop_id         = $shop_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $missmatch_parts= $this->missmatch_parts;
        $shopname = DB::table('c1ft_pos_prestashop.ps_shop')->where('id_shop',$this->shop_id)->value('name');

        $subject = 'MissMatch Parts from '.$shopname.' Parts StockTake.';

        return $this->from('manager@funtech.ie')
                    ->subject($subject)
                    ->view('email.Mail_missmatchEmail',compact('missmatch_parts','shopname'));

    }
}
