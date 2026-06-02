<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;

// Aquí debe estar tu ruta para obtener al usuario autenticado (si usas Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 1. Ruta de Productos (¡Esta es la que faltaba!)
Route::apiResource('productos', ProductoController::class);

// 2. Nuevas rutas de Categorías
Route::apiResource('categorias', CategoriaController::class);
Route::get('categorias/{categoria}/productos', [CategoriaController::class, 'productos']);
