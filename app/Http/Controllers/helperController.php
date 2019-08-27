<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Model\Stage\stage_HQ_replishment_history as RepHistory;
use App\Model\Record\HQ_replishment_history as RepHistory;
use App\Model\Track\Parts_stock;
//use App\Model\Record\Branch_stock_standard as Branch_standard;
use App\Model\Partner\BranchStock;
use App\Helper\Common;
use App\Model\Device\Devicepool;
use App\Model\Standard\Standard_Branch;
use App\Model\Standard\new_standard;
use App\Model\Parts\Parts_standard;


use Nexmo\Laravel\Facade\Nexmo;

use DB;
use PDF;

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

       $no_branchStockID = [];
       $no_name = [];
       $no_webstock = [];
       $pass = [];


     //   $refs = DB::table('c1ft_stock_manager.stockTakeAllShops')->pluck('ref')->toArray();
     // foreach($refs as $ref){
     //      if(!Common::get_branchStockID_by_ref($ref,36)){
     //          array_push($no_branchStockID,$ref);
     //      }
     //      else if(!Common::get_productName_by_ref($ref)){
     //           array_push($no_name,$ref);
     //      }
     //      else if(!Common::get_webStockID_by_ref($ref)){
     //          array_push($no_webstock,$ref);
     //      }
     //      else{
     //          array_push($pass ,$ref);
     //      }
     //
     //  }
     //
     //  return response()->json(['no_branchStockID' => $no_branchStockID, 'no_name' => $no_name,'noWebStock'=>$no_webstock]);
     //

        // $ref = '6959297700961';
        //
        //    if(!Common::get_branchStockID_by_ref($ref,26)){
        //        return 'no branchstock';
        //    }
        //    else if(!Common::get_productName_by_ref($ref)){
        //         return 'no name';
        //    }
        //    else if(!Common::get_webStockID_by_ref($ref)){
        //         return 'no webstock';
        //    }
        //    else{
        //        return 'all pass';
        //    }


   }



  public function getThis(){

      // $refs = DB::table('c1ft_pos_prestashop.ps_product')->pluck('reference')->toArray();
      // return $refs;
      //gel, auto focus,solid invisible,gel,
      //shockproof,commuter,defeneder

      return Common::usams_refs();
      $arr1 = [1,23,4,5,6];
      $arr2 = [2,34,5,6,23,4,6];
      return array_unique($arr2);
      return array_merge($arr1,$arr2);
      return array_diff($arr1,$arr2);

  }



  public function getThat(){

         $query =
         DB::select("select a.issue_id from
            c1ft_track_repair.issue_history a,
            c1ft_track_repair.issue_history b
            where a.issue_id = b.issue_id
            and subDate(a.created_at,10) = b.created_at
            AND b.id - a.id = 1;");

            return $query;

  }


  public function merge_stock(){
      $query = DB::select(
          "
          SELECT id_product FROM c1ft_store_prestashop.ps_stock_available

          group by id_product

          having count(id_product) > 1");
          $arr = [];
        foreach($query as $q){
            $sum_qty = DB::table('ps_stock_available')
                    ->where('id_product',$q->id_product)->where('id_product_attribute','>',0)
                    ->sum('quantity');
            if($sum_qty){
                DB::table('ps_stock_available')
                ->where('id_product',$q->id_product)->where('id_product_attribute',0)->update(['quantity'=>$sum_qty]);
            }

        }
        return 'done';

  }


}
