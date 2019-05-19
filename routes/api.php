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



//inventory

Route::get('/hq_inventoryList','inventoryController@HQ_invetory_list');

Route::post('/saveToInventoryHistory','inventoryController@saveTo_hqInventoryHistory');

/*get replishment sales list*/

Route::post('/getlistbysale','replishmentController@salesList');

Route::post('/save_replist','replishmentController@save_repList');

Route::post('/getSavedList','replishmentController@getSavedList');

/*helper*/

Route::get('/test_ref','helperController@test_ref');
