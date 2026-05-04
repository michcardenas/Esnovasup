<?php

use App\Models\Page;
use App\Models\Seo;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $page = Page::firstOrCreate(
            ['slug' => 'politica-de-devoluciones'],
            [
                'name' => 'devoluciones',
                'title' => 'Política de Devoluciones',
                'description' => 'Conoce los plazos, condiciones y el proceso para realizar una devolución en nuestra tienda.',
                'is_active' => true,
                'content' => [
                    'hero_eyebrow' => 'Información para el cliente',
                    'hero_title' => 'Política de Devoluciones',
                    'hero_subtitle' => 'Tu satisfacción es nuestra prioridad. Te explicamos cómo y cuándo puedes devolver un producto.',

                    'plazos_title' => 'Plazos para solicitar la devolución',
                    'plazos_body' => '<p>Cuentas con un plazo de <strong>5 días hábiles</strong> a partir de la entrega del producto para solicitar una devolución. Pasado este término, no será posible aceptar la solicitud, salvo casos cubiertos por la garantía del fabricante.</p>',

                    'condiciones_title' => 'Condiciones del producto',
                    'condiciones_body' => '<ul><li>El producto debe estar en su <strong>empaque original</strong>, sin uso y con sus etiquetas y accesorios.</li><li>No se aceptan devoluciones de productos personalizados, de uso íntimo o consumibles abiertos.</li><li>El cliente debe presentar la factura o comprobante de compra.</li></ul>',

                    'proceso_title' => 'Proceso de devolución',
                    'proceso_body' => '<ol><li>Comunícate con nuestro equipo de soporte indicando el número de pedido y el motivo.</li><li>Te enviaremos las instrucciones para el envío del producto a nuestra bodega.</li><li>Una vez recibido y verificado, procesamos el reembolso por el mismo medio de pago en un plazo de hasta 10 días hábiles.</li></ol>',

                    'contacto_title' => '¿Necesitas ayuda?',
                    'contacto_body' => '<p>Escríbenos a nuestro correo o WhatsApp y un asesor te guiará en el proceso.</p>',
                    'contacto_email' => '',
                    'contacto_telefono' => '',
                ],
            ]
        );

        Seo::firstOrCreate(
            ['page_id' => $page->id],
            [
                'meta_title' => 'Política de Devoluciones | ' . config('app.name'),
                'meta_description' => 'Conoce nuestra política de devoluciones: plazos, condiciones, proceso paso a paso y canales de contacto para resolver cualquier inconveniente con tu compra.',
                'meta_keywords' => 'política de devoluciones, devoluciones, reembolso, garantía, condiciones de compra',
                'canonical_url' => url('/politica-de-devoluciones'),
                'robots' => 'index,follow',
                'og_title' => 'Política de Devoluciones',
                'og_description' => 'Plazos, condiciones, proceso de devolución y contacto. Tu compra está respaldada.',
                'og_type' => 'article',
                'og_url' => url('/politica-de-devoluciones'),
                'twitter_card' => 'summary',
                'twitter_title' => 'Política de Devoluciones',
                'twitter_description' => 'Plazos, condiciones y proceso de devolución.',
                'focus_keyword' => 'política de devoluciones',
                'breadcrumb_title' => 'Política de Devoluciones',
                'sitemap_include' => true,
                'sitemap_priority' => 0.6,
                'sitemap_changefreq' => 'yearly',
                'is_active' => true,
            ]
        );
    }

    public function down(): void
    {
        $page = Page::where('slug', 'politica-de-devoluciones')->first();
        if ($page) {
            Seo::where('page_id', $page->id)->delete();
            $page->delete();
        }
    }
};
