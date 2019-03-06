<?php

namespace App\HQ;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'ps_customer';

    public function order(){
    	return $this->hasMany('App\HQ\Order','id_customer');
    }
}
