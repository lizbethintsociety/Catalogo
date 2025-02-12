<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\SuitController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RateController;




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

Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])->name('catalogo.show');

Route::get('/suit', [SuitController::class, 'index'])->name('suit.index');
Route::get('/suit/{id}', [SuitController::class, 'show'])->name('suit.show');


Route::get('/rate', [RateController::class, 'index'])->name('rate.index');
Route::get('/rate/{id}', [RateController::class, 'show'])->name('rate.show');


Route::get('/service', [ServiceController::class, 'index'])->name('service.index');
Route::get('/service/{id}', [ServiceController::class, 'show'])->name('service.show');