<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tt extends Model
{
    protected $connection = 'mysql3';
    protected $table = 'athlone_pos_deduct';
    public $timestamps = false;
}
