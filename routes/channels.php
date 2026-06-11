<?php

use Illuminate\Support\Facades\Broadcast;

// Temporalmente retornamos 'true' para no pelear con la autenticación durante la prueba
Broadcast::channel('admin-panel', function ($user) {
    return true;
});