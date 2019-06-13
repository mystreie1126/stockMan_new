<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Stage\stage_HQ_replishment_history as RepHistory;
//use App\Model\Record\HQ_replishment_history as RepHistory;
use App\Model\Track\Parts_stock;
use App\Model\Record\Branch_stock_standard as Branch_standard;
use App\Model\Partner\BranchStock;
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
      $ref =   102356;
      $shop_id = 27;


          if(!Common::get_branchStockID_by_ref($ref,27)){
            return 'this dosent have pos stock id ' . $ref;
          }
          else if(!Common::get_productName_by_ref($ref)){
            return 'this dosent have proper name ' . $ref;
          }
          else if(!Common::get_webStockID_by_ref($ref)){
            return 'this dosent have webStock id ' . $ref;
          }

          else{

            return Common::get_webStockID_by_ref($ref);
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





  public function getThis(){
      $email = DB::table('c1ft_stock_manager.sm_shop_email')->where('shop_id',26)->value('shop_mail');
      return $email;
  }


  public function stockTake_check(){
      $from = '2019-06-06 19:40:33';
      $to = date('Y-m-d h:i:s');

      $ref = 7507;
      return BranchStock::find(intval($ref))->quantity;
      $query = DB::table('c1ft_stock_manager.sm_branchStockTake_history')
               ->select('pos_stock_id','name','reference',DB::raw('sum(updated_quantity) as stockTake'))
               ->where('created_at','>','2019-06-06')
               ->where('shop_id',27)
               ->groupBy('pos_stock_id')
               ->get();

      foreach($query as $q){
          $q->soldQty = Common::get_productSoldQty_by_ref($q->reference,27,$from,$to);
          $q->stockIn = Common::get_product_deliveredQty_to_Branch($q->reference,27,$from,$to);
          $q->systemQty = BranchStock::find(intval($q->pos_stock_id))->quantity;
      }

      return $query;


  }

  public function test_stockTake_refs(){
      // $refs = DB::table('c1ft_stock_manager.sm_branchStockTake_history')->where('shop_id',27)->where('sealed',1)->pluck('reference')->toArray();
      // $arr = [];
      // foreach($refs as $ref){
      //     if(!Common::get_webStockID_by_ref($ref)){
      //         array_push($arr,$ref);
      //     }
      // }
      //
      // return $arr;

      $query = DB::table('c1ft_store_prestashop.ps_stock_available as a')
               ->select('a.web_stock_id','b.name','b.reference','a.quantity as current_quantity')
               ->groupBy('a.web_stock_id')
                ->where('b.sealed',1)
               ->join('c1ft_stock_manager.sm_HQstockTake_history as b','a.id_stock_available','b.web_stock_id')
                ->get();

             return $query;
  }

  public function solfdelete(){
       Branch_standard::find(2)->delete();

       // $query = DB::table('c1ft_stock_manager.sm_standardstock_branches')
       //          ->
       return Branch_standard::onlyTrashed()->get();


      // if(Branch_standard::find(1)->trashed()){
      //     return 'yes';
      // }


  }







}
