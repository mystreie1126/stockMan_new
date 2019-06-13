<?php

namespace App\Model\Record;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Branch_stock_standard extends Model
{
    use SoftDeletes;
    protected $connection = 'mysql3';
    protected $table = 'sm_standardstock_branches';

}
