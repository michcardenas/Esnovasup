<?php

namespace App\Listeners;

use App\Events\CompraAprobada;
use App\Mail\CompraAprobadaVendedor;
use App\Mail\CompraConfirmadaCliente;
use App\Models\NotificacionDestinatario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarNotificacionCompraAprobada implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct() {}

    public function handle(CompraAprobada $event): void
    {
        $compra = $event->compra;
        $empresa = $compra->empresa;

        // 1. Email al CLIENTE con el resumen del pedido.
        if (!empty($compra->email_cliente)) {
            try {
                Mail::to($compra->email_cliente)->send(new CompraConfirmadaCliente($compra));
                Log::info("CompraAprobada: confirmación enviada al cliente {$compra->email_cliente} - compra #{$compra->numero_compra}");
            } catch (\Throwable $e) {
                Log::error("CompraAprobada: error enviando email al cliente - {$e->getMessage()}");
            }
        }

        // 2. Notificación interna a destinatarios admin.
        // Combina el dueño de la empresa + los destinatarios configurados desde el panel.
        $destinatarios = NotificacionDestinatario::emailsParaEvento('compra_aprobada');

        if ($empresa && $empresa->usuario && $empresa->usuario->email) {
            $destinatarios[] = $empresa->usuario->email;
        }

        $destinatarios = array_values(array_unique(array_filter($destinatarios)));

        if (empty($destinatarios)) {
            Log::warning("CompraAprobada: no hay destinatarios admin configurados ni dueño con email para compra #{$compra->numero_compra}");
            return;
        }

        try {
            Mail::to($destinatarios)->send(new CompraAprobadaVendedor($compra));
            Log::info("CompraAprobada: notificación interna enviada a " . implode(',', $destinatarios) . " - compra #{$compra->numero_compra}");
        } catch (\Throwable $e) {
            Log::error("CompraAprobada: error enviando notificación interna - {$e->getMessage()}");
        }
    }
}
