<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Str;

class CategoriaProductoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = ['Electrónica', 'Ropa', 'Hogar', 'Deportes'];

        foreach ($categorias as $nombre) {
            $cat = Categoria::create([
                'nombre' => $nombre,
                'slug' => Str::slug($nombre),
            ]);

            // Crea 5 productos inventados por cada categoría
            Producto::factory(5)->create(['categoria_id' => $cat->id]);
        }
    }
}
