<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Aquí le damos permiso a Laravel para guardar estos campos en la BD
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen' // <-- ¡Esta es la palabra mágica que faltaba!
    ];
}
