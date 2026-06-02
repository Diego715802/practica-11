<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'imagen', 'categoria_id'];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    // --- NUEVOS FILTROS (QUERY SCOPES) ---

    public function scopeBuscar($query, $termino)
    {
        // Usamos 'when' para que solo busque si realmente escribiste algo
        return $query->when($termino, function ($q) use ($termino) {
            $q->where(function ($sub) use ($termino) {
                $sub->where('nombre', 'LIKE', "%{$termino}%")
                    ->orWhere('descripcion', 'LIKE', "%{$termino}%");
            });
        });
    }

    public function scopeDeCategoria($query, $categoriaId)
    {
        return $query->when($categoriaId, fn($q) => $q->where('categoria_id', $categoriaId));
    }

    public function scopeRangoPrecio($query, $min, $max)
    {
        return $query
            ->when($min, fn($q) => $q->where('precio', '>=', $min))
            ->when($max, fn($q) => $q->where('precio', '<=', $max));
    }
}
