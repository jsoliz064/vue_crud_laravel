<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PhotoController;


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

Route::resource('productos', ProductoController::class);
Route::get('buscarProducto/{id}', [ProductoController::class, 'buscarProducto']);
Route::get('buscarProducto2/{id}', [ProductoController::class, 'buscarProducto2']);
Route::put('editarProducto/{id}', [ProductoController::class, 'editarProducto']);
Route::delete('eliminarProducto/{id}', [ProductoController::class, 'eliminarProducto']);


Route::get('buscarPorNombre', [ProductoController::class, 'buscarPorNombre']);


Route::post('/save-photo', [PhotoController::class, 'store']);
