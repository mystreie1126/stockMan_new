<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;

class Online_pos_partner extends Model
{
    protected $connection = 'mysql3';
    protected $table = 'sm_pos_partners';
    protected $primaryKey = 'ps_customer_id';
}
