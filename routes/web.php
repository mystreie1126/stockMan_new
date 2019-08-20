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

Auth::routes();


//Route::get('/dashboard','HomeController@index')->name('salespage');

Route::get('/',function(){
	return view('sales');
})->name('salespage')->middleware('auth');

Route::get('/product',function(){
	return view('sales');
})->name('stockpage')->middleware('auth');

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




//Price

Route::get('/partner_delivery_prices','PriceController@partner_order_with_price_page')->name('partner_delivery_prices');


//Tracking Stock
Route::get('/track_stock_record',function(){
	return view('tracking.stockTracking');
})->name('track_stock_record');

Route::get('/topselling',function(){
	return view('tracking.topselling');
})->name('topselling');






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
