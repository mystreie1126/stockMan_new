<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Record\HQ_replishment_history as RepHistory;
//use App\Model\Stage\stage_HQ_replishment_history as RepHistory;
use DB;
use App\Helper\Common;
use Facades\App\Repository\Replishment;

class replishmentController extends Controller
{
  private function make_uploadList($by_sale = 0,$by_standard = 0,$by_custom = 0){
      $shops_by_sale = DB::table('c1ft_stock_manager.sm_all_replishment_history as a')
                        ->select('a.shop_id','b.name as shop_name')
                        ->join('c1ft_pos_prestashop.ps_shop as b','b.id_shop','a.shop_id')
                        ->where('a.uploaded',0)
                        ->where('a.rep_by_sale',$by_sale)
                        ->where('a.rep_by_custom',$by_custom)
                        ->where('a.rep_by_standard',$by_standard)
                        ->groupBy('a.shop_id')
                        ->get();

   foreach($shops_by_sale as $shop){
       $shop->detail = DB::table('c1ft_stock_manager.sm_all_replishment_history as a')
                       ->select('a.shop_stock_id','a.reference as barcode','a.product_name','a.updated_quantity','b.name as shopname','a.selected_startDate','a.selected_endDate','a.created_at')
                       ->where('a.shop_id',$shop->shop_id)
                       ->where('a.uploaded',0)
                       ->where('a.rep_by_sale',$by_sale)
                       ->where('a.rep_by_custom',$by_custom)
                       ->where('a.rep_by_standard',$by_standard)
                       ->join('c1ft_pos_prestashop.ps_shop as b','a.shop_id','b.id_shop')
                       ->get();
        }

        return  $shops_by_sale;
  }



  public function rep_page(){

    $shops = DB::connection('mysql2')->table('ps_shop')
          ->select('id_shop','name')
          ->whereNotIn('id_shop',[1,35,42])
          ->get();

    $need_upload = RepHistory::where('uploaded',0)->get();

    return view('stock_out/rep',compact('shops','need_upload'));
  }

  public function rep_update_page(){

      $shops_by_sale = self::make_uploadList(1,0,0);

      return view('stock_out/update_to_branch',compact('shops_by_sale'));
  }



   //get rep list by sale

    public function salesList(Request $request){
        //$request->shop_id, $request->start_time,$request->end_time
        return Replishment::branch_replishmentWithDate($request->shop_id,$request->start_time,$request->end_time);
    }

    public function save_repList(Request $request){

        $data = $request->sheetData;
        foreach($data as $d){
            $history = new RepHistory;
            $history->reference           = $d['reference'];
            $history->product_name        = $d['name'];
            $history->web_stock_id        = $d['web_stockID'];
            $history->shop_stock_id       = $d['pos_stockID'];
            $history->shop_id             = $d['shop_id'];
            $history->updated_quantity    = $d['suggest_send'];
            $history->standard_quantity   = $d['standard'];
            $history->uploaded            = 0;
            $history->rep_by_sale         = 1;
            $history->rep_by_custom       = 0;
            $history->rep_by_standard     = 0;
            $history->selected_startDate  = $d['selected_from'];
            $history->selected_endDate    = $d['selected_to'];
            $history->created_at          = date('Y-m-d h:i:s');

            if($history->save()){
                DB::table('ps_stock_available')->where('id_stock_available', $d['web_stockID'])
                ->decrement('quantity',$d['suggest_send']);
            }
        }

        return response()->json('saved');

    }


/* getting custom sales list */

    public function custom_replishment_search(Request $request){
        $result = (object)[];

        if(
            Common::get_webStockID_by_ref($request->ref) !== null &&
            Common::get_branchStockID_by_ref($request->ref,intval($request->shop_id)) !== null &&
            Common::get_productName_by_ref($request->ref) !== null &&
            Common::get_productStandard_by_ref($request->ref) !== null
         ){
             $result->ref           = $request->ref;
             $result->branchStockID = Common::get_branchStockID_by_ref($request->ref,intval($request->shop_id));
             $result->webStockID    = Common::get_webStockID_by_ref($request->ref);
             $result->name          = Common::get_productName_by_ref($request->ref);
             $result->standard      = Common::get_productStandard_by_ref($request->ref);
             $result->shop_id       = $request->shop_id;
             $result->shop_name     = DB::table('c1ft_pos_prestashop.ps_shop')->where('id_shop',intval($request->shop_id))->value('name');

              return response()->json(['result'=>$result,'pass'=>1]);
          }else{
              return response()->json(['pass'=>0]);

          }
    }

    public function custom_replishment_save(Request $request){

        // $history = new RepHistory;
        // $history->reference           = $request->detail['ref'];
        // $history->product_name        = $request->detail['name'];
        // $history->web_stock_id        = intval($request->detail['webStockID']);
        // $history->shop_stock_id       = intval($request->detail['branchStockID']);
        // $history->shop_id             = intval($request->detail['shop_id']);
        // $history->updated_quantity    = intval($request->qty);
        // $history->standard_quantity   = intval($request->detail['standard']);
        // $history->uploaded            = 0;
        // $history->rep_by_sale         = 0;
        // $history->rep_by_custom       = 1;
        // $history->rep_by_standard     = 0;
        // $history->created_at          = date('Y-m-d h:i:s');
        //
        // if($history->save()){
        //     DB::table('ps_stock_available')->where('id_stock_available',intval($request->detail['webStockID']))
        //     ->decrement('quantity',intval($request->qty));
        //
        //     return 'success';
        // }
        return $request;
    }

/* ----*/


    //update list data
    public function update_to_branch(Request $request){


        $query = DB::table('c1ft_stock_manager.sm_all_replishment_history')
                ->where('uploaded',0)
                ->where('shop_id',$request->shop_id)
                ->where('rep_by_sale',$request->by_sale)
                ->get();

        foreach($query as $q){
            DB::table('c1ft_pos_prestashop.ps_stock_available')->where('id_stock_available',$q->shop_stock_id)
                ->increment('quantity',intval($q->updated_quantity));
        }

        DB::table('c1ft_stock_manager.sm_all_replishment_history')
            ->where('uploaded',0)
            ->where('shop_id',$request->shop_id)
            ->where('rep_by_sale',$request->by_sale)
            ->update(['uploaded'=>1]);

        return redirect()->route('rep_update');
    }

    public function delete_before_update_to_branch(Request $request){
        $query = DB::table('c1ft_stock_manager.sm_all_replishment_history')
                ->where('uploaded',0)
                ->where('shop_id',$request->shop_id)
                ->where('rep_by_sale',$request->by_sale)
                ->get();
        foreach($query as $q){
            DB::table('ps_stock_available')->where('id_stock_available',$q->web_stock_id)
            ->increment('quantity',intval($q->updated_quantity));
        }

        DB::table('c1ft_stock_manager.sm_all_replishment_history')
            ->where('uploaded',0)
            ->where('shop_id',$request->shop_id)
            ->where('rep_by_sale',$request->by_sale)
            ->delete();

        return redirect()->route('rep_update');

    }













































}
