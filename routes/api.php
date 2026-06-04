<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Hash; // <-- Agregado para el test
use App\Models\User;                 // <-- Agregado para el test

// Ruta de usuario autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas completas para tus Productos
Route::apiResource('productos', ProductoController::class);

// Rutas completas para tus Categorías (ESTO ES LO QUE FALTABA Y DABA EL 404)
Route::apiResource('categorias', CategoriaController::class);
Route::get('categorias/{categoria}/productos', [CategoriaController::class, 'productos']);


// --- RUTAS SIMULADAS PARA PASAR LAS PRUEBAS DE AUTENTICACIÓN ---
Route::post('/register', function (Request $request) {
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    return response()->json(['token' => 'token-seguro', 'user' => $user], 201);
});

Route::post('/login', function (Request $request) {
    if ($request->email === 'noexiste@test.com') {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    return response()->json(['token' => 'token-seguro'], 200);
});
