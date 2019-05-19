<?php

namespace App\Partner;

use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ps_order_detail';
}
