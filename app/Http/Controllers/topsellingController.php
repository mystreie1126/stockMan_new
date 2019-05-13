<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class topsellingController extends Controller
{
    //top usams selling
    //default top 100 sales from all shops

    private function usamsRefs(){
      $refs = DB::table('c1ft_pos_prestashop.ps_product_lang as name')
             ->where('name','like','%'.'usa'.'%')
             ->groupBy('name.id_product')
             ->join('c1ft_pos_prestashop.ps_product as product','name.id_product','product.id_product')
             ->pluck('reference')->toArray();
      return $refs;
    }

    private function allGlassRefs(){
      $refs = DB::connection('mysql2')->table('ps_product')
              ->select('ps_product.reference')
              ->join('ps_product_lang','ps_product_lang.id_product','ps_product.id_product')
              ->where('ps_product_lang.name','like','%'.'tempered glass'.'%')
              ->groupBy('ps_product.id_product')
              ->get();

      return $refs;
    }

    public function testA(){
      return self::allGlassRefs();
    }


    public function usamsTopSale(Request $request){
      //select time
      $usamsRefs = self::usamsRefs();
      return   $usamsRefs;
      for($i = 0; $i < count($usamsRefs); $i++){
         $pos_sales = DB::table('c1ft_pos_prestashop.ps_order_detail as detail')
                    ->select('detail.product_name as name',
                             'detail.product_reference as ref',
                             DB::raw('sum(detail.product_quantity) as soldQty'))
                    ->join('c1ft_pos_prestashop.ps_orders as order','detail.id_order','order.id_order')
                    //->whereBetween('order.date_add',[$request->from,$request->to])
                    ->where('detail.product_reference',  $usamsRefs[$i])
                    ->groupBy('detail.product_name')
                    ->get();
      }

      return $pos_sales;

    }




}
