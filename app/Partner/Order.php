<?php

namespace App\Partner;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ps_orders';
}
