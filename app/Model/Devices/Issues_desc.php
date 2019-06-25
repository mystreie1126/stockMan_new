<?php

namespace App\Model\Devices;

use Illuminate\Database\Eloquent\Model;

class Issues_desc extends Model
{
    protected $connection = 'mysql5';
    protected $table      = 'dm_issue_description';
}
