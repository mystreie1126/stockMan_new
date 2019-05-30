<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Record\HQ_replishment_history as RepHistory;
use DB;
use App\Helper\Common;
use Facades\App\Repository\Replishment;

class replishmentController extends Controller
{

  public function index(){
    $shops = DB::connection('mysql2')->table('ps_shop')
          ->select('id_shop','name')
          ->whereNotIn('id_shop',[1,35,42])
          ->get();
    return view('rep',compact('shops'));
  }

   //get rep list by sale

    public function salesList(Request $request){
        //$request->shop_id, $request->start_time,$request->end_time

        return Replishment::branch_replishmentWithDate($request->shop_id,$request->start_time,$request->end_time);

    }


/* end of getting sales list by sales */

/* getting custom sales list */
//shop_id, reference
    public function customRepList(Request $request){
        $search = DB::table('c1ft_pos_prestashop.ps_product')
                ->where('reference','like','%'.$request->input_reference.'%')
                ->get();
        if($search->count() > 0){
          $ref = $search[0]->reference;
          return self::singleRefRepDetails($ref,$request->shop_id);
        }
    }
/* ----*/




    public function save_repList(Request $request){

        //$data = $request->json()->all();
        $data = $request->sheetData;

        //return count($data);
        foreach($data as $d){
            $history = new RepHistory;
            $history->reference           = $d['reference'];
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

            $history->save();
        }



        return response()->json('saved and updated');

    }

    public function getSavedList(Request $request){
      $arr = [1,2,3];
      return response()->json($arr);
    }



    /*ready to export */

    public function readyToExport(Request $request)
    {
        $sendList = DB::table('sm_replishment_history as a')->select('a.reference as reference','a.quantity as Quantity','b.name')
                  ->join('ps_product_lang as b','a.shop_product_id','b.id_product')
                  ->where('b.id_shop',1)
                  ->where('a.send',0)
                  ->where('shop_id',$request->shop_id)
                  ->get();

       $shop_name = DB::connection('mysql2')->table('ps_shop')
                ->select('name')->where('id_shop',$request->shop_id)
                ->value('name');

        return response()->json(['list'=>$sendList,'shop'=>$shop_name,'date'=>date('Y-m-d')]);
    }


    /*ready to send */

    public function readyToSend(Request $request)
    {
      $flag_arr = [];
      $list = send::select('pos_product_id','quantity')
                   ->where('send',0)->where('shop_id',$request->shop_id)->get();
      // return $request->shop_id;

       for($i = 0; $i<$list->count(); $i++){
         DB::connection('mysql2')->table('ps_stock_available')
            ->where('id_shop',$request->shop_id)
            ->where('id_product',$list[$i]->pos_product_id)
            ->increment('quantity',$list[$i]->quantity);

              array_push($flag_arr,$i);
       }

      if(count($flag_arr)>0){
        DB::table('sm_replishment_history')->where('send',0)->where('shop_id',$request->shop_id)->update(['send'=>1]);
        return response()->json(['msg'=>'successfull added qty to pos','arr'=>count($flag_arr)]);
      }
    }

    /*ready to delete */
    public function readyToDelete(Request $request)
    {
     $flag_arr = [];
     $list = send::select('shop_product_id','quantity')
                  ->where('send',0)->where('shop_id',$request->shop_id)->get();

      for($i = 0; $i<$list->count(); $i++){
        DB::table('ps_stock_available')
           ->where('id_product',$list[$i]->shop_product_id)
           ->where('id_shop_group',3)
           ->increment('quantity',$list[$i]->quantity);
          array_push($flag_arr,$i);
      }

      if(count($flag_arr)>0){
        DB::table('sm_replishment_history')->where('send',0)->where('shop_id',$request->shop_id)->delete();
        return response()->json(['msg'=>'successfull added qty back','arr'=>count($flag_arr)]);

      }


    }

    public function check(){
        $arr = [];
    		$updatedRecord = DB::connection('mysql3')->table('sm_updateStockRecord')->where('created_at','!=','2019-04-26 00:00:00')->get()->toArray();
    		for($i = 0; $i < count($updatedRecord); $i++){
    			$name = DB::table('ps_product_lang')->where('id_product',$updatedRecord[$i]->id_product)->value('name');
    			$sendQty = DB::table('sm_replishment_history')->where('created_at','>=',$updatedRecord[$i]->created_at)->where('shop_product_id',$updatedRecord[$i]->id_product)->sum('quantity');
    			$currentQty = DB::table('ps_stock_available')->where('id_stock_available',$updatedRecord[$i]->stock_id)->value('quantity');
    			 $arr[]=[
    			 		    'name'=>$name,
    			 	    'barcode' =>$updatedRecord[$i]->reference,
    			 	 'updated_qty'=>$updatedRecord[$i]->updated_qty,
    			    'updated_time'=>$updatedRecord[$i]->created_at,
    			 	    'send_qty'=>$sendQty,
    			     'current_qty'=>$currentQty,
    			  ];
    		}
    		return response()->json($arr);
    }








































}
