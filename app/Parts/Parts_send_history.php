<?php

namespace App\Model\Parts;

use Illuminate\Database\Eloquent\Model;

class Parts_send_history extends Model
{
    protected $connection = 'mysql3';
    public $timestamp = false;
    protected $table = 'sm_parts_sendHistory';
}
