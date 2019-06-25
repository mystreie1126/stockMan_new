<?php

namespace App\Model\Devices;

use Illuminate\Database\Eloquent\Model;

class Device_pool extends Model
{
    protected $connection = 'mysql5';
    protected $table = 'dm_device_pool';

    public function type(){
        return $this->hasOne('App\Model\Devices\Device_category','id','category_id');
    }
}
