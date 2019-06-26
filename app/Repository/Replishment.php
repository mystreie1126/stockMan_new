<?php

namespace App\Repository;

use DB;
use App\Helper\Common;
use App\Model\Inventory\hqInventory_history as InvHistory;
use App\record\RepHistory;
use Carbon\Carbon;
use App\Model\Standard\Standard_Branch;
use App\Model\Partner\Shop;

class Replishment{

    private function branch_replishmentWithDate_results($shop_id,$from,$to){

            $pos_sale_refs  = Common::totalSalesRefs($from,$to,$shop_id);
            $web_sales_refs = Common::webSalesRefs($from,$to,$shop_id);
              $missing_refs = Common::missingPart($pos_sale_refs,$web_sales_refs);

       $sell_refs = array_merge($pos_sale_refs,$missing_refs);
       $list = [];

       $final_refs = $sell_refs;

       foreach($final_refs as $ref){
           if(
               Common::get_webStockID_by_ref($ref) !== null &&
               Common::get_branchStockID_by_ref($ref,$shop_id) !== null &&
               Common::get_productName_by_ref($ref) !== null

           ){
               $list[] = [

                   'web_stockID' =>  Common::get_webStockID_by_ref($ref),
                   'pos_stockID' =>  Common::get_branchStockID_by_ref($ref,$shop_id),
                          'name' =>  Common::get_productName_by_ref($ref),
                     'reference' =>  $ref,
                       'soldQty' =>  Common::get_productSoldQty_by_ref($ref,$shop_id,$from,$to),
              'suggest_send'     =>  Common::get_productSoldQty_by_ref($ref,$shop_id,$from,$to),
                  'shop_name'    =>  Common::get_branch_name_by_shopID($shop_id),
                  'shop_id'      => $shop_id,
                  'selected_from'=> $from,
                  'selected_to'  => $to,
                  'retail_price' => Common::get_retail_price_by_ref($ref)
                  // 'suggest_send' =>  Common::ifhasBranchStock(Common::get_branchStockID_by_ref($ref,$shop_id)) ?
                  // (Common::get_productStandard_by_ref($ref) - Common::get_branchStockQty_by_ref($ref,$shop_id)):"TBD",
                  // 'branch_stock_qty' =>  Common::ifhasBranchStock(Common::get_branchStockID_by_ref($ref,$shop_id)) ?  (Common::get_branchStockQty_by_ref($ref,$shop_id)):"Not Sure"


                       ];
           }
       }//end of loop

       return response()->json(['list'=>$list,'howMany'=>count($final_refs)]);

    }


    private function branch_replishmentbystandard($shop_id){
        $query = Standard_Branch::where('shop_id',$shop_id)->whereNull('deleted_at')->get();
        $standard_list = [];

        foreach($query as $q){
          $q->send_qty = intval($q->standard_quantity) - intval($q->stock_qty());
          if(
               $q->send_qty > 0 &&
               Common::get_webStockID_by_ref($q->reference) !== null &&
               Common::get_branchStockID_by_ref($q->reference,$shop_id) !== null &&
               Common::get_productName_by_ref($q->reference) !== null
             ){
              $standard_list[]=[
                  'name'         => Common::get_productName_by_ref($q->reference),
                  'reference'    => $q->reference,
                  'send'         => $q->send_qty,
                  'standard'     => $q->standard_quantity,
                  'shop_name'    => Shop::find(intval($shop_id))->name,
                  'webStockID'   => Common::get_webStockID_by_ref($q->reference),
                  'branchStockID'=> Common::get_branchStockID_by_ref($q->reference,$shop_id),
                  'shop_id'      => $shop_id,
                  'retail_price' => Common::get_retail_price_by_ref($q->reference)
              ];
          }
        }

        return  $standard_list;
    }


    public function branch_replishmentWithDate($shop_id,$from,$to){

        $cacheKey = strtoupper($shop_id.$from.$to);
        //return $cacheKey;
        return cache()->remember($cacheKey,Carbon::now()->addDays(2),function() use($shop_id,$from,$to){
            return self::branch_replishmentWithDate_results($shop_id,$from,$to);
        });
    }

    public function branch_replishmentWithStandard($shop_id){
        $cacheKey = strtoupper($shop_id.'by_standard');
        return cache()->remember($cacheKey,Carbon::now()->addDays(2),function() use($shop_id){
            return self::branch_replishmentbystandard($shop_id);
        });
    }
}
