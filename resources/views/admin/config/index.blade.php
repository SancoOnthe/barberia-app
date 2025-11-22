@extends('layouts.admin')
@section('title', 'Configuración del Sistema')
@section('header-title', 'Configuración')

@section('content')
<div class="admin-content">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-shop"></i> Datos del Negocio</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.config.update') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nombre del Negocio</label>
                                <input type="text" name="nombre_negocio" class="form-control" value="{{ $settings['nombre_negocio'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email de Contacto</label>
                                <input type="email" name="email_contacto" class="form-control" value="{{ $settings['email_contacto'] ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="direccion" class="form-control" value="{{ $settings['direccion'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" value="{{ $settings['telefono'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Moneda (Símbolo)</label>
                                <input type="text" name="moneda" class="form-control" placeholder="$" value="{{ $settings['moneda'] ?? '$' }}">
                            </div>
                        </div>

                        <hr class="text-muted">

                        <h6 class="mb-3 text-primary"><i class="bi bi-share"></i> Redes Sociales</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-facebook"></i> Facebook</label>
                                <input type="text" name="facebook" class="form-control" placeholder="URL..." value="{{ $settings['facebook'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-instagram"></i> Instagram</label>
                                <input type="text" name="instagram" class="form-control" placeholder="@usuario" value="{{ $settings['instagram'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-whatsapp"></i> WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control" placeholder="+57..." value="{{ $settings['whatsapp'] ?? '' }}">
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-activity"></i> Estado del Sistema</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Base de Datos
                            <span class="badge bg-{{ $dbStats['status_color'] }}">{{ $dbStats['status'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Usuarios Registrados
                            <span class="badge bg-secondary rounded-pill">{{ $dbStats['usuarios'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Servicios Activos
                            <span class="badge bg-secondary rounded-pill">{{ $dbStats['servicios'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Citas
                            <span class="badge bg-secondary rounded-pill">{{ $dbStats['citas'] }}</span>
                        </li>
                    </ul>
                    <div class="mt-3 text-center text-muted small">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-start border-4 border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">💡 Tip</h6>
                    <p class="card-text small text-muted">
                        Los datos de contacto que configures aquí aparecerán automáticamente en el pie de página de la web pública y en los correos electrónicos.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
