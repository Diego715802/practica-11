/**
* @OA\Post(
* path="/api/v1/login",
* tags={"Autenticación"},
* summary="Iniciar sesión",
* @OA\RequestBody(
* required=true,
* @OA\JsonContent(
* required={"email","password"},
* @OA\Property(property="email", type="string", format="email", example="admin@test.com"),
* @OA\Property(property="password", type="string", format="password", example="password123")
* )
* ),
* @OA\Response(
* response=200,
* description="Login exitoso",
* @OA\JsonContent(
* @OA\Property(property="token", type="string"),
* @OA\Property(property="user", type="object")
* )
* ),
* @OA\Response(response=401, description="Credenciales incorrectas")
* )
*/