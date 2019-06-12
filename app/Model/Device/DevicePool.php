<?php

namespace App\Model\Device;

use Illuminate\Database\Eloquent\Model;

class DevicePool extends Model
{
    protected $connection = 'mysql3';
    protected $table = 'sm_device_pool';
    protected $primaryKey = 'device_id';

    public function transfer(){
        return $this->hasMany('App\Model\Device\Device_transfer','device_id');
    }
}
