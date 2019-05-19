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
      $query = DB::connection('mysql4')->table('stock')->where('user_id',244)->get()->toArray();
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
        $stock->user_id  = 214;
        $stock->standard = $stefan->standard;
        $stock->created_at = date('Y-m-d h:i:s');
        $stock->updated_at = date('Y-m-d h:i:s');

        $stock->save();
      }

      return 'updated';

    }


   public function test_ref(){
      $r = 100353;

      $arr = [];
      $a = [];



      if(!Common::get_branchStockID_by_ref($r,26)){
        return 'this dosent have pos stock id ' . $r;
      }else if(!Common::get_productName_by_ref($r)){
        return 'this dosent have proper name ' . $r;
      }else if(!Common::get_webStockID_by_ref($r)){
        return 'this dosent have webStock id ' . $r;
      }else{
        return 'all good';
      }

      //return self::getPosStockIdByRef($r,26);
      //return self::getProductNameByRef($r);
      $data = DB::table('c1ft_stock_manager.sm_updateStockRecord')->get();

      $refs = DB::table('c1ft_pos_prestashop.ps_product')->select('reference','id_product')->get();

      //first round check


      foreach($refs as $r){
        if(Common::get_branchStockID_by_ref($r->reference,26) &&
           Common::get_productName_by_ref($r->reference) &&
           Common::get_webStockID_by_ref($r->reference) &&
           Common::get_productStandard_by_ref($r->reference)
         ){
           //  $arr[] = [
           //  'pos_stock_id' => self::getPosStockIdByRef($d->reference,$d->shop_id),
           //  'web_stock_id' => $d->stock_id,
           //  'updated_qty'  => $d->updated_qty,
           //         'name'  => self::getProductNameByRef($d->reference)
           // ];
          array_push($arr,$r->reference);
        }else{
          $a[] = [
            'ref' => $r->reference,
            'id' => $r->id_product
           ];
        }

      }


       return $a;
   }




    public function allrefs(){
       if(self::get_ifhasBranchStock(12221)){
        return 'has';
       }else{
        return 'nono';
       }
    }

   public function getsalesqtybyref(Request $request){

      return self::get_productSoldQty_by_ref($request->ref,$request->shop_id,$request->from,$request->to);

   }


}
