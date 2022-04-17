<?php

use Illuminate\Support\Facades\Route;

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

# works as a way to lump multiple resource routes into authentication processes
# otherwise we'd have to do all this manually
Route::group(['middleware' => 'auth'], function() {
    Route::resource('items', '\App\Http\Controllers\ItemController');
    Route::resource('categories', '\App\Http\Controllers\CategoryController');

    Route::get('/logout', '\App\Http\Controllers\LogoutController@perform')->name('logout.perform'); # testing logouts
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/front', [App\Http\Controllers\FrontController::class, 'index'])->name('frontAlpha');
Route::get('/front/{id}', [App\Http\Controllers\FrontController::class, 'indexId'])->name('front');
Route::get('/details/{id}', [App\Http\Controllers\DetailsController::class, 'index'])->name('details');

Route::post('/cart/itemProcess',[App\Http\Controllers\CartController::class, 'store'])->name('add_to_cart');
Route::put('/cart/updateItem',[App\Http\Controllers\CartController::class, 'update'])->name('update_cart');
Route::delete('/cart/deleteItem',[App\Http\Controllers\CartController::class, 'delete'])->name('remove_item');
Route::get('/cart/view/{sid}/{ipaddr}',[App\Http\Controllers\CartController::class, 'show'])->name('show_cart');

Route::post('/order/check',[App\Http\Controllers\OrderController::class, 'check'])->name('check_order');
Route::get('/thankyou/{id}',[App\Http\Controllers\ThankyouController::class, 'index'])->name('thankyou');
