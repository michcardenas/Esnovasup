<?php

namespace App\Mail;

use App\Mail\Concerns\UsesEmailTemplate;
use App\Models\Compra;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompraConfirmadaCliente extends Mailable
{
    use Queueable, SerializesModels, UsesEmailTemplate;

    public Compra $compra;
    protected string $templateKey = 'compra_confirmada_cliente';

    public function __construct(Compra $compra)
    {
        $this->compra = $compra;
    }

    public function envelope(): Envelope
    {
        $tpl = $this->dbTemplate();
        return new Envelope(
            subject: $tpl['subject'] ?? ('Tu compra #' . $this->compra->numero_compra . ' ha sido confirmada'),
        );
    }

    public function content(): Content
    {
        $tpl = $this->dbTemplate();
        if ($tpl) {
            return new Content(htmlString: $tpl['body']);
        }

        return new Content(
            view: 'emails.compra-confirmada-cliente',
            with: ['compra' => $this->compra],
        );
    }

    protected function templateVars(): array
    {
        $compra = $this->compra->loadMissing(['items', 'empresa']);

        $itemsHtml = '';
        foreach ($compra->items as $item) {
            $itemsHtml .= '<tr>'
                . '<td>' . e($item->nombre_producto) . '</td>'
                . '<td align="center">' . (int) $item->cantidad . '</td>'
                . '<td align="right">$' . number_format($item->precio_unitario, 0, ',', '.') . '</td>'
                . '<td align="right">$' . number_format($item->precio_total, 0, ',', '.') . '</td>'
                . '</tr>';
        }

        return [
            'numero_compra' => $compra->numero_compra,
            'nombre_cliente' => $compra->nombre_cliente,
            'email_cliente' => $compra->email_cliente,
            'telefono_cliente' => $compra->telefono_cliente,
            'total' => number_format($compra->total, 0, ',', '.'),
            'metodo_pago' => $compra->metodo_pago,
            'fecha_compra' => optional($compra->created_at)->format('d/m/Y H:i'),
            'empresa_nombre' => $compra->empresa->nombre ?? '',
            'items_html' => $itemsHtml,
        ];
    }
}
