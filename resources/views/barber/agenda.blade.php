<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mi Agenda de Hoy
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if($appointments->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center">No tienes citas pendientes para hoy.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($appointments as $appointment)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700 flex flex-col h-full justify-between">
    
    <div>
                <div class="flex justify-between items-center mb-3">
            <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}
            </span>
            
            @php
                $colors = [
                    'pending' => 'bg-yellow-200 text-yellow-800',
                    'confirmed' => 'bg-blue-200 text-blue-800',
                    'completed' => 'bg-green-200 text-green-800',
                    'cancelled' => 'bg-red-200 text-red-800',
                ];
                $statusColor = $colors[$appointment->status] ?? 'bg-gray-200 text-gray-800';
            @endphp
            
            <span class="px-2 py-1 text-xs font-bold rounded {{ $statusColor }}">
                {{ ucfirst($appointment->status) }}
            </span>
        </div>

        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ $appointment->client->name ?? 'Cliente General' }}
        </h3>
        
        <p class="text-gray-600 dark:text-gray-300 mt-1">
            ✂️ {{ $appointment->service->name }} ({{ $appointment->service->duration_min }} min)
        </p>

        @if($appointment->notes)
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 italic bg-white dark:bg-gray-800 p-2 rounded">
                "{{ $appointment->notes }}"
            </p>
        @endif
    </div>

    @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
    <div class="mt-4 grid grid-cols-2 gap-2 pt-4 border-t border-gray-200 dark:border-gray-600">
        
        <form method="POST" action="{{ route('barber.appointments.update', $appointment->id) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="completed">
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-2 rounded transition">
                ✅ Completar
            </button>
        </form>

        <form method="POST" action="{{ route('barber.appointments.update', $appointment->id) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-2 rounded transition" onclick="return confirm('¿Seguro que quieres cancelar esta cita?');">
                ❌ Cancelar
            </button>
        </form>
    </div>
    @endif

</div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
