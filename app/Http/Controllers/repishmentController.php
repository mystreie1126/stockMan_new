<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class repishmentController extends Controller
{
    public function salesForm(Request $request)
    {
      $shop_id = $request->shop_id;
      $date_from = date('Y-m-d',strtotime($request->date_start)).' '.$request->time_start;
      $date_to   = date('Y-m-d',strtotime($request->date_end)).' '.$request->time_end;

      $store_sale = DB::connection('mysql2')->table('ps_order_detail as X')
         ->select('X.product_reference',
          'X.product_name',DB::raw('sum(X.product_quantity) as quantity','X.product_id'),'X.product_id as pos_id','y.id_product as shop_id','z.quantity as hq_stock')
         ->groupBy('X.product_id')
         ->join('ps_orders','X.id_order','=','ps_orders.id_order')
         ->join('c1ft_store_prestashop.ps_product as y','y.reference','X.product_reference')
         ->join('c1ft_store_prestashop.ps_stock_available as z','z.id_product','y.id_product')
         ->where('z.id_shop',1)
         ->where('X.product_name','not like','%test%')
         ->where('X.id_shop',$shop_id)
         ->where('ps_orders.date_add','>=',$date_from)
         ->where('ps_orders.date_add','<=',$date_to)
         ->orderBy('X.product_reference','desc')
         ->get();

        //return count($store_sale);

      $web_sale = DB::table('vr_confirm_payment as a')
          ->select(DB::raw('sum(b.product_quantity) as quantity'),'b.product_name','b.product_reference','b.product_id as shop_id','y.id_product as pos_id','z.quantity as hq_qty','a.shop_name')
          ->join('ps_order_detail as b','a.order_id','b.id_order')
          ->groupBy('b.product_id')
          ->join('c1ft_pos_prestashop.ps_product as y','y.reference','b.product_reference')
          ->join('ps_stock_available as z','z.id_product','y.id_product')
          ->where('a.rockpos_shop_id',$shop_id)
          ->where('a.created_at','>=',$date_from)
          ->where('a.created_at','<=',$date_to)
          ->where('device_order',0)
          ->get();
      $shop_name = DB::table('vr_confirm_payment')->where('rockpos_shop_id',$shop_id)->value('shop_name');
      return response()->json(['web_sale'=>$web_sale,'store_sale'=>$store_sale,'shop_name'=>$shop_name]);
    }


    public function orderForm(Request $request)
    {

    }

    public function customForm(Request $request)
    {

    }


}
