<?php

namespace App\Model\Record;

use Illuminate\Database\Eloquent\Model;

class HQ_replishment_history extends Model
{
  protected $connection = 'mysql3';
  protected $table = 'sm_all_replishment_history';
  public $timetamps = false;
}
