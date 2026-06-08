<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Pedido;

class ConfirmacionPedidoNotification extends Notification
{
    use Queueable;

    public function __construct(public Pedido $pedido) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmación de tu pedido #' . $this->pedido->id)
            ->greeting('¡Hola, ' . $notifiable->name . '!')
            ->line('Recibimos tu pedido correctamente.')
            ->line('Total: $' . number_format($this->pedido->total, 2))
            ->action('Ver mi pedido', url('/pedidos/' . $this->pedido->id))
            ->line('Gracias por tu compra.');
    }
}
