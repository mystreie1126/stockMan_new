<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\HQ\Stock as HQ_stock;
use App\Partner\Stock as pos_stock;
use App\HQ\req_history as send;

class repishmentController extends Controller
{
    public function salesForm(Request $request)
    {
      $shop_id = $request->shop_id;
      $date_from = date('Y-m-d',strtotime($request->date_start)).' '.$request->time_start;
      $date_to   = date('Y-m-d',strtotime($request->date_end)).' '.$request->time_end;

         $store_sale_update = DB::connection('mysql2')->table('ps_order_detail as X')

            ->select( DB::raw('sum(X.product_quantity) as quantity','X.product_id'),
                      'X.product_name',
                      'X.product_reference',
                      'y.id_product as shop_product_id',
                      'X.product_id as pos_product_id',
                      'X.id_shop',
                      'z.quantity as hq_stock')
            ->groupBy('X.product_id')
            ->join('ps_orders','X.id_order','=','ps_orders.id_order')
            ->join('c1ft_store_prestashop.ps_product as y','y.reference','X.product_reference')
            ->join('c1ft_store_prestashop.ps_stock_available as z','z.id_product','y.id_product')
            ->where('X.product_name','not like','%test%')
            ->where('X.id_shop',$shop_id)
            ->where('ps_orders.date_add','>=',$date_from)
            ->where('ps_orders.date_add','<=',$date_to)
            ->orderBy('X.product_reference','desc')
            ->get();

            $missing_ref = [];
            for($i = 0; $i<count($store_sale_update);$i++){
              array_push($missing_ref,$store_sale_update[$i]->product_reference);
            }


        $missing = DB::connection('mysql2')->table('ps_order_detail as X')
           ->select('X.product_reference',
            'X.product_name',DB::raw('sum(X.product_quantity) as quantity','X.product_id as pos_product_id'))
           ->groupBy('X.product_id')
           ->join('ps_orders','X.id_order','=','ps_orders.id_order')
           ->where('X.product_name','not like','%test%')
           ->where('X.id_shop',$shop_id)
           ->where('ps_orders.date_add','>=',$date_from)
           ->where('ps_orders.date_add','<=',$date_to)
           ->whereNotIn('X.product_reference',$missing_ref)
           ->orderBy('X.product_reference','desc')
           ->get();




      $web_sale = DB::table('vr_confirm_payment as a')
          ->select(DB::raw('sum(b.product_quantity) as quantity'),
                  'b.product_name',
                  'b.product_reference',
                  'b.product_id as shop_product_id',
                  'b.id_shop',
                  'z.quantity as hq_stock',
                  'y.id_product as pos_product_id')
          ->join('ps_order_detail as b','a.order_id','b.id_order')
          ->groupBy('b.product_id')
           ->join('c1ft_pos_prestashop.ps_product as y','y.reference','b.product_reference')
          ->join('ps_stock_available as z','z.id_product','b.product_id')
          ->where('a.rockpos_shop_id',$shop_id)
          ->where('a.created_at','>=',$date_from)
          ->where('a.created_at','<=',$date_to)
          ->where('device_order',0)
          ->get();


      $shop_name = DB::table('vr_confirm_payment')->where('rockpos_shop_id',$shop_id)->value('shop_name');

      return response()->json(['web_sale'=>$web_sale,
                                'store_sale'=>$store_sale_update,
                                'shop_name'=>$shop_name,
                                'missing'=>$missing]);
    }




    public function save_saleList(Request $request){

      $list = $request->json()->all();

      $saved_id = [];

      for($i = 0; $i<count($list);$i++){

        DB::table('ps_stock_available')
           ->where('id_product',$list[$i]['shop_product_id'])
           ->decrement('quantity',$list[$i]['qty']);

        $send = new send;
        $send->shop_product_id = $list[$i]['shop_product_id'];
        $send->pos_product_id = $list[$i]['pos_product_id'];
        $send->reference = $list[$i]['ref'];
        $send->quantity = $list[$i]['qty'];
        $send->shop_id = $list[$i]['shop_id'];
        $send->send = 0;
        $send->created_at=date("Y-m-d H:i:s");
        $send->save();

        array_push($saved_id,$send->id);

         // DB::connection('mysql2')->table('ps_stock_available')
         //    ->where('id_product',$list[$i]['pos_product_id'])
         //    ->increment('quantity',$list[$i]['qty']);
      }

        if(count($saved_id)>0){
          //get all shops
            $shops = DB::table('sm_replishment_history')
                  ->select(DB::raw('distinct(shop_id)'))
                  ->where('send',0)->get();
            $shops = send::select('shop_id')->where('send',0)->distinct()->get();
            $stage_send = [];

            //loop to get each shop data
            for($c = 0; $c<$shops->count();$c++){
              $send = DB::table('sm_replishment_history')
                    ->where('send',0)
                    ->where('shop_id',$shops[$c]->shop_id)->get();

              $shop_name = DB::connection('mysql2')->table('ps_shop')
                    ->select('name')->where('id_shop',$shops[$c]->shop_id)
                    ->value('name');

              $last_update = DB::table('sm_replishment_history')
                    ->select('created_at')
                    ->where('send',0)
                    ->where('shop_id',$shops[$c]->shop_id)
                    ->orderBy('created_at','desc')
                    ->value('created_at');

              array_push($stage_send,['sendQty'=>$send->count(),'shop_name'=>$shop_name,
                                      'last_update'=>$last_update,'shop_id'=>$shops[$c]->shop_id]);
            }


            return $stage_send;
        }

    }

    public function getSavedList(){
      $shops = DB::table('sm_replishment_history')
            ->select(DB::raw('distinct(shop_id)'))
            ->where('send',0)->get();
      $shops = send::select('shop_id')->where('send',0)->distinct()->get();
      $stage_send = [];

      //loop to get each shop data
      for($c = 0; $c<$shops->count();$c++){
        $send = DB::table('sm_replishment_history')
              ->where('send',0)
              ->where('shop_id',$shops[$c]->shop_id)->get();

        $shop_name = DB::connection('mysql2')->table('ps_shop')
              ->select('name')->where('id_shop',$shops[$c]->shop_id)
              ->value('name');

        $last_update = DB::table('sm_replishment_history')
              ->select('created_at')
              ->where('send',0)
              ->where('shop_id',$shops[$c]->shop_id)
              ->orderBy('created_at','desc')
              ->value('created_at');

        array_push($stage_send,['sendQty'=>$send->count(),'shop_name'=>$shop_name,
                                'last_update'=>$last_update,'shop_id'=>$shops[$c]->shop_id]);
      }


      return $stage_send;
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


}
