<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Agendar Cita</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('admin.appointments.store') }}">
                    @csrf

                    <div class="mt-4">
                        <x-input-label for="barber_id" :value="__('Barbero')" />
                        <select name="barber_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach($barbers as $barber)
                                <option value="{{ $barber->id }}">{{ $barber->name }} ({{ $barber->specialty ?? 'General' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="client_id" :value="__('Cliente')" />
                        <select name="client_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="{{ Auth::id() }}">Yo (Admin)</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="service_id" :value="__('Servicio')" />
                        <select name="service_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }} - ${{ $service->price }} ({{ $service->duration_min }} min)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="appointment_date" :value="__('Fecha y Hora')" />
                        <x-text-input id="appointment_date" class="block mt-1 w-full" type="datetime-local" name="appointment_date" required />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">Agendar Cita</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
