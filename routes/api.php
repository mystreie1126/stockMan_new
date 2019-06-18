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



//inventory/stockTake

Route::get('/hq_inventoryList','inventoryController@HQ_invetory_list');

Route::post('/saveToInventoryHistory','inventoryController@saveTo_stockTakeHistory');


Route::post('/myStockTake_records','inventoryController@myStockTake_records');
Route::get('/allStockTake_records','inventoryController@allStockTake_records');
Route::get('/stockTake_final_results','inventoryController@stockTake_final_results');


//StockIn

Route::get('/available_for_stockIn','StockInController@available_stock');
Route::post('/save_update_stock','StockInController@save_and_update');


/*get replishment sales list*/

Route::post('/getlistbysale','replishmentController@salesList');

Route::post('/save_replist','replishmentController@save_repList');

Route::post('/getSavedList','replishmentController@getSavedList');

/* upload replishment result by sale */

Route::post('/update_to_branch','replishmentController@update_to_branch')->name('update_to_branch');

Route::post('/delete_before_update_to_branch','replishmentController@delete_before_update_to_branch')->name('delete_before_update_to_branch');

/* custom repishment */

Route::post('/custom_get_rep_data','replishmentController@custom_get_rep_data');
Route::post('/custom_rep_data_save','replishmentController@custom_rep_data_save');


/* standard replishment*/

Route::post('/standard_replishment_list','replishmentController@standard_replishment_list');

Route::post('/save_standard_replist','replishmentController@save_standard_replist');


/* device management*/

//new device stock in
Route::post('/new_device_stockIn','DeviceController@new_device_saveToPool');

//check avaialble deivce
Route::get('/get_available_device_for_transfer','DeviceController@available_device_for_transfer');

//transfer devices
Route::post('/transfer_device','DeviceController@transfer_device');

Route::post('/send_device','DeviceController@send_device')->name('sendDevice_to_branch');

/*helper*/
Route::get('/soldAll','helperController@soldAll');
Route::get('/test_ref','helperController@test_ref');

Route::get('/pp','helperController@test_stockTake_refs');
Route::get('/pa','helperController@getThis');

Route::get('/stockCheck','helperController@stockTake_check');

Route::get('/allshopSales','kerianController@allShopSalesQty_by_ref');

Route::get('/soft_delete','helperController@solfdelete');

/* kerian controller*/
Route::get('/athlone_standard_list','kerianController@athlone_standard_list');

Route::get('/allShopSalesQty_by_ref','kerianController@allShopSalesQty_by_ref');

Route::get('/pas','kerianController@product_without_imageSold');
