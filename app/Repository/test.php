<?php

namespace App\Repository;
use DB;
use App\Helper\Common;
use App\Model\Inventory\hqInventory_history as InvHistory;

class test{
  public function a(){
    $inventory = Common::hq_inventory_list();
    return $inventory;
  }
}
