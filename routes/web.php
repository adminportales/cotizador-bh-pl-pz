<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CotizadorController;
use App\Http\Livewire\Companies;
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

Route::get('/loginEmail', [LoginController::class, 'loginWithLink'])->name('loginWithLink');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [CotizadorController::class, 'catalogo'])->name('catalogo');
    Route::get('/catalogo/{product}', [CotizadorController::class, 'verProducto'])->name('show.product');
    Route::get('/mis-cotizaciones', [CotizadorController::class, 'cotizaciones'])->name('cotizaciones');
    Route::get('/cotizacion-actual', [CotizadorController::class, 'cotizacion'])->name('cotizacion');
    Route::get('/ver-cotizacion/{quote}', [CotizadorController::class, 'verCotizacion'])->name('verCotizacion');
    Route::get('/finalizar-cotizacion', [CotizadorController::class, 'finalizar'])->name('finalizar');
    Route::get('/previsualizar-cotizacion', [CotizadorController::class, 'previsualizar'])->name('previsualizar');
    Route::get('/ver-cotizacion-pdf/{quote}', [CotizadorController::class, 'previsualizar'])->name('previsualizar.cotizacion');
    Route::get('/ver-presentacion-pdf/{presentacion}', [CotizadorController::class, 'previsualizarPPT'])->name('previsualizar_ppt.cotizacion');
    Route::get('/changeCompany/{company}', [CotizadorController::class, 'changeCompany'])->name('changeCompany.cotizador');
    Route::get('/changeCurrency/{currency}', [CotizadorController::class, 'changeCurrencyType'])->name('changeCurrency.cotizador');
    Route::get('/addProduct/create', [CotizadorController::class, 'addProductCreate'])->name('addProduct.cotizador');
    Route::post('/addProduct/store', [CotizadorController::class, 'addProductStore'])->name('storeproduct.cotizador');
    Route::get('/list-products', [CotizadorController::class, 'listProducts'])->name('listProducts.cotizador');
    Route::get('/exportUsuarios', [CotizadorController::class, 'exportUsuarios'])->name('exportUsuarios.cotizador');
    Route::get('/exportProducts', [CotizadorController::class, 'exportProducts'])->name('exportProducts.cotizador');
    Route::post('/exportProducts/download', [CotizadorController::class, 'exportProductsDownload'])->name('exportProducts.download.cotizador');

    //Route Hooks - Do not delete//
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::get('/', [CotizadorController::class, 'dashboard'])->name('dashboard');
        Route::get('/all-cotizaciones', [CotizadorController::class, 'all'])->name('all.cotizacion');
        Route::view('importTechniques', 'admin.personalizacion.import-techniques.index')->middleware('auth');
        Route::view('materials', 'admin.personalizacion.material_y_tecnica.materials.index')->middleware('auth');
        Route::view('sizes', 'admin.personalizacion.sizes.sizes.index')->middleware('auth');
        Route::view('prices', 'admin.personalizacion.prices.index')->middleware('auth');
        Route::view('users', 'admin.users.index')->middleware('auth');
        Route::view('clients', 'admin.clients.index')->middleware('auth');
        Route::view('companies', 'admin.companies.index')->middleware('auth');
        Route::view('tradenames', 'admin.tradenames.index')->middleware('auth');
        Route::view('tradenames', 'admin.tradenames.index')->middleware('auth');

    });
});

Route::get('/sendQuotesOdoo', [CotizadorController::class, 'enviarCotizacionesAOdoo']);
