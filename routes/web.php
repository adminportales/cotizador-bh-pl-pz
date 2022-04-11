<?php

use App\Http\Controllers\CotizadorController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/catalogo', [CotizadorController::class, 'catalogo'])->name('catalogo');
Route::get('/catalogo/{product}', [CotizadorController::class, 'verProducto'])->name('show.product');
Route::get('/mis-cotizaciones', [CotizadorController::class, 'cotizaciones'])->name('cotizaciones');
Route::get('/cotizacion-actual', [CotizadorController::class, 'cotizacion'])->name('cotizacion');
Route::get('/actualizarCatalogo', [App\Http\Controllers\ActualizarCatalogoController::class, 'actualizarCatalogo'])->name('actualizarCatalogo');
