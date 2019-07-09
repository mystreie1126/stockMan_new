<?php

namespace App\Model\Devices;

use Illuminate\Database\Eloquent\Model;

class Device_pool_desc_ps extends Model
{
    protected $connection = 'mysql5';
    protected $table      = 'dm_jiance_device_desc_ps';
    public $timestamps    = false;
}
