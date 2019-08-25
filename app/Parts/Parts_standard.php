<?php

namespace App\Model\Parts;

use App\Helper\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Parts_standard extends Model
{
    use SoftDeletes;
    protected $connection ='mysql3';
    protected $table = 'sm_parts_standard';
}
