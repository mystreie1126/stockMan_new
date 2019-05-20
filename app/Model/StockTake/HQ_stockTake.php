<?php

namespace App\Model\StockTake;

use Illuminate\Database\Eloquent\Model;

class HQ_stockTake extends Model
{
    public $timestamps = false;

    protected $connection = 'mysql3';
    protected $table = 'sm_HQstockTake_history';
}
