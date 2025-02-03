<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;


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
Route::prefix('managament_catalogo')->group(function(){

    Route::get('/', ['as' => 'catalogo_index','uses' => 'WebController@catalogo_index']);
    Route::get('show_catalogo', ['as' => 'show_catalogo', 'uses' => 'WebController@show_catalogo']);

});
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])->name('catalogo.show');

Route::prefix('managament_product')->group(function () {
    Route::get('/', ['as' => 'product_index','uses' => 'WebController@product_index']);
    Route::post('upload_image', ['as' => 'upload_image', 'uses' => 'WebController@upload_image']);
    Route::post('store_product', ['as' => 'store_product','uses' => 'WebController@store_product']);
    Route::post('product_restore', ['as' => 'product_restore', 'uses' => 'WebController@product_restore']);
    Route::put('update_product', ['as' => 'update_product', 'uses' => 'WebController@update_product']);
    Route::get('product_register_view', ['as' => 'product_register_view', 'uses' => 'WebController@product_register_view']);
    Route::get('details_stock_in_branch', ['as' => 'details_stock_in_branch', 'uses' => 'WebController@details_stock_in_branch']);
    Route::post('get_products', ['as' => 'get_products', 'uses' => 'WebController@get_products']);
    Route::get('get_products_by_category_report', ['as' => 'get_products_by_category_report', 'uses' => 'WebController@get_products_by_category_report']);
    Route::post('get_products_by_category', ['as' => 'get_products_by_category', 'uses' => 'WebController@get_products_by_category']);
    Route::post('get_product', ['as' => 'get_product', 'uses' => 'WebController@get_product']);
    Route::post('get_product_all', ['as' => 'get_product_all', 'uses' => 'WebController@get_product_all']);
    Route::delete('product_delete', ['as' => 'product_delete', 'uses' => 'WebController@product_delete']);
    Route::get('show_product', ['as' => 'show_product', 'uses' => 'WebController@show_product']);
    Route::post('update_price_product', ['as' => 'update_price_product', 'uses' => 'WebController@update_price_product']);
    Route::post('get_products_by_filters', ['as' => 'get_products_by_filters', 'uses' => 'WebController@get_products_by_filters']);
    Route::post('update_price', ['as' => 'update_price', 'uses' => 'WebController@update_price']);
    Route::post('delete_price', ['as' => 'delete_price', 'uses' => 'WebController@delete_price']);
    Route::post('price_store', ['as' => 'price_store', 'uses' => 'WebController@price_store']);
    Route::post('get_product_with_prices', ['as' => 'get_product_with_prices', 'uses' => 'WebController@get_product_with_prices']);
    Route::post('get_product_prices', ['as' => 'get_product_prices', 'uses' => 'WebController@get_product_prices']);
  });

