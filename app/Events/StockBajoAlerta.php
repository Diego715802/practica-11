<?php

namespace App\Events;

use App\Models\Producto;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // <--- CAMBIO AQUÍ
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// CAMBIO AQUÍ TAMBIÉN:
class StockBajoAlerta implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Producto $producto,
        public int $stockActual
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('admin-panel')];
    }

    public function broadcastWith(): array
    {
        return [
            'producto_id' => $this->producto->id,
            'nombre' => $this->producto->nombre,
            'stock_actual' => $this->stockActual,
        ];
    }
}
