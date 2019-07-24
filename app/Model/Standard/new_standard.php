<?php

namespace App\Model\Standard;

use App\Helper\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class new_standard extends Model
{
    use SoftDeletes;
    protected $connection ='mysql3';
    protected $table = 'sm_all_branch_standard';
}
