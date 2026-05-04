<!-- Editor específico para la página Política de Devoluciones -->
<div class="devoluciones-content-editor">

    {{-- Sección Hero --}}
    <div class="editor-section mb-4">
        <h6 class="text-uppercase text-muted mb-3"><i class="bi bi-megaphone me-2"></i>Encabezado de la página</h6>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Eyebrow (texto pequeño superior)</label>
                <input type="text" class="form-control" name="content[hero_eyebrow]"
                       value="{{ old('content.hero_eyebrow', $page->content['hero_eyebrow'] ?? '') }}"
                       placeholder="Ej: Información para el cliente">
            </div>
            <div class="col-md-6">
                <label class="form-label">Título principal</label>
                <input type="text" class="form-control" name="content[hero_title]"
                       value="{{ old('content.hero_title', $page->content['hero_title'] ?? 'Política de Devoluciones') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Subtítulo</label>
                <textarea class="form-control" name="content[hero_subtitle]" rows="2">{{ old('content.hero_subtitle', $page->content['hero_subtitle'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <hr>

    {{-- Sección Plazos --}}
    <div class="editor-section mb-4">
        <h6 class="text-uppercase text-muted mb-3"><i class="bi bi-clock-history me-2"></i>Plazos</h6>
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" class="form-control" name="content[plazos_title]"
                   value="{{ old('content.plazos_title', $page->content['plazos_title'] ?? 'Plazos para solicitar la devolución') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Contenido</label>
            <textarea class="form-control" name="content[plazos_body]" rows="4"
                      placeholder="Acepta HTML básico (<p>, <strong>, <ul>, etc.)">{{ old('content.plazos_body', $page->content['plazos_body'] ?? '') }}</textarea>
            <small class="form-text text-muted">Puedes usar HTML simple: &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;&lt;li&gt;, &lt;ol&gt;.</small>
        </div>
    </div>

    <hr>

    {{-- Sección Condiciones --}}
    <div class="editor-section mb-4">
        <h6 class="text-uppercase text-muted mb-3"><i class="bi bi-check2-square me-2"></i>Condiciones del producto</h6>
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" class="form-control" name="content[condiciones_title]"
                   value="{{ old('content.condiciones_title', $page->content['condiciones_title'] ?? 'Condiciones del producto') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Contenido</label>
            <textarea class="form-control" name="content[condiciones_body]" rows="6">{{ old('content.condiciones_body', $page->content['condiciones_body'] ?? '') }}</textarea>
        </div>
    </div>

    <hr>

    {{-- Sección Proceso --}}
    <div class="editor-section mb-4">
        <h6 class="text-uppercase text-muted mb-3"><i class="bi bi-arrow-repeat me-2"></i>Proceso de devolución</h6>
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" class="form-control" name="content[proceso_title]"
                   value="{{ old('content.proceso_title', $page->content['proceso_title'] ?? 'Proceso de devolución') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Contenido (idealmente lista numerada)</label>
            <textarea class="form-control" name="content[proceso_body]" rows="6">{{ old('content.proceso_body', $page->content['proceso_body'] ?? '') }}</textarea>
        </div>
    </div>

    <hr>

    {{-- Sección Contacto --}}
    <div class="editor-section mb-4">
        <h6 class="text-uppercase text-muted mb-3"><i class="bi bi-headset me-2"></i>Contacto</h6>
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" class="form-control" name="content[contacto_title]"
                   value="{{ old('content.contacto_title', $page->content['contacto_title'] ?? '¿Necesitas ayuda?') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Texto introductorio</label>
            <textarea class="form-control" name="content[contacto_body]" rows="3">{{ old('content.contacto_body', $page->content['contacto_body'] ?? '') }}</textarea>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Email de contacto</label>
                <input type="email" class="form-control" name="content[contacto_email]"
                       value="{{ old('content.contacto_email', $page->content['contacto_email'] ?? '') }}"
                       placeholder="soporte@ejemplo.com">
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono / WhatsApp</label>
                <input type="text" class="form-control" name="content[contacto_telefono]"
                       value="{{ old('content.contacto_telefono', $page->content['contacto_telefono'] ?? '') }}"
                       placeholder="+57 300 000 0000">
                <small class="form-text text-muted">Se generará automáticamente un enlace de WhatsApp.</small>
            </div>
        </div>
    </div>

</div>
