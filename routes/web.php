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
Auth::routes();
Route::get('/', function () {
    return view('welcome');
});

Route::get('home', 'WebController@index_home')->name('home');
Route::get('/', 'WebController@index_home')->name('home_index');

Route::prefix('management_menu')->group(function () {
    Route::get('/', ['as' => 'product_index',  'middleware' => 'rule_permission:1', 'uses' => 'WebController@product_index']);
    Route::post('product_store', ['as' => 'product_store',  'middleware' => 'rule_permission:1', 'uses' => 'WebController@product_store']);
    Route::post('product_update', ['as' => 'product_update',  'middleware' => 'rule_permission:1', 'uses' => 'WebController@product_update']);
    Route::post('get_products', ['as' => 'get_products',  'middleware' => 'rule_permission:1|2|3', 'uses' => 'WebController@get_products']);
    Route::post('get_product', ['as' => 'get_product',  'middleware' => 'rule_permission:1|2|3', 'uses' => 'WebController@get_product']);
    Route::delete('product_delete', ['as' => 'product_delete',  'middleware' => 'rule_permission:1', 'uses' => 'WebController@product_delete']);
    Route::post('change_product', ['as' => 'change_product',  'middleware' => 'rule_permission:1', 'uses' => 'WebController@change_product']);
    Route::post('get_products_delete', ['as' => 'get_products_delete',  'middleware' => 'rule_permission:1', 'uses' => 'WebController@get_products_delete']);
    Route::post('product_restore', ['as' => 'product_restore',  'middleware' => 'rule_permission:1', 'uses' => 'WebController@product_restore']);
    Route::get('product_show', ['as' => 'product_show',  'middleware' => 'rule_permission:1', 'uses' => 'WebController@product_show']);
});
