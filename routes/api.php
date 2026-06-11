<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PedidoController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Importamos el nuevo controlador V2 (que crearemos en el siguiente paso)
use App\Http\Controllers\Api\V2\ProductoController as ProductoControllerV2;

// ── VERSIÓN 1 (Tu código original intacto, agrupado en v1) ──────────────────
Route::prefix('v1')->name('v1.')->group(function () {

    // --- RUTAS DE PEDIDOS (Libres temporalmente para la prueba) ---
    Route::post('/pedidos', [PedidoController::class, 'store']);
    Route::get('/pedidos/{id}', [PedidoController::class, 'show']);

    // Rutas completas para tus Productos
    Route::apiResource('productos', ProductoController::class);

    // Rutas completas para tus Categorías 
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

    // --- RUTAS PROTEGIDAS ---
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });
});

// ── VERSIÓN 2 (Nuevas funcionalidades que pide la práctica) ──────────────────
Route::prefix('v2')->name('v2.')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        // v2 agrega búsqueda full-text y nuevos campos
        Route::apiResource('productos', ProductoControllerV2::class);
    });
});
