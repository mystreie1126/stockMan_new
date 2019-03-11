<?php

namespace App\Partner;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ps_product';

    protected $primaryKey = 'id_product';

    
}
