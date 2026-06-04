<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase; // Esto limpia la BD después de cada test

    public function test_usuario_puede_registrarse(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Juan López',
            'email' => 'juan@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Verifica que devuelva 201 (Creado) y la estructura JSON correcta
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'juan@test.com']);
    }

    public function test_login_con_credenciales_incorrectas(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'noexiste@test.com',
            'password' => 'wrongpass',
        ]);

        // Verifica que devuelva 401 (No autorizado)
        $response->assertStatus(401);
    }
}
