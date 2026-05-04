@extends('tienda.layout')

@php
    $seo = $page->seo;
    $c = $page->content ?? [];
    $titleTag = $seo->meta_title ?? ($page->title . ' | ' . $empresa->nombre);
    $descTag = $seo->meta_description ?? $page->description;
    $kwTag = $seo->meta_keywords ?? '';
    $canonical = $seo->canonical_url ?? url('/politica-de-devoluciones');
    $robots = $seo->robots ?? 'index,follow';

    // Limpiamos descripciones para JSON-LD
    $cleanDesc = trim(strip_tags($descTag ?? ''));
@endphp

@section('title', $titleTag)
@section('description', $descTag)
@section('keywords', $kwTag)

@section('seo_extras')
    <link rel="canonical" href="{{ $canonical }}">
    <meta name="robots" content="{{ $robots }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $seo->og_type ?? 'article' }}">
    <meta property="og:title" content="{{ $seo->og_title ?? $titleTag }}">
    <meta property="og:description" content="{{ $seo->og_description ?? $descTag }}">
    <meta property="og:url" content="{{ $seo->og_url ?? $canonical }}">
    @if(!empty($seo->og_image))
        <meta property="og:image" content="{{ asset('storage/' . $seo->og_image) }}">
    @endif

    {{-- Twitter --}}
    <meta name="twitter:card" content="{{ $seo->twitter_card ?? 'summary' }}">
    <meta name="twitter:title" content="{{ $seo->twitter_title ?? $titleTag }}">
    <meta name="twitter:description" content="{{ $seo->twitter_description ?? $descTag }}">
    @if(!empty($seo->twitter_image))
        <meta name="twitter:image" content="{{ asset('storage/' . $seo->twitter_image) }}">
    @endif

    {{-- Schema.org structured data --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": @json($titleTag),
        "description": @json($cleanDesc),
        "url": @json($canonical),
        "inLanguage": "es",
        "publisher": {
            "@type": "Organization",
            "name": @json($empresa->nombre),
            "url": @json(url('/'))
        },
        "breadcrumb": {
            "@type": "BreadcrumbList",
            "itemListElement": [
                {"@type": "ListItem", "position": 1, "name": "Inicio", "item": @json(url('/'))},
                {"@type": "ListItem", "position": 2, "name": @json($seo->breadcrumb_title ?? $page->title), "item": @json($canonical)}
            ]
        }
    }
    </script>

    <style>
        .legal-page { padding: 64px 0 80px; background: #fafafa; }
        .legal-page .legal-hero {
            background: linear-gradient(135deg, var(--accent-color, #0071e3) 0%, #0a3d62 100%);
            color: #ffffff !important;
            padding: 56px 0 48px;
            text-align: center;
            border-radius: 0 0 18px 18px;
            margin-bottom: 32px;
        }
        .legal-page .legal-hero,
        .legal-page .legal-hero * {
            color: #ffffff !important;
        }
        .legal-page .legal-hero .eyebrow {
            display: inline-block;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            background: rgba(255,255,255,0.18);
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 14px;
            color: #ffffff !important;
        }
        .legal-page .legal-hero h1 {
            font-size: clamp(1.8rem, 3.2vw, 2.6rem);
            font-weight: 800;
            margin: 0 0 12px;
            line-height: 1.15;
            color: #ffffff !important;
        }
        .legal-page .legal-hero p {
            font-size: 1.05rem;
            opacity: 0.95;
            max-width: 720px;
            margin: 0 auto;
            color: #ffffff !important;
        }
        .legal-page .legal-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 6px 28px rgba(0,0,0,0.05);
            padding: 28px 32px;
            margin-bottom: 22px;
        }
        .legal-page .legal-card h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1d1d1f;
            margin: 0 0 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .legal-page .legal-card h2 i {
            color: var(--accent-color, #0071e3);
        }
        .legal-page .legal-card .legal-body { color: #444; font-size: 0.97rem; line-height: 1.65; }
        .legal-page .legal-card .legal-body p { margin-bottom: 0.85rem; }
        .legal-page .legal-card .legal-body ul,
        .legal-page .legal-card .legal-body ol { padding-left: 1.3rem; margin-bottom: 0.85rem; }
        .legal-page .legal-card .legal-body li { margin-bottom: 0.4rem; }
        .legal-page .legal-contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }
        .legal-page .legal-contact-grid a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            background: #f5f5f7;
            border-radius: 10px;
            color: #1d1d1f;
            text-decoration: none;
            transition: background 0.2s, transform 0.2s;
            font-weight: 500;
        }
        .legal-page .legal-contact-grid a:hover {
            background: rgba(0,113,227,0.08);
            transform: translateY(-1px);
        }
        .legal-page .legal-contact-grid a i { font-size: 1.3rem; color: var(--accent-color, #0071e3); }
        .legal-page .breadcrumb-nav { font-size: 0.88rem; color: #6e6e73; margin-bottom: 18px; }
        .legal-page .breadcrumb-nav a { color: #6e6e73; text-decoration: none; }
        .legal-page .breadcrumb-nav a:hover { color: var(--accent-color, #0071e3); }
    </style>
@endsection

@section('content')
<article class="legal-page">
    <header class="legal-hero">
        <div class="container">
            @if(!empty($c['hero_eyebrow']))
                <span class="eyebrow">{{ $c['hero_eyebrow'] }}</span>
            @endif
            <h1>{{ $c['hero_title'] ?? $page->title }}</h1>
            @if(!empty($c['hero_subtitle']))
                <p>{{ $c['hero_subtitle'] }}</p>
            @endif
        </div>
    </header>

    <div class="container">
        <nav class="breadcrumb-nav" aria-label="breadcrumb">
            <a href="{{ url('/') }}">Inicio</a>
            <span aria-hidden="true">›</span>
            <span>{{ $page->title }}</span>
        </nav>

        <div class="row">
            <div class="col-lg-9 mx-auto">

                @if(!empty($c['plazos_title']) || !empty($c['plazos_body']))
                <section class="legal-card">
                    <h2><i class="bi bi-clock-history"></i> {{ $c['plazos_title'] ?? 'Plazos' }}</h2>
                    <div class="legal-body">{!! $c['plazos_body'] ?? '' !!}</div>
                </section>
                @endif

                @if(!empty($c['condiciones_title']) || !empty($c['condiciones_body']))
                <section class="legal-card">
                    <h2><i class="bi bi-check2-square"></i> {{ $c['condiciones_title'] ?? 'Condiciones' }}</h2>
                    <div class="legal-body">{!! $c['condiciones_body'] ?? '' !!}</div>
                </section>
                @endif

                @if(!empty($c['proceso_title']) || !empty($c['proceso_body']))
                <section class="legal-card">
                    <h2><i class="bi bi-arrow-repeat"></i> {{ $c['proceso_title'] ?? 'Proceso' }}</h2>
                    <div class="legal-body">{!! $c['proceso_body'] ?? '' !!}</div>
                </section>
                @endif

                @if(!empty($c['contacto_title']) || !empty($c['contacto_body']) || !empty($c['contacto_email']) || !empty($c['contacto_telefono']))
                <section class="legal-card">
                    <h2><i class="bi bi-headset"></i> {{ $c['contacto_title'] ?? 'Contacto' }}</h2>
                    <div class="legal-body">{!! $c['contacto_body'] ?? '' !!}</div>

                    @if(!empty($c['contacto_email']) || !empty($c['contacto_telefono']))
                    <div class="legal-contact-grid">
                        @if(!empty($c['contacto_email']))
                            <a href="mailto:{{ $c['contacto_email'] }}">
                                <i class="bi bi-envelope-fill"></i>
                                <span>{{ $c['contacto_email'] }}</span>
                            </a>
                        @endif
                        @if(!empty($c['contacto_telefono']))
                            @php $waNum = preg_replace('/[^0-9]/', '', $c['contacto_telefono']); @endphp
                            <a href="https://wa.me/{{ $waNum }}" target="_blank" rel="noopener">
                                <i class="bi bi-whatsapp"></i>
                                <span>{{ $c['contacto_telefono'] }}</span>
                            </a>
                        @endif
                    </div>
                    @endif
                </section>
                @endif

            </div>
        </div>
    </div>
</article>
@endsection
