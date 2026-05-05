<x-app-layout>
    <x-slot name="slot">
<div class="container-fluid">
    <a href="{{ route('admin.notificaciones.plantillas.index') }}" class="text-muted small">&larr; Volver al listado</a>
    <h3 class="my-3"><i class="bi bi-envelope-paper me-2"></i>{{ $plantilla->name }}</h3>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    <form action="{{ route('admin.notificaciones.plantillas.update', $plantilla) }}" method="POST">
        @csrf @method('PUT')

        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Asunto del correo</label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject', $plantilla->subject) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cuerpo (HTML)</label>
                    <textarea name="body" class="form-control" rows="14" style="font-family: monospace; font-size: 0.85rem;">{{ old('body', $plantilla->body) }}</textarea>
                </div>

                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $plantilla->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Plantilla activa</label>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Variables disponibles</div>
            <div class="card-body">
                <p class="small text-muted mb-2">Puedes usar estas variables en el asunto y en el cuerpo. Se reemplazan automáticamente al enviar el correo.</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(($plantilla->available_variables ?? []) as $var)
                        <code class="px-2 py-1 bg-light border rounded" style="cursor: copy;" onclick="navigator.clipboard.writeText('{' + this.dataset.var + '}');" data-var="{{ $var }}">
                            &#123;{{ $var }}&#125;
                        </code>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Guardar
        </button>
    </form>
</div>
    </x-slot>
</x-app-layout>
