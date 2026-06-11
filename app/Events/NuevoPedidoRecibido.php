<?php

namespace App\Events;

use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // <--- CAMBIO AQUÍ
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// CAMBIO AQUÍ TAMBIÉN:
class NuevoPedidoRecibido implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Pedido $pedido) {}

    public function broadcastOn(): array
    {
        return [new Channel('admin-panel')];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->pedido->id,
            'total' => $this->pedido->total,
            'cliente' => $this->pedido->user ? $this->pedido->user->name : 'Cliente',
            'items' => $this->pedido->items->count(),
            'created_at' => $this->pedido->created_at->format('H:i:s'),
        ];
    }
}
