<?php

namespace App\Partner;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ps_orders';
    protected $primaryKey = 'id_order';

    public function shop(){
      return $this->hasOne('App\Partner\Shop','id_shop','id_shop');
    }
}
