@extends('layouts.admin')
@section('title', 'Gestión de Horarios')
@section('header-title', 'Gestión de Horarios')

@section('content')
<div class="admin-content">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-top border-4 border-primary">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-plus"></i> Generador de Disponibilidad</h5>
                    <p class="text-muted small mb-0">Define los días y horas de trabajo para tus barberos.</p>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.schedules.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">Barbero *</label>
                            <select name="barber_id" class="form-select form-select-lg" required>
                                <option value="" selected disabled>Selecciona un barbero...</option>
                                @foreach($barbers as $barber)
                                    <option value="{{ $barber->id }}">{{ $barber->name }} ({{ $barber->cedula ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha Inicio *</label>
                                <input type="date" name="date_start" class="form-control" required min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha Fin *</label>
                                <input type="date" name="date_end" class="form-control" required min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold d-block mb-2">Días Laborales *</label>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                    $days = [
                                        1 => 'Lunes', 
                                        2 => 'Martes', 
                                        3 => 'Miércoles', 
                                        4 => 'Jueves', 
                                        5 => 'Viernes', 
                                        6 => 'Sábado', 
                                        0 => 'Domingo'
                                    ];
                                @endphp
                                
                                @foreach($days as $val => $label)
                                    <div class="form-check-inline">
                                        <input type="checkbox" class="btn-check" name="days[]" id="day{{ $val }}" value="{{ $val }}" {{ $val >= 1 && $val <= 5 ? 'checked' : '' }} autocomplete="off">
                                        <label class="btn btn-outline-secondary" for="day{{ $val }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-text">Selecciona los días que se aplicarán en el rango de fechas.</div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hora Entrada *</label>
                                <input type="time" name="start_time" class="form-control" value="09:00" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hora Salida *</label>
                                <input type="time" name="end_time" class="form-control" value="18:00" required>
                            </div>
                        </div>

                        <div class="alert alert-info d-flex align-items-center">
                            <i class="bi bi-info-circle-fill fs-4 me-2"></i>
                            <div>
                                Al hacer clic en Generar, se crearán los registros de disponibilidad en la base de datos. Si ya existen horarios para esas fechas, se actualizarán.
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg text-uppercase fw-bold py-3">
                                Generar Horarios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
