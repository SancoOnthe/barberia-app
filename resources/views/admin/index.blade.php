@extends('layouts.admin')

@section('title', 'Dashboard - Admin BarberShop')
@section('header-title', 'Panel Administrativo')

@section('content')
<div class="admin-content">
    <div class="mb-8 bg-white p-6 rounded-lg shadow-sm border-l-4 border-barber-gold flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Bienvenido, <span class="text-barber-gold">{{ Auth::user()->name }}</span></h2>
            <p class="text-gray-500 mt-1">Aquí tienes el resumen de actividad de tu barbería.</p>
        </div>
        <div class="hidden md:block text-4xl text-barber-gold opacity-20">
            <i class="bi bi-scissors"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="stat-card group hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-500 uppercase text-xs font-bold tracking-wider">Barberos</h6>
                    <div class="text-3xl font-bold text-gray-800 mt-2 group-hover:text-barber-gold transition-colors">{{ $stats['barberos'] }}</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-full text-barber-gold group-hover:bg-barber-gold group-hover:text-white transition-colors">
                    <i class="bi bi-person-badge text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card group hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-500 uppercase text-xs font-bold tracking-wider">Servicios</h6>
                    <div class="text-3xl font-bold text-gray-800 mt-2 group-hover:text-barber-gold transition-colors">{{ $stats['servicios'] }}</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-full text-barber-gold group-hover:bg-barber-gold group-hover:text-white transition-colors">
                    <i class="bi bi-scissors text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card group hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-500 uppercase text-xs font-bold tracking-wider">Clientes</h6>
                    <div class="text-3xl font-bold text-gray-800 mt-2 group-hover:text-barber-gold transition-colors">{{ $stats['clientes'] }}</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-full text-barber-gold group-hover:bg-barber-gold group-hover:text-white transition-colors">
                    <i class="bi bi-people text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card group hover:-translate-y-1 transition-transform duration-300 border-barber-gold">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-500 uppercase text-xs font-bold tracking-wider">Citas Hoy</h6>
                    <div class="text-3xl font-bold text-gray-800 mt-2 group-hover:text-barber-gold transition-colors">{{ $stats['citas_hoy'] }}</div>
                </div>
                <div class="p-3 bg-yellow-50 rounded-full text-barber-gold group-hover:bg-barber-gold group-hover:text-white transition-colors">
                    <i class="bi bi-calendar-check text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card group hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-500 uppercase text-xs font-bold tracking-wider">Semana</h6>
                    <div class="text-3xl font-bold text-gray-800 mt-2 group-hover:text-barber-gold transition-colors">{{ $stats['proxima_semana'] }}</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-full text-barber-gold group-hover:bg-barber-gold group-hover:text-white transition-colors">
                    <i class="bi bi-calendar2-week text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
            <h5 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 border-gray-100">
                <i class="bi bi-bar-chart-line text-barber-gold mr-2"></i> Actividad (7 Días)
            </h5>
            <div id="chart-citas-semanales" style="min-height: 300px;"></div>
        </div>

        <div class="space-y-8">
            
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex justify-between items-center mb-4 border-b pb-2 border-gray-100">
                    <h5 class="text-lg font-bold text-gray-800">
                        <i class="bi bi-clock-history text-barber-gold mr-2"></i> Próximas
                    </h5>
                    <a href="{{ route('admin.appointments.index') }}" class="text-xs font-bold text-barber-gold hover:text-black uppercase tracking-wide transition-colors">Ver todas</a>
                </div>
                
                <div class="space-y-4">
                    @forelse($upcomingAppointments as $cita)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border-l-2 border-barber-gold hover:bg-gray-100 transition-colors">
                            <div class="flex-1">
                                <h6 class="font-bold text-gray-900 text-sm">{{ $cita->client->name ?? 'Cliente' }}</h6>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $cita->service->name ?? 'Servicio' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="block text-lg font-bold text-barber-gold">{{ \Carbon\Carbon::parse($cita->appointment_date)->format('H:i') }}</span>
                                <span class="block text-xs text-gray-400">{{ \Carbon\Carbon::parse($cita->appointment_date)->format('d/m') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400">
                            <i class="bi bi-calendar-x text-4xl mb-2 block opacity-30"></i>
                            <p class="text-sm">Sin citas próximas.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h5 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 border-gray-100">
                    <i class="bi bi-pie-chart text-barber-gold mr-2"></i> Estados
                </h5>
                <div id="chart-estados" class="flex justify-center"></div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // DATOS DE PHP (Sin errores de sintaxis)
        const labelsData = {!! json_encode($chartLabels) !!};
        const seriesData = {!! json_encode($chartData) !!};
        const statusCounts = {!! json_encode($statusCounts) !!};

        // Configuración Común de Colores
        const barberGold = '#d4af37';
        const barberDark = '#1a1a1a';

        // 1. GRÁFICO DE BARRAS
        var optionsBar = {
            series: [{
                name: 'Citas',
                data: seriesData
            }],
            chart: {
                type: 'bar',
                height: 320,
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: false,
                    columnWidth: '40%',
                }
            },
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: {
                categories: labelsData,
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            grid: {
                strokeDashArray: 4,
                borderColor: '#f0f0f0'
            },
            fill: { opacity: 1 },
            colors: [barberGold], // Usamos el dorado
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) { return val + " citas" }
                }
            }
        };

        var chartBar = new ApexCharts(document.querySelector("#chart-citas-semanales"), optionsBar);
        chartBar.render();

        // 2. GRÁFICO DE DONA
        var optionsDonut = {
            series: [
                statusCounts.programada || 0, 
                statusCounts.confirmada || 0, 
                statusCounts.completada || 0, 
                statusCounts.cancelada || 0
            ],
            chart: {
                type: 'donut',
                height: 280,
                fontFamily: 'inherit'
            },
            labels: ['Programada', 'Confirmada', 'Completada', 'Cancelada'],
            colors: ['#3b82f6', '#1d4ed8', '#10b981', '#ef4444'], // Azul, Azul Oscuro, Verde, Rojo
            legend: {
                position: 'bottom',
                horizontalAlign: 'center' 
            },
            dataLabels: { enabled: false },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: { show: false },
                            value: {
                                show: true,
                                fontSize: '24px',
                                fontWeight: 'bold',
                                color: barberDark,
                                offsetY: 8
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Total',
                                fontSize: '12px',
                                color: '#9ca3af',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                }
                            }
                        }
                    }
                }
            }
        };

        var chartDonut = new ApexCharts(document.querySelector("#chart-estados"), optionsDonut);
        chartDonut.render();
    });
</script>
@endpush