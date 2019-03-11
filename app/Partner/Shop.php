<?php

namespace App\Partner;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ps_shop';

    protected $primaryKey = 'id_shop';

    public function product(){
    	return $this->hasMany('App\Partner\Stock','id_shop','id_shop');
    }
}
