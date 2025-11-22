@extends('layouts.admin')
@section('title', 'Gestión de Clientes')
@section('header-title', 'Gestión de Clientes')

@section('content')
<div class="admin-content">
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-3">
        <h2 class="text-lg font-semibold"><i class="bi bi-people text-barber-gold"></i> Lista de Clientes</h2>

        <div class="flex gap-2 w-full md:w-auto items-center">
            <div class="flex items-center bg-white rounded-md border border-gray-200 px-2">
                <i class="bi bi-search text-gray-400 mr-2"></i>
                <input type="text" id="searchInput" class="form-field bg-transparent border-0 p-0" placeholder="Buscar cliente...">
            </div>

            <button type="button" class="btn-barber text-nowrap" data-bs-toggle="modal" data-bs-target="#clientModal" onclick="resetForm()">
                <i class="bi bi-person-plus mr-2"></i> Nuevo
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-50 border border-green-100 text-green-800 flex items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="ml-auto" data-bs-dismiss="alert">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 admin-table" id="clientsTable">
            <thead class="bg-gray-50">
                <tr class="text-left text-sm font-medium text-gray-700">
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Teléfono</th>
                    <th class="px-4 py-2">Notas / Preferencias</th>
                    <th class="px-4 py-2">Estado</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($clients as $client)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold">{{ $client->name }}</td>
                    <td class="px-4 py-3">{{ $client->email }}</td>
                    <td class="px-4 py-3">{{ $client->phone ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ Str::limit($client->notes ?? '-', 40) }}</td>
                    <td class="px-4 py-3">
                        @if($client->activo)
                            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Activo</span>
                        @else
                            <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 space-x-2">
                        <button class="btn-barber-outline btn-edit" 
                            data-bs-toggle="modal" data-bs-target="#clientModal"
                            data-id="{{ $client->id }}"
                            data-name="{{ $client->name }}"
                            data-email="{{ $client->email }}"
                            data-phone="{{ $client->phone }}"
                            data-notes="{{ $client->notes }}"
                            data-activo="{{ $client->activo }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar cliente?');">
                            @csrf @method('DELETE')
                            <button class="btn-barber-outline bg-red-600 border-red-600 text-white hover:bg-red-700"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr id="no-results">
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="bi bi-people fs-1"></i>
                        <p>No hay clientes registrados.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="clientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="clientForm" action="{{ route('admin.clients.store') }}" method="POST" class="modal-content">
            @csrf
            <div id="methodField"></div>

            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalTitle">Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text" name="name" id="inputName" class="form-control" required>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" id="inputEmail" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono *</label>
                        <input type="text" name="phone" id="inputPhone" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Mínimo 8 caracteres">
                    <small class="text-muted d-none" id="passwordHint">Dejar vacío para mantener la actual.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notas / Preferencias</label>
                    <textarea name="notes" id="inputNotes" class="form-control" rows="2" placeholder="Ej: Prefiere tijera, Alergia al talco..."></textarea>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="activo" id="inputActivo" checked>
                    <label class="form-check-label" for="inputActivo">Cliente Activo</label>
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
    // --- Lógica del Modal (Crear/Editar) ---
    const form = document.getElementById('clientForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');
    const passwordInput = document.getElementById('inputPassword');
    const passwordHint = document.getElementById('passwordHint');

    // Inputs
    const inputName = document.getElementById('inputName');
    const inputEmail = document.getElementById('inputEmail');
    const inputPhone = document.getElementById('inputPhone');
    const inputNotes = document.getElementById('inputNotes');
    const inputActivo = document.getElementById('inputActivo');

    window.resetForm = function() {
        form.action = "{{ route('admin.clients.store') }}";
        methodField.innerHTML = '';
        form.reset();
        modalTitle.textContent = "Nuevo Cliente";
        passwordInput.setAttribute('required', 'required');
        passwordHint.classList.add('d-none');
        inputActivo.checked = true;
    }

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            form.action = `/admin/clientes/${id}`;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            modalTitle.textContent = "Editar Cliente";
            
            passwordInput.removeAttribute('required');
            passwordHint.classList.remove('d-none');

            inputName.value = this.dataset.name;
            inputEmail.value = this.dataset.email;
            inputPhone.value = this.dataset.phone;
            inputNotes.value = this.dataset.notes;
            inputActivo.checked = (this.dataset.activo == "1");
        });
    });

    // --- Lógica de Búsqueda en Vivo ---
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('#clientsTable tbody tr:not(#no-results)');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(value)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
