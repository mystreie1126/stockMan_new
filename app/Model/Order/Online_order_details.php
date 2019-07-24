<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;

class Online_order_details extends Model
{
    protected $table = 'ps_order_detail';
    protected $primaryKey = 'id_order';
}
