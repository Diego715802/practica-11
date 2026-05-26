<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        Producto::create([
            'nombre' => 'MacBook Pro',
            'descripcion' => 'Laptop Apple con chip M3',
            'precio' => 1599.99,
            'stock' => 10
        ]);
        Producto::create([
            'nombre' => 'iPhone 15',
            'descripcion' => 'Teléfono Apple de 128GB',
            'precio' => 799.50,
            'stock' => 25
        ]);
    }
}
