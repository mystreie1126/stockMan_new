<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Reclrd\Branch_stock_UpdateHistory;
use App\Model\Reclrd\HQ_replishment_history;
use App\Model\Track\Parts_stock;
use App\Helper\Common;
use DB;

class helperController extends Controller
{

    //1. get all reference needs to be updated
    //2. get stock id, reference, pos id_product
    //3. update the stock qty with sending sheet
    //4. caclulate the sold qty from sending date to Now
    //5. reduce by the sold qty from stock qty
    //6. insert into sm_updateStockRecord table

    const update_shop_id = 39;

    private function getRefsDetails(){
      $query = DB::connection('mysql2')
             ->table('helper_glass_stock as a')
             ->select('b.id_product','c.id_stock_available','a.qty','a.ref'
                      )
             ->join('ps_product as b','a.ref','b.reference')
             ->join('ps_stock_available as c','b.id_product','c.id_product')

             ->where('c.id_shop',self::update_shop_id)
             ->where('a.shop_id',self::update_shop_id)
             ->get()->toArray();
             //->pluck('id_product')->toArray();

      $query1 = DB::connection('mysql2')
             ->table('helper_glass_stock as a')
             ->select('b.id_product')
             ->join('ps_product as b','a.ref','b.reference')
             ->pluck('id_product')->toArray();

      //return self::missingPart($query,$query1);

      return $query;
    }


    public function test(){

       $details = self::getRefsDetails();
       // return count($details);
       // return $details;
      for($i = 0; $i < count($details); $i++){
        //update rockpos stock
        DB::connection('mysql2')->table('ps_stock_available')
        ->where('id_stock_available',$details[$i]->id_stock_available)
        ->update(['quantity'=>$details[$i]->qty]);

        //insert into updaterecord table

        $history = new UpdateHistory;
        $history->reference = $details[$i]->ref;
        $history->stock_id = $details[$i]->id_stock_available;
        $history->updated_qty = $details[$i]->qty;
        $history->edited_qty = 0;
        $history->shop_id = self::update_shop_id;
        $history->id_product = $details[$i]->id_product;
        $history->created_at = date('Y-m-d h:i:s');
        $history->save();

      }

      return 2;
    }

    private function track_staff(){
      $query = DB::connection('mysql4')->table('stock')->where('user_id',224)->get()->toArray();
      return $query;
    }



    public function update_part_stock(){
      $stefan_parts =  self::track_staff();

      foreach($stefan_parts as $stefan){
        $stock = new Parts_stock;
        $stock->parts_id = $stefan->parts_id;
        $stock->quantity = $stefan->quantity;
        $stock->pending  = $stefan->pending;
        $stock->return   = $stefan->return;
       $stock->intransit = $stefan->intransit;
        $stock->user_id  = 230;
        $stock->standard = $stefan->standard;
        $stock->created_at = date('Y-m-d h:i:s');
        $stock->updated_at = date('Y-m-d h:i:s');

        $stock->save();
      }

      return 'updated';

    }


   public function test_ref(){
      $ref = 102790;
      $shop_id = 26;


      $arr = [];
      $a = [];

      $a = Common::hq_inventory_list();

      return $a;


      if(!Common::get_branchStockID_by_ref($ref,26)){
        return 'this dosent have pos stock id ' . $ref;
      }
      else if(!Common::get_productName_by_ref($ref)){
        return 'this dosent have proper name ' . $ref;
      }
      else if(!Common::get_webStockID_by_ref($ref)){
        return 'this dosent have webStock id ' . $ref;
      }

      else{
        return 'all good';
      }


   }


   public function test_ref_ifMatch(){
       $sold_refs   = Common::totalSalesRefs_allshops();
       $web_refs    = Common::webSalesRefs_allshops();
       $record_refs = Common::updated_record_refs_allShops();

       $a = Common::missingPart($sold_refs, $web_refs );

       $b = array_merge($sold_refs,$a);// $sold_refs + $web_refs
       $c = Common::missingPart($b,$record_refs);

       $all = array_merge($b,$c);

       $total_refs = DB::table('c1ft_pos_prestashop.ps_product')->pluck('reference')->toArray();

       //return $total_refs;

       $validate = [];
       $invalidate = [];

       foreach($total_refs as $r){
         if(
            Common::get_branchStockID_by_ref($r,26) !== null &&
            Common::get_productName_by_ref($r) !== null &&
            Common::get_webStockID_by_ref($r) !== null &&
            Common::get_productStandard_by_ref($r)!== null
        ){
            array_push($validate,$r);
        }else{
            array_push($invalidate,$r);
        }
        }

        $invalid_pos_stockId_ref =[];
        $invalid_name_ref = [];
        $invalid_web_stockId_ref = [];

        $all_god = [];



        if(count($invalidate) > 0){
            foreach($invalidate as $ir){
              if(!Common::get_branchStockID_by_ref($ir,26)){
                array_push($invalid_pos_stockId_ref,$ir);
              }
              else if(!Common::get_productName_by_ref($ir)){
                array_push($invalid_name_ref,$ir);
              }
              else if(!Common::get_webStockID_by_ref($ir)){
                array_push($invalid_web_stockId_ref,$ir);
              }

              else{
                array_push($all_god,$ir);
              }

            }
        }

        return response()->json([
            'invalidPos' => $invalid_pos_stockId_ref,

            'invalidName' => $invalid_name_ref,
            'invalidWeb' => $invalid_web_stockId_ref

        ]);



   }

   public function getStockTakeTableName(){
       $query = DB::table('c1ft_stock_manager.sm_HQstockTake_history')->get();

       foreach($query as $q){
           DB::table('c1ft_stock_manager.sm_HQstockTake_history')->where('reference',$q->reference)
           ->update(['name'=>Common::get_productName_by_ref($q->reference)]);
       }

       return 'updated';
   }


   public function helpupdatestockthursd(){
       $query = DB::table('c1ft_stock_manager.sm_replishment_history')
       ->where('uploaded',null)
       ->get();

       foreach($query as $q){
           DB::table('c1ft_store_prestashop.ps_stock_available')->where('id_stock_available',$q->web_stock_id)
           ->decrement('quantity',intval($q->updated_quantity));

           DB::table('c1ft_pos_prestashop.ps_stock_available')->where('id_stock_available',$q->shop_stock_id)
           ->increment('quantity',intval($q->updated_quantity));
       }
       return 'udated';
   }


   public function helpDouglas(){
       $query = DB::table('c1ft_pos_prestashop.helper_glass_stock')->get();
       $shop_id = 36;
       $from = "2019-05-14";
       $to = date('Y-m-d h:i:s');

         //return        Common::get_productSoldQty_by_ref(102512,$shop_id,$from,$to);
       foreach($query as $q){
           DB::table('c1ft_pos_prestashop.helper_glass_stock')
           ->decrement('qty',Common::get_productSoldQty_by_ref($q->ref,$shop_id,$from,$to));
       }

       return 'up';

   }

}
