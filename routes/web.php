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

Route::get('/', [CotizadorController::class, 'index'])->name('home');

Route::get('/catalogo', [CotizadorController::class, 'catalogo'])->name('catalogo');
Route::get('/catalogo/{product}', [CotizadorController::class, 'verProducto'])->name('show.product');
Route::get('/mis-cotizaciones', [CotizadorController::class, 'cotizaciones'])->name('cotizaciones');
Route::get('/cotizacion-actual', [CotizadorController::class, 'cotizacion'])->name('cotizacion');

Route::get('/actualizarCatalogo', [ActualizarCatalogoController::class, 'actualizarCatalogo'])->name('actualizarCatalogo');

//Route Hooks - Do not delete//
// Route::view('prices_techniques', 'livewire.prices_techniques.index')->middleware('auth');
// Route::view('size_material_technique', 'livewire.size_material_technique.index')->middleware('auth');
// Route::view('sizes', 'livewire.sizes.index')->middleware('auth');
// Route::view('material_technique', 'livewire.material-techniques.index')->middleware('auth');
// Route::view('materials', 'livewire.materials.index')->middleware('auth');
Route::middleware(['role:admin'])->group(function () {
    Route::view('techniques', 'livewire.techniques.index')->middleware('auth');
});
