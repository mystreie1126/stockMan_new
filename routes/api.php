<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//check all the order from funtech.ie and allow client search for order info
Route::get('/checkOrder','OrderController@checkOrder')->name('order.viewall');


//view each order details one order has many details 
Route::get('/order_details/{id}','OrderController@viewOrder_details')->name('order.each');

