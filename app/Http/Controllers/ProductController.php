<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\HQ\Product\Product as Product;
use App\HQ\Product\Product_lang;
use App\HQ\Product\Product_stock;
use App\Partner\Shop as Branch;

use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\eachProductResource;


class ProductController extends Controller
{
    

    public function index(){
    	
    	$data = Product::with(['name','stock'])->paginate(15);

    	return ProductResource::collection($data);
    }

    public function each_product($ref){
    	$product = Product::where('reference',$ref)->get();

    	return new eachProductResource($product);
    }

    public function product_each_store($ref){

    	$branches = DB::connection('mysql2')->table('ps_product as a')
    	    ->select('a.reference','b.name','c.quantity','d.name')
    	    ->join('ps_product_lang as b','a.id_product','b.id_product')
    	    ->join('ps_stock_available as c','a.id_product','c.id_product')
    	    ->join('ps_shop as d','d.id_shop','c.id_shop')
    	    ->where('a.reference',$ref)
    	    ->groupBy('d.name')
    	    ->get();

    	$hq = DB::table('ps_product as a')->select('b.quantity')
    		->join('ps_stock_available as b','a.id_product','b.id_product')
    		->where('a.reference',$ref)
    		->get();

    	return response()->json(['branch'=> $branches,'HQ'=>$hq]);
    }
}
