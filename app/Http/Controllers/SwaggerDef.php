<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Mi Tienda Online API",
    description: "API REST para gestión de tienda"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "Sanctum"
)]
class SwaggerDef
{
    #[OA\Post(
        path: "/api/v1/login",
        summary: "Iniciar sesión",
        tags: ["Autenticación"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["email", "password"],
            properties: [
                new OA\Property(property: "email", type: "string", format: "email", example: "admin@test.com"),
                new OA\Property(property: "password", type: "string", format: "password", example: "password123")
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Login exitoso")]
    #[OA\Response(response: 401, description: "Credenciales incorrectas")]
    public function loginDummy() {}
}
