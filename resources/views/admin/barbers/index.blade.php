@extends('layouts.admin')
@section('title', 'Gestión de Barberos')
@section('header-title', 'Gestión de Barberos')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-badge"></i> Lista de Barberos</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#barberModal" onclick="resetForm()">
            <i class="bi bi-plus-circle"></i> Nuevo Barbero
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Atención!</strong> Revisa los siguientes errores:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table admin-table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Especialidad</th>
                    <th>Exp. (Años)</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barbers as $barber)
                <tr>
                    <td class="fw-bold">{{ $barber->name }}</td>
                    <td>{{ $barber->email }}</td>
                    <td>{{ $barber->phone ?? 'N/A' }}</td>
                    <td>{{ $barber->specialty ?? 'General' }}</td>
                    <td>{{ $barber->experience ?? '0' }}</td>
                    <td>
                        @if($barber->activo)
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Activo</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-slash-circle"></i> Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning text-white btn-edit" 
                            data-bs-toggle="modal" data-bs-target="#barberModal"
                            data-id="{{ $barber->id }}"
                            data-name="{{ $barber->name }}"
                            data-cedula="{{ $barber->cedula ?? '' }}"
                            data-email="{{ $barber->email }}"
                            data-phone="{{ $barber->phone ?? '' }}"
                            data-specialty="{{ $barber->specialty ?? '' }}"
                            data-experience="{{ $barber->experience ?? '' }}"
                            data-bio="{{ $barber->bio ?? '' }}"
                            data-activo="{{ $barber->activo }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <form action="{{ route('admin.barbers.destroy', $barber->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar a este barbero?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="bi bi-people fs-1"></i>
                        <p>No hay barberos registrados.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="barberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="barberForm" action="{{ route('admin.barbers.store') }}" method="POST" class="modal-content">
            @csrf
            <div id="methodField"></div>

            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalTitle">Nuevo Barbero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Completo *</label>
                        <input type="text" name="name" id="inputName" class="form-control" required placeholder="Ej: Juan Pérez">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Cédula *</label>
                        <input type="text" name="cedula" id="inputCedula" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" id="inputEmail" class="form-control" required placeholder="juan@barberia.com">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Teléfono *</label>
                        <input type="text" name="phone" id="inputPhone" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contraseña *</label>
                        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Mínimo 8 caracteres">
                        <small class="text-muted d-none" id="passwordHint">Dejar en blanco para mantener la actual.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Especialidad</label>
                        <input type="text" name="specialty" id="inputSpecialty" class="form-control" placeholder="Ej: Cortes Modernos, Barba">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Años de Experiencia</label>
                        <input type="number" name="experience" id="inputExperience" class="form-control" min="0">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Biografía</label>
                        <textarea name="bio" id="inputBio" class="form-control" rows="3" placeholder="Breve descripción..."></textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="activo" id="inputActivo" checked>
                            <label class="form-check-label" for="inputActivo">Barbero Activo (Puede recibir citas)</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Referencias al DOM
    const form = document.getElementById('barberForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');
    const passwordInput = document.getElementById('inputPassword');
    const passwordHint = document.getElementById('passwordHint');

    // Función Reset (Modo Crear)
    window.resetForm = function() {
        form.action = "{{ route('admin.barbers.store') }}";
        methodField.innerHTML = ''; // Limpiar método PUT
        form.reset();
        modalTitle.textContent = "Nuevo Barbero";
        
        // En modo crear, la contraseña es obligatoria
        passwordInput.setAttribute('required', 'required');
        passwordHint.classList.add('d-none');
        
        document.getElementById('inputActivo').checked = true;
    }

    // Función Cargar Datos (Modo Editar)
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            
            // Configurar el formulario para Editar
            form.action = `/admin/barberos/${id}`;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            modalTitle.textContent = "Editar Barbero";

            // La contraseña es opcional al editar
            passwordInput.removeAttribute('required');
            passwordHint.classList.remove('d-none');

            // Rellenar campos
            document.getElementById('inputName').value = this.dataset.name;
            document.getElementById('inputCedula').value = this.dataset.cedula;
            document.getElementById('inputEmail').value = this.dataset.email;
            document.getElementById('inputPhone').value = this.dataset.phone;
            document.getElementById('inputSpecialty').value = this.dataset.specialty;
            document.getElementById('inputExperience').value = this.dataset.experience;
            document.getElementById('inputBio').value = this.dataset.bio;
            
            // Checkbox
            document.getElementById('inputActivo').checked = (this.dataset.activo == "1");
        });
    });
</script>
@endpush
