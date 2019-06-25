<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use App\Model\Device\Devicepool;
use App\Model\Device\Device_transfer;
use App\Model\Partner\Shop;
use App\Mail\DeviceSendEmail;
use Mail;
use DB;

use App\Model\Devices\Device_pool as Device;
use App\Model\Devices\Issues_desc;

class DeviceController extends Controller
{
    public function new_device_page(){
        return view('device.newDevice');
    }

    public function new_device_saveToPool(Request $request){
        $devicepool = new Devicepool;
        $devicepool->order_number  = $request->order_id;
        $devicepool->IMEI          = $request->imei;
        $devicepool->brand         = $request->brand;
        $devicepool->model         = $request->model;
        $devicepool->color         = $request->color;
        $devicepool->condition     = $request->condition;
        $devicepool->storage       = $request->storage;
        $devicepool->by_user       = $request->user;
        $devicepool->created_at    = date('Y-m-d h:i:s');
        $devicepool->save();

        return 'saved';
    }

    public function transfer_device_page(){
        $devices = Devicepool::all();
        $shops = DB::connection('mysql2')->table('ps_shop')
              ->select('id_shop','name')
              ->whereNotIn('id_shop',[1,35,42])
              ->get();

        return view('device.deviceTransfer',compact('devices','shops'));
    }

    public function available_device_for_transfer(){
        $id = DB::table('c1ft_stock_manager.sm_device_transferToBranch')
              ->groupBy('device_id')
              ->pluck('device_id')->toArray();

        $devices = Devicepool::whereNotIn('device_id',$id)->get();

        return $devices;
    }

    public function transfer_device(Request $request){
        $transfer = new Device_transfer;
        $transfer->device_id     = $request->device_id;
        $transfer->staff_id      = $request->user_id;
        $transfer->shop_id       = $request->shop_id;
        $transfer->notes         = $request->notes;
        $transfer->send          = 0;
        $transfer->transfer_date = date('Y-m-d h:i:s');
        $transfer->save();

        return 'saved transfer';
    }

    public function ready_to_send(){
        $shopIDs = Device_transfer::where('send','0')
                   ->groupBy('shop_id')
                   ->pluck('shop_id')->toArray();
        $lists = [];

        //return Devicepool::find(2)->transfer;
        //return Device_transfer::where('shop_id',26)->shopname();


        foreach($shopIDs as $shop_id){
            $devices = Device_transfer::with('shopname','record')->where('shop_id',$shop_id)->where('send',0)->get();
            $devices->name = Shop::find($shop_id)->name;
            $lists[] = $devices;
        }

         //return $lists;
        return view('device.ready_to_send',compact('lists'));
         //return Devicepool::with('transfer')->find(1);
    }

    public function send_device(Request $request){

        $ids = $request->transfer_id;

        $query = Device_transfer::with('record')->where('shop_id',intval($request->shop_id))->where('send',0)->get();
        $shopname = Shop::find(intval($request->shop_id))->name;


        $email = DB::table('c1ft_stock_manager.sm_shop_email')->where('shop_id',intval($request->shop_id))->value('shop_mail');
        //return new DeviceSendEmail($query,$shopname);

        Mail::to($email)->cc('warehouse@funtech.ie')->send(new DeviceSendEmail($query,$shopname));

        foreach($ids as $id){
            Device_transfer::find(intval($id))->update(['send'=>1]);
        }


        return redirect()->route('sendDevice');
    }


    public function checking_device(){

        $devices = Device::with('type')->where('new_device',1)->get();
        $issues_desc = Issues_desc::all();

         

        return view('device.checking_device',compact('devices','issues_desc'));
    }







}
