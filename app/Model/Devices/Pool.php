<?php

namespace App\Model\Devices;

use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    protected $connection = 'mysql5';
    protected $table      = 'dm_device_pool';
    public $timestamps    = false;
}
