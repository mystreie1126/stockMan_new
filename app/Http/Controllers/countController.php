<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class countController extends Controller
{
  public function index(){
    $shops = DB::connection('mysql2')->table('ps_shop')
          ->select('id_shop','name')
          ->whereNotIn('id_shop',[1,35,42])
          ->get();
    return view('countStock',compact('shops'));
  }
}
