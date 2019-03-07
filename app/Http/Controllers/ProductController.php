<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\HQ\Product\Product as Product;
use App\HQ\Product\Product_lang;
use App\HQ\Product\Product_stock;

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
}
