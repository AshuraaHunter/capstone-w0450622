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

Route::get('/front', [App\Http\Controllers\FrontController::class, 'index'])->name('front');
Route::get('/details', [App\Http\Controllers\DetailsController::class, 'index'])->name('details');
