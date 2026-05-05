<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('subject');
            $table->longText('body');
            $table->json('available_variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('notificaciones_destinatarios', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('nombre')->nullable();
            $table->json('eventos')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Sembrar plantillas por defecto. El cuerpo usa marcadores {variable}
        // que se reemplazan en runtime con datos reales del pedido.
        $now = now();
        $defaults = [
            [
                'key' => 'compra_confirmada_cliente',
                'name' => 'Compra confirmada (al cliente)',
                'description' => 'Se envía al cliente cuando su pago es aprobado.',
                'subject' => 'Tu compra #{numero_compra} ha sido confirmada',
                'available_variables' => json_encode([
                    'numero_compra', 'nombre_cliente', 'email_cliente', 'total',
                    'metodo_pago', 'fecha_compra', 'empresa_nombre', 'items_html',
                ]),
                'body' => '<h2>¡Gracias por tu compra, {nombre_cliente}!</h2>
<p>Tu pedido <strong>#{numero_compra}</strong> ha sido confirmado.</p>
<p><strong>Resumen:</strong></p>
<table cellpadding="6" cellspacing="0" border="1" style="border-collapse:collapse;width:100%">
{items_html}
<tr><td colspan="3"><strong>Total</strong></td><td align="right"><strong>${total}</strong></td></tr>
</table>
<p><strong>Método de pago:</strong> {metodo_pago}<br>
<strong>Fecha:</strong> {fecha_compra}</p>
<p>Te avisaremos por correo cuando tu pedido sea despachado.</p>
<p>— Equipo de {empresa_nombre}</p>',
            ],
            [
                'key' => 'compra_aprobada_vendedor',
                'name' => 'Nueva venta (al admin/vendedor)',
                'description' => 'Notificación interna cuando se aprueba una compra.',
                'subject' => 'Nueva venta confirmada — #{numero_compra}',
                'available_variables' => json_encode([
                    'numero_compra', 'nombre_cliente', 'email_cliente', 'telefono_cliente',
                    'total', 'metodo_pago', 'fecha_compra', 'empresa_nombre', 'items_html',
                ]),
                'body' => '<h2>Nueva venta confirmada</h2>
<p><strong>Pedido:</strong> #{numero_compra}<br>
<strong>Cliente:</strong> {nombre_cliente} ({email_cliente})<br>
<strong>Teléfono:</strong> {telefono_cliente}<br>
<strong>Total:</strong> ${total}<br>
<strong>Método de pago:</strong> {metodo_pago}<br>
<strong>Fecha:</strong> {fecha_compra}</p>
<p><strong>Productos:</strong></p>
<table cellpadding="6" cellspacing="0" border="1" style="border-collapse:collapse;width:100%">
{items_html}
</table>',
            ],
        ];

        foreach ($defaults as $row) {
            DB::table('email_templates')->insert(array_merge($row, [
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones_destinatarios');
        Schema::dropIfExists('email_templates');
    }
};
