<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Nueva Reserva</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">¡Atención!</strong>
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('client.appointments.store') }}">
                    @csrf

                    <div>
                        <x-input-label for="service_id" :value="__('¿Qué te hacemos?')" />
                        <select name="service_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm p-2">
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }} (${{ $service->price }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="barber_id" :value="__('Elige a tu Barbero')" />
                        <select name="barber_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm p-2">
                            @foreach($barbers as $barber)
                                <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="appointment_date" :value="__('¿Cuándo vienes?')" />
                        <input type="datetime-local" name="appointment_date" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm p-2" required min="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition">
                            Confirmar Reserva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
