<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Record\HQ_replishment_history as RepHistory;
//use App\Model\Stage\stage_HQ_replishment_history as RepHistory;
use DB;
use App\Helper\Common;
use Facades\App\Repository\Replishment;
use Mail;
use App\Mail\replishmentEmail;
use App\Model\Partner\Shop;

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


  private function shopname($shop_id){
      $shopname = DB::table('c1ft_pos_prestashop.ps_shop')->where('id_shop',$shop_id)->value('name');
      return $shopname;
  }

  private function shopemail($shop_id){
      $email = DB::table('c1ft_stock_manager.sm_shop_email')->where('shop_id',$shop_id)->value('shop_mail');
      return $email;

  }
  /*----------------------------------Replishment page------------------------------------------------------------------------------------------------------*/


   public function rep_page(){

     $shops = DB::connection('mysql2')->table('ps_shop')
           ->select('id_shop','name')
           ->whereNotIn('id_shop',[1,35,42])
           ->get();

     $need_upload = RepHistory::where('uploaded',0)->get();

     return view('stock_out/rep',compact('shops','need_upload'));
   }

   /*

   PAGE ACTION

   */

  /*=============1. REPLISHMENT BY SALE ACTION================================================================================================  */


    public function salesList(Request $request){
        return Replishment::branch_replishmentWithDate($request->shop_id,$request->start_time,$request->end_time);
    }

    public function save_repList(Request $request){

        $data = json_decode($request->sheetData,true);

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
            $history->created_at          = date('Y-m-d H:i:s');

             //$history->save();
            if($history->save()){
                DB::table('ps_stock_available')->where('id_stock_available', $d['web_stockID'])
                ->decrement('quantity',$d['suggest_send']);
            }
        }

        return response()->json('saved');

    }


/*=========2. REPLISHMENT BY SDANRARD ACTION================================================================================================  */

public function standard_replishment_list(Request $request){

    return Replishment::branch_replishmentWithStandard($request->shop_id);
}


public function save_standard_replist(Request $request){

    $data = json_decode($request->sheetData,true);

    foreach($data as $d){

        $history = new RepHistory;
        $history->reference           = (string)$d['reference'];
        $history->product_name        = (string)$d['name'];
        $history->web_stock_id        = intval($d['webStockID']);
        $history->shop_stock_id       = intval($d['branchStockID']);
        $history->shop_id             = intval($d['shop_id']);
        $history->updated_quantity    = intval($d['send']);
        $history->standard_quantity   = intval($d['standard']);
        $history->uploaded            = 0;
        $history->rep_by_sale         = 0;
        $history->rep_by_custom       = 0;
        $history->rep_by_standard     = 1;
        $history->created_at          = date('Y-m-d H:i:s');

        //$history->save();

        if($history->save()){
            DB::table('ps_stock_available')->where('id_stock_available', $d['webStockID'])
            ->decrement('quantity',$d['send']);
        }
    }


    return response()->json('saved');
}

/*=========3. REPLISHMENT BY Custom ACTION================================================================================================  */

    public function custom_get_rep_data(Request $request){
        $search_result = DB::table('c1ft_pos_prestashop.ps_product as a')
                       ->select('a.reference','b.name',DB::raw('concat(a.reference,b.name) as str'))
                       ->join('c1ft_pos_prestashop.ps_product_lang as b','a.id_product','b.id_product')
                       ->where('b.id_shop',26)
                       ->where(DB::raw('concat(a.reference,b.name)'),'like','%'.$request->search.'%')
                       ->first();
        if($search_result){
            $ref = $search_result->reference;
            $name = $search_result->name;
            if(Common::get_webStockID_by_ref($ref) !== null && Common::get_branchStockID_by_ref($ref,$request->shop_id) !== null){
                return response()->json(['exsists'=>1,'result'=>[
                        'ref'=>$ref,
                        'name'=>$name,
                        'pos_stock_id'=>Common::get_branchStockID_by_ref($ref,$request->shop_id),
                        'web_stock_id'=>Common::get_webStockID_by_ref($ref),
                        'shopname'    =>Shop::find(intval($request->shop_id))->name,
                        'shop_id'     =>$request->shop_id,
                        'send'        =>''
                    ]]);
            }else{
                return response()->json(['exsists'=>0]);
            }
        }else{
            return response()->json(['missing'=>1]);
        }
    }

    public function custom_rep_data_save(Request $request){

        $data = json_decode($request->list,true);

        foreach($data as $d){

            $history = new RepHistory;
            $history->reference           = (string)$d['ref'];
            $history->product_name        = (string)$d['name'];
            $history->web_stock_id        = intval($d['web_stock_id']);
            $history->shop_stock_id       = intval($d['pos_stock_id']);
            $history->shop_id             = intval($d['shop_id']);
            $history->updated_quantity    = intval($d['send']);
            $history->standard_quantity   = -1;
            $history->uploaded            = 0;
            $history->rep_by_sale         = 0;
            $history->rep_by_custom       = 1;
            $history->rep_by_standard     = 0;
            $history->created_at          = date('Y-m-d H:i:s');

            // $history->save();

            if($history->save()){
                DB::table('ps_stock_available')->where('id_stock_available', $d['web_stock_id'])
                ->decrement('quantity',$d['send']);
            }
        }

        return response()->json('saved custom ');
    }




/*----------------------------------update to branch page------------------------------------------------------------------------------------------------------  */

      public function rep_update_page(){

          $shops_by_sale     = self::make_uploadList(1,0,0);
          $shops_by_standard = self::make_uploadList(0,1,0);
          $shops_by_custom   = self::make_uploadList(0,0,1);

          return view('stock_out/update_to_branch',compact('shops_by_sale','shops_by_standard','shops_by_custom'));
      }

  /*

  PAGE ACTION

  */


    //UPDATE SALED REPLISHMENT LIST AND SEND NOTIFICATION EMAIL

    private function mark_as_uploaded($sale,$standard,$custom,$shop_id){
        DB::table('c1ft_stock_manager.sm_all_replishment_history')
            ->where('uploaded',0)
            ->where('shop_id',$shop_id)
            ->where('rep_by_sale',$sale)
            ->where('rep_by_custom',$custom)
            ->where('rep_by_standard',$standard)
            ->update(['uploaded'=>1]);
    }


    public function update_to_branch(Request $request){
        $query = DB::table('c1ft_stock_manager.sm_all_replishment_history')
                ->where('uploaded',0)
                ->where('shop_id',$request->shop_id)
                ->where('rep_by_sale',$request->by_sale)
                ->where('rep_by_custom',$request->by_custom)
                ->where('rep_by_standard',$request->by_standard)
                ->get();
        $shopname = self::shopname($request->shop_id);
        $email = self::shopemail($request->shop_id);

        foreach($query as $q){
            DB::table('c1ft_pos_prestashop.ps_stock_available')->where('id_stock_available',$q->shop_stock_id)
                ->increment('quantity',intval($q->updated_quantity));
        }

        if(intval($request->by_sale) == 1){

            self::mark_as_uploaded(1,0,0,$request->shop_id);

        }else if(intval($request->by_standard) == 1){

            self::mark_as_uploaded(0,1,0,$request->shop_id);

        }else if(intval($request->by_custom) == 1){

            self::mark_as_uploaded(0,0,1,$request->shop_id);

        }

        //return new replishmentEmail($query,$shopname);

        Mail::to($email)->send(new replishmentEmail($query,$shopname));

        return redirect()->route('rep_update');
    }


    //DELETE SALE LIST

    private function delete_list($sale,$standard,$custom,$shop_id){
        DB::table('c1ft_stock_manager.sm_all_replishment_history')
            ->where('uploaded',0)
            ->where('shop_id',$shop_id)
            ->where('rep_by_sale',$sale)
            ->where('rep_by_custom',$custom)
            ->where('rep_by_standard',$standard)
            ->delete();
    }


    public function delete_before_update_to_branch(Request $request){
        $query = DB::table('c1ft_stock_manager.sm_all_replishment_history')
                ->where('uploaded',0)
                ->where('shop_id',$request->shop_id)
                ->where('rep_by_sale',$request->by_sale)
                ->where('rep_by_custom',$request->by_custom)
                ->where('rep_by_standard',$request->by_standard)
                ->get();

        foreach($query as $q){
            DB::table('ps_stock_available')->where('id_stock_available',$q->web_stock_id)
            ->increment('quantity',intval($q->updated_quantity));
        }

        if(intval($request->by_sale) == 1){

            self::delete_list(1,0,0,$request->shop_id);

        }else if(intval($request->by_standard) == 1){

            self::delete_list(0,1,0,$request->shop_id);

        }else if(intval($request->by_custom) == 1){

            self::delete_list(0,0,1,$request->shop_id);

        }

        return redirect()->route('rep_update');

    }













































}
