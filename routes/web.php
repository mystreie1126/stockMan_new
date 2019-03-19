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


Route::get('/',function(){
	return view('sales');
})->name('salespage');

Route::get('/product',function(){
	return view('product');
})->name('stockpage');

Route::get('/order',function(){
	return view('order');
})->name('orderpage');

Route::get('/replishment',function(){
	return view('replishment');
})->name('replishment');



Route::get('/test',function(){
	return view('testdd');
});
