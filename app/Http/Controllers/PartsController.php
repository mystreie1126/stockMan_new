<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use DB;
use Mail;
use PDF;
use App\Mail\parts_sendEmail;

class PartsController extends Controller
{
    private function parts_ids(){
        $parts_ids = DB::table('c1ft_pos_prestashop.ps_product')->whereBetween('id_product',[4448,4870])->pluck('id_product')->toArray();
        return $parts_ids;
    }

    private function part_standard_byID($id,$shop_id){
        $standard = DB::table('c1ft_stock_manager.sm_parts_standard')->where('parts_id',$id)->where('shop_id',$shop_id)->value('standard');
        return intval($standard);
    }

    public function get_parts_list_byStandard(Request $request){

        $parts_ids  = self::parts_ids();
        $parts_list = [];

        $parts = DB::table('c1ft_pos_prestashop.ps_product_lang as a')
                 ->select('a.name','b.quantity','a.id_product','c.standard','b.id_stock_available','b.id_shop')
                 ->groupBy('a.name')
                 ->join('c1ft_pos_prestashop.ps_stock_available as b','a.id_product','b.id_product')
                 ->where('b.id_shop',$request->shop_id)
                 ->whereIn('a.id_product',self::parts_ids())
                 ->join('c1ft_stock_manager.sm_parts_standard as c','a.id_product','c.parts_id')
                 ->where('c.shop_id',$request->shop_id)
                 ->whereRaw('c.standard - b.quantity > 0')
                 ->get();

        foreach($parts as $part){
            $part->standard = self::part_standard_byID($part->id_product,$request->shop_id);
        }

        return $parts;

    }

    public function savePartsToPos(Request $request){
        $records = json_decode($request->list,true);
        $send_ids = [];
        foreach($records as $record){
            $id = DB::table('c1ft_stock_manager.sm_parts_sendHistory')->insertGetId([
                    'parts_name'  =>$record['name'],
                    'parts_ref' => 'none',
                    'pos_parts_id' => $record['id_product'],
                    'send_quantity'    => $record['send'],
                    'shop_id'   => $record['id_shop'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);


            DB::table('c1ft_pos_prestashop.ps_stock_available')
                     ->where('id_product',$record['id_product'])
                     ->where('id_shop',$record['id_shop'])
                     ->increment('quantity',$record['send']);

            array_push($send_ids,$id);
        }

        $send_query = DB::table('c1ft_stock_manager.sm_parts_sendHistory')->whereIn('id',$send_ids)->get();
        $shop_name = Common::shopname($records[0]['id_shop']);
        $email = Common::shopemail($records[0]['id_shop']);

        Mail::to($email)
        ->cc(['warehouse@funtech.ie','hq@funtech.ie','it@funtech.ie'])
        ->send(new parts_sendEmail($send_query,$shop_name));


        return 'send';
    }
}
