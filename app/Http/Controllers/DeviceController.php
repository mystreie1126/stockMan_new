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

use App\Model\Devices\Pool;
use App\Model\Devices\Issues_desc;
use App\Model\Devices\Device_pool_desc_ps as Jiance;

class DeviceController extends Controller
{
    const pre_own_category_id   = 394;
    const brand_new_category_id = 1451;

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
              ->whereNotIn('id_shop',[1,35,42,41,32])
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
                   ->where('shop_id','!=',700)
                   ->groupBy('shop_id')
                   ->pluck('shop_id')->toArray();
        $lists = [];

        foreach($shopIDs as $shop_id){
            $devices = Device_transfer::with('shopname','record')->where('shop_id',$shop_id)->where('send',0)->get();
            if($shop_id == 0){
                $devices->name = 'wholesellers';
            }else{
                $devices->name = Shop::find($shop_id)->name;
            }

            $lists[] = $devices;
        }

        return view('device.ready_to_send',compact('lists'));
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


    public function device_stockIn(){

        return view('device.device_stockIn');
    }


/*new devices actions start here*/
    private function get_category_by_parent($parent){
        $query = DB::table('ps_category as a')
                 ->select('a.id_category','b.name')
                 ->join('ps_category_lang as b','a.id_category','b.id_category')
                 ->where('a.id_parent',$parent)
                 ->where('a.active',1)
                 ->groupBy('a.id_category')
                 ->get();
        return $query;
    }

    public function device_stockIn_pool(){

        $preown_models   = self::get_category_by_parent(self::pre_own_category_id);
        $brandnew_models = self::get_category_by_parent(self::brand_new_category_id);
        return view('devices.device_pool',compact('preown_models','brandnew_models'));
    }

    public function save_mobileDevice_inPool(Request $request){

        $device = new Pool;
        $device->supply_order_id   = $request->supplier_order_id;
        $device->device_type       = $request->device_type;
        $device->pre_own           = $request->preOwn;
        $device->brand_new         = $request->brandNew;
        $device->user_created      = $request->staff_id;
        $device->created_at        = date('Y-m-d H:i:s');

        if($device->save()){
            $jiance = new Jiance;
            $jiance->device_id            = $device->id;
            $jiance->brand_ps_category_id = $request->model_category_id;
            $jiance->brand_name           = $request->brand_name;
            $jiance->model_name           = $request->model_name;
            $jiance->save();

            return 'saved';
        }
    }

    public function device_details_by_id(Request $request){

    }

    public function device_awaiting_update(){

        $awaiting_devices = DB::table('c1ft_device_manager.dm_jiance_device_desc_ps as a')
                ->select('a.brand_name','a.model_name','a.device_id',
                         'b.pre_own','b.brand_new','b.serial_number','b.tested','b.user_created','b.created_at')
                ->join('c1ft_device_manager.dm_device_pool as b','a.device_id','b.id')
                ->get();
        return $awaiting_devices;
        return view('devices.device_awaiting_check',compact('awaiting_devices'));
    }


    public function device_issues(){
        $issues = DB::table('c1ft_device_manager.dm_issue_description')->get();

        return response()->json(['issues'=>$issues]);
    }

    public function save_device_issues(Request $request){

    }



}
