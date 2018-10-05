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

Route::get('/', function () {
    return view('welcome');
});

//suplier
Route::get('/jsondata','SuplierController@json');
Route::resource('/indexsuplier','SuplierController');
Route::post('store','SuplierController@store')->name('tambah');
Route::get('ajaxdata/removedatasup', 'SuplierController@removedata')->name('ajaxdata.removedatasup');
Route::post('suplier/edit/{id}','SuplierController@update');
Route::get('suplier/getedit/{id}','SuplierController@edit');
//endsuplier

//barang 
Route::resource('barang','BarangController');
Route::get('jsonbarang','BarangController@json');
Route::post('storebar','BarangController@store')->name('storebar');
Route::get('ajaxdata/removedatabar', 'BarangController@removedata')->name('ajaxdata.removedatabar');
Route::post('barang/edit/{id}','BarangController@update');
Route::get('barang/getedit/{id}','BarangController@edit');

//jual
Route::resource('jual','PenjualanController');
Route::get('/jsonjual', 'PenjualanController@json');
Route::post('storejual', 'PenjualanController@store');
Route::post('jual/edit/{id}', 'PenjualanController@update');
Route::get('jual/getedit/{id}','PenjualanController@edit');
Route::get('ajaxdata/removedatajual','PenjualanController@removedata')->name('ajaxdata.removedatajual');
