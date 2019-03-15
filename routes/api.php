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

/* order routes...*/
//check all the order info from funtech.ie
Route::get('/orders','OrderController@allOrder')->name('order.viewall');
//get single order info from funtech.ie
Route::get('/order/{order_id}','OrderController@single_order')->name('order.each');
//view each order details one order has many details
Route::get('/order_details/{order_id}','OrderController@viewOrder_details')->name('order_details.each');
//update ordered product to branch
Route::post('/stock/order','OrderController@update_stock');



/* product routes....*/

Route::get('/products','ProductController@index');

Route::get('/product/{ref}','ProductController@each_product');

Route::get('/ProductStockSell/{ref}','ProductController@productStockAndSell');


/* order routes....*/

Route::get('/recent_orders','OrderController@recentOrders');

Route::get('/searchOrder/{ref}','OrderController@searchOrderByRef');


/*sales chart routes ...*/

Route::post('/showsales','OrderController@allsales');

/* top sales qty */

Route::get('/topSalesQty','OrderController@topSalesQty');
