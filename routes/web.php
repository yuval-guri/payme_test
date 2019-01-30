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

Route::get("/","Controller@index");
Route::get("/table","Controller@table");
Route::get("/process_form","Controller@process_form");
Route::get("/send_sale_details","Controller@sendSaleDetails");

//Route::get('/', function () {
//    return view('welcome');
//
//});
