<?php

namespace App\Model\Partner;

use Illuminate\Database\Eloquent\Model;

class BranchStock extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ps_stock_available';
    protected $primaryKey = 'id_stock_available';
}
