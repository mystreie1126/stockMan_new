<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use DB;

class StandardController extends Controller
{
    public function index()
    {
        $query = DB::table('c1ft_stock_manager.sm_standard_branch')
    }
}
