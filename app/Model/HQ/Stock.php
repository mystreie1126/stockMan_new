<?php

namespace App\HQ;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'ps_stock_available';
    protected $primaryKey = 'id_stock_available';
    public $timestamps = false;

}
