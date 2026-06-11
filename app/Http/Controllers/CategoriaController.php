<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Resources\CategoriaResource;
use App\Http\Resources\ProductoResource;
use Illuminate\Support\Facades\Cache;

class CategoriaController extends Controller
{
    public function index()
    {
        // Guardamos en caché por 1 hora (3600 segundos)
        $categorias = Cache::remember('categorias.todas', 3600, function () {
            // Usamos resolve() para convertir tu Resource en un arreglo plano 
            // que Redis pueda guardar en memoria sin problemas.
            return CategoriaResource::collection(Categoria::with('productos')->get())->resolve();
        });

        return response()->json(['data' => $categorias]);
    }

    // Tu método original intacto para no romper tu frontend
    public function productos(Categoria $categoria)
    {
        return ProductoResource::collection($categoria->productos()->with('categoria')->get());
    }

    // ─── MÉTODOS DE ESCRITURA PARA LIMPIAR EL CACHÉ (Requisito de la práctica) ───

    public function store(Request $request)
    {
        $data = $request->validate(['nombre' => 'required|string']);
        $categoria = Categoria::create($data);

        Cache::forget('categorias.todas'); // ← Limpiar caché porque hay datos nuevos

        return response()->json(new CategoriaResource($categoria), 201);
    }

    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate(['nombre' => 'required|string']);
        $categoria->update($data);

        Cache::forget('categorias.todas'); // ← Limpiar caché porque se actualizó un dato

        return response()->json(new CategoriaResource($categoria));
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        Cache::forget('categorias.todas'); // ← Limpiar caché porque se borró un dato

        return response()->json(['mensaje' => 'Categoría eliminada']);
    }
}
