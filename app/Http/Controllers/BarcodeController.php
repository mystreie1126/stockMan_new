<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class BarcodeController extends Controller
{
    private function parts(){
        $parts = DB::table('ps_category_product as a')
                 ->select('a.id_product','b.name','d.reference as barcode')
                 ->where('a.id_category',1498)
                 //->where('a.id_product','>',186232)
                 ->join('ps_product_lang as b','a.id_product','b.id_product')
                 ->where('b.id_shop',11)
                 ->join('ps_product_shop as c','a.id_product','c.id_product')
                 ->where('c.id_shop',11)
                 ->join('ps_product as d','a.id_product','d.id_product')
                 ->orderBy('a.id_product','desc')
                 ->get();
        return $parts;
    }

    public function barcode(){
        $parts = self::parts();
        return view('barcode.barcode',compact('parts'));
    }

    public function check_parts_barcode(){
        return self::parts();
    }

    public function parts_brand(){
        $brands = DB::table('c1ft_device_manager.parts_brand')->get();
        return $brands;
    }

    public function parts_model(Request $request){
        $models = DB::table('c1ft_device_manager.parts_model')
                  ->where('brand_id',$request->brand_id)
                  ->get()->toArray();

        return $models;
    }

    public function set_barcode_parts_ref(Request $request){

        DB::table('ps_product')
        ->where('id_product',intval($request->id_product))
        ->update(['reference'=>$request->barcode]);

        return 'done';
    }
}
