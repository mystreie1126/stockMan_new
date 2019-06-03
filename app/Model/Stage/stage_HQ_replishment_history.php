<?php

namespace App\Model\Stage;

use Illuminate\Database\Eloquent\Model;

class stage_HQ_replishment_history extends Model
{
    protected $connection = 'mysql3';
    protected $table = 'stage_sm_all_replishment_history';
    public $timetamps = false;
}
