<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class parts extends Model
{
    protected $connection = 'mysql3';
    protected $table = 'sm_parts_standard';
}
