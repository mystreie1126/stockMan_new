<?php

namespace App\Repository;

use DB;
use App\Helper\Common;
use App\Model\Inventory\hqInventory_history as InvHistory;
use App\record\RepHistory;
use Carbon\Carbon;

class Replishment{

    private function branch_replishmentWithDate_results($shop_id,$from,$to){

        $updated_stock_refs = Common::updated_record_refs($shop_id);
            $pos_sale_refs  = Common::totalSalesRefs($from,$to,$shop_id);
            $web_sales_refs = Common::webSalesRefs($from,$to,$shop_id);
              $missing_refs = Common::missingPart($pos_sale_refs,$web_sales_refs);

       $sales_refs = array_merge($pos_sale_refs,$missing_refs);
       $missing_updated_stock_refs = Common::missingPart($sales_refs,$updated_stock_refs);
       $all_refs = array_merge($sales_refs,$missing_updated_stock_refs);
       $list = [];

       foreach($sales_refs as $ref){
           if(
               Common::get_webStockID_by_ref($ref) !== null &&
               Common::get_branchStockID_by_ref($ref,$shop_id) !== null &&
               Common::get_productName_by_ref($ref) !== null &&
               Common::get_productStandard_by_ref($ref) !== null

           ){
               $list[] = [

                   'web_stockID' =>  Common::get_webStockID_by_ref($ref),
                   'pos_stockID' =>  Common::get_branchStockID_by_ref($ref,$shop_id),
                          'name' =>  Common::get_productName_by_ref($ref),
                     'reference' =>  $ref,
                      'standard' =>  Common::get_productStandard_by_ref($ref),
                       'soldQty' =>  Common::get_productSoldQty_by_ref($ref,$shop_id,$from,$to),
               'has_branch_stock'=>  Common::ifhasBranchStock(Common::get_branchStockID_by_ref($ref,$shop_id)) ? "Yes":"No",
               'branch_stock_qty'=> "Not Sure",
              'suggest_send'     =>  Common::get_productSoldQty_by_ref($ref,$shop_id,$from,$to),
                  'shop_name'    =>  Common::get_branch_name_by_shopID($shop_id),
                  'shop_id'      => $shop_id,
                  'selected_from'=> $from,
                  'selected_to'  => $to
                  // 'suggest_send' =>  Common::ifhasBranchStock(Common::get_branchStockID_by_ref($ref,$shop_id)) ?
                  // (Common::get_productStandard_by_ref($ref) - Common::get_branchStockQty_by_ref($ref,$shop_id)):"TBD",
                  // 'branch_stock_qty' =>  Common::ifhasBranchStock(Common::get_branchStockID_by_ref($ref,$shop_id)) ?  (Common::get_branchStockQty_by_ref($ref,$shop_id)):"Not Sure"


                       ];
           }
       }//end of loop

       return response()->json(['list'=>$list,'howMany'=>count($sales_refs)]);

    }



    public function branch_replishmentWithDate($shop_id,$from,$to){

        $cacheKey = strtoupper($shop_id.$from.$to);

        //return $cacheKey;
        return cache()->remember($cacheKey,Carbon::now()->addDays(2),function() use($shop_id,$from,$to){
            return self::branch_replishmentWithDate_results($shop_id,$from,$to);
        });
    }
}
