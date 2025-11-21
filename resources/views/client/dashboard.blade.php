<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mis Citas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-end">
                <a href="{{ route('client.appointments.create') }}" class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 px-6 rounded-lg shadow-lg transform transition hover:scale-105">
                    📅 Agendar Nueva Cita
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($appointments->isEmpty())
                    <div class="text-center py-10">
                        <p class="text-gray-500 text-lg">Aún no has visitado nuestra barbería.</p>
                        <p class="text-gray-400">¡Reserva tu primera cita hoy mismo!</p>
                    </div>
                @else
                    <div class="grid gap-6 lg:grid-cols-2">
                        @foreach($appointments as $cita)
                            <div class="border-l-4 {{ $cita->status == 'confirmed' ? 'border-green-500' : ($cita->status == 'pending' ? 'border-yellow-500' : 'border-red-500') }} bg-gray-50 dark:bg-gray-700 rounded p-4 shadow">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($cita->appointment_date)->format('d M Y') }}</p>
                                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                            {{ \Carbon\Carbon::parse($cita->appointment_date)->format('h:i A') }}
                                        </h3>
                                        <p class="mt-2 font-semibold text-indigo-600 dark:text-indigo-400">{{ $cita->service->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">Barbero: {{ $cita->barber->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="flex flex-col items-end justify-between">
                                        <span class="px-2 py-1 text-xs font-bold rounded 
                                            {{ $cita->status == 'confirmed' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                            {{ ucfirst($cita->status) }}
                                        </span>
                                        <span class="text-lg font-bold text-gray-700 dark:text-gray-200">${{ $cita->service->price }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
