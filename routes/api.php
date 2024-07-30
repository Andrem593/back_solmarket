<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PedidoEncabezadoController;
use App\Http\Controllers\VentaEncabezadoController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function () {
    Route::get('productos', [ProductoController::class, 'index']);
    Route::get('producto/{id}', [ProductoController::class, 'show']);
    Route::post('producto', [ProductoController::class, 'store']);
    Route::put('producto/{id}', [ProductoController::class, 'update']);
    Route::delete('producto/{id}', [ProductoController::class, 'destroy']);



    Route::get('clientes', [ClienteController::class, 'index']);
    Route::get('cliente/{id}', [ClienteController::class, 'show']);
    Route::post('cliente', [ClienteController::class, 'store']);
    Route::put('cliente/{id}', [ClienteController::class, 'update']);
    Route::delete('cliente/{id}', [ClienteController::class, 'destroy']);


    Route::apiResource('pedidos-encabezados', PedidoEncabezadoController::class);
    Route::get('cambio-stock-producto/{idProduct}/{amount}/{type}', [PedidoEncabezadoController::class, 'changeProductStockValue']);
    Route::post('devolver-cantidad-productos', [PedidoEncabezadoController::class, 'returnQuantityToProductStock']);


    Route::apiResource('venta-encabezados', VentaEncabezadoController::class);

});
