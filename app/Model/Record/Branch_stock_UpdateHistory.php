<?php

namespace App\Model\Record;

use Illuminate\Database\Eloquent\Model;

class Branch_stock_UpdateHistory extends Model
{
  public $timestamps = false;
  protected $connection = 'mysql3';
  protected $table ='sm_updateStockRecord';
//ref,stock_id,update_qty,add_qty,
}
