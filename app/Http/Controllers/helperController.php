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
use Excel;
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
      $parts = DB::table('c1ft_stock_manager.ns_parts')->get();

      foreach($parts as $part){
          DB::table('c1ft_pos_prestashop.ps_stock_available')->where('id_product',$part->id)
          ->where('id_shop',26)
          ->update(['quantity'=> intval($part->qty)]);
      }
      return 'done';

    }



   public function test_ref(){

       $no_branchStockID = [];
       $no_name = [];
       $no_webstock = [];
       $pass = [];


     //   $refs = DB::table('c1ft_stock_manager.stockTakeAllShops')->pluck('ref')->toArray();

     $refs = [

         6934177707452
];
     foreach($refs as $ref){
          if(!Common::get_branchStockID_by_ref($ref,36)){
              array_push($no_branchStockID,$ref);
          }
          else if(!Common::get_productName_by_ref($ref)){
               array_push($no_name,$ref);
          }
          else if(!Common::get_webStockID_by_ref($ref)){
              array_push($no_webstock,$ref);
          }
          else{
              array_push($pass ,$ref);
          }

      }

      return response()->json(['no_branchStockID' => $no_branchStockID, 'no_name' => $no_name,'noWebStock'=>$no_webstock]);


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

   private function abc(){
        $var1 = 0;
        return $var1+2;
    }

    public function cde(){

    }

  public function getThis(){
      $time = '2019-09-01';
    $sold = DB::table('c1ft_pos_prestashop.ps_orders as a')
    ->join('c1ft_pos_prestashop.ps_order_detail as b','a.id_order','b.id_order')
    ->select(DB::raw('b.product_quantity as qty'))
    ->where('b.product_id',3663)
    ->where('b.id_shop',28)
    ->where('a.date_add','>=',$time)
    ->groupBy('b.product_id')->get();
    //->value('qty');
    return intval($sold);
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

  public function demon_stockCheck(){
      $results = DB::table('c1ft_stock_manager.sm_demo_stockcheck')
               ->select('name','barcode',DB::raw('sum(qty) as total'))
               ->groupBy('barcode')
               ->orderBy('barcode')
               ->get();
      return view('demon_stockCheck',compact('results'));
  }

  public function import(Request $request){

      // $this->validate($request, [
      //     'select_file'  => 'required|mimes:xls,xlsx'
      // ]);

        $shop_id = 36;

        $path = $request->file('select_file')->getRealPath();

        $sheet_data = Excel::load($path)->get();


        $keys = [];
        foreach ($sheet_data[0] as $key => $value) {
            array_push($keys,$key);
         }
        /*
            $keys[2] -> name
            $keys[3] -> imei
            $keys[4] -> status

        */
        $devices = [];
        foreach($sheet_data as $sheet){
            if($sheet[$keys[2]] !== null && $sheet[$keys[3]] !== null && $sheet[$keys[4]] !== null)
            array_push($devices,$sheet);
        }

        $instock_check = [];
        $sold_check=[];
        foreach($devices as $device){
            //check the in stock imei
            if(strpos(strtolower($device[$keys[4]]),'stock') !== false){
                if(Common::checkdeviceInPos_inStock((string)$device[$keys[3]],$shop_id) !== 1){
                    array_push($instock_check,$device);
                }
             }
            //check the sold imei
            else if(strpos(strtolower($device[$keys[4]]),'sold') !== false){
                if(Common::checkdeviceInPos_sold((string)$device[$keys[3]],$shop_id) !== 1){
                    array_push($sold_check,$device);
                }
            }

        }
        return redirect()->route('phone_check',compact('instock_check'));


        return $data;

      return redirect()->back()->with('errors', 'No file selected');

      return 123;

  }
}
