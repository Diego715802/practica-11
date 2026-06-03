<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\User;

class ProductoPolicy
{
    public function create(User $user): bool
    {
        return in_array($user->rol, ['admin', 'editor']);
    }

    public function update(User $user, Producto $producto): bool
    {
        return in_array($user->rol, ['admin', 'editor']);
    }

    public function delete(User $user, Producto $producto): bool
    {
        return $user->esAdmin();
    }
}
