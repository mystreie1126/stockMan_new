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



/* new device stock in actions.....*/

Route::post('/get_device_details_by_id','DeviceController@device_details_by_id');

Route::post('/create_mobile_device','DeviceController@save_mobileDevice_inPool');

Route::get('/check_awaiting_update_devices','DeviceController@device_awaiting_update');

Route::get('/get_device_issues','DeviceController@device_issues');

Route::post('/save_device_issues','DeviceController@save_device_issues');


/* partner order prices */

Route::post('/get_partner_order_by_date','PriceController@partner_all_delivery');


/* tracking stocks */

Route::post('/track_stock_by_brand','TrackStockController@trackStockByBrand');

Route::post('/trackStockBy_singleProduct','TrackStockController@trackStockBy_singleProduct');

Route::post('/trackStockBy_category','TrackStockController@trackStockBy_category');

Route::post('/trackStockBy_topSelling','TrackStockController@trackStockBy_topSelling');

Route::post('/trackStockBy_stockCheck','TrackStockController@trackStockBy_stockCheck');

/*barcode..*/

Route::get('/parts_with_barcodes','BarcodeController@barcode');

Route::get('/check_parts_barcodes','BarcodeController@check_parts_barcode');

Route::get('/get_parts_brand','BarcodeController@parts_brand');

Route::post('/get_parts_model','BarcodeController@parts_model');

Route::post('/setbarcode_topartsref','BarcodeController@set_barcode_parts_ref');

/*helper*/
Route::get('/soldAll','helperController@soldAll');
Route::get('/test_ref','helperController@test_ref');

Route::get('/standard','helperController@standard_model');

Route::get('/pp','helperController@check_earphone');
Route::get('/pa','helperController@getThis');
Route::get('/pb','helperController@getThat');

Route::get('/stockCheck','helperController@stockTake_check');

Route::get('/allshopSales','kerianController@allShopSalesQty_by_ref');

Route::get('/soft_delete','helperController@delete_standard');

/* kerian controller*/
Route::get('/athlone_standard_list','kerianController@athlone_standard_list');

Route::get('/allShopSalesQty_by_ref','kerianController@allShopSalesQty_by_ref');
