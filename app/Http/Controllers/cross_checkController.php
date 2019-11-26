<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Helper\Common;

class cross_checkController extends Controller
{
    public function index(){
        $shops = DB::table('c1ft_pos_prestashop.ps_shop')
                 ->whereNotIn('id_shop',[1,32,35,42])->get();
        $tasks = DB::table('c1ft_stock_manager.sm1_rep_tasks')->where('checked',0)->get();
        foreach($tasks as $task){
            $task->products = DB::table('c1ft_stock_manager.sm1_rep_task_products')
                            ->where('task_id',$task->id)->get();
            $task->shopname = Common::shopname($task->shop_id);
        }

        $lastTime_send_devices = DB::table('c1ft_stock_manager.sm_device_transferToBranch')
                ->select(DB::raw("distinct(date(transfer_date)) as last_send_date"))->orderBy('transfer_id','desc')->limit(1)->get()[0]->last_send_date;
        //return $lastTime_send_devices;
        //$lastTime_send_devices = DB::select(`select date(transfer_date) from c1ft_stock_manager.sm_device_transferToBranch`)->get();    
        return view('crosscheck.crosscheck',compact('shops','tasks','lastTime_send_devices'));
    }

    public function barcode_scan($task_id){
        //$scanned_products = DB::table('c1ft_stock_manager')
        //return $task_id;
        return view('crosscheck.scan',compact('task_id'));
    }
}
