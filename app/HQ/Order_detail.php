<?php

namespace App\HQ;

use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    protected $table = 'ps_order_detail';
    protected $primaryKey = 'id_order_detail';
    protected $forgeinKey = 'id_order';
}
