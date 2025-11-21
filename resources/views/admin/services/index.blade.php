@extends('layouts.admin')
@section('title', 'Gestión de Servicios')
@section('header-title', 'Gestión de Servicios')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-scissors"></i> Lista de Servicios</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#serviceModal" onclick="resetForm()">
            <i class="bi bi-plus-circle"></i> Nuevo Servicio
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table admin-table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Duración</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                <tr>
                    <td>
                        @if($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                                <i class="bi bi-camera-video-off"></i>
                            </div>
                        @endif
                    </td>
                    <td class="fw-bold">{{ $service->name }}</td>
                    <td class="text-muted small">{{ Str::limit($service->description, 50) }}</td>
                    <td><i class="bi bi-clock"></i> {{ $service->duration_min }} min</td>
                    <td class="fw-bold text-success">${{ number_format($service->price, 2) }}</td>
                    <td>
                        @if($service->activo)
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Activo</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-slash-circle"></i> Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning text-white btn-edit" 
                            data-bs-toggle="modal" data-bs-target="#serviceModal"
                            data-id="{{ $service->id }}"
                            data-name="{{ $service->name }}"
                            data-description="{{ $service->description }}"
                            data-duration="{{ $service->duration_min }}"
                            data-price="{{ $service->price }}"
                            data-activo="{{ $service->activo }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este servicio permanentemente?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p>No hay servicios registrados.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="serviceForm" action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div id="methodField"></div> 

            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalTitle">Nuevo Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre del Servicio *</label>
                    <input type="text" name="name" id="inputName" class="form-control" required placeholder="Ej: Corte Clásico">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" id="inputDesc" class="form-control" rows="2" placeholder="Detalles del servicio..."></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Duración (min) *</label>
                        <input type="number" name="duration_min" id="inputDuration" class="form-control" required min="5" value="30">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Precio ($) *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="price" id="inputPrice" class="form-control" required min="0" step="0.01" placeholder="0.00">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen (Opcional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Deja vacío para mantener la actual (al editar).</small>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="activo" id="inputActivo" checked>
                    <label class="form-check-label" for="inputActivo">Servicio Activo (Visible para clientes)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Servicio</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Lógica para reutilizar el Modal (Crear vs Editar)
    const modalElement = document.getElementById('serviceModal');
    const form = document.getElementById('serviceForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    // Inputs
    const inputName = document.getElementById('inputName');
    const inputDesc = document.getElementById('inputDesc');
    const inputDuration = document.getElementById('inputDuration');
    const inputPrice = document.getElementById('inputPrice');
    const inputActivo = document.getElementById('inputActivo');

    // Resetear form al abrir para "Nuevo"
    function resetForm() {
        form.action = "{{ route('admin.services.store') }}";
        methodField.innerHTML = ''; // Quitar @method('PUT')
        form.reset();
        modalTitle.textContent = "Nuevo Servicio";
        inputActivo.checked = true;
    }

    // Rellenar datos al hacer clic en "Editar"
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            
            // Cambiar URL del action y Título
            form.action = `/admin/services/${id}`;
            modalTitle.textContent = "Editar Servicio";
            
            // Inyectar directiva PUT oculta
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // Rellenar campos
            inputName.value = this.dataset.name;
            inputDesc.value = this.dataset.description;
            inputDuration.value = this.dataset.duration;
            inputPrice.value = this.dataset.price;
            inputActivo.checked = (this.dataset.activo == "1" || this.dataset.activo == "true");
        });
    });
</script>
@endpush