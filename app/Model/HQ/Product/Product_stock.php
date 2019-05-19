<?php

namespace App\HQ\Product;

use Illuminate\Database\Eloquent\Model;

class Product_stock extends Model
{
    protected $table = 'ps_stock_available';
    protected $primaryKey = 'id_product';
}
