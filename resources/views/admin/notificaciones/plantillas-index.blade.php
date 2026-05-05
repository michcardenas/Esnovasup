<x-app-layout>
    <x-slot name="slot">
<div class="container-fluid">
    <h3 class="mb-3"><i class="bi bi-envelope-paper me-2"></i>Plantillas de correo</h3>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead><tr><th>Nombre</th><th>Asunto</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                @foreach($plantillas as $p)
                    <tr>
                        <td>
                            <strong>{{ $p->name }}</strong>
                            @if($p->description)<br><small class="text-muted">{{ $p->description }}</small>@endif
                        </td>
                        <td>{{ $p->subject }}</td>
                        <td>
                            @if($p->is_active)
                                <span class="badge bg-success">Activa</span>
                            @else
                                <span class="badge bg-secondary">Inactiva</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.notificaciones.plantillas.edit', $p) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-muted small mt-3">
        Si una plantilla está marcada como <em>Inactiva</em>, el sistema usa el diseño por defecto (blade) en su lugar.
    </p>
</div>
    </x-slot>
</x-app-layout>
