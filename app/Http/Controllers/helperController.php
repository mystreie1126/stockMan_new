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

use App\tt;
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

      $query = DB::table('c1ft_stock_manager.athlone_pos_deduct')->get();

      foreach($query as $q){
          DB::table('c1ft_pos_prestashop.ps_stock_available')
          ->where('id_stock_available',intval($q->stock_id))
          ->decrement('quantity',$q->qty);
      }

      return 'sss';


      foreach($refs as $ref){
          if(!Common::get_webStockID_by_ref($ref)){
              array_push($no_name,$ref);
              //return 'this dosent have pos stock id ' . $ref;
          }
      }
      $name = [];

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
            return 'webstockID'.Common::get_webStockID_by_ref($ref);
          }





   }


  public function getThis(){
      //
      // $query = DB::table('ps_specific_price as a')
      //          ->select('a.id_product','a.reduction','b.price',DB::raw('(b.price - a.reduction)*7.5/7'),DB::raw('b.price-((b.price - a.reduction)*7.5/7) as new_reduction'))
      //          ->where('a.id_shop',11)
      //          ->where('a.id_group',1)
      //          ->where('a.reduction_tax','>',0)
      //          ->join('ps_product as b','a.id_product','b.id_product')
      //          ->where(DB::raw('(b.price - a.reduction)*7.5/7'),'>','0')
      //          ->get();
      //
      // foreach($query as $q){
      //     DB::table('ps_specific_price')->where('id_product',$q->id_product)->where('id_shop',11)->where('id_group',1)
      //     ->update(['reduction'=>$q->new_reduction]);
      // }
      //
      // return 'done';

      return $query;
  }


  public function stockTake_check(){
     $query = DB::table('c1ft_stock_manager.need_to_merge')->get();
     $from = '2019-06-13 23:00:00';
     $to = '2019-06-18';

     foreach($query as $q){
        $q->stockId = Common::get_branchStockID_by_ref($q->ref,27);
        $q->soldQty = Common::get_productSoldQty_by_ref($q->ref,27,$from,$to);
        $q->stockIn_qty = Common::get_product_deliveredQty_to_Branch($q->ref,27,$from,$to);

        $q->current_qty = intval($q->qty) - intval($q->soldQty) + intval($q->stockIn_qty);

        DB::table('c1ft_pos_prestashop.ps_stock_available')->where('id_stock_available',$q->stockId)->update(['quantity'=>intval($q->current_qty)]);
     }

     return $query;
  }

  public function soldAll(){
      $ref = 'KITS2BK';
      $from="2019-06-15";
      $to  ="2019-06-16";
      //return $to;

      $shops = DB::table('c1ft_pos_prestashop.ps_shop')->select('id_shop','name')->get();
      $arr=[];
      foreach($shops as $shop){
          $arr[]=[
              'name'=>$shop->name,
              'sold'=>Common::get_productSoldQty_by_ref($ref,$shop->id_shop,$from,$to)
          ];
      }
      return $arr;
      return Common::get_productSoldQty_by_ref_allShop($ref,$from,$to);
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

  public function standard_model(){
      $shop_id = 26;
      $from = '2019-06-17 00:00:00';
      $to = '2019-06-19 23:59:59';
      $ref = "6958444955292";

      $pos_qty = DB::table('c1ft_pos_prestashop.ps_order_detail as detail')
                ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
                ->join('c1ft_pos_prestashop.ps_orders as order','order.id_order','detail.id_order')
                ->whereBetween('order.date_add',[$from,$to])
                ->where('detail.id_shop',$shop_id)
                ->where('detail.product_reference',$ref)
                ->groupBy('detail.product_reference')
                ->value('soldQty');

                $web_qty = DB::table('ps_product_attribute as attr')
                            ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
                            ->where('attr.reference',$ref)
                            ->join('ps_order_detail as detail','attr.id_product_attribute','detail.product_attribute_id')
                            ->groupBy('detail.product_attribute_id')
                            ->join('vr_confirm_payment as webSales','webSales.order_id','detail.id_order')
                            ->where('webSales.device_order',0)
                            ->where('webSales.rockpos_shop_id',$shop_id)
                            ->whereBetween('webSales.created_at',[$from,$to])
                            ;

        return intval($pos_qty);
  }






}
