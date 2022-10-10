<?php

use App\Http\Controllers\ActualizarCatalogoController;
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

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [CotizadorController::class, 'catalogo'])->name('catalogo');
    Route::get('/catalogo/{product}', [CotizadorController::class, 'verProducto'])->name('show.product');
    Route::get('/mis-cotizaciones', [CotizadorController::class, 'cotizaciones'])->name('cotizaciones');
    Route::get('/cotizacion-actual', [CotizadorController::class, 'cotizacion'])->name('cotizacion');
    Route::get('/ver-cotizacion/{quote}', [CotizadorController::class, 'verCotizacion'])->name('verCotizacion');
    Route::get('/finalizar-cotizacion', [CotizadorController::class, 'finalizar'])->name('finalizar');
    Route::get('/previsualizar-cotizacion', [CotizadorController::class, 'previsualizar'])->name('previsualizar');
    Route::get('/actualizarCatalogo', [ActualizarCatalogoController::class, 'actualizarCatalogo'])->name('actualizarCatalogo');
    Route::get('/ver-cotizacion-pdf/{quote}', [CotizadorController::class, 'previsualizar'])->name('previsualizar.cotizacion');
    Route::get('/all-cotizaciones', [CotizadorController::class, 'all'])->name('all.cotizacion');

    //Route Hooks - Do not delete//
    Route::middleware(['role:admin'])->group(function () {
        Route::view('techniques', 'livewire.techniques.index')->middleware('auth');
        Route::view('importTechniques', 'livewire.import-techniques.index')->middleware('auth');
        Route::view('materials', 'livewire.materials.index')->middleware('auth');
        Route::view('sizes', 'livewire.sizes.index')->middleware('auth');
        Route::view('prices', 'livewire.prices.index')->middleware('auth');
        Route::view('users', 'livewire.users.index')->middleware('auth');
        Route::view('clients', 'livewire.clients.index')->middleware('auth');
    });
});
