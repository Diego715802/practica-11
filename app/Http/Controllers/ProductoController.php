<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Resources\ProductoResource;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::with('categoria')
            ->buscar($request->busqueda)
            ->deCategoria($request->categoria_id)
            ->rangoPrecio($request->precio_min, $request->precio_max)
            ->orderBy($request->get('orden', 'nombre'), $request->get('dir', 'asc'))
            ->paginate($request->get('por_pagina', 5)); // Puse 5 para que veas la paginación rápido

        // ESTA ES LA LÍNEA CLAVE PARA LOS LINKS
        return \App\Http\Resources\ProductoResource::collection($productos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'  => 'required|string',
            'precio'  => 'required|numeric',
            'stock'   => 'required|numeric',
            'imagen'  => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'categoria_id' => 'nullable|exists:categorias,id'
        ]);

        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        return response()->json(new ProductoResource(Producto::create($data)), 201);
    }

    public function update(Request $request, string $id)
    {
        $producto = Producto::findOrFail($id);

        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);
        return response()->json(new ProductoResource($producto));
    }

    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        $producto->delete();
        return response()->json(['mensaje' => 'Eliminado']);
    }
}
