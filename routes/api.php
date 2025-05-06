<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CarteraElectronicaController;
use App\Http\Controllers\Api\V1\TransaccionController;
use App\Http\Controllers\Api\V1\MetodoPagoController;

use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')->group(function () {
    // Rutas para el controlador EmpleadosController
    Route::apiResource('empleados', 'App\Http\Controllers\Api\V1\EmpleadosController');
    Route::post('empleados/{id}', 'App\Http\Controllers\Api\V1\EmpleadosController@update');
    Route::delete('empleados/{id}', 'App\Http\Controllers\Api\V1\EmpleadosController@destroy');

    // Rutas para el controlador DetallesEmpleadoController
    Route::apiResource('detalles_empleados', 'App\Http\Controllers\Api\V1\DetallesEmpleadoController');
    Route::post('detalles_empleados/{id}', 'App\Http\Controllers\Api\V1\DetallesEmpleadoController@update');
    Route::delete('detalles_empleados/{id}', 'App\Http\Controllers\Api\V1\DetallesEmpleadoController@destroy');

    // Rutas para el controlador ClientesController
    Route::apiResource('clientes', 'App\Http\Controllers\Api\V1\ClientesController');
    Route::post('clientes/{id}', 'App\Http\Controllers\Api\V1\ClientesController@update');
    Route::delete('clientes/{id}', 'App\Http\Controllers\Api\V1\ClientesController@destroy');

    // Rutas para el controlador PedidosController
    Route::apiResource('pedidos', 'App\Http\Controllers\Api\V1\PedidosController');
    Route::post('pedidos/{id}', 'App\Http\Controllers\Api\V1\PedidosController@update');
    Route::delete('pedidos/{id}', 'App\Http\Controllers\Api\V1\PedidosController@destroy');

    // Rutas para el controlador ProductosController
    Route::apiResource('productos', 'App\Http\Controllers\Api\V1\ProductosController');
    Route::post('productos/{id}', 'App\Http\Controllers\Api\V1\ProductosController@update');
    Route::delete('productos/{id}', 'App\Http\Controllers\Api\V1\ProductosController@destroy');
    
    // Rutas para el controlador ProveedorController
    Route::apiResource('proveedores', 'App\Http\Controllers\Api\V1\ProveedorController');
    Route::post('proveedores/{id}', 'App\Http\Controllers\Api\V1\ProveedorController@update');
    Route::delete('proveedores/{id}', 'App\Http\Controllers\Api\V1\ProveedorController@destroy');

    // Rutas para el controlador UserController
    Route::apiResource('users', 'App\Http\Controllers\Api\V1\UserController');
    Route::post('users/{id}', 'App\Http\Controllers\Api\V1\UserController@update');
    Route::delete('users/{id}', 'App\Http\Controllers\Api\V1\UserController@destroy');

    // Rutas para autenticación
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        // Otras rutas protegidas aquí
    });

    Route::apiResource('carteras-electronicas', CarteraElectronicaController::class);
    Route::apiResource('transacciones', TransaccionController::class);
    Route::apiResource('metodos-pago', MetodoPagoController::class);
});
