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