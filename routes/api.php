<?php

use App\Http\Controllers\ApiOdooController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Cotizador\CatalogoComponent;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('setUsers/v1',[ ApiOdooController::class, 'getUsers']);
Route::post('setClients/v1',[ ApiOdooController::class, 'getClients']);

Route::get('/catalogo/proveedor', [CatalogoComponent::class, 'catalogoImportacion']);
