<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Agenda General</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-gray-100 text-lg">Citas Programadas</h3>
                    <a href="{{ route('admin.appointments.create') }}" class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-700">Nueva Cita</a>
                </div>

                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Barbero</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Servicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach ($appointments as $cita)
                        <tr>
                            <td class="px-6 py-4 text-gray-100">
                                {{ \Carbon\Carbon::parse($cita->appointment_date)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-gray-100">{{ $cita->barber->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $cita->client->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-100">
                                {{ $cita->service->name ?? 'N/A' }} <br>
                                <span class="text-xs text-gray-400">Duración: {{ $cita->service->duration_min ?? 0 }} min</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $cita->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
