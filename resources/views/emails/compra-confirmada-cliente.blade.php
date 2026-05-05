<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Compra confirmada</title>
</head>
<body style="font-family: Arial, sans-serif; color:#333; max-width:600px; margin:auto; padding:24px;">
    <h2 style="color:#0071e3;">¡Gracias por tu compra, {{ $compra->nombre_cliente }}!</h2>
    <p>Tu pedido <strong>#{{ $compra->numero_compra }}</strong> ha sido confirmado.</p>

    <h3>Resumen del pedido</h3>
    <table cellpadding="8" cellspacing="0" style="border-collapse:collapse;width:100%;border:1px solid #ddd;">
        <thead style="background:#f5f5f7;">
            <tr>
                <th align="left">Producto</th>
                <th align="center">Cant.</th>
                <th align="right">Precio</th>
                <th align="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compra->items as $item)
                <tr>
                    <td>{{ $item->nombre_producto }}</td>
                    <td align="center">{{ $item->cantidad }}</td>
                    <td align="right">${{ number_format($item->precio_unitario, 0, ',', '.') }}</td>
                    <td align="right">${{ number_format($item->precio_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" align="right"><strong>Total</strong></td>
                <td align="right"><strong>${{ number_format($compra->total, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top:24px;">
        <strong>Método de pago:</strong> {{ ucfirst($compra->metodo_pago) }}<br>
        <strong>Fecha:</strong> {{ $compra->created_at?->format('d/m/Y H:i') }}
    </p>

    <p>Te avisaremos por correo cuando tu pedido sea despachado.</p>
    <p style="color:#888;font-size:0.9em;">— Equipo de {{ $compra->empresa->nombre ?? '' }}</p>
</body>
</html>
