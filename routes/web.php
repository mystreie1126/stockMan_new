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




//clear cache:
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
