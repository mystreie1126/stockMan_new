<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\DB;

Auth::routes();


//Route::get('/dashboard','HomeController@index')->name('salespage');

/* chart */
Route::get('/',function(){
	return view('sales.allSales');
})->name('salespage')->middleware('auth');

Route::get('/product_sales',function(){
	return view('sales.product_sales');
})->name('product_sales');

Route::get('/order',function(){
		return view('order');
})->name('orderpage')->middleware('auth');

Route::get('/hq_stockTake',function(){
		return view('hq_stockTake');
})->name('HQ_stockTake')->middleware('auth');

Route::get('/test',function(){
	return view('testdd');
});

//stockTake
Route::get('/my_stockTake',function(){
	return view('my_stocktake');
})->name('mystocktake')->middleware('auth');

Route::get('/stockTake_analysis',function(){
	return view('stockTake_analysis');
})->name('stockTake_analysis')->middleware('auth');

//stockOut

Route::get('/rep','replishmentController@rep_page')->name('replishment')->middleware('auth');

Route::get('/rep_update','replishmentController@rep_update_page')->name('rep_update')->middleware('auth');

//device page

Route::get('/new_device','DeviceController@new_device_page')->name('newDeviceStockIn');

Route::get('/deviceTransfer','DeviceController@transfer_device_page')->name('transferDevices');

Route::get('/send_device','DeviceController@ready_to_send')->name('sendDevice');

/* new device action*/

Route::get('/device_stockIn','DeviceController@device_stockIn')->middleware('auth');

// Route::get('/checking_device','DeviceController@checking_device')->name('checking_device')->middleware('auth');

// Route::get('/create_new_device',function(){
// 	return view('devices.device_pool');
// });

Route::get('/create_new_device','DeviceController@device_stockIn_pool');

Route::get('/device_awaiting_check',function(){
	return view('devices.device_awaiting_check');
});

Route::get('/test_device',function(){
	return view('devices.device_awaiting_update');
});
//'DeviceController@test_device_by_id'
Route::get('/test_device/{device_id}',function($device_id){
	return view('devices.devices_technicals_test',compact('device_id'));
})->name('test_device_by_id');


/* barcode */
Route::get('/barcode_page','BarcodeController@barcode')->name('parts_barcode');



//StockIn
Route::get('/accumulate_stock',function(){
	return view('stock_in.stock_accumulate');
})->name('accumulate_stock');


//Order

Route::get('/partner_orders','OrderController@pos_partner_order')->name('partner_order');

Route::post('/order_to_pos','OrderController@order_to_pos')->name('order_to_pos');


//standard

Route::get('/standards','StandardController@index')->name('standards_page');

//Price

Route::get('/partner_delivery_prices','PriceController@partner_order_with_price_page')->name('partner_delivery_prices');


//Tracking Stock
Route::get('/track_stock_record',function(){
	return view('tracking.stockTracking');
})->name('track_stock_record');

Route::get('/topselling',function(){
	return view('tracking.topselling');
})->name('topselling');


//phone and parts check


Route::post('/clearAll','Phone_checkController@clearAll')->name('clear_allImported');
Route::post('/clearAll_parts','Phone_checkController@clearAll_parts')->name('clear_allImported_parts');

Route::post('/checkedAndDelete','Phone_checkController@checkedAndDelete')->name('checkedAndDelete');
Route::post('/checkedAndDelete_parts','Phone_checkController@checkedAndDelete_parts')->name('checkedAndDelete_parts');


Route::get('/phone_check','Phone_checkController@index')->name('phone_check');

Route::post('/excel_import','Phone_checkController@import')->name('import_pop_list');

Route::get('/editParts','PartsController@editParts')->name('edit_parts');

Route::post('/updatedPartsByApproval','PartsController@editPartsWithApproval')->name('updatedPartsByApproval');

// Route::post('/sendMissMatchPartEmail','PartsController@sendMissMatchPartEmail')->name('sendMissMatchPartEmail');


/*test routes*/

Route::get('/demon_stockCheck','helperController@demon_stockCheck')->name('demon_stockCheck');
//invoice

Route::get('/invoice','invoiceController@invoice_page')->name('invoice_page');
// Clear application cache:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});

// Clear view cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return 'View cache cleared';
});

//Clear route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return 'Routes cache cleared';
});

//Clear config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return 'Config cache cleared';
});

// Clear compiled classes and services application cache
Route::get('/clear-compiled', function() {
    $exitCode = Artisan::call('clear-compiled');
    return 'Compiled classes cache cleared';
});


/* cross check */

Route::get('/crosscheck','cross_checkController@index')->name('crosscheck');
Route::get('/crosscheck/scan-product/{task_id}','cross_checkController@barcode_scan')->name('scanproducts');


/*get check parts */

Route::get('/trackPartsByStandard','PartsController@track_Parts_by_Standard')->name('track_Parts_by_Standard');
Route::get('/parts_uploaded',function(){
	return view('ss_upload.parts');
})->name('parts_uploaded');

Route::get('/pop_stockTake',function(){
	$shops = \DB::table('c1ft_pos_prestashop.ps_shop')
			->whereNotIn('id_shop',[1,32,35,41,42])
			->get();
	return view('pop_stockTake',compact('shops'));
})->name('pop_stockTake');

Route::get('/parts_check_upload_history','PartsController@sm1_parts_upload_history')->name('sm1_parts_upload_history');


/* tracking */

Route::get('/track_product_info',function(){
	return view('tracking.track_product_info');
})->name('track_product_info_chart');


Route::get('/product_order_transaction',function(){
	return view('tracking.productOrderTransaction');
})->name('product_order_transaction');

Route::get('/detail_product_all',function(){
	$shops = \DB::table('c1ft_pos_prestashop.ps_shop')
			->whereNotIn('id_shop',[1,32,35,41,42])
			->get();
	return view('detail_product_all',compact('shops'));
})->name('detail_product_all');

Route::get('/detail_product_warehouse_info',function(){
	$catas = \DB::table('c1ft_stock_manager.sm_wholesale_categories')->get();
	return view('detail_product_warehouse_info',compact('catas'));
})->name('detail_product_warehouse_info');