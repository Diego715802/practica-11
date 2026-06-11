<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\ProductoController as V1ProductoController;
use Illuminate\Http\Request;
use App\Models\Producto;
// Si usas un ProductoResource, asegúrate de importarlo aquí:
// use App\Http\Resources\ProductoResource; 

class ProductoController extends V1ProductoController
{
    public function index(Request $request)
    {
        // v2: agrega búsqueda full-text con MySQL FULLTEXT
        $query = Producto::with('categoria');

        if ($request->q) {
            $query->whereFullText(['nombre', 'descripcion'], $request->q);
        }

        // Si tienes ProductoResource, usa la línea de abajo. 
        // Si no lo tienes, puedes retornar simplemente: return response()->json($query->paginate(15));
        // return ProductoResource::collection($query->paginate(15));

        return response()->json($query->paginate(15));
    }
}
