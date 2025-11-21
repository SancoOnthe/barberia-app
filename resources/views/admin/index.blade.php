@extends('layouts.admin')

@section('title', 'Dashboard - Admin BarberShop')
@section('header-title', 'Panel Administrativo')

@section('content')
<div class="admin-content">
    <div class="dashboard-welcome mb-4 p-4 bg-light rounded-3 border-start border-4 border-warning">
        <h2>Bienvenido al Dashboard, <span class="text-primary">{{ Auth::user()->name }}</span></h2>
        <p class="mb-0 text-muted">Gestiona tu barbería desde este panel central.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4 col-lg-2"> <div class="stat-card bg-white p-3 rounded shadow-sm h-100 border-top border-4 border-primary">
                <i class="bi bi-person-badge fs-1 text-primary mb-2"></i>
                <h6 class="text-muted">Barberos</h6>
                <div class="fs-2 fw-bold">{{ $stats['barberos'] }}</div>
            </div>
        </div>
        
        <div class="col-md-4 col-lg-2">
            <div class="stat-card bg-white p-3 rounded shadow-sm h-100 border-top border-4 border-success">
                <i class="bi bi-scissors fs-1 text-success mb-2"></i>
                <h6 class="text-muted">Servicios</h6>
                <div class="fs-2 fw-bold">{{ $stats['servicios'] }}</div>
            </div>
        </div>
        
        <div class="col-md-4 col-lg-2">
            <div class="stat-card bg-white p-3 rounded shadow-sm h-100 border-top border-4 border-info">
                <i class="bi bi-people fs-1 text-info mb-2"></i>
                <h6 class="text-muted">Clientes</h6>
                <div class="fs-2 fw-bold">{{ $stats['clientes'] }}</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="stat-card bg-white p-3 rounded shadow-sm h-100 border-top border-4 border-warning">
                <i class="bi bi-calendar-check fs-1 text-warning mb-2"></i>
                <h6 class="text-muted">Citas Hoy</h6>
                <div class="fs-2 fw-bold">{{ $stats['citas_hoy'] }}</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="stat-card bg-white p-3 rounded shadow-sm h-100 border-top border-4 border-secondary">
                <i class="bi bi-calendar2-week fs-1 text-secondary mb-2"></i>
                <h6 class="text-muted">Próxima Semana</h6>
                <div class="fs-2 fw-bold">{{ $stats['proxima_semana'] }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-bar-chart-line"></i> Analítica de Citas (7 Días)</h5>
                </div>
                <div class="card-body">
                    <canvas id="citasUltimos7DiasChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Próximas Citas</h5>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($upcomingAppointments as $cita)
                            <div class="list-group-item p-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold">{{ $cita->client->name ?? 'Cliente General' }}</h6>
                                    <small class="text-primary fw-bold">{{ \Carbon\Carbon::parse($cita->appointment_date)->format('H:i') }}</small>
                                </div>
                                <p class="mb-1 text-muted small">
                                    {{ $cita->service->name ?? 'Servicio' }} con {{ $cita->barber->name ?? 'Barbero' }}
                                </p>
                                <small class="text-secondary">
                                    <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($cita->appointment_date)->format('d/m/Y') }}
                                </small>
                            </div>
                        @empty
                            <div class="text-center p-4 text-muted">
                                <i class="bi bi-calendar-x fs-1"></i>
                                <p class="mt-2">No hay citas próximas programadas.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Distribución de Estados</h5>
                </div>
                <div class="card-body">
                    <div style="max-width: 300px; margin: 0 auto;">
                        <canvas id="estadoCitasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Pasamos los datos de PHP a JS de forma limpia
        // Al usar @json una sola vez, evitamos los errores de sintaxis en el editor
        const chartData = {
            labels: @json($chartLabels),
            data: @json($chartData),
            status: @json($statusCounts)
        };

        // 2. Gráfico de Barras (Últimos 7 días)
        const ctxLine = document.getElementById('citasUltimos7DiasChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Citas por día',
                    data: chartData.data,
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    borderColor: '#ffc107',
                    borderWidth: 2,
                    borderRadius: 5,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });

        // 3. Gráfico de Torta (Estados)
        const ctxPie = document.getElementById('estadoCitasChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Programada', 'Confirmada', 'Completada', 'Cancelada'],
                datasets: [{
                    data: [
                        chartData.status.programada || 0, 
                        chartData.status.confirmada || 0, 
                        chartData.status.completada || 0, 
                        chartData.status.cancelada || 0
                    ],
                    backgroundColor: [
                        '#0dcaf0', // Info (Programada)
                        '#0d6efd', // Primary (Confirmada)
                        '#198754', // Success (Completada)
                        '#dc3545'  // Danger (Cancelada)
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
@endpush
