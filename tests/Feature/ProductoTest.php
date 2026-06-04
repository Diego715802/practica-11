<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Producto;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin()
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        return $this->actingAs($admin, 'sanctum');
    }

    public function test_puede_listar_productos(): void
    {
        Producto::factory(5)->create(); // Crea 5 productos falsos

        $this->actingAsAdmin()
            ->getJson('/api/productos')
            ->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_puede_crear_producto(): void
    {
        $this->actingAsAdmin()
            ->postJson('/api/productos', [
                'nombre' => 'Laptop Dell',
                'precio' => 1299.99,
                'stock' => 10,
            ])
            ->assertCreated()
            ->assertJsonPath('nombre', 'Laptop Dell'); // <-- El "data." ya no está

        $this->assertDatabaseHas('productos', ['nombre' => 'Laptop Dell']);
    }

    public function test_cliente_no_puede_eliminar(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);
        $producto = Producto::factory()->create();

        $this->actingAs($cliente, 'sanctum')
            ->deleteJson("/api/productos/{$producto->id}")
            ->assertForbidden(); // Esperamos un 403 (Prohibido)
    }
}
