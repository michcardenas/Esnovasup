<?php

namespace App\Console\Commands;

use App\Models\Compra;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LiberarStockComprasPendientesCommand extends Command
{
    protected $signature = 'compras:liberar-stock-pendientes
                            {--horas=72 : Cancelar pendientes con más de N horas sin aprobación}
                            {--dry-run : Solo reportar, no ejecutar}';

    protected $description = 'Cancela compras pendientes vencidas y devuelve su stock al inventario.';

    public function handle()
    {
        $horas = (int) $this->option('horas');
        $dryRun = (bool) $this->option('dry-run');
        $limite = now()->subHours($horas);

        $query = Compra::where('estado', 'pendiente')
            ->where('created_at', '<', $limite)
            ->with('items.producto');

        $total = $query->count();

        if ($total === 0) {
            $this->info("No hay compras pendientes con más de {$horas}h.");
            return self::SUCCESS;
        }

        $this->info(($dryRun ? '[DRY-RUN] ' : '') . "Procesando {$total} compras pendientes con más de {$horas}h…");

        $procesadas = 0;
        $errores = 0;

        $query->chunkById(50, function ($compras) use (&$procesadas, &$errores, $dryRun) {
            foreach ($compras as $compra) {
                try {
                    if ($dryRun) {
                        $this->line("  - #{$compra->numero_compra} ({$compra->created_at->diffForHumans()}) total \${$compra->total}");
                        $procesadas++;
                        continue;
                    }

                    DB::transaction(function () use ($compra) {
                        $this->liberarStockDeCompra($compra);
                        $compra->update([
                            'estado' => 'cancelada',
                            'notas' => trim(($compra->notas ?? '') . "\n\n[" . now()->format('d/m/Y H:i') . "] Cancelada automáticamente por vencimiento de tiempo de pago."),
                        ]);
                        $compra->transaccionesPago()->where('estado', 'pendiente')->update([
                            'estado' => 'rechazada',
                            'mensaje_error' => 'Vencida automáticamente',
                        ]);
                    });

                    $procesadas++;
                } catch (\Throwable $e) {
                    $errores++;
                    Log::error("Error liberando stock de compra {$compra->numero_compra}: " . $e->getMessage());
                    $this->error("  ! Error en #{$compra->numero_compra}: {$e->getMessage()}");
                }
            }
        });

        $this->info(($dryRun ? '[DRY-RUN] ' : '') . "Procesadas: {$procesadas} | Errores: {$errores}");
        return $errores === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function liberarStockDeCompra(Compra $compra): void
    {
        foreach ($compra->items as $item) {
            $producto = $item->producto;
            if (!$producto || !$producto->controlar_stock) {
                continue;
            }

            $stock = $item->variante_producto_id
                ? $producto->stock()->where('variante_producto_id', $item->variante_producto_id)->first()
                : $producto->stockPrincipal;

            if ($stock) {
                $stock->entrada(
                    $item->cantidad,
                    'devolucion',
                    $compra->numero_compra,
                    'Liberación automática por compra pendiente vencida'
                );
            }
        }
    }
}
