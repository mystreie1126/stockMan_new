<?php

namespace App\Model\Device;

use Illuminate\Database\Eloquent\Model;

class Device_transfer extends Model
{
    protected $connection ='mysql3';
    protected $table = 'sm_device_transferToBranch';
    protected $primaryKey = 'transfer_id';
    protected $fillable = ['send'];
    public $timestamps = false;

    public function record(){
        return $this->belongsTo('App\Model\Device\Devicepool','device_id','device_id');
    }

    public function shopname(){
        return $this->hasOne('App\Model\Partner\Shop','id_shop','shop_id');
    }
}
