<x-app-layout>
    <x-slot name="slot">
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"><i class="bi bi-people-fill me-2"></i>Destinatarios de notificaciones internas</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Agregar destinatario</div>
        <div class="card-body">
            <form action="{{ route('admin.notificaciones.destinatarios.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nombre (opcional)</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Eventos</label>
                    <select name="eventos[]" class="form-select" multiple size="2">
                        <option value="compra_aprobada" selected>Compra aprobada</option>
                    </select>
                    <small class="form-text text-muted">Sin selección = todos.</small>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="activo" id="activo" value="1" checked>
                        <label class="form-check-label" for="activo">Activo</label>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Destinatarios actuales</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr><th>Email</th><th>Nombre</th><th>Eventos</th><th>Estado</th><th></th></tr>
                </thead>
                <tbody>
                @forelse($destinatarios as $d)
                    <tr>
                        <form action="{{ route('admin.notificaciones.destinatarios.update', $d) }}" method="POST">
                            @csrf @method('PUT')
                            <td><input type="email" name="email" value="{{ $d->email }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="nombre" value="{{ $d->nombre }}" class="form-control form-control-sm"></td>
                            <td>
                                <select name="eventos[]" class="form-select form-select-sm" multiple size="1">
                                    <option value="compra_aprobada" {{ in_array('compra_aprobada', $d->eventos ?? []) ? 'selected' : '' }}>Compra aprobada</option>
                                </select>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="activo" value="1" {{ $d->activo ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-check-lg"></i></button>
                        </form>
                                <form action="{{ route('admin.notificaciones.destinatarios.destroy', $d) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este destinatario?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Aún no hay destinatarios. Agrega el primero arriba.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-muted small mt-3">
        Estos destinatarios reciben las notificaciones internas (ej. "nueva venta confirmada").
        El correo del dueño de la empresa también recibe la notificación automáticamente, no es necesario agregarlo aquí.
    </p>
</div>
    </x-slot>
</x-app-layout>
