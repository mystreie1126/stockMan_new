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

       $sell_refs = array_merge($pos_sale_refs,$missing_refs);
       $missing_updated_stock_refs = Common::missingPart($sell_refs,$updated_stock_refs);
       $all_refs = array_merge($sell_refs,$missing_updated_stock_refs);
       $list = [];



       // if(intval($shop_id) == 27){
       //     $sell_refs = array_merge($pos_sale_refs,$missing_refs);
       //     $extraRefs = Common::extraRefsAfterStockTake($shop_id);
       //     $final_refs = array_merge($sell_refs, Common::missingPart($sell_refs,$extraRefs));
       // }else{
       //      $final_refs = $sell_refs;
       // }

       $final_refs = $sell_refs;

       foreach($final_refs as $ref){
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
                      'standard' =>  -1,
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

       return response()->json(['list'=>$list,'howMany'=>count($final_refs)]);

    }


    private function branch_replishmentbystandard($shop_id){
        $query = DB::table('c1ft_stock_manager.sm_branch_standard_stock as standard')
                 ->select('standard.reference','standard.standard_qty as standard','stock.quantity','name.name','stock.id_stock_available as branchStockID',
                            DB::raw('(standard.standard_qty - stock.quantity) as send')
                 )
                 ->where('standard.shop_id',$shop_id)
                 ->join('c1ft_pos_prestashop.ps_stock_available as stock','standard.branch_stock_id','stock.id_stock_available')
                 ->join('c1ft_pos_prestashop.ps_product_lang as name','name.id_product','stock.id_product')
                 ->where('name.id_shop',$shop_id)
                 ->whereRaw('standard.standard_qty - stock.quantity > 0')
                 ->get();

        foreach($query as $q){

                $q->webStockID = Common::get_webStockID_by_ref($q->reference) !== null ? Common::get_webStockID_by_ref($q->reference) : 0;
                $q->shop_name = DB::table('c1ft_pos_prestashop.ps_shop')->where('id_shop',$shop_id)->value('name');
                $q->shop_id   = $shop_id;

        }
        return $query;
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
