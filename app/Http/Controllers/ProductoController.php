<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Resources\ProductoResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;

use OpenApi\Attributes as OA;

class ProductoController extends Controller
{
    #[OA\Get(
        path: "/api/v1/productos",
        summary: "Listar todos los productos",
        security: [["bearerAuth" => []]],
        tags: ["Productos"]
    )]
    #[OA\Response(response: 200, description: "Lista de productos obtenida exitosamente")]
    public function index(Request $request)
    {
        $productos = Producto::with('categoria')
            ->buscar($request->busqueda)
            ->deCategoria($request->categoria_id)
            ->rangoPrecio($request->precio_min, $request->precio_max)
            ->orderBy($request->get('orden', 'nombre'), $request->get('dir', 'asc'))
            ->paginate($request->get('por_pagina', 5));

        return \App\Http\Resources\ProductoResource::collection($productos);
    }

    #[OA\Post(
        path: "/api/v1/productos",
        summary: "Crear un nuevo producto",
        security: [["bearerAuth" => []]],
        tags: ["Productos"]
    )]
    #[OA\Response(response: 201, description: "Producto creado")]
    #[OA\Response(response: 422, description: "Error de validación")]
    public function store(StoreProductoRequest $request)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        return response()->json(new ProductoResource(Producto::create($data)), 201);
    }

    #[OA\Get(
        path: "/api/v1/productos/{id}",
        summary: "Obtener un producto específico",
        security: [["bearerAuth" => []]],
        tags: ["Productos"]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Detalles del producto")]
    #[OA\Response(response: 404, description: "Producto no encontrado")]
    public function show(string $id)
    {
        $producto = Producto::with('categoria')->findOrFail($id);
        return response()->json(new ProductoResource($producto));
    }

    #[OA\Put(
        path: "/api/v1/productos/{id}",
        summary: "Actualizar un producto",
        security: [["bearerAuth" => []]],
        tags: ["Productos"]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Producto actualizado")]
    #[OA\Response(response: 404, description: "Producto no encontrado")]
    public function update(UpdateProductoRequest $request, string $id)
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

    #[OA\Delete(
        path: "/api/v1/productos/{id}",
        summary: "Eliminar un producto",
        security: [["bearerAuth" => []]],
        tags: ["Productos"]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Producto eliminado")]
    #[OA\Response(response: 403, description: "Acción no autorizada")]
    #[OA\Response(response: 404, description: "Producto no encontrado")]
    public function destroy(string $id)
    {
        if (request()->user() && request()->user()->rol === 'cliente') {
            abort(403, 'Acción no autorizada.');
        }

        $producto = Producto::findOrFail($id);

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        $producto->delete();
        return response()->json(['mensaje' => 'Eliminado']);
    }
}
