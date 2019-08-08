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

use App\tt;
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


//
// $no_branchStockID = [];
// $no_name = [];
// $no_webstock = [];
// $pass = [];
//
// $final_lists = [];
//     foreach($refs as $ref){
//         $final_lists[]=[
//             'name' => (Common::get_productName_by_ref($ref)!== null) ? Common::get_productName_by_ref($ref) :"no name",
//             'ref'  => $ref,
//             'standard_gorey' => (Common::get_standardQty_by_ref($ref,30)!==null) ? Common::get_standardQty_by_ref($ref,30) :'Need Standard',
//             'standard_athlone' => (Common::get_standardQty_by_ref($ref,27)!==null) ? Common::get_standardQty_by_ref($ref,27) :'Need Standard',
//             'standard_mill'  => (Common::get_standardQty_by_ref($ref,26)!==null) ? Common::get_standardQty_by_ref($ref,26) :'Need Standard',
//             'standrd_marketcross' => (Common::get_standardQty_by_ref($ref,34)!==null) ? Common::get_standardQty_by_ref($ref,34) :'Need Standard',
//             'standard_millfield' => (Common::get_standardQty_by_ref($ref,37)!==null) ? Common::get_standardQty_by_ref($ref,37) :'Need Standard'
//         ];
//     }
//
//     return $final_lists;
//
//      $standard_refs = DB::table('c1ft_stock_manager.sm_standard_branch')->groupBy('reference')->pluck('reference')->toArray();
//
//      return Common::missingPart($standard_refs,$refs);


    $ref = 6958444967806;

      // foreach($refs as $ref){
          if(!Common::get_branchStockID_by_ref($ref,33)){
              //array_push($no_branchStockID,$ref);
              return 'no branchstock';
          }
          else if(!Common::get_productName_by_ref($ref)){
               //array_push($no_name,$ref);
               return 'no name';
          }
          else if(!Common::get_webStockID_by_ref($ref)){
              //array_push($no_webstock,$ref);
               return 'no webstock';
          }
          else{
              //array_push($pass ,$ref);
              return 'all pass';
          }

      // }


    return response()->json(['nobranchstock' => $no_branchStockID, 'noname'=>$no_name, 'nowebstock' => $no_webstock,'allpass'=>$pass]);

   }



  public function getThis(){
      $refs = DB::table('c1ft_stock_manager.sm_standard_branch')->where('shop_id',27)->pluck('reference')->toArray();
      $ns = [];
      foreach($refs as $ref){
          $ns[] = [
              'name' => Common::get_productName_by_ref($ref),
              'code' => $ref
          ];
      }
      return $ns;


      $shop_id = 26;

      return Common::get_standardQty_by_ref($ref,$shop_id);

      return 'done';


      // $ref      = 6958444949659;
      // $shop_id  = 30;
      // $from     = '2019-07-25 00:00:00';
      // $to       = '2019-07-25';
      // return Common::get_productSoldQty_by_ref($ref,$shop_id,$from,$to);
      //
      //
      //
      // $no_wholesale =[];
      // $valid_tax = [8,9];
      // $no_tax = [];

      // $id_group = 1;
      // $wholesale_price = 2.14;

      $query = DB::table('c1ft_stock_manager.wholesale')->get();


      foreach($query as $q){
          self::get_reduction_price($q->ref,$q->wholesale,5);
      }

      return 'finished';

      foreach($refs as $ref){
          if(!in_array(self::check_has_tax($ref),$valid_tax)){
              array_push($no_tax,$ref);
          }
      }

      foreach($refs as $ref){
          if(self::check_wholesale_price($ref,$id_group) == NULL){
               array_push($no_wholesale,$ref);
          }
      }


      //return response()->json(['no_tax'=>$no_tax,'no_wholesale'=>$no_wholesale]);

      // foreach($refs as $ref){
      //     self::get_reduction_price($ref,$wholesale_price,$id_group);
      //
      // }
      return 'done';



     // $id_group = 1;
     // $wholesale_price = 4.82;
     // $id = 183611;
     //
     //   return self::get_reduction_price_byID($id,$wholesale_price,$id_group);



  }

  public function getThat(){

      $refs = [

];



      return PDF;





      //athlone has gorey none
      $athlone_has_gorey_none = Common::missingPart($gorey,$athlone);
      $gorey_has_athlone_none = Common::missingPart($athlone,$gorey);

      $gorey_miss = [];
      $athlone_miss = [];

      foreach($athlone_has_gorey_none as $ref){
           $gorey_miss[]=[
              'name'=>Common::get_productName_by_ref($ref),
              'ref'=>$ref
          ];
      }

      foreach($gorey_has_athlone_none as $ref){
          $athlone_miss[]=[
              'name' => Common::get_productName_by_ref($ref),
              'ref'  => $ref
          ];
      }
      //return  $athlone_miss;
      return  $gorey_miss;


      return $query;
      return count($gorey);





  }

  private function get_id($ref){
    $query = DB::table('ps_product')->select('id_product')->where('reference',$ref)->value('id_product');
    return $query;
  }

  private function check_wholesale_price($ref,$id_group){
      $price = DB::table('ps_product as a')
               ->select('a.price',DB::raw('a.price - b.reduction as new_wholesale'))
               ->where('a.reference',$ref)
               ->join('ps_specific_price as b','a.id_product','b.id_product')
               ->where('b.id_shop',11)
               ->where('b.id_group',$id_group)
               ->get();
      if($price->count() == 1){
          return number_format($price[0]->new_wholesale, 2);
      }
      // return $price;
  }


  private function check_has_tax($ref){
      $price = DB::table('ps_product')->select('id_tax_rules_group')->where('reference',$ref)->get();

      if($price->count() == 1)
      return intval($price[0]->id_tax_rules_group);
  }


  private function get_reduction_price($ref,$wholesale,$id_group){
      $price = DB::table('ps_product')->where('reference',$ref)->value('price');
      $id = DB::table('ps_product')->where('reference',$ref)->value('id_product');


      if($price){
          DB::table('ps_specific_price')->where('id_product',$id)->where('id_shop',11)->where('id_group',$id_group)
          ->update(['reduction' => $price - $wholesale]);

          DB::table('ps_specific_price')->where('id_product',$id)->where('id_shop',11)->where('id_group',$id_group)
          ->update(['reduction_tax' => 0]);

          return 'lol';
      }

      return 'done';
  }

  private function get_reduction_price_byID($id,$wholesale,$id_group){
      $price = DB::table('ps_product')->where('id_product',$id)->value('price');

      if($price){
          DB::table('ps_specific_price')->where('id_product',$id)->where('id_shop',11)->where('id_group',$id_group)
          ->update(['reduction' => $price - $wholesale]);

          DB::table('ps_specific_price')->where('id_product',$id)->where('id_shop',11)->where('id_group',$id_group)
          ->update(['reduction_tax' => 0]);

          return 'lol';
      }

      return 'done';
  }

  public function check_earphone(){
      $ref = 6958444966502;
      $from = '2019-06-07 12:57:00';
      $to  = '2019-07-12 00:00:00';

      $query = DB::table('c1ft_stock_manager.sm_all_replishment_history')
             ->select(DB::raw('sum(updated_quantity) as send'),'shop_id','reference as ref')
             ->where('reference',$ref)
             ->groupBy('shop_id')
             ->get();

      foreach($query as $q){
          $q->sold = Common::get_productSoldQty_by_ref($ref,$q->shop_id,$from,$to);
      }

      return $query;

  }

  public function delete_standard(){
      $ref = [];

      new_standard::find(1)->delete();

      //Standard_Branch::whereIn('reference',$ref)->delete();

      return 'done';
  }



}
