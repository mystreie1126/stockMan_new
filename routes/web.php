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

Route::get('/hq_inventoryControl',function(){
		return view('HQ_inventoryControl');
})->name('hq_inventory')->middleware('auth');

Route::get('/test',function(){
	return view('testdd');
});

Route::get('/rep','repishmentController@index')->name('replishment')->middleware('auth');
Route::get('/check','repishmentController@check');



// Route::get('/', 'HomeController@index')->name('home');
