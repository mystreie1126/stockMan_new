<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;

class hqInventory_history extends Model
{
  public $timestamps = false;

  protected $connection = 'mysql3';
  protected $table = 'sm_hqInventoryCountHistory';
}
