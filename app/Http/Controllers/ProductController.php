<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\HQ\Product\Product as Product;
use App\HQ\Product\Product_lang;
use App\HQ\Product\Product_stock;
use App\Partner\Shop as Branch;
use App\Partner\Stock as Branch_stock;

use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\eachProductResource;


class ProductController extends Controller
{


    public function index(){

    	$data = Product::with(['name','stock'])->paginate(15);

    	return ProductResource::collection($data);
    }

    public function addtionalItemWithReference(Request $request){

      $exsistInPos = DB::connection('mysql2')
      ->table('ps_product as a')->select('b.id_stock_available')
      ->join('ps_stock_available as b','a.id_product','b.id_product')
      ->where('a.reference','like','%'.$request->reference.'%')
      ->where('b.id_shop',$request->id_shop)
      ->get();

      $exsistInShop_productRef = DB::table('ps_product as a')->select('b.id_stock_available')
      ->join('ps_stock_available as b','a.id_product','b.id_product')
      ->where('a.reference','like','%'.$request->reference.'%')
      ->get();

      return 123;

    }

    public function productStockAndSell($ref){

    	$branch_stock = DB::connection('mysql2')->table('ps_product as a')
    	    ->select('a.reference','b.name','c.quantity','d.name')
    	    ->join('ps_product_lang as b','a.id_product','b.id_product')
    	    ->join('ps_stock_available as c','a.id_product','c.id_product')
    	    ->join('ps_shop as d','d.id_shop','c.id_shop')
    	    ->where('a.reference',$ref)
            ->whereNotIn('c.id_shop',[1,35,41])
    	    ->groupBy('d.name')
    	    ->get();

    	$hq_stock = DB::table('ps_product as a')
    		->join('ps_stock_available as b','a.id_product','b.id_product')
    		->where('a.reference',$ref)
    		->value('b.quantity');

        $branch_total_stock = DB::connection('mysql2')->table('ps_stock_available as a')
                    ->join('ps_product as b','a.id_product','b.id_product')
                    ->where('b.reference',$ref)
                    ->whereNotIn('a.id_shop',[1,35,41])
                    ->sum('a.quantity');


        $stockin = DB::table('sm_stockin as a')
                    ->where('a.reference',$ref)
                    ->sum('a.quantity');

        $days = -28;

        $arr = [];

        for($i = 28; $i > 0; $i-=7){


            $shop_sales = DB::connection('mysql2')->table('ps_order_detail as X')
               ->select('X.id_order','X.id_shop',
                'X.product_name',DB::raw('sum(X.product_quantity) as quantity'),'ps_orders.date_add as Date','ps_shop.name')
               ->groupBy('X.id_shop')
               ->join('ps_orders','X.id_order','=','ps_orders.id_order')
               ->join('ps_shop','ps_shop.id_shop','X.id_shop')
               ->where('X.product_reference',$ref)
               ->where('ps_orders.date_add','>=',date("Y-m-d h:i:s", strtotime($days. "day")))
               ->whereNotIn('X.id_shop',[1,35,41])
               ->orderBy('ps_orders.date_add')

               ->get();

            $web_sales = DB::table('vr_confirm_payment as a')
                       ->select('a.order_id','a.shop_name','b.date_add','c.product_reference',DB::raw('sum(c.product_quantity) as quantity'))
                       ->join('ps_orders as b','a.order_id','b.id_order')
                       ->join('ps_order_detail as c','c.id_order','a.order_id')
                       ->groupBy('a.shop_name')
                       ->where('c.product_reference',$ref)
                        ->where('b.date_add','>=',date("Y-m-d h:i:s", strtotime($days. "day")))
                       ->get();

            array_push($arr, ['weekShop'=>$shop_sales,'weekWeb'=>$web_sales]);
            $days += 7;
        }

       //return $arr;
    	return response()->json([
              'shop_stock' => ['branch'=> $branch_stock,'HQ'=>$hq_stock],
            'allShop_stock'=> ['branches'=>$branch_total_stock,'HQ'=>$hq_stock,'stockIn'=>$stockin],
            'sales' =>$arr

        ]);


    }












}
