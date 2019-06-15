<?php

namespace App\Model\Record;

use Illuminate\Database\Eloquent\Model;

class Stock_in_history extends Model
{
    protected $connection = 'mysql3';
    protected $table = 'sm_stock_in_history';
}
