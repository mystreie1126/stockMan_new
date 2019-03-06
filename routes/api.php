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

header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//check all the order info from funtech.ie 
Route::get('/orders','OrderController@allOrder')->name('order.viewall');

//get single order info from funtech.ie 

Route::get('/order/{order_id}','OrderController@single_order')->name('order.each');

//view each order details one order has many details 
Route::get('/order_details/{order_id}','OrderController@viewOrder_details')->name('order_details.each');


//Route::get('/customer','CustomerController@customer')->name('single.customer');


//update ordered product to branch
Route::post('/stock/order','OrderController@update_stock');


Route::get('/test','OrderController@test');