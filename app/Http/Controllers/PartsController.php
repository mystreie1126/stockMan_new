<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use DB;
use Mail;
use PDF;
use App\Mail\parts_sendEmail;
use App\Mail\parts_missmatchEmail;

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

    private function get_parts_repair_notes($id_product,$id_shop,$suggest_send){
        $query = DB::table('c1ft_pos_prestashop.ps_order_detail as detail')
                ->leftJoin('c1ft_pos_prestashop.ps_message as message','detail.id_order','message.id_order')
                ->join('c1ft_pos_prestashop.ps_orders as order','order.id_order','detail.id_order')
                ->join('c1ft_pos_prestashop.ps_shop as shop','shop.id_shop','detail.id_shop')
                ->join('c1ft_pos_prestashop.ps_order_history as history','history.id_order','order.id_order')
                ->join('c1ft_pos_prestashop.ps_employee as staff','history.id_employee','staff.id_employee')
                ->select('message.message','order.reference','order.date_add','staff.lastname','staff.firstname',DB::raw("concat(staff.lastname,' ',staff.firstname) as fullname"))
                ->where('detail.product_id',$id_product)
                ->where('detail.id_shop',$id_shop)
                ->orderBy('detail.id_order','desc')
                ->limit($suggest_send)
                ->get()->toArray();
        return $query;
    }

    private function get_parts_sendHistory_betweenOrder($from,$to,$shop_id,$parts_id){
        $qty = DB::table('c1ft_stock_manager.sm_parts_sendHistory')
                ->select(DB::raw('sum(send_quantity) as send'))
                ->where('shop_id',$shop_id)
                ->whereBetween('created_at',[$from,$to])
                ->where('pos_parts_id',$parts_id)
                ->groupBy('pos_parts_id')
                ->value('send');
        return intval($qty);

    }

    public function get_parts_list_byStandard(Request $request){

        $parts_ids  = self::parts_ids();
        $parts_list = [];

        $parts = DB::table('c1ft_pos_prestashop.ps_product_lang as a')
            ->select('a.name','b.quantity','a.id_product','c.standard','b.id_stock_available','b.id_shop',DB::raw('c.standard - b.quantity as suggest_send'))
            ->groupBy('a.name')
            ->join('c1ft_pos_prestashop.ps_stock_available as b','a.id_product','b.id_product')
            ->where('b.id_shop',$request->shop_id)
            ->whereIn('a.id_product',self::parts_ids())
            ->join('c1ft_stock_manager.sm_parts_standard as c','a.id_product','c.parts_id')
            ->where('c.shop_id',$request->shop_id)
            ->whereRaw('c.standard - b.quantity > 0')
            ->get();

        // foreach($parts as $part){
        //     $part->standard = self::part_standard_byID($part->id_product,$request->shop_id);
        // }
        return $parts;
    }

    public function savePartsToPos(Request $request){
        $records = json_decode($request->list,true);
        $send_ids = [];
        foreach($records as $record){
            if(intval($record['send']) > 0){
                $id = DB::table('c1ft_stock_manager.sm_parts_sendHistory')->insertGetId([
                    'parts_name'  =>$record['name'],
                    'parts_ref' => 'none',
                    'pos_parts_id' => $record['id_product'],
                    'send_quantity'    => $record['send'],
                    'shop_id'   => $record['id_shop'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                array_push($send_ids,$id);
            }

            DB::table('c1ft_pos_prestashop.ps_stock_available')
                     ->where('id_product',$record['id_product'])
                     ->where('id_shop',$record['id_shop'])
                     ->increment('quantity',$record['send']);

        }

        $send_query = DB::table('c1ft_stock_manager.sm_parts_sendHistory')->whereIn('id',$send_ids)->get();
        $shop_name = Common::shopname($records[0]['id_shop']);
        $email = Common::shopemail($records[0]['id_shop']);

        Mail::to($email)
        ->cc(['warehouse@funtech.ie','hq@funtech.ie','it@funtech.ie'])
        ->send(new parts_sendEmail($send_query,$shop_name));

        return 'send';
    }

    public function checkPartsByStandardAndNotes(Request $request)
    {

        // $id_product = 4451;
        // $suggest_send = 10;

        //return self::get_parts_repair_notes($id_product,$shop_id,$suggest_send);
        $parts = DB::table('c1ft_pos_prestashop.ps_product_lang as a')
            ->select('a.name','b.quantity','a.id_product','c.standard','b.id_shop',DB::raw('c.standard - b.quantity as suggest_send'),'c.id')
            ->groupBy('a.name')
            ->join('c1ft_pos_prestashop.ps_stock_available as b','a.id_product','b.id_product')
            ->where('b.id_shop',$request->shop_id)
            ->whereIn('a.id_product',self::parts_ids())
            ->join('c1ft_stock_manager.sm_parts_standard as c','a.id_product','c.parts_id')
            ->where('c.shop_id',$request->shop_id)
            ->whereRaw('c.standard - b.quantity > 0')
            ->orderBy('suggest_send','desc')
            ->get();

        foreach($parts as $part){

            $part->repair_historyByStandard = self::get_parts_repair_notes($part->id_product,$part->id_shop,intval($part->suggest_send));
            if(count($part->repair_historyByStandard) == 1){
                $from = $part->repair_historyByStandard[0]->date_add;
                //$to   = $part->repair_historyByStandard[count($part->repair_historyByStandard) - 1]->date_add;
                $to   = date('Y-m-d H:i:s');
                $part->sendBetweenRepairs = self::get_parts_sendHistory_betweenOrder($from,$to,$part->id_shop,$part->id_product);
            }else if(count($part->repair_historyByStandard) > 1){
                //$to = $part->repair_historyByStandard[0]->date_add;
                $to   = date('Y-m-d H:i:s');
                $from   = $part->repair_historyByStandard[count($part->repair_historyByStandard) - 1]->date_add;
                $part->sendBetweenRepairs = self::get_parts_sendHistory_betweenOrder($from,$to,$part->id_shop,$part->id_product);

            }else{
                $part->sendBetweenRepairs = 0;
            }
            $part->totalSendFromBeginning = self::get_parts_sendHistory_betweenOrder('2019-08-29 00:00:00',date('Y-m-d H:i:s'),$part->id_shop,$part->id_product);

            //$to = $part->repair_historyByStandard[count($part->repair_historyByStandard)]->date_add;
        }
        return $parts;
        return $parts[0]->repair_historyByStandard[0]->date_add;
    }

    public function track_Parts_by_Standard(){
        $shops = DB::connection('mysql2')->table('ps_shop')
              ->select('id_shop','name')
              ->whereNotIn('id_shop',[1,35,42,41,32])
              ->get();

        return view('phone_check.trackPartsByStandard',compact('shops'));
    }

    public function editParts(){
        $shops = DB::connection('mysql2')->table('ps_shop')
              ->select('id_shop','name')
              ->whereNotIn('id_shop',[1,35,42,41,32])
              ->get();
        $reasons = DB::table('c1ft_stock_manager.sm_parts_edit_reason as a')
                    ->join('c1ft_pos_prestashop.ps_product_lang as b','a.parts_id','b.id_product')
                    // ->join('c1ft_stock_manager.sm_users as c','a.sm_user','')
                    ->join('c1ft_pos_prestashop.ps_shop as c','c.id_shop','a.shop_id')
                    ->groupBy('b.name')
                    ->select('a.*','b.name','c.name as shopname')
                    ->orderBy('a.id','desc')
                    ->simplePaginate(2);

        return view('phone_check.edit_parts',compact('shops','reasons'));
    }

    public function findParts(Request $request){
        $part = DB::table('c1ft_pos_prestashop.ps_product_lang as a')
                ->join('c1ft_pos_prestashop.ps_stock_available as b','a.id_product','b.id_product')
                ->join('c1ft_pos_prestashop.ps_shop as c','b.id_shop','c.id_shop')
                ->select('a.id_product as part_id','a.name as partsname','b.quantity as rockpos_qty','c.name as shopname','c.id_shop')
                ->where('a.id_shop',$request->shop_id)
                ->where('b.id_shop',$request->shop_id)
                ->where('a.id_product',$request->parts_id)
                ->whereIn('a.id_product',self::parts_ids())
                ->first();
        if($part){
            return response()->json(['findPart' => 1,'found_parts'=>$part]);
        }else{
            return response()->json(['findPart' => 0]);
        }
    }

    public function saveEditPartsReason(Request $request){

         DB::table('c1ft_stock_manager.sm_parts_edit_reason')->insertGetId([
            'parts_id'     => $request->parts_id,
            'shop_id'      => $request->shop_id,
            'issued_staff' => $request->staff,
            'reason'       => $request->reason,
            'updated_quantity'   => $request->actual_qty,
            'sm_user'      => $request->sm_user,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return 'done';
    }

    public function editPartsWithApproval(Request $request){
        $this->validate($request, [
            'shop_id'      => 'required',
            'parts_id'     => 'required',
            'request_qty'  => 'required',
            'reason_id'    => 'required',
            'user_id'      => 'required'
        ]);
        DB::table('c1ft_pos_prestashop.ps_stock_available')
            ->where('id_shop',$request->shop_id)
            ->where('id_product',$request->parts_id)
            ->update(['quantity' => $request->request_qty]);

        DB::table('c1ft_stock_manager.sm_parts_edit_reason')
            ->where('id',intval($request->reason_id))
            ->update(['approved_by'=>intval($request->user_id)]);


        return redirect()->route('edit_parts');

    }

    public function sendMissMatchPartEmail(Request $request){

        $query = DB::table('c1ft_stock_manager.sm_parts_import')
                ->where('shop_id',$request->shop_id)
                ->get();
        //$email = DB::table('c1ft_stock_manager.sm_shop_email')->where('shop_id',$request->shop_id)->value('shop_mail');
        return new parts_missmatchEmail($query,$request->shop_id);
        $email = 'jianqilu1126@gmail.com';
        Mail::to($email)
        //->cc(['warehouse@funtech.ie','it@funtech.ie'])
        ->send(new \App\Mail\parts_missmatchEmail($query,$request->shop_id));

        return redirect()->route('phone_check');

    }

}
