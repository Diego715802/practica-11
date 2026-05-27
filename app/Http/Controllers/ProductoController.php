<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Resources\ProductoResource; // Si lo estás usando
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        // En tu práctica piden que retornemos todo o usemos resource.
        // Si usas Resource, lo mapeamos:
        return response()->json(ProductoResource::collection(Producto::all()));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'  => 'required|string',
            'precio'  => 'required|numeric',
            'stock'   => 'required|numeric',
            'imagen'  => 'nullable|image|mimes:jpg,png,webp|max:2048',
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
            // Borra la imagen vieja si existe
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
