<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

/**
 * Exportación de Compras con múltiples hojas (Resumen + Detalle de items)
 */
class ComprasExport implements WithMultipleSheets
{
    protected $compras;

    public function __construct($compras)
    {
        $this->compras = $compras;
    }

    public function sheets(): array
    {
        return [
            new ComprasResumenSheet($this->compras),
            new ComprasItemsSheet($this->compras),
        ];
    }
}

/**
 * Hoja 1: Resumen de compras (una fila por compra)
 */
class ComprasResumenSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $compras;

    public function __construct($compras)
    {
        $this->compras = $compras;
    }

    public function collection(): Collection
    {
        return collect($this->compras);
    }

    public function headings(): array
    {
        return [
            '# Compra',
            'Fecha',
            'Cliente',
            'Email',
            'Teléfono',
            'Dirección',
            'Ciudad',
            'Estado',
            'Método de Pago',
            'Subtotal',
            'Descuento',
            'Envío',
            'Total',
            'Items',
            'Referencia Pago',
            'Notas',
        ];
    }

    public function map($compra): array
    {
        $itemsResumen = '';
        if ($compra->items && $compra->items->count() > 0) {
            $itemsResumen = $compra->items->map(function ($it) {
                return ($it->cantidad ?? 1) . 'x ' . ($it->nombre_producto ?? 'Producto');
            })->implode(' | ');
        }

        $referenciaPago = optional($compra->transaccionAprobada)->referencia
            ?? optional($compra->transaccionAprobada)->id
            ?? '';

        return [
            $compra->numero_compra,
            optional($compra->created_at)->format('d/m/Y H:i'),
            $compra->nombre_cliente,
            $compra->email_cliente,
            $compra->telefono_cliente,
            $compra->direccion_envio,
            optional($compra->ciudad)->nombre ?? '',
            ucfirst($compra->estado ?? ''),
            $compra->metodo_pago,
            (float) $compra->subtotal,
            (float) $compra->descuento_total,
            (float) $compra->costo_envio,
            (float) $compra->total,
            $itemsResumen,
            $referenciaPago,
            $compra->notas,
        ];
    }

    public function title(): string
    {
        return 'Resumen';
    }

    public function styles(Worksheet $sheet)
    {
        // Encabezado en negrita con fondo azul
        $sheet->getStyle('A1:P1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:P1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('2E5BBA');

        // Formato moneda COP para columnas Subtotal, Descuento, Envío, Total (J:M)
        $lastRow = $sheet->getHighestRow();
        if ($lastRow > 1) {
            $sheet->getStyle("J2:M{$lastRow}")
                ->getNumberFormat()
                ->setFormatCode('"$"#,##0');
        }

        // Filtros y vista congelada
        $sheet->setAutoFilter('A1:P1');
        $sheet->freezePane('A2');

        return [];
    }
}

/**
 * Hoja 2: Detalle de items (una fila por producto comprado)
 */
class ComprasItemsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $compras;

    public function __construct($compras)
    {
        $this->compras = $compras;
    }

    public function collection(): Collection
    {
        // Aplanar: una fila por cada item de cada compra
        $rows = collect();
        foreach ($this->compras as $compra) {
            if (!$compra->items || $compra->items->count() === 0) {
                continue;
            }
            foreach ($compra->items as $item) {
                $rows->push([
                    'compra' => $compra,
                    'item' => $item,
                ]);
            }
        }
        return $rows;
    }

    public function headings(): array
    {
        return [
            '# Compra',
            'Fecha',
            'Cliente',
            'Estado Compra',
            'Producto',
            'Variante',
            'Cantidad',
            'Precio Unit.',
            'Subtotal Item',
        ];
    }

    public function map($row): array
    {
        $compra = $row['compra'];
        $item = $row['item'];

        return [
            $compra->numero_compra,
            optional($compra->created_at)->format('d/m/Y H:i'),
            $compra->nombre_cliente,
            ucfirst($compra->estado ?? ''),
            $item->nombre_producto ?? '',
            $item->variante_descripcion ?? ($item->talla ?? '') . ' ' . ($item->color ?? ''),
            (int) ($item->cantidad ?? 0),
            (float) ($item->precio_unitario ?? 0),
            (float) ($item->precio_total ?? 0),
        ];
    }

    public function title(): string
    {
        return 'Items';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:I1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('2E5BBA');

        $lastRow = $sheet->getHighestRow();
        if ($lastRow > 1) {
            $sheet->getStyle("H2:I{$lastRow}")
                ->getNumberFormat()
                ->setFormatCode('"$"#,##0');
        }

        $sheet->setAutoFilter('A1:I1');
        $sheet->freezePane('A2');

        return [];
    }
}
