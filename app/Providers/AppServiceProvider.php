<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // <-- Importa Gate
use App\Models\User; // <-- Importa User

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::define('crear-producto', function (User $user) {
            return in_array($user->rol, ['admin', 'editor']);
        });

        Gate::define('editar-producto', function (User $user) {
            return in_array($user->rol, ['admin', 'editor']);
        });

        Gate::define('eliminar-producto', function (User $user) {
            return $user->esAdmin();
        });
    }
}
