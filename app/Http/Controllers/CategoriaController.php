<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Resources\CategoriaResource;
use App\Http\Resources\ProductoResource;

class CategoriaController extends Controller
{
    public function index()
    {
        return CategoriaResource::collection(Categoria::with('productos')->get());
    }

    public function productos(Categoria $categoria)
    {
        return ProductoResource::collection($categoria->productos()->with('categoria')->get());
    }
}