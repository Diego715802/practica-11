<?php

namespace App\Http\Controllers;

use App\Events\NuevoPedidoRecibido;
use App\Events\StockBajoAlerta;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\User;
use App\Jobs\EnviarConfirmacionPedido;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function store(Request $request)
    {
        try {
            $usuario = User::firstOrCreate(
                ['email' => 'prueba@tienda.com'],
                ['name' => 'Cliente Prueba', 'password' => bcrypt('123456')]
            );

            $pedido = DB::transaction(function () use ($request, $usuario) {
                // 1. Creamos el pedido principal
                $p = Pedido::create([
                    'user_id' => $usuario->id,
                    'total' => collect($request->items)->sum(fn($i) => $i['precio'] * $i['cantidad']),
                ]);

                // 2. Guardamos los items a la fuerza para que no ignore el precio
                foreach ($request->items as $item) {
                    PedidoItem::insert([
                        'pedido_id' => $p->id,
                        'producto_id' => $item['producto_id'],
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $producto = Producto::find($item['producto_id']);
                    if ($producto) {
                        $producto->decrement('stock', $item['cantidad']);
                    }
                }
                return $p;
            });


            // Despacha el Job del correo
            EnviarConfirmacionPedido::dispatch($pedido)->delay(now()->addSeconds(5));

            return response()->json(['pedido_id' => $pedido->id], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error de Laravel: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        return response()->json(Pedido::findOrFail($id));
    }
}
