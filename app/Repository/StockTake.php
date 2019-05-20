<?php

namespace App\Repository;
use DB;
use App\Helper\Common;
use App\Model\Inventory\hqInventory_history as InvHistory;
use Carbon\Carbon;

class StockTake{

    private function HQ_stockTake_Results(){
        $arr = [];
        $inventory = Common::hq_inventory_list();
        foreach($inventory as $inve){

          if(Common::get_productName_by_ref($inve->ref)){
            $arr[] = [
                  'web_stock_id' => $inve->stock_id,
                  'reference'    => $inve->ref,
                  'name'         => Common::get_productName_by_ref($inve->ref)
                    ];
                }
            }
        return $arr;
    }

    public function HQ_stockTake(){

        $cacheKey = strtoupper('HQ_stockTake');
        //return DB::table('ps_orders')->get();
        return cache()->remember($cacheKey,Carbon::now()->addDays(1),function(){
            return self::HQ_stockTake_Results();
        });
    }



}
