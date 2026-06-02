<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        // Lista de nombres reales en español
        $nombresReales = [
            'Laptop Ultra Slim',
            'Smartphone 5G',
            'Auriculares Inalámbricos',
            'Camiseta de Algodón',
            'Chamarra de Cuero',
            'Tenis para Correr',
            'Smart TV 4K',
            'Cafetera Automática',
            'Licuadora Profesional',
            'Balón de Fútbol',
            'Raqueta de Tenis',
            'Mancuernas de 5kg',
            'Reloj Inteligente',
            'Mochila de Viaje',
            'Gafas de Sol'
        ];

        return [
            // Elige un nombre real de la lista y le añade un modelo inventado (ej: Laptop Ultra Slim XJ-102)
            'nombre' => fake()->randomElement($nombresReales) . ' ' . fake()->bothify('??-###'),
            'descripcion' => 'Excelente producto de alta calidad y durabilidad garantizada.',
            'precio' => fake()->randomFloat(2, 99, 5000),
            'stock' => fake()->numberBetween(5, 100),
        ];
    }
}
